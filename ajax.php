<? 
require 'includes.php'; 

if (isset($_POST['action']) )
{
	echo 'boo!';
	if ($_POST['action'] == 'delete')
	{
		Paid::delete_paid($_POST['clientID'], $_POST['month']);
	}
	else if ($_POST['action'] == 'insert')
	{
		Paid::insert_paid($_POST['clientID'], $_POST['month']);
	}
}

/*


<form action="" method="post">
<input type="text" name="clientID" value="65e852e6d0">

<input type="text" name="action" value="delete">
<input type="text" name="month" value="1365051600">
<input type="submit">
</form>


*/

?>

