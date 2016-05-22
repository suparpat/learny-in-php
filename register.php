<?php
	$errorMsgReg='';
	include("lib/config.php");
	include('lib/userClass.php');
	$userClass = new userClass();

	/* Signup Form */
	if (!empty($_POST['registerSubmit'])){
		$email=$_POST['email'];
		$username=$_POST['username'];
		$password=$_POST['password'];

		/* Regular expression check */
		$username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
		$email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
		$password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);

		if($username_check && $email_check && $password_check){
			$uid=$userClass->userRegistration($username,$password,$email);
			if($uid){
				$url=BASE_URL.'index.php';
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorMsgReg="Username or Email already exists.";
			}
		}else{
				$errorMsgReg="Please check that you entered valid information. User name must consist of at least 3 characters. Password must be at least 6 characters.";
		}
	}

?>


<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Register</h3>
			</header>
            <div>
                <form id="login_form" action="register.php" method="post">
                    <input class="input-default-format form-input" type="text" name="email" placeholder="email">
	                <input class="input-default-format form-input" type="text" name="username" placeholder="username">
  					<input class="input-default-format form-input" type="password" name="password" placeholder="password">
                    <?php 
	                    echo "<p>".$errorMsgReg."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" name="registerSubmit" value="Register">
                </form>

            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
