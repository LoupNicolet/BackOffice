<?php
	date_default_timezone_set("Europe/Paris");
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		if (Test_Licences() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous")) || (isset($_POST['etat']) && ($_POST['etat'] != "Indifferent")) || (isset($_POST['type']) && ($_POST['type'] != "Indifferent"))){
				$sql = recherche_licences();
			}else{
				$sql = 'SELECT C.Customer_ID AS ID,P.Product_Name AS Logiciel,max(K.KeyActivity_Date) AS Date, Ku.InitialisationDate AS Date2,C.Customer_Name AS Client,Pk.Label AS Label,Pk.Licences AS Licences,Pk.Revoked AS Etat,Pk.InstallKey AS Cle
						FROM productkey AS Pk
						LEFT JOIN keyactivityca AS K ON Pk.InstallKey = K.ProductKey
						LEFT JOIN products AS P ON Pk.ProductID = P.Product_ID
						LEFT JOIN customers AS C ON Pk.CustomerID = C.Customer_ID
						LEFT JOIN keyusage AS Ku ON Pk.RowID = Ku.KeyRowID
						GROUP BY Pk.InstallKey';
			}
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$y=0;
		while($row = mysql_fetch_array($req)){
			$ID[$y] = $row['ID'];
			$Logiciel[$y] = $row['Logiciel'];
			$Date[$y] = $row['Date'];
			$Date2[$y] = $row['Date2'];
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
			if($Date2[$i] != null){
				$Date2[$i] = date("Y/m/d H:i:s",$Date2[$i]);
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
	}else{
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
?>
<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
<script type='text/javascript' src= <?php echo $sc_details; ?> ></script>
<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
<script type='text/javascript' src= <?php echo $sc_modif; ?>></script>
<script type='text/javascript'>
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
						+"&InstallKey="+document.getElementById(""+8+ligne).innerHTML
					);
	}
	
	$(document).ready(function(){
		sortTable(2, false, "licencesTable");
	});
</script>
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
							<td align="right">Installation : </td>
							<td>
								<small>yyyy/mm/dd</small>
								<input type="text" onkeyup="verif(this,5)" name="date1" value="<?php if (isset($_POST['date1'])) echo htmlentities(trim($_POST['date1'])); ?>"><br>
								<input <?php if(!isset($_POST['operateur_date1']) || ($_POST['operateur_date1'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="sup"><?php echo '>='; ?>
								<input <?php if(isset($_POST['operateur_date1']) && ($_POST['operateur_date1'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="inf"><?php echo '<='; ?>
								<input <?php if(isset($_POST['operateur_date1']) && ($_POST['operateur_date1'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="eg"><?php echo '='; ?>
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
						<tr id="plus">
							<td align="right">Mise à Jour : </td>
							<td>
								<small>yyyy/mm/dd</small>
								<input type="text" onkeyup="verif(this,5)" name="date2" value="<?php if (isset($_POST['date2'])) echo htmlentities(trim($_POST['date2'])); ?>"><br>
								<input <?php if(!isset($_POST['operateur_date2']) || ($_POST['operateur_date2'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="sup"><?php echo '>='; ?>
								<input <?php if(isset($_POST['operateur_date2']) && ($_POST['operateur_date2'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="inf"><?php echo '<='; ?>
								<input <?php if(isset($_POST['operateur_date2']) && ($_POST['operateur_date2'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="eg"><?php echo '='; ?>
							</td>
							<td align="right">Nb Licences :</td>
							<td>
								<input type="text" name="numberL" value="<?php if (isset($_POST['numberL'])) echo htmlentities(trim($_POST['numberL'])); ?>"><br>
								<input <?php if(!isset($_POST['operateur_nombreL']) || ($_POST['operateur_nombreL'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="sup"><?php echo '>='; ?>
								<input <?php if(isset($_POST['operateur_nombreL']) && ($_POST['operateur_nombreL'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="inf"><?php echo '<='; ?>
								<input <?php if(isset($_POST['operateur_nombreL']) && ($_POST['operateur_nombreL'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="eg"><?php echo '='; ?>
							</td>
						</tr>
						<tr id="plus">
							<td align="right">Client :</td>
							<td><input type="text" name="client" value="<?php if (isset($_POST['client'])) echo htmlentities(trim($_POST['client'])); ?>"></td>
							<td align="right">Label :</td>
							<td><input type="text" name="label" value="<?php if (isset($_POST['label'])) echo htmlentities(trim($_POST['label'])); ?>"></td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								<input <?php if(!isset($_POST['type']) || ($_POST['type'] == 'Indifferent')){echo 'checked="checked"';}?> type="radio" name="type" value="Indifferent">Indifférent<br>
								<input <?php if(isset($_POST['type']) && $_POST['type'] == 'Client'){echo 'checked="checked"';}?> type="radio" name="type" value="Client">Client<br>
								<input <?php if(isset($_POST['type']) && $_POST['type'] == 'Prospect'){echo 'checked="checked"';}?> type="radio" name="type" value="Prospect">Prospect
							</td>
							<td align="center" colspan="2">
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
							<td align="center" colspan="2">
								<input <?php if(!isset($_POST['etat']) || ($_POST['etat'] == 'Indifferent')){echo 'checked="checked"';}?> type="radio" name="etat" value="Indifferent">Indifférent<br>
								<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'Active'){echo 'checked="checked"';}?> type="radio" name="etat" value="Active">Active<br>
								<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'Desactive'){echo 'checked="checked"';}?> type="radio" name="etat" value="Desactive">Desactive
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
							<input class='button_titre' type='button' onclick='sortTable(1,false,\"licencesTable\")' value='Installation' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(2,false,\"licencesTable\")' value='Mise à Jour' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(3,true,\"licencesTable\")' value='Client' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(4,true,\"licencesTable\")' value='Label' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(5,true,\"licencesTable\")' value='Licences' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(6,true,\"licencesTable\")' value='Utilisateurs' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(7,true,\"licencesTable\")' value='Etat' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(8,true,\"licencesTable\")' value='Cle' />
						</th>
					</tr>";

					for ($i=0; $i<$y;$i++){
						echo 
						'<tr ';if($depp[$i]==1){ echo 'style="background-color:#FF6666;color:#FFFFFF;"';} echo '>
							<td id="0'.($i+1).'" align="center">'.$Logiciel[$i].'</td>
							<td id="1'.($i+1).'" align="center">'.$Date2[$i].'</td>
							<td id="2'.($i+1).'" align="center">'.$Date[$i].'</td>
							<td id="3'.($i+1).'" align="center"><a href="'.$hr_licences_customers.'&tri='.$Client[$i].'&id='.$ID[$i].'">'.$Client[$i].'</a></td>
							<td id="4'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Label[$i].'</td>
							<td id="5'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Licences[$i].'</td>
							<td id="6'.($i+1).'" align="center">'.$Utilisateurs[$i][0].'</td>
							<td id="7'.($i+1).'" onclick="clic(this,3,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Etat[$i].'</td>
							<td id="8'.($i+1).'" align="center">'.$Cle[$i].'</td>
						</tr>';
					}
					echo '</table>';
				}
			?>
		</td>
	</tr>
</table>