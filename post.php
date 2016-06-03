<?php
	$commentMessage = '';
	require('lib/config.php');
	require('lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php');
	require('lib/commentClass.php');
	
	$commentClass = new commentClass();
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);

	if(isset($_GET['id'])){
		require('lib/postClass.php');
		$postClass = new postClass();
		$post = $postClass->fetchAPost($_GET['id']);
	}else{
		$url=BASE_URL.'browse.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

	if(!empty($_POST['commentSubmit'])){
		$comment = $_POST['comment'];
		$postId = $_GET['id'];
		$author=$_SESSION['uid'];

		if(strlen(trim($comment))>0){
			$result = $commentClass->createComment($postId, $comment, $author);
			if($result){
				$commentMessage = 'Thanks! Got your comment.';
			}else{
				$commentMessage = 'ermm. Need to finish implementing this!';
			}
		}else{
			//nothing in $comment
			$commentMessage = '... please enter something in the comment box';
		}

		//store comment in database
	}

	//fetch comments
	$comments = $commentClass->fetchComments($_GET['id'])
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
			<?php 
				if(!empty($_POST['commentSubmit'])){
					echo $commentMessage;
				} 
			?>
			<header>
				<h3>
					<?php
						echo "<code>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')." by ".$post->uid." on ".$post->created_at."</code>";
					?>

				</h3>
			</header>
			<div id="postContent">
				<?php
					echo "<p>".$purifier->purify($post->content)."</p>";
				?>
			</div>

			<hr>
			<code><b>Comment</b></code>
			<div id="commentForm">
				<form action=<?php echo "post.php?id=".$_GET['id']; ?> method="post">
	                <textarea name="comment" id="comment" rows="6" cols="80"></textarea>
	                <div>
			            <input class="input-default-format" id="comment-submit-button" type="submit" name="commentSubmit" value="Submit">
	                </div>
				</form>
			</div>

			<hr>

			<?php 
				foreach (array_reverse($comments) as $key=>$comment){
					echo "<p>By $comment->uid on $comment->created_at <br>" . (count($comments)-($key)) . ". " . htmlspecialchars($comment->comment, ENT_QUOTES, 'UTF-8')."</p>";
				};
			?>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
