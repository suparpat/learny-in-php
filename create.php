<?php
	include('lib/config.php');
	include('lib/session.php');
?>

<html>
	<head>
		<title>Learny</title>
        <?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>
			<?php
			    if(!$isLoggedIn){
					$url=BASE_URL.'error_not_login.php';
					header("Location: $url"); // Page redirecting to home.php 
			    }
			?>
			
			<header>
				<h3>Create</h3>
			</header>
            <div>
                <form action="submit_post.php" method="post">
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
	                    <input class="input-default-format" id="create-submit-button" type="submit" value="Submit">
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
