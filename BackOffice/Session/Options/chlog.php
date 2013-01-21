<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['cLogin']) && !empty($_POST['cLogin'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['nLogin']) && !empty($_POST['nLogin']))) {

				if ($_POST['nLogin'] != $_POST['cLogin']) {
					$erreur = 'Les deux login sont differents.';
				}else{
					$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
					mysql_select_db ($SQL_Cdw_name, $base);
					
					$data = RequeteSQL_Select('count(*)','operator','login_operator',$_SESSION['login'],'pass_operator',md5($_POST['pass']));

					if ($data[0] == 0){
						$erreur = "Mauvais mot de passe.";
					}else{
						$data = RequeteSQL_Select('count(*)','operator','login_operator',$_POST['nLogin'],"","");
						if ($data[0] == 0) {
							RequeteSQL_Update('operator', 'login_operator', $_POST['nLogin'],"","","","", 'login_operator', $_SESSION['login'], 'pass_operator', md5($_POST['pass']));
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
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
?>
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
<table border="6">
	<td>
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
	</td>
</table>