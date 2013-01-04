<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			if (test_Customer() || (isset($_POST['type']) && ($_POST['type'] != "indifferent"))){
				$sql = recherche_Customer('*');	
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
		<script type='text/javascript' src="./Add/tri.js"></script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Clients</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="./backoffice.php?action=customers" method="post">
						<div align="center">
							<table>
								<tr>
									<td align="right">Email : </td>
									<td><input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">Name :</td>
									<td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo htmlentities(trim($_POST['name'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">FirstName :</td>
									<td><input type="text" name="firstName" value="<?php if (isset($_POST['firstName'])) echo htmlentities(trim($_POST['firstName'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">LastName :</td>
									<td><input type="text" name="lastName" value="<?php if (isset($_POST['lastName'])) echo htmlentities(trim($_POST['lastName'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">Tel :</td>
									<td><input type="text" name="tel" value="<?php if (isset($_POST['tel'])) echo htmlentities(trim($_POST['tel']));?>"></td>
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
								</tr>
								<tr><td  colspan="2"  align="center"><input class="button" type="submit" name="recherche" value="Rechercher"></td></tr>
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
									<td id=1 align="center">'.$name[$i].'</td>
									<td align="center">'.$lastName[$i].'</td>
									<td align="center">'.$firstName[$i].'</td>
									<td align="center">'.$email[$i].'</td>
									<td align="center">'.$telephon[$i].'</td>
									<td align="center">'.$mobile[$i].'</td>
									<td align="center">'.$prospect[$i].'</td>
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