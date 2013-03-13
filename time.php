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
<div  class="overflow_hidden">
<div class="left">
<label>Client</label>

<select name="clientID" tabindex="501">
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

<select name="taskID" tabindex="502">

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
</div><!-- end .left -->
<div class="right">
<label for="first">Year/Month</label>
<select name="monthSelect" tabindex="1001">
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

<select name="daySelect" id="daySelect" tabindex="1002">

<?php	for ($i = 1; $i <= 31; $i++)
	{
	echo '<option ';
	echo ($i == date('j') && ! $edit || $edit && $i == date('j', $time['date'])) ? 'selected="selected" ' : '';
	echo 'value="' . $i . '">' . $i . '</option>';
}
?>
</select>
</div>

</div><!-- end .overflow_hidden -->

<label for="minutes">Time amount (minutes : seconds)</label>
<div class="both">
<input type="submit" value="Start" id="startstop"><input type="text" tabindex="1" name="timeAmount" id="minutes" value="<?= $edit ? $time['timeAmount'] : ''?>"/>
<div class="seconds">:&nbsp;<span id="seconds"></span></div>

</div>
<label for="accomplish">What did you accomplish?</label>
<textarea id="accomplish" tabindex="2" name="comments"><?= $edit ? $time['comments'] : '' ?></textarea>
<input type="submit" value="Save">
</form>

<? require 'footer.php'; ?>
