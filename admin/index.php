<?php
	require_once(__DIR__.'/../lib/config.php');
	require_once(__DIR__.'/../lib/userClass.php');
	require_once(__DIR__.'/../lib/postClass.php');
	require_once(__DIR__.'/../lib/tagClass.php');

	$userClass = new userClass();
	$postClass = new postClass();
	$tagClass = new tagClass();

	$users = $userClass->fetchAllUsers();
	$posts = $postClass->fetchPosts(1000, 1);
	$tags = $tagClass->fetchTags();

?>
<html>
	<head>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
		
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Admin</h3>
			</header>

			<h4>Users</h4>
			<?php 
				foreach($users as $user){
					echo $user->username."<br>";
				}
			?>

			<h4>Posts</h4>
			<?php 
				foreach($posts as $post){
					echo $post->subject."<br>";
				}
			?>

			<h4>Tags</h4>
			<?php 
				foreach($tags as $tag){
					echo $tag->name."<br>";
				}
			?>
		</div>
		
	</body>
</html>
