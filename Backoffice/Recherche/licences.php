<!DOCTYPE html>
<?php
	date_default_timezone_set("Europe/Paris");
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');
		
		if (Test_Licences() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous")) || (isset($_POST['etat']) && ($_POST['etat'] != "Indifferent")) || (isset($_POST['type']) && ($_POST['type'] != "Indifferent"))){
				$sql = recherche_licences();
			}else{
				$sql = 'SELECT C.Customer_ID AS ID,P.Product_Name AS Logiciel,max(K.KeyActivity_Date) AS Date, Ku.InitialisationDate AS Date2,C.Customer_Email AS Contact,Pk.Label AS Client,Pk.Licences AS Licences,Pk.Revoked AS Etat,Pk.InstallKey AS Cle
						FROM productkey AS Pk
						LEFT JOIN keyactivityCA AS K ON Pk.InstallKey = K.ProductKey
						LEFT JOIN products AS P ON Pk.ProductID = P.Product_ID
						LEFT JOIN customers AS C ON Pk.CustomerID = C.Customer_ID
						LEFT JOIN keyusage AS Ku ON Pk.RowID = Ku.KeyRowID
						GROUP BY Pk.InstallKey';
			}
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		$y=0;
		while($row = mysql_fetch_array($req)){
			$ID[$y] = $row['ID'];
			$Logiciel[$y] = $row['Logiciel'];
			$Date[$y] = $row['Date'];
			$Date2[$y] = $row['Date2'];
			$Contact[$y] = $row['Contact'];
			$Client[$y] = $row['Client'];
			$Licences[$y] = $row['Licences'];
			$Etat[$y] = $row['Etat'];
			$Cle[$y] = $row['Cle'];
			$y++;
		}

		mysql_free_result($req);
		for($i=0;$i<$y;$i++){
			$Utilisateurs[$i] = RequeteSQL_Select('NumUsers', 'keyactivityCA', 'KeyActivity_Date',mysql_real_escape_string($Date[$i]),"ProductKey",mysql_real_escape_string($Cle[$i]));
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
		header ($he_deconnexion);
		exit();
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_Licences; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_details; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_modif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_valide; ?>></script>
		<script type='text/javascript'>
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
				sortTable(2, false, "Table");
			});
			
			function key(event){
				if(event.keyCode == 13){
					document.getElementById("form").submit();
				}
			}
		</script>
	</head>
	<body onkeypress="key(event)">
		<h2 align="center">Licences</h2>
		<form id="form" name="formVal" onsubmit="return valide('licences')" action= <?php echo $fo_licences_licences; ?> method="post">
			<div class="recherche">
				<button class="button">Detail</button>
				
				<div class="text1" id="plus">Installation :</div>
				<div class="tf1" id="plus">
					<small>yyyy/mm/dd</small><br>
					<input type="text" onkeyup="verif(this,5)" name="date1" value="<?php if (isset($_POST['date1'])) echo htmlentities(trim($_POST['date1'])); ?>"><br>
					<input <?php if(!isset($_POST['operateur_date1']) || ($_POST['operateur_date1'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="sup"><?php echo '>='; ?>
					<input <?php if(isset($_POST['operateur_date1']) && ($_POST['operateur_date1'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="inf"><?php echo '<='; ?>
					<input <?php if(isset($_POST['operateur_date1']) && ($_POST['operateur_date1'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date1" value="eg"><?php echo '='; ?>
				</div>
				
				<div class="text2" id="plus">Mise à Jour :</div>
				<div class="tf2" id="plus">
					<small>yyyy/mm/dd</small><br>
					<input type="text" onkeyup="verif(this,5)" name="date2" value="<?php if (isset($_POST['date2'])) echo htmlentities(trim($_POST['date2'])); ?>"><br>
					<input <?php if(!isset($_POST['operateur_date2']) || ($_POST['operateur_date2'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="sup"><?php echo '>='; ?>
					<input <?php if(isset($_POST['operateur_date2']) && ($_POST['operateur_date2'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="inf"><?php echo '<='; ?>
					<input <?php if(isset($_POST['operateur_date2']) && ($_POST['operateur_date2'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date2" value="eg"><?php echo '='; ?>
				</div>
				
				<div class="text3" id="plus">Contact :</div>
				<div class="tf3" id="plus"><input type="text" name="contact" value="<?php if (isset($_POST['contact'])) echo htmlentities(trim($_POST['contact'])); ?>"></div>
				
				<div class="text4" id="plus">Client :</div>
				<div class="tf4" id="plus"><input type="text" name="client" value="<?php if (isset($_POST['client'])) echo htmlentities(trim($_POST['client'])); ?>"></div>
				
				<div class="text5" id="plus">Cle :</div>
				<div class="tf5" id="plus"><input type="text" onkeyup="verif(this,6)" name="key" value="<?php if (isset($_POST['key'])) echo htmlentities(trim($_POST['key'])); ?>"></div>
				
				<div class="text6" id="plus">Nb Utilisateurs :</div>
				<div class="tf6" id="plus">
					<input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"><br>
					<input <?php if(!isset($_POST['operateur_nombre']) || ($_POST['operateur_nombre'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="sup"><?php echo '>='; ?>
					<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="inf"><?php echo '<='; ?>
					<input <?php if(isset($_POST['operateur_nombre']) && ($_POST['operateur_nombre'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombre" value="eg"><?php echo '='; ?>
				</div>
				
				<div class="text7" id="plus">Nb Licences :</div>
				<div class="tf7" id="plus">
					<input type="text" name="numberL" value="<?php if (isset($_POST['numberL'])) echo htmlentities(trim($_POST['numberL'])); ?>"><br>
					<input <?php if(!isset($_POST['operateur_nombreL']) || ($_POST['operateur_nombreL'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="sup"><?php echo '>='; ?>
					<input <?php if(isset($_POST['operateur_nombreL']) && ($_POST['operateur_nombreL'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="inf"><?php echo '<='; ?>
					<input <?php if(isset($_POST['operateur_nombreL']) && ($_POST['operateur_nombreL'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_nombreL" value="eg"><?php echo '='; ?>
				</div>
				
				<div class="type">
					<input <?php if(!isset($_POST['type']) || ($_POST['type'] == 'Indifferent')){echo 'checked="checked"';}?> type="radio" name="type" value="Indifferent">Indifférent<br>
					<input <?php if(isset($_POST['type']) && $_POST['type'] == 'Client'){echo 'checked="checked"';}?> type="radio" name="type" value="Client">Client<br>
					<input <?php if(isset($_POST['type']) && $_POST['type'] == 'Prospect'){echo 'checked="checked"';}?> type="radio" name="type" value="Prospect">Prospect
				</div>
				
				<div class="logiciel">
					<?php
						$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
						mysql_select_db ($SQL_Cdw_name, $base) or die('Erreur Selection Base SQL !');
						$sql = 'SELECT Product_Name FROM products';
						$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
						?><input <?php if(!isset($_POST['logiciel']) || ($_POST['logiciel'] == 'tous')){echo 'checked="checked"';}?> type="radio" name="logiciel" value="tous">Tous<br><?php
						while($row = mysql_fetch_array($req)){
							if(($row['Product_Name'] != "S2GS")&&($row['Product_Name'] != "Mig6")){
								?><input <?php if(isset($_POST['logiciel']) && $_POST['logiciel'] == $row['Product_Name']){echo 'checked="checked"';}?> type="radio" name="logiciel" value=<?php echo '"'.$row['Product_Name'].'"'?>><?php if($row['Product_Name'] == "CloudMailMover"){ echo "CloudXFer"; }else{ echo $row['Product_Name']; } ?><br><?php
							}
						}
						mysql_free_result($req);
						mysql_close();
					?>
				</div>
				
				<div class="etat">
					<input <?php if(!isset($_POST['etat']) || ($_POST['etat'] == 'Indifferent')){echo 'checked="checked"';}?> type="radio" name="etat" value="Indifferent">Indifférent<br>
					<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'Active'){echo 'checked="checked"';}?> type="radio" name="etat" value="Active">Active<br>
					<input <?php if(isset($_POST['etat']) && $_POST['etat'] == 'Desactive'){echo 'checked="checked"';}?> type="radio" name="etat" value="Desactive">Desactive
				</div>
				
				<input class="button" type="submit" name="recherche" value="Rechercher">
			</div>
		</form>
		<p><?php echo $y." resultats"?></p></td></tr>
		<?php
			if($y > 0){
				echo
				"<table id='Table'>
				<tr>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(0,true,\"Table\")' value='Logiciel' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(1,false,\"Table\")' value='Installation' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(2,false,\"Table\")' value='Mise à Jour' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(3,true,\"Table\")' value='Contact' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(4,true,\"Table\")' value='Client' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(5,true,\"Table\")' value='Licences' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(6,true,\"Table\")' value='Utilisateurs' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(7,true,\"Table\")' value='Etat' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(8,true,\"Table\")' value='Cle' />
					</th>
				</tr>";

				for ($i=0; $i<$y;$i++){
					echo 
					'<tr ';if($depp[$i]==1){ echo 'style="background-color:#FF6666;color:#FFFFFF;"';} echo '>
						<td id="0'.($i+1).'" align="center">'.$Logiciel[$i].'</td>
						<td id="1'.($i+1).'" align="center">'.$Date2[$i].'</td>
						<td id="2'.($i+1).'" align="center">'.$Date[$i].'</td>
						<td id="3'.($i+1).'" align="center"><a target="_blank" href="'.$hr_licences_customers.'?tri='.$Contact[$i].'&id='.$ID[$i].'">'.$Contact[$i].'</a></td>
						<td id="4'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Client[$i].'</td>
						<td id="5'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Licences[$i].'</td>
						<td id="6'.($i+1).'" align="center">'.$Utilisateurs[$i][0].'</td>
						<td id="7'.($i+1).'" onclick="clic(this,3,'.($i+1).',\''.$ID[$i].'\')" align="center">'.$Etat[$i].'</td>
						<td id="8'.($i+1).'" align="center">'.$Cle[$i].'</td>
					</tr>';
				}
				echo '</table>';
			}
		?>
	</body>
</html>