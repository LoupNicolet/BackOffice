function valide(page)
{
	if(page == "chprofil"){
		var element=document.forms[0][2];
		if ( element.value.length < 1){
			return true;
		}else if (( element.value.indexOf("@") == -1 ) 
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
				document.getElementById("erreur").innerHTML="Mauvais format email";
				return false;
		}else{
			return true;
		}
	}
	
	else if(page == "chpass"){
		var nPass=document.forms[0][0].value;
		var cPass=document.forms[0][1].value;
		var aPass=document.forms[0][2].value;
		if ((nPass.length < 6) || (cPass.length < 6) || (aPass.length < 6))
		{
			document.getElementById("erreur").innerHTML="Mauvais format (min 6)";
			return false;
		}else{
			return true;
		}
	}
	
	else if(page == "chlog"){
		var login=document.forms[0][0].value;
		var conf=document.forms[0][1].value;
		if ((login.length < 3) || (conf.length < 3))
		{
			document.getElementById("erreur").innerHTML="Mauvais login";
			return false;
		}else{
			return true;
		}
	}
	
	else if(page == "licences"){
		var element=document.forms["formVal"]["date1"];
		var element3=document.forms["formVal"]["date2"];
		var element2=document.forms["formVal"]["key"];
		if ((element.value != "")
			&&(( element.value.indexOf("/") != 4) 
			|| ( element.value.lastIndexOf("/") != 7 )
			|| (element.value.lastIndexOf("/") != (element.value.length - 3))))
		{
			alert("Mauvais format de Date");
			return false;
		}
		if ((element3.value != "")
			&&(( element3.value.indexOf("/") != 4) 
			|| ( element3.value.lastIndexOf("/") != 7 )
			|| (element3.value.lastIndexOf("/") != (element3.value.length - 3))))
		{
			alert("Mauvais format de Date");
			return false;
		}
		if ((element2.value != "")
			&&(( element2.value.indexOf("-") != 5) 
			|| ( element2.value.lastIndexOf("-") != 29 )
			|| ( element2.value.charAt(11) != "-" )
			|| ( element2.value.charAt(17) != "-" )
			|| ( element2.value.charAt(23) != "-" )
			|| ( element2.value.length != 35))
			)
		{
			alert("Mauvais format de Cle");
			return false;
		}else{
			return true;
		}
	}
	
	else if(page == "downloads"){
		var element=document.forms[0]["time"];
		if ((element.value != "")&&(( element.value.indexOf("/") != 4) 
			|| ( element.value.lastIndexOf("/") != 7 )
			|| (element.value.lastIndexOf("/") != (element.value.length - 3))))
		{
			alert("Mauvais format de Date ");
			return false;
		}else{
			return true;
		}
	}
	
	else if(page == "addOpe"){
		var login=document.forms[0]["login"];
		var mdp=document.forms[0]["pass"];
		var email=document.forms[0]["email"];
		if (login.value.length < 3)
		{
			document.getElementById("erreur").innerHTML="Mauvais format de Login (min 3)";
			return false;
		}
		if (mdp.value.length < 6)
		{
			document.getElementById("erreur").innerHTML="Mauvais format de mot de passe (min 6)";
			return false;
		}
		if (email.value.length < 1){}
		else if (( email.value.indexOf("@") == -1 )
			|| ( email.value.indexOf("@") == 0 )
			|| ( email.value.indexOf("@") != email.value.lastIndexOf("@") ) 
			|| ( email.value.indexOf(".") == email.value.indexOf("@")-1 ) 
			|| ( email.value.indexOf(".") == email.value.indexOf("@") +1 ) 
			|| (email.value.indexOf("@") == email.value.length -1 ) 
			|| (email.value.indexOf (".") == -1) 
			|| ( email.value.lastIndexOf (".") == email.value.length -1 ) 
			|| (email.value.indexOf (" ") != -1) 
			|| ((email.value.indexOf(".") == email.value.lastIndexOf(".")) && (email.value.lastIndexOf(".") < email.value.indexOf("@")))
			)
		{
			document.getElementById("erreur").innerHTML="Mauvais format d'Email";
			return false;
		}else{
			return true;
		}
	}
	
	else if(page == "chOpe"){
		var email=document.forms[0]["email"];
		if (email.value.length < 1){}
		else if ( ( email.value.indexOf("@") == -1 )
			|| ( email.value.indexOf("@") == 0 )
			|| ( email.value.indexOf("@") != email.value.lastIndexOf("@") ) 
			|| ( email.value.indexOf(".") == email.value.indexOf("@")-1 ) 
			|| ( email.value.indexOf(".") == email.value.indexOf("@") +1 ) 
			|| (email.value.indexOf("@") == email.value.length -1 ) 
			|| (email.value.indexOf (".") == -1) 
			|| ( email.value.lastIndexOf (".") == email.value.length -1 ) 
			|| (email.value.indexOf (" ") != -1) 
			|| ((email.value.indexOf(".") == email.value.lastIndexOf(".")) && (email.value.lastIndexOf(".") < email.value.indexOf("@")))
			)
		{
			document.getElementById("erreur").innerHTML="Mauvais format d'Email";
			return false;
		}else{
			return true;
		}
	}
}	