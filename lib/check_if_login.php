<?php

	function isLoggedIn(){
		if(empty($_SESSION['uid'])){
			return false;
		}
		else{
			return true;
		}
	}

?>