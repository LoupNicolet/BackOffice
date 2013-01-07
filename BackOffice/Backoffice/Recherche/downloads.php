<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			if (test_Downloads() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous"))){
				$sql = recherche_downloads('*');	
			}else{
				$sql = 'SELECT * FROM downloadkey';
			}
			
			$y = 0;
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			while($row = mysql_fetch_array($req)){
				$time[$y] = date("Y/m/d - H:i:s",$row['timestamp']);
				$email[$y] = $row['mail'];
				$number[$y] = $row['downloads'];
				$logiciel[$y] = $row['Application'];
				$y++;
			}
			
			mysql_free_result($req);
			mysql_close();

		}else{
			$y = 0;
		}
	}else{
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<script src="./Add/JQuery.js"></script>
		<script type="text/javascript" src="./Add/tri.js"></script>
		<script type='text/javascript'  src="./Add/details.js"></script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Telechargements</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="./backoffice.php?action=downloads" method="post">
						<div align="center">
							<table>
								<tr>
									<td align="center" colspan="6"><button class="button">Detail</button></td>
								</tr>
								<tr id="plus">
									<td align="right">Date : </td>
									<td>
										<input type="text" name="time" value="<?php if (isset($_POST['time'])) echo htmlentities(trim($_POST['time'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_date']) || ($_POST['operateur_date'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="eg"><?php echo '='; ?>
									</td>
									<td align="right">Email :</td>
									<td><input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"></td>
									<td align="right">Downloads :</td>
									<td><input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"></td>
								</tr>
								<tr>
									<td colspan="6" align="center">
										<?php
											$x=0;
											$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
											mysql_select_db ($SQL_Cdw_name, $base);
											$sql = 'SELECT Product_Name FROM products';
											$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
											?><input <?php if(!isset($_POST['logiciel']) || ($_POST['logiciel'] == 'tous')){echo 'checked="checked"';}?> type="radio" name="logiciel" value="tous">Tous<br><?php
											while($row = mysql_fetch_array($req)){
												$mem[$x] = $row;
												?><input <?php if(isset($_POST['logiciel']) && $_POST['logiciel'] == $row['Product_Name']){echo 'checked="checked"';}?> type="radio" name="logiciel" value=<?php echo '"'.$row['Product_Name'].'"'?>><?php echo $row['Product_Name'] ?><br><?php
												$logiciels[$x] = $row['Product_Name'];
												$x++;
											}
											mysql_free_result($req);
											mysql_close();
										?>
									</td>
								</tr>
								<tr><td  colspan="6" align="center"><input class="button" type="submit" name="recherche" value="Rechercher"></td></tr>
							</table>
						</div>
					</form>
				</td>
			</tr>
			<tr><td><p><?php echo $y." resultats"?></p></td></tr>
			<tr>
				<td>
					<?php
						if($y > 0){
							echo
							"<table id='downloadsTable'>
							<tr>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,false,\"downloadsTable\")' value='Date' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,true,\"downloadsTable\")' value='Email' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"downloadsTable\")' value='Downloads' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"downloadsTable\")' value='Logiciel' />
								</th>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td id=1 align="center">'.$time[$i].'</td>
									<td align="center">'.$email[$i].'</td>
									<td align="center">'.$number[$i].'</td>
									<td align="center">'.$logiciel[$i].'</td>
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