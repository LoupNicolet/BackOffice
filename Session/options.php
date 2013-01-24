<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../Add/define.php';
	session_start();
	if (!isset($_SESSION['login'])) {
		header ('Location: /Session/deconnexion.php?action="co"');
		exit();
	}
?>
<html id="PagesFrame">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css; ?>>
	</head>
	<body>
		<table>	
			<tr>
				<td><h2  align="center">Options</h2></td>
				<td>
					<table class="menu" style="background-color:#245DB2;">
						<tr>
							<td><a class="menu" href= <?php echo $hr_options_chlog; ?>>Changer Login</a></td>
							<td><a class="menu" href= <?php echo $hr_options_chpass; ?>>Changer Mdp</a></td>
							<td><a class="menu" href= <?php echo $hr_options_chprofil; ?>>Profil</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php 	
						if(isset($_GET['page'])){
							if($_GET['page'] == 'chlog'){echo '<iframe src='.$in_options_chlog.' frameborder="0" height="400" width="100%"></iframe>';}
							else if($_GET['page'] == 'chpass'){echo '<iframe src='.$in_options_chpass.' frameborder="0" height="400" width="100%"></iframe>';}
							else if($_GET['page'] == 'chprofil'){echo '<iframe src='.$in_options_chprofil.' frameborder="0" height="400" width="100%"></iframe>';}
						}
					?> 
				</td>
			</tr>
		</table>
	</body>
</html>