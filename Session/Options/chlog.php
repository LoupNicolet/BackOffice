<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
					mysql_select_db ($SQL_Cdw_name, $base);
					
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
				var login=document.forms["formVal"]["nLogin"].value;
				var conf=document.forms["formVal"]["cLogin"].value;
				if ((login.length < 3) || (conf.length < 3))
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
				<tr><td colspan="3" align="center"><h3>Nouveau Login :</h3></td></tr>
				<tr><td colspan="3" align="center"><h4><?php echo $_SESSION['login']; ?></h4></td></tr>
				<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
				<form name="formVal" action= <?php echo $fo_chlog_chlog; ?> method="post" onsubmit="return valide()" >
					<tr>
						<td align="right">Nouveau : </td>
						<td><input onkeyup="verif(this,1)" type="text" name="nLogin" value=""></td>
					</tr>
					<tr>
						<td align="right">Confirmer :</td>
						<td><input onkeyup="verif(this,1)" type="text" name="cLogin" value=""></td>
					</tr>
					<tr>
						<td align="right">Mot de passe :</td>
						<td><input onkeyup="verif(this,2)" type="password" name="pass"></td>
					</tr>
					<tr>
					<td colspan="3" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>
					</tr>
				</form>	
			</table>
		</div>
	</body>
</html>