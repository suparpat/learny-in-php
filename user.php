<?php
	require_once('lib/config.php');
	require_once('lib/postClass.php');
	require_once('lib/userClass.php');

	if(isset($_GET['id'])){
		$userClass = new userClass();
		$postClass = new postClass();

		$user = $userClass->fetchUserPublicProfile($_GET['id']);
		$posts = $postClass->fetchAUsersPublicPosts($_GET['id']);
		$getUserPoints = $userClass->getPoints($_GET['id']);

	}else{
		$url=BASE_URL.'browse.php';
		header("Location: $url"); // Page redirecting to home.php 
	}

?>

<html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['user-profile'] ." ". $user->username ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<!-- credit: https://pinboard.in/ -->
		<div id="content">
			<?php include 'partials/top_menu.php' ?>
			<?php 
				// print_r($getUserPoints['comments']);
			?>
			<h2>
				<?php echo $lang['user-profile'] ." ". $user->username;?>
			</h2>
			<header>
				<h3><?php echo $lang['your-experience']; ?></h3>
			</header>

			<?php 
			$scoreFromCreatingPosts = $getUserPoints['postCount']*5;
			$scoreFromVotes = $getUserPoints['votePoints'];
			$scoreFromComments = $getUserPoints['points'] - $scoreFromCreatingPosts - $scoreFromVotes;

			echo $getUserPoints['points']." ".$lang['points'];
			echo "<ul>".
			"<li>".$scoreFromCreatingPosts  ." ". $lang['points'] . " " . $lang['from-your-new-posts'] . "</li>" . 
			"<li>".$scoreFromVotes ." ". $lang['points'] ." ". $lang['from-votes'] . "</li>" .
			"<li>".$scoreFromComments ." ". $lang['points'] ." ". $lang['from-comments'] . "</li></ul>"; ?>

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
						echo "</td></tr>";
					}

				?>
			</table>

			<?php
				if($getUserPoints['postVotes']):
			?>
		
			<header>
				<h3><?php echo $lang['your-votes']; ?></h3>
			</header>


			<table>
			<thead>
				<tr>
					<td>Score</td>
					<td>Post</td>
					<!-- <td>By</td> -->
					<td>On</td>
				</tr>
			</thead>
			<?php
				foreach($getUserPoints['postVotes'] as $key=>$postVote){
					$vote = 0;
					if($postVote->vote == 1){
						$vote = ($postVote->vote)*10;
						echo "<tr><td>+".$vote."</td>";
					}
					else if($postVote->vote == -1){
						$vote = ($postVote->vote)*3;
						echo "<tr><td>".$vote."</td>";
					}
					echo "<td><a href='post.php?id=$postVote->pid'>$postVote->subject</a></td>";
					// echo "<td>$postVote->voteBy</td>";
					echo "<td>".date('j M Y\, G:i', strtotime($postVote->created_at))."</td>";
					echo "</tr>";
				}
			?>
			</table>

			<?php 
				endif;
			?>

			<?php
				if($getUserPoints['comments']):
			?>

			<header>
				<h3><?php echo $lang['your-comments']; ?></h3>
			</header>


			<table>
			<thead>
				<tr>
					<td>Score</td>
					<td>Comment</td>
					<!-- <td>By</td> -->
					<td>On</td>
				</tr>
			</thead>
			<?php
				foreach($getUserPoints['comments'] as $key=>$comment){
					echo "<tr><td>+5</td>";
					echo "<td><a href='post.php?id=$comment->post_id'>$comment->comment</a></td>";
					// echo "<td>$postVote->voteBy</td>";
					echo "<td>".date('j M Y\, G:i', strtotime($comment->created_at))."</td>";
					echo "</tr>";
				}
			?>
			</table>

			<?php 
				endif;
			?>



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
