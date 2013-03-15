<?php

abstract class Task
{
	static function showTaskList()
	{

		Time::getPeriod();
		$clientList = array();
		$period = $_SESSION['period'];
		$begin = strtotime($period);
		$end = strtotime('+1 month', $begin);

		$sql = 'SELECT count(L.postID) as primarySort, count(TA.taskID) as secondarySort, taskName, TA.taskID FROM tasks TA LEFT JOIN times T ON TA.taskID = T.taskID LEFT JOIN lookup L on T.id = L.postID AND L.date >= ' . $begin . ' AND L.date < ' . $end . ' WHERE TA.userID = ' . $_SESSION['loggedIn']['id'] . ' GROUP BY TA.taskID ORDER BY count(L.postID) DESC, count(TA.taskID) DESC, taskName';

		$core = Core::getInstance(); 
		$s = $core->pdo->query($sql);
		return $s->fetchAll();
	}

	static function replaceTask()
	{
		// Are they trying to delete
		if (isset($_POST['delete']))
		{
			self::removeTask($_POST['taskID']);
		}

		else
		{
			$core = Core::getInstance();

			// Are they trying to update?
			if (isset($_POST['taskID']))
			{
				$sql = 'UPDATE tasks SET taskName = :taskName WHERE taskID = :taskID';
				$s = $core->pdo->prepare($sql);
				$s->bindValue('taskName', $_POST['taskName']);
				$s->bindValue('taskID', $_POST['taskID']);
			}
			else
			{
				$sql = 'INSERT INTO tasks SET taskName = :taskName, userID = :userID';
				$s = $core->pdo->prepare($sql);
				$s->bindValue('userID', $_SESSION['loggedIn']['id']);
				$s->bindValue('taskName', $_POST['taskName']);
			}
			
			$s->execute();
		}
		header('Location: /');
		exit;
	}

	static function removeTask($id)
	{		
	
		$sql = 'DELETE tasks, times FROM tasks LEFT JOIN times on times.taskID = tasks.taskID WHERE tasks.taskID = :taskID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('taskID', $id);
		$s->execute();
	}

	static function retrieveTask($id)
	{
		$sql = 'SELECT * FROM tasks T WHERE T.taskID = :taskID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('taskID', $id);
		$s->execute();
		return $s->Fetch();
	}
}

?>
