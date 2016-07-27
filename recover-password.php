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
	if (!empty($_POST['resetPasswordSubmit'])) 
	{
		$email=$_POST['email'];

		if(strlen(trim($email))>1){
			$res=$userClass->resetPassword($email);
			if($res){
				echo "<script>alert('ระบบได้ส่งอีเมล์แล้ว'); window.location='.';</script>";
			}
			else{
				echo "<script>alert('มีปัญหาในการส่งอีเมล์...');</script>";
				$errorMsgLogin="Please check login details.";
			}
		}
	}

?>


 <html>
	<head>
		<title><?php echo $lang['learny']; ?>: <?php echo $lang['forgot-password']; ?></title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3><?php echo $lang['forgot-password']; ?></h3>
			</header>
            <div>
                <form id="login_form" action="recover-password.php" method="post">
	                <input class="input-default-format form-input" type="text" name="email" placeholder="<?php echo $lang['email']; ?>">
                    <?php 
	                    echo "<p>".$errorMsgLogin."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" value="<?php echo $lang['submit-forgot-password']; ?>" name="resetPasswordSubmit">
                </form>
            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
