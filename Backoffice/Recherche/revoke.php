<?php
	require 'define_Recherche.php';
	
	$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
	mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');
	if((isset($_POST['id'])) && ($_POST['id'] == "downloads")){ 
		if((isset($_POST['type'])) && ($_POST['type'] == "Suppr")){
			$sql = 'INSERT INTO revoked_dl  (Rev_UniqueID) VALUES ("'.mysql_real_escape_string($_POST["Rev"]).'")';
		}else if((isset($_POST['type'])) && ($_POST['type'] == "Rest")){
			$sql = 'DELETE FROM revoked_dl  WHERE Rev_UniqueID="'.mysql_real_escape_string($_POST["Rev"]).'"';
		}
	}
	else if((isset($_POST['id'])) && ($_POST['id'] == "customers")){
		if((isset($_POST['type'])) && ($_POST['type'] == "Suppr")){
			$sql = 'INSERT INTO revoked_cu  (Rev_CustomerID) VALUES ("'.mysql_real_escape_string($_POST["Rev"]).'")';
		}else if((isset($_POST['type'])) && ($_POST['type'] == "Rest")){
			$sql = 'DELETE FROM revoked_cu  WHERE Rev_CustomerID="'.mysql_real_escape_string($_POST["Rev"]).'"';
		}
	}
	$req = mysql_query($sql) or die('Erreur SQL !');
	if($req){
		if((isset($_POST['type'])) && ($_POST['type'] == "Suppr")){
			echo "1";
		}else if((isset($_POST['type'])) && ($_POST['type'] == "Rest")){
			echo "2";
		}else{
			echo "0";
		}
	}else{
		echo "0";
	}
	mysql_close();
?>