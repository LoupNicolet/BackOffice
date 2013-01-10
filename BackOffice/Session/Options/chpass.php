<?php
if (isset($_SESSION['login'])){
		// on teste si le visiteur a soumis le formulaire
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['cPass']) && !empty($_POST['cPass'])) && (isset($_POST['aPass']) && !empty($_POST['aPass'])) && (isset($_POST['nPass']) && !empty($_POST['nPass']))) {
				
				// on teste les deux mots de passe
				if ($_POST['cPass'] != $_POST['nPass']) {
					$erreur = 'Les deux mots de passe sont differents.';
				}
				else 
				{
					$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
					mysql_select_db ($SQL_Cdw_name, $base);
					
					// on recherche si il est enregistré
					$data = RequeteSQL_Select('count(*)','operator','login_operator',$_SESSION['login'],'pass_operator',md5($_POST['aPass']));
					
					//si il ne l'est pas
					if ($data[0] == 0){
						$erreur = "Mauvais mot de passe.";
					//si il l'est
					}else{
						//On l'ajoute dans la base
						RequeteSQL_Update('operator', 'pass_operator', md5($_POST['nPass']),"","","","", 'login_operator', $_SESSION['login'], 'pass_operator', md5($_POST['aPass']));
						$erreur = 'Changement effectué.';
						mysql_close();
					}
				}
			}
			else {$erreur = 'Au moins un des champs est vide.';}
		}
	}else{
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
?>

<html id="chpass">
	<head>
		<script src= <?php echo $sc_JQuery; ?>></script>
		<script src= <?php echo $sc_JQuery_Color; ?>></script>
		<script src= <?php echo $sc_verif; ?>></script>
		<script>
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
	<table border="6">
			<td>
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
			</td>
		</table>
	</body>
</html>