<?php # Script 12.1 - login_page.inc.php
// This page prints any errors associated with logging in
// and it creates the entire login page, including the form.

// Include the header:
$page_title = 'Login';
include('includes/header.html');

// Print any error messages, if they exist:
if (isset($errors) && !empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	if (is_array($errors)) {
		foreach($errors as $msg) {
			echo " - $msg<br />\n";
		}
	} else {
		echo " - $errors<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Display the form:
?><h1>Login</h1>
<form action="login.php" method="post">
<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="
<?php if (isset($_POST['email'])) echo "{$_POST['email']}"; ?>" /></p>
<p>Password: <input type="password" name="pass" size="20" maxlength="20" value="<?php
if (isset($_POST['pass'])) echo "{$_POST['email']}"; ?>"/></p>
<p><input type="submit" name="submit" value="Login" /></p>
</form>

<?php include ('includes/footer.html');