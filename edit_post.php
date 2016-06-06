<?php
	$errorEditPostMessage='';
	require("lib/config.php");
	require('lib/postClass.php');
	require('lib/vendor/htmlpurifier/library/HTMLPurifier.auto.php');

	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$postClass = new postClass();

	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}else{

		if (!empty($_POST['postSubmit'])&&isset($_POST['subject'])&&isset($_POST['editor'])&&isset($_POST['type'])&&isset($_SESSION['uid'])) 
		{
			$subject=$_POST['subject'];
			$content=$_POST['editor'];
			$type=$_POST['type'];
			$author=$_SESSION['uid'];
			$postId = $_POST['id'];

			if(strlen(trim($subject))>1 && strlen(trim($content))>1 && strlen(trim($type))){
				$result=$postClass->editPost($postId, $subject, $content, $type, $author);
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
				$errorEditPostMessage="Please make sure there are no empty fields.";
			}

		}

	}


?>
<html>
	<head>
		<title>Learny: Edit Post</title>
        <?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			
			<header>
				<h3>Edit</h3>
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
							<option value="" disabled selected>Select a type</option>
							<option value="fact">Fact</option>
							<option value="idea">Idea</option>
							<option value="insight">Insight</option>
							<option value="thought">Thought</option>
						</select>
						<input class="input-default-format" id="tag_input" placeholder="Enter tags (comma-separated)">
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
            </script>
	</body>
</html>
