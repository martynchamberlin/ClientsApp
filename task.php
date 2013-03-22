<? 

require 'includes.php'; 
require 'header.php'; 

if (isset($_GET['edit']))
{
	$edit = true;
	$task = Task::retrieveTask($_GET['edit']); 
	if ($task['userID'] != $_SESSION['loggedIn']['userID'] )
	{
		echo '<h1>Silly hacker!</h1>
		<p>Oops, this task doesn\'t belong to you!</p>';
		exit;
	}
}
else
	$edit = false;

?>
<h1><? echo (! $edit) ? 'New Task' : 'Edit Task' ?></h1>
<!--
<div class="good-alert">Not all tasks are created equal! If this one's special, give it a different hourly rate. Otherwise, it'll defer to its client's hourly rate.</div>-->
<form action="" method="post" class="create-task">
<input type="hidden" name="replaceTask" value="replacetask"/>
<? if ($edit) echo '<input type="hidden" name="taskID" value="' . $_GET['edit'] . '">' ; ?>
<label for="first">Name</label>
<input type="text" name="taskName" value="<? echo ($edit) ? $task['taskName'] : ''?>" id="first">
<!--
<label for="taskRate">Task rate:</label>
<input type="text" name="taskRate" placeholder="Purely optional" value="<? echo ($edit) ? $task['taskRate'] : ''?>" id="taskRate">
-->
<?php if ($edit) { ?>
<input type="hidden" value="<?= $_GET['redirect']; ?>" name="redirect">

<div class="overflow_hidden">
<input id="delete" type="checkbox" name="delete" value="delete"/>
<label id="delete_label" for="delete">Delete</label>
</div>
<?php } ?>

<input type="submit" value="<? echo ($edit) ? 'Save' : 'Add'?>">
</form>


<? require 'footer.php'; ?>

