<?php


class typeClass{

	public function fetchTypes(){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT id, name FROM types");
			$stmt->execute();

			$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}


	public function createType($typeName){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO types(name) VALUES (:name)");
			$stmt->bindParam("name", $typeName, PDO::PARAM_STR);
			$stmt->execute();
			$db = null;

			return true;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function fetchPostsByType($type){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT posts.id, posts.subject, types.name, posts.uid, users.username, posts.created_at FROM types INNER JOIN posts_type ON types.id=posts_type.type_id JOIN posts ON posts_type.post_id=posts.id JOIN users ON posts.uid=users.uid WHERE types.name=:name");

			$stmt->bindParam("name", $type, PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}

	
	public function createPostType($postId, $typeId){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO posts_type(post_id, type_id) VALUES (:post_id, :type_id)");
			$stmt->bindParam("post_id", $postId, PDO::PARAM_INT);
			$stmt->bindParam("type_id", $typeId, PDO::PARAM_INT);
			$stmt->execute();

			$db = null;
			return true;

		}

		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function updatePostType($postId, $typeId){
		// error_log($postId);
		// error_log($typeId);
		try{
			$db = getDB();
			$stmt = $db->prepare("UPDATE posts_type SET type_id=:typeId WHERE post_id=:postId");
			$stmt->bindParam("typeId", $typeId, PDO::PARAM_INT);
			$stmt->bindParam("postId", $postId, PDO::PARAM_INT);
			$stmt->execute();

			$db = null;
			return true;

		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
}

?>