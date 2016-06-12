<?php
	$commentMessage = '';
	require('lib/config.php');
	require('lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php');
	require('lib/commentClass.php');
	require('lib/tagClass.php');

	$commentClass = new commentClass();
	$tagClass = new tagClass();
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);

	if(isset($_GET['id'])){
		require('lib/postClass.php');
		$postClass = new postClass();
		$post = $postClass->fetchAPost($_GET['id']);
		$tags = $tagClass -> fetchTagsByPostId($_GET['id'], false);
	}else{
		$url=BASE_URL.'browse.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

	if(!empty($_POST['commentSubmit'])){
		$comment = $_POST['comment'];
		$postId = $_GET['id'];
		if(empty($_SESSION['uid'])){
		//do nothing
		}else{
			$author = $_SESSION['uid'];
		}

		if(empty($author)){
			$commentMessage = 'Please <a href="register.php">register</a>/<a href="login.php">login</a> first before commenting.';
		}else{
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
		}


		//store comment in database
	}

	//fetch comments
	$comments = $commentClass->fetchComments($_GET['id'])
?>

<html>
	<head>
		<title>Learny: <?php echo htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8'); ?></title>
		<?php include 'partials/header.php' ?>
        <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="lib/vendor/aehlke-tag-it/css/jquery.tagit.css">
        <style>
        	.ui-widget-content{
        		border: 0px;
        		background: transparent;
        	}
        </style>
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
						echo "<code>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')." by $post->username on ".date('j F Y\, h:i:s A', strtotime($post->created_at))."</code>";
						if(isset($post->updated_at)){
							echo "<br><code>last updated: ".date('j F Y\, h:i:s A', strtotime($post->updated_at))."</code>";
						}
					?>
				</h3>
			</header>
			<div id="postContent">
				<?php
					echo "<p>".$purifier->purify($post->content)."</p>";
				?>
			</div>

				<?php
				echo "<ul id='tags_display'>";
					foreach($tags as $tag){
						echo "<li>$tag->name</li>";
					}
				echo "</ul>";

				if(!empty($_SESSION['uid'])){
					if($_SESSION['uid']==$post->uid){
						echo "[<a href='edit_post.php?id=".$post->id."'>edit post</a>]";
					}					
				}

				?>
			<hr>
			<code><b>Comment</b></code>
			<div id="commentForm">
				<form action=<?php echo "post.php?id=".$_GET['id']; ?> method="post" style="overflow:hidden;">
	                <textarea name="comment" id="comment" rows="6" cols="80"></textarea>
	                <div>
			            <input class="input-default-format" id="comment-submit-button" type="submit" name="commentSubmit" value="Submit">
	                </div>
				</form>
			</div>

			<hr>

			<?php 
				foreach (array_reverse($comments) as $key=>$comment){
					echo "<p>By $comment->username on $comment->created_at <br>" . (count($comments)-($key)) . ". " . htmlspecialchars($comment->comment, ENT_QUOTES, 'UTF-8')."</p>";
				};
			?>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
    	<script src="js/jquery-ui/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
    	<script src="lib/vendor/aehlke-tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
    	<script>
    		
			$(document).ready(function() {
			    $("#tags_display").tagit({
			    	readOnly: true,
			    	onTagClicked: function(event, ui){
			    		window.location.href="tag.php?name="+ui.tag[0].textContent;
			    		// console.log(ui.tag);
			    	}
			    })
			});

    	</script>
	</body>
</html>
