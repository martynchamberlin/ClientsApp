<?

abstract class Expense
{

	static function getSpecificExpense($id)
	{
		$sql = 'SELECT * 
FROM lookup L
INNER JOIN expenses E ON L.clientID = E.clientID
WHERE E.id = :id
AND E.id = L.postID
AND L.postType = "expense"';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('id',$id);
		$s->execute();
		return $s->fetch();
	}

	static function addExpense()
	{
		$sql = 'INSERT INTO expenses SET clientID = :clientID, userID = :userID, amount = :amount, comments = :comments';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('amount', $_POST['amount']);
		$s->bindValue('comments', $_POST['comments']);
		$s->execute();
		$postID = $core->pdo->lastInsertId();

		$sql = 'INSERT INTO lookup SET 
			userID = :userID, 
			clientID = :clientID, 
			postID = :postID, 
			date = :date, 
			postType = "expense"';

		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('postID', $postID);
		$s->execute();

		header('Location: /view/?clientID=' . $_POST['clientID']);
		exit;
	}

	static function updateExpense($id, $redirect = '/')
	{
		$sql = 'UPDATE expenses SET clientID = :clientID, amount = :amount, comments = :comments WHERE id = :id AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('amount', $_POST['amount']);
		$s->bindValue('comments', $_POST['comments']);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->bindValue('id', $id);
		$s->execute();

		// Do not make this conditional based on previous row count. Because if they just change the date, then the expenses table will not be updated, and so the affected rowcount will = 0. Took me 20 minutes to figure this out.
		$sql = 'UPDATE lookup SET 
			clientID = :clientID, 
			date = :date 
			WHERE postType = "expense" AND postID = :postID';
		
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('postID', $id);
		$s->execute();

		header('Location: ' . $redirect);
		exit;
	}

	static function deleteExpense($id, $redirect = '/')
	{
		$sql = 'DELETE FROM expenses WHERE id = :id AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('id', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['id']);
		$s->execute();

		if ($s->rowCount() > 0)
		{
			$sql = 'DELETE FROM lookup WHERE postID = :postID AND postType = "expense"';
			$s = $core->pdo->prepare($sql);
			$s->bindValue('postID', $id);
			$s->execute();
		}

		header('Location: ' . $redirect);
		exit;
	}

	static function showPeriod($id)
	{
		$period = $_SESSION['period'];
		$begin = strtotime($period);
		$end = strtotime('+1 month', $begin);
		$sql = 'SELECT *, E.id as expenseID FROM clients C
INNER JOIN lookup L ON C.clientID = L.clientID
INNER JOIN expenses E on L.postID = E.id AND L.postType = "expense"
WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND L.userID = ' . $_SESSION['loggedIn']['id'] . '
AND C.clientID = "' . $id . '"
ORDER BY L.date';

		$core = Core::getInstance();
		$s = $core->pdo->query($sql); 
		$data = $s->fetchAll();
		return $data;
	}
}

