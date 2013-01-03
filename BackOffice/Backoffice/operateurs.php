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
								<th></th>
								<th></th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,true,\"productTable\")' value='Login' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,false,\"productTable\")' value='Type' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"productTable\")' value='Prenom' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"productTable\")' value='Nom' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"productTable\")' value='Email' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td>
										<a href="../Backoffice/backoffice.php?action=licences">Modifier</a>
									</td>
									<td>
										<a href="../Backoffice/backoffice.php?action=licences">Supprimer</a>
									</td>
									<td id=1 align="center">'.$login[$i].'</td>
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