//This file contains scripts for manipulating the appearance of elements in the front end UI

adjustForResize();
document.addEventListener('click', function(event){
	if (!event.target.closest('.colors_dropdown')) {
		hideAllDropdowns(event);
	}
});

//Taken from https://www.mattcromwell.com/detecting-mobile-devices-javascript/
var isMobile = { 
	Android: function() { return navigator.userAgent.match(/Android/i); }, 
	BlackBerry: function() { return navigator.userAgent.match(/BlackBerry/i); }, 
	iOS: function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i); }, 
	Opera: function() { return navigator.userAgent.match(/Opera Mini/i); }, 
	Windows: function() {return navigator.userAgent.match(/IEMobile/i);},
	any: function() { return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()); } 
};

if (isMobile.any()) {
	document.getElementsByClassName('graph_options')[0].classList.add('locked');
}


//Sets the arrow of the go button to point down if window is narrow enough that the mobile layout is used.
function adjustForResize(){
	if (window.innerWidth <= 1100){
		document.getElementById("go_button").value = "▼";
	}
	else {
		document.getElementById("go_button").value = "▶";
	}
}

function showTooltip(tooltip) {
	if (isMobile.any()) {
		var tooltip = document.getElementById(tooltip);
		if (tooltip.parentNode.parentNode.className.split(' ').indexOf('tooltip')>=0) {
			tooltip.style.opacity = 1;
			tooltip.style.visibility = 'visible';
		}
	}
}

function hideTooltip(tooltip) {
	if (isMobile.any()) {
		document.getElementById(tooltip).style.opacity = 0;
		document.getElementById(tooltip).style.visibility = 'hidden';
	}
}

//Hides or shows dropdown menu
function toggleDropdown(id) {
	hideAllDropdowns();
	var dropdown = document.getElementById('dropdown' + id).getElementsByClassName('options')[0];
	if (dropdown.style.display == 'block') {
		dropdown.style.display = 'none';
		document.getElementsByClassName('graph_options')[0].classList.remove('show');
	}
	else {
		dropdown.style.display = 'block';
		document.getElementsByClassName('graph_options')[0].classList.add('show');
	}
}


//Selects a new value in the dropdown menu
function select(id, color) {
	clearSelection(id, color, newSelection);
}

function clearSelection(id, color, callback) {
	var dropdown = document.getElementById('dropdown' + id);
	dropdown.getElementsByClassName('options')[0].style.display = 'none';
	dropdown.getElementsByClassName('hidden')[0].classList.remove('hidden');
	callback(id, color);
}

function newSelection(id, color) {
	var dropdown = document.getElementById('dropdown' + id);
	var colorElement = dropdown.getElementsByClassName(color)[0];
	dropdown.getElementsByClassName('selected')[0].innerHTML = colorElement.outerHTML + "<span>▼</span>";
	colorElement.classList.add('hidden');
	var input = document.getElementById('input' + id);
	input.value = color;
	document.getElementsByClassName('graph_options')[0].classList.remove('show');
}

function hideAllDropdowns() {
		var dropdowns = document.getElementsByClassName('colors_dropdown');
		for (var i = 0; i < dropdowns.length; i++) {
			var dropdown = dropdowns[i].getElementsByClassName('options')[0];
			dropdown.style.display = 'none';
			document.getElementsByClassName('graph_options')[0].classList.remove('show');
		}
}

//Prevents graph toolbar from appearing when graph is being dragged
function disableToolbar() {
	document.getElementsByClassName('toolbar_overlay')[0].style.display = 'none';
}

function enableToolbar() {
	document.getElementsByClassName('toolbar_overlay')[0].style.display = '';
}



