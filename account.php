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

<h1>Accunt Settings</h1>

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
	
	<div class="both color_scheme">
		<span class="label">Color Scheme:</span>
		<div class="right">
			<input type="radio" id="orange" name="color_scheme" value="orange" <?  if (User::color_scheme("orange") ) { echo "checked=\"checked\""; } ?>><label for="orange">Classic Orange</label>
			<br/>
<input type="radio" id="green" name="color_scheme" value="green" <?  if (User::color_scheme("green") ) { echo "checked=\"checked\""; } ?>><label for="green">Cold Green</label>
			<br/>
			<input type="radio" id="teal" name="color_scheme" value="teal" <?  if (User::color_scheme("teal") ) { echo "checked=\"checked\""; } ?>><label for="teal">Neo Teal</label>
			<br/>
			<input type="radio" id="blue" name="color_scheme" value="blue" <?  if (User::color_scheme("blue") ) { echo "checked=\"checked\""; } ?>><label for="blue">Corporate Blue</label>
			<br/>
			<input type="radio" id="red" name="color_scheme" value="red" <?  if (User::color_scheme("red") ) { echo "checked=\"checked\""; } ?>><label for="red">Crazy Red</label>
			<br/>
			<input type="radio" id="bw" name="color_scheme" value="bw" <?  if (User::color_scheme("bw") ) { echo "checked=\"checked\""; } ?>><label for="bw">Black & White</label>
			<br/>

			<br/>
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

