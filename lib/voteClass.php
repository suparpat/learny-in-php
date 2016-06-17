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

	public function checkIfUserVoted(){

	}

	public function updateVote(){

	}

}

?>