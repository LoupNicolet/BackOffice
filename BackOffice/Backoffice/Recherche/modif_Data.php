<?php
	require 'define_Recherche.php';
	
	$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
	mysql_select_db ($SQL_Cdw_name, $base);
	
	if((isset($_POST['id'])) && ($_POST['id'] == "customers")){
		$sql = 'UPDATE '.$_POST['id'].' SET '.$Tab_customers[$_POST['col']].'="'.$_POST["value"].'" WHERE ';
		$first = true;
		for($i=0;$i<(count($Tab_customers)-1);$i++){
			if($_POST['col'] != $i){
				if($first == false){
					$sql = $sql." AND ";
				}
				$first = false;
				if($_POST[$Tab_customers[$i]] != null){
					$sql = $sql.$Tab_customers[$i].'="'.$_POST[$Tab_customers[$i]].'"';
				}else{
					$sql = $sql.$Tab_customers[$i].' IS NULL';
				}
			}
		}	
	}else if((isset($_POST['id'])) && ($_POST['id'] == "productkey")){
		$sql = 'UPDATE '.$_POST['id'].' SET '.$Tab_licences[$_POST['col']].'="'.$_POST["value"].'" WHERE ';
		$first = true;
		for($i=0;$i<count($Tab_licences);$i++){
			if(($_POST['col'] != $i) && ($i != 0) && ($i != 1) && ($i != 4) && ($i != 5)){
				if($first == false){
					$sql = $sql." AND ";
				}
				$first = false;
				if($_POST[$Tab_licences[$i]] != null){
					$sql = $sql.$Tab_licences[$i].'="'.$_POST[$Tab_licences[$i]].'"';
				}else{
					$sql = $sql.$Tab_licences[$i].' IS NULL';
				}
			}
		}	
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