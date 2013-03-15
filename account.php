<? 

require 'includes.php'; 
require 'header.php'; 

?>

<h1>Accunt Settings</h1>

<?
if (isset($_POST['email']) && User::update($_POST) === true)
{
	echo '<p>Account successfully updated</p>';
}
?>
<form action="" method="post">

<form action = "<?= Config::home() ."/account/"; ?>" method="POST">
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
		<label>(Optional) New Password:</label>
		<div class="right">
			<input autocomplete="off" type="password" name="password" value="" class="required password1"/>
			<?php if (!empty(User::$create_error_message['password'])){ echo ' <span class="error">'.User::$create_error_message['password'].'</span>'; } ?>
		</div>
	</div>
		
	<div class="both">
		<input type="submit" class="register_input" value="Complete" name="continue"></div>
</form>


</form>


<?
require 'footer.php'; ?>

