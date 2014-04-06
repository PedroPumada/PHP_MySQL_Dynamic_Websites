<?php # Script 10.3 - edit_user.php
// This page is for editing a user record.
// This page is accessed through view_users.php

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && is_numeric($_GET['id']) ) { // From view_users.php
	$id = $_GET['id'];
	$page_title = $_GET['fn'] . ' ' . $_GET['ln'];
} elseif ( (isset($_POST['id'])) && is_numeric($_POST['id'])) { // Form submission
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include('includes/footer.html');
	exit();
}

$page_title = 'Edit a User';
include('includes/header.html');

echo '<h1>Edit a User</h1>';

require_once('../../mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array();

	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}

	// Check for an e-mail address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your e-mail address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	if (!empty($_POST['old_pass'])) { // If they tried to enter a password, does it match old one?
		$old_pass = sha1($_POST['old_pass']);
		$pq = "SELECT pass FROM users WHERE user_id=$id";
		$pr = @mysqli_query($dbc, $pq);
		if (mysqli_num_rows($pr) == 1) { // If it ran OK
			$prow = mysqli_fetch_array($pr, MYSQLI_NUM);
			if ($old_pass != $prow[0]) {
				$errors[] = 'Your old password did not match the user\'s old password.';
			}
		} else { // The query didn't find anything
			$errors[] = "User ID does not exist, unable to check password.";
		}
	}

	if (!empty($_POST['pass1']) || !empty($_POST['pass2'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = "The new passwords do not match each other.";
		} else {
			$new_pw = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	}

	if (empty($errors)) { // If everything's OK

		// Test for unique e-mail address
		$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_num_rows($r) == 0) {
			// Make the query:
			$q = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e'";
			if(isset($new_pw)) {
				$q .= ", pass=SHA1('$new_pw') WHERE user_id=$id LIMIT 1";
			} else {
				$q .= " WHERE user_id=$id LIMIT 1";
			}
			$r = @mysqli_query($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
					// Print a message:
					echo '<p>The user has been edited.</p>';
				} else { // If it did not run OK. 
					echo '<p class="error">The user could not be edited due to a system error. 
					We apologize for any inconvenience.</p>'; // Public message
					echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message
				}
		} else { // Already registered.
			echo '<p class="error">The email address has already been registered.</p>';
		}

	} else { // Report the errors.

		echo '<p class="error">The following error(s) occurred: <br />';
		foreach ($errors as $msg) {
			// Print each error:
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';
	} // end of if (empty($errors)) IF
} // end of submit conditional.

// Always show the form....

// Retrieve the user's information:
$q = "SELECT first_name, last_name, email FROM users WHERE user_id=$id";
$r = @mysqli_query($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array($r, MYSQLI_NUM);

	// Create the form:
	echo '<form action="edit_user.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '" /></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . $row[2] . '" /></p>
	<p>Old Password: <input type="password" name="old_pass" size="10" maxlength="20" /></p>
	<p>New Password: <input type="password" name="pass1" size="10" maxlength="20" /></p>
	<p>Confirm New Password: <input type="password" name="pass2" size="10" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="id" value="' . $id .'" />
	</form>';

} else { // Not a valid user ID. 

	echo '<p class="error">This page has been accessed in error.</p>';
}

mysqli_close($dbc);

include('includes/footer.html');
?>







