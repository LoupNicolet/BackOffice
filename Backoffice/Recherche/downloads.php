<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	date_default_timezone_set("Europe/Paris");
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		if (test_Downloads() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous")) || (isset($_POST['number']) && ($_POST['number'] != "Tous"))){
			$sql = recherche_downloads('*');	
		}else{
			$sql = 'SELECT * FROM downloadkey';
		}
		
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		
		while($row = mysql_fetch_array($req)){
			$time[$y] = date("Y/m/d  H:i:s",$row['timestamp']);
			$email[$y] = $row['mail'];
			$number[$y] = $row['downloads'];
			$logiciel[$y] = $row['Application'];
			if($logiciel[$y] == "CloudMailMover"){
				$logiciel[$y] = "CloudXFer";
			}
			$y++;
		}
		for($i=0;$i<$y;$i++){
			if($number[$i] == 0){
				$number[$i] = "Non-Téléchargé";
			}else if($number[$i] == 1){
				$number[$i] = "Ok";
			}else{
				$number[$i] = 'Ok +';
			}
		}
		mysql_free_result($req);
		mysql_close();
	}else{
		header ($he_deconnexion);
		exit();
	}	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_Download; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_details; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_valide; ?>></script>
		<script type='text/javascript'>
			$(document).ready(function(){
				sortTable(0, false, "Table");
			});
		</script>
	</head>
	<body>
		<h2 align="center">Telechargements</h2>
		<form id="form" action=<?php echo $fo_downloads_downloads; ?> method="post" onsubmit="return valide('downloads')">
			<div class="recherche">
				<button class="button">Detail</button>
				<div class="text1" id="plus">Date :</div>
				<div class="tf1" id="plus">
					<small>yyyy/mm/dd</small><br>
					<input type="text" onkeyup="verif(this,5)" name="time" value="<?php if (isset($_POST['time'])){ echo htmlentities(trim($_POST['time']));}?>"><br>
					<input <?php if(!isset($_POST['operateur_date']) || ($_POST['operateur_date'] == 'sup')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="sup"><?php echo '>='; ?>
					<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'inf')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="inf"><?php echo '<='; ?>
					<input <?php if(isset($_POST['operateur_date']) && ($_POST['operateur_date'] == 'eg')){echo 'checked="checked"';}?> type="radio" name="operateur_date" value="eg"><?php echo '='; ?>
				</div>
				<div class="text2" id="plus">Email :</div>
				<div class="tf2" id="plus">
					<input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>">
				</div>
				<div class="text3" id="plus">Downloads :</div>
				<div class="tf3" id="plus">
					<input <?php if(!isset($_POST['number']) || ($_POST['number'] == 'Tous')){echo 'checked="checked"';}?> type="radio" name="number" value="Tous">Tous<br>
					<input <?php if(isset($_POST['number']) && $_POST['number'] == 'Non-Telecharge'){echo 'checked="checked"';}?> type="radio" name="number" value="Non-Telecharge">Non-Téléchargé<br>
					<input <?php if(isset($_POST['number']) && $_POST['number'] == 'Telecharge'){echo 'checked="checked"';}?> type="radio" name="number" value="Telecharge">Téléchargé
				</div>
				<div class="logiciel">
					<?php
						$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
						mysql_select_db ($SQL_Cdw_name, $base);
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
				<input class="button" type="submit" name="recherche" value="Rechercher">
			</div>
		</form>
		<p><?php echo $y." resultats"?></p>
		<?php
			if($y > 0){
				echo
				"<table id='Table'>
				<tr>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(0,false,\"Table\")' value='Date' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(1,true,\"Table\")' value='Email' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(2,true,\"Table\")' value='Downloads' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(3,true,\"Table\")' value='Logiciel' />
					</th>";

				for ($i=0; $i<$y;$i++){
					echo 
					'<tr>
						<td align="center">'.$time[$i].'</td>
						<td align="center">'.$email[$i].'</td>
						<td align="center">'.$number[$i].'</td>
						<td align="center">'.$logiciel[$i].'</td>
					</tr>';
				}
				echo '</table>';
			}
		?>
	</body>
</html>