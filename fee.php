<? 

require 'includes.php';


// If they're entering a new expense for a specific client
if(isset($_POST['addExpense']))
{
	$array = array('clientID', 'amount', 'comments');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Expense::addExpense();
}

// If they're modifying a previously entered expense
else if(isset($_POST['updateExpense']))
	Expense::updateExpense($_POST['id'], urldecode($_POST['redirect']));



require 'header.php'; 

if (isset($_GET['expense']))
{
	$edit = true;
	$expense = Expense::getSpecificExpense($_GET['expense']);
	if ($expense['userID'] != $_SESSION['loggedIn']['userID'] )
	{
		echo '<h1>Silly hacker!</h1>
		<p>Oops, this expense doesn\'t belong to you!</p>';
		exit;
	}

}
else
	$edit = false;

?>

<h1><? echo $edit ? 'Edit Fee' : 'New Fee'; ?></h1>

<form action="" method="post" class="create-fee">
<? 
if ($edit)
{
	echo '<input type="hidden" name="updateExpense"/>';
	echo '<input type="hidden" name="id" value="' . $_GET['expense'] . '"/>';
	echo '<input type="hidden" name="redirect" value="' . urlencode($_GET['redirect']) . '"/>';
}
else
	echo '<input type="hidden" name="addExpense"/>';
?>
<label for="first">Year/Month</label>
<select name="monthSelect">
	<? $months = Time::showMonths(12);
	for ($i = 0; $i < count($months); $i++)
	{
	echo '<option ';
	echo ($months[$i] == Time::getPeriod('F o') && ! $edit || $edit && $months[$i] == date('F o', $expense['date'])) ? 'selected="selected" ' : '';
	echo 'value="' . $months[$i] . '">' . $months[$i] . '</option>';
}
?>
</select>

<label for="daySelect">Day</label>

<select name="daySelect" id="daySelect">

<?php	for ($i = 1; $i <= 31; $i++)
	{
	echo '<option ';
	echo ($i == date('j') && ! $edit || $edit && $i == date('j', $expense['date'])) ? 'selected="selected" ' : '';
	echo 'value="' . $i . '">' . $i . '</option>';
}
?>
</select>

<label>Client</label>

<select name="clientID">
<? $clientList = Client::showClientList(); 
foreach ($clientList as $instance)
{
	echo '<option value="' . $instance['clientID'] . '"';
	echo $edit && $instance['clientID'] == $expense['clientID'] ? 'selected' : '';
	echo '>';
	echo $instance['first'] . ' ' . $instance['last'];
	echo '</option>';
}
?>
</select>

<label for="amount">Amount</label>
<div class="both">
<span>$</span><input type="text" name="amount" id="amount" value="<?= $edit ? $expense['amount'] : ''?>"/>
<label for="accomplish">Briefly describe the fee.</label>
<textarea id="accomplish" name="comments"><?= $edit ? $expense['comments'] : '' ?></textarea>
<input type="submit" value="<?= $edit ? 'Save' : 'Add' ?>">
</form>

<? require 'footer.php'; ?>
