<?php
	if (!isset($_SESSION['login'])) {
		header ('Location: ./Session/deconnexion.php?action="co"');
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
							<td><a class="menu" href="./backoffice.php?action=options&page=chlog">Changer Login</a></td>
							<td><a class="menu" href="./backoffice.php?action=options&page=chpass">Changer Mdp</a></td>
							<td><a class="menu" href="./backoffice.php?action=options&page=chprofil">Profil</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="pages" colspan="2">
					<?php 	
						if(isset($_GET['page'])){
							if($_GET['page'] == 'chlog'){include('./Session/Options/chlog.php');}
							else if($_GET['page'] == 'chpass'){include('./Session/Options/chpass.php');}
							else if($_GET['page'] == 'chprofil'){include('./Session/Options/chprofil.php');}
						}
					?> 
				</td>
			</tr>
		</table>
	</body>
</html>