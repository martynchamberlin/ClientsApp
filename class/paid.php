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
		$sql = 'INSERT INTO paid SET clientID=:clientID, month=:month';
		$core = Core::getInstance(); 
		$s = $core->pdo->prepare($sql);
		$s->bindValue('clientID', $clientID);
		$s->bindValue('month', $month);
		$s->execute();

	}

}

?>
