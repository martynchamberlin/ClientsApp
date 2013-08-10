<? 

require 'includes.php'; 

// If they're entering a new time for a specific client
if(isset($_POST['addTime']))
{
	$array = array('clientID', 'timeAmount', 'comments', 'taskID');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Time::addTime();
}

// If they're modifying a previously entered time
else if(isset($_POST['updateTime']))
{
	$array = array('clientID', 'timeAmount', 'comments', 'taskID');
	$errors = Validate::genVal($array);
	if (empty($errors))
		Time::updateTime($_POST['id'], urldecode($_POST['redirect']));
}

else if ( isset( $_GET['ajax'] ) )
{
	$tasks = Task::showTaskList( false, true, $_GET['client_id'] );
	echo $tasks[0]['taskID'];
	exit;
}

require 'header.php'; 

if (isset($_GET['time']))
{
	$edit = true;
	$time = Time::getSpecificTime($_GET['time']);
	if ($time['userID'] != $_SESSION['loggedIn']['userID'] )
	{
		echo '<h1>Silly hacker!</h1>
		<p>Oops, this time doesn\'t belong to you!</p>';
		exit;
	}
}
else
	$edit = false;

?>

<h1><? echo $edit ? 'Edit Entry' : 'New Time'; ?></h1>

<form action="" method="post" class="create-time ajax">
<? 
if ($edit)
{
	echo '<input type="hidden" name="updateTime" value="updateTime"/>';
	echo '<input type="hidden" name="id" value="' . $_GET['time'] . ' "/>';
	echo '<input type="hidden" name="redirect" value="' . urlencode($_GET['redirect']) . '"/>';
}
else
	echo '<input type="hidden" value="addTime" name="addTime"/>';
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
	echo ( $edit && $months[$i] == date('F Y', $time['date'])) ? 'selected="selected" ' : '';
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
<textarea id="accomplish" tabindex="2" name="comments"><?= $edit ? htmlspecialchars($time['comments']) : '' ?></textarea>
<input type="submit" value="Save">
<a href="/" class="cancel-time">Cancel</a>
</form>

<? require 'footer.php'; ?>
