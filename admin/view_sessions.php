<html>
	<head>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
		
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>View Sessions</h3>
			</header>
			<?php
			    session_start();
				echo '<pre>';
				var_dump($_SESSION);
				echo '</pre>';
			?>
		</div>



	</body>
</html>

