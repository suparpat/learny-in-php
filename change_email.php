<?php
	$errorMsgChangeEmail='';
	require("lib/config.php");

	/* Login Form */
	if (!empty($_POST['changeEmailSubmit'])) 
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		
		if(strlen(trim($username))>1 && strlen(trim($password))>1 ){
			$uid=$userClass->userLogin($username, $password);
			if($uid){
				$url=BASE_URL.'index.php';
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorMsgChangeEmail="Please check login details.";
			}
		}
	}

?>


 <html>
	<head>
		<title>Learny: Change Email</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Change Email</h3>
			</header>

            <div>
                <form id="login_form" action="change_email.php" method="post">
	                <input class="input-default-format form-input" type="email" name="email" value=<?php echo $userDetails->email; ?>>
					<input class="input-default-format form-input" type="password" name="password" placeholder="password">
                    <?php 
	                    echo "<p>".$errorMsgChangeEmail."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" value="Change" name="changeEmailSubmit">
                </form>
            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
