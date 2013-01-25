<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require 'Add/define.php';
	require 'Add/function.php';
	session_start();
	if (isset($_SESSION['login'])) {

		$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
		mysql_select_db ($SQL_Cdw_name, $base);
		
		$data = RequeteSQL_Select('type_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		if ($data[0] == "admin") {
			$_SESSION['type'] = $data[0];
		}
		else if ($data[0] == null) {
			$_SESSION['type'] = "";
		}
		else{
			header('Location: Session/deconnexion.php?action="err"');
		}
		$data = RequeteSQL_Select('lastName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['lastName'] = $data[0];
		$data = RequeteSQL_Select('firstName_operator', 'operator', 'login_operator', mysql_real_escape_string($_SESSION['login']),"","");
		$_SESSION['firstName'] = $data[0];
		mysql_close();
	}else{
		header ('Location: /Session/deconnexion.php?action="co"');
		exit();
	}
?>

<html id="backoffice">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" > 
		<title>BackOffice</title>
		<link rel="stylesheet" type="text/css" href= <?php echo $hr_Css_Back0ffice; ?>>
		<script type='text/javascript' src= <?php echo $sc_JQuery; ?> ></script>
		<script language="JavaScript">
			function autoResize(id){
				document.getElementById(id).height= (document.documentElement.clientHeight - 80) + "px";
				document.getElementById(id).width= (document.documentElement.clientWidth - 20) + "px";
			}
		</script>
	</head>
	<body>
		<div class="menu">
			<a class="pages" href= <?php echo $hr_backoffice_customers; ?> >Clients</a>
			<a class="pages" href= <?php echo $hr_backoffice_downloads; ?> >Telechargements</a>
			<a class="pages" href= <?php echo $hr_backoffice_licences; ?> >Licences</a>
			<a class="pages" href= <?php echo $hr_backoffice_operateurs; ?> >Operateurs</a>
			<a class="pages" href= <?php echo $hr_backoffice_options; ?> >Options</a>
			<a class="deco" href= <?php echo $hr_backoffice_deconnexion; ?> >Deconnexion</a>
		</div>
		<div class="log"><small><?php echo "[".htmlentities(trim($_SESSION['type']))."]"."[".htmlentities(trim($_SESSION['firstName'])).htmlentities(trim($_SESSION['lastName']))."]";?><small></div>
		<div class="pages">
			<?php 	
				if(isset($_GET['action'])){
					if($_GET['action'] == 'customers'){ echo '<iframe id="iframeCustomers" onLoad="autoResize(\'iframeCustomers\')" src='.$in_backoffice_customers.' frameborder="0" height="0" width="0"></iframe>';}
					else if($_GET['action'] == 'downloads'){ echo '<iframe id="iframeDownloads" onLoad="autoResize(\'iframeDownloads\')" src='.$in_backoffice_downloads.' frameborder="0" height="0" width="0"></iframe>';}
					else if($_GET['action'] == 'licences'){echo '<iframe id="iframeDownloads" onLoad="autoResize(\'iframeDownloads\')" src='.$in_backoffice_licences.' frameborder="0" height="0" width="0"></iframe>';}
					else if($_GET['action'] == 'operateurs'){echo '<iframe id="iframeDownloads" onLoad="autoResize(\'iframeDownloads\')" src='.$in_backoffice_operateurs.' frameborder="0" height="0" width="0"></iframe>';}
					else if($_GET['action'] == 'options'){echo '<iframe id="iframeDownloads" onLoad="autoResize(\'iframeDownloads\')" src='.$in_backoffice_options.' frameborder="0" height="0" width="0"></iframe>';}
				}
			?> 
		</div>
	</body>
</html>