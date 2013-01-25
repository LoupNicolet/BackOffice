$(document).ready(function(){
	$('div#plus').hide();
	$("button").click(function(){
		$("div#plus").toggle(500);
		return false;
	});
});