<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
				
				$data = RequeteSQL_Select('count(*)','operator','login_operator',$_POST['login'],"","");
				
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
					$sql = $sql.") VALUES ('".$_POST['login']."', '".md5($_POST['pass'])."'";
					if(!empty($_POST['email'])){
						$sql = $sql.", '".$_POST['email']."'";
					}
					if(!empty($_POST['prenom'])){
						$sql = $sql.", '".$_POST['prenom']."'";
					}
					if(!empty($_POST['nom'])){
						$sql = $sql.", '".$_POST['nom']."'";
					}
					if(!empty($_POST['type'])){
						$sql = $sql.", '".$_POST['type']."'";
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
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="addOpe">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
	</head>
	<body>
		<table border="6">
			<td>
				<table>
					<tr><td align="center" colspan="2"><h3>Inscription à l'espace membre :</h3></td></tr>
					<?php if(isset($erreur)) echo '<tr><td colspan="2" align="center">'.$erreur.'</td></tr>'; ?>
					<form action="./backoffice.php?action=operateurs&page=addOpe" method="post" >
						<tr>
							<td align="right">Login : </td>
							<td><input type="text" name="login" value=""></td>
						</tr>
						<tr>
							<td align="right">Mot de passe :</td>
							<td><input type="password" name="pass" value=""></td>
						</tr>
						<tr>
							<td align="right">Confirmation :</td>
							<td><input type="password" name="pass_confirm"></td>
						</tr>
						<tr>
							<td align="right">Email :</td>
							<td><input type="text" name="email"></td>
						</tr>
						<tr>
							<td align="right">Prenom :</td>
							<td><input type="text" name="prenom"></td>
						</tr>
						<tr>
							<td align="right">Nom :</td>
							<td><input type="text" name="nom"></td>
						</tr>
						<tr>
							<td align="right">type :</td>
							<td><input type="text" name="type"></td>
						</tr>
						<td align="center" colspan="2"><input class="button" type="submit" name="valider" value="Valider"></td>
					</form>
				</table>
			</td>
		</table>
	</body>
</html>