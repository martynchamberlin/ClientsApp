<?php

abstract class Paid
{
	static function delete_paid( $clientID, $month )
	{
		$sql = 'DELETE FROM paid WHERE clientID=:clientID AND month=:month';
		$core = Core::getInstance(); 
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('month', $month);
		$s->execute();
	}

	static function insert_paid( $clientID, $month )
	{
		$sql = 'INSERT INTO paid SET clientID=:clientID, month=:month, userID = :userID, date_added = :date_added';
		$core = Core::getInstance(); 
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('month', $month);
		$s->bindValue('userID', $_SESSION['loggedIn']['userID']);
		$s->bindValue('date_added', time());
		$s->execute();

	}

}

?>
