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
require 'class/user.php';
require 'class/config.php';
require 'class/core.php';
require 'class/validate.php';
require 'class/client.php';
require 'class/time.php';
require 'class/expense.php';
require 'class/task.php';

// Now that we're done calling all the classes, we need to handle all of our conditionals before showing the actual content

// If they're updating the month period they're wanting to look at
if(isset($_POST['monthSelect']))
	Time::updatePeriod($_POST['monthSelect']);

// If they're creating a new client or updating an old one
if(isset($_POST['replaceClient']))
{
	$array = array('first', 'last', 'email', 'rate');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Client::replaceClient();
}

// If they're entering a new time for a specific client
if(isset($_POST['addTime']))
{
	$array = array('clientID', 'timeAmount', 'comments', 'taskID');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Time::addTime();
}

// If they're deleting, updating, or creating a new task
if (isset($_POST['replaceTask']))
{
	$array = array('taskName');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Task::replaceTask();
}

// If they're modifying a previously entered time
if(isset($_POST['updateTime']))
{
	$array = array('clientID', 'timeAmount', 'comments', 'taskID');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Time::updateTime($_POST['id'], urldecode($_POST['redirect']));
}

// If they're entering a new expense for a specific client
if(isset($_POST['addExpense']))
{
	$array = array('clientID', 'amount', 'comments');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Expense::addExpense();
}

// If they're modifying a previously entered expense
if(isset($_POST['updateExpense']))
	Expense::updateExpense($_POST['id'], urldecode($_POST['redirect']));

// If they're trying to log in
if(isset($_POST['login']))
	User::login();

// If they're not logged in, send them to the login page
if (!isset($_SESSION['loggedIn']['email']) && !Core::isPage('login'))
{
	header('location: /login/ ');
	exit;
}



