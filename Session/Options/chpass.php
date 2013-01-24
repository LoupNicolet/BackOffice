<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
					mysql_select_db ($SQL_Cdw_name, $base);

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
		header ('Location: /Session/deconnexion.php?action="co"');
		exit();
	}
?>
<html id="PagesFrame">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript'>
			function valide()
			{
				var nPass=document.forms["formVal"]["nPass"].value;
				var cPass=document.forms["formVal"]["cPass"].value;
				var aPass=document.forms["formVal"]["aPass"].value;
				if ((nPass.length < 6) || (cPass.length < 6) || (aPass.length < 6))
				{
					document.getElementById("erreur").innerHTML="Mauvais login";
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div style="border:10px outset #245DB2;">
			<table>
				<tr><td colspan="3" align="center"><h3>Nouveau Mdp :</h3></td></tr>
				<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
				<form name="formVal" onsubmit="return valide()" action= <?php echo $fo_chpass_chpass; ?> method="post" >
					<tr>
						<td align="right">Nouveau : </td>
						<td><input onkeyup="verif(this,2)" type="password" name="nPass"></td>
					</tr>
					<tr>
						<td align="right">Confirmer :</td>
						<td><input onkeyup="verif(this,2)" type="password" name="cPass"></td>
					</tr>
					<tr>
						<td align="right">Ancien :</td>
						<td><input onkeyup="verif(this,2)" type="password" name="aPass"></td>
					</tr>
					<tr>
					<td colspan="3" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>
					</tr>
				</form>	
			</table>
		</div>
	</body>
</html>