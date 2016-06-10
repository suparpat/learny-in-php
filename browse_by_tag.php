<?php
	require('lib/config.php');
	require('lib/tagClass.php');
	$tagClass = new tagClass();
	$tags = $tagClass->fetchTags();
?>

<html>
	<head>
		<title>Learny: About</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Browse by tag</h3>
			</header>


			<?php
				foreach($tags as $tag){
					echo "<p><a href='tag.php?name=$tag->name'>$tag->name</a></p>";
				}

			?>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
