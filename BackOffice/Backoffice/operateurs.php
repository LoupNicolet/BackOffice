<?php
	if (isset($_SESSION['login'])) {
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
			
		$sql = 'SELECT login_operator,email_operator,firstName_operator,lastName_operator,type_operator FROM operator';
			
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
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
		header ('Location: ../Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<script type='text/javascript' src="../Add/tri.js"></script>
		<script>
			function confirme(colonne, login, table)
			{
			var x;
			var r=confirm("Effacer l'operateur " + login + " ?");
			if (r==true)
			  {
				window.location.assign("../Backoffice/backoffice.php?action=operateurs&page=supOpe&log=" + login);
			  }
			}
		</script>
	</head>
	<body>
		<table>	
			<tr>
				<td><h2  align="center">Operateurs</h2></td>
				<td>
					<table class="menu">
						<tr>
							<td><a class="menu" href="../Backoffice/backoffice.php?action=operateurs&page=addOpe">Ajouter</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages" colspan="2">
					<?php 	
						if(isset($_GET['page'])){
							if($_GET['page'] == 'addOpe'){include('../Backoffice/Operateurs/addOpe.php');}
							else if($_GET['page'] == 'supOpe'){
								$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
								mysql_select_db ($SQL_Cdw_name, $base);
								echo $sql = 'DELETE FROM operator WHERE login_operator="'.$_GET["log"].'"';
								$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
								mysql_close();
								header("location:../Backoffice/backoffice.php?action=operateurs");
							}
							if($_GET['page'] == 'chOpe'){include('../Backoffice/Operateurs/chOpe.php');}
						}
					?> 
				</td>
			</tr>
			<tr>
				<td  colspan='2'>
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
										<td align="center" style="background-color:#7A991A;">
											<a href="../Backoffice/backoffice.php?action=operateurs&page=chOpe&log='.$login[$i].'">Modifier</a>
										</td>
										<td align="center" style="background-color:#7A991A;">
											<input class="button_titre" type="button" onclick="confirme(this,\''.$login[$i].'\',\'operateurTable\')" value="Suppr" />
										</td>';
								}else{
									echo 
									'<tr>
										<td align="center" style="background-color:#7A991A;">
											<a href="../Backoffice/backoffice.php?action=options&page=chprofil">Modifier</a>
										</td>
										<td align="center" style="background-color:#7A991A;"></td>';
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