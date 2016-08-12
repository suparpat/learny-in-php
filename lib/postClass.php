<?php
	require_once(__DIR__.'/tagClass.php');
	require_once(__DIR__.'/typeClass.php');
	require_once(__DIR__.'/voteClass.php');

	class postClass{
		/* Create Post */
		public function createPost($subject, $content, $typeId, $author, $tags, $draft){
			try{
				$db = getDB();
				$stmt = $db->prepare("INSERT INTO posts(subject,content,uid,draft) VALUES (:subject,:content,:author,:draft)");

				$stmt->bindParam("subject", $subject,PDO::PARAM_STR);
				$stmt->bindParam("content", $content,PDO::PARAM_STR);
				$stmt->bindParam("author", $author,PDO::PARAM_STR);
				$stmt->bindParam("draft", $draft,PDO::PARAM_STR);

				$stmt->execute();
				global $lastPostId;
				$lastPostId=$db->lastInsertId(); // Last inserted row id

				$typeClass = new typeClass();
				$typeClass->createPostType($lastPostId, $typeId);

				if(count($tags)>0){
					$tagClass = new tagClass();
					$tagClass->insertPostTags($lastPostId, $tags);
				}

				$db = null;

				return true;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}

		}//end createPost()



		public function editPost($postId, $subject, $content, $typeId, $author, $tags, $draft){
			try{
				$checkOwnershipResult = $this->checkOwnership($postId, $author);
				if($checkOwnershipResult){
					$db = getDB();
					$stmt = $db->prepare("UPDATE posts SET subject=:subject, content=:content, uid=:author, draft=:draft WHERE id=:id");

					$stmt->bindParam("subject", $subject, PDO::PARAM_STR);
					$stmt->bindParam("content", $content, PDO::PARAM_STR);
					// $stmt->bindParam("type", $type, PDO::PARAM_STR);
					$stmt->bindParam("author", $author, PDO::PARAM_INT);
					$stmt->bindParam("id", $postId, PDO::PARAM_INT);
					$stmt->bindParam("draft", $draft, PDO::PARAM_INT);

					$stmt->execute();

					$typeClass = new typeClass();
					$typeClass->updatePostType($postId, $typeId);

					$tagClass = new tagClass();
					$tagClass->updatePostTags($postId, $tags);

					$db = null;

					return true;
				}else{
					throw new Exception("You do not own the post you are trying to edit!");
				}
			}//end try
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}			
		}// end editPost()

		public function deletePost($postId, $uid){
			try{
				$checkOwnershipResult = $this->checkOwnership($postId, $uid);
				if($checkOwnershipResult){
					$db = getDB();
					$stmt = $db->prepare("DELETE FROM posts WHERE id=:id");
					$stmt->bindParam("id", $postId, PDO::PARAM_STR);
					$stmt->execute();

					$db = null;

					return true;
				}else{
					throw new Exception("You do not own the post you are trying to delete!");
				}

			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}				
		}//end deletePost()

		public function checkOwnership($postId, $uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT uid FROM posts WHERE id=:postId AND uid=:uid");
				$stmt->bindParam("postId", $postId);
				$stmt->bindParam("uid", $uid);
				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ);

				if(count($data) > 0){
					return true;
				}else{
					return false;
				}

			}catch(PDOException $e){
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}
		/* Fetch Posts */
		public function fetchPosts($limit, $page){
			//http://stackoverflow.com/questions/2262923/sql-query-get-tags-associated-with-post
			try{
				$db = getDB();
				// $stmt = $db->prepare("SELECT id, subject, posts.uid, username, posts.created_at, FROM posts INNER JOIN users ON posts.uid=users.uid ORDER BY updated_at DESC LIMIT :limit OFFSET :offset"); 
				$stmt = $db->prepare("
					SELECT posts.id AS id, 
					posts.subject AS subject, 
					posts.content AS content, 
					posts.uid AS uid, 
					users.username AS username, 
					posts.created_at AS created_at, 
					posts.updated_at AS updated_at, 
					types.name AS type, 
					posts.votes AS votes,
					GROUP_CONCAT(tags.name ORDER BY tags.name SEPARATOR '\t') AS tags
					FROM posts 
					INNER JOIN users ON posts.uid=users.uid 
					INNER JOIN posts_type ON posts.id=posts_type.post_id 
					INNER JOIN types ON types.id=posts_type.type_id
					LEFT JOIN posts_tags ON posts.id=posts_tags.post_id
					LEFT JOIN tags ON posts_tags.tag_id=tags.id
					WHERE posts.draft=0
					GROUP BY posts.id
					ORDER BY updated_at DESC 
					LIMIT :limit OFFSET :offset");
				$stmt->bindParam("limit", $limit,PDO::PARAM_INT);
				$offset = ($page-1)*$limit;
				$stmt->bindParam("offset", $offset,PDO::PARAM_INT);

				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data

				//get users vote information
				$voteClass = new voteClass();
				//http://stackoverflow.com/questions/1118994/php-extracting-a-property-from-an-array-of-objects
				$postIdsArray = array_map(create_function('$o', 'return $o->id;'), $data);

				$result = [];
				if(isset($_SESSION['uid'])){
					$result = $voteClass->checkIfUserVoted($_SESSION['uid'], $postIdsArray);
				}

				return array('postsData'=>$data, 'votesData'=>$result);
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchPosts()



		/* Fetch A Post */
		public function fetchAPost($id){
			try{
				$db = getDB();
				$stmt = $db->prepare("
					SELECT posts.id AS id, 
					posts.subject AS subject, 
					posts.content AS content, 
					posts.uid AS uid, 
					users.username AS username, 
					posts.created_at AS created_at, 
					posts.updated_at AS updated_at, 
					posts.draft AS draft,
					types.name AS type 
					FROM posts 
					INNER JOIN users ON posts.uid=users.uid 
					INNER JOIN posts_type ON posts.id=posts_type.post_id 
					INNER JOIN types ON types.id=posts_type.type_id 
					WHERE posts.id=:id"); 

				$stmt->bindParam("id", $id,PDO::PARAM_STR);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchAPost()

		/* Fetch A User's Posts */
		public function fetchAUsersPosts($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT id, subject, content, uid, draft, posts.created_at FROM posts WHERE uid=:uid"); 
				$stmt->bindParam("uid", $uid,PDO::PARAM_STR) ;
				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchAUsersPosts()


		/* Fetch A User's Public Posts */
		public function fetchAUsersPublicPosts($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("
					SELECT id,
					subject,
					content,
				 	uid,
					draft,
					posts.created_at
					FROM posts
					WHERE uid=:uid AND posts.draft=0"); 
				$stmt->bindParam("uid", $uid,PDO::PARAM_STR) ;
				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchAUsersPosts()


		/* Fetch Number of Posts */
		// http://stackoverflow.com/questions/883365/row-count-with-pdo
		public function fetchPostsCount(){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT count(*) FROM posts"); 
				$stmt->execute();
				$data = $stmt->fetchColumn();
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchPostsCount()

		public function changePostVoteCount($postId, $vote){
			try{
				$db = getDB();
				if($vote==1){
					$stmt = $db->prepare("UPDATE posts SET votes=votes+1 WHERE id=:postId");

				}else if($vote==-1){
					$stmt = $db->prepare("UPDATE posts SET votes=votes-1 WHERE id=:postId");
				}else{
					throw new Exception("Vote value is neither 1 nor -1...");
				}
				$stmt->bindParam("postId", $postId, PDO::PARAM_INT);
				$stmt->execute();

				$db = null;
				return true;
			}
			catch(Exception $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}
	}//end postClass


?>