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
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
			$dbConnection->exec("set names utf8");
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbConnection;
		}
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	};

?>