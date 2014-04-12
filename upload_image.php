<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Upload an Image</title>
	<style type="text/css" title="text/css" media="all">
	. error {
		font-weight: bold;
		color: #C00;
	}
	</style>
</head>
<body>
<?php #Script 11.2 - upload_image.php

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Check for an uploaded file:
	if (isset($_FILES['upload'])) {

		// Validate the type. Should be JPEG, PNG, GIF, or PDF.

		$allowed = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
		if (in_array($_FILES['upload']['type'], $allowed)) {
			// Move the file over.
			if (move_uploaded_file($_FILES['upload']['tmp_name'], "../../uploads/{$_FILES['upload']['name']}")) {
				echo '<p><em>The file has been uploaded!</em></p>';
			} // end of move...IF
		
		} else { // Invalid type
			echo '<p class="error">Please upload a JPEG, PNG, or GIF file.</p>';
		}

	} // End of isset($_FILES['upload']) IF.

	// Check for an error:
	if ($_FILES['upload']['error'] > 0) {
		echo '<p class="error">The file could not be uploaded because: <strong>';

		// Print a message based upon the error.
		switch($_FILES['upload']['error']) {
			case 1:
				print 'The file exceeds the upload_max_filesize setting in php.ini';
				break;
			case 2:
				print 'The file exceeds the MAX_FILES_SIZE setting in the HTML form.';
				break;
			case 3:
				print 'The file was only partially uploaded.';
				break;
			case 4:
				print 'No file was uploaded.';
				break;
			case 6:
				print 'No temporary folder was available.';
				break;
			case 7:
				print 'Unable to write to the disk.';
				break;
			case 8:
				print 'File upload stopped.';
				break;
			default:
				print 'A system error occurred.';
				break;
		}	// End of switch.

		print '</strong></p>';
	
	} // End of Error IF

	// Delete the file if it still exists:
	if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
		unlink($_FILES['upload']['tmp_name']);
	}

} // End of the submitted conditional.
?>

<form enctype="multipart/form-data" action="upload_image.php" method="post">

<input type="hidden" name="MAX_FILE_SIZE" value="524288" />

<fieldset><legend>Select a JPEG, PNG, or GIF file 512KB or smaller to be uploaded:</legend>

<p><b>File</b><input type="file" name="upload" /></p>

</fieldset>
<div align="center">
<input type="submit" name="submit" value="Submit" /></div>
</form>
</body>
</html>