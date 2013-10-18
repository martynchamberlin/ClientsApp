<?

session_start();
$time_zone = get_user_meta( 'timezone' );
if ( empty( $time_zone ) )
{
	$time_zone = 'America/North_Dakota/Beulah';
}
date_default_timezone_set( $time_zone );

if (!empty($_POST))
{
	foreach ($_POST as $key=>$value)
	{
		$_POST[$key] = stripslashes($value);
	}
}
function get_user_meta( $meta )
{	
	if ( isset( $_SESSION['loggedIn'][$meta] ) )
	{
		return $_SESSION['loggedIn'][$meta];
	}
	else 
	{
		return "";
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
require 'class/paid.php';

// Now that we're done calling all the classes, we need to handle all of our conditionals before showing the actual content

if ( Config::ssl() )
{
	Validate::check_ssl();
}


if ( User::logged_in() && Core::is_page('login') )
{
	$redirect = isset( $_POST['redirect-login'] ) ? $_POST['redirect-login'] : "";
	header('location: ' .Config::home() . $redirect);
	exit;
}	

// If they're updating the month period they're wanting to look at
if(isset($_POST['monthSelect']))
{
	Time::updatePeriod($_POST['monthSelect']);
	if (isset($_POST['in_header']))
	{
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
	}
}


// If they're creating a new client or updating an old one
else if(isset($_POST['replaceClient']))
{
	$array = array('billing_email', 'rate');
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

function make_links_clickable( $code )
{
	// This is beautiful but it can't handle complex links ;(
$code = preg_replace('@(\s|^)(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-1234567890=]*(\?\S+)?[^\.\s])?)?)@', '$1<notextile><a href="$2" target="_blank">$2</a></notextile>', $code);

	
	return $code;
}





