<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);	
			
		$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_GET['log']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$email_ope = $row['email_operator'];
			$prenom_ope = $row['firstName_operator'];
			$nom_ope = $row['lastName_operator'];
		}
		mysql_free_result($req);
		
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
				$erreur = 'Changement effectu�.';
							
				$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_GET['log']).'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
				while($row = mysql_fetch_array($req)){
					$email_ope = $row['email_operator'];
					$prenom_ope = $row['firstName_operator'];
					$nom_ope = $row['lastName_operator'];
				}
				mysql_free_result($req);
			}
			else {$erreur = 'Veulliez remplir au moins un champs.';}
		}
		mysql_close();
	}else{
		header ('Location: /Session/deconnexion.php?action="co"');
		exit();
	}
?>
<html id="PagesFrame">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript'>
			function valide()
			{
				var email=document.forms["formVal"]["email"];
				if (email.value.length < 1){}
				else if ( ( email.value.indexOf("@") == -1 )
					|| ( email.value.indexOf("@") == 0 )
					|| ( email.value.indexOf("@") != email.value.lastIndexOf("@") ) 
					|| ( email.value.indexOf(".") == email.value.indexOf("@")-1 ) 
					|| ( email.value.indexOf(".") == email.value.indexOf("@") +1 ) 
					|| (email.value.indexOf("@") == email.value.length -1 ) 
					|| (email.value.indexOf (".") == -1) 
					|| ( email.value.lastIndexOf (".") == email.value.length -1 ) 
					|| (email.value.indexOf (" ") != -1) 
					|| ((email.value.indexOf(".") == email.value.lastIndexOf(".")) && (email.value.lastIndexOf(".") < email.value.indexOf("@")))
					)
				{
					document.getElementById("erreur").innerHTML="Mauvais format d'Email";
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div style="border:10px outset #245DB2;">
			<table>
				<tr><td colspan="4" align="center"><h3>Profil de <?php echo $_GET['log']; ?> :</h3></td></tr>
				<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
				<form name="formVal" onsubmit="return valide()" action= <?php echo $fo_chOpe_chOpe.$_GET['log']; ?> method="post" >
					<tr>
						<td></td>
						<td align="center"><b>Informations :</b></td>
						<td><b>Modifier :</b></td>
					</tr>
					<tr>
						<td align="right">Nom :</td>
						<td align="center"><?php echo $nom_ope; ?></td>
						<td><input onkeyup="verif(this,3)" type="text" name="nom" value=""></td>
					</tr>
					<tr>
						<td align="right">Prenom :</td>
						<td align="center"><?php echo $prenom_ope; ?></td>
						<td><input onkeyup="verif(this,3)" type="text" name="prenom" value=""></td>
					</tr>
					<tr>
						<td align="right">Email :</td>
						<td align="center"><?php echo $email_ope; ?></td>
						<td><input onkeyup="verif(this,4)" type="text" name="email" value=""></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>
					</tr>
				</form>	
			</table>
		</div>
	</body>
</html>