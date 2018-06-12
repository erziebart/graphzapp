//This file contains scripts for manipulating the appearance of elements in the front end UI

adjustForResize();

//Taken from https://www.mattcromwell.com/detecting-mobile-devices-javascript/
var isMobile = { 
Android: function() { return navigator.userAgent.match(/Android/i); }, 
BlackBerry: function() { return navigator.userAgent.match(/BlackBerry/i); }, 
iOS: function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i); }, 
Opera: function() { return navigator.userAgent.match(/Opera Mini/i); }, 
Windows: function() { return navigator.userAgent.match(/IEMobile/i); }, 
any: function() { return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()); } };

console.log(isMobile.any());

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

