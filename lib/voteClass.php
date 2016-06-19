<?php
	require_once(__DIR__.'/postClass.php');

class voteClass{


	public function addVote($userId, $postId, $vote){
		//true is +1, false is -1
		try{
			$checkVoted = $this->checkIfUserVoted($userId, array($postId));
			if(count($checkVoted)>0){
				//need to remove stored vote
				if($checkVoted[0]->vote==$vote){
					$this->removeVote($userId, $postId, $vote);
					$postClass = new postClass();
					$postClass->changePostVoteCount($postId,-1*intval($vote));
				}
				if($checkVoted[0]->vote==-1*$vote){
					$this->removeVote($userId, $postId, -1*intval($vote));
					$postClass = new postClass();
					$postClass->changePostVoteCount($postId, intval($vote));
				}

				// }else{
				// 	//need to update to opposite
				// 	$this->updateVote($userId,$postId,$vote);
				// 	$postClass = new postClass();
				// 	$postClass->changePostVoteCount($postId,2*intval($vote));
				// }	

				return true;
			}else{
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
				$postClass->changePostVoteCount($postId, $vote);

				$db = null;
				return true;
			}


		}
		catch(Exception $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}
	// public function updateVote($userId, $postId, $vote){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("UPDATE users_postvotes SET vote=:vote WHERE post_id=:postId AND user_id=:userId AND vote=:oppositeVote");
	// 								error_log($vote);

	// 		$stmt->bindParam("post_id", $postId, PDO::PARAM_INT);
	// 		$stmt->bindParam("user_id", $userId, PDO::PARAM_INT);
	// 		$stmt->bindParam("vote", $vote, PDO::PARAM_INT);

	// 		if($vote!=1&&$vote!=-1){
	// 			throw new Exception("Vote value is neither 1 nor -1...");
	// 		}
	// 		error_log($vote);
	// 		if($vote==1){
	// 			error_log('a');
	// 			$stmt->bindParam("oppositeVote", -1, PDO::PARAM_INT);
	// 		}
	// 		if($vote==-1){
	// 							error_log('b');

	// 			$stmt->bindParam("oppositeVote", 1, PDO::PARAM_INT);
	// 		}
	// 		$stmt->execute();

	// 		$db = null;
	// 		return true;
	// 	}
	// 	catch(Exception $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }
	public function removeVote($userId, $postId, $vote){
		try{
			$db = getDB();
			$stmt = $db->prepare("DELETE FROM users_postvotes WHERE post_id=:postId AND user_id=:userId AND vote=:vote");
			$stmt->bindParam("postId", $postId, PDO::PARAM_INT);
			$stmt->bindParam("userId", $userId, PDO::PARAM_INT);
			$stmt->bindParam("vote", $vote, PDO::PARAM_INT);
			$stmt->execute();

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


}

?>