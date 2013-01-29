<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../../Add/define.php';
	require '../../Add/function.php';
	session_start();
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		if (test_Customer() || (isset($_POST['type']) && ($_POST['type'] != "indifferent"))){
			$sql = recherche_Customer('*');	
		}else if (isset($_GET['id'])){
			$sql = 'SELECT * FROM customers WHERE Customer_ID="'.mysql_real_escape_string($_GET['id']).'"';
		}else{
			$sql = 'SELECT * FROM customers';
		}
		
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.mysql_error());
		while($row = mysql_fetch_array($req)){
			$name[$y] = $row['Customer_Name'];
			$lastName[$y] = $row['Customer_LastName'];
			$firstName[$y] = $row['Customer_FirstName'];
			$email[$y] = $row['Customer_Email'];
			$telephon[$y] = $row['Customer_Telephon'];
			$mobile[$y] = $row['Customer_Mobile'];
			$prospect[$y] = $row['Customer_Prospect'];
			$id[$y] = $row['Customer_ID'];
			$y++;
		}
		
		mysql_free_result($req);
			
		for($i=0;$i<$y;$i++){
			if($prospect[$i] == 1){
				$prospect[$i] = "Prospect";
			}else if($prospect[$i] == 0){
				$prospect[$i] = "Client";
			}else{
				$prospect[$i] = 'undefined';
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
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_Customers; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_tri; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_details; ?> ></script>
		<script type='text/javascript' src= <?php echo $sc_JQuery_Color; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_verif; ?>></script>
		<script type='text/javascript' src= <?php echo $sc_modif; ?>></script>
		<script type='text/javascript'>
			function requete(xmlhttp){
				var val = "0";
				if(valType==1){
					 val = document.getElementById("tfCase").value
				}else if(valType == 2){
					if(valeur == "Client"){
						val = "1";
					}
				}
				xmlhttp.send(	
									"id=customers"
									+"&value="+val
									+"&col="+colonne
									+"&idc="+id
								);
			}
			function key(event){
				if(event.keyCode == 13){
					document.getElementById("form").submit();
				}
			}
		</script>
	</head>
	<body onkeypress="key(event)">
		<h2 align="center">Clients</h2>
		<form id="form" action= <?php echo $fo_customers_customers ?> method="post">
			<div class="recherche">
				<button class="button">Detail</button>
				<div class="text1" id="plus">Email :<br>Name :</div>
				<div class="tf1" id="plus">
					<input class="tf" type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"><br>
					<input class="tf" type="text" name="name" value="<?php if (isset($_POST['name'])) echo htmlentities(trim($_POST['name'])); ?>">
				</div>
				<div class="text2" id="plus">LastName :<br>FirstName :</div>
				<div class="tf2" id="plus">
					<input class="tf" type="text" name="lastName" value="<?php if (isset($_POST['lastName'])) echo htmlentities(trim($_POST['lastName'])); ?>"><br>
					<input class="tf" type="text" name="firstName" value="<?php if (isset($_POST['firstName'])) echo htmlentities(trim($_POST['firstName'])); ?>">
				</div>
				<div class="text3" id="plus">Tel :<br>Mobile :</div>
				<div class="tf3" id="plus">
					<input class="tf" type="text" name="tel" value="<?php if (isset($_POST['tel'])) echo htmlentities(trim($_POST['tel']));?>"><br>
					<input class="tf" type="text" name="mobile" value="<?php if (isset($_POST['mobile'])) echo htmlentities(trim($_POST['mobile'])); ?>">
				</div>
				<div class="type">
					<input <?php if(!isset($_POST['type']) || ($_POST['type'] == 'indifferent')){echo 'checked="checked"';}?> type="radio" name="type" value="indifferent">Indifferent<br>
					<input <?php if(isset($_POST['type']) && $_POST['type'] == 'client'){echo 'checked="checked"';}?> type="radio" name="type" value="client">Client<br>
					<input <?php if(isset($_POST['type']) && $_POST['type'] == 'prospect'){echo 'checked="checked"';}?> type="radio" name="type" value="prospect">Prospect
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
						<input class='button_titre' type='button' onclick='sortTable(0,true,\"Table\")' value='Name' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(1,true,\"Table\")' value='LastName' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(2,true,\"Table\")' value='FirstName' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(3,true,\"Table\")' value='Email' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(4,true,\"Table\")' value='Telephone' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(5,true,\"Table\")' value='Mobile' />
					</th>
					<th class='titre' align='center'>
						<input class='button_titre' type='button' onclick='sortTable(6,true,\"Table\")' value='Type' />
					</th>
				</tr>";

				for ($i=0; $i<$y;$i++){
					echo 
					'<tr>
						<td id="0'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$name[$i].'</td>
						<td id="1'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$lastName[$i].'</td>
						<td id="2'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$firstName[$i].'</td>
						<td id="3'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$email[$i].'</td>
						<td id="4'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$telephon[$i].'</td>
						<td id="5'.($i+1).'" onclick="clic(this,1,'.($i+1).',\''.$id[$i].'\')" align="center">'.$mobile[$i].'</td>
						<td id="6'.($i+1).'" onclick="clic(this,2,'.($i+1).',\''.$id[$i].'\')" align="center">'.$prospect[$i].'</td>
					</tr>';
				}
				echo '</table>';
			}
		?>
	</body>
</html>