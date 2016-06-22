<?php
	$errorMsgLogin='';
	require_once("lib/config.php");
	require_once('lib/userClass.php');
	$userClass = new userClass();

	if($isLoggedIn){
		$url=BASE_URL.'index.php';
		header("Location: $url"); // Page redirecting to home.php 		
	}

	/* Login Form */
	if (!empty($_POST['loginSubmit'])) 
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
				$errorMsgLogin="Please check login details.";
			}
		}
	}

?>


 <html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['login']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['login']; ?></h3>
			</header>
            <div>
                <form id="login_form" action="login.php" method="post">
	                <input class="input-default-format form-input" type="text" name="username" placeholder="<?php echo $lang['username']; ?>">
					<input class="input-default-format form-input" type="password" name="password" placeholder="<?php echo $lang['password']; ?>">
                    <?php 
	                    echo "<p>".$errorMsgLogin."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" value="<?php echo $lang['submit']; ?>" name="loginSubmit">
                </form>
            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
