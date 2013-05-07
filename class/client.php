<?php

abstract class Client
{
	static $client_list = array();
	// Flag for ensuring method is only invoked once in page request
	static $bool_client_list = false;

	static function history( $clientID )
	{
		// Based on 1 year chunks
		$begin = strtotime( 'Jan 1, ' . substr($_SESSION['period'], -4) );
		$end = strtotime( "+1 year", $begin );
		$sql = 'SELECT *, T.id as timeID, E.amount as expenseAmount FROM clients C
INNER JOIN lookup L ON C.clientID = L.clientID
LEFT JOIN times T on L.post_id = T.lid AND L.postType = "time"
LEFT JOIN tasks TA ON T.taskID = TA.taskID
LEFT JOIN expenses E ON L.post_id = E.lid
WHERE
 L.userID = :userID
AND C.clientID = :clientID AND L.date >= ' . $begin . ' AND L.date < ' . $end . ' ORDER BY L.date ASC, L.post_id ASC';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
		return $s->fetchAll();
	}

	static function format_name( $client, $link = "" )
	{
		$output = "";
		$name = false;
		if ( ! empty( $client['first'] ) && ! empty( $client['last']) )
		{
			if ( !empty( $link ) )
			{
				$output .= "<a href=" . $link . ">" . $client['first'] . " " . $client['last'] . "</a>";
			}
			else
			{
				$output .= $client['first'] . " " . $client['last'];

			}
			$name = true;
		}

		if ( !empty( $client['company_name'] ) )
		{
			if ( $name )
			{
				$output .= " (" . $client['company_name'] . ")";
			}
			else
			{
				if ( !empty( $link ) )
				{
					$output .= "<a href=" . $link . ">" . $client['company_name'] . "</a>";
				}
				else
				{
					$output .= $client['company_name'];
				}
			}
		}
		return $output;
	}

	static function activate( $clientID )
	{
		$sql = 'UPDATE clients SET active="1" WHERE clientID = :clientID AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
	}

	static function deactivate( $clientID )
	{
		$sql = 'UPDATE clients SET active="0" WHERE clientID = :clientID AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
	}

	static function showClientList( $show_all = false )
	{
		Time::getPeriod();
		if ( ! self::$bool_client_list || $show_all )
		{
			$period = $_SESSION['period'];
			$begin = strtotime($period);
			$end = strtotime('+1 month', $begin);

			$sql = 'SELECT count(DISTINCT L.post_id) as primarySort, count(DISTINCT L2.post_id) as secondarySort, C.first, C.last, C.active, C.rate, C.email, C.clientID, C.company_name, C.billing_email FROM clients C
		LEFT JOIN lookup L on C.clientID = L.clientID AND L.date >= ' . $begin . ' AND L.date < ' . $end . '

		LEFT JOIN lookup L2 on C.clientID = L2.clientID 

		WHERE C.userID = ' . $_SESSION['loggedIn']['userID'] . '';
		if ( ! $show_all )
		{
			$sql .= ' AND C.active = 1 ';
		}

		$sql .= '	
		GROUP BY C.clientID
		ORDER BY primarySort DESC, secondarySort DESC, last, first';
			$core = Core::getInstance(); 
			$s = $core->pdo->query($sql); 
			$data = $s->fetchAll();
			self::$client_list = $data;
			self::$bool_client_list = true;
		}
		return self::$client_list;
	}

	static function replaceClient()
	{
		// Was originally using an `ON DUPLICATE KEY` clause to combine the `update`	 and `insert` queries, but it was too vunerable from a security standpoint
			$clientID = isset($_POST['clientID']) ? $_POST['clientID'] : substr(md5(microtime()), 0, 10);

		// They're deleting a client record
		if (isset($_POST['delete']))
		{
			self::removeClient($_POST['clientID']);
		}
		// They're updating a client record
		else if (isset($_POST['clientID'] ) )
		{
			$sql = 'UPDATE clients SET 
				first = :first, 
				last = :last, 
				email = :email, 
				rate=:rate,			
				billing_email = :billing_email,
				company_name = :company_name
				WHERE userID = :userID AND clientID = :clientID';
			$core = Core::getInstance();
			$s = $core->pdo->prepare($sql);
			$s->bindValue('clientID', $clientID);
			$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
			$s->bindValue('first', $_POST['first']);
			$s->bindValue('last', $_POST['last']);
			$s->bindValue('email', $_POST['email']);
			$s->bindValue('rate', $_POST['rate']);
			$s->bindValue(':company_name', $_POST['company_name']);
			$s->bindValue(':billing_email', $_POST['billing_email']);
			$s->execute();
		}
		// They're creating a new client record
		else
		{
			$sql = 'INSERT INTO clients SET 
				userID = :userID, 
				first = :first, 
				last = :last, 
				email = :email, 
				rate=:rate,
				billing_email = :billing_email,
				company_name = :company_name';
			$core = Core::getInstance();
			$s = $core->pdo->prepare($sql);
			$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
			$s->bindValue('first', $_POST['first']);
			$s->bindValue('last', $_POST['last']);
			$s->bindValue('email', $_POST['email']);
			$s->bindValue('rate', $_POST['rate']);
			$s->bindValue(':company_name', $_POST['company_name']);
			$s->bindValue(':billing_email', $_POST['billing_email']);
			$s->execute();
		}
		header('Location: /clients/');
		exit;
	}

	static function removeClient($id)
	{		
	
		$sql = 'DELETE C, T, L, E FROM clients C LEFT JOIN lookup L on L.clientID = C.clientID LEFT JOIN times T on T.lid = L.post_id LEFT JOIN expenses E on E.lid = L.post_id WHERE C.clientID = :clientID AND C.userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
	}

	static function retrieveClient($id)
	{
		$sql = 'SELECT * FROM clients C WHERE C.clientID = :clientID AND userID = :userID';
		$core = Core::getInstance();
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $id);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->execute();
		return $s->fetch();
	}
}
