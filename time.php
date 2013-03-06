<? 

require 'includes.php'; 
require 'header.php'; 

if (isset($_GET['time']))
{
	$edit = true;
	$time = Time::getSpecificTime($_GET['time']); 
}
else
	$edit = false;

?>

<h1><? echo $edit ? 'Edit Entry' : 'New Time'; ?></h1>

<form action="" method="post">
<? 
if ($edit)
{
	echo '<input type="hidden" name="updateTime"/>';
	echo '<input type="hidden" name="id" value="' . $_GET['time'] . ' "/>';
	echo '<input type="hidden" name="redirect" value="' . urlencode($_GET['redirect']) . '"/>';
}
else
	echo '<input type="hidden" name="addTime"/>';
?>
<label for="first">Year/Month</label>
<select name="monthSelect">
	<? $months = Time::showMonths(12);
	for ($i = 0; $i < count($months); $i++)
	{
	echo '<option ';
	echo ($months[$i] == Time::getPeriod('F o') && ! $edit || $edit && $months[$i] == date('F o', $time['date'])) ? 'selected="selected" ' : '';
	echo 'value="' . $months[$i] . '">' . $months[$i] . '</option>';
}
?>
</select>

<label for="daySelect">Day</label>

<select name="daySelect" id="daySelect">

<?php	for ($i = 1; $i <= 31; $i++)
	{
	echo '<option ';
	echo ($i == date('j') && ! $edit || $edit && $i == date('j', $time['date'])) ? 'selected="selected" ' : '';
	echo 'value="' . $i . '">' . $i . '</option>';
}
?>
</select>

<label>Client</label>

<select name="clientID">
<? $clientList = Client::showClientList(); 
if (empty($clientList))
{
	echo '<option></option>';
}

foreach ($clientList as $instance)
{
	echo '<option value="' . $instance['clientID'] . '"';
	echo $edit && $instance['clientID'] == $time['clientID'] ? 'selected' : '';
	echo '>';
	echo $instance['first'] . ' ' . $instance['last'];
	echo '</option>';
}
?>
</select>

<label>Task</label>

<select name="taskID">

<? $taskList = Task::showTaskList(); 
if (empty($taskList))
{
	echo '<option></option>';
}
foreach ($taskList as $instance)
{
	echo '<option value="' . $instance['taskID'] . '"';
	echo $edit && $instance['taskID'] == $time['taskID'] ? 'selected' : '';
	echo '>';
	echo $instance['taskName'];
	echo '</option>';
}
?>
</select>

<label for="minutes">Time amount (minutes : seconds)</label>
<div class="both">
<input type="text" tabindex="501" name="timeAmount" id="minutes" value="<?= $edit ? $time['timeAmount'] : ''?>"/>
<div class="seconds">:<span id="seconds"></span></div>
<input type="submit" value="Start" id="startstop">
</div>
<label for="accomplish">What did you accomplish?</label>
<textarea id="accomplish" tabindex="502" name="comments"><?= $edit ? $time['comments'] : '' ?></textarea>
<input type="submit" value="Save">
</form>

<? require 'footer.php'; ?>
