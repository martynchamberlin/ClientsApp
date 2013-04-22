<? 
require 'includes.php'; 

if (isset($_POST['action']) ) // Handles home page
{
	if ($_POST['action'] == 'delete')
	{
		Paid::delete_paid($_POST['clientID'], $_POST['month']);
	}
	else if ($_POST['action'] == 'insert')
	{
		Paid::insert_paid($_POST['clientID'], $_POST['month']);
	}
}

else if ( isset($_POST['clientID'] ) ) // handles /clients/ page
{
	if ( $_POST['status'] == "0" )
	{
		Client::deactivate( $_POST['clientID'] );
	}
	else
	{
		Client::activate( $_POST['clientID'] );
	}
}

else if ( isset( $_POST['taskID'] ) ) // handles /tasks/ page
{
	if ( $_POST['status'] == "0" )
	{
		Task::deactivate( $_POST['taskID'] );
	}
	else
	{
		Task::activate( $_POST['taskID'] );
	}
}

/*
?>
<form action="" method="post">
<input type="text" name="ID" value="12">
<input type="text" name="status" value="0">
<input type="submit">
</form>

*/

