<?php
	$errorPostMessage='';
	require_once("lib/config.php");
	require_once('lib/postClass.php');
	require_once('lib/typeClass.php');

	$postClass = new postClass();
	$typeClass = new typeClass();
	$types = $typeClass->fetchTypes();
	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

	if ((!empty($_POST['postSubmit'])||!empty($_POST['postDraftSubmit']))&&isset($_POST['subject'])&&isset($_POST['editor'])&&isset($_POST['type'])&&isset($_SESSION['uid'])) 
	{
		$subject=$_POST['subject'];
		$content=$_POST['editor'];
		$type=$_POST['type'];
		$author=$_SESSION['uid'];
		if(!empty($_POST['postDraftSubmit'])){
			$draft = true;
		}else{
			$draft = false;
		}
		if(isset($_POST['tags'])){
			$tags = $_POST['tags'];
		}
		else{
			$tags = [];
		}
		if(strlen(trim($subject))>1 && strlen(trim($content))>1 && strlen(trim($type))){
			$result=$postClass->createPost($subject, $content, $type, $author, $tags, $draft);
			if($result){
				$url=BASE_URL.'post.php?id='.$lastPostId;
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorPostMessage="Problem adding post to database...";
			}
		}else{
			$errorPostMessage="Please make sure there are no empty fields.";
		}
	}else{
		if(!empty($_POST['postSubmit'])){
			$errorPostMessage="Please make sure there are no empty fields.";
		}
	}

?>
<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['Create']; ?></title>
        <?php include 'partials/header.php' ?>
        <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="lib/vendor/aehlke-tag-it/css/jquery.tagit.css">

	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			
			<header>
				<h3><?php echo $lang['Create']; ?></h3>
			</header>
            <?php 
	            echo "<p>".$errorPostMessage."</p>";
	        ?>
            <div>
                <form action="create.php" method="post">
                    <input class="input-default-format form-input" type="text" name="subject" placeholder="Type your subject" value=<?php if(isset($_POST['subject'])) {echo htmlentities ($_POST['subject']); }?>>
                    <textarea name="editor" id="create_editor" rows="10" cols="80"><?php if(isset($_POST['editor'])) {echo $_POST['editor']; }?></textarea>
					<div style="width:100%; overflow:hidden; display:flex;">
						<select name="type" class="input-default-format" id="post_type_select">
							<option value="" disabled selected>Select a type</option>
							<?php
								foreach($types as $type){
									echo "<option value='$type->id'>$type->name</option>";
								}

							?>
							<!-- <option value="fact">Fact</option>
							<option value="idea">Idea</option>
							<option value="insight">Insight</option>
							<option value="thought">Thought</option> -->
						</select>
		                <ul id="tag_input"></ul>
						<!-- <input class="input-default-format" id="tag_input" placeholder="Enter tags (comma-separated)"> -->
					</div>
	                <div style="text-align:center;">
		                <input class="input-default-format" id="create-submit-button" type="submit" name="postSubmit" value="<?php echo $lang['publish']; ?>">
		                <input class="input-default-format" id="create-submit-button" type="submit" name="postDraftSubmit" value="<?php echo $lang['save-draft']; ?>">
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
	                ['Format','Font','Bold','Italic','Underline','Strike','-',
	                // 'Undo','Redo','-',
					'JustifyLeft','JustifyCenter','JustifyRight','-',
					'Outdent','Indent','NumberedList','BulletedList','Table',
					'TextColor','BGColor','Link','Image','Youtube','Source', 'Maximize']
                ] ;
                CKEDITOR.config.extraPlugins = 'youtube';
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
