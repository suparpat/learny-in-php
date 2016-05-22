<?php
	require('lib/config.php');

	if(isset($_GET['id'])){
		require('lib/postClass.php');
		$postClass = new postClass();
		$post = $postClass->fetchAPost($_GET['id']);
	}

?>

<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>
					<?php
						echo "$post->subject";
						echo "<code> by ".$post->uid." on ".$post->created_at."</code>";
					?>

				</h3>
			</header>

			<?php
				echo "<p>$post->content</p>";
			?>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
