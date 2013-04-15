<? 

require 'includes.php'; 
require 'header.php'; 




if (isset($_GET['edit']))
{
	$edit = true;
	$client = Client::retrieveClient($_GET['edit']);
	if ($client['userID'] != $_SESSION['loggedIn']['userID'] )
	{
		echo '<h1>Silly hacker!</h1>
		<p>Oops, this client doesn\'t belong to you!</p>';
		exit;
	}
}
else
	$edit = false;

?>
<h1><? echo (! $edit) ? 'New Client' : Client::format_name( $client ) ?></h1>

<div class="good-alert one">This is the email address to which you'll be sending invoices.</div>

<div class="good-alert two">If you are doing contract work instead of hourly billing with this client, make your hourly rate $0.00 and create one-time fees instead.</div>


<form action="" method="post" class="add-client">
<input type="hidden" name="replaceClient" value="replaceClient"/>
<? if ($edit) echo '<input type="hidden" name="clientID" value="' . $_GET['edit'] . '">' ; ?>

<label for="billing_email">Billing Email <span class="required">*</span></label>
<input type="text" id="billing_email" name="billing_email" value="<? echo ($edit) ? $client['billing_email'] : ''?>" >


<label for="rate">Rate per hour <span class="required">*</span></label>

<input type="text" name="rate" id="rate" value="<? echo ($edit) ? $client['rate'] : ''?>"/>


<label for="first">First Name (optional)</label>
<input type="text" name="first" value="<? echo ($edit) ? $client['first'] : ''?>" id="first">

<label for="last">Last Name (optional)</label>
<input type="text" name="last" value="<? echo ($edit) ? $client['last'] : ''?>" id="last">

<label for="email">Their Personal Email (optional)</label>
<input type="text" name="email" value="<? echo ($edit) ? $client['email'] : ''?>" id="email">



<label for="company_name">Company Name (optional)</label>
<input type="text" id="company_name" name="company_name" value="<? echo ($edit) ? $client['company_name'] : ''?>">


<?php if ($edit) { ?>
<div class="overflow_hidden">
<input id="delete" type="checkbox" name="delete" value="delete"/>
<label id="delete_label" for="delete">Delete</label>
</div>
<?php } ?>

<input type="submit" value="<? echo ($edit) ? 'Save Client' : 'Create Client'?>">
</form>


<? require 'footer.php'; ?>

