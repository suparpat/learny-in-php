<?php
	if(!empty($_SESSION['uid']))
	{
		$session_uid=$_SESSION['uid'];
		include('userClass.php');
		$userClass = new userClass();
		$userDetails = $userClass->userDetails($session_uid);
	}
?>