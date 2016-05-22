<?php
//http://www.9lessons.info/2016/04/php-login-system-with-pdo-connection.html

	class userClass{
		/* User Login */
		public function userLogin($username, $password){
		try{
			echo $username." ".$password;
			$db = getDB();

			$stmt = $db->prepare("SELECT uid, password FROM users WHERE username=:username"); 
			$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;

			if(password_verify($password, $data->password)){
				$_SESSION['uid']=$data->uid; // Storing user session value
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
				$st->bindParam("email", $email,PDO::PARAM_STR);
				$st->execute();
				$count=$st->rowCount();
				if($count < 1){
					$stmt = $db->prepare("INSERT INTO users(username,password,email) VALUES (:username,:hash_password,:email)");
					$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
					// $hash_password= hash('sha256', $password); //Password encryption
					$hash_password = password_hash($password, PASSWORD_DEFAULT);
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





		/* User Details */
		public function userDetails($uid){
			try{
				$db = getDB();
				$stmt = $db->prepare("SELECT email,username FROM users WHERE uid=:uid"); 
				$stmt->bindParam("uid", $uid,PDO::PARAM_INT);
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
				return $data;
			}
			catch(PDOException $e) {
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}//end userDetails()





	}//end userClass


?>