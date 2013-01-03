<?php
	if (isset($_SESSION['login'])) {
	
		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		$data = RequeteSQL_Select('type_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		if ($data[0] == null){
			header('Location: ../Session/deconnexion.php?action="err"');
			exit();
		}
		else if ($data[0] == "admin") {
			$_SESSION['type'] = $data[0];
		}
		else{
			header('Location: ../Session/deconnexion.php?action="err"');
		}

		/*$data = RequeteSQL_Select('lastName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['lastName'] = $data[0];
		$data = RequeteSQL_Select('firstName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['firstName'] = $data[0];*/
		mysql_close();
	}else{
		header ('Location: ../Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html>
	<body>
		<table>
				
			<tr>
				<td><h2  align="center">Options</h2></td>
				<td>
					<table class="menu">
						<tr>
							<td><a class="menu" href="options.php?action=chlog">Changer de Login</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages">
					<?php 	
						if(isset($_GET['action'])){
							if($_GET['action'] == 'chlog'){include('chlog.php');}
						}
					?> 
				</td>
			</tr>
		</table>
	</body>
</html>