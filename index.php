<?php
	require_once('lib/config.php');
	require_once('lib/postClass.php');
	require_once('lib/voteClass.php');

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	$postClass = new postClass();
	$voteClass = new voteClass();

	$postsPerPage = 15;
	$data = $postClass->fetchPosts($postsPerPage, $page);
	$postCount = $postClass->fetchPostsCount();

	if(isset($_POST['votePostId'])){
		$result = $voteClass->addVote( $_SESSION['uid'], $_POST['votePostId'], $_POST['vote']);
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
        <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="lib/vendor/aehlke-tag-it/css/jquery.tagit.css">
        <style>
        	.ui-widget-content{
        		border: 0px;
        		background: transparent;
        	}
        	ul.tagit {
        		margin-top: 0px;
        		margin-bottom: 0px;
        		font-size: 12px;
        		padding-left: 0px;
        	}
        </style>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>New Posts</h3>
				<!-- <h3>Browsing <?php echo "(" . $postsPerPage . " from " . $postCount . ")"; ?></h3> -->
			</header>
			<?php
			// echo "<table>";
   //                  foreach ($data as $key => $value) {
   //                      echo "<tr>";
   //                      echo "<th>";
   //                      echo $key;
   //                      echo "</th>";
   //                      echo "<td>";
   //                      echo var_dump($value);
   //                      echo "</td>";
   //                      echo "</tr>";
   //                  }
			// echo "</table>";
			echo "<table>";
				//reddit style list
				foreach ($data['postsData'] as $key=>$post){
					$postNumber = ($page-1)*$postsPerPage+($key+1);
					$postTags = preg_split('/\t/', $post->tags);
					$upvoted = false;
					$downvoted = false;
					foreach($data['votesData'] as $v){
						if($v->post_id==$post->id && $v->user_id==$_SESSION['uid']){
							if($v->vote==1){
								$upvoted = true;
							}
							if($v->vote==-1){
								$downvoted = true;
							}
						}
					}
					// print_r($postTags)."<br>";
					echo "<tr>";
					echo "<td>$postNumber. </td>
					<td style='text-align:center'><div>";
						if($upvoted){
							echo "<a href='#' style='color:grey' data-pid=$post->id>▲</a>";
						}
						else{
							echo "<a href='#' class='upvote' data-pid=$post->id>▲</a>";
						}
						echo "</div><div class='voteCount'>$post->votes</div><div>";
						if($downvoted){
							echo "<a href='#' style='color:grey' data-pid=$post->id>▼</a>";
						}
						else{
							echo "<a href='#' class='downvote' data-pid=$post->id>▼</a>";
						}
						echo "</div></td>

					<td><a href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a>
					<br><span style='font-size:12px'>$post->username, ".date('j F Y\, h:ia', strtotime($post->created_at))."</span>";
					if($postTags[0]!=""){
						echo "<br><ul class='tags_display'>";
						foreach($postTags as $postTag){
							$tag = trim($postTag);
							echo "<li>".$tag."</li>";
						}		
						echo "</ul>";			
					}

					echo "</td><td>$post->type</td></tr>";
				}
			echo "</table>";
			?>
			

			<?php 
				$prevPage = $page - 1;
				$nextPage = $page + 1;

				if($page-1 > 0){
					echo "<a href='index.php?page=$prevPage'>Prev</a>";
				}
				if(($page*$postsPerPage)+1<=$postCount){
					echo "<a href='index.php?page=$nextPage'> Next</a>";
				}
			?>


			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
		</div>
    	<script src="js/jquery-ui/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
    	<script src="lib/vendor/aehlke-tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script>
			$(document).ready(function() {
			    $(".tags_display").tagit({
			    	readOnly: true,
			    	onTagClicked: function(event, ui){
			    		window.location.href="tag.php?name="+ui.tag[0].textContent;
			    		// console.log(ui.tag);
			    	}
			    });

				$('.upvote').click(function () { 
					$(this).off();
					$(this).css('color','grey');
					var postId = $(this).attr("data-pid");
					upvote(postId, $(this));
				});

				$('.downvote').click(function () {
					$(this).off();
					$(this).css('color','grey');
					console.log($(this))
					var postId = $(this).attr("data-pid");
					downvote(postId, $(this));

				});

				var upvote = function(postId, element){
					$.post("index.php", {'votePostId':postId, 'vote': 1}, function(){
						//add to total vote
						var thisVoteElement = $(element).closest('td').find('div.voteCount');
						thisVoteElement.html(parseInt(thisVoteElement.html(), 10)+1);
					})
				}

				var downvote = function(postId, element){
					$.post("index.php", {'votePostId':postId, 'vote': -1}, function(){
						//subtract from total vote
						var thisVoteElement = $(element).closest('td').find('div.voteCount');
						thisVoteElement.html(parseInt(thisVoteElement.html(), 10)-1);

					})
				}
			});
		</script>
	</body>
</html>
