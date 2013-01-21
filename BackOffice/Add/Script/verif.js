function verif(element,type) {
	if (type == 1) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else if(element.value.length < 3){
			$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}else if (type == 2) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else if(element.value.length < 6){
			$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}else if (type == 3) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}else if (type == 4) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else if ( ( element.value.indexOf("@") == -1 ) 
				|| ( element.value.indexOf("@") == 0 )
				|| ( element.value.indexOf("@") != element.value.lastIndexOf("@") ) 
				|| ( element.value.indexOf(".") == element.value.indexOf("@")-1 ) 
				|| ( element.value.indexOf(".") == element.value.indexOf("@") +1 ) 
				|| (element.value.indexOf("@") == element.value.length -1 ) 
				|| (element.value.indexOf (".") == -1) 
				|| ( element.value.lastIndexOf (".") == element.value.length -1 ) 
				|| (element.value.indexOf (" ") != -1) 
				|| ((element.value.indexOf(".") == element.value.lastIndexOf(".")) && (element.value.lastIndexOf(".") < element.value.indexOf("@")))
				){
			$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}
	else if (type == 5) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else if ( ( element.value.indexOf("/") != 4) 
				|| ( element.value.lastIndexOf("/") != 7 )
				|| ( element.value.length != 10) 
				){
			$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}
	else if (type == 6) {
		if (element.value.length == 0) {
			$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
		}else if ( ( element.value.indexOf("-") != 5) 
				|| ( element.value.lastIndexOf("-") != 29 )
				|| ( element.value.charAt(11) != "-" )
				|| ( element.value.charAt(17) != "-" )
				|| ( element.value.charAt(23) != "-" )
				|| ( element.value.length != 35) 
				){
			$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
		}else{
			$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
		}
	}
}
	