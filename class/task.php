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

		$sql = 'SELECT count(DISTINCT L.post_id) as primarySort, count(DISTINCT L2.post_id) as secondarySort, taskName, TA.taskID FROM tasks TA LEFT JOIN times T ON TA.taskID = T.taskID 
		LEFT JOIN lookup L on T.lid = L.post_id AND L.date >= ' . $begin . ' AND L.date < ' . $end . ' 
		LEFT JOIN lookup L2 on T.lid = L2.post_id
		WHERE TA.userID = ' . $_SESSION['loggedIn']['userID'] . ' 
		GROUP BY TA.taskID 
		ORDER BY count(L.post_id) DESC, count(L2.post_id) DESC, taskName';
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
				$sql = 'UPDATE tasks SET taskName = :taskName, taskRate = :taskRate WHERE taskID = :taskID AND userID = :userID';
				$s = $core->pdo->prepare($sql);
				$s->bindValue('taskName', $_POST['taskName']);
				$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
				$s->bindValue('taskID', $_POST['taskID']);
				$s->bindValue('taskRate', $_POST['taskRate']);
			}
			else
			{
				$sql = 'INSERT INTO tasks SET taskName = :taskName, taskRate = :taskRate, userID = :userID';
				$s = $core->pdo->prepare($sql);
				$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
				$s->bindValue('taskName', $_POST['taskName']);
				$s->bindValue('taskRate', $_POST['taskRate']);
			}
			
			$s->execute();
		}
		header('Location: /tasks');
		exit;
	}

	static function removeTask($id)
	{		
	
		$sql = 'DELETE tasks, times, lookup FROM tasks 
			LEFT JOIN times on times.taskID = tasks.taskID AND tasks.taskID = :taskID AND times.userID = :userID
			LEFT JOIN lookup on times.lid = lookup.post_id AND lookup.postType="time" AND tasks.userID = :userID
			WHERE tasks.taskID = :taskID AND tasks.userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('taskID', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
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
