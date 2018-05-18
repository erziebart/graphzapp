/////////////////////////////////////////////////////////////////////
// Proof of concept using html canvas element
//
// From "http://www.javascripter.net/faq/plotafunctiongraph.htm"
// with only slgiht modifications and additions, so definitely 
// don't actually use this code.
/////////////////////////////////////////////////////////////////////

////////////////////THESE WOULD BE GENERATED/////////////////////////
function fun1(x,t) {return 3*Math.sin(2*x)+Math.cos(x/2)+x*t;  }
function fun2(x,t) {return 5*Math.cos(x)+x*t;}
/////////////////////////////////////////////////////////////////////

var tt = 0;

// called at the start
function draw() {
 var canvas = document.getElementById("canvas");
 if (null==canvas || !canvas.getContext) return;

 var axes={}, ctx=canvas.getContext("2d");
 axes.x0 = .5 + .5*canvas.width;  // x0 pixels from left to x=0
 axes.y0 = .5 + .5*canvas.height; // y0 pixels from top to y=0
 axes.scale = 40;                 // 40 pixels from x=0 to x=1
 axes.doNegativeX = true;

 showAxes(ctx,axes,1);
 funGraph(ctx,axes,fun1,"rgb(11,153,11)",1); 
 funGraph(ctx,axes,fun2,"rgb(66,44,255)",2);
}

function funGraph (ctx,axes,func,color,thick) {
 var xx, yy, dx=4, x0=axes.x0, y0=axes.y0, scale=axes.scale;
 var iMax = Math.round((ctx.canvas.width-x0)/dx);
 var iMin = axes.doNegativeX ? Math.round(-x0/dx) : 0;
 ctx.beginPath();
 ctx.lineWidth = thick;
 ctx.strokeStyle = color;

 for (var i=iMin;i<=iMax;i++) {
  xx = dx*i; yy = scale*func(xx/scale,tt);
  if (i==iMin) ctx.moveTo(x0+xx,y0-yy);
  else         ctx.lineTo(x0+xx,y0-yy);
 }
 ctx.stroke();
}

function showAxes(ctx,axes,thick) {
 var x0=axes.x0, w=ctx.canvas.width;
 var y0=axes.y0, h=ctx.canvas.height;
 var xmin = axes.doNegativeX ? 0 : x0;
 ctx.beginPath();
 ctx.lineWidth = thick;
 ctx.strokeStyle = "rgb(128,128,128)"; 
 ctx.moveTo(xmin,y0); ctx.lineTo(w,y0);  // X axis
 ctx.moveTo(x0,0);    ctx.lineTo(x0,h);  // Y axis
 ctx.stroke();
}

// called for every button click
function redraw() {
	// change t
	tt += 0.125;

	var canvas = document.getElementById("canvas");
    if (null==canvas || !canvas.getContext) return;

    var axes={}, ctx=canvas.getContext("2d");
    axes.x0 = .5 + .5*canvas.width;  // x0 pixels from left to x=0
    axes.y0 = .5 + .5*canvas.height; // y0 pixels from top to y=0
    axes.scale = 40;                 // 40 pixels from x=0 to x=1
    axes.doNegativeX = true;

    ctx.fillStyle = "#FFFFFF";
    ctx.fillRect(0,0,canvas.width,canvas.height);
    showAxes(ctx,axes,1);
    funGraph(ctx,axes,fun1,"rgb(11,153,11)",1); 
    funGraph(ctx,axes,fun2,"rgb(66,44,255)",2);
}