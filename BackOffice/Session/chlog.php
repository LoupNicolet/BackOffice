<?php
	// on teste si le visiteur a soumis le formulaire
	if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
		if ((isset($_POST['cLogin']) && !empty($_POST['cLogin'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['nLogin']) && !empty($_POST['nLogin']))) {
			
			// on teste les deux mots de passe
			if ($_POST['nLogin'] != $_POST['cLogin']) {
				$erreur = 'Les deux login sont differents.';
			}
			else 
			{
				$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
				mysql_select_db ($SQL_Cdw_name, $base);
				
				// on recherche si il est enregistré
				$data = RequeteSQL_Select('count(*)','operator','login_operator',$_SESSION['login'],'pass_operator',md5($_POST['pass']));
				
				//si il ne l'est pas
				if ($data[0] == 0){
					$erreur = "Mauvais mot de passe.";
				//si il l'est
				}else{
					// on recherche si ce login est déjà utilisé par un autre membre
					$data = RequeteSQL_Select('count(*)','operator','login_operator',$_POST['nLogin'],0,0);
					
					//On l'ajoute dans la base
					if ($data[0] == 0) {
						RequeteSQL_Update('operator', 'login_operator', $_POST['nLogin'], 'login_operator', $_SESSION['login'], 'pass_operator', md5($_POST['pass']));
						$_SESSION['login'] = $_POST['nLogin'];
						$erreur = 'Changement effectué.';
					}
					else {$erreur = 'Login deja utilisé.';}
					mysql_close();
				}
			}
		}
		else {$erreur = 'Au moins un des champs est vide.';}
	}
?>

<html id="chlog">
	<body>
	<table border="6">
			<td>
				<table>
					<tr><td colspan="3" align="center"><h3>Nouveau Login :</h3></td></tr>
					<?php if(isset($erreur)) echo '<tr><td colspan="3" align="center">'.$erreur.'</td></tr>'; ?>
					<form action="../Gestion/gestion.php?action=options&page=chlog" method="post" >
						<tr>
							<td align="right">Nouveau : </td>
							<td><input type="text" name="nLogin" value=""></td>
						</tr>
						<tr>
							<td align="right">Confirmer :</td>
							<td><input type="text" name="cLogin" value=""></td>
						</tr>
						<tr>
							<td align="right">Mot de passe :</td>
							<td><input type="password" name="pass"></td>
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