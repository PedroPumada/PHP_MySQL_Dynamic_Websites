<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Constants</title>
</head>
<body>
	<?php # Script 1.9 - constants.php

	// Set today's date as a constant:
	define ('TODAY', 'November 16, 2013');

	// Print a message using predefined constants and the TODAY constant:

	echo '<p>Today is ' . TODAY . '. <br />
	This server is running version <b>' . 
	PHP_VERSION . '</b> of PHP on the <b>' . 
	PHP_OS . '</b> operating system. </p>';

	?>
</body>
</html>
