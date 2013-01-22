<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require 'Add/define.php';
	require 'Add/function.php';
	date_default_timezone_set("Europe/Paris");
	if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
		if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login,$SQL_Cdw_pass) or die('Erreur Connexion ! '.'<br>'.mysql_error());
			mysql_select_db ($SQL_Cdw_name, $base);
			$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_POST['login']),'pass_operator',mysql_real_escape_string(md5($_POST['pass'])));
			mysql_close();

			if ($data[0] == 1) {
				session_start();
				$_SESSION['login'] = $_POST['login'];
				$fp = fopen($open_index_log, 'a');
				fwrite($fp, 'Connexion de : '.$_SESSION['login'].' ( le '.date("d/m/Y").' à '.date("H:i").' )'."\n");
				fclose($fp);
				header($he_index_backoffice);
				exit();
			}
			elseif ($data[0] == 0) {
				$erreur = 'Compte non reconnu.';
			}
			else {
				$erreur = 'Probème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion.';
			}
		}
		else {
			$erreur = 'Au moins un des champs est vide.';
		}
	}
?>

<html id="index">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href="Add/css.css">
	</head>
	<body>
		<table border="6">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="2" align="center"><h3>Connexion :</h3><td>
						</tr>
						<?php if(isset($erreur)) echo '<tr><td colspan="2" align="center">'.$erreur.'</td></tr>'; ?>
						<form action= <?php echo $fo_index_index; ?> method="post" >
							<tr>
								<td align="right">Login : </td>
								<td><input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
							</tr>
							<tr>
								<td align="right">Mot de passe :</td>
								<td><input type="password" name="pass" value=""></td>
							</tr>
							<tr>
								<td></td>
								<td><input class="button" type="submit" name="connexion" value="Connexion"></td>
							</tr>
						</form>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>