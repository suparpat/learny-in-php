<?php
	require('lib/config.php');
	require('lib/postClass.php');
	$postClass = new postClass();
	$posts = $postClass->fetchAUsersPosts($userDetails->uid);
?>

<html>
	<head>
		<title>Learny: Profile</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>
			<?php
			    if(!$isLoggedIn){
					$url=BASE_URL.'error_not_login.php';
					header("Location: $url"); // Page redirecting to home.php 
			    }
			?>

			<header>
				<h3>Profile</h3>
			</header>
			<h4>Username: <?php echo $userDetails->username; ?></h4>
			<h4>Email: <?php echo $userDetails->email; ?></h4>

			<hr>

			<header>
				<h3>Your Posts</h3>
			</header>

			<?php
				//reddit style list
				foreach ($posts as $post){
					echo "<a href=post.php?id=$post->id>".$post->subject."</a> $post->created_at<br>";
				}

			?>

			<hr>

			<header>
				<h3>Account</h3>
			</header>
			<!-- http://stackoverflow.com/questions/2906582/how-to-create-an-html-button-that-acts-like-a-link -->
			<p>
				<form action="change_email.php">
				    <input type="submit" value="change email">
				</form>
			</p>
			<p>
				<button onclick="changePassword()">change password</button>
			</p>
			<p>
				<form action="logout.php">
				    <input type="submit" value="logout">
				</form>
			</p>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>

			<script>
				function changePassword(){
					var r = confirm("An email will be sent to you with a password reset link. Press to confirm.");
					if(r){
						console.log("send password reset email.")
					}
				}
			</script>
		</div>
	</body>
</html>
