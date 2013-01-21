<?php
	date_default_timezone_set("Europe/Paris");
	
	//Recheche des enregistrements pour une table et plusieurs arguments
	function RequeteSQL_Select($recherche, $table, $enrg1, $valEnrg1, $enrg2, $valEnrg2){
		$sql = 'SELECT ';
		if(!empty($recherche)){
			$sql = $sql.$recherche;
		}else{
			$sql = $sql.'*';
		}
		$sql = $sql.' FROM ';
		$sql = $sql.$table;
		if(!empty($enrg1)){
			$sql = $sql.' WHERE '.$enrg1.' LIKE "%'.$valEnrg1.'%"';
			if(!empty($enrg2)){
				$sql = $sql.' AND '.$enrg2.' LIKE "%'.$valEnrg2.'%"';
			}
		}
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$retData = mysql_fetch_array($req);
		mysql_free_result($req);
		return $retData;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	function RequeteSQL_Update($table, $att1, $val1, $att2, $val2, $att3, $val3, $enrg1, $valEnrg1, $enrg2, $valEnrg2){
		$sql = 'UPDATE '.$table.' SET '.$att1.'="'.$val1.'"';
		if(!empty($att2)){
			$sql = $sql.', '.$att2.'="'.$val2.'"'; 
		}
		if(!empty($att3)){
			$sql = $sql.', '.$att3.'="'.$val3.'"';
		}
		$sql = $sql.' WHERE '.$enrg1.'="'.$valEnrg1.'"';
		if(!empty($enrg2)){
			$sql = $sql.' AND '.$enrg2.'="'.$valEnrg2.'"';
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
			$sql = $sql.' '.$table.' LIKE "%'.$_POST[$value].'%"';
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
			$sql = $sql.' '.$table.' = "'.$_POST[$value].'"';
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
					LEFT JOIN keyactivityca AS K ON Pk.InstallKey = K.ProductKey
					LEFT JOIN products AS P ON Pk.ProductID = P.Product_ID
					LEFT JOIN customers AS C ON Pk.CustomerID = C.Customer_ID
					LEFT JOIN keyusage AS Ku ON Pk.RowID = Ku.KeyRowID
				WHERE ';
		
		if(!empty($_POST['label'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' Pk.Label LIKE "%'.$_POST['label'].'%"';
			$prec = 1;
		}
				
		if(!empty($_POST['client'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' C.Customer_Name LIKE "%'.$_POST['client'].'%"';
			$prec = 1;
		}
				
		if(!empty($_POST['key'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' K.ProductKey = "'.$_POST['key'].'"';
			$prec = 1;
		}
		
		if($_POST['logiciel'] != 'tous'){
			if($prec == 1){
					$sql = $sql.' AND';
				}
			if($_POST['logiciel'] == "CloudXFer"){
					$_POST['logiciel'] = "CloudMailMover";
			}
			$sql = $sql.' P.Product_Name = "'.$_POST['logiciel'].'"';
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
			$sql = $sql.' Pk.Revoked = "'.$etat.'"';
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
			$sql = $sql.' C.Customer_Prospect = "'.$type.'"';
			$prec = 1;
		}
		
		if(!empty($_POST['number'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_nombre'] == 'sup'){
				$sql = $sql.' K.NumUsers>="'.$_POST['number'].'"';
			}else if($_POST['operateur_nombre'] == 'inf'){
				$sql = $sql.' K.NumUsers<="'.$_POST['number'].'"';
			}else{
				$sql = $sql.' K.NumUsers="'.$_POST['number'].'"';
			}
			$prec = 1;
		}
		
		if(!empty($_POST['numberL'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_nombreL'] == 'sup'){
				$sql = $sql.' Pk.Licences>="'.$_POST['numberL'].'"';
			}else if($_POST['operateur_nombreL'] == 'inf'){
				$sql = $sql.' Pk.Licences<="'.$_POST['numberL'].'"';
			}else{
				$sql = $sql.' Pk.Licences="'.$_POST['numberL'].'"';
			}
			$prec = 1;
		}
		
		if(!empty($_POST['date1'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$_POST['date1'] = strtotime($_POST['date1']);
			if($_POST['operateur_date1'] == 'sup'){
				$sql = $sql.' Ku.InitialisationDate>="'.$_POST['date1'].'"';$prec = 1;
			}else if($_POST['operateur_date1'] == 'inf'){
				$sql = $sql.' Ku.InitialisationDate<="'.($_POST['date1']+3600*24).'"';$prec = 1;
			}else{
				$sql = $sql.' Ku.InitialisationDate>="'.($_POST['date1']).'" AND Ku.InitialisationDate<="'.($_POST['date1']+3600*24).'"';$prec = 1;
			}
			$_POST['date1'] = date("Y/m/d",$_POST['date1']);
		}
		if(!empty($_POST['date2'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$_POST['date2'] = strtotime($_POST['date2']);
			if($_POST['operateur_date2'] == 'sup'){
				$sql = $sql.' K.KeyActivity_Date>="'.$_POST['date2'].'"';$prec = 1;
			}else if($_POST['operateur_date2'] == 'inf'){
				$sql = $sql.' K.KeyActivity_Date<="'.($_POST['date2']+3600*24).'"';$prec = 1;
			}else{
				$sql = $sql.' K.KeyActivity_Date>="'.($_POST['date2']).'" AND K.KeyActivity_Date<="'.($_POST['date2']+3600*24).'"';$prec = 1;
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
		$sql = 'SELECT '.$champ.' FROM downloadkey WHERE';
		
		$ret = ajout_si_existe_like('email','mail',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		//$ret = ajout_si_existe('number','downloads',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		if($_POST['number'] != 'Tous'){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['number'] == 'Non-Telecharge'){
				$sql = $sql.' downloads="0"';$prec = 1;
			}else if($_POST['number'] == 'Telecharge'){
				$sql = $sql.' downloads="1" OR downloads="2"';$prec = 1;
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
				$sql = $sql.' Application="'.$logiciel.'"';
				$prec = 1;
			}
			
		}
		
		if(!empty($_POST['time'])){
			$_POST['time'] = strtotime($_POST['time']);
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_date'] == 'sup'){
				$sql = $sql.' timestamp>="'.$_POST['time'].'"';$prec = 1;
			}else if($_POST['operateur_date'] == 'inf'){
				$sql = $sql.' timestamp<="'.($_POST['time']+3600*24).'"';$prec = 1;
			}else{
				$sql = $sql.' timestamp>="'.($_POST['time']).'" AND timestamp<="'.($_POST['time']+3600*24).'"';$prec = 1;
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
		$sql = 'SELECT '.$champ.' FROM customers WHERE';
		
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
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	/*function recherche_Licences($row_Customer_ID){
		$prec = 0;
		$sql = 'SELECT * FROM productkey WHERE';

		if(!empty($_POST['email']) || !empty($_POST['name']) || !empty($_POST['firstName']) || !empty($_POST['lastName']) || !empty($_POST['tel']) || !empty($_POST['mobile'])  || !empty($_POST['type'])){
			$sql = $sql.' CustomerID="'.$row_Customer_ID["Customer_ID"].'"';
			$prec = 1;
		}
		
		//$ret = ajout_si_existe('installGuid','InstallGuid',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('installKey','InstallKey',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe_like('label','Label',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('date','Expiration',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		if(!empty($_POST['number'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_nombre'] == 'sup'){
				$sql = $sql.' Licences>="'.$_POST['number'].'"';$prec = 1;
			}else if($_POST['operateur_nombre'] == 'inf'){
				$sql = $sql.' Licences<="'.$_POST['number'].'"';$prec = 1;
			}else{
				$sql = $sql.' Licences="'.$_POST['number'].'"';$prec = 1;
			}
		}
	
		if($_POST['etat'] != 'indifferent'){
			if($_POST['etat'] == 'valide'){
				if($prec == 1){
					$sql = $sql.' AND';
				}
				$sql = $sql.' Revoked="0"';$prec = 1;
			}else{
				if($prec == 1){
					$sql = $sql.' AND';
				}
				$sql = $sql.' Revoked="1"';$prec = 1;
			}
		}
		
		if($_POST['logiciel'] != 'tous'){
			include 'define.php';
			mysql_close();
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			$productID = RequeteSQL_Select('Product_ID', 'products', 'Product_Name',$_POST["logiciel"],"","");
			
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' ProductID="'.$productID[0].'"';$prec = 1;	
		}
		
		return $sql;
	}*/
	
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
	
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	
	//Test si des champs Licences sont remplit
	/*function Test_Licences(){
		if (/*(isset($_POST['installGuid'])&& !empty($_POST['installGuid']))
		|| *//*(isset($_POST['installKey']) && !empty($_POST['installKey']))
		|| (isset($_POST['label']) 		&& !empty($_POST['label']))
		|| (isset($_POST['number']) 	&& !empty($_POST['number']))
		|| (isset($_POST['date']) 		&& !empty($_POST['date']))
		){
			return true;
		}else{
			return false;
		}
	}*/
?>