<?

session_start();
date_default_timezone_set('America/North_Dakota/Beulah');
if (!empty($_POST))
{
	foreach ($_POST as $key=>$value)
	{
		$_POST[$key] = stripslashes($value);
	}
}
require 'markdown.php';
require 'class/user.php';
require 'class/config.php';
require 'class/core.php';
require 'class/validate.php';
require 'class/client.php';
require 'class/time.php';
require 'class/expense.php';
require 'class/task.php';

// Now that we're done calling all the classes, we need to handle all of our conditionals before showing the actual content

// If they're not logged in, send them to the login page
if ( ! User::logged_in() && (!Core::isPage('login') && !Core::isPage('landing') && ! Core::isPage('signup')) )
{
	header('location: /landing/ ');
	exit;
}

else if ( User::logged_in() && Core::isPage('login') )
{
	header('location: / ');
	exit;
}	

// If they're updating the month period they're wanting to look at
if(isset($_POST['monthSelect']))
	Time::updatePeriod($_POST['monthSelect']);



// If they're creating a new client or updating an old one
else if(isset($_POST['replaceClient']))
{
	$array = array('first', 'last', 'email', 'rate');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Client::replaceClient();
}

// If they're deleting, updating, or creating a new task
else if (isset($_POST['replaceTask']))
{
	$array = array('taskName');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Task::replaceTask();
}






