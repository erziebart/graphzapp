// Adapted from "http://usefulangle.com/post/19/html5-canvas-tutorial-how-to-draw-graphical-coordinate-system-with-grids-and-axis"

// class acts as a wrapper around html canvas and provides methods to draw and update the grapher
class GraphzappGrapher {
    constructor(canvasObj) {
        this.canvas = canvasObj;
        this.resize(this.canvas);

        // grid properties
        this.grid_size = 25;
        this.x_axis_distance_grid_lines = 10;
        this.y_axis_distance_grid_lines = 10;
        this.x_axis_starting_point = 1;
        this.y_axis_starting_point = 1;

        // equation and slider
        this.eq = null;
        this.slider = null;
    }

    // should be called whenever the canvas is resized
    resize(canvas) {
        this.canvas_width = canvas.width;
        this.canvas_height = canvas.height;
    }

    // should add the equation to a list -- for now jst sets a variable
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
        var ctx = this.canvas.getContext("2d");
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0,0,this.canvas_width,this.canvas_height);
        this.draw(ctx);
    }

    // this is called to draw all the graph elements
    draw(ctx) {
        ctx.moveTo(0,0);
        
        this.grids(ctx,"#000000","#e9e9e9",1,"9px Arial");
        this.plot(ctx, this.eqn, "#4D6F96", 2);
    }

    // draws the gridlines and axes ticks and labels
    grids(ctx,axis_color,grid_color,thick,font) {
        var canvas_width = this.canvas_width;
        var canvas_height = this.canvas_height;
        var grid_size = this.grid_size;
        var x_axis_distance_grid_lines = this.x_axis_distance_grid_lines;
        var y_axis_distance_grid_lines = this.y_axis_distance_grid_lines;
        var x_axis_starting_point = this.x_axis_starting_point;
        var y_axis_starting_point = this.y_axis_starting_point;

        // no of vertical grid lines
        var num_lines_x = Math.floor(canvas_height/grid_size);

        // no of horizontal grid lines
        var num_lines_y = Math.floor(canvas_width/grid_size);

        // gridline and tick marks formatting
        ctx.lineWidth = thick;

        // Draw grid lines along X-axis
        for(var i=0; i<=num_lines_x; i++) {
            ctx.beginPath();

            // If line represents X-axis draw in different color
            if(i == x_axis_distance_grid_lines)
                ctx.strokeStyle = axis_color;
            else
                ctx.strokeStyle = grid_color;

            if(i == num_lines_x) {
                ctx.moveTo(0, grid_size*i);
                ctx.lineTo(canvas_width, grid_size*i);
            }
            else {
                ctx.moveTo(0, grid_size*i+0.5);
                ctx.lineTo(canvas_width, grid_size*i+0.5);
            }
            ctx.stroke();
        }

        // Draw grid lines along Y-axis
        for(i=0; i<=num_lines_y; i++) {
            ctx.beginPath();

            // If line represents Y-axis draw in different color
            if(i == y_axis_distance_grid_lines)
                ctx.strokeStyle = axis_color;
            else
                ctx.strokeStyle = grid_color;

            if(i == num_lines_y) {
                ctx.moveTo(grid_size*i, 0);
                ctx.lineTo(grid_size*i, canvas_height);
            }
            else {
                ctx.moveTo(grid_size*i+0.5, 0);
                ctx.lineTo(grid_size*i+0.5, canvas_height);
            }
            ctx.stroke();
        }

        ctx.translate(y_axis_distance_grid_lines*grid_size, x_axis_distance_grid_lines*grid_size);

        // tick mark formatting
        ctx.lineWidth = thick;
        ctx.strokeStyle = axis_color;
        ctx.fillStyle = axis_color;
        ctx.font = font;
        ctx.textAlign = 'start';

        // Ticks marks along the positive X-axis
        for(i=1; i<(num_lines_y - y_axis_distance_grid_lines); i++) {
            ctx.beginPath();

            // Draw a tick mark 6px long (-3 to 3)
            ctx.moveTo(grid_size*i+0.5, -3);
            ctx.lineTo(grid_size*i+0.5, 3);
            ctx.stroke();

            // Text value at that point
            ctx.fillText(x_axis_starting_point*i, grid_size*i-2, 15);
        }

        // Ticks marks along the negative X-axis
        for(i=1; i<y_axis_distance_grid_lines; i++) {
            ctx.beginPath();

            // Draw a tick mark 6px long (-3 to 3)
            ctx.moveTo(-grid_size*i+0.5, -3);
            ctx.lineTo(-grid_size*i+0.5, 3);
            ctx.stroke();

            // Text value at that point
            ctx.fillText(-x_axis_starting_point*i, -grid_size*i+3, 15);
        }

        // Ticks marks along the positive Y-axis
        // Positive Y-axis of graph is negative Y-axis of the canvas
        for(i=1; i<(num_lines_x - x_axis_distance_grid_lines); i++) {
            ctx.beginPath();

            // Draw a tick mark 6px long (-3 to 3)
            ctx.moveTo(-3, grid_size*i+0.5);
            ctx.lineTo(3, grid_size*i+0.5);
            ctx.stroke();

            // Text value at that point
            ctx.font = font;
            ctx.fillText(-y_axis_starting_point*i, 8, grid_size*i+3);
        }

        // Ticks marks along the negative Y-axis
        // Negative Y-axis of graph is positive Y-axis of the canvas
        for(i=1; i<x_axis_distance_grid_lines; i++) {
            ctx.beginPath();

            // Draw a tick mark 6px long (-3 to 3)
            ctx.moveTo(-3, -grid_size*i+0.5);
            ctx.lineTo(3, -grid_size*i+0.5);
            ctx.stroke();

            // Text value at that point
            ctx.fillText(y_axis_starting_point*i, 8, -grid_size*i+3);
        }

        ctx.translate(-y_axis_distance_grid_lines*grid_size, -x_axis_distance_grid_lines*grid_size);
    }

    // draws the function plot
    plot(ctx,eqn,color,thick) {
        var y_axis_distance_grid_lines = this.y_axis_distance_grid_lines;
        var x_axis_distance_grid_lines = this.x_axis_distance_grid_lines;
        var grid_size = this.grid_size;

        ctx.translate(y_axis_distance_grid_lines*grid_size, x_axis_distance_grid_lines*grid_size);

        ctx.beginPath();
        ctx.lineWidth = thick;
        ctx.strokeStyle = color;

        var cur_x, cur_y, next_x, next_y;

        var tstart = eqn.tstart;
        var tstop = eqn.tstop;

        // the step is handcoded for now until resolution implemented
        var tstep = 0.0625;

        // get the current slider value
        var kk = this.slider.val;

        for(var tt = tstart; tt < tstop; tt += tstep) {
            if(tt > tstart) {
                cur_x = next_x;
                cur_y = next_y;
            } else {
                var cur_x = eval(tt,kk,eqn.x);
                var cur_y = eval(tt,kk,eqn.y);
            }

            var next_x = eval(tt+tstep,kk,eqn.x);
            var next_y = eval(tt+tstep,kk,eqn.y);

            if(!isNaN(cur_x) && !isNaN(cur_y)) {
                ctx.moveTo(grid_size * cur_x, -cur_y * grid_size);

                if(!isNaN(next_x) && !isNaN(next_y)) {
                    ctx.lineTo(grid_size * next_x, -next_y * grid_size);
                }
            }
        }
        ctx.stroke();

        ctx.translate(-y_axis_distance_grid_lines*grid_size, -x_axis_distance_grid_lines*grid_size);
    }
}

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