<?php
	if (!isset($_SESSION['login'])) {
		require "../Add/define.php";
		header ($he_deconnexion);
		exit();
	}
?>
<table>	
	<tr>
		<td><h2  align="center">Options</h2></td>
		<td>
			<table class="menu">
				<tr>
					<td><a class="menu" href= <?php echo $hr_options_chlog; ?>>Changer Login</a></td>
					<td><a class="menu" href= <?php echo $hr_options_chpass; ?>>Changer Mdp</a></td>
					<td><a class="menu" href= <?php echo $hr_options_chprofil; ?>>Profil</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td id="pages" colspan="2">
			<?php 	
				if(isset($_GET['page'])){
					if($_GET['page'] == 'chlog'){include($in_options_chpass);}
					else if($_GET['page'] == 'chpass'){include($in_options_chprofil);}
					else if($_GET['page'] == 'chprofil'){include($in_options_chlog);}
				}
			?> 
		</td>
	</tr>
</table>