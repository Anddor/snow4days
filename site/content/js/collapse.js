function toggle(elementId) {
	var ele = document.getElementById(elementId);
	if(ele.style.display == "block") { // if the element is blocked/hidden, collapse the element.
    		ele.style.display = "none"; // // this shows the content.
  	}
	else {
		ele.style.display = "block"; // if it is already collapsed, block it.
	}
} 