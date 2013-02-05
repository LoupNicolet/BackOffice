<!DOCTYPE html>
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');	
			
		$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_GET['log']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !');
		while($row = mysql_fetch_array($req)){
			$email_ope = $row['email_operator'];
			$prenom_ope = $row['firstName_operator'];
			$nom_ope = $row['lastName_operator'];
		}
		mysql_free_result($req);
		$y=0;
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['nom']) && !empty($_POST['nom'])) || (isset($_POST['prenom']) && !empty($_POST['prenom'])) || (isset($_POST['email']) && !empty($_POST['email']))) {
				
				$nEmail = $email_ope;
				$nNom = $nom_ope;
				$nPrenom = $prenom_ope;
				
				if(!empty($_POST['nom'])){
					$nNom = $_POST['nom'];
				}
				if(!empty($_POST['prenom'])){
					$nPrenom = $_POST['prenom'];
				}
				if(!empty($_POST['email'])){
					$nEmail = $_POST['email'];
				}
				
				RequeteSQL_Update('operator', 'email_operator', mysql_real_escape_string($nEmail), 'firstName_operator', mysql_real_escape_string($nPrenom), 'lastName_operator', mysql_real_escape_string($nNom), 'login_operator', mysql_real_escape_string($_GET['log']),"","");
				$erreur = 'Changement effectué.';
							
				$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_GET['log']).'"';
				$req = mysql_query($sql) or die('Erreur SQL !');
				while($row = mysql_fetch_array($req)){
					$email_ope = $row['email_operator'];
					$prenom_ope = $row['firstName_operator'];
					$nom_ope = $row['lastName_operator'];
					$y++;
				}
				mysql_free_result($req);
			}
			else {$erreur = 'Veulliez remplir au moins un champs.';}
		}
		mysql_close();
	}else{
		header ($he_deconnexion);
		exit();
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_chOpe; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_valide; ?>></script>
	</head>
	<body>
		<div class="cadre">
			<h3 align="center">Profil de <?php echo $_GET['log']; ?> :</h3>
			<div id="erreur" class="erreur"><?php if(isset($erreur)) echo $erreur; ?></div>
			<form onsubmit="return valide('chprofil')" action= <?php echo $fo_chOpe_chOpe.$_GET['log']."'"; ?> method="post" >
				<div class="text"><p>Nom :</p><br><p>Prenom :</p><br><p>Email :</p></div>
				<div class="info"><p><?php echo $nom_ope."<br>"; ?></p><br><p><?php echo $prenom_ope."<br>"; ?></p><br><p><?php  echo $email_ope."<br>"; ?></p></div>
				<div class="input">
					<input class="tf" onkeyup="verif(this,3)" type="text" name="nom" value=""><br>
					<input class="tf" onkeyup="verif(this,3)" type="text" name="prenom" value=""><br>
					<input class="tf" onkeyup="verif(this,4)" type="text" name="email" value=""><br>
				</div>
				<div class="button"><input class="button" type="submit" name="valider" value="Valider"></div>
			</form>	
		</div>
	</body>
</html>