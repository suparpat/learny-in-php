<html>
	<head>
		<title>Learny</title>
		<?php include 'partials/header.php' ?>
	</head>

	<body>
		<div id="content">
			<?php include 'partials/top_menu.php' ?>

			<header>
				<h3>Post created</h3>
			</header>
            <?php
                echo "thanks!";
            ?>

            <br><br>

            <!--http://stackoverflow.com/questions/9332718/how-do-i-print-all-post-results-when-a-form-is-submitted-->
            <!--http://www.w3schools.com/css/css_table.asp-->
            <div style="overflow-x:auto;">
                <table>
                <?php
                    foreach ($_POST as $key => $value) {
                        echo "<tr>";
                        echo "<th>";
                        echo $key;
                        echo "</th>";
                        echo "<td>";
                        echo $value;
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>

            <br>

            <a href="#">View your post</a>

			<?php include 'partials/quote_block.php' ?>
			<?php include 'partials/footer.php' ?>
			<?php include 'partials/imports.php' ?>
			
		</div>
	</body>
</html>
