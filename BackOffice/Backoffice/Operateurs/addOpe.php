<?php
	if (isset($_SESSION['login'])) {
	if (isset($_POST['valider']) && $_POST['valider'] == 'Valider') {
		if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {

			if ($_POST['pass'] != $_POST['pass_confirm']) {
				$erreur = 'Les 2 mots de passe sont différents.';
			}
			else {
				$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
				mysql_select_db ($SQL_Cdw_name, $base);
				
				$data = RequeteSQL_Select('count(*)','operator','login_operator',mysql_real_escape_string($_POST['login']),"","");
				
				if ($data[0] == 0){
					$sql = "INSERT INTO operator (login_operator, pass_operator";
					if(!empty($_POST['email'])){
						$sql = $sql.", email_operator";
					}
					if(!empty($_POST['prenom'])){
						$sql = $sql.", firstName_operator";
					}
					if(!empty($_POST['nom'])){
						$sql = $sql.", lastName_operator";
					}
					if(!empty($_POST['type'])){
						$sql = $sql.", type_operator";
					}
					$sql = $sql.") VALUES ('".mysql_real_escape_string($_POST['login'])."', '".mysql_real_escape_string(md5($_POST['pass']))."'";
					if(!empty($_POST['email'])){
						$sql = $sql.", '".mysql_real_escape_string($_POST['email'])."'";
					}
					if(!empty($_POST['prenom'])){
						$sql = $sql.", '".mysql_real_escape_string($_POST['prenom'])."'";
					}
					if(!empty($_POST['nom'])){
						$sql = $sql.", '".mysql_real_escape_string($_POST['nom'])."'";
					}
					if(!empty($_POST['type'])){
						$sql = $sql.", '".mysql_real_escape_string($_POST['type'])."'";
					}
					$sql = $sql.")";
					
					mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
					$erreur = "operateur ajoute";
					mysql_close();
				}else{
					$erreur = 'Login deja utilise.';
				}
			}
		}
		else {$erreur = 'Veuillez rentrer au moins un login et un mot de passe.';}
	}
	}else{
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
?>
<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
<script type='text/javascript'>
	function valide()
	{
		var login=document.forms["formVal"]["login"];
		var mdp=document.forms["formVal"]["pass"];
		var email=document.forms["formVal"]["email"];
		if (login.value.length < 3)
		{
			document.getElementById("erreur").innerHTML="Mauvais format de Login (min 3)";
			return false;
		}
		if (mdp.value.length < 6)
		{
			document.getElementById("erreur").innerHTML="Mauvais format de mot de passe (min 6)";
			return false;
		}
		if ( ( email.value.indexOf("@") == -1 )
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
<table border="6">
	<td>
		<table>
			<tr><td align="center" colspan="2"><h3>Inscription d'un opérateur :</h3></td></tr>
			<tr><td id="erreur" colspan="3" align="center"><?php if(isset($erreur)) echo $erreur; ?></td></tr>
			<form name="formVal" onsubmit="return valide()" action=<?php echo $fo_addOpe_addOpe; ?> method="post" >
				<tr>
					<td align="right">Login : </td>
					<td><input onkeyup="verif(this,1)" type="text" name="login" value=""></td>
				</tr>
				<tr>
					<td align="right">Mot de passe :</td>
					<td><input onkeyup="verif(this,2)" type="password" name="pass" value=""></td>
				</tr>
				<tr>
					<td align="right">Confirmation :</td>
					<td><input onkeyup="verif(this,2)" type="password" name="pass_confirm"></td>
				</tr>
				<tr>
					<td align="right">Email :</td>
					<td><input onkeyup="verif(this,4)" type="text" name="email"></td>
				</tr>
				<tr>
					<td align="right">Prenom :</td>
					<td><input onkeyup="verif(this,3)" type="text" name="prenom"></td>
				</tr>
				<tr>
					<td align="right">Nom :</td>
					<td><input onkeyup="verif(this,3)" type="text" name="nom"></td>
				</tr>
				<tr>
					<td align="right">Type :</td>
					<td><input onkeyup="verif(this,3)" type="text" name="type"></td>
				</tr>
				<td align="center" colspan="2"><input class="button" type="submit" name="valider" value="Valider"></td>
			</form>
		</table>
	</td>
</table>