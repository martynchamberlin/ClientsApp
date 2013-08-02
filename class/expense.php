<?

abstract class Expense
{

	static function getSpecificExpense($id)
	{
		$sql = 'SELECT * 
FROM lookup L
INNER JOIN expenses E ON L.clientID = E.clientID
WHERE E.lid = :id
AND E.lid = L.post_id
AND L.postType = "expense"';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('id',$id);
		$s->execute();
		return $s->fetch();
	}

	static function addExpense()
	{
		$core = Core::getInstance();

		$sql = 'INSERT INTO lookup SET 
			userID = :userID, 
			clientID = :clientID, 
			date = :date, 
			postType = "expense"';

		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
		$post_id = $core->pdo->lastInsertId();


		$sql = 'INSERT INTO expenses SET 
			clientID = :clientID, 
			userID = :userID, 
			amount = :amount, 
			lid = :lid, 
			comments = :comments';
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->bindValue('amount', $_POST['amount']);
		$s->bindValue('comments', $_POST['comments']);
		$s->bindValue('lid', $post_id);
		$s->execute();

		header('Location: /view/?clientID=' . $_POST['clientID']);
		exit;
	}

	static function updateExpense($id, $redirect = '/')
	{
		$sql = 'UPDATE expenses SET clientID = :clientID, amount = :amount, comments = :comments WHERE lid = :lid AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('amount', $_POST['amount']);
		$s->bindValue('comments', $_POST['comments']);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->bindValue('lid', $id);
		$s->execute();

		// Do not make this conditional based on previous row count. Because if they just change the date, then the expenses table will not be updated, and so the affected rowcount will = 0. Took me 20 minutes to figure this out.
		$sql = 'UPDATE lookup SET 
			clientID = :clientID, 
			date = :date 
			WHERE postType = "expense" AND post_id = :post_id';
		
		$s = $core->pdo->prepare($sql);
		$s->bindValue('date', strtotime($_POST['daySelect'] . ' ' . $_POST['monthSelect']));
		$s->bindValue('clientID', $_POST['clientID']);
		$s->bindValue('post_id', $id);
		$s->execute();

		header('Location: /view/?clientID=' . $_POST['clientID'] );
		exit;
	}

	static function deleteExpense($id, $redirect = '/')
	{
		$sql = 'DELETE FROM expenses WHERE lid = :lid AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('lid', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();

		if ($s->rowCount() > 0)
		{
			$sql = 'DELETE FROM lookup WHERE post_id = :post_id AND postType = "expense" AND userID = :userID';
			$s = $core->pdo->prepare($sql);
			$s->bindValue('post_id', $id);
			$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
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
INNER JOIN expenses E on L.post_id = E.lid AND L.postType = "expense"
WHERE L.date >= ' . $begin . ' 
		AND L.date < ' . $end . ' 
    AND L.userID = ' . $_SESSION['loggedIn']['userID'] . '
AND C.clientID = "' . $id . '"
ORDER BY L.date';

		$core = Core::getInstance();
		$s = $core->pdo->query($sql); 
		$data = $s->fetchAll();
		return $data;
	}
}

