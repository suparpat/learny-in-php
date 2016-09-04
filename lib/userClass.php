<?php
	require(ROOT_PATH.'/vendor/autoload.php');
	use Mailgun\Mailgun;
	require(ROOT_PATH.'/lib/vendor/StoPasswordReset.php');

//http://www.9lessons.info/2016/04/php-login-system-with-pdo-connection.html

	class userClass{
		/* User Login */
		public function userLogin($username, $password){
		try{
			$db = getDB();

			$stmt = $db->prepare("SELECT uid, password FROM users WHERE username=:username"); 
			$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;

			if(isset($data->password)&&password_verify($password, $data->password)){
				//prevent session fixation
				if (!isset($_SESSION['uid'])||$_SESSION['uid']=='')
				{
				    session_regenerate_id();
				    $_SESSION['uid'] = $data->uid;
				}else{
					$_SESSION['uid']=$data->uid; // Storing user session value
				}
				return true;
			}
			else{
				return false;
			} 
		}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}

		}//end userLogin()





		/* User Registration */
		public function userRegistration($username,$password,$email){
			try{
				$db = getDB();
				$st = $db->prepare("SELECT uid FROM users WHERE username=:username OR email=:email"); 
				$st->bindParam("username", $username,PDO::PARAM_STR);
				$st->bindParam("email", $email, PDO::PARAM_STR);
				$st->execute();
				$count=$st->rowCount();
				if($count < 1){
					$stmt = $db->prepare("INSERT INTO users(username,password,email) VALUES (:username,:hash_password,:email)");
					$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
					$hash_password = $this->hashPassword($password);
					$stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
					$stmt->bindParam("email", $email,PDO::PARAM_STR) ;
					$stmt->execute();
					$uid=$db->lastInsertId(); // Last inserted row id
					$db = null;
					$_SESSION['uid']=$uid;
					return true;
				}
				else{
					$db = null;
					return false;
				}

			} 
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}'; 
			}
		}//end userRegistration()


		public function hashPassword($rawPassword){
			// $hash_password= hash('sha256', $password); //Password encryption
			$hash_password = password_hash($rawPassword, PASSWORD_DEFAULT);
			return $hash_password;
		}


		/* User Details */
		public function userDetails($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT uid,email,username,users.created_at FROM users WHERE uid=:uid"); 
				$stmt->bindParam("uid", $uid,PDO::PARAM_INT);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end userDetails()

		public function fetchUserPublicProfile($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT username FROM users WHERE uid=:uid"); 
				$stmt->bindParam("uid", $uid,PDO::PARAM_INT);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}

		public function fetchAllUsers(){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT uid,email,username,users.created_at FROM users"); 
				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_OBJ); //User data
				return $data;				
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}

		public function setNewPassword($uid, $password){
			try{
				$db = getDB();
				$stmt = $db->prepare("UPDATE users SET password=:password WHERE uid=:uid");
				$hash_password = $this->hashPassword($password);
				$stmt->bindParam("password", $hash_password, PDO::PARAM_STR);
				$stmt->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt->execute();

				$stmt2 = $db->prepare("DELETE FROM user_reset_password WHERE uid=:uid");
				$stmt2->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt2->execute();

				$db = null;

				return true;
			}
			catch(PDOException $e){
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}

		public function resetPassword($email){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT uid,email FROM users WHERE email=:email");
				$stmt->bindParam("email", $email, PDO::PARAM_STR);
				$stmt->execute();
				$count = $stmt->rowCount();
				$data = $stmt->fetch(PDO::FETCH_OBJ);
				$db = null;

				if($count>0){
					//http://www.martinstoeckli.ch/php/php.html#passwordreset
					// $tokenForLink;
					// $tokenHashForDatabase;
					StoPasswordReset::generateToken($tokenForLink, $tokenHashForDatabase);
					$emailLink = 'https://borkhairuu.com/set_new_password.php?tok=' . $tokenForLink;
					$creationDate = new DateTime();
					$this->savePasswordResetToDatabase($tokenHashForDatabase, $data->uid, $creationDate);
					$thisEmail = $data->email;
					//https://github.com/mailgun/mailgun-php/issues/130
					$client = new \GuzzleHttp\Client(['verify'=>MAILGUN_SSL]);
					$adaptor = new \Http\Adapter\Guzzle6\Client($client);

					$mgClient = new Mailgun(MAILGUN_API, $adaptor);
					$domain = "mg.borkhairuu.com";
					$html = '<p>Click this link to reset your password: <a href="'.$emailLink.'">'.$emailLink.'</a></p>';
					$result = $mgClient->sendMessage($domain, array(
						'from' => 'Borkhairuu <mailgun@borkhairuu.com>',
						'to' => $thisEmail,
						'subject' => 'Borkhairuu: Reset Password',
						'html' => $html
						// 'text' => 'Click this link to reset your password: '.$emailLink
						));
					return true;
				}else{
					return false;
				}
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}

		public function savePasswordResetToDatabase($tokenHashForDatabase, $uid, $creationDate){
			try{
				$db = getDB();
				$deleteStmt = $db->prepare("DELETE FROM user_reset_password WHERE uid=:uid");
				$deleteStmt->bindParam("uid", $uid, PDO::PARAM_INT);
				$deleteStmt->execute();

				$stmt = $db->prepare("INSERT INTO user_reset_password(uid, token_hash, creation_date) VALUES (:uid, :token_hash, :creation_date)");
				$stmt->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt->bindParam("token_hash", $tokenHashForDatabase, PDO::PARAM_STR);
				$dateAsString = $creationDate->format('d-m-Y H:i:s');
				$stmt->bindParam("creation_date", $dateAsString, PDO::PARAM_STR);
				$stmt->execute();
				
				$db = null;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}


		public function checkTokenHash($tokenHashFromLink){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT id, uid, creation_date FROM user_reset_password WHERE token_hash=:token_hash");
				$stmt->bindParam("token_hash", $tokenHashFromLink, PDO::PARAM_STR);
				$stmt->execute();

				$count = $stmt->rowCount();
				$db = null;

				$data = $stmt->fetch(PDO::FETCH_OBJ);

				$stringToDateTime = date_create_from_format('d-m-Y H:i:s', $data->creation_date);
				$checkIfExpired = StoPasswordReset::isTokenExpired($stringToDateTime);
				if($count > 0 && !$checkIfExpired){
					return $data;
				}else{
					return false;
				}
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}



		public function getPoints($uid){
			try{
				// Count number of posts by this user
				$db = getDB();
				$stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE uid=:uid AND posts.draft=0");
				$stmt->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt->execute();
				$userPostsCount = $stmt->fetchColumn();

				// Get votes on user's posts
				$stmt2 = $db->prepare("
					SELECT users_postvotes.vote AS vote,
					posts.id AS pid, 
					users.username AS voteBy, 
					posts.subject AS subject,
					users_postvotes.created_at AS created_at
					FROM users_postvotes
					LEFT JOIN posts ON users_postvotes.user_id=posts.uid
					LEFT JOIN users ON users.uid=users_postvotes.user_id
					WHERE users_postvotes.post_id=posts.id AND posts.uid=:uid
					");
				$stmt2->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt2->execute();
				$result = $stmt2->fetchAll(PDO::FETCH_OBJ);


				$countVotes = 0;
				foreach($result as $key=>$res){
					if($res->vote == 1){
						$countVotes = $countVotes + 10;
					}
					else if($res->vote == -1){
						$countVotes = $countVotes - 3;
					}
				}

				// Get comments by this user
				$stmt3 = $db->prepare("
					SELECT comments.comment, comments.post_id, comments.created_at 
					FROM comments 
					LEFT JOIN posts ON comments.post_id=posts.id 
					WHERE posts.id=comments.post_id AND comments.uid=:uid AND posts.draft=0
					");

				$stmt3->bindParam("uid", $uid, PDO::PARAM_INT);
				$stmt3->execute();
				$countComment=$stmt3->rowCount();
				$comments = $stmt3->fetchAll(PDO::FETCH_OBJ);
				$db = null;

				// error_log(print_r($comments));
				$points = ($userPostsCount*5) + $countVotes + $countComment*5;
				return array(
					 'postCount'=>$userPostsCount, 
					 'postVotes'=>$result,
					 'comments'=>$comments,
					 'points'=>$points, 
					 'votePoints'=>$countVotes);
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}
	}//end userClass


?>