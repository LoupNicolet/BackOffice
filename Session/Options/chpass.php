<!DOCTYPE html>
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['cPass']) && !empty($_POST['cPass'])) && (isset($_POST['aPass']) && !empty($_POST['aPass'])) && (isset($_POST['nPass']) && !empty($_POST['nPass']))) {
				
				if ($_POST['cPass'] != $_POST['nPass']) {
					$erreur = 'Les deux mots de passe sont differents.';
				}else 
				{
					$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
					mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');

					$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_SESSION['login']),'pass_operator',mysql_real_escape_string(md5($_POST['aPass'])));

					if ($data[0] == 0){
						$erreur = "Mauvais mot de passe.";
					}else{
						RequeteSQL_Update('operator', 'pass_operator', mysql_real_escape_string(md5($_POST['nPass'])),"","","","", 'login_operator', mysql_real_escape_string($_SESSION['login']), 'pass_operator', mysql_real_escape_string(md5($_POST['aPass'])));
						$erreur = 'Changement effectué.';
						mysql_close();
					}
				}
			}
			else {$erreur = 'Au moins un des champs est vide.';}
		}
	}else{
		header ($he_deconnexion);
		exit();
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_chpass; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_valide; ?>></script>
	</head>
	<body>
		<div class="cadre">
			<h3 align="center">Nouveau Mdp :</h3>
			<div id="erreur" class="erreur"><?php if(isset($erreur)) echo $erreur; ?></div>
			<form onsubmit="return valide('chpass')" action= <?php echo $fo_chpass_chpass; ?> method="post" >
				<div class="text"><p>Nouveau :</p><br><p>Confirmer :</p><br><p>Ancien :</p></div>
				<div class="input">
					<input class="tf" onkeyup="verif(this,2)" type="password" name="nPass"><br>
					<input class="tf" onkeyup="verif(this,2)" type="password" name="cPass"><br>
					<input class="tf" onkeyup="verif(this,2)" type="password" name="aPass"><br>
				</div>
				<div class="button"><input class="button" type="submit" name="valider" value="Valider"></div>
			</form>
		</div>
	</body>
</html>