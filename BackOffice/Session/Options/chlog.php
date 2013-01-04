<?php
	if (isset($_SESSION['login'])){
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
						$data = RequeteSQL_Select('count(*)','operator','login_operator',$_POST['nLogin'],"","");
						
						//On l'ajoute dans la base
						if ($data[0] == 0) {
							RequeteSQL_Update('operator', 'login_operator', $_POST['nLogin'],"","","","", 'login_operator', $_SESSION['login'], 'pass_operator', md5($_POST['pass']));
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
	}else{
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="chlog">
	<head>
	<script src="./Add/JQuery.js"></script>
	<script src="./Add/JQuery_Color.js"></script>
	<script>
	function verif(element,type) {
				if (type == 1) {
					if (element.value.length == 0) {
						$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
					}else if(element.value.length < 3){
						$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
					}else{
						$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
					}
				}else if (type == 2) {
					if (element.value.length == 0) {
						$(element).animate( {backgroundColor: "#FFFFFF"}, 250);
					}else if(element.value.length < 6){
						$(element).animate( {backgroundColor: "#FF4D4D"}, 250);
					}else{
						$(element).animate( {backgroundColor: "#9CBB3C"}, 250);
					}
				}
			}
			
			/*function valider() {
				$("input").hide();
				$.post("./backoffice.php?action=partenaires&page=chlog"),
				{
				  name:"Donald Duck"
				  //city:"Duckburg"
				}
			}*/
			
			$(document).ready(function(){
  $("button").click(function(){
    $.post("demo_test_post.asp",
    {
      name:"Donald Duck",
      city:"Duckburg"
    },
    function(data,status){
      alert("Data: " + data + "\nStatus: " + status);
    });
  });
});
	</script>
	</head>
	<body>
	<table border="6">
			<td>
				<table>
					<tr><td colspan="3" align="center"><h3>Nouveau Login :</h3></td></tr>
					<?php if(isset($erreur)) echo '<tr><td colspan="3" align="center">'.$erreur.'</td></tr>'; ?>
					<!--<form action="./backoffice.php?action=options&page=chlog" method="post" >-->
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
						<button>Send an HTTP POST request to a page and get the result back</button>
						<!--<td colspan="3" align="center"><button onclick="valider()" class="button" name="valider">Valider</button>-->
						<!--<td colspan="3" align="center"><input class="button" type="submit" name="valider" value="Valider"></td>-->
						</tr>
					<!--</form>-->	
				</table>
			</td>
		</table>
	</body>
</html>