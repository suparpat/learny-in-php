<?php
	require_once('lib/config.php');
	require_once('lib/postClass.php');

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	$postClass = new postClass();

	$postsPerPage = 15;
	$data = $postClass->fetchPosts($postsPerPage, $page);
	$postCount = $postClass->fetchPostsCount();

?>


<!-- To do:
1. Make each row in browse section a 'card'. A card should display: up/downvote buttons, current total votes, subject, author, date created, number of comments, and it's "sublearny"
2. Join tables to get username instead of displaying the user's id
3. Allow up/downvote on a post
4. Allow up/downvote on a comment
5. Add "sublearny": to categorize posts (replacing "type"?) -->

<html>
	<head>
		<title><?php echo $lang['learny']; ?></title>
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
				<h3><?php echo $lang['new-posts']; ?></h3>
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
					$upvoted = 0;
					$downvoted = 0;
					foreach($data['votesData'] as $v){
						if($v->post_id==$post->id && $v->user_id==$_SESSION['uid']){
							if($v->vote==1){
								$upvoted = 1;
							}
							if($v->vote==-1){
								$downvoted = 1;
							}
						}
					}
					// print_r($postTags)."<br>";
					// http://unicode-table.com/en/sets/arrows-symbols/
					echo "<tr>";
					echo "<td>$postNumber</td>
					<td style='text-align:center'><div>";
						if($upvoted){
							echo "<a href='#' class='upvoted' style='color:grey' data-pid=$post->id>▲</a>";
						}
						else{
							echo "<a href='#' class='upvote' data-pid=$post->id>▲</a>";
						}
						echo "</div><div class='voteCount'>$post->votes</div><div>";
						if($downvoted){
							echo "<a href='#' class='downvoted' style='color:grey' data-pid=$post->id>▼</a>";
						}
						else{
							echo "<a href='#' class='downvote' data-pid=$post->id>▼</a>";
						}
						echo "</div></td>

					<td><a style='font-size:18px;' href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a>
					<br><span style='font-size:12px'><span>($post->type)</span> $post->username, ".date('j F Y\, h:ia', strtotime($post->updated_at))."</span>";
					if($postTags[0]!=""){
						echo "<br><ul class='tags_display'>";
						foreach($postTags as $postTag){
							$tag = trim($postTag);
							echo "<li>".$tag."</li>";
						}		
						echo "</ul>";			
					}

					// echo "</td><td>$post->type</td></tr>";
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
		// http://stackoverflow.com/questions/13137563/jqueryui-tagit-how-can-i-suppress-the-new-entry-text-field-from-appearing
			$(document).ready(function() {
			    $(".tags_display").tagit({
			    	readOnly: true,
			    	onTagClicked: function(event, ui){
			    		window.location.href="tag.php?name="+ui.tag[0].textContent;
			    		// console.log(ui.tag);
			    	}
			    }).ready(function() {
				    $(this).find('.tagit-new').css('height', '13px').empty();
				});

			    var loggedIn = <?php if($isLoggedIn){echo "true";}else{echo "false";} ?>;

			    function notLoggedInAlert(){
					alert('Please register/login first to vote');
			    }
				$('.upvote').click(function () { 
					if(loggedIn){
						$(this).off();
						var postId = $(this).attr("data-pid");
						AJAXupvote(postId, $(this));
					}else{
						notLoggedInAlert();
					}

				});

				$('.downvote').click(function () {
					if(loggedIn){
						$(this).off();
						var postId = $(this).attr("data-pid");
						AJAXdownvote(postId, $(this));						
					}else{
						notLoggedInAlert();
					}


				});

				$('.upvoted').click(function () { 
					if(loggedIn){
						$(this).off();
						var postId = $(this).attr("data-pid");
						AJAXupvote(postId, $(this));						
					}else{
						notLoggedInAlert();
					}

				});

				$('.downvoted').click(function () {
					if(loggedIn){
						$(this).off();
						var postId = $(this).attr("data-pid");
						AJAXdownvote(postId, $(this));						
					}else{
						notLoggedInAlert();
					}

				});

				var AJAXupvote = function(postId, element){
					$.post("lib/vote.php", {'votePostId':postId, 'vote':1}, function(res){
						if(res=="true"){
							var thisVoteElement = $(element).closest('td').find('div.voteCount');

							//Case 1: post upvoted before
							if($(element).hasClass("upvoted")){
								thisVoteElement.html(parseInt(thisVoteElement.html(), 10)-1);
								$(element).removeClass("upvoted").addClass("upvote").css('color','#11a');
								$(element).click(function () { 
									$(this).off();
									$(this).css('color','grey');
									var postId = $(this).attr("data-pid");
									AJAXupvote(postId, $(this));
								});
							}else{
								var downVoteArrow = $(element).closest('td').find('a.downvoted');

								//Case 2: post downvoted before
								if(downVoteArrow.hasClass("downvoted")){
									downVoteArrow.removeClass("downvoted").addClass("downvote").css('color','#11a');

								}else{
									//Case 3: post first time upvoted
									$(element).removeClass("upvote").addClass("upvoted").css('color','grey');
								}
								thisVoteElement.html(parseInt(thisVoteElement.html(), 10)+1);
								$(element).click(function () { 
									$(this).off();
									$(this).css('color','#11a');
									var postId = $(this).attr("data-pid");
									AJAXupvote(postId, $(this));
								});
							}
						}

					})
				}

				var AJAXdownvote = function(postId, element){

					$.post("lib/vote.php", {'votePostId':postId, 'vote': -1}, function(res){
						if(res=="true"){
							var thisVoteElement = $(element).closest('td').find('div.voteCount');

							//Case 1: post downvoted before
							if($(element).hasClass("downvoted")){
								thisVoteElement.html(parseInt(thisVoteElement.html(), 10)+1);
								$(element).removeClass("downvoted").addClass("downvote").css('color', '#11a');
								$(element).click(function () {
									$(this).off();
									$(this).css('color','grey');
									var postId = $(this).attr("data-pid");
									AJAXdownvote(postId, $(this));
								});

							}else{
								var upVoteArrow = $(element).closest('td').find('a.upvoted');

								//Case 2: post upvoted before
								if(upVoteArrow.hasClass("upvoted")){
									upVoteArrow.removeClass("upvoted").addClass("upvote").css('color','#11a');

								}else{
									//Case 3: post first time downvoted
									$(element).removeClass("downvote").addClass("downvoted").css('color','grey');

								}
								thisVoteElement.html(parseInt(thisVoteElement.html(), 10)-1);
								$(element).click(function () {
									$(this).off();
									$(this).css('color','#11a');
									var postId = $(this).attr("data-pid");
									AJAXdownvote(postId, $(this));
								});
							}

						}



					})
				}
			});
		</script>
	</body>
</html>
