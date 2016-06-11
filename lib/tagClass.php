<?php

class tagClass{
	public function createTag($name){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO tags(name) VALUES (:name)");
			$stmt->bindParam("name", $name, PDO::PARAM_STR);
			$stmt->execute();
			$lastTagId = $db->lastInsertId();
			$db = null;

			return $lastTagId;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function fetchTags(){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM tags");
			$stmt->execute();

			$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function fetchPostsByTagName($tagName){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT posts.id, posts.subject, posts.type, posts.uid, users.username, posts.created_at FROM tags INNER JOIN posts_tags ON tags.id=posts_tags.tag_id JOIN posts ON posts_tags.post_id=posts.id JOIN users ON posts.uid=users.uid WHERE tags.name=:name");
			$stmt->bindParam("name", $tagName, PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}
	public function fetchTagsByPostId($postId, $asTagNameArray){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT tags.id, tags.name FROM tags
				INNER JOIN posts_tags ON tags.id=posts_tags.tag_id WHERE posts_tags.post_id=:postId");
			$stmt->bindParam("postId", $postId, PDO::PARAM_INT);
			$stmt->execute();
			if($asTagNameArray){
				$data = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
			}else{
				$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			}
			return $data;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function checkIfTagExists($inputTag){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT id FROM tags WHERE name=:name");
			$stmt->bindParam("name", $inputTag, PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;

			if(count($data) > 0){
				return $data->id;
			}else{
				return -1;
			}
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
	public function createPostTag($postId, $tagId){
		try{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO posts_tags(post_id, tag_id) VALUES (:post_id, :tag_id)");
			$stmt->bindParam("post_id", $postId, PDO::PARAM_INT);
			$stmt->bindParam("tag_id", $tagId, PDO::PARAM_INT);
			$stmt->execute();

			$db = null;
			return true;

		}

		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function insertPostTags($postId, $tags){
		$tagIdArray = [];
		//Check if each tag already exists and create one if not
		foreach($tags as $tag){
			$tempTagCheckResult = $this->checkIfTagExists($tag);
			if($tempTagCheckResult >= 0){
				$tagIdArray[] = $tempTagCheckResult;
			}else{
				$tagIdArray[] = $this->createTag($tag);
			}
		}

		//then enter tag and post id to post_tags table
		foreach($tagIdArray as $tagId){
			$this->createPostTag($postId, $tagId);
		}

	}//end insertPostTags()

	public function deletePostTag($postId, $tagName){
		try{
			$db = getDB();
			$stmt = $db->prepare("DELETE posts_tags FROM posts_tags INNER JOIN tags ON posts_tags.tag_id=tags.id WHERE post_id=:post_id AND name=:tag_name");
			$stmt->bindParam("post_id", $postId, PDO::PARAM_INT);
			$stmt->bindParam("tag_name", $tagName, PDO::PARAM_STR);
			$stmt->execute();

			$db = null;
			return true;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function deleteTagIfNoPost($tagNameToDelete){
		try{
			$checkTagHasPosts = $this->checkIfTagHasPosts($tagNameToDelete);
			if($checkTagHasPosts){
				// do nothing
			}else{
				$db = getDB();
				$stmt = $db->prepare("DELETE FROM tags WHERE tags.name=:tagNameToDelete");
				$stmt->bindParam("tagNameToDelete", $tagNameToDelete, PDO::PARAM_STR);
				$stmt->execute();
			}

			$db = null;
			return true;
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function checkIfTagHasPosts($tagName){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT id FROM tags INNER JOIN posts_tags ON tags.id=posts_tags.tag_id WHERE tags.name=:tagName");
			$stmt->bindParam("tagName", $tagName, PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			error_log(count($data));
			if(count($data)>0){
				return true;
			}else{
				return false;
			}
		}
		catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function updatePostTags($postId, $tags){

		//get post's current tags
		$originalTags = $this->fetchTagsByPostId($postId, true);

		//Find tags to add
		$newTags = array_diff($tags, $originalTags);
		$this->insertPostTags($postId, $newTags);

		//Find tags removed
		$deleteTags = array_diff($originalTags, $tags);

		foreach($deleteTags as $tagToDelete){
			$this->deletePostTag($postId, $tagToDelete);
		}

		//If a tag has no more post, delete tag
		foreach($deleteTags as $tagToDelete){
			$this->deleteTagIfNoPost($tagToDelete);
		}		
	}
}

?>