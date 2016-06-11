<?php


class commentClass{
	public function createComment($postId, $comment, $author){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO comments(post_id, comment, uid) VALUES (:postId, :comment, :author)");

			$stmt->bindParam("comment", $comment,PDO::PARAM_STR);
			$stmt->bindParam("postId", $postId,PDO::PARAM_STR);
			$stmt->bindParam("author", $author,PDO::PARAM_STR);

			$stmt->execute();
			$db = null;
			return true;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}


	public function fetchComments($postId){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT id,comment,comments.uid,username,comments.created_at FROM comments INNER JOIN users ON users.uid=comments.uid WHERE post_id=:postId ORDER BY updated_at DESC");
			$stmt->bindParam("postId", $postId, PDO::PARAM_INT) ; 
			$stmt->execute();

			$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

}


?>