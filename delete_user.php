<?php # Script 10.2 - delete_user.php
// This page is for deleting a user and is accessed via view_users.php

// Check for a valid user ID, through GET or POST:
if (isset($_GET['id']) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
	$fn = $_GET['fn'];
	$ln = $_GET['ln'];
	$page_title = $_GET['fn'] . ' ' . $_GET['ln'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission
	$id = $_POST['id'];
	$page_title = $_POST['first_name'] . ' ' . $_POST['last_name'];
} else { // No valid ID, kill the script
	echo '<p class="error">This page has been accessed in error.</p>';
	include('includes/footer.html');
	exit();
}

include ('includes/header.html');
echo '<h1>Delete a User</h1>';

require_once('../../mysqli_connect.php');
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if($_POST['sure'] == 'Yes') {
		// Delete the record
		// Make the query:
		$q = "DELETE FROM users WHERE user_id=$id LIMIT 1";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // if it ran OK.
			// Print a message:
			echo '<p>The user has been deleted.</p>';
		} else { // If the query did not run OK.
			echo '<p class="error">The user could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message
		}
	} else { // No confirmation of deletion
		echo '<p>The user has NOT been deleted.</p>';
	}
} else { // Show the form.
	// Retrieve the user's information:
	$q = "SELECT CONCAT(last_name, ', ', first_name) FROM users WHERE user_id=$id";
	$r = @mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

		// Get the user's information:
		$row = mysqli_fetch_array($r, MYSQLI_NUM);

		// Display the record being deleted:
		echo "<h3>Name: $row[0]</h3>
		Are you sure you want to delete this user?";

		// Create the form:
		echo '<form action="delete_user.php" method="post">
		<input type="radio" name="sure" value="Yes" />Yes
		<input type="radio" name="sure" value="No" checked="checked" />No
		<input type="submit" name="submit" value="Submit" />
		<input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="first_name" value="' . $fn . '" />
		<input type="hidden" name="last_name" value="' . $ln . '" /></form>';

	} else { // Not a valid user ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // end of the main submission conditional

mysqli_close($dbc);

include('includes/footer.html');
?> 
