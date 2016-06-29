<?php
	
	if(isset($_POST['email'])){
		mail($_POST['email'], 'My Subject', 'test');
	}

?>