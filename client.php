<? 

require 'includes.php'; 
require 'header.php'; 




if (isset($_GET['edit']))
{
	$edit = true;
	$client = Client::retrieveClient($_GET['edit']); 
}
else
	$edit = false;

?>
<h1><? echo (! $edit) ? 'New Client' : $client[0]['first'] . ' ' . $client[0]['last']; ?></h1>

<form action="" method="post" class="add-client">
<input type="hidden" name="replaceClient" value="replaceClient"/>
<? if ($edit) echo '<input type="hidden" name="clientID" value="' . $_GET['edit'] . '">' ; ?>
<label for="first">First Name</label>
<input type="text" name="first" value="<? echo ($edit) ? $client[0]['first'] : ''?>" id="first">

<label for="last">Last Name</label>
<input type="text" name="last" value="<? echo ($edit) ? $client[0]['last'] : ''?>" id="last">

<label for="email">Email</label>
<input type="text" name="email" value="<? echo ($edit) ? $client[0]['email'] : ''?>" id="email">

<label for="rate">Rate per hour</label>
<input type="text" name="rate" value="<? echo ($edit) ? $client[0]['rate'] : '50.00'?>"/>

<?php if ($edit) { ?>
<div class="overflow_hidden">
<input id="delete" type="checkbox" name="delete" value="delete"/>
<label id="delete_label" for="delete">Delete</label>
</div>
<?php } ?>

<input type="submit" value="<? echo ($edit) ? 'Save' : 'Add'?>">
</form>


<? require 'footer.php'; ?>

