doClick = function() { 
	alert("clicked!");
}

changeIt = function() { 
	newColor = document.getElementById("divcolor").value;
	document.getElementsByClassName("div1")[0].style.backgroundColor = newColor;
}