<?php
	require 'Add/define.php';
	require 'Add/function.php';
	
	if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
		if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {
			
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_POST['login']),'pass_operator',mysql_real_escape_string(md5($_POST['pass'])));
			mysql_close();

			if ($data[0] == 1) {
				session_start();
				$_SESSION['login'] = $_POST['login'];
				$fp = fopen('Add/log.txt', 'a');
				fwrite($fp, 'Connexion de : '.$_SESSION['login'].' ( le '.Date("d/m/Y").' à '.Date("H:i").' )'."\n");
				fclose($fp);
				header('Location: Gestion/gestion.php');
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
		<title>Accueil</title>
		<link rel="stylesheet" type="text/css" href="Add/css.css">
	</head>
	
	<body>
		<table border="6">
			<td>
				<table>
					<tr><h3>Connexion :</h3></tr>
					<?php if(isset($erreur)){echo $erreur;}?>
					<form action="index.php" method="post" >
						<tr>
							<td align="right">Login : </td>
							<td><input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
						</tr>
						<tr>
							<td align="right">Mot de passe :</td>
							<td><input type="password" name="pass" value=""></td>
						</tr>
						<tr><td></td><td><input class="button" type="submit" name="connexion" value="Connexion"></td></tr>
					</form>
				</table>
			</td>
		</table>
	</body>
</html>