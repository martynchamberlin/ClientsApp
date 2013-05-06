<? 

require 'includes.php'; 
require 'header.php'; 

$tasks = Task::showTaskList( true );

echo '<h1>Your Tasks <a href="/task/">(Add New)</a></h1>';

if (empty($tasks) )
{
	echo "<center>You haven't created any tasks yet.</center>";
	exit;
}
?>

<table>
<thead>
<tr>
	<th class="no-name">&nbsp;</th>
	<th>Task name</th>
	<th>All time</th>
	<th><?= Time::getPeriod() ?></th>
</tr>
</thead>
<? foreach ( $tasks as $task ) : static $i = 1; ?>
	<tr>
		<td class="vertical-center count-wrap"><input type="hidden" value="<?=$task['taskID'];?>" name="taskID"/><div class="count<?= $task['active'] == 1 ? " active" : ""; ?>"><span><?= $i++; ?></span></div></td>	
		<td><a href="/task?edit=<?= $task['taskID']; ?>"><?= $task['taskName']; ?></a></td>
		<td><?= $task['secondarySort']; ?></td>
		<td><?= $task['primarySort']; ?></td>

	</tr>
<? endforeach; 

require 'footer.php'; ?>


