<?php
	require('lib/config.php');
	require('lib/postClass.php');
	$postClass = new postClass();
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	$postsPerPage = 10;
	$posts = $postClass->fetchPosts($postsPerPage, $page);
	$postCount = $postClass->fetchPostsCount();
?>
<!-- Todo:
1. Add like/dislike count for each post
2. show "type" of each post with some kind of visual?
3. format time 
4. show author... how to get author from uid?
5. Allow a post to be public/private
6. add password_needs_rehash to add security(http://php.net/manual/en/function.password-needs-rehash.php)
7. add password reset feature (send email with reset password link)
8. add verify email feature (to confirm email ownership after registration)... how to set up an email server?
-->
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
				<!-- <h3>Browsing <?php echo "(" . $postsPerPage . " from " . $postCount . ")"; ?></h3> -->
			</header>
			<?php
				//reddit style list
				foreach (array_reverse($posts) as $key=>$post){
					echo ($page-1)*$postsPerPage+($key+1) . ". <a href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a> by $post->uid on $post->created_at<br>";
				}

			?>
			<?php 
				$prevPage = $page - 1;
				$nextPage = $page + 1;

				if($page-1 > 0){
					echo "<a href='browse.php?page=$prevPage'>Prev</a>";
				}
				if(($page*$postsPerPage)+1<=$postCount){
					echo "<a href='browse.php?page=$nextPage'> Next</a>";
				}
			?>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>

		</div>
	</body>
</html>
