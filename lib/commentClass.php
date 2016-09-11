<?php


class commentClass{
	public function createComment($postId, $comment, $author){
		try{
			$db = getDB();
			$stmt = $db->prepare("
				INSERT INTO comments(post_id, comment, uid) VALUES (:postId, :comment, :author)
				");

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
			$stmt = $db->prepare("
				SELECT id,comment,comments.uid,username,comments.created_at 
				FROM comments 
				INNER JOIN users ON users.uid=comments.uid 
				WHERE post_id=:postId 
				ORDER BY updated_at DESC
				");

			$stmt->bindParam("postId", $postId, PDO::PARAM_INT) ; 
			$stmt->execute();

			$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function fetchAComment($commentId){
		try{
			$db = getDB();
			$stmt = $db->prepare("
				SELECT id,comment,comments.uid,username, comments.post_id, comments.created_at 
				FROM comments 
				INNER JOIN users ON users.uid=comments.uid 
				WHERE id=:commentId
				");

			$stmt->bindParam("commentId", $commentId, PDO::PARAM_INT) ; 
			$stmt->execute();

			$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	} 

	public function editComment($commentId, $comment, $postId, $author){
		try{
			$checkOwnershipResult = $this->checkOwnership($commentId, $author);
			if($checkOwnershipResult){
				$db = getDB();
				$stmt = $db->prepare("
					UPDATE comments SET comment=:comment 
					WHERE id=:id
					");

				$stmt->bindParam("comment", $comment, PDO::PARAM_STR);
				$stmt->bindParam("id", $commentId, PDO::PARAM_INT);

				$stmt->execute();

				$db = null;

				return true;
			}else{
				throw new Exception("You do not own the comment you are trying to edit!");
			}
		}//end try
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}		
	}

	public function deleteComment($commentId, $author){
		try{
			$checkOwnershipResult = $this->checkOwnership($commentId, $author);
			if($checkOwnershipResult){
				$db = getDB();
				$stmt = $db->prepare("DELETE FROM comments WHERE id=:id");
				$stmt->bindParam("id", $commentId, PDO::PARAM_INT);
				$stmt->execute();

				$db = null;

				return true;
			}else{
				throw new Exception("You do not own the comment you are trying to delete!");
			}

		}
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}				
	}

	public function checkOwnership($commentId, $uid){
		try{
			$db = getDB();
			$stmt = $db->prepare("
				SELECT uid 
				FROM comments 
				WHERE id=:commentId AND uid=:uid
				");
			$stmt->bindParam("commentId", $commentId);
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

}


?>