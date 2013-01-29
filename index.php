<!DOCTYPE html>
<?php
	require 'Add/define.php';
	require 'Add/function.php';
	date_default_timezone_set("Europe/Paris");
	if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
		if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login,$SQL_Cdw_pass) or die('Erreur Connexion SQL !');
			mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');
			$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_POST['login']),'pass_operator',mysql_real_escape_string(md5($_POST['pass'])));
			mysql_close();

			if ($data[0] == 1) {
				session_start();
				$_SESSION['login'] = $_POST['login'];
				$fp = fopen($open_index_log.'E', 'a');
				fwrite($fp, 'Connexion de : '.$_SESSION['login'].' ( le '.date("d/m/Y").' � '.date("H:i").' )'."\n");
				fclose($fp);
				header($he_index_backoffice);
				exit();
			}
			else if ($data[0] == 0) {
				$erreur = 'Compte non reconnu.';
				$fp = fopen($open_index_log, 'a');
				fwrite($fp, "\t".'Erreur Connexion de : '.$_POST['login'].' ( le '.date("d/m/Y").' � '.date("H:i").' )'."\n");
				fclose($fp);
			}
			else {
				$erreur = 'Conflit dans la base de donn�es.';
			}
		}
		else {
			$erreur = 'Au moins un des champs est vide.';
		}
	}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_index; ?>>
	</head>
	<body>
		<div class="bloc">
			<h3>Connexion :</h3>
			<?php if(isset($erreur)) echo $erreur; ?>
			<form action= <?php echo $fo_index_index; ?> method="post" >
				<div class="text">Login :<br>Mot de passe :</div>
				<div class="input">
					<input class="tf" type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>">
					<input class="tf" type="password" name="pass" value="">
				</div>
				<div class="button"><input class="button" type="submit" name="connexion" value="Connexion"></div>
			</form>
		</div>
	</body>
</html>