<!-- credit: https://pinboard.in/ -->
<div id="banner">
	<div id="logo">
	    <a href="index.php">
	    <img src="img/logo.png" class="pin_logo">
	    </a>
		<a id="pinboard_name" href="index.php">Learny</a>
	</div>
	<div id="top_menu">
		<a href="browse_by_tag.php">tags</a>
		&#8231;
		<?php
		    if(!$isLoggedIn){
				echo "<a href='register.php'>register</a>";
				echo "\n&#8231;\n";
		    	echo "<a href='login.php'>log in</a>";
		    }else{
		    	echo "<a href='create.php'>create</a>";
		    	echo "\n&#8231;\n";
		    	echo "<a href='profile.php'>$userDetails->username</a>";

		    } 
		?>

		
	</div>
	<div style="clear:both"></div>
</div>
