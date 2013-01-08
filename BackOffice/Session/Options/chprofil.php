<?php
	if (isset($_SESSION['login'])){
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);	
			
		$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.$_SESSION['login'].'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$email = $row['email_operator'];
			$prenom = $row['firstName_operator'];
			$nom = $row['lastName_operator'];
		}
		mysql_free_result($req);
		
		// on teste si le visiteur a soumis le formulaire
		if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
			if ((isset($_POST['nom']) && !empty($_POST['nom'])) || (isset($_POST['prenom']) && !empty($_POST['prenom'])) || (isset($_POST['email']) && !empty($_POST['email']))) {
				
				$nEmail = $email;
				$nNom = $nom;
				$nPrenom = $prenom;
				
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
				RequeteSQL_Update('operator', 'email_operator', $nEmail, 'firstName_operator', $nPrenom, 'lastName_operator', $nNom, 'login_operator', $_SESSION['login'],"","");
				$erreur = 'Changement effectué.';
							
				$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.$_SESSION['login'].'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				while($row = mysql_fetch_array($req)){
					$email = $row['email_operator'];
					$prenom = $row['firstName_operator'];
					$nom = $row['lastName_operator'];
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
	<head>
		<script src="./Add/JQuery.js"></script>
		<script src="./Add/JQuery_Color.js"></script>
		<script src="./Add/verif.js"></script>
		<script>
			function valide()
			{
				var aPass=document.forms["formVal"]["email"].value;
				if (aPass.length < 6))
				{
					document.getElementById("erreur").innerHTML="Mauvais format email";
					return false;
				}
			}
		</script>
	</head>
	<body>
		<table border="6">
			<td>
				<table>
					<tr><td colspan="4" align="center"><h3>Profil :</h3></td></tr>
					<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
					<form name="formVal" onsubmit="return valide()" action="./backoffice.php?action=options&page=chprofil" method="post" >
						<tr>
							<td></td>
							<td align="center"><b>Informations :</b></td>
							<td><b>Modifier :</b></td>
						</tr>
						<tr>
							<td align="right">Nom :</td>
							<td align="center"><?php echo $nom; ?></td>
							<td><input onkeyup="verif(this,3)" type="text" name="nom" value=""></td>
						</tr>
						<tr>
							<td align="right">Prenom :</td>
							<td align="center"><?php echo $prenom; ?></td>
							<td><input onkeyup="verif(this,3)" type="text" name="prenom" value=""></td>
						</tr>
						<tr>
							<td align="right">Email :</td>
							<td align="center"><?php echo $email; ?></td>
							<td><input onkeyup="verif(this,4)" type="text" name="email" value=""></td>
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