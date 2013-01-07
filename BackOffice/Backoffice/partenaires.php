<?php
	if (isset($_SESSION['login'])) {
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
			
		$sql = 'SELECT * FROM partenaires';
			
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$ID[$y] = $row['ID_partenaire'];
			$time[$y] = $row['time_partenaire'];
			$label[$y] = $row['label_partenaire'];
			$mail[$y] = $row['mail_partenaire'];
			$number[$y] = $row['number_partenaire'];
			$y++;
		}
			
		mysql_free_result($req);
		mysql_close();
	}else{
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<script type='text/javascript' src="./Add/tri.js"></script>
		<script>
			function confirme(colonne, label, table)
			{
			var x;
			var r=confirm("Effacer le partenaire " + label + " ?");
			if (r==true)
			  {
				window.location.assign("./backoffice.php?action=partenaires&page=supPar&lab=" + label);
			  }
			}
		</script>
	</head>
	<body>
		<table>	
			<tr>
				<td><h2  align="center">Partenaires</h2></td>
				<td>
					<table class="menu">
						<tr>
							<td><a class="menu" href="./backoffice.php?action=partenaires&page=addPar">Ajouter</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages" colspan="2">
					<?php 	
						if(isset($_GET['page'])){
							if($_GET['page'] == 'addPar'){include('./Backoffice/Partenaires/addPar.php');}
							else if($_GET['page'] == 'supPar'){
								$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
								mysql_select_db ($SQL_Cdw_name, $base);
								echo $sql = 'DELETE FROM partenaires WHERE label_partenaire="'.$_GET["log"].'"';
								$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
								mysql_close();
								header("location:./backoffice.php?action=operateurs");
							}
							if($_GET['page'] == 'chOpe'){include('./Backoffice/Operateurs/chOpe.php');}
						}
					?> 
				</td>
			</tr>
			<tr>
				<td  colspan='2'>
					<?php
						if($y > 0){
							echo
							"<table id='partenairesTable'>
							<tr>
								<th id='resultat' colspan='2' align='center'></th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"operateurTable\")' value='Label' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"operateurTable\")' value='Nombres Clients' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true,\"operateurTable\")' value='Mail' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true,\"operateurTable\")' value='Date' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td align="center" style="background-color:#245DB2;">
										<a href="./backoffice.php?action=partenaires&page=chPar&lab='.$label[$i].'">Modifier</a>
									</td>
									<td align="center" style="background-color:#245DB2;">
										<input class="button_titre" type="button" onclick="confirme(this,\''.$label[$i].'\',\'operateurTable\')" value="Suppr" />
									</td>';
								echo
									'<td align="center">'.$label[$i].'</td>
									<td align="center">'.$number[$i].'</td>
									<td align="center">'.$mail[$i].'</td>
									<td align="center">'.$time[$i].'</td>
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