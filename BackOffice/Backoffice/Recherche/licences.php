<!--<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">-->
<?php
	date_default_timezone_set("Europe/Paris");
	if (isset($_SESSION['login'])){
		//if (isset($_POST['recherche'])){
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			if (Test_Licences() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous"))){
					$sql = recherche_licences();
				}else{
					/*$sql = 'SELECT C.Customer_ID AS ID,P.Product_Name AS Logiciel,max(K.KeyActivity_Date) AS Date,C.Customer_Name AS Client,Pk.Label AS Label,Pk.licences AS Licences,Pk.Revoked AS Etat,K.ProductKey AS Cle
							FROM keyactivityCA AS K,productkey AS Pk,customers As C,products As P
							WHERE K.ProductKey = Pk.InstallKey AND Pk.CustomerID = C.Customer_ID AND K.ProductID = P.Product_ID
							GROUP BY K.ProductKey';
				*/
				$sql = 'SELECT C.Customer_ID AS ID,P.Product_Name AS Logiciel,max(K.KeyActivity_Date) AS Date,C.Customer_Name AS Client,Pk.Label AS Label,Pk.Licences AS Licences,Pk.Revoked AS Etat,Pk.InstallKey AS Cle
						FROM productkey AS Pk
						LEFT JOIN keyactivityca AS K ON Pk.InstallKey = K.ProductKey
						LEFT JOIN products AS P ON Pk.ProductID = P.Product_ID
						LEFT JOIN customers AS C ON Pk.CustomerID = C.Customer_ID
						GROUP BY Pk.InstallKey';
				}
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			$y=0;
			while($row = mysql_fetch_array($req)){
				$ID[$y] = $row['ID'];
				$Logiciel[$y] = $row['Logiciel'];
				$Date[$y] = $row['Date'];
				$Client[$y] = $row['Client'];
				$Label[$y] = $row['Label'];
				$Licences[$y] = $row['Licences'];
				$Etat[$y] = $row['Etat'];
				$Cle[$y] = $row['Cle'];
				$y++;
			}
			mysql_free_result($req);
			for($i=0;$i<$y;$i++){
				$Utilisateurs[$i] = RequeteSQL_Select('NumUsers', 'keyactivityca', 'KeyActivity_Date',$Date[$i],"ProductKey",$Cle[$i]);
				if($Date[$i] != null){
					$Date[$i] = date("Y/m/d H:i:s",$Date[$i]);
				}
				if($Licences[$i] < $Utilisateurs[$i][0]){
					$depp[$i] = 1;
				}else{
					$depp[$i] = 0;
				}
				if($Etat[$i] == 1){
					$Etat[$i] = "Desactive";
				}else if($Etat[$i] == 0){
					$Etat[$i] = "Active";
				}else{
					$Etat[$i] = 'undefined';
				}
			}
			mysql_close();
		/*}else{
			$y = 0;
		}*/
	}else{
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
	
?>
<!--<html id = 'licences'>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" >-->
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_details; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_modif; ?>></script>
		
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
			
			function requete(xmlhttp){
				var val = "0";
				if(valType==1){
					 val = document.getElementById("tfCase").value
				}else if(valType == 3){
					if(valeur == "Active"){
						val = "1";
					}
				}
				xmlhttp.send(	
									"id=productkey"
									+"&value="+val
									+"&col="+colonne
									+"&InstallKey="+document.getElementById(""+7+ligne).innerHTML
								);
			}
		</script>
	<!--</head>
	<body>-->
		<table>
			<tr><h2 align="center">Licences</h2></tr>
			<tr>
				<td>
					<form name="formVal" id="form" onsubmit="return valide()" class="recherche" action= <?php echo $fo_licences_licences; ?> method="post">
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
									<td align="right">Nb Utilisateurs :</td>
									<td>
										<input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"><br>
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
											$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
											mysql_select_db ($SQL_Cdw_name, $base);
											$sql = 'SELECT Product_Name FROM products';
											$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
											?><input <?php if(!isset($_POST['logiciel']) || ($_POST['logiciel'] == 'tous')){echo 'checked="checked"';}?> type="radio" name="logiciel" value="tous">Tous<br><?php
											while($row = mysql_fetch_array($req)){
												if(($row['Product_Name'] != "S2GS")&&($row['Product_Name'] != "Mig6")){
													?><input <?php if(isset($_POST['logiciel']) && $_POST['logiciel'] == $row['Product_Name']){echo 'checked="checked"';}?> type="radio" name="logiciel" value=<?php echo '"'.$row['Product_Name'].'"'?>><?php if($row['Product_Name'] == "CloudMailMover"){ echo "CloudXFer"; }else{ echo $row['Product_Name']; } ?><br><?php
												}
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
							"<table id='licencesTable'>
							<tr>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,true,\"licencesTable\")' value='Logiciel' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,false,\"licencesTable\")' value='Date' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true,\"licencesTable\")' value='Client' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true,\"licencesTable\")' value='Label' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true,\"licencesTable\")' value='Licences' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true,\"licencesTable\")' value='Utilisateurs' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(6,true,\"licencesTable\")' value='Etat' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(7,true,\"licencesTable\")' value='Cle' />
								</th>
							</tr>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr ';if($depp[$i]==1){ echo 'style="background-color:#FF6666;color:#FFFFFF;"';} echo '>
									<td id="0'.($i+1).'" align="center">'.$Logiciel[$i].'</td>
									<td id="1'.($i+1).'" align="center">'.$Date[$i].'</td>
									<td id="2'.($i+1).'" align="center"><a href="'.$hr_licences_customers.'&id='.$ID[$i].'">'.$Client[$i].'</a></td>
									<td id="3'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Label[$i].'</td>
									<td id="4'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Licences[$i].'</td>
									<td id="5'.($i+1).'" align="center">'.$Utilisateurs[$i][0].'</td>
									<td id="6'.($i+1).'" onclick="clic(this,3,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Etat[$i].'</td>
									<td id="7'.($i+1).'" align="center">'.$Cle[$i].'</td>
								</tr>';
							}
							echo '</table>';
						}
					?>
				</td>
			</tr>
		</table>
	<!--</body>
</html>-->