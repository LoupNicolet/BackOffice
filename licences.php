<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			if (test_Customer() || (isset($_POST['type']) && ($_POST['type'] != "indifferent"))){
				$sql = recherche_Customer('Customer_ID');	
			}else{
				$sql = 'SELECT Customer_ID FROM customers';
			}
			
			$customer_ID = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$row_Customer_ID = mysql_fetch_array($customer_ID);
			
			$y = 0;
			
			do{
				$sql = recherche_Licences($row_Customer_ID);
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				while($row = mysql_fetch_array($req)){
					$installGuid[$y] = $row['InstallGuid'];
					$installKey[$y] = $row['InstallKey'];
					$productID[$y] = $row['ProductID'];
					$licences[$y] = $row['Licences'];
					$expiration[$y] = $row['Expiration'];
					$revoked[$y] = $row['Revoked'];
					$label[$y] = $row['Label'];
					$y++;
				}
			}while($row_Customer_ID = mysql_fetch_array($customer_ID));
			
			mysql_free_result($req);
				
			for($i=0;$i<$y;$i++){
				if($revoked[$i] == 1){$revoked[$i] = "Revoked";
				}else if($revoked[$i] == 0){$revoked[$i] = "/";
				}else{$revoked[$i] = 'undefined';}
				$productID[$i] = RequeteSQL_Select('Product_Name', 'products', 'Product_ID',$productID[$i],"","");
			}
			mysql_close();
		}else{
			$y = 0;
		}
	}else{
		header ('Location: deconnexion.php?action="co"');
		exit();
	}
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<script type='text/javascript'  src="tri.js"></script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Licences</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="gestion.php?action=licences" method="post">
						<div align="center">
							<table>
								<tr>
									<td><h3>Informations Clients :</h3></td>
									<td></td>
									<td><h3>Informations Licences :</h3></td>
								</tr>
								<tr>
									<td align="right">Email : </td>
									<td><input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"></td>
									<td align="right">InstallGuid : </td>
									<td><input type="text" name="installGuid" value="<?php if (isset($_POST['installGuid'])) echo htmlentities(trim($_POST['installGuid'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">Name :</td>
									<td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo htmlentities(trim($_POST['name'])); ?>"></td>
									<td align="right">InstallKey :</td>
									<td><input type="text" name="installKey" value="<?php if (isset($_POST['installKey'])) echo htmlentities(trim($_POST['installKey'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">FirstName :</td>
									<td><input type="text" name="firstName" value="<?php if (isset($_POST['firstName'])) echo htmlentities(trim($_POST['firstName'])); ?>"></td>
									<td align="right">Label :</td>
									<td><input type="text" name="label" value="<?php if (isset($_POST['label'])) echo htmlentities(trim($_POST['label'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">LastName :</td>
									<td><input type="text" name="lastName" value="<?php if (isset($_POST['lastName'])) echo htmlentities(trim($_POST['lastName'])); ?>"></td>
									<td align="right">Number :</td>
									<td>
										<input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_nombre']) || ($_POST['operateur_nombre'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="eg"><?php echo '='; ?>
									</td>
								</tr>
								<tr>
									<td align="right">Tel :</td>
									<td><input type="text" name="tel" value="<?php if (isset($_POST['tel'])) echo htmlentities(trim($_POST['tel']));?>"></td>
									<td align="right">Expire :</td>
									<td>
										<input type="text" name="date" value="<?php if (isset($_POST['date'])) echo htmlentities(trim($_POST['date'])); ?>"><br>
										<!--<input <?php //if(!isset($_POST['operateur_date']) || ($_POST['operateur_date'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="sup"><?php// echo '>='; ?>
										<input <?php //if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="inf"><?php// echo '<='; ?>
										<input <?php //if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="eg"><?php// echo '='; ?>-->
									</td>
								</tr>
								<tr>
									<td align="right">Mobile :</td>
									<td><input type="text" name="mobile" value="<?php if (isset($_POST['mobile'])) echo htmlentities(trim($_POST['mobile'])); ?>"></td>	
								</tr>
								<tr>
									<td align="right">Type :</td>
									<td>
										<input <?php if(!isset($_POST['type']) || ($_POST['type'] == 'indifferent')){echo 'checked="checked"';}?> type="radio" name="type" value="indifferent">Indifferent<br>
										<input <?php if(isset($_POST['type']) && $_POST['type'] == 'client'){echo 'checked="checked"';}?> type="radio" name="type" value="client">Client<br>
										<input <?php if(isset($_POST['type']) && $_POST['type'] == 'prospect'){echo 'checked="checked"';}?> type="radio" name="type" value="prospect">Prospect
									</td>
									<td align="right">Etat :</td>
									<td>
										<input <?php if(!isset($_POST['etat']) || ($_POST['etat'] == 'indifferent')){echo 'checked="checked"';}?> type="radio" name="etat" value="indifferent">Indifferent<br>
										<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'valide'){echo 'checked="checked"';}?> type="radio" name="etat" value="valide">Valide<br>
										<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'invalide'){echo 'checked="checked"';}?> type="radio" name="etat" value="invalide">Invalide
									</td>
									<td align="right">Logiciel :</td>
									<td>
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
								<tr><td></td><td></td><td><input class="button" type="submit" name="recherche" value="Rechercher"></td></tr>
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
							"<table id='licencesTable'>
							<tr>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,true,\"licencesTable\")' value='Label' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,true,\"licencesTable\")' value='Logiciel' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"licencesTable\")' value='Nombre' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"licencesTable\")' value='Revoked' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,false,\"licencesTable\")' value='Expire' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true,\"licencesTable\")' value='InstallGuid' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(6,true,\"licencesTable\")' value='InstallKey' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td id=1 align="center">'.$label[$i].'</td>
									<td align="center">'.$productID[$i][0].'</td>
									<td align="center">'.$licences[$i].'</td>
									<td align="center">'.$revoked[$i].'</td>
									<td align="center">'.$expiration[$i].'</td>
									<td align="center">'.$installGuid[$i].'</td>
									<td align="center">'.$installKey[$i].'</td>
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