<?php
	if (isset($_SESSION['login'])){
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);	
			
		$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.$_GET['log'].'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$email_ope = $row['email_operator'];
			$prenom_ope = $row['firstName_operator'];
			$nom_ope = $row['lastName_operator'];
		}
		mysql_free_result($req);
		
		// on teste si le visiteur a soumis le formulaire
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
				
				//On modifie dans la base
				RequeteSQL_Update('operator', 'email_operator', $nEmail, 'firstName_operator', $nPrenom, 'lastName_operator', $nNom, 'login_operator', $_GET['log'],"","");
				$erreur = 'Changement effectué.';
							
				$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.$_GET['log'].'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
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
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="chprofil">
	<body>
		<table border="6">
			<td>
				<table>
					<tr><td colspan="4" align="center"><h3>Profil de <?php echo $_GET['log']; ?> :</h3></td></tr>
					<?php if(isset($erreur)) echo '<tr><td colspan="4" align="center">'.$erreur.'</td></tr>'; ?>
					<form action= <?php echo '"./backoffice.php?action=operateurs&page=chOpe&log='.$_GET['log'].'"' ?> method="post" >
						<tr>
							<td></td>
							<td align="center"><b>Informations :</b></td>
							<td><b>Modifier :</b></td>
						</tr>
						<tr>
							<td align="right">Nom :</td>
							<td align="center"><?php echo $nom_ope; ?></td>
							<td><input type="text" name="nom" value=""></td>
						</tr>
						<tr>
							<td align="right">Prenom :</td>
							<td align="center"><?php echo $prenom_ope; ?></td>
							<td><input type="text" name="prenom" value=""></td>
						</tr>
						<tr>
							<td align="right">Email :</td>
							<td align="center"><?php echo $email_ope; ?></td>
							<td><input type="text" name="email" value=""></td>
						</tr>
						<tr>
						<td colspan="4" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>
						</tr>
					</form>	
				</table>
			</td>
		</table>
	</body>
</html>