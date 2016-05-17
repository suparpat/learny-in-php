<?php
	// Create a multidimensional array
	$quotes = array(
				array("Only a life lived for others is a life worthwhile.", "Albert Einstein"),
				array("As is a tale, so is life: not how long it is, but how good it is, is what matters.", "Seneca"),
				array("To know what is right and not to do it is the worst cowardice",""),
				array("Our greatest weakness lies in giving up. The most certain way to succeed is always to try just one more time.","Thomas A. Edison"),
				array("I'd rather attempt to do something great and fail than to attempt to do nothing and succeed.","Robert H. Schuller"),
				array("There is no passion to be found playing small — in settling for a life that is less than the one you are capable of living.","Nelson Mandela")
			);

	// Return a random quote from the array
	$rand_quote = array_rand($quotes, 1);
	$quote = $quotes[$rand_quote][0];	
	$quote_author = $quotes[$rand_quote][1];
?>