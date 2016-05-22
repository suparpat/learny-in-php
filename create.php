<?php
	$errorPostMessage='';
	require("lib/config.php");
	require('lib/postClass.php');
	$postClass = new postClass();

	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

	if (!empty($_POST['postSubmit'])) 
	{
		$subject=$_POST['subject'];
		$content=$_POST['editor'];
		$type=$_POST['type'];
		$author=$_SESSION['uid'];

		if(strlen(trim($subject))>1 && strlen(trim($content))>1 && strlen(trim($type))){
			$result=$postClass->createPost($subject, $content, $type, $author);
			if($result){
				$url=BASE_URL.'index.php';
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorPostMessage="Problem adding post to database...";
			}
		}else{
			$errorPostMessage="Please make sure there is no empty field.";
		}
	}

?>
<html>
	<head>
		<title>Learny: Create</title>
        <?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			
			<header>
				<h3>Create</h3>
			</header>
            <?php 
	            echo "<p>".$errorPostMessage."</p>";
	        ?>
            <div>
                <form action="create.php" method="post">
                    <input class="input-default-format form-input" type="text" name="subject" placeholder="Type your subject">
                    <textarea name="editor" id="create_editor" rows="10" cols="80"></textarea>
					<div>
						<select name="type" class="input-default-format" id="post_type_select">
							<option value="" disabled selected>Select a type</option>
							<option value="fact">Fact</option>
							<option value="idea">Idea</option>
							<option value="insight">Insight</option>
							<option value="thought">Thought</option>
						</select>
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
