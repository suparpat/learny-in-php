<?php
	require_once('config.php');
	require_once('voteClass.php');
	$voteClass = new voteClass();


	if(isset($_POST['votePostId'])){

		if($isLoggedIn){
			$result = $voteClass->addVote( $_SESSION['uid'], $_POST['votePostId'], $_POST['vote']);
			echo "true";
		}else{
			echo "false";

			//do nothing
		}
	}


?>