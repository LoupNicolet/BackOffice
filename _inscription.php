<?php
	require 'define.php';
	require 'function.php';
	
	// on teste si le visiteur a soumis le formulaire
	if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
		if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {
			
			// on teste les deux mots de passe
			if ($_POST['pass'] != $_POST['pass_confirm']) {
				$erreur = 'Les 2 mots de passe sont différents.';
			}
			else {
				$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
				mysql_select_db ($SQL_Cdw_name, $base);
				
				// on recherche si il est enregistré
				$data = RequeteSQL_Select_1('count(*)','customers','Customer_Email',mysql_real_escape_string($_POST['login']));
				mysql_close();
				
				//si il ne l'est pas
				if ($data[0] == 0){
					$erreur = "Vous ne pouvez pas vous inscrir car vous n'etes pas enregistré par Cloudiway, pour cela contacter directement Cloudiway ou bien telecharger une version d'essai sur www.Cloudiway.fr.";
				//si il l'est
				}else{
					$base = mysql_connect ($SQL_serveur, $SQL_login, $SQL_pass);
					mysql_select_db ($SQL_name, $base);

					// on recherche si ce login est déjà utilisé par un autre membre
					$data = RequeteSQL_Select_1('count(*)','membre','login',mysql_real_escape_string($_POST['login']));
					
					//On l'ajoute dans la base
					if ($data[0] == 0) {
						RequeteSQL_Insert_3('membre', mysql_real_escape_string($_POST['login']), mysql_real_escape_string(md5($_POST['pass'])), mktime(0,0,0,date("m"),date("d"),date("Y")));
						session_start();
						$_SESSION['login'] = $_POST['login'];
						header('Location: membre.php');
						exit();
					}
					else {$erreur = 'Vous etes déjà inscrit.';}
					mysql_close();
				}
			}
		}
		else {$erreur = 'Au moins un des champs est vide.';}
	}
?>

<html id="inscription">
	<head>
		<title>Inscription</title>
		<link rel="stylesheet" type="text/css" href="css.css">
	</head>

	<body>
	<table border="6">
			<td>
				<table>
					<tr><h3>Inscription à l'espace membre :</h3></tr>
					<?php if(isset($erreur)) echo $erreur; ?>
					<form action="inscription.php" method="post" >
						<tr>
							<td align="right">Login : </td>
							<td><input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
						</tr>
						<tr>
							<td align="right">Mot de passe :</td>
							<td><input type="password" name="pass" value=""></td>
						</tr>
						<tr>
							<td align="right">Confirmation du mot de passe :</td>
							<td><input type="password" name="pass_confirm"></td>
						</tr>
						<td><a href="index.php">Retour</a></td>
						<td><input class="button" type="submit" name="inscription" value="Inscription"></td>
					</form>
					
				</table>
			</td>
		</table>
	</body>
</html>