<? 
require 'includes.php'; 
require 'header.php'; 

echo '<h1>Task History — ' . substr($_SESSION['period'], -4) . '</h1>';

$taskList = Task::showTaskList( true );
echo '<center><select name="taskList">';
if (empty($taskList))
{
	echo '<option></option>';
}
else
{
	echo '<option value="">Select Task</option>';
}
foreach ($taskList as $instance)
{
	echo '<option value="' . $instance['taskID'] . '"';
	echo isset( $_GET['taskID'] ) && $_GET['taskID'] == $instance['taskID'] ? 'selected' : '';
	echo '>';
	echo $instance['taskName'];
	echo '</option>';
}
echo '</select></center><br/>';

if ( isset ($_GET['taskID'])  )
{

	$task = $_GET['taskID'];
	$history = Task::history( $task );
	if ( empty( $history ) )
	{
	echo '<center>No history for this task in this year.</center>';
	}
else
{
	echo '
<table class="">
	<thead>
	<tr>
	<th style="width: 40px;"></th>
	<th style="width: 40px">Date</th>
	<th></th>
	<th class="summary">Time</th>
	<th class="summary">Total Time</th>
	<th>Total Income</th>

	</tr>
</thead>';

	$previous_month = ""; 
	$total_time = 0; 
	foreach ( $history as $task ) 
	{
		$total_time += $task['timeAmount'];
		static $i = 0; 
		$new_month = false;
		if ( date('M', $task['date'] ) != $previous_month ) 
		{
			$new_month = true;
		}
		$previous_month = date('M', $task['date'] ); 

		echo '<tr'; if ( $new_month) echo ' class="new"'; echo '>';
		echo '<td class="border count-wrap"><a class="circle" href="/time?time=' . $task['lid'] . '&redirect=/view?' . $task['clientID'] . '"><span>' . ++$i . '</span></a></td>';
		echo '<td>';
		echo date('M', $task['date'] ) . '</td>';
		echo '<td>' . date('j', $task['date'] ) . '</td>';
		echo '<td>' . $task['timeAmount'] . ' minutes</td>';
		echo '<td>' . Time::roundToHours( $total_time ) . ' hours</td>';
		echo '<td>$' . number_format($total_time / 60 * $task['rate'], 2);
		echo '</tr>';
	}
}
}
require 'footer.php'; ?>

