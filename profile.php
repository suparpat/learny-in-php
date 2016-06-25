<?php
	require('lib/config.php');
	require('lib/postClass.php');

	$postClass = new postClass();
	$posts = $postClass->fetchAUsersPosts($userDetails->uid);
	if(!$isLoggedIn){
		$url=BASE_URL.'error_not_login.php';
		header("Location: $url"); // Page redirecting to home.php 
	}else{

		if(isset($_POST['postToDelete'])){
			$result = $postClass->deletePost($_POST['postToDelete'], $_SESSION['uid']);
		}
	}
?>

<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['profile']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['profile']; ?></h3>
			</header>
			<h4><?php echo $lang['username']; ?>: <?php echo $userDetails->username; ?></h4>
			<h4><?php echo $lang['email']; ?>: <?php echo $userDetails->email; ?></h4>
			<h4><?php echo $lang['since']; ?>: <?php echo date('j F Y',strtotime($userDetails->created_at)); ?></h4>
			<hr>

			<header>
				<h3><?php echo $lang['your-posts']; ?></h3>
			</header>
			<table>
				<?php
					//reddit style list
					foreach ($posts as $key=>$post){
						echo "<tr><td>".($key+1)."</td>";
						echo "<td>";
						
						if($post->draft){
							echo " (".$lang['draft'].") ";
						}

						echo "<a href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8').
						"</a><br><span style='font-size:15px;'>".date('j F Y\, h:i A', strtotime($post->created_at));
						echo "</td><td style='text-align:right'>
						<a href='edit_post.php?id=".$post->id."'>".$lang['edit-post']."</a><br>
						<a href='javascript:deletePost($post->id)'>".$lang['delete']."</a> ";
					    echo "</span></td></tr>";
					}

				?>
			</table>
			<hr>

			<header>
				<h3><?php echo $lang['account']; ?></h3>
			</header>
			<!-- http://stackoverflow.com/questions/2906582/how-to-create-an-html-button-that-acts-like-a-link -->
			<p>
				<form action="change_email.php">
				    <input type="submit" value="<?php echo $lang['change-email']; ?>">
				</form>
			</p>
			<p>
				<button onclick="changePassword()"><?php echo $lang['change-password']; ?></button>
			</p>
			<p>
				<form action="logout.php">
				    <input type="submit" value="<?php echo $lang['logout']; ?>">
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

			   function deletePost(postId){
			   	var r = confirm("Are you sure you want to delete this post?");

			   	if(r){
					$.post("profile.php", { 'postToDelete': postId }, function(){
					  window.location.href = 'profile.php'
					} );		   		
			   	}

			   }
			</script>
		</div>
	</body>
</html>
