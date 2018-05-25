var t = 0;
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
    t = (num/1000) * (tmax-tmin) + tmin;
    document.getElementById("t_value").innerHTML = t.toFixed(2);

    //TODO: add call to funtion that redraws graph based on new t
}