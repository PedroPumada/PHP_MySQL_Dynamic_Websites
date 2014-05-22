<?php 
// This includes a check login function for my newly Ajax 
// functional site. 
// It accepts a database connection and the login and password sent by 
// Ajax.

function check_login($dbc, $email = '', $password = '') {

	// Validate the e-mail
	$row = 0; // Set to false unless data is put into it
	if (empty($email)) {
		return array('INCOMPLETE', $row);
	} else {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$e = mysqli_real_escape_string($dbc, trim($email));
		} else {
			return array('INVALID_EMAIL', $row);
		}
	}

	// Validate there's a password and clean it up:
	if (empty($password)) {
		return array('INCOMPLETE', $row);
	} else {
		$p = mysqli_real_escape_string($dbc, trim($password));
	}

	// Start the database validation to see if a login exists:
	$q = "SELECT user_id, first_name FROM users WHERE email='$e' AND pass=SHA1('$p')";
	$r = @mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) == 1) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		return array('CORRECT', $row);
	} else {
		return array('INCORRECT', $row);
	}

} // End of check_login() function