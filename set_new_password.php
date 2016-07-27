<?php
	$errorMsgLogin='';
	require_once("lib/config.php");
	require_once('lib/userClass.php');
	$userClass = new userClass();

	if($isLoggedIn){
		$url=BASE_URL.'index.php';
		header("Location: $url"); // Page redirecting to home.php 		
	}

	if(empty($_GET['tok'])||!StoPasswordReset::isTokenValid($_GET['tok'])){
		$url=BASE_URL.'index.php';
		header("Location: $url"); // Page redirecting to home.php 		
	}

	$tokenHashFromLink = StoPasswordReset::calculateTokenHash($_GET['tok']);
	$check = $userClass->checkTokenHash($tokenHashFromLink);
	if($check){
		//allow to continue
	}else{
		$url=BASE_URL.'index.php';
		header("Location: $url"); // Page redirecting to home.php 			
	}

	/* Login Form */
	if (!empty($_POST['setNewPasswordSubmit'])) 
	{
		$uid = $check->uid;
		$password=$_POST['password'];
		
		if(strlen(trim($password))>1 ){
			$result=$userClass->setNewPassword($uid, $password);
			if($result){
				$url=BASE_URL.'login.php';
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorMsgLogin="Something went wrong.";
			}
		}else{
			$errorMsgLogin="Please enter a valid password";
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
				<h3><?php echo $lang['set-new-password']; ?></h3>
			</header>
            <div>
                <form id="login_form" action="set_new_password.php?tok=<?php echo $_GET['tok']; ?>" method="post">
					<input class="input-default-format form-input" type="password" name="password" placeholder="<?php echo $lang['password']; ?>">
                    <?php 
	                    echo "<p>".$errorMsgLogin."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" value="<?php echo $lang['set-new-password']; ?>" name="setNewPasswordSubmit">
                    <div style="margin-top: 5px">
		                <a style="font-size:14px" href="recover-password.php"><?php echo $lang['forgot-password']; ?></a>
                    </div>
                </form>
            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
