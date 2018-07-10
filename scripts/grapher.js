// tunable basis parameters for the grapher behavior
const res = 1.5; // how close adjacent points on curve should be (pixels)
const tickRatio = 0.006; // fraction of screen for tick mark length
const gridRatio = 0.04; // fraction of screen of smallest possible grid length
const scaleRate = 0.015625; // rate at which the scale will change to zoom in and out
const scaleRange = {min: -2, max: 2};

// class acts as a wrapper around html canvas and provides methods to draw and update the graph
class GraphzappGrapher {
    constructor(canvasObj, origin, scale, options) {
        this.canvas = canvasObj;
        this.origin = origin;
        this.scale = scale;
        this.computeCalibration(this.canvas);
        this.computeTickLen(this.canvas);
        this.computeSF(this.calibration, this.scale);
        this.computeUnit(this.scale);
        this.computeGrid(this.sf, this.unit);
        this.computeGridLocations(this.canvas, this.grid, this.origin);
        this.computeDeltas(this.sf);

        // equation and slider
        this.eq = null;
        this.slider = null;

        // colors and options
        this.showAxes = options.axes;
        this.showGrids = options.grid;
        this.showLabels = options.numbers;
        this.curveColor = options.curveColor;
        this.backgroundColor = options.backgroundColor;
        this.axesColor = options.axesColor;
        this.gridColor = options.gridColor;
    }

    computeCalibration(canvas) {
        var getCalibration = function(size) {
            return ( 1 / (gridRatio * size) );
        };

        this.calibration = {
            x: getCalibration(canvas.width),
            y: getCalibration(canvas.height)
        };
    }

    computeTickLen(canvas) {
        var getTickLen = function(size) {
            return ( tickRatio * size );
        };

        this.tickLen = {
            x: getTickLen(canvas.width), 
            y: getTickLen(canvas.height)
        };
    }

    computeSF(calibration, scale) {
        var getScaleFactor = function(calibration, scale) {
            return calibration * Math.pow(10, scale);
        };

        this.sf = {
            x: getScaleFactor(calibration.x, scale.x),
            y: getScaleFactor(calibration.y, scale.y)
        };
    }

    computeUnit(scale) {
        var getUnit = function(scale) {
            var order = Math.floor(scale);
            var mag = scale - order;
            if (mag == 0) {return Math.pow(10,order);}
            if (mag < Math.log10(2)) {return 2*Math.pow(10,order);}
            if (mag < Math.log10(5)) {return 5*Math.pow(10,order);}
            else {return 10*Math.pow(10,order);}
        };

        this.unit = {
            x: getUnit(scale.x),
            y: getUnit(scale.y)
        };
    }

    computeGrid(sf, unit) {
        var getGrid = function(sf, unit) {
            return ( unit/sf );
        }

        this.grid = {
            x: getGrid(sf.x, unit.x),
            y: getGrid(sf.y, unit.y)
        };
    }

    computeGridLocations(canvas, grid, origin) {
        var getGridLocation = function(origin, grid, range) {
            var ret = {
                begin: Math.ceil(-origin/grid),
                end: Math.floor((range-origin)/grid)
            }; 

            return ret;
        };

        this.gridLocations = {
            hor: getGridLocation(origin.x, grid.x, canvas.width),
            ver: getGridLocation(origin.y, grid.y, canvas.height)
        };
    }

    computeDeltas(sf) {
        var getDelta = function(sf) {
            return ( res * sf );
        };

        var dx = getDelta(sf.x);
        var dy = getDelta(sf.y);
        var dt = Math.min(dx, dy);
        this.delta = {
            x: dx,
            y: dy,
            t: dt
        };
    }
    
    // should add the equation to a list -- for now just sets a variable
    addEquation(eqn) {
        this.eqn = eqn;
    }

    // should add the slider to a list -- for now just sets a variable
    addSlider(slider) {
        this.slider = slider;
    }

    // gets the slider variable -- in the future should take an index and return correct slider from a list
    getSlider() {
        return this.slider;
    }

    // paint everything on the canvas
    paint() {
        // this.showAxes = axes
        // this.showGrids = grid
        // this.showLabels = numbers
        // this.curveColor = curveColor
        // this.backgroundColor = backgroundColor
        // this.axesColor = axesColor
        // this.gridColor = gridColor

        var ctx = this.canvas.getContext("2d");
        ctx.fillStyle = this.backgroundColor;
        ctx.fillRect(0,0,this.canvas.width,this.canvas.height);
        this.draw(ctx);
    }

    // this is called to draw all the graph elements
    draw(ctx) {
        if (this.showGrids) {
            this.drawGrids(ctx);
            if (this.showAxes) {
                this.drawAxes(ctx);
                this.drawTickMarks(ctx);
                if (this.showLabels) {
                    this.drawLabels(ctx);
                }
            }
        }
        else if (this.showAxes) {
            this.drawAxes(ctx);
            if (this.showLabels) {
                this.drawTickMarks(ctx);
                this.drawLabels(ctx);
            }
        }

        this.drawPlot(ctx, this.eqn);
    }

    drawGrids(ctx) {
        var grids = this.gridLocations;
        var originX = this.origin.x;
        var originY = this.origin.y;
        var gridX = this.grid.x;
        var gridY = this.grid.y;
        var width = this.canvas.width;
        var height = this.canvas.height;

        ctx.save();

        ctx.translate(originX, originY);

        ctx.strokeStyle = this.gridColor;
        ctx.lineWidth = 1;

        // horizontal grids
        var begin = grids.hor.begin * gridX;
        var end = grids.hor.end * gridX;
        var upper = -originY;
        var lower = height-originY;
        for (var cur = begin; cur <= end; cur += gridX) {
            ctx.beginPath();
            ctx.moveTo(cur, upper);
            ctx.lineTo(cur, lower);
            ctx.stroke();
        }

        // vertical grids
        var begin = grids.ver.begin * gridY;
        var end = grids.ver.end * gridY;
        var left = -originX;
        var right = width-originX;
        for (var cur = begin; cur <= end; cur += gridY) {
            ctx.beginPath();
            ctx.moveTo(left, cur);
            ctx.lineTo(right, cur);
            ctx.stroke();
        }

        ctx.restore();
    }

    drawAxes(ctx) {
        var originX = this.origin.x;
        var originY = this.origin.y;
        var width = this.canvas.width;
        var height = this.canvas.height;

        ctx.save();

        ctx.strokeStyle = this.axesColor;
        ctx.lineWidth = 1;

        // x-axis
        if (0 <= originY && originY <= height) {
            ctx.beginPath();
            ctx.moveTo(0, originY);
            ctx.lineTo(width, originY);
            ctx.stroke();
        }

        // y-axis
        if (0 <= originX && originX <= width) {
            ctx.beginPath();
            ctx.moveTo(originX, 0);
            ctx.lineTo(originX, height);
            ctx.stroke();
        }

        ctx.restore();        
    }

    drawTickMarks(ctx) {
        var grids = this.gridLocations;
        var tickLenX = this.tickLen.x;
        var tickLenY = this.tickLen.y;
        var originX = this.origin.x;
        var originY = this.origin.y;
        var gridX = this.grid.x;
        var gridY = this.grid.y;
        var unitX = this.unit.x;
        var unitY = this.unit.y;

        ctx.save();

        ctx.translate(originX, originY);

        ctx.lineWidth = 1;
        ctx.strokeStyle = this.axesColor;

        // horizontal ticks
        var begin = grids.hor.begin;
        var end = grids.hor.end;
        for (var cur = begin; cur <= end; cur++) {
            if (cur) {
                ctx.beginPath();
                ctx.moveTo(cur * gridX, -tickLenX);
                ctx.lineTo(cur * gridX, tickLenX);
                ctx.stroke();
            }
            
        }

        // vertical ticks
        var begin = grids.ver.begin;
        var end = grids.ver.end;
        for (var cur = begin; cur <= end; cur++) {
            if (cur) {
                ctx.beginPath();
                ctx.moveTo(-tickLenY, cur * gridY);
                ctx.lineTo(tickLenY, cur * gridY);
                ctx.stroke();
            }
            
        }

        ctx.restore();
    }

    drawLabels(ctx) {
        var textTickDistance = 5;
        var textVerOffset = 3;

        var grids = this.gridLocations;
        var tickLenX = this.tickLen.x;
        var tickLenY = this.tickLen.y;
        var originX = this.origin.x;
        var originY = this.origin.y;
        var gridX = this.grid.x;
        var gridY = this.grid.y;
        var unitX = this.unit.x;
        var unitY = this.unit.y;

        ctx.save();

        ctx.translate(originX, originY);

        ctx.fillStyle = this.axesColor;
        ctx.font = '9px Arial';
        ctx.textAlign = 'start';

        var textHorOffsetX = textTickDistance + tickLenX;
        var textHorOffsetY = textTickDistance + tickLenY;

        // vertical labels
        var begin = grids.ver.begin;
        var end = grids.ver.end;
        for (var cur = begin; cur <= end; cur++) {
            if (cur) {
                var nbr = Number.parseFloat(cur*unitY).toFixed(14);
                ctx.fillText(-nbr, textHorOffsetY, gridY*cur + textVerOffset);
            }
        }

        ctx.rotate(0.5*Math.PI);

        // horizontal labels
        var begin = grids.hor.begin;
        var end = grids.hor.end;
        for (var cur = begin; cur <= end; cur++) {
            if (cur) {
                var nbr = Number.parseFloat(-cur*unitX).toFixed(14);
                ctx.fillText(-nbr, textHorOffsetX, -gridX*cur + textVerOffset);
            }
        }

        ctx.restore();
    }

    drawPlot(ctx, eqn) {
        var originX = this.origin.x;
        var originY = this.origin.y;
        var sfX = this.sf.x;
        var sfY = this.sf.y;
        var width = this.canvas.width;
        var height = this.canvas.height;

        ctx.save();

        ctx.translate(originX, originY);

        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.strokeStyle = this.curveColor;

        var cur_x, cur_y, next_x, next_y;
        var isCurDef, isNextDef, isCurVisible, isNextVisible;

        var lower = (-originY)*sfY;
        var upper = (height-originY)*sfY;
        var left = (-originX)*sfX;
        var right = (width-originX)*sfX;
        var isInWindow = function(x, y) {
            var ret = 
                left <= x &&
                x <= right &&
                lower <= y &&
                y <= upper;

            return ret;
        }

        var tstart = this.eqn.tstart;
        var tstop = this.eqn.tstop;
        var tstep = this.delta.t;

        // get the current slider value
        var kk = this.slider.val;

        for(var tt = tstart; tt < tstop; tt += tstep) {
            if(tt > tstart) {
                cur_x = next_x;
                cur_y = next_y;
                isCurDef = isNextDef;
                isCurVisible = isNextVisible;
            } else {
                cur_x = eqn.x(tt,kk);
                cur_y = eqn.y(tt,kk);
                isCurDef = !isNaN(cur_x) && !isNaN(cur_y);
                isCurVisible = isInWindow(cur_x, cur_y);
            }

            next_x = eqn.x(tt+tstep,kk);
            next_y = eqn.y(tt+tstep,kk);
            isNextDef = !isNaN(next_x) && !isNaN(next_y);
            isNextVisible = isInWindow(next_x, next_y);

            // if so, follow the curve
            if(isNextDef) {
                if(isCurDef /*&& (isCurVisible || isNextVisible)*/) {
                    ctx.lineTo(next_x/sfX, -next_y/sfY);
                } else {
                    ctx.moveTo(next_x/sfX, -next_y/sfY);
                }
            }
        }
        ctx.stroke();

        ctx.restore();
    }

    // called to update the position of the view window
    scroll(x_change, y_change) {
        this.origin = {
            x: this.origin.x + x_change,
            y: this.origin.y + y_change
        };

        this.computeGridLocations(this.canvas, this.grid, this.origin);
    }

    toOrigin() {
    	this.origin = {
    		x: 0.5*this.canvas.width,
    		y: 0.5*this.canvas.height
    	};

    	this.computeGridLocations(this.canvas, this.grid, this.origin);
    }

    // called to zoom in or out
    zoom(zoomX, zoomY, 
        centerX = 0.5*this.canvas.width, 
        centerY = 0.5*this.canvas.height) {

        this.scale = {
            x: this.scale.x + zoomX,
            y: this.scale.y + zoomY
        };
        
        var originX = this.origin.x;
        var originY = this.origin.y;
        var dstX = originX - centerX;
        var dstY = originY - centerY;

        this.origin = {
            x: originX - dstX*(Math.pow(10,zoomX)-1),
            y: originY - dstY*(Math.pow(10,zoomY)-1)
        };

        // limits
        if (this.scale.x > scaleRange.max) {
            this.scale.x = scaleRange.max;
            this.origin.x = originX;
        }
        if (this.scale.x < scaleRange.min) {
            this.scale.x = scaleRange.min;
            this.origin.x = originX;
        }
        if (this.scale.y > scaleRange.max) {
            this.scale.y = scaleRange.max;
            this.origin.y = originY;
        }
        if (this.scale.y < scaleRange.min) {
            this.scale.y = scaleRange.min;
            this.origin.y = originY;
        }

        this.computeSF(this.calibration, this.scale);
        this.computeUnit(this.scale);
        this.computeGrid(this.sf, this.unit);
        this.computeGridLocations(this.canvas, this.grid, this.origin);
        this.computeDeltas(this.sf);
    }
}

// these pieces of code are never called, but could be a helpful reference when we implement play button later

// // continuously updates the kk value and redraws to animate the graph
// // TODO: add a more precise timing mechanism
// async function play() {
//     while(running) {
//         // update the value of kk
//         if(kk < 2*Math.PI) {
//             kk += Math.PI/30;
//         } else {
//             kk = 0 + Math.PI/30;
//         }

//         this.paint();

//         // sleep a bit
//         await new Promise(resolve => setTimeout(resolve, 20));
//     }
// }

// // switches the graph into play mode or stops it
// // TODO: should be connected to the play button
// function run() {
//     running = !running;
//     play();
// }
// t value specs -- TODO: let the user adjust this
