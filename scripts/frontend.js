var grapher;
var grid = true;
var axes = true;
var numbers = true;
var gridColor = '#E9E9E9';
var axesColor = '#000000';
var backgroundColor = '#FFFFFF';
var colors = {
    'black': ['#000000', '#E9E9E9'], 
    'white' : ['#FFFFFF', '#8C8C8C'],
    'blue' : ['#4D6F96', '#DDE5EE'],
    'red' : ['#CC0000', '#FFCCCC'],
    'green' : ['#1AFF1A', '#CCFFCC'],
    'purple' : ['#660066', '#FF80FF'],
    'gray' : ['#999999', '#F7F7F7']
};

// called when the page is first loaded
function init() {

    // create grapher
    var canvas = document.getElementById('canvas');
    var origin = {x: 0.5*canvas.width, y: 0.5*canvas.height};
    var scale = {x: -1.3, y: -1.3};
    grapher = new GraphzappGrapher(canvas, origin, scale);

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
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

//Updates kmin and kmax when user hits 'update range'
function adjustRange() {
    var kslider = grapher.getSlider();
    kslider.adjustRange();
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

//Called whenever slider is moved
function adjustValue() {
    var kslider = grapher.getSlider();
    kslider.adjustValue();
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function handleCheckboxes(){
    grid = document.getElementById('grid_checkbox').checked;
    axes= document.getElementById('axes_checkbox').checked;
    var numbers_checkbox = document.getElementById('numbers_checkbox');

    if (!axes) {
        numbers_checkbox.checked = false;
        numbers_checkbox.disabled = true;
    }
    else {
        numbers_checkbox.disabled = false;
    }
    numbers = numbers_checkbox.checked;

    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);

}

function changeBackground(color) {
    var colorCode = colors[color][0];
    select(1, color);
    backgroundColor = colorCode;
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function changeAxesColor(color) {
    var mainColorCode = colors[color][0];
    var lightColorCode = colors[color][1];
    select(2, color);
    gridColor = lightColorCode;
    axesColor = mainColorCode;
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}
