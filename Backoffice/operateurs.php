<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../Add/define.php';
	session_start();
	if (isset($_SESSION['login'])) {
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
			
		$sql = 'SELECT login_operator,email_operator,firstName_operator,lastName_operator,type_operator FROM operator';
			
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$login[$y] = $row['login_operator'];
			$type[$y] = $row['type_operator'];
			$prenom[$y] = $row['firstName_operator'];
			$nom[$y] = $row['lastName_operator'];
			$email[$y] = $row['email_operator'];
			if($login[$y] == $_SESSION['login']){
				$Qui[$y] = 1;
			}else{
				$Qui[$y] = 0;
			}
			$y++;
		}
			
		mysql_free_result($req);
		mysql_close();
	}else{
		header ('Location: /Session/deconnexion.php?action="co"');
		exit();
	}
?>
<html id="PagesFrame">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css; ?>>
		<script type='text/javascript' src= <?php echo $sc_tri; ?>></script>
		<script type='text/javascript'>
			function confirme(colonne, login, table)
			{
				var x;
				var r=confirm("Effacer l'operateur " + login + " ?");
				if (r==true)
				  {
					window.location.assign("/Backoffice/operateurs.php?page=supOpe&log=" + login);
				  }
			}
		</script>
	</head>
	<body>
		<table>	
			<tr>
				<td><h2  align="center">Operateurs</h2></td>
				<td>
					<table class="menu" style="background-color:#245DB2;">
						<tr>
							<td><a class="menu" href= <?php echo $hr_operateur_addOpe ?>>Ajouter</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php 	
						if(isset($_GET['page'])){
							if($_GET['page'] == 'addOpe'){echo '<iframe src='.$in_operateur_addOpe.' frameborder="0" height="400" width="100%"></iframe>';}
							else if($_GET['page'] == 'supOpe'){
								$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
								mysql_select_db ($SQL_Cdw_name, $base);
								echo $sql = 'DELETE FROM operator WHERE login_operator="'.mysql_real_escape_string($_GET["log"]).'"';
								$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
								mysql_close();
								header($he_operateur_operateur);
							}
							if($_GET['page'] == 'chOpe'){echo '<iframe src='.$in_operateur_chOpe.$_GET['log'].' frameborder="0" height="300" width="100%"></iframe>';}
						}
					?> 
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<?php
						if($y > 0){
							echo
							"<table id='operateurTable'>
							<tr>
								<th id='resultat' colspan='2' align='center'></th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"operateurTable\")' value='Login' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"operateurTable\")' value='Type' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true,\"operateurTable\")' value='Prenom' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true,\"operateurTable\")' value='Nom' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(6,true,\"operateurTable\")' value='Email' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								if($Qui[$i] == 0){
									echo 
									'<tr>
										<td align="center" style="background-color:#245DB2;">
											<a href='.$hr_operateur_chOpe.$login[$i].'>Modifier</a>
										</td>
										<td align="center" style="background-color:#245DB2;">
											<input class="button_titre" type="button" onclick="confirme(this,\''.$login[$i].'\',\'operateurTable\')" value="Suppr" />
										</td>';
								}else{
									echo 
									'<tr>
										<td align="center" style="background-color:#245DB2;">
											<a href='.$hr_operateur_chprofil.'>Modifier</a>
										</td>
										<td align="center" style="background-color:#245DB2;"></td>';
								}
								echo
									'<td align="center">'.$login[$i].'</td>
									<td align="center">'.$type[$i].'</td>
									<td align="center">'.$prenom[$i].'</td>
									<td align="center">'.$nom[$i].'</td>
									<td align="center">'.$email[$i].'</td>
								</tr>';
							}
							echo '</table>';
						}
					?>
				</td>
			</tr>
		</table>
	</body>
</html>