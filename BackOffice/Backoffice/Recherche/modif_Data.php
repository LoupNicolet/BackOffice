<?php
	require 'define_Recherche.php';
	
	$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
	mysql_select_db ($SQL_Cdw_name, $base);
	
	if((isset($_POST['id'])) && ($_POST['id'] == "customers")){
		$sql = 'UPDATE '.$_POST['id'].' SET '.$Tab_customers[$_POST['col']].'="'.$_POST["value"].'" WHERE Customer_ID="'.$_POST["idc"].'"';
	}else if((isset($_POST['id'])) && ($_POST['id'] == "productkey")){
		$sql = 'UPDATE '.$_POST['id'].' SET '.$Tab_licences[$_POST['col']].'="'.$_POST["value"].'" WHERE InstallKey="'.$_POST["InstallKey"].'"';
	}
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	if($req){
		echo $_POST["value"];
	}else{
		if((isset($_POST['id'])) && ($_POST['id'] == "customers")){
			echo $_POST[$Tab_customers[$_POST['col']]];
		}else if((isset($_POST['id'])) && ($_POST['id'] == "productkey")){
			echo $_POST[$Tab_licences[$_POST['col']]];	
		}
	}
	mysql_close();
?>