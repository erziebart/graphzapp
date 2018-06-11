//This file contains scripts for manipulating the appearance of elements in the front end UI

adjustForResize();

//Sets the arrow of the go button to point down if window is narrow enough that the mobile layout is used.
function adjustForResize(){
	if (window.innerWidth <= 1100){
		document.getElementById("go_button").value = "▼";
	}
	else {
		document.getElementById("go_button").value = "▶";
	}
}