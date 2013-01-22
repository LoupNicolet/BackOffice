<?php
	date_default_timezone_set("Europe/Paris");
	
	//Recheche des enregistrements pour une table et plusieurs arguments
	function RequeteSQL_Select($recherche, $table, $enrg1, $valEnrg1, $enrg2, $valEnrg2){
		$sql = 'SELECT ';
		if(!empty($recherche)){
			$sql = $sql.mysql_real_escape_string($recherche);
		}else{
			$sql = $sql.'*';
		}
		$sql = $sql.' FROM ';
		$sql = $sql.mysql_real_escape_string($table);
		if(!empty($enrg1)){
			$sql = $sql.' WHERE '.mysql_real_escape_string($enrg1).' LIKE "%'.mysql_real_escape_string($valEnrg1).'%"';
			if(!empty($enrg2)){
				$sql = $sql.' AND '.mysql_real_escape_string($enrg2).' LIKE "%'.mysql_real_escape_string($valEnrg2).'%"';
			}
		}
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		$retData = mysql_fetch_array($req);
		mysql_free_result($req);
		return $retData;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	function RequeteSQL_Update($table, $att1, $val1, $att2, $val2, $att3, $val3, $enrg1, $valEnrg1, $enrg2, $valEnrg2){
		$sql = 'UPDATE '.mysql_real_escape_string($table).' SET '.mysql_real_escape_string($att1).'="'.mysql_real_escape_string($val1).'"';
		if(!empty($att2)){
			$sql = $sql.', '.mysql_real_escape_string($att2).'="'.mysql_real_escape_string($val2).'"'; 
		}
		if(!empty($att3)){
			$sql = $sql.', '.mysql_real_escape_string($att3).'="'.mysql_real_escape_string($val3).'"';
		}
		$sql = $sql.' WHERE '.mysql_real_escape_string($enrg1).'="'.mysql_real_escape_string($valEnrg1).'"';
		if(!empty($enrg2)){
			$sql = $sql.' AND '.mysql_real_escape_string($enrg2).'="'.mysql_real_escape_string($valEnrg2).'"';
		}
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//verifie si la valeur est renseigné et l'ajoute à la requete SQL (avec opérateur LIKE)
	function ajout_si_existe_like($value,$table,$sql,$prec){
		if(!empty($_POST[$value])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' '.mysql_real_escape_string($table).' LIKE "%'.mysql_real_escape_string($_POST[$value]).'%"';
			$prec = 1;
		}
		$ret[0] = $sql;
		$ret[1] = $prec;
		return $ret;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//verifie si la valeur est renseigné et l'ajoute à la requete SQL (avec opérateur =)
	function ajout_si_existe($value,$table,$sql,$prec){
		if(!empty($_POST[$value])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' '.mysql_real_escape_string($table).' = "'.mysql_real_escape_string($_POST[$value]).'"';
			$prec = 1;
		}
		$ret[0] = $sql;
		$ret[1] = $prec;
		return $ret;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	function recherche_licences(){
		$prec = 0;
		$sql = 'SELECT 
					C.Customer_ID AS ID,
					P.Product_Name AS Logiciel,
					max(K.KeyActivity_Date) AS Date,
					Ku.InitialisationDate AS Date2,
					C.Customer_Name AS Client,
					Pk.Label AS Label,
					Pk.Licences AS Licences,
					Pk.Revoked AS Etat,
					Pk.InstallKey AS Cle	
				FROM
					productkey AS Pk
					LEFT JOIN keyactivityCA AS K ON Pk.InstallKey = K.ProductKey
					LEFT JOIN products AS P ON Pk.ProductID = P.Product_ID
					LEFT JOIN customers AS C ON Pk.CustomerID = C.Customer_ID
					LEFT JOIN keyusage AS Ku ON Pk.RowID = Ku.KeyRowID
				WHERE ';
		
		if(!empty($_POST['label'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' Pk.Label LIKE "%'.mysql_real_escape_string($_POST['label']).'%"';
			$prec = 1;
		}
				
		if(!empty($_POST['client'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' C.Customer_Name LIKE "%'.mysql_real_escape_string($_POST['client']).'%"';
			$prec = 1;
		}
				
		if(!empty($_POST['key'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' K.ProductKey = "'.mysql_real_escape_string($_POST['key']).'"';
			$prec = 1;
		}
		
		if($_POST['logiciel'] != 'tous'){
			if($prec == 1){
					$sql = $sql.' AND';
				}
			if($_POST['logiciel'] == "CloudXFer"){
					$_POST['logiciel'] = "CloudMailMover";
			}
			$sql = $sql.' P.Product_Name = "'.mysql_real_escape_string($_POST['logiciel']).'"';
			$prec = 1;
		}
		
		if($_POST['etat'] != 'Indifferent'){
			if($prec == 1){
					$sql = $sql.' AND';
				}
			if($_POST['etat'] == "Active"){
				$etat = "0";
			}else{
				$etat = "1";
			}
			$sql = $sql.' Pk.Revoked = "'.mysql_real_escape_string($etat).'"';
			$prec = 1;
		}
		
		if($_POST['type'] != 'Indifferent'){
			if($prec == 1){
					$sql = $sql.' AND';
				}
			if($_POST['type'] == "Client"){
				$type = "0";
			}else{
				$type = "1";
			}
			$sql = $sql.' C.Customer_Prospect = "'.mysql_real_escape_string($type).'"';
			$prec = 1;
		}
		
		if(!empty($_POST['number'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_nombre'] == 'sup'){
				$sql = $sql.' K.NumUsers>="'.mysql_real_escape_string($_POST['number']).'"';
			}else if($_POST['operateur_nombre'] == 'inf'){
				$sql = $sql.' K.NumUsers<="'.mysql_real_escape_string($_POST['number']).'"';
			}else{
				$sql = $sql.' K.NumUsers="'.mysql_real_escape_string($_POST['number']).'"';
			}
			$prec = 1;
		}
		
		if(!empty($_POST['numberL'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_nombreL'] == 'sup'){
				$sql = $sql.' Pk.Licences>="'.mysql_real_escape_string($_POST['numberL']).'"';
			}else if($_POST['operateur_nombreL'] == 'inf'){
				$sql = $sql.' Pk.Licences<="'.mysql_real_escape_string($_POST['numberL']).'"';
			}else{
				$sql = $sql.' Pk.Licences="'.mysql_real_escape_string($_POST['numberL']).'"';
			}
			$prec = 1;
		}
		
		if(!empty($_POST['date1'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$_POST['date1'] = strtotime($_POST['date1']);
			if($_POST['operateur_date1'] == 'sup'){
				$sql = $sql.' Ku.InitialisationDate>="'.mysql_real_escape_string($_POST['date1']).'"';$prec = 1;
			}else if($_POST['operateur_date1'] == 'inf'){
				$sql = $sql.' Ku.InitialisationDate<="'.mysql_real_escape_string(($_POST['date1']+3600*24)).'"';$prec = 1;
			}else{
				$sql = $sql.' Ku.InitialisationDate>="'.mysql_real_escape_string($_POST['date1']).'" AND Ku.InitialisationDate<="'.mysql_real_escape_string(($_POST['date1']+3600*24)).'"';$prec = 1;
			}
			$_POST['date1'] = date("Y/m/d",$_POST['date1']);
		}
		if(!empty($_POST['date2'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$_POST['date2'] = strtotime($_POST['date2']);
			if($_POST['operateur_date2'] == 'sup'){
				$sql = $sql.' K.KeyActivity_Date>="'.mysql_real_escape_string($_POST['date2']).'"';$prec = 1;
			}else if($_POST['operateur_date2'] == 'inf'){
				$sql = $sql.' K.KeyActivity_Date<="'.mysql_real_escape_string(($_POST['date2']+3600*24)).'"';$prec = 1;
			}else{
				$sql = $sql.' K.KeyActivity_Date>="'.mysql_real_escape_string($_POST['date2']).'" AND K.KeyActivity_Date<="'.mysql_real_escape_string($_POST['date2']+3600*24).'"';$prec = 1;
			}
			$_POST['date2'] = date("Y/m/d",$_POST['date2']);
		}
		$sql = $sql.'GROUP BY Pk.InstallKey';
		return $sql;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	function recherche_downloads($champ){
		$prec = 0;
		$sql = 'SELECT '.mysql_real_escape_string($champ).' FROM downloadkey WHERE';
		
		$ret = ajout_si_existe_like('email','mail',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];

		if($_POST['number'] != 'Tous'){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['number'] == 'Non-Telecharge'){
				$sql = $sql.' downloads="0"';$prec = 1;
			}else if($_POST['number'] == 'Telecharge'){
				$sql = $sql.' ( downloads="1" OR downloads="2" )';$prec = 1;
			}
		}
		
		if($_POST['logiciel'] != 'tous'){
			if(!empty($_POST['logiciel'])){
				if($prec == 1){
					$sql = $sql.' AND';
				}
				$logiciel = $_POST['logiciel'];
				if($logiciel == 'BCP Anywhere'){
					$logiciel = 'BCPAnywhere';
				}
				$sql = $sql.' Application="'.mysql_real_escape_string($logiciel).'"';
				$prec = 1;
			}
			
		}
		
		if(!empty($_POST['time'])){
			$_POST['time'] = strtotime($_POST['time']);
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_date'] == 'sup'){
				$sql = $sql.' timestamp>="'.mysql_real_escape_string($_POST['time']).'"';$prec = 1;
			}else if($_POST['operateur_date'] == 'inf'){
				$sql = $sql.' timestamp<="'.mysql_real_escape_string($_POST['time']+3600*24).'"';$prec = 1;
			}else{
				$sql = $sql.' timestamp>="'.mysql_real_escape_string($_POST['time']).'" AND timestamp<="'.mysql_real_escape_string($_POST['time']+3600*24).'"';$prec = 1;
			}
			$_POST['time'] = date("Y/m/d",$_POST['time']);
		}
		return $sql;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	function recherche_Customer($champ){
		$prec = 0;
		$sql = 'SELECT '.mysql_real_escape_string($champ).' FROM customers WHERE';
		
		$ret = ajout_si_existe_like('email','Customer_Email',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('name','Customer_Name',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('firstName','Customer_FirstName',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('lastName','Customer_LastName',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('tel','Customer_Telephon',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('mobile','Customer_Mobile',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		if($_POST['type'] != 'indifferent'){
			if($_POST['type'] == 'client'){
				if($prec == 1){
					$sql = $sql.' AND';
				}
				$sql = $sql.' Customer_Prospect="0"';$prec = 1;
			}else{
				if($prec == 1){
					$sql = $sql.' AND';
				}
				$sql = $sql.' Customer_Prospect="1"';$prec = 1;
			}
		}
		return $sql;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Test si des champs product sont remplit
	function Test_Licences(){
		if ((isset($_POST['date1']) 	&& !empty($_POST['date1']))
		|| (isset($_POST['date2']) 		&& !empty($_POST['date2']))
		|| (isset($_POST['key']) 		&& !empty($_POST['key']))
		|| (isset($_POST['client']) 	&& !empty($_POST['client'])) 
		|| (isset($_POST['label']) 		&& !empty($_POST['label']))
		|| (isset($_POST['numberL']) 	&& !empty($_POST['numberL']))		
		|| (isset($_POST['number']) 	&& !empty($_POST['number']))
		){
			return true;
		}else{
			return false;
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Test si des champs Downloads sont remplit
	function Test_Downloads(){
		if ((isset($_POST['time']) 		&& !empty($_POST['time']))
		|| (isset($_POST['email']) 		&& !empty($_POST['email'])) 
		|| (isset($_POST['downloads']) 	&& !empty($_POST['downloads']))  
		){
			return true;
		}else{
			return false;
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Test si des champs Customer sont remplit
	function Test_Customer(){
		if ((isset($_POST['email']) 	&& !empty($_POST['email']))
		|| (isset($_POST['name']) 		&& !empty($_POST['name'])) 
		|| (isset($_POST['firstName']) 	&& !empty($_POST['firstName'])) 
		|| (isset($_POST['lastName']) 	&& !empty($_POST['lastName'])) 
		|| (isset($_POST['tel']) 		&& !empty($_POST['tel'])) 
		|| (isset($_POST['mobile']) 	&& !empty($_POST['mobile']))
		){
			return true;
		}else{
			return false;
		}
	}
?>