<?php
	$errorMsgAddType='';
	require_once(__DIR__."/../lib/config.php");
	require_once(__DIR__.'/../lib/typeClass.php');
	$typeClass = new typeClass();

	if(!$isLoggedIn){
		$url=BASE_URL.'index.php';
		header("Location: $url"); // Page redirecting to home.php 		
	}

	/* Login Form */
	if (!empty($_POST['addTypeSubmit'])) 
	{
		$typeName=$_POST['typeName'];
		
		if(strlen(trim($typeName))>1){
			$result=$typeClass->createType($typeName);
			if($result){
				$url=BASE_URL.'browse_by_type.php';
				header("Location: $url"); // Page redirecting to home.php 
			}
			else{
				$errorMsgAddType="Please check input details.";
			}
		}
	}

?>


 <html>
	<head>
		<title>Learny: Add Type</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Add Type</h3>
			</header>
            <div>
                <form id="login_form" action="add_type.php" method="post">
	                <input class="input-default-format form-input" type="text" name="typeName" placeholder="Type Name">
                    <?php 
	                    echo "<p>".$errorMsgAddType."</p>";
	                ?>
                    <input class="input-default-format form-submit-button" type="submit" value="Add Type" name="addTypeSubmit">
                </form>
            </div>



		</div>
	</body>
</html>
