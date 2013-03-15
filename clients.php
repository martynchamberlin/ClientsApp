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
	<th>First name</th>
	<th>Last name</th>
	<th>Email</th>
	<th>Rate</th>
	<th>All time</th>
	<th><?= Time::getPeriod() ?></th>
	<th>&nbsp;</th>
</tr>
</thead>
<? foreach ( $clients as $client ) : ?>
	<tr>
		<td><?= $client['first']; ?></td>
		<td><?= $client['last']; ?></td>
		<td><?= $client['email']; ?></td>
		<td><?= $client['rate']; ?></td>
		<td><?= $client['secondarySort']; ?></td>
		<td><?= $client['primarySort']; ?></td>
		<td><a href="/client?edit=<?= $client['clientID']; ?>">(Edit)</a></td>
	</tr>
<? endforeach; 

require 'footer.php'; ?>

