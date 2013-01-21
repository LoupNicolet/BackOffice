<?php
	if (isset($_SESSION['login'])){
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		if (test_Customer() || (isset($_POST['type']) && ($_POST['type'] != "indifferent"))){
			$sql = recherche_Customer('*');	
		}else if (isset($_GET['id'])){
			$sql = 'SELECT * FROM customers WHERE Customer_ID="'.$_GET['id'].'"';
		}else{
			$sql = 'SELECT * FROM customers';
		}
		
		$y = 0;
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
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
</script>
<table>
	<tr><h2 align="center">Clients</h2></tr>
	<tr>
		<td>
			<form id="form" class="recherche" action= <?php echo $fo_customers_customers ?> method="post">
				<div align="center">
					<table>
						<tr>
							<td align="center" colspan="6"><button class="button">Detail</button></td>
						</tr>
						<tr id="plus">
							<td align="right">Email : </td>
							<td><input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"></td>
							<td align="right">FirstName :</td>
							<td><input type="text" name="firstName" value="<?php if (isset($_POST['firstName'])) echo htmlentities(trim($_POST['firstName'])); ?>"></td>
							<td align="right">Tel :</td>
							<td><input type="text" name="tel" value="<?php if (isset($_POST['tel'])) echo htmlentities(trim($_POST['tel']));?>"></td>
						</tr>
						<tr id="plus">
							<td align="right">Name :</td>
							<td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo htmlentities(trim($_POST['name'])); ?>"></td>
							<td align="right">LastName :</td>
							<td><input type="text" name="lastName" value="<?php if (isset($_POST['lastName'])) echo htmlentities(trim($_POST['lastName'])); ?>"></td>
							<td align="right">Mobile :</td>
							<td><input type="text" name="mobile" value="<?php if (isset($_POST['mobile'])) echo htmlentities(trim($_POST['mobile'])); ?>"></td>
						</tr>
						<tr>
							<td colspan="6" align="center">
								<input <?php if(!isset($_POST['type']) || ($_POST['type'] == 'indifferent')){echo 'checked="checked"';}?> type="radio" name="type" value="indifferent">Indifferent<br>
								<input <?php if(isset($_POST['type']) && $_POST['type'] == 'client'){echo 'checked="checked"';}?> type="radio" name="type" value="client">Client<br>
								<input <?php if(isset($_POST['type']) && $_POST['type'] == 'prospect'){echo 'checked="checked"';}?> type="radio" name="type" value="prospect">Prospect
							</td>
						</tr>
						<tr><td  colspan="6"  align="center"><input class="button" type="submit" name="recherche" value="Rechercher"></td></tr>
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
					"<table id='customersTable'>
					<tr>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(0,true,\"customersTable\")' value='Name' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(1,true,\"customersTable\")' value='LastName' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(2,true,\"customersTable\")' value='FirstName' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(3,true,\"customersTable\")' value='Email' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(4,true,\"customersTable\")' value='Telephone' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(5,true,\"customersTable\")' value='Mobile' />
						</th>
						<th class='titre' align='center'>
							<input class='button_titre' type='button' onclick='sortTable(6,true,\"customersTable\")' value='Type' />
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
		</td>
	</tr>
</table>