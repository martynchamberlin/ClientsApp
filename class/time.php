<? 
// The lines between "time" and "expense" are blurred since we are constantly wanting to show them together. Despite (or rather, due to) my efforts to keep everything object oriented, I am aware of the confusion.
abstract class Time
{
	static function getPeriod($format = 'F')
	{
		if (!isset($_SESSION['period']))
		{
			$_SESSION['period'] = date('F Y');
		}
		return date($format, strtotime($_SESSION['period']));
	}

	static function getSpecificTime($id)
	{
		$sql = 'SELECT * 
FROM lookup L
INNER JOIN times T ON L.clientID = T.clientID
WHERE T.id = :id
AND T.id = L.postID
AND L.postType = "time"';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('id',$id);
		$s->execute();
		return $s->fetch();
	}

	static function updatePeriod($period)
	{
		$_SESSION['period'] = $period;
	}

	static function showMonths($num)
	{
		$months = array();
		$currentMonth = date('F Y');
		for ($i = 0; $i < $num; $i++)
		{
			array_push($months, $currentMonth);
			$currentMonth = date('F Y', strtotime('first day of ' . -($i + 1) . ' months'));
		}
		return $months;
	}

	static function showDays()
	{
		$days = array();
		$total = date('t', strtotime($_SESSION['period']));
		for ($i = 1; $i <= $total; $i++)
			array_push($days, $i);
		return $days;
	}
 
	static function addTime()
	{
		$sql = 'INSERT INTO times SET 
			clientID = :clientID, 
			userID = :userID, 
			timeAmount = :timeAmount,
			taskID = :taskID,
			comments = :comments';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('timeAmount', $_POST['timeAmount']);
		$s->bindValue('taskID', $_POST['taskID']);
		$s->bindValue('comments', $_POST['comments']);
		$s->execute();
		$postID = $core->pdo->lastInsertId();

		// We've got the actual content in there, and now we have to populate the lookup table. 
		$sql = 'INSERT INTO lookup SET 
			userID = :userID, 
			clientID = :clientID, 
			postID = :postID, 
			date = :date, 
			postType = "time"';
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('postID', $postID);
		$s->execute();
		header('Location: /view/?clientID=' . $_POST['clientID']);
		exit;
	}

	static function updateTime($id, $redirect = '/')
	{
		$sql = 'UPDATE times SET clientID = :clientID, timeAmount = :timeAmount, taskID = :taskID, comments = :comments WHERE id = :id AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('timeAmount', $_POST['timeAmount']);
		$s->bindValue('taskID', $_POST['taskID']);
		$s->bindValue('comments', $_POST['comments']);
		$s->bindValue('id', $id);
		$s->execute();

		$sql = 'UPDATE lookup SET 
			clientID = :clientID, 
			date = :date 
			WHERE postType = "time" AND postID = :postID';
		
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('postID', $id);
		$s->execute();

		header('Location: ' . $redirect);
		exit;
	}

	static function deleteTime($id, $redirect = '/')
	{
		$sql = 'DELETE FROM times WHERE id = :id AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('id', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->execute();

		if ($s->rowCount() > 0 )
		{
			$sql = 'DELETE FROM lookup WHERE postID = :postID AND postType = "time" AND userID = :userID';
			$s = $core->pdo->prepare($sql);
			$s->bindValue('postID', $id);
			$s->bindValue('userID', $_SESSION['loggedIn']['id']);
			$s->execute();
		}
		header('Location: ' . $redirect);
		exit;
	}
	
	// This method is ONLY for showing individual clients. An ID is required in order for this function to work.  
	static function showSinglePeriod($id)
	{
		$period = $_SESSION['period'];
		$begin = strtotime($period);
		$end = strtotime('+1 month', $begin);

		$sql = 'SELECT *, T.id as timeID FROM clients C
INNER JOIN lookup L ON C.clientID = L.clientID
INNER JOIN times T on L.postID = T.id AND L.postType = "time"
LEFT JOIN tasks TA ON T.taskID = TA.taskID
WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND L.userID = ' . $_SESSION['loggedIn']['id'] . '
AND C.clientID = "' . $id . '"
ORDER BY L.date DESC';
//ORDER BY TA.taskName, L.date';
//echo $sql;
		$core = Core::getInstance();
		$s = $core->pdo->query($sql); 
		$data = $s->fetchAll();
		return $data;
	}

	// You'll notice that instead of a bunch of fabulous PHP code, we're just doing some fancy SQL querying. This is largely due to my productive conversation with Instructor Crandell. This is easily the most important, complex query in the entire application.
	static function showHomePeriod()
	{
		$period = $_SESSION['period'];
		$begin = strtotime($period);
		$end = strtotime('+1 month', $begin);
		// We are ordering this return set by the amount of money the person owes us. I am confident this query will get much more complicated in the future, but right now it's downright beatiful and highly functional
		$sql = 'SELECT C.clientID, C.first, C.last, C.rate, 
    if(sum(T.timeAmount), sum(T.timeAmount), 0) as timeAmount, 
    if(sum(E.amount), sum(E.amount), 0) as expenseAmount, 
    if(sum(T.timeAmount), sum(T.timeAmount) * C.rate / 60, 0) + IF(sum(E.amount), sum(E.amount), 0) as orderBY FROM clients C
INNER JOIN lookup L ON C.clientID = L.clientID
LEFT JOIN times T on L.postID = T.id AND L.postType = "time"
LEFT JOIN expenses E on L.postID = E.id AND L.postType = "expense"
WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND L.userID = ' . $_SESSION['loggedIn']['id'] . '
GROUP BY C.clientID
ORDER BY IF(sum(T.timeAmount), sum(T.timeAmount) * C.rate / 60, 0)  + IF(sum(E.amount), sum(E.amount), 0) DESC, T.timeAmount DESC, C.last DESC';
		//echo $sql;
		$core = Core::getInstance();
		$s = $core->pdo->query($sql);
		return $s->fetchAll();
	}

	static function roundToHours($minutes)
	{
		return number_format($minutes / 60, 2);
	}

	static function calculateTotal($minutes, $rate = 50)
	{
		return $minutes / 60 * $rate;	
	}
}

