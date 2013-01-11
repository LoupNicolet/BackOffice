<?php

	require 'Add/define.php';
	require 'Add/function.php';
	
	session_start();
	
	if (isset($_SESSION['login'])) {

		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		$data = RequeteSQL_Select('type_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		if ($data[0] == "admin") {
			$_SESSION['type'] = $data[0];
		}
		else if ($data[0] == null) {
			$_SESSION['type'] = "";
		}
		else{
			header($he_deconnexion_err);
		}

		$data = RequeteSQL_Select('lastName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['lastName'] = $data[0];
		$data = RequeteSQL_Select('firstName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['firstName'] = $data[0];
		mysql_close();
	}else{
		header ($he_deconnexion);
		exit();
	}
?>

<html id="backoffice">
	<head>
		<title>BackOffice</title>
		<link rel="stylesheet" type="text/css" href="Add/css.css">
	</head>

	<body>
		<table border=6>
			<tr>
				<td>
					<table class="menu">
						<tr>
							<!--<td><a class="menu" href= <?php //echo $hr_backoffice_licences; ?> >Licences</a></td>-->
							<td><a class="menu" href= <?php echo $hr_backoffice_customers; ?> >Clients</a></td>
							<td><a class="menu" href= <?php echo $hr_backoffice_downloads; ?> >Telechargements</a></td>
							<td><a class="menu" href= <?php echo $hr_backoffice_product; ?> >Licences</a></td>
							<!--<td><a class="menu" href= <?php //echo $hr_backoffice_partenaires; ?> >Partenaires</a></td>-->
							<td><a class="menu" href= <?php echo $hr_backoffice_operateurs; ?> >Operateurs</a></td>
							<td><a class="menu" href= <?php echo $hr_backoffice_options; ?> >Options</a></td>
							<td align="center"><small><?php echo "[".htmlentities(trim($_SESSION['type']))."]"."[".htmlentities(trim($_SESSION['firstName'])).htmlentities(trim($_SESSION['lastName']))."]";?></small></td>
							<td><a class="deconnexion" href= <?php echo $hr_backoffice_deconnexion; ?> >Deconnexion</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages">
					<?php 	
						if(isset($_GET['action'])){
							//if($_GET['action'] == 'licences'){include($in_backoffice_licences);}
							if($_GET['action'] == 'customers'){include($in_backoffice_customers);}
							else if($_GET['action'] == 'downloads'){include($in_backoffice_downloads);}
							else if($_GET['action'] == 'product'){include($in_backoffice_product);}
							//else if($_GET['action'] == 'partenaires'){include($in_backoffice_partenaires);}
							else if($_GET['action'] == 'operateurs'){include($in_backoffice_operateurs);}
							else if($_GET['action'] == 'options'){include($in_backoffice_options);}
						}
					?> 
				</td>
			</tr>
		</table>
	</body>
</html>