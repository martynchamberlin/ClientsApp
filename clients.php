<? 

require 'includes.php'; 
require 'header.php'; 

$clients = Client::showClientList();

echo '<h1>Your Clients';
	if ( ! empty( $clients) ) echo  ' <a href="/client/">(Add New)</a>';
echo '</h1>';

if (empty($clients) )
{
	echo "You haven't created any clients yet. <a href=\"/client\">Click here to add one</a>.";
	exit;
}
?>

<table>
<thead>
<tr>
	<th>&nbsp;</th>
	<th>Name</th>
	<th>Email</th>
	<th>Rate</th>
	<th>All time</th>
	<th><?= Time::getPeriod() ?></th>

</tr>
</thead>
<? foreach ( $clients as $client ) : static $i = 1;?>
	<tr>

		<td><?= $i++; ?></td>
		<td><a href="/client?edit=<?= $client['clientID']; ?>"><?= $client['first'] . " " . $client['last'] ?></a></td>
		<td><?= $client['email']; ?></td>
		<td><?= $client['rate']; ?></td>
		<td><?= $client['secondarySort']; ?></td>
		<td><? if ($client['primarySort'] > 0 ) : ?>
			<a href="/view/?clientID=<?= $client['clientID']; ?>"><? endif; ?><?= $client['primarySort']; ?></a></td>

	</tr>
<? endforeach; 

require 'footer.php'; ?>

