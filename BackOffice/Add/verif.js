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
		}
	}
	