<!-- credit: https://pinboard.in/ -->
<div id="banner">
	<div id="logo">
	    <a href="index.php">
	    <img src="img/logo.png" class="pin_logo">
	    </a>
		<a id="pinboard_name" href="index.php"><?php echo $lang['learny']; ?></a>
	</div>
	<div id="top_menu">
		<a href="index.php"><?php echo $lang['home']; ?></a>
		&#8231;
		<a href="browse_by_tag.php"><?php echo $lang['tags']; ?></a>
		&#8231;
		<a href="browse_by_type.php"><?php echo $lang['types']; ?></a>
		&#8231;
		<?php
		    if(!$isLoggedIn){
				echo "<a href='register.php'>".$lang['register']."</a>";
				echo "\n&#8231;\n";
		    	echo "<a href='login.php'>".$lang['login']."</a>";
		    }else{
		    	echo "<a href='create.php'>".$lang['create']."</a>";
		    	echo "\n&#8231;\n";
		    	echo "<a href='profile.php'>$userDetails->username</a>";

		    } 
		?>

		
	</div>
	<div style="clear:both"></div>
</div>
