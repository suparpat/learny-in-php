<?php
	//http://www.w3schools.com/php/php_mysql_connect.asp
	//http://www.9lessons.info/2016/04/php-login-system-with-pdo-connection.html

	//define makes a constant. Contants are global and can be used anywhere.
	//They also cannot be redefined.

	session_start();
	
	/* DATABASE CONFIGURATION */
	define("DB_SERVER", "localhost");
	define("DB_USERNAME", "root");
	define("DB_PASSWORD", "123456");
	define("DB_DATABASE", "learny");
	define("BASE_URL", "http://localhost/learny/"); // Eg. http://yourwebsite.com


	function getDB(){
		$dbhost=DB_SERVER;
		$dbuser=DB_USERNAME;
		$dbpassword=DB_PASSWORD;
		$dbname=DB_DATABASE;
		try{
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
			$dbConnection->exec("SET NAMES utf8");
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
			$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			return $dbConnection;
		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	};


	function isLoggedIn(){
		if(empty($_SESSION['uid'])){
			return false;
		}
		else{
			$session_uid=$_SESSION['uid'];
			include('userClass.php');
			$userClass = new userClass();
			global $userDetails;
			$userDetails = $userClass->userDetails($session_uid);
			return true;
		}
	}

	$isLoggedIn = isLoggedIn(); 

?>