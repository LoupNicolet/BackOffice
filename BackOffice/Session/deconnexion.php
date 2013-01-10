<?php
	require "../Add/define.php";
    session_start();
	if (isset($_SESSION['login'])) {
		session_unset();  
		session_destroy();  
	}
	if (isset($_GET['action'])){
		if($_GET['action'] == '"dec"'){$message = "Vous etes déconnecté";}
		if($_GET['action'] == '"co"'){$message = "Veuillez vous connecter";}
		if($_GET['action'] == '"err"'){$message = "Erreur utilisateur inconnu";}
	}else{
		$message = "Veuillez vous connecter";
	}
?> 

<html id="deconnexion">
	<head>
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href="../Add/css.css">
	</head>

	<body>
	<table border="6">
			<td>
				<table align="center">
					<tr><td colspan="2" align="center"><h3><?php echo $message;?></h3></td></tr>
					<td align="center"><a href= <?php echo $hr_deconnexion_index; ?> >Ok</a></td>
				</table>
			</td>
		</table>
	</body>
</html>

