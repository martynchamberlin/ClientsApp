<? 

require 'includes.php'; 
require 'header.php'; 

if (isset($_GET['edit']))
{
	$edit = true;
	$task = Task::retrieveTask($_GET['edit']); 
}
else
	$edit = false;

?>
<h1><? echo (! $edit) ? 'New Task' : 'Edit Task' ?></h1>

<form action="" method="post" class="create-task">
<input type="hidden" name="replaceTask" value="replacetask"/>
<? if ($edit) echo '<input type="hidden" name="taskID" value="' . $_GET['edit'] . '">' ; ?>
<label for="first">Name</label>
<input type="text" name="taskName" value="<? echo ($edit) ? $task['taskName'] : ''?>" id="first">

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

