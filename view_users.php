<?php # Script 10.4 - view_users.php #4
// This script retrieves all the records from the users table
// This new version paginates the query results
session_start();

$page_title = 'View the Current Users';
include ('includes/header.html');
echo '<h1>Registered Users</h1>';

require_once('../../mysqli_oop_connect.php'); // Connect to the db

// Number of records to show per page:
$display = 10;

// Determine how many pages there are.....
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.

	$pages = $_GET['p'];

} else { // Need to determine.

	// Count the number of records:
	$q = "SELECT COUNT(user_id) FROM users";
	$p = $mysqli->query($q);
	$row = $p->fetch_array(MYSQLI_NUM);
	$records = $row[0];

	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil($records/$display);
	} else {
		$pages = 1;
	} 
	$p->free();
	unset($p);
} // end of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine a sorting variable
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

switch ($sort) {
	case 'ln':
		$order_by = 'last_name ASC';
		break;
	case 'fn':
		$order_by = 'first_name ASC';
		break;
	case 'rd':
		$order_by = 'registration_date ASC';
		break;
	default:
		$order_by = 'registration_date ASC';
		$sort = 'rd';
		break;
}

// Define the query:
$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') 
AS dr, user_id FROM users ORDER BY $order_by LIMIT $start, $display";
$r = $mysqli->query($q);

$num = $r->num_rows;

if ($num > 0) { // If ran OK, display the records 
	// Table header:
	echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
	<tr>
		<td align="left"><b>Edit</b></td>
		<td align="left"><b>Delete</b></td>
		<td align="left"><b><a href="view_users.php?sort=ln">Last Name</a></b></td>
		<td align="left"><b><a href="view_users.php?sort=fn">First Name</b></a></td>
		<td align="left"><b><a href="view_users.php?sort=rd">Date Registered</b></a></td>
	</tr>';

	// Fetch and print all records...

	$bg = '#eeeeee'; // Set the initial background color

	while ($row = $r->fetch_object()) {

		$bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color
		echo '<tr bgcolor="' . $bg . '">
			<td align="left"><a href="edit_user.php?id=' . $row->user_id . '&fn=' . $row->first_name . '&ln=' . $row->last_name . '">Edit</a></td>
			<td align="left"><a href="delete_user.php?id=' . $row->user_id . '&fn=' . $row->first_name . '&ln=' . $row->last_name . '">Delete</a></td>
			<td align="left">' . $row->last_name . '</td>
			<td align="left">' . $row->first_name . '</td>
			<td align="left">' . $row->dr . '</td>
			</tr>
			';

	} // End of WHILE loop.

echo '</table>';
$r->free(); // Free up the result
unset($r);

} else { // If no records returned.

	echo '<p class="error">There are currently no registered users.</p>';

}

// Make the links to other pages, if necessary.
if ($pages > 1) {

	// Add some spacing and start a paragraph:
	echo '<br /><p>';

	// Determine what page the script is on:

	$current_page = ($start/$display) + 1;

	// If it's not the first page, make a previous link:
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start-$display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}

	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i-1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a>';
		} else {
			echo $i . ' ';
		}
	} // end for FOR loop

	// If it's not the last page, make a NEXT button:
	if ($current_page != $pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '$sort=' . $sort . '">Next</a>';
	}

	echo '</p>'; // Close the paragraph.

} // End of links section

// Close the database connection:
$mysqli->close();
unset($mysqli);
include ('includes/footer.html');
?>