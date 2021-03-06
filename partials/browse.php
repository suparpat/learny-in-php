<?php
	// require('lib/config.php');
	require('lib/postClass.php');
	$postClass = new postClass();

	$postsPerPage = 15;
	$posts = $postClass->fetchPosts($postsPerPage, $page);
	$postCount = $postClass->fetchPostsCount();
?>
<!-- Todo:
1. Add like/dislike count for each post
2. show "type" of each post with some kind of visual?
3. format time 
4. show author... how to get author from uid?
5. Allow a post to be public/private
6. add password_needs_rehash to add security(http://php.net/manual/en/function.password-needs-rehash.php)
7. add password reset feature (send email with reset password link)
8. add verify email feature (to confirm email ownership after registration)... how to set up an email server?
-->



	<body>
		<div id="content">

			<header>
				<h3>New Posts</h3>
				<!-- <h3>Browsing <?php echo "(" . $postsPerPage . " from " . $postCount . ")"; ?></h3> -->
			</header>
			<?php
			// echo "<table>";
   //                  foreach ($posts as $key => $value) {
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
				foreach ($posts as $key=>$post){
					$postNumber = ($page-1)*$postsPerPage+($key+1);
					$postTags = preg_split('/\t/', $post->tags);
					echo "<tr>";
					echo "<td>$postNumber. </td>
					<td><a href=post.php?id=$post->id>".htmlspecialchars($post->subject, ENT_QUOTES, 'UTF-8')."</a>
					<br>$post->username, ".date('j F Y\, h:ia', strtotime($post->created_at));
					if(count($postTags)>0){
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
			});
		</script>
	</body>
