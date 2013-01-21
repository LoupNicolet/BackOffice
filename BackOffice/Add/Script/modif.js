var debut = 1;
var valeur = "";
var savtd = "";
var colonne = "";
var ligne = "";
var valType = "";
var id = "";

function clic(td,type,row,idc)
{	
	id = idc;
	valType = type;
	if(debut == 1){
		colonne = td.cellIndex
		ligne = row;
		valeur = document.getElementById(""+colonne+ligne).innerHTML;
		savtd = td;
		if(valType==1){
			td.innerHTML='<input id="tfCase" type="text" onkeyup="verif(this,3)" name="case" value=""><input type="button" onclick="valider(this)" name="RcaseV" value="Modifier"><input type="button" onclick="annuler(this)" name="RcaseF" value="Annuler">';
			document.getElementById("tfCase").value=valeur;
		}else if(valType== 2){
			var valeurB = "Client";
			if(valeur == "Client"){
				valeurB = "Prospect";
			}
			td.innerHTML=valeur+'<br>'+'<input id="tfCase" type="button" onclick="valider(this)" name="RcaseV" value='+valeurB+'><input type="button" onclick="annuler(this)" name="RcaseF" value="Annuler">';
		}else if(valType== 3){
			var valeurB = "Active";
			if(valeur == "Active"){
				valeurB = "Desactive";
			}
			td.innerHTML=valeur+'<br>'+'<input id="tfCase" type="button" onclick="valider(this)" name="RcaseV" value='+valeurB+'><input type="button" onclick="annuler(this)" name="RcaseF" value="Annuler">';
		}
		debut = 0;
	}else if(debut==2){
		debut = 1;
	}
}

function annuler(button){
	savtd.innerHTML = valeur;
	debut = 2;
}

			
function valide_Case()
{
	var element=document.getElementById("tfCase");
	if (element.value.length < 1)
	{
		alert("Mauvais format !");
		return false;
	}else{
		return true;
	}
}
		
function valider(button){
	if(valide_Case()){
		var rep=confirm("Modifier "+valeur+" en "+document.getElementById("tfCase").value+" ?");
		if(rep){
			var xmlhttp;
			xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function()
			{
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			  {
				var reponse = xmlhttp.responseText;
				if(valType==2){
					if(reponse == "1"){
						reponse = "Prospect";
					}else{
						reponse = "Client";
					}
				}else if(valType==3){
					if(reponse == "1"){
						reponse = "Desactive";
					}else{
						reponse = "Active";
					}
				}
				savtd.innerHTML = reponse;
				debut = 2;
			  }
			}
			xmlhttp.open("POST","./Backoffice/Recherche/modif_Data.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			requete(xmlhttp);
		}else{
			savtd.innerHTML = valeur;
			debut = 2;
		}
	}
}