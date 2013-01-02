<?php

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
		<link rel="stylesheet" type="text/css" href="css.css">
	</head>

	<body>
	<table border="6">
			<td>
				<table align="center">
					<tr><h3><?php echo $message;?></h3></tr>
					<td><a href="index.php">Ok</a></td>
				</table>
			</td>
		</table>
	</body>
</html>

