<?php
	require_once(__DIR__.'/postClass.php');

class voteClass{


	public function addVote($userId, $postId, $vote){
		//true is +1, false is -1
		try{
			$db = getDB();
			if($vote==1||$vote==-1){
				$stmt = $db->prepare("INSERT INTO users_postvotes(user_id,post_id,vote) VALUES (:userId, :postId, :vote)");
			}
			else{
				throw new Exception("Vote value is neither 1 nor -1...");
			}
			$stmt->bindParam("userId", $userId, PDO::PARAM_INT);
			$stmt->bindParam("postId", $postId, PDO::PARAM_INT);
			$stmt->bindParam("vote", $vote, PDO::PARAM_INT);
			$stmt->execute();

			$postClass = new postClass();
			$postClass->addPostVoteCount($postId, $vote);

			$db = null;
			return true;
		}
		catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}

	public function checkIfUserVoted($userId, $postIds){
		try{
			$postIdsString = implode(',', $postIds);
			$db = getDB();
			$stmt = $db->prepare("SELECT post_id, user_id, vote FROM users_postvotes WHERE user_id=:userId AND post_id IN (".$postIdsString.")");
			$stmt->bindParam("userId", $userId, PDO::PARAM_INT);

			// error_log($postIdsString);
			// $stmt->bindParam("postIds", $postIdsString, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
				// 			error_log(print_r($postIdsString));
				// die();
			$db = null;
			return $result;
		}
		catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function updateVote(){

	}

}

?>