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
				if($name[$i] == null){$name[$i] = "/";}
				if($lastName[$i] == null){$lastName[$i] = "/";}
				if($firstName[$i] == null){$firstName[$i] = "/";}
				if($email[$i] == null){$email[$i] = "/";}
				if($telephon[$i] == null){$telephon[$i] = "/";}
				if($mobile[$i] == null){$mobile[$i] = "/";}
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
		<script type='text/javascript'>	
		
			var sens = false;
			var cur_col = -1;
			
			function dASC(a, b){
				return(a[1] - b[1]);
			}
			
			function dDESC(a, b){
				return(b[1] - a[1]);
			}
			
			function ASC(a, b){
				var x = parseInt(a[1], 10);
				var y = parseInt(b[1], 10);
				var c1 = replaceSpec(a[1]);
				var c2 = replaceSpec(b[1]);
 
				if (isNaN(x) || isNaN(y)){
					if (c1 > c2){
						return 1;
					} else if(c1 < c2){
						return -1;
					} else {
						return 0;
					}
				} else {
					return(a[1] - b[1]);
				}
			}
		
			function DESC(a, b){
				var x = parseInt(a[1], 10);
				var	y = parseInt(b[1], 10);
				var	c1 = replaceSpec(a[1]);
				var	c2 = replaceSpec(b[1]);
 
				if (isNaN(x) || isNaN(y)){
					if (c1 > c2){
						return -1;
					} else if(c1 < c2){
						return 1;
					} else {
						return 0;
					}
				} else {
					return(b[1] - a[1]);
				}
			}
		
		
			 
			function sortTable(colonne, type){
			
				if(cur_col != colonne){sens = false;}
				
				if(sens){
					if(type){ordre = DESC;}
					else{ordre = dDESC;}
					sens = false;
					cur_col = colonne;
				}
				else{
					if(type){ordre = ASC;}
					else{ordre = dASC;}
					sens = true;
					cur_col = colonne;
				}
				
				mybody = document.getElementById('customersTable').getElementsByTagName('tbody')[0];
				lignes = mybody.getElementsByTagName('tr');
				
				var tamp = new Array();
				var z = new Array();
				var i=0;
				z.length=0;
				tamp.length=0;
				
				while(lignes[++i]){
					if(type){
						tamp.push([lignes[i],lignes[i].getElementsByTagName('td')[colonne].innerHTML]);
					}else{
						var date = new Date(lignes[i].getElementsByTagName('td')[colonne].innerHTML);
						tamp.push([lignes[i],date]);
						delete date;
					}
				}
				
				tamp.sort(ordre);
				
				var j=0;
				while(tamp[++j]){
					mybody.appendChild(tamp[j][0]);
				}
			}
			
			function replaceSpec(Texte){
				var TabSpec = {"à":"a","á":"a","â":"a","ã":"a","ä":"a","å":"a","ò":"o","ó":"o","ô":"o","õ":"o","ö":"o","ø":"o","è":"e","é":"e","ê":"e","ë":"e","ç":"c","ì":"i","í":"i","î":"i","ï":"i","ù":"u","ú":"u","û":"u","ü":"u","ÿ":"y","ñ":"n","-":" ","_":" "},
				reg=/[àáâãäåòóôõöøèéêëçìíîïùúûüÿñ_-]/gi;
				return Texte.replace(reg,function(){return TabSpec[arguments[0].toLowerCase()];}).toLowerCase();
			}
			
		</script>
	</head>
	<body>
		<table>
			<tr><h2 align="center">Customers</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="gestion.php?action=customers" method="post">
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
								<tr><td  colspan="2" align="center"><input class="button" type="submit" name="recherche" value="Rechercher"></td></tr>
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
									<input class='button_titre' type='button' onclick='sortTable(0,true)' value='Name' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,true)' value='LastName' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true)' value='FirstName' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true)' value='Email' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(4,true)' value='Telephone' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(5,true)' value='Mobile' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(6,true)' value='Type' />
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