<?php
	if (isset($_SESSION['login'])){
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);	
			
		$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_SESSION['login']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$email = $row['email_operator'];
			$prenom = $row['firstName_operator'];
			$nom = $row['lastName_operator'];
		}
		mysql_free_result($req);
		
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
				
				RequeteSQL_Update('operator', 'email_operator', mysql_real_escape_string($nEmail), 'firstName_operator', mysql_real_escape_string($nPrenom), 'lastName_operator', mysql_real_escape_string($nNom), 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
				$erreur = 'Changement effectué.';
							
				$sql = 'SELECT email_operator,firstName_operator,lastName_operator FROM operator WHERE login_operator="'.mysql_real_escape_string($_SESSION['login']).'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
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
		var element=document.forms["formVal"]["email"];
		if (( element.value.length < 1){}
		else if (( element.value.indexOf("@") == -1 ) 
			|| ( element.value.indexOf("@") == 0 )
			|| ( element.value.indexOf("@") != element.value.lastIndexOf("@") ) 
			|| ( element.value.indexOf(".") == element.value.indexOf("@")-1 ) 
			|| ( element.value.indexOf(".") == element.value.indexOf("@") +1 ) 
			|| (element.value.indexOf("@") == element.value.length -1 ) 
			|| (element.value.indexOf (".") == -1) 
			|| ( element.value.lastIndexOf (".") == element.value.length -1 ) 
			|| (element.value.indexOf (" ") != -1) 
			|| ((element.value.indexOf(".") == element.value.lastIndexOf(".")) && (element.value.lastIndexOf(".") < element.value.indexOf("@")))
			)
		{
			document.getElementById("erreur").innerHTML="Mauvais format email";
			return false;
		}
	}
</script>
<table border="6">
	<td>
		<table>
			<tr><td colspan="4" align="center"><h3>Profil :</h3></td></tr>
			<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
			<form id="form" name="formVal" onsubmit="return valide()" action= <?php echo $fo_chprofil_chprofil; ?> method="post" >
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
					<td><input id="email" onkeyup="verif(this,4)" type="text" name="email" value=""></td>
				</tr>
				<tr>
				<td colspan="4" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>
				</tr>
			</form>	
		</table>
	</td>
</table>