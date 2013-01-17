var sens = false;
var cur_col = -1;
			
function dASC(a, b){
	return(a[1] - b[1]);
}

function dDESC(a, b){
	return(b[1] - a[1]);
}

function ASC(a, b){
	var x = parseInt(a[1], 10);
	var y = parseInt(b[1], 10);
	var c1 = replaceSpec(a[1]);
	var c2 = replaceSpec(b[1]);

	if (isNaN(x) || isNaN(y)){
		if (c1 > c2){
			return 1;
		} else if(c1 < c2){
			return -1;
		} else {
			return 0;
		}
	} else {
		return(a[1] - b[1]);
	}
}

function DESC(a, b){
	var x = parseInt(a[1], 10);
	var	y = parseInt(b[1], 10);
	var	c1 = replaceSpec(a[1]);
	var	c2 = replaceSpec(b[1]);

	if (isNaN(x) || isNaN(y)){
		if (c1 > c2){
			return -1;
		} else if(c1 < c2){
			return 1;
		} else {
			return 0;
		}
	} else {
		return(b[1] - a[1]);
	}
}


 
function sortTable(colonne, type, table){
	var ordre;
	if(cur_col != colonne){sens = false;}
	
	if(sens){
		if(type){ordre = DESC;}
		else{ordre = dDESC;}
		sens = false;
		cur_col = colonne;
	}
	else{
		if(type){ordre = ASC;}
		else{ordre = dASC;}
		sens = true;
		cur_col = colonne;
	}
	
	var mybody = document.getElementById(table).getElementsByTagName('tbody')[0];
	var lignes = mybody.getElementsByTagName('tr');
	
	var tamp = new Array();
	var z = new Array();
	var i=0;
	z.length=0;
	tamp.length=0;
	while(lignes[++i]){
		  if(type){
			tamp.push([lignes[i],lignes[i].getElementsByTagName('td')[colonne].innerHTML]);
		  }else{
			  var date = new Date(lignes[i].getElementsByTagName('td')[colonne].innerHTML);
			  tamp.push([lignes[i],date.getTime()]);
			  delete date;
		  }
	}
	
	tamp.sort(ordre);
	
	var j=0;
	while(tamp[++j]){
		mybody.appendChild(tamp[j][0]);
	}
}

function replaceSpec(Texte){
	var TabSpec = {"à":"a","á":"a","â":"a","ã":"a","ä":"a","å":"a","ò":"o","ó":"o","ô":"o","õ":"o","ö":"o","ø":"o","è":"e","é":"e","ê":"e","ë":"e","ç":"c","ì":"i","í":"i","î":"i","ï":"i","ù":"u","ú":"u","û":"u","ü":"u","ÿ":"y","ñ":"n","-":" ","_":" "},
	reg=/[àáâãäåòóôõöøèéêëçìíîïùúûüÿñ_-]/gi;
	return Texte.replace(reg,function(){return TabSpec[arguments[0].toLowerCase()];}).toLowerCase();
}