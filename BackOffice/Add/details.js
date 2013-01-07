$(document).ready(function(){
			$('tr#plus').hide();
			$("button").click(function(){
				$("tr#plus").toggle(500);
				return false;
			});
		});