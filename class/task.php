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

		$sql = 'SELECT count(DISTINCT L.postID) as primarySort, count(DISTINCT L2.postID) as secondarySort, taskName, TA.taskID FROM tasks TA LEFT JOIN times T ON TA.taskID = T.taskID 
		LEFT JOIN lookup L on T.id = L.postID AND L.date >= ' . $begin . ' AND L.date < ' . $end . ' 
		LEFT JOIN lookup L2 on T.id = L2.postID
		WHERE TA.userID = ' . $_SESSION['loggedIn']['userID'] . ' 
		GROUP BY TA.taskID 
		ORDER BY count(L.postID) DESC, count(L2.postID) DESC, taskName';
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
				$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
				$s->bindValue('taskName', $_POST['taskName']);
			}
			
			$s->execute();
		}
		header('Location: /tasks');
		exit;
	}

	static function removeTask($id)
	{		
	
		$sql = 'DELETE tasks, times, lookup FROM tasks LEFT JOIN times on times.taskID = tasks.taskID WHERE tasks.taskID = :taskID LEFT JOIN lookup on times.id = lookup.postID AND lookup.postType="time"';
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
