<?php
	require('lib/config.php');
	require('lib/typeClass.php');
	$typeClass = new typeClass();
	$types = $typeClass->fetchTypes();
?>

<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['types']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['types']; ?></h3>
			</header>



			<?php
				foreach($types as $type){
					echo "<p><a href='type.php?name=$type->name'>$type->name</a></p>";
				}

			?>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
