<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Login</h3>
			</header>
            <div>
                <form id="login_form" action="submit_login.php" method="post">
                    <input class="input-default-format form-input" type="text" name="username" placeholder="username">
										<input class="input-default-format form-input" type="password" name="password" placeholder="password">
                    <input class="input-default-format form-submit-button" type="submit" value="Submit">
                </form>
								<div id="register-link-div">
									<a href="register.php">Register</a>
								</div>
            </div>
			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>


		</div>
	</body>
</html>
