<?php # Script 15.9 - login_ajax.php
// This script is called via Ajax from login2.php
// The script expects to recieve two values in the URL: an email address and a password.
// The script returns a string indicating the results.

// Need two pieces of information:

if (isset($_GET['email'], $_GET['password'])) {

	// Include helper files to validate:
	require('../../mysqli_connect.php');
	require('includes/login2_functions.inc.php');

	list($result, $data) = check_login($dbc, $_GET['email'], $_GET['password']);
	session_start();
	if ($data !== 0) {
		$_SESSION['first_name'] = $data['first_name'];
	}
	echo $result;
} else {
	echo 'INCOMPLETE';
}	
/* The old way before database validation:
	// Need a valid e-mail address:
	if (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {

		// Set a cookie, if you want, or start a session.

		// Indicate success:
		echo 'CORRECT';

	} else { // Mismatch!
		echo 'INCORRECT';
	}

	} else { // Invalid email address! 
			echo 'INVALID_EMAIL';
	}

} else { // Missing one of the two variables!
	echo 'INCOMPLETE';
}
*/
?>