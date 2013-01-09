
<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){

			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			$y=0;
			$encore = true;
			while($encore == true){
				$encore = false;
				if (/*test_product() || */(isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous"))){
					if($y == 0){
						$sql = recherche_product_spe($y,0);	
					}else{
						$sql = recherche_product_spe($y,$val);
					}
				}else{
					$sql = 'SELECT ProductKey FROM keyactivityca ';
					for($a=0;$a<$y;$a++){
						if($a != 0){
							$sql = $sql.' AND ';
						}else{
							$sql = $sql.' WHERE ';
						}
						$sql = $sql.'ProductKey<>"'.$val[$a].'"';
					}
				}
				if($req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error())){
					while($row = mysql_fetch_array($req)){
						$val[$y] = $row['ProductKey'];
						$encore = true;
					}
					if($encore){
						mysql_free_result($req);
						$sql = 'SELECT * FROM keyactivityca WHERE ProductKey="'.$val[$y].'" ORDER BY KeyActivity_Date DESC LIMIT 1';
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
						while($row = mysql_fetch_array($req)){
							$key[$y] = $row['ProductKey'];
							$productID[$y] = $row['ProductID'];
							$number[$y] = $row['NumUsers'];
							$time[$y] = date("Y/m/d - H:i:s",$row['KeyActivity_Date']);
						}
					}	
				}
				mysql_free_result($req);
				$y++;
			}
			$y = $y-1;

			for($i=0;$i<$y;$i++){
				$productID[$i] = RequeteSQL_Select('Product_Name', 'products', 'Product_ID',$productID[$i],"","");
			}
			
			for($i=0;$i<$y;$i++){
				$client[$i] = RequeteSQL_Select('label', 'productkey', 'InstallKey',$key[$i],"","");
			}
			
			mysql_close();

		}else{
			$y = 0;
		}
	}else{
		header ('Location: ./Session/deconnexion.php?action="co"');
		exit();
	}
	
?>
<html id = 'product'>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<script src="./Add/JQuery.js"></script>
		<script type="text/javascript" src="./Add/tri.js"></script>
		<script type='text/javascript'  src="./Add/details.js"></script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Produits</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="./backoffice.php?action=product" method="post">
						<div align="center">
							<table>
								<!--<tr>
									<td align="center" colspan="6"><button class="button">Detail</button></td>
								</tr>
								<tr id="plus">
									<td align="right">Date : </td>
									<td>
										<input type="text" name="time" value="<?php /*if (isset($_POST['time'])) echo htmlentities(trim($_POST['time'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_date']) || ($_POST['operateur_date'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="eg"><?php echo '='; ?>
									</td>
									<td align="right">Nombre :</td>
									<td>
										<input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_nombre']) || ($_POST['operateur_nombre'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="eg"><?php echo '='; ?>
									</td>
									<td align="right">Cle :</td>
									<td><input type="text" name="key" value="<?php if (isset($_POST['key'])) echo htmlentities(trim($_POST['key'])); */?>"></td>
								</tr>-->
								<tr>
									<td align="center" colspan="6">
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
							"<table id='productTable'>
							<tr>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,true,\"productTable\")' value='Logiciel' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,false,\"productTable\")' value='Date' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"productTable\")' value='Client' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"productTable\")' value='Utilisateur' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true,\"productTable\")' value='Cle' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td align="center">'.$productID[$i][0].'</td>
									<td align="center">'.$time[$i].'</td>
									<td align="center">'.$client[$i][0].'</td>
									<td align="center">'.$number[$i].'</td>
									<td align="center">'.$key[$i].'</td>
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