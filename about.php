<?php
	require('lib/config.php');
?>

<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['about']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>About</h3>
			</header>
			<!--To do: make this page with ckeditor?-->
			<h4>Learny is place to share knowledge, stories, thoughts, ideas, et cetera—</h4>
			<p style="text-indent: 0px;">
				Learny wants to be the place where people come to learn and share. Some of Learny's inspirations include:
				Reddit (the upvote/downvote feature and design), StackOverflow (the reputation system) and Wikipedia.
			</p>

			<h4>Design</h4>
			<p style="text-indent: 0px;">
				Learny has a clutter-free design, allowing its users to focus on the actual contents. The design is actually based on <a href="https://pinboard.in/">Pinboard</a>'s design combined with those of other places.
			</p>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
