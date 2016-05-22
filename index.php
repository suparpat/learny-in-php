<?php
	require('lib/config.php');
?>

<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
			<?php 
				if(!$isLoggedIn){
					echo "<h3>Welcome</h3>";
				}else{
					echo "<h3>Welcome, $userDetails->username</h3>";
				}
			?>

				<aside>Share what you know!</aside>
			</header>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
