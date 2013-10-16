<? 

require 'includes.php'; 

// They're deleting their account
if (isset($_POST['delete']) && User::delete($_POST) === true )
{
	header('Location: /');
	exit;
}

$alert = isset($_POST['email']) && User::update($_POST) === true;
require 'header.php'; 

?>

<h1>Account Settings</h1>

<?
// They're updating their account
if ( $alert )
{
	echo '<p class="good-alert">Account successfully updated</p>';
}

?>

<form action = "" method="POST" class="account-settings">
	<div class="both">
		<label>First Name:</label>
		<div class="right">
			<input type="text" name="first_name" value="<?= $_SESSION['loggedIn']['first_name']; ?>" class="required">
			<?php if (!empty(User::$create_error_message['first_name'])){ echo ' <span class="error">'.User::$create_error_message['first_name'].'</span>'; } ?>
		</div>
	</div>
	
	<div class="both">
		<label>Last Name:</label>
		<div class="right">
			<input type="text" name="last_name" autocomplete="off" value="<?= $_SESSION['loggedIn']['last_name']; ?>" class="required">
			<?php if (!empty(User::$create_error_message['last_name'])){ echo ' <span class="error">'.User::$create_error_message['last_name'].'</span>'; } ?>
		</div>
	</div>
	
	<div class="both">
		<label>Email:</label>
		<div class="right">
			<input type="text" name="email" autocomplete="off" value="<?= $_SESSION['loggedIn']['email']; ?>" class="required email">
			<?php if (!empty(User::$create_error_message['email'])){ echo ' <span class="error">'.User::$create_error_message['email'].'</span>'; } ?>
		</div>
	</div>

<div class="both">
		<label>Timezone:</label>
		<div class="right">
			<select name="timezone">';
	<?php 
	$time_zones = Time::timezones();
	foreach ( $time_zones as $key=>$val )
	{
		echo '<option ';
		if ( get_user_meta( 'timezone' ) == $key )
		{
			echo 'selected="selected" ';
		}
		echo 'value="' . $key . "\">" . $val . "</option>";
	}
	?>
	</select>
	<?php if (!empty(User::$create_error_message['timezone'])){ echo ' <span class="error">'.User::$create_error_message['timezone'].'</span>'; } ?>
		</div>
	</div>

	<div class="both">
		<label>(Optional) New Password:</label>
		<div class="right">
			<input autocomplete="off" type="password" name="password" value="" class="required password1"/>
			<?php if (!empty(User::$create_error_message['password'])){ echo ' <span class="error">'.User::$create_error_message['password'].'</span>'; } ?>
		</div>
	</div>
		
	<div class="both">
		<input type="submit" class="register_input" value="Update Profile" name="continue"></div>
</form>


</form>

<p><a href="?delete" class="cancel">Cancel my account</a></p>

<div class="delete-section">
<p>We're sorry to see you go! All your data you've ever created on this platform will be immediately deleted by deactivating it below.</p>

<? if (isset($_GET['delete']) && isset(User::$login_error_message['password']) ) : ?>
<p class="error"><?= User::$login_error_message['password']; ?></p>
<? endif; ?>
<form action="?delete" method="POST" class="delete-account">
	<div class="both">
		<label>Password:</label>
		<div class="right">
			<input type="hidden" name="delete">
			<input type="password" name="password" class="required">
			<?php if (!empty(User::$create_error_message['first_name'])){ echo ' <span class="error">'.User::$create_error_message['first_name'].'</span>'; } ?>
		</div>
	</div>

		<div class="both">
		<input type="submit" class="delete" value="Permanently Delete Account" name="continue"></div>

</form>
</div>

<?
require 'footer.php'; ?>

