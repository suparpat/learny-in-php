<?php
	require_once('lib/config.php');
	require_once('lib/postClass.php');
	require_once('lib/userClass.php');

	if(isset($_GET['id'])){
		$userClass = new userClass();
		$postClass = new postClass();

		$user = $userClass->fetchUserPublicProfile($_GET['id']);
		$posts = $postClass->fetchAUsersPublicPosts($_GET['id']);

	}else{
		$url=BASE_URL.'browse.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

?>

<html>
	<head>
		<title><?php echo $lang['tags']; ?>: <?php echo $_GET['name']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['your-posts'] . " $user->username"; ?></h3>
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
						echo "</td></tr>";
					}

				?>
			</table>



			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>

			<script>
			
				function changePassword(){
					var r = confirm("An email will be sent to you with a password reset link. Press to confirm.");
					if(r){
						$.post("lib/changePassword.php", { 'email': '<?php echo $userDetails->email; ?>' }, function(){
						  window.location.href = 'profile.php'
						} );	
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
	</body>
</html>
