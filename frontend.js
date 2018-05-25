var tt = 0;
var tmin;
var tmax;
adjustRange();
adjustT();

//Updates tmin and tmax when user hits 'update range'
function adjustRange(){
	tmin = document.getElementById('tmin').value;
	tmax = document.getElementById('tmax').value;
	tmin = parseFloat(tmin);
	tmax = parseFloat(tmax);
	adjustT();
}

//Called whenever slider is moved
function adjustT(){
    var num = document.getElementById('t_slider').value;
    tt = (num/1000) * (tmax-tmin) + tmin;
    document.getElementById("t_value").innerHTML = tt.toFixed(2);

    // redraw the canvas
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    redraw(ctx);
}