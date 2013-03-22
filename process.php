<?
require 'includes.php'; 

$core = Core::getInstance(); 
$sql = 'SELECT T.id as time_id, L.post_id as lookup_id FROM lookup L INNER JOIN times T ON L.postID = T.id AND postType = "time"';
$s = $core->pdo->query($sql); 
			$data = $s->fetchAll();
foreach ($data as $row)
{
	$sql = 'UPDATE times T SET T.lid = ' . $row['lookup_id'] . ' WHERE T.id = ' . $row['time_id'];
	$core->pdo->query($sql);
}

