<? 

require 'includes.php'; 
require 'header.php'; 

	echo '
	<h1>Account successfully created</h1>';
	echo '<p>Click <a href="#" class="fancybox">here</a> to log in</p>';
	exit;
if (isset($_POST['email']) && User::create($_POST) === true)
{
	echo '<h1>Account successfully created</h1>';
	echo '<p>Click <a href="#" class="fancybox">here</a> to log in</p>';
	exit;
}


?>

<h1>Signup for a free account</h1>
<form class="signup" action = "<?= Config::home() ."/signup/"; ?>" method="POST">
	<div class="both">
		<label>First Name:</label>
		<div class="right">
			<input type="text" name="first_name" value="<?php if (isset($_POST['first_name'])) { echo $_POST['first_name']; } ?>" class="required">
			<?php if (!empty(User::$create_error_message['first_name'])){ echo ' <span class="error">'.User::$create_error_message['first_name'].'</span>'; } ?>
		</div>
	</div>
	
	<div class="both">
		<label>Last Name:</label>
		<div class="right">
			<input type="text" name="last_name" autocomplete="off" value="<?php if (isset($_POST['last_name'])) { echo $_POST['last_name']; } ?>" class="required">
			<?php if (!empty(User::$create_error_message['last_name'])){ echo ' <span class="error">'.User::$create_error_message['last_name'].'</span>'; } ?>
		</div>
	</div>
	
	<div class="both">
		<label>Email:</label>
		<div class="right">
			<input type="text" name="email" autocomplete="off" value="<?php if (isset($_GET['email'])) { echo $_GET['email']; } else if (isset($_POST['email'])) echo $_POST['email']; ?>" class="required email">
			<?php if (!empty(User::$create_error_message['email'])){ echo ' <span class="error">'.User::$create_error_message['email'].'</span>'; } ?>
		</div>
	</div>
	
	
	<div class="both">
		<label>Password:</label>
		<div class="right">
			<input autocomplete="off" type="password" name="password" value="<?php if (isset($_GET['password'])) { echo $_GET['password']; } else if (isset($_POST['password'])) { echo $_POST['password']; } ?>" class="required password1"/>
			<?php if (!empty(User::$create_error_message['password'])){ echo ' <span class="error">'.User::$create_error_message['password'].'</span>'; } ?>
		</div>
	</div>
		
	<div class="both">
		<input type="submit" class="register_input" value="Complete" name="continue"></div>
</form>

<? require 'footer.php'; ?>

