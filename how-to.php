<?php
	require('lib/config.php');
?>

<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['how-to']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['how-to']; ?></h3>
			</header>
				<?php echo $lang['how-to-content']; ?>
				<?php echo $lang['how-to-score']; ?>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
