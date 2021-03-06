<?php
	$commentMessage = '';
	require_once('lib/config.php');
	require_once('lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php');
	require_once('lib/commentClass.php');
	require_once('lib/tagClass.php');

	$commentClass = new commentClass();
	$tagClass = new tagClass();
	$config = HTMLPurifier_Config::createDefault();
	//http://stackoverflow.com/questions/18726480/embed-html5-youtube-video-without-iframe
	$config->set('HTML.SafeIframe', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo
	$purifier = new HTMLPurifier($config);

	if(isset($_GET['id'])){
		require('lib/postClass.php');
		$postClass = new postClass();
		$post = $postClass->fetchAPost($_GET['id']);
		if($post->draft&&$_SESSION['uid']!=$post->uid){
			$url=BASE_URL;
			header("Location: $url"); // Page redirecting to home.php 
		}
		$tags = $tagClass -> fetchTagsByPostId($_GET['id'], false);
	}else{
		$url=BASE_URL;
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
		<title><?php echo $lang['learny']; ?>: <?php echo htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8'); ?></title>
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
				<div>
					<h3>
						<?php
							echo "<div style='float:left;width:auto;'><code><a href='post.php?id=$post->id'>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a></code></div>";
							echo "<div style='font-size:15px; float:right; text-align:right; width:auto;font-weight:normal;'>";
							if(isset($post->updated_at)){
								echo date('j F Y\, h:i A', strtotime($post->updated_at));
							}else{
								echo date('j F Y\, h:i A', strtotime($post->created_at));
							}
							echo "<br><a href=user.php?id=$post->uid>$post->username</a></div>";
						?>
					</h3>
				</div>
				<div style="clear:both"></div>
			</header>

			<div id="postContent">
				<?php
					echo $purifier->purify($post->content);
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
						echo "[<a href='edit_post.php?id=".$post->id."'>".$lang['edit-post']."</a>]";
					}					
				}

				?>
			<hr>
			<?php 
				if(!empty($_SESSION['uid'])):
			?>
			<code><b><?php echo $lang['comment']; ?></b></code>
			<div id="commentForm">
				<form action=<?php echo "post.php?id=".$_GET['id']; ?> method="post" style="overflow:hidden;">
	                <!-- <textarea name="comment" id="comment" rows="6" cols="80"></textarea> -->
                    <textarea name="comment" id="create_editor" rows="6" cols="80"></textarea>
	                <div>
			            <input class="input-default-format" id="comment-submit-button" type="submit" name="commentSubmit" value="<?php echo $lang['submit']; ?>">
	                </div>
				</form>
			</div>
			<?php
				else:
					echo "Please <a href='register.php'>register</a>/<a href='login.php'>login</a> first before commenting.";
				endif;
			?>
			<hr>

			<?php 
				foreach (array_reverse($comments) as $key=>$comment){
					$formattedDate = date('j F Y\, h:i A', strtotime($comment->created_at));
					echo "<p>".(count($comments)-($key)).". <a href='user.php?id=$comment->uid'>$comment->username</a> $formattedDate </p>" . $purifier->purify($comment->comment);
					if($comment->uid == $_SESSION['uid']){
						echo "<span style='float:right;'>";
						echo "[<a href='edit_comment.php?id=".$comment->id."'>".$lang['edit']."</a>]";
						echo "</span>";
						echo "<div style='clear:both'></div>";
					}
					echo "<hr class='style-six'>";

				};
			?>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
    	<script src="js/jquery-ui/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
    	<script src="lib/vendor/aehlke-tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
        <script src="lib/vendor/ckeditor/ckeditor.js"></script>
    	<script>
    		
			$(document).ready(function() {
			    $("#tags_display").tagit({
			    	readOnly: true,
			    	onTagClicked: function(event, ui){
			    		window.location.href="tag.php?name="+ui.tag[0].textContent;
			    		// console.log(ui.tag);
			    	}
			    }).ready(function() {
				    $(this).find('.tagit-new').css('height', '13px').empty();
				});
			    var session = <?php 
				    if(!empty($_SESSION['uid'])){
				    	echo json_encode($_SESSION['uid']);
				    }
				    else{
				    	echo "''";
				    }
			    ?>;
			    if(session){
	                CKEDITOR.replace( 'comment' );
	                CKEDITOR.config.toolbar = [
		                [
		                'Format','Bold','Italic','Underline','Strike','-','Image','Table','BulletedList', 'Link', 'Youtube', 'Source'
						]
	                ] ;
	                CKEDITOR.config.extraPlugins = 'youtube';
	                CKEDITOR.config.height = '35%';			    	
			    }

			});

    	</script>
	</body>
</html>
