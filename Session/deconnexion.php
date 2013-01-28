<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require "../Add/define.php";
    session_start();
	if (isset($_SESSION['login'])) {
		session_unset();  
		session_destroy();  
	}
	if (isset($_GET['action'])){
		$message = "Veuillez vous connecter";
		if($_GET['action'] == "dec"){$message = "Vous etes déconnecté";}
		if($_GET['action'] == '"co"'){$message = "Veuillez vous connecter";}
		if($_GET['action'] == '"err"'){$message = "Erreur utilisateur inconnu";}
	}else{
		$message = "Veuillez vous connecter";
	}
?> 

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_deconnexion; ?>>
	</head>
	<body>
		<div class="bloc">
			<h3><?php echo $message;?></h3>
			<div class="button"><a href= <?php echo $hr_deconnexion_index; ?> >Ok</a></div>
		</div>
	</body>
</html>