<?php
	$errorEditPostMessage='';
	require("lib/config.php");
	require('lib/postClass.php');
	require('lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php');

	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$postClass = new postClass();

	//redirect to error_not_login.php if user is not logged in
	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}else{

		//redirect to index.php if user is not the owner of this post
		if($_SESSION['uid']!=$postClass->checkOwnership($_GET['id'], $_SESSION['uid'])){
			$url=BASE_URL.'index.php';
			header("Location: $url");
		}

		if (!empty($_POST['postSubmit'])&&isset($_POST['subject'])&&isset($_POST['editor'])&&isset($_POST['type'])&&isset($_SESSION['uid'])) 
		{
			$subject=$_POST['subject'];
			$content=$_POST['editor'];
			$type=$_POST['type'];
			$author=$_SESSION['uid'];
			$postId = $_POST['id'];
			$tags = $_POST['tags'];

			if(strlen(trim($subject))>1 && strlen(trim($content))>1 && strlen(trim($type))){
				$result=$postClass->editPost($postId, $subject, $content, $type, $author, $tags);
				if($result){
					$url=BASE_URL.'post.php?id='.$postId;
					header("Location: $url"); // Page redirecting to home.php 
				}
				else{
					$errorEditPostMessage="Problem editing post...";
				}
			}else{
				$errorEditPostMessage="Please make sure there are no empty fields.";
			}
		}else{
			//Get post
			if(isset($_GET['id'])){
				$postId = $_GET['id'];
				$post = $postClass->fetchAPost($_GET['id']);
			}

			if(!empty($_POST['postSubmit'])){
				$errorEditPostMessage = "Please make sure there are no empty fields.";
			}

		}

	}


?>
<html>
	<head>
		<title>Learny: Edit Post</title>
        <?php include 'partials/header.php' ?>
        <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="lib/vendor/aehlke-tag-it/css/jquery.tagit.css">
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			
			<header>
				<h3>Edit Post</h3>
			</header>
            <?php 
	            echo "<p>".$errorEditPostMessage."</p>";
	        ?>
            <div>
                <form action="edit_post.php" method="post">
                	<input name="id" value=<?php echo $postId; ?> hidden>
                    <input class="input-default-format form-input" type="text" name="subject" placeholder="Type your subject" value=<?php if(isset($_POST['subject'])) {echo htmlentities ($_POST['subject']); } else{echo htmlentities($post->subject);} ?>>
                    <textarea name="editor" id="create_editor" rows="10" cols="80"><?php if(isset($_POST['editor'])) {echo $purifier->purify($_POST['editor']); } else{echo $purifier->purify($post->content);}?></textarea>
					<div style="width:100%; overflow:hidden; display:flex;">
						<select name="type" class="input-default-format" id="post_type_select">
							<option value="" disabled <?php if($post->type==""){echo "selected";}?>>Select a type</option>
							<option value="fact" <?php if($post->type=="fact"){echo "selected";}?>>Fact</option>
							<option value="idea" <?php if($post->type=="idea"){echo "selected";}?>>Idea</option>
							<option value="insight" <?php if($post->type=="insight"){echo "selected";}?>>Insight</option>
							<option value="thought" <?php if($post->type=="thought"){echo "selected";}?>>Thought</option>
						</select>
		                <ul id="tag_input">
		                	<?php 
		                		//echo <li> of already existing tags
		                	?>
		                </ul>
						<!-- <input class="input-default-format" id="tag_input" placeholder="Enter tags (comma-separated)"> -->
					</div>
	                <div style="text-align:center;">
		                <input class="input-default-format" id="create-submit-button" type="submit" name="postSubmit" value="Submit">
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
                ['Format','Font','Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-',
								'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-',
								'Outdent','Indent','Image','Table','NumberedList','BulletedList',
								'Link','TextColor','BGColor','-','Maximize']
                ] ;
                CKEDITOR.config.height = '55%';

			    $(document).ready(function() {
			        $("#tag_input").tagit({
			        	placeholderText: "Enter tags here",
			        	tagLimit: 5,
			        	allowSpaces: true,
			        	caseSensitive: false,
			        	fieldName: "tags[]"
			        });
			    });
            </script>
	</body>
</html>
