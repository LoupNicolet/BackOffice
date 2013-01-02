<?php
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
			$sql = $sql.' WHERE '.$enrg1.'="'.$valEnrg1.'"';
			if(!empty($enrg2)){
				$sql = $sql.' AND '.$enrg2.'="'.$valEnrg2.'"';
			}
		}
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$retData = mysql_fetch_array($req);
		mysql_free_result($req);
		return $retData;
	}
	
	//Insert un enregistrements pour une table de 3 champs
	function RequeteSQL_Insert_3($table, $val1, $val2, $val3){
		$sql = 'INSERT INTO '.$table.' VALUES("", "'.$val1.'", "'.$val2.'", "'.$val3.'")';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
	}
	
	//verifie si la valeur est renseigné et l'ajoute à la requete SQL
	function ajout_si_existe($value,$table,$sql,$prec){
		if(!empty($_POST[$value])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			$sql = $sql.' '.$table.'="'.$_POST[$value].'"';
			$prec = 1;
		}
		$ret[0] = $sql;
		$ret[1] = $prec;
		return $ret;
	}
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	function recherche_downloads($champ){
		$prec = 0;
		$sql = 'SELECT '.$champ.' FROM downloadkey WHERE';
		
		$ret = ajout_si_existe('time','timestamp',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('email','mail',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('number','downloads',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('logiciel','Application',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		return $sql;
	}
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	function recherche_Customer($champ){
		$prec = 0;
		$sql = 'SELECT '.$champ.' FROM customers WHERE';
		
		$ret = ajout_si_existe('email','Customer_Email',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('name','Customer_Name',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('firstName','Customer_FirstName',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('lastName','Customer_LastName',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('tel','Customer_Telephon',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('mobile','Customer_Mobile',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
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
	
	//Construit la requete SQL en fonction de ce que l'utilisateur a renseigné
	function recherche_Licences($row_Customer_ID){
		$prec = 0;
		$sql = 'SELECT * FROM productkey WHERE';

		if(!empty($_POST['email']) || !empty($_POST['name']) || !empty($_POST['firstName']) || !empty($_POST['lastName']) || !empty($_POST['tel']) || !empty($_POST['mobile'])  || !empty($_POST['type'])){
			$sql = $sql.' CustomerID="'.$row_Customer_ID["Customer_ID"].'"';
			$prec = 1;
		}
		
		$ret = ajout_si_existe('installGuid','InstallGuid',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('installKey','InstallKey',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('label','Label',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		$ret = ajout_si_existe('date','Expiration',$sql,$prec);	$sql=$ret[0];$prec=$ret[1];
		
		/*if(!empty($_POST['date'])){
			if($prec == 1){
				$sql = $sql.' AND';
			}
			if($_POST['operateur_date'] == 'sup'){
				$sql = $sql.' Expiration>="'.$_POST['date'].'"';$prec = 1;
			}else if($_POST['operateur_date'] == 'inf'){
				$sql = $sql.' Expiration<="'.$_POST['date'].'"';$prec = 1;
			}else{
				$sql = $sql.' Expiration="'.$_POST['date'].'"';$prec = 1;
			}
		}*/
		
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
	}
	
	
	//Test si des champs Downloads sont remplit
	function Test_Downloads(){
		if ((isset($_POST['time']) 	&& !empty($_POST['time']))
		|| (isset($_POST['email']) 		&& !empty($_POST['email'])) 
		|| (isset($_POST['downloads']) 	&& !empty($_POST['downloads'])) 
		|| (isset($_POST['number']) 	&& !empty($_POST['number'])) 
		){
			return true;
		}else{
			return false;
		}
	}
	
	
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
	
	//Test si des champs Licences sont remplit
	function Test_Licences(){
		if ((isset($_POST['installGuid'])&& !empty($_POST['installGuid']))
		|| (isset($_POST['installKey']) && !empty($_POST['installKey']))
		|| (isset($_POST['label']) 		&& !empty($_POST['label']))
		|| (isset($_POST['number']) 	&& !empty($_POST['number']))
		|| (isset($_POST['date']) 		&& !empty($_POST['date']))
		){
			return true;
		}else{
			return false;
		}
	}
?>