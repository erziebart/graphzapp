var kk = 0;
var kmin;
var kmax;
adjustRange();
adjustK();

//Updates tmin and tmax when user hits 'update range'
function adjustRange(){
	kmin = document.getElementById('kmin').value;
	kmax = document.getElementById('kmax').value;
	kmin = parseFloat(kmin);
	kmax = parseFloat(kmax);
	adjustK();
}

//Called whenever slider is moved
function adjustK(){
    var num = document.getElementById('k_slider').value;
    kk = (num/1000) * (kmax-kmin) + kmin;
    document.getElementById("k_value").innerHTML = kk.toFixed(2);

    // redraw the canvas
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    redraw(ctx);
}