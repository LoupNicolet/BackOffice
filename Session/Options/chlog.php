<!DOCTYPE html>
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['cLogin']) && !empty($_POST['cLogin'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['nLogin']) && !empty($_POST['nLogin']))) {
				if ($_POST['nLogin'] != $_POST['cLogin']) {
					$erreur = 'Les deux login sont differents.';
				}else{
					$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
					mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');
					
					$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_SESSION['login']),'pass_operator',mysql_real_escape_string(md5($_POST['pass'])));

					if ($data[0] == 0){
						$erreur = "Mauvais mot de passe.";
					}else{
						$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_POST['nLogin']),"","");
						if ($data[0] == 0) {
							RequeteSQL_Update('operator', 'login_operator', mysql_real_escape_string($_POST['nLogin']),"","","","", 'login_operator', mysql_real_escape_string($_SESSION['login']), 'pass_operator', mysql_real_escape_string(md5($_POST['pass'])));
							$_SESSION['login'] = $_POST['nLogin'];
							$erreur = 'Changement effectué.';
						}
						else {$erreur = 'Login deja utilisé.';}
						mysql_close();
					}
				}
			}else{
				$erreur = 'Au moins un des champs est vide.';
			}
		}
	}else{
		header ($he_deconnexion);
		exit();
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_chlog; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_valide; ?>></script>
	</head>
	<body>
		<div class="cadre">
			<h3 align="center">Nouveau Login :</h3><br>
			<h4 align="center"><p class="log"><?php echo $_SESSION['login']; ?></p></h4>
			<div id="erreur" class="erreur"><?php if(isset($erreur)) echo $erreur; ?></div>
			<form name="formVal" action= <?php echo $fo_chlog_chlog; ?> method="post" onsubmit="return valide('chlog')" >
				<div class="text"><p>Nouveau :</p><br><p>Confirmer :</p><br><p>Mot de passe :</p></div>
				<div class="input">
					<input class="tf" onkeyup="verif(this,1)" type="text" name="nLogin" value=""><br>
					<input class="tf" onkeyup="verif(this,1)" type="text" name="cLogin" value=""><br>
					<input class="tf" onkeyup="verif(this,2)" type="password" name="pass"><br>
				</div>
				<div class="button"><input class="button" type="submit" name="valider" value="Valider"></div>
			</form>
		</div>
	</body>
</html>