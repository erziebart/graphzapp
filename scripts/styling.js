//This file contains scripts for manipulating the appearance of elements in the front end UI

adjustForResize();

//Taken from https://www.mattcromwell.com/detecting-mobile-devices-javascript/
var isMobile = { 
Android: function() { return navigator.userAgent.match(/Android/i); }, 
BlackBerry: function() { return navigator.userAgent.match(/BlackBerry/i); }, 
iOS: function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i); }, 
Opera: function() { return navigator.userAgent.match(/Opera Mini/i); }, 
Windows: function() { return navigator.userAgent.match(/IEMobile/i); }, 
any: function() {  (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()); } };

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

function toggleDropdown(id) {
	var dropdown = document.getElementById('dropdown' + id).getElementsByClassName('options')[0];
	if (dropdown.style.display == 'block') {
		dropdown.style.display = 'none';
	}
	else {
		dropdown.style.display = 'block';
	}
}

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
}


