<? 

require 'includes.php'; 
require 'header.php'; 

$tasks = Task::showTaskList();

echo '<h1>Your Tasks';
	if ( ! empty( $tasks) ) echo  ' <a href="/task/">(Add New)</a>';
echo '</h1>';

if (empty($tasks) )
{
	echo "You haven't created any tasks yet. <a href=\"/task\">Click here to add one</a>.";
	exit;
}
?>

<table>
<thead>
<tr>	<th>&nbsp;</th>

	<th>Task name</th>
	<th>All time</th>
	<th><?= Time::getPeriod() ?></th>
</tr>
</thead>
<? foreach ( $tasks as $task ) : static $i = 1; ?>
	<tr>
		<td><? echo $i; $i++; ?></td>
		<td><a href="/task?edit=<?= $task['taskID']; ?>"><?= $task['taskName']; ?></a></td>
		<td><?= $task['secondarySort']; ?></td>
		<td><?= $task['primarySort']; ?></td>

	</tr>
<? endforeach; 

require 'footer.php'; ?>


