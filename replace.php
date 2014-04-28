<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Testing PCRE</title>
</head>
<body>
<?php // Script 14.3 - replace.php
// This script takes a submitted string and checks it against a submitted pattern.
// This version replaces one value with another.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Trim the strings:
	$pattern = trim($_POST['pattern']);
	$subject = trim($_POST['subject']);
	$replace = trim($_POST['replace']);

	// Print a caption:
	echo "<p>The result of replacing <br /><b>$pattern</b><br />with<br />$replace<br />in<br />$subject<br /><br />";

	// Check for  match:
	if (preg_match($pattern, $subject) ) {
		echo preg_replace($pattern, $replace, $subject) . '</p>';
	} else {
		echo 'The pattern was not found!</p>';
	}

} // End of submission IF.
// Display the HTML form.
?>
<form action="replace.php" method="post">
<p>Regular Expression Pattern: <input type="text" name="pattern" value="<?php if (isset($pattern)) echo htmlentities($pattern); ?>" size="40" />(include the delimiters)</p>
<p>Replacement: <input type="text" name="replace" value="<?php if (isset($replace)) echo htmlentities($replace); ?>" size="40" /></p>
<p>Test Subject: <textarea name="subject" rows="5" cols="40"><?php if (isset($subject)) echo htmlentities($subject); ?></textarea></p>
<input type="submit" name="submit" value="Test!" />
</form>
</body>
</html>