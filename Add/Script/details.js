$(document).ready(function(){
	var selected_text = $("div#plus input[value!=''][type='text']");
	/*var selected_radio = $("div#plus input[value='Tous'][type='radio'][name='number'][checked!='checked']");*/
	if((selected_text.length == 0)/* && (selected_radio.length == 0)*/){
		$('div#plus').hide();
	}
	$("button.button").click(function(){
		$('div#plus').toggle(500);
		return false;
	});
});