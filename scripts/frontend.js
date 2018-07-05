var grapher;

function addListener(elt, type, handler) {
    if (elt.addEventListener) { // For all major browsers, except IE 8 and earlier
        elt.addEventListener(type, handler);
    } else if (elt.attachEvent) { // For IE 8 and earlier versions
        elt.attachEvent(type, handler);
    }
}

// called when the page is first loaded
function init() {
    // create grapher
    var canvas = document.getElementById('canvas');
    var origin = {x: 0.5*canvas.width, y: 0.5*canvas.height};
    var scale = {x: 0, y: 0};
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

    // set the graph options
    document.getElementById('grid_checkbox').checked = grid;
    document.getElementById('axes_checkbox').checked = axes;
    numbers_checkbox = document.getElementById('numbers_checkbox');
    if (!axes) {numbers_checkbox.disabled = true; numbers_checkbox.checked = false;}
    else {numbers_checkbox.checked = numbers;}

    // draw the content
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

// called to submit the forms
submitForms = function() {
    document.getElementById("eqn_input").submit();
    //document.getElementById("graph_options").submit();
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

function toggleShowGrids() {
    grid = document.getElementById('grid_checkbox').checked;
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function toggleShowAxes() {
    axes = document.getElementById('axes_checkbox').checked;
    numbers_checkbox = document.getElementById('numbers_checkbox');
    if (axes) {
        numbers_checkbox.disabled = false;
        numbers_checkbox.checked = numbers;
    } else {
        numbers_checkbox.disabled = true;
        numbers_checkbox.checked = false;
    }

    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function toggleShowLabels() {
    numbers = document.getElementById('numbers_checkbox').checked;

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

// for dragging the canvas screen
var dragStart;
var dragging = false;
var inCanvas = false;

function enterCanvas(event) {
    inCanvas = true;
    dragStart = {x:event.offsetX , y:event.offsetY };
}

function leaveCanvas(event) {
    inCanvas = false;
}

function startDrag(event) {
    if(inCanvas && event.which === 1) { // left mouse button
        dragging = true;
        dragStart = {x:event.offsetX , y:event.offsetY };
    }
}

function doDrag(event) {
    if(inCanvas && dragging) {
        var changeX = event.offsetX - dragStart.x;
        var changeY = event.offsetY - dragStart.y;
        grapher.scroll(changeX, changeY);
        grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
        dragStart = {x:event.offsetX , y:event.offsetY };
    }
    
}

function stopDrag(event) {
    dragging = false;
}

const interval = 20;
var intvalId;

// zooming
function onPressPlus(event) {
    if (event.which === 1) { // left press
        zoomIn();
        intvalId = setInterval(zoomIn, interval);
    }
}

function onPressMinus(event) {
    if (event.which === 1) { // left press
        zoomOut();
        intvalId = setInterval(zoomOut, interval);
    }
}

function zoomIn() {
    var rate = -scaleRate;
    grapher.zoom(rate, rate);
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function zoomOut() {
    var rate = scaleRate;
    grapher.zoom(rate, rate);
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}

function stopZoom() {
    clearInterval(intvalId);
}

// center at origin
function toOrigin() {
    grapher.toOrigin();
    grapher.paint(grid, axes, numbers, gridColor, axesColor, backgroundColor);
}