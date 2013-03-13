<?php

abstract class Client
{
	static function showClientList()
	{
		$period = $_SESSION['period'];
		$begin = strtotime($period);

		$sql = 'SELECT count(L.postID) as primarySort, count(L2.postID) as secondarySort, C.first, C.last, C.clientID FROM clients C
LEFT JOIN lookup L on C.clientID = L.clientID AND L.date >= ' . $begin . '

LEFT JOIN lookup L2 on C.clientID = L2.clientID 

WHERE C.userID = ' . $_SESSION['loggedIn']['id'] . ' 
GROUP BY C.clientID
ORDER BY primarySort DESC, secondarySort DESC, last, first';

		$core = Core::getInstance(); 
		$s = $core->pdo->query($sql); 
		return $s->fetchAll();
	}

	static function replaceClient()
	{
		if (isset($_POST['delete']))
		{
			self::removeClient($_POST['clientID']);
		}
		else
		{
			$sql = 'INSERT INTO clients SET clientID = :clientID, userID = :userID, first = :first, last = :last, email = :email, rate=:rate ON DUPLICATE KEY UPDATE first = :first, last = :last, email = :email, rate = :rate';
			$core = Core::getInstance();
			$s = $core->pdo->prepare($sql);
			$clientID = isset($_POST['clientID']) ? $_POST['clientID'] : substr(md5(microtime()), 0, 10);
			$s->bindValue('clientID', $clientID);
			$s->bindValue('userID', $_SESSION['loggedIn']['id']);
			$s->bindValue('first', $_POST['first']);
			$s->bindValue('last', $_POST['last']);
			$s->bindValue('email', $_POST['email']);
			$s->bindValue('rate', $_POST['rate']);
			$s->execute();
		}
		header('Location: /time/');
		exit;
	}

	static function removeClient($id)
	{		
	
		$sql = 'DELETE C, T, L, E FROM clients C LEFT JOIN lookup L on L.clientID = C.clientID LEFT JOIN times T on T.id = L.postID LEFT JOIN expenses E on E.id = L.postID WHERE C.clientID = :clientID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $id);
		$s->execute();
	}

	static function retrieveClient($id, $getTimes = false)
	{
		if ($getTimes)
		{
			$sql = 'SELECT * FROM clients C LEFT JOIN times T on T.clientID = C.clientID WHERE C.clientID = :clientID ORDER BY T.date';
		}
		else
		{
			$sql = 'SELECT * FROM clients C WHERE C.clientID = :clientID';
		}

		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $id);
		$s->execute();
		return $s->fetchALL();
	}
}
