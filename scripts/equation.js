// graphzapp equation classes
var Mode = Object.freeze({functional:0, parametric:1, polar:2});

// basis class
function Equation(mode) {
	this.mode = mode;
}

Equation.prototype.getXY = function(tt, kk) {
	return (NaN, NaN);
};

// functional equations
function Functional(y_eqn) {
	Equation.call(this, Mode.functional);
	this.y = y_eqn;
}

Functional.prototype = Object.create(Equation.prototype);

Functional.prototype.getXY = function(tt, kk) {
	return {x:tt, y:this.y(tt, kk)};
}

// parametric equations
function Parametric(x_eqn, y_eqn, t_start, t_stop) {
	Equation.call(this, Mode.parametric);
	this.x = x_eqn;
	this.y = y_eqn;
	this.tstart = t_start;
	this.tstop = t_stop;
}

Parametric.prototype = Object.create(Equation.prototype);

Parametric.prototype.getXY = function(tt, kk) {
	return {x:this.x(tt, kk), y:this.y(tt, kk)};
}

// polar equations
function Polar(r_eqn, t_start, t_stop) {
	Equation.call(this, Mode.polar);
	this.r = r_eqn;
	this.tstart = t_start;
	this.tstop = t_stop;
}

Polar.prototype = Object.create(Equation.prototype);

Polar.prototype.getXY = function(tt, kk) {
	return {x:this.r(tt, kk)*Math.cos(tt*Math.PI/180), y:this.r(tt, kk)*Math.sin(tt*Math.PI/180)};
}