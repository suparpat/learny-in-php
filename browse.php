<?php
	require('lib/config.php');
	require('lib/postClass.php');
	$postClass = new postClass();
	$posts = $postClass->fetchPosts();
?>

<html>
	<head>
		<title>Learny: Browse</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Browse</h3>
			</header>

			<?php
				//reddit style list
				foreach ($posts as $post){
					echo "<a href=post.php?id=$post->id>".$post->subject."</a> $post->created_at<br>";
				}

			?>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>

		</div>
	</body>
</html>
