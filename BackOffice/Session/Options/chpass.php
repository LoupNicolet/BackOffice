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
		header ('Location: ../Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="chpass">
	<body>
	<table border="6">
			<td>
				<table>
					<tr><td colspan="3" align="center"><h3>Nouveau Mdp :</h3></td></tr>
					<?php if(isset($erreur)) echo '<tr><td colspan="3" align="center">'.$erreur.'</td></tr>'; ?>
					<form action="../Backoffice/backoffice.php?action=options&page=chpass" method="post" >
						<tr>
							<td align="right">Nouveau : </td>
							<td><input type="password" name="nPass"></td>
						</tr>
						<tr>
							<td align="right">Confirmer :</td>
							<td><input type="password" name="cPass"></td>
						</tr>
						<tr>
							<td align="right">Ancien :</td>
							<td><input type="password" name="aPass"></td>
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