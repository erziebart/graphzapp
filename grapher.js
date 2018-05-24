// Adapted from "http://usefulangle.com/post/19/html5-canvas-tutorial-how-to-draw-graphical-coordinate-system-with-grids-and-axis"

// current t value
var tt = 0;

// n value specs
var nstart = -10;
var nstop = 10;
var nstep = 0.0625;

// grid properties
var grid_size = 25;
var x_axis_distance_grid_lines = 10;
var y_axis_distance_grid_lines = 10;
var x_axis_starting_point = 1;
var y_axis_starting_point = 1;

// canvas dimensions
var canvas_width;
var canvas_height;

// player
var running = false;

//var _x = [function(n,t){if(n%2 == 0) {return NaN;} return n;}];
//var _y = [function(n,t){return Math.sqrt(n);},function(n,t){return Math.abs(n);}];

// this is called when the canvas is first loaded
function init() {
    var canvas = document.getElementById("canvas");
    update_canvas(canvas);

    var ctx = canvas.getContext("2d");
    draw(ctx);
}

// this should be called whenever the canvas changes dimensions
function update_canvas(canvas) {
    canvas_width = canvas.width;
    canvas_height = canvas.height;
}

// this is called to draw all the graph elements
function draw(ctx) {
    ctx.moveTo(0,0);
    
    grids(ctx,"#000000","#e9e9e9",1,"9px Arial");

    plot(ctx,"#96ECFF",1);

    
}

// draws the gridlines and axes ticks and labels
function grids(ctx,axis_color,grid_color,thick,font) {
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
function plot(ctx,color,thick) {
    ctx.translate(y_axis_distance_grid_lines*grid_size, x_axis_distance_grid_lines*grid_size);

    ctx.beginPath();
    ctx.lineWidth = thick;
    ctx.strokeStyle = color;

    var cur_x, cur_y, next_x, next_y;

    // var n = 0;
    for(var n = nstart; n < nstop; n += nstep) {
        if(n > nstart) {
            cur_x = next_x;
            cur_y = next_y;
        } else {
            var cur_x = eval(n,tt,_x);
            var cur_y = eval(n,tt,_y);
        }

        var next_x = eval(n+nstep,tt,_x);
        var next_y = eval(n+nstep,tt,_y);

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

// used to evaluate the functions
function eval(n,t,fn) {
    for(var i = 0; i < fn.length; i++) {
        var val = fn[i](n,t);
        if (isFinite(val) && !isNaN(val)) {
            if (val === true) { return 1;}
            else if(val === false) {return 0;}
            else {return val;}
        }
    }
    return NaN;
}

// called every time canvas needs to be redrawn with something already on it
function redraw(ctx) {
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.fillStyle = "#FFFFFF";
    ctx.fillRect(0,0,canvas_width,canvas_height);
    draw(ctx);
}

// called to update the tt value
// TODO: works with the slider, taking the new value as argument
function update() {
    // ignore this call if the graph is already playing
    if (running) {
        return;
    }

    // update the value of tt
    if(tt >= 5) {
        tt = 0;
    } else {
        tt += 0.125;
    }

    // redraw the canvas
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    redraw(ctx);
}

// continuously updates the tt value and redraws to animate the graph
// TODO: add a more precise timing mechanism
async function play() {
    while(running) {
        // update the value of tt
        if(tt >= 10) {
            tt = 0;
        } else {
            tt += 0.125;
        }

        redraw();

        // sleep a bit
        await new Promise(resolve => setTimeout(resolve, 20));
    }
}

// switches the graph into play mode or stops it
// TODO: should be connected to the play button
function run() {
    running = !running;
    play();
}