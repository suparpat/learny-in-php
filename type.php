<?php
	require_once('lib/config.php');
	require_once('lib/typeClass.php');
	require_once('lib/postClass.php');

	if(isset($_GET['name'])){
		$typeClass = new typeClass();
		$posts = $typeClass->fetchPostsByType($_GET['name']);

	}else{
		$url=BASE_URL.'browse.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

?>

<html>
	<head>
		<title><?php echo $lang['types']; ?>: <?php echo $_GET['name']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Type: <?php echo $_GET['name']; ?></h3>
			</header>


			<?php
				//reddit style list
				foreach($posts as $key=>$post){
					echo ($key+1).". <a href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a> by $post->username on ".date('j F Y\, h:i:s A', strtotime($post->created_at))."<br>";
				}

			?>



			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
