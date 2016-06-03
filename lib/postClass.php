<?php

	class postClass{
		/* Create Post */
		public function createPost($subject, $content, $type, $author){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO posts(subject,content,type,uid) VALUES (:subject,:content,:type,:author)");

			$stmt->bindParam("subject", $subject,PDO::PARAM_STR) ;
			$stmt->bindParam("content", $content,PDO::PARAM_STR) ;
			$stmt->bindParam("type", $type,PDO::PARAM_STR) ;
			$stmt->bindParam("author", $author,PDO::PARAM_STR) ;

			$stmt->execute();
			global $lastPostId;
			$lastPostId=$db->lastInsertId(); // Last inserted row id
			$db = null;

			return true;
		}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}

		}//end createPost()


		/* Fetch Posts */
		public function fetchPosts($limit, $page){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT id,subject,type,uid,created_at FROM posts ORDER BY updated_at DESC LIMIT :limit OFFSET :offset"); 
				$stmt->bindParam("limit", $limit,PDO::PARAM_INT);
				$offset = ($page-1)*$limit;
				$stmt->bindParam("offset", $offset,PDO::PARAM_INT);

				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchPosts()



		/* Fetch A Post */
		public function fetchAPost($id){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT id,subject,content,type,uid,created_at FROM posts WHERE id=:id"); 
				$stmt->bindParam("id", $id,PDO::PARAM_STR) ;
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end fetchAPost()

		/* Fetch A User's Post */
		public function fetchAUsersPosts($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT id,subject,content,type,uid,created_at FROM posts WHERE uid=:uid"); 
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


	}//end postClass


?>