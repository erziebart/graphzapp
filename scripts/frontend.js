var grapher;

// called when the page is first loaded
function init() {
    // create grapher
    var canvas = document.getElementById('canvas');
    grapher = new GraphzappGrapher(canvas);

    // add the equations
    grapher.addEquation(eqn);

    // add the sliders
    var kmin = document.getElementById('kmin');
    var kmax = document.getElementById('kmax');
    var kval = document.getElementById('k_value');
    var kslider = new GraphzappSlider(document.getElementById("k_slider"), kmin, kmax, kval);
    grapher.addSlider(kslider);

    // get t values
    var tmin = document.getElementById('tmin');
    var tmax = document.getElementById('tmax');
    var eqnRange = new GraphzappEquationRange(tmin, tmax);
    grapher.addEqnRange(eqnRange);

    // draw the content
    grapher.paint();
}

//Updates kmin and kmax when user hits 'update range'
function adjustRange() {
    var kslider = grapher.getSlider();
    kslider.adjustRange();
    grapher.paint();
}

//Called whenever slider is moved
function adjustValue() {
    var kslider = grapher.getSlider();
    kslider.adjustValue();
    grapher.paint();
}
