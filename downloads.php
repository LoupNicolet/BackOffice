<?php
	if (isset($_SESSION['login'])){
		if (isset($_POST['recherche'])){
			$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
			mysql_select_db ($SQL_Cdw_name, $base);
			
			if (test_Downloads() || (isset($_POST['logiciel']) && ($_POST['logiciel'] != "tous"))){
				$sql = recherche_downloads('*');	
			}else{
				$sql = 'SELECT * FROM downloadkey';
			}
			
			$y = 0;
			$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
			while($row = mysql_fetch_array($req)){
				$time[$y] = date("Y/m/d - H:i:s",$row['timestamp']);
				$email[$y] = $row['mail'];
				$number[$y] = $row['downloads'];
				$logiciel[$y] = $row['Application'];
				$y++;
			}
			
			mysql_free_result($req);
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
				
				mybody = document.getElementById('downloadsTable').getElementsByTagName('tbody')[0];
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
			<tr><h2 align="center">Downloads</h2></tr>
			<tr>
				<td>
					<form id="form" class="recherche" action="gestion.php?action=downloads" method="post">
						<div align="center">
							<table>
								<tr>
									<td align="right">Date : </td>
									<td><input type="text" name="time" value="<?php if (isset($_POST['time'])) echo htmlentities(trim($_POST['time'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">Email :</td>
									<td><input type="text" name="email" value="<?php if (isset($_POST['email'])) echo htmlentities(trim($_POST['email'])); ?>"></td>
								</tr>
								<tr>
									<td align="right">Downloads :</td>
									<td><input type="text" name="number" value="<?php if (isset($_POST['number'])) echo htmlentities(trim($_POST['number'])); ?>"></td>
								</tr>
								<tr>
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
							"<table id='downloadsTable'>
							<tr>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(0,false)' value='Date' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(1,true)' value='Email' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(2,true)' value='Downloads' />
								</th>
								<th class='titre' align='center'>
									<input class='button_titre' type='button' onclick='sortTable(3,true)' value='Logiciel' />
								</th>";

							for ($i=0; $i<$y;$i++){
								echo 
								'<tr>
									<td id=1 align="center">'.$time[$i].'</td>
									<td align="center">'.$email[$i].'</td>
									<td align="center">'.$number[$i].'</td>
									<td align="center">'.$logiciel[$i].'</td>
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