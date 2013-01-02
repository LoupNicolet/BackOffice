<?php
	require '../Add/define.php';
	require '../Add/function.php';
	
	session_start();
	
	if (isset($_SESSION['login'])) {
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		$data = RequeteSQL_Select('type_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		if ($data[0] == null){
			header('Location: ../Session/deconnexion.php?action="err"');
			exit();
		}
		else if ($data[0] == "admin") {
			$_SESSION['type'] = 'admin';
		}
		else{
			header('Location: ../Session/deconnexion.php?action="err"');
		}

		$data = RequeteSQL_Select('lastName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['lastName'] = $data[0];
		$data = RequeteSQL_Select('firstName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['firstName'] = $data[0];
		mysql_close();
	}else{
		header ('Location: ../Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="gestion">
	<head>
		<title>Gestion</title>
		<link rel="stylesheet" type="text/css" href="../Add/css.css">
	</head>

	<body>
		<table border=6>
			<tr>
				<td align="center"><p>Gestion<?php echo " [ ".htmlentities(trim($_SESSION['type']))." ] "." [ ".htmlentities(trim($_SESSION['firstName']))." ".htmlentities(trim($_SESSION['lastName']))." ]";?></p></td>
			</tr>
			<tr>
				<td>
					<table class="menu">
						<tr>
							<td><a class="menu" href="gestion.php?action=licences">Licences</a></td>
							<td><a class="menu" href="gestion.php?action=customers">Clients</a></td>
							<td><a class="menu" href="gestion.php?action=downloads">Telechargements</a></td>
							<td><a class="menu" href="gestion.php?action=product">Produits</a></td>
							<td><a class="deconnexion" href='../Session/deconnexion.php?action="dec"'>Deconnexion</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages">
					<?php 	
						if(isset($_GET['action'])){
							if($_GET['action'] == 'licences'){include('licences.php');}
							else if($_GET['action'] == 'customers'){include('customers.php');}
							else if($_GET['action'] == 'downloads'){include('downloads.php');}
							else if($_GET['action'] == 'product'){include('product.php');}
						}
					?> 
				</td>
			</tr>
		</table>
	</body>
</html>