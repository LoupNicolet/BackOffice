<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require '../Add/define.php';
	session_start();
	if (!isset($_SESSION['login'])) {
		header ($he_deconnexion);
		exit();
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_options; ?>>
	</head>
	<body>
		<div class="titre"><h2 align="center">Options</h2></div>
		<div class="choix">
			<a href= <?php echo $hr_options_chlog; ?>>Changer Login</a>
			<a href= <?php echo $hr_options_chpass; ?>>Changer Mdp</a>
			<a href= <?php echo $hr_options_chprofil; ?>>Profil</a>
		</div>
		<div class="page">
			<?php 	
				if(isset($_GET['page'])){
					if($_GET['page'] == 'chlog'){echo '<iframe src='.$in_options_chlog.' frameborder="0" height="400" width="100%"></iframe>';}
					else if($_GET['page'] == 'chpass'){echo '<iframe src='.$in_options_chpass.' frameborder="0" height="400" width="100%"></iframe>';}
					else if($_GET['page'] == 'chprofil'){echo '<iframe src='.$in_options_chprofil.' frameborder="0" height="400" width="100%"></iframe>';}
				}
			?> 
		</div>
	</body>
</html>