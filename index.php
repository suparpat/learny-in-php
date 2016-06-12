<?php
	require('lib/config.php');
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
?>


<!-- To do:
1. Make each row in browse section a 'card'. A card should display: up/downvote buttons, current total votes, subject, author, date created, number of comments, and it's "sublearny"
2. Join tables to get username instead of displaying the user's id
3. Allow up/downvote on a post
4. Allow up/downvote on a comment
5. Add "sublearny": to categorize posts (replacing "type"?) -->

<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<?php
				include ('partials/browse.php')
			?>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
	</body>
</html>
