<? 

require 'includes.php'; 

// If they're trying to log in
if(isset($_POST['login']))
{
	User::logged_in();
}

require 'header.php'; 

?>


<h1>Log In</h1>

<form action="" method="post">
<input type="hidden" name="login">
<label for="email">Email</labeL>
<input type="text" name="email" <?= isset($_POST['email']) ? 'value="' . $_POST['email'] . '"' : ''; ?>>
<? if (isset( User::$login_error_message['email'] ) )
	echo '<p class="error">' . User::$login_error_message['email'] . '</p>';
?>

<label for="password">Password</label>
<input type="password" name="password"/>

<? if (isset( User::$login_error_message['password'] ) )
	echo '<p class="error">' . User::$login_error_message['password'] . '</p>';
?>

<input type="submit" value="Login">
</form>

<? require 'footer.php'; ?>
