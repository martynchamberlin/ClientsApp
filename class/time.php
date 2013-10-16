<? 
// The lines between "time" and "expense" are blurred since we are constantly wanting to show them together. Despite (or rather, due to) my efforts to keep everything object oriented, I am aware of the confusion.
abstract class Time
{
	static function getPeriod($format = 'F Y')
	{
		if (!isset($_SESSION['period']))
		{
			$_SESSION['period'] = date('F Y');
		}
		return date($format, strtotime($_SESSION['period']));
	}

	static function get_month_totals( $time )
	{
			$begin = strtotime($time);
		$end = strtotime('+1 month', $begin);

		$sql = 'SELECT C.rate, if(P.id, P.id, "") as paid, if(sum(T.timeAmount), sum(T.timeAmount), 0) as timeAmount, if(sum(E.amount), sum(E.amount), 0) as expenseAmount FROM clients C INNER JOIN lookup L ON C.clientID = L.clientID LEFT JOIN times T on L.post_id = T.lid AND L.postType = "time" LEFT JOIN expenses E on L.post_id = E.lid AND L.postType = "expense" LEFT JOIN paid P on P.clientID=C.clientID AND P.month >= ' . $begin . ' AND P.month < ' . $end . ' WHERE L.date >= ' . $begin . ' AND L.date < ' . $end . ' AND L.userID = ' . $_SESSION['loggedIn']['userID'] . ' GROUP BY C.clientID';
	$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->execute();
		return $s->fetchAll();

	}

	static function timezones()
	{
    static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }

    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

        $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
    }

    return $timezone_list;

	}

	static function getSpecificTime($id)
	{
		$sql = 'SELECT * 
FROM lookup L
INNER JOIN times T ON L.clientID = T.clientID
WHERE T.lid = :id
AND T.lid = L.post_id
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
	
	// From now on we're officially ignoring this parameter.
	static function showMonths($num = 12, $all_years = true)
	{
		$months = array();
		$currentMonth = date('F Y');
		$firstMonth = date('F Y', $_SESSION['loggedIn']['creation']);

		while ( true )
		{
			if ( $all_years === true || date('Y', strtotime($currentMonth) ) == $all_years )
			array_push($months, $currentMonth);

			if ( $currentMonth == $firstMonth )
				break;

			else
			$currentMonth = date('F Y', strtotime('-1 month', strtotime( $currentMonth)));
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
		/** 
		 * If today is the day that the time log occured (which will 
		 * be true 99% of the time for most users) then let's get a more
		 * accurate time stamp. Who knows, we might use it for something
		 * in the future.
		 */
		$date = strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']);
		if ( $date == strtotime( "today" ) )
		{	
			$date = time();
		}
		$core = Core::getInstance();

		$sql = 'INSERT INTO lookup SET 
			userID = :userID, 
			clientID = :clientID, 
			date = :date, 
			postType = "time"';
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', $date);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
		$post_id = $core->pdo->lastInsertId();

		$sql = 'INSERT INTO times SET 
			clientID = :clientID, 
			userID = :userID, 
			timeAmount = :timeAmount,
			taskID = :taskID,
			lid = :lid,
			comments = :comments';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('lid', $post_id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->bindValue('timeAmount', $_POST['timeAmount']);
		$s->bindValue('taskID', $_POST['taskID']);
		$s->bindValue('comments', $_POST['comments']);
		$s->execute();
		header('Location: /view/?clientID=' . $_POST['clientID']);
		exit;
	}

	static function updateTime($id, $redirect = '/')
	{
		$sql = 'UPDATE times SET 
			clientID = :clientID, 
			timeAmount = :timeAmount, 
			taskID = :taskID, 
			comments = :comments 
		WHERE lid = :lid AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->bindValue('timeAmount', $_POST['timeAmount']);
		$s->bindValue('taskID', $_POST['taskID']);
		$s->bindValue('comments', $_POST['comments']);
		$s->bindValue('lid', $id);
		$s->execute();

		$sql = 'UPDATE lookup SET 
			clientID = :clientID, 
			date = :date 
			WHERE postType = "time" AND post_id = :id AND userID = :userID';
		
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('id', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();

		header('Location: /view/?clientID=' . $_POST['clientID']);
		exit;
	}

	static function deleteTime($id, $redirect = '/')
	{
		$sql = 'DELETE FROM times WHERE lid = :lid AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('lid', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();

		if ($s->rowCount() > 0 )
		{
			$sql = 'DELETE FROM lookup WHERE post_id = :id AND postType = "time" AND userID = :userID';
			$s = $core->pdo->prepare($sql);
			$s->bindValue('id', $id);
			$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
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
INNER JOIN times T on L.post_id = T.lid AND L.postType = "time"
LEFT JOIN tasks TA ON T.taskID = TA.taskID
WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND (L.userID = ' . $_SESSION['loggedIn']['userID'] . '  OR C.billing_email = "' . $_SESSION['loggedIn']['email'] . '")
AND C.clientID = "' . $id . '"
ORDER BY L.date DESC, L.post_id DESC';
//ORDER BY TA.taskName, L.date';
//echo $sql;
		$core = Core::getInstance();
		$s = $core->pdo->query($sql); 
		$data = $s->fetchAll();
		return $data;
	}

	// You'll notice that instead of a bunch of fabulous PHP code, we're just doing some fancy SQL querying. This is largely due to my productive conversation with instructor Crandell. This is easily the most important, complex query in the entire application.
	static function showHomePeriod()
	{
		$period = $_SESSION['period'];
		$begin = strtotime($period);
		$end = strtotime('+1 month', $begin);
		// We are ordering this return set by the amount of money the person owes us. I am confident this query will get much more complicated in the future, but right now it's downright beatiful and highly functional
		$sql = 'SELECT C.clientID, C.first, C.last, C.rate, C.company_name, C.billing_email, if(P.id, P.id, "") as paid,
    if(sum(T.timeAmount), sum(T.timeAmount), 0) as timeAmount, 
    if(sum(E.amount), sum(E.amount), 0) as expenseAmount, 
    if(sum(T.timeAmount), sum(T.timeAmount) * C.rate / 60, 0) + IF(sum(E.amount), sum(E.amount), 0) as orderBY FROM clients C
INNER JOIN lookup L ON C.clientID = L.clientID
LEFT JOIN times T on L.post_id = T.lid AND L.postType = "time"
LEFT JOIN expenses E on L.post_id = E.lid AND L.postType = "expense"
LEFT JOIN paid P on P.clientID=C.clientID AND P.month >= ' . $begin . ' AND P.month < ' . $end . ' 

WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND L.userID = ' . $_SESSION['loggedIn']['userID'] . '
GROUP BY C.clientID
ORDER BY IF(sum(T.timeAmount), sum(T.timeAmount) * C.rate / 60, 0)  + IF(sum(E.amount), sum(E.amount), 0) DESC, T.timeAmount DESC, C.last DESC';
	//	echo $sql;
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

