<?php
	$errorEditPostMessage='';
	require_once("lib/config.php");
	require_once('lib/commentClass.php');

	$commentClass = new commentClass();

	//redirect to error_not_login.php if user is not logged in
	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}else{
		//redirect to index.php if user is not the owner of this comment or id not specified
		if(isset($_GET['id'])){
			if($_SESSION['uid']!=$commentClass->checkOwnership($_GET['id'], $_SESSION['uid'])){
				$url=BASE_URL.'index.php';
				header("Location: $url");
			}		
		}
		else{
			$url=BASE_URL.'index.php';
			header("Location: $url");
		}

		//Delete Comment
		if(!empty($_POST['deleteComment'])){
			$commentId = $_POST['id'];
			$author = $_SESSION['uid'];
			$postId = $_POST['post_id'];
			$result = $commentClass->deleteComment($commentId, $author);
			if($result){
				$url=BASE_URL.'post.php?id='.$postId;
				header("Location: $url"); // Page redirecting to home.php 
			}
		}

		//Update Comment
		if (!empty($_POST['updateComment'])&&isset($_POST['editor'])&&isset($_SESSION['uid'])) 
		{
			$commentId = $_POST['id'];
			$comment = $_POST['editor'];
			$postId = $_POST['post_id'];
			$author = $_SESSION['uid'];

			if(strlen(trim($comment)) > 1){
				$result=$commentClass->editComment($commentId, $comment, $postId, $author);
				if($result){
					$url=BASE_URL.'post.php?id='.$postId;
					header("Location: $url"); // Page redirecting to home.php 
				}
				else{
					$errorEditPostMessage="Problem editing comment...";
				}
			}else{
				$errorEditPostMessage="Please make sure there are no empty fields.";
			}
			exit();
		}else{
			//Get comment
			if(isset($_GET['id'])){
				$commentId = $_GET['id'];
				$comment = $commentClass->fetchAComment($_GET['id']);
				$postId = $comment->post_id;
			}

			if(!empty($_POST['updateComment'])){
				$errorEditPostMessage = "Please make sure there are no empty fields.";
			}

		}

	}


?>
<html>
	<head>
		<title><?php echo $lang['learny'] . ": " . $lang['edit-comment']; ?></title>
        <?php include 'partials/header.php' ?>
        <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="lib/vendor/aehlke-tag-it/css/jquery.tagit.css">
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			
			<header>
				<h3><?php echo $lang['edit-comment']; ?></h3>
			</header>
            <?php 
	            echo "<p>".$errorEditPostMessage."</p>";
	        ?>
            <div>
                <form action="edit_comment.php" method="post">
                	<input name="id" value=<?php echo $commentId; ?> hidden>
                	<input name="post_id" value=<?php echo $postId; ?> hidden>
                    <textarea name="editor" id="create_editor" rows="10" cols="80"><?php if(isset($_POST['editor'])) {echo $_POST['editor']; } else{echo $comment->comment;}?></textarea>
	                <div style="text-align:center;">
		                <input class="input-default-format" id="create-submit-button" type="submit" name="updateComment" value="<?php echo $lang['edit']; ?>">
		                <input class="input-default-format" id="create-submit-button" type="submit" name="deleteComment" value="<?php echo $lang['delete']; ?>" onclick="return confirm('Sure you want to delete this comment?');">
					</div>
				</form>

            </div>


			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
			
        </div>
        	<script src="js/jquery-ui/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
        	<script src="lib/vendor/aehlke-tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
            <script src="lib/vendor/ckeditor/ckeditor.js"></script>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                // Basic: http://stackoverflow.com/questions/13499025/how-to-show-ckeditor-with-basic-toolbar
                CKEDITOR.replace( 'editor' );
                CKEDITOR.config.toolbar = [
	                [
	                'Format','Bold','Italic','Underline','Strike','-','Image','Table','BulletedList', 'Link', 'Youtube', 'Source'
					]
                ] ;
                CKEDITOR.config.extraPlugins = 'youtube';
                CKEDITOR.config.height = '55%';

            </script>
	</body>
</html>
