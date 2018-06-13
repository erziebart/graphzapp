// Adapted from "http://usefulangle.com/post/19/html5-canvas-tutorial-how-to-draw-graphical-coordinate-system-with-grids-and-axis"

// tunable basis parameters for the grapher behavior
const res = 1.5; // how close adjacent points should be
const tickLenRate = 0.006 // fraction of screen for tick mark length
const scaleRate = 0.03125 // rate at which the scale will change to zoom in and out

// class acts as a wrapper around html canvas and provides methods to draw and update the graph
class GraphzappGrapher {
    constructor(canvasObj, origin, scale) {
        //this.canvas = canvasObj;
        this.resize(canvasObj);
        this.reposition(origin);
        this.rescale(scale);

        // grid properties
        this.grid_size = 25;
        this.x_axis_distance_grid_lines = 10;
        this.y_axis_distance_grid_lines = 10;
        this.x_axis_starting_point = 1;
        this.y_axis_starting_point = 1;

        // equation and slider
        this.eq = null;
        this.slider = null;
        this.eqnRange = null;

        // colors and options
        this.showAxes = true
        this.showGrids = true
        this.showLables = true
        this.backgroundColor = "#FFFFFF"
        this.axesColor = "#E9E9E9"
        this.gridColor = "#000000"
    }

    // should be called whenever the canvas is resized
    resize(canvas) {
        this.canvas = canvas;
        this.canvas_width = canvas.width;
        this.canvas_height = canvas.height;
        var width = canvas.width;
        var height = canvas.height;

        // set tick mark length
        var tickLenX = tickLenRate * width;
        var tickLenY = tickLenRate * height;
        this.tickLen = {x: tickLenX, y: tickLenY};
    }

    // this should be called to update the position of the origin
    reposition(origin) {
        this.origin = origin;
    }

    // this should be called to update the scale
    rescale(scale) {
        var scaleX = scale.x;
        var scaleY = scale.y;
        this.scale = scale;

        // set the scale factors
        var sfX = 0.05*Math.pow(10, scaleX);
        var sfY = 0.05*Math.pow(10, scaleY);
        this.sf = {x: sfX, y: sfY};

        // set the units
        var unitX = this.getUnit(scaleX);
        var unitY = this.getUnit(scaleY);
        this.unit = {x: unitX, y: unitY};

        // set the grid separation
        var gridX = unitX/sfX;
        var gridY = unitY/sfY;
        this.grid = {x: gridX, y: gridY};
    }

    // helper function to find the correct unit for an axis
    getUnit(scale) {
        var order = Math.floor(scale);
        var mag = scale - order;
        if (mag == 0) {return Math.pow(10,order);}
        if (mag < Math.log10(2)) {return 2*Math.pow(10,order);}
        if (mag < Math.log10(5)) {return 5*Math.pow(10,order);}
        else {return 10*Math.pow(10,order);}
    }


    // Add the desired t range
    addEqnRange(eqnRange) {
        this.eqnRange = eqnRange;
    }

    // returns the t range to ultimately be modified
    getEqnRange() {
        return this.eqnRange;
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
    paint(grid, axes, numbers, gridColor, axesColor, backgroundColor) {
        this.showAxes = axes
        this.showGrids = grid
        this.showLables = numbers
        this.backgroundColor = backgroundColor
        this.axesColor = axesColor
        this.gridColor = gridColor

        var ctx = this.canvas.getContext("2d");
        ctx.clearRect(0,0,this.canvas.width,this.canvas.height);
        this.draw(ctx);
    }

    // this is called to draw all the graph elements
    draw(ctx) {
        if (this.showGrids) {
            this.getGridLocations();
            this.drawGrids(ctx);
            
        }

        if (this.showAxes) {
            this.drawAxes(ctx);
        }
    }

    getGridLocations() {
        var ret = {horizontal: [], vertical: []};

        // positive x-axis


    }

    drawGrids(ctx) {

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
            ctx.moveTo(0, originY);
            ctx.lineTo(width, originY);
            ctx.stroke();
        }

        // y-axis
        if (0 <= originX && originX <= width) {
            ctx.moveTo(originX, 0);
            ctx.lineTo(originX, height);
            ctx.stroke();
        }

        ctx.restore();
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
