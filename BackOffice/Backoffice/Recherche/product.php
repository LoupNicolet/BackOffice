<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	date_default_timezone_set("Europe/Paris");
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){

			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
		
			$a=0;
			$sql = 'SELECT ProductKey, max( KeyActivity_Date ) FROM keyactivityCA GROUP BY ProductKey';
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			while($row = mysql_fetch_array($req)){
				$valKey[$a] = $row['ProductKey'];
				$valDate[$a] = $row['max( KeyActivity_Date )'];
				$a++;
			}
			mysql_free_result($req);
			$y=0;
			for($i=0;$i<$a;$i++){
				if (test_product() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous"))){
					$sql = recherche_product($valKey[$i],$valDate[$i]);
				}else{
					$sql = 'SELECT * FROM keyactivityCA WHERE ProductKey="'.$valKey[$i].'" AND KeyActivity_Date="'.$valDate[$i].'"';
				}
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				while($row = mysql_fetch_array($req)){
					$key[$y] = $row['ProductKey'];
					$productID[$y] = $row['ProductID'];
					$number[$y] = $row['NumUsers'];
					$time[$y] = date("Y/m/d - H:i:s",$row['KeyActivity_Date']);
					$y++;
				}
				mysql_free_result($req);
			}
			$b=0;
			for($i=0;$i<$y;$i++){
				$productID[$i] = RequeteSQL_Select('Product_Name', 'products', 'Product_ID',$productID[$i],"","");
				$sql = 'SELECT Label,Licences,Revoked FROM productkey WHERE InstallKey="'.$key[$i].'"';
				$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
				while($row = mysql_fetch_array($req)){
					$client[$b] = $row['Label'];
					$licences[$b] = $row['Licences'];
					$revoked[$b] = $row['Revoked'];
					$b++;
				}
				mysql_free_result($req);
				if($licences[$i] < $number[$i]){
					$depp[$i] = 1;
				}else{
					$depp[$i] = 0;
				}
			}		
			
			mysql_close();
			
		}else{
			$y = 0;
		}
	}else{
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
	
?>
<html id = 'product'>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<script src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
		<script type='text/javascript'  src= <?php echo $sc_details; ?> ></script>
		<script src= <?php echo $sc_JQuery_Color; ?>></script>
		<script src= <?php echo $sc_verif; ?>></script>
		<script>
			function valide()
			{
				var element=document.forms["formVal"]["time"];
				var element2=document.forms["formVal"]["key"];
				if ((element.value != "")
					&&(( element.value.indexOf("/") != 4) 
					|| ( element.value.lastIndexOf("/") != 7 )
					|| (element.value.lastIndexOf("/") != (element.value.length - 3))))
				{
					alert("Mauvais format de Date ");
					return false;
				}
				if ((element2.value != "")
					&&(( element2.value.indexOf("-") != 5) 
					|| ( element2.value.lastIndexOf("-") != 29 )
					|| ( element2.value.charAt(11) != "-" )
					|| ( element2.value.charAt(17) != "-" )
					|| ( element2.value.charAt(23) != "-" )
					|| ( element2.value.length != 35))
					)
				{
					alert("Mauvais format de Cle");
					return false;
				}
			}
		</script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Licences</h2></tr>
			<tr>
				<td>
					<form name="formVal" id="form" onsubmit="return valide()" class="recherche" action= <?php echo $fo_product_product; ?> method="post">
						<div align="center">
							<table>
								<tr>
									<td align="center" colspan="6"><button class="button">Detail</button></td>
								</tr>
								<tr id="plus">
									<td align="right">Date : </td>
									<td>
										<small>yyyy/mm/dd</small>
										<input type="text" onkeyup="verif(this,5)" name="time" value="<?php if (isset($_POST['time'])) echo htmlentities(trim($_POST['time'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_date']) || ($_POST['operateur_date'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="eg"><?php echo '='; ?>
									</td>
									<td align="right">Nb Utilisateurs:</td>
									<td>
										<input type="text" name="numberU" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"><br>
										<input <?php if(!isset($_POST['operateur_nombre']) || ($_POST['operateur_nombre'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="sup"><?php echo '>='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="inf"><?php echo '<='; ?>
										<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="eg"><?php echo '='; ?>
									</td>
									<td align="right">Cle :</td>
									<td><input type="text" onkeyup="verif(this,6)" name="key" value="<?php if (isset($_POST['key'])) echo htmlentities(trim($_POST['key'])); ?>"></td>
								</tr>
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
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"productTable\")' value='Licences' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true,\"productTable\")' value='Utilisateurs' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true,\"productTable\")' value='Etat' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(6,true,\"productTable\")' value='Cle' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr ';if($depp[$i]==1){ echo 'style="background-color:#FF6666;color:#FFFFFF;"';} echo '>
									<td align="center">'.$productID[$i][0].'</td>
									<td align="center">'.$time[$i].'</td>
									<td align="center">'.$client[$i].'</td>
									<td align="center">'.$licences[$i].'</td>
									<td align="center">'.$number[$i].'</td>
									<td align="center">'.$revoked[$i].'</td>
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