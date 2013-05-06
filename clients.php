<? 

require 'includes.php'; 
require 'header.php'; 

$clients = Client::showClientList( true );

echo '<h1>Your Clients <a href="/client/">(Add New)</a></h1>';

if (empty($clients) )
{
	echo "<center>You haven't created any clients yet.</center>";
	exit;
}
?>
<p>For each contact listed below, the name of the <strong>company</strong> and the <strong>billing email</strong> is listed, followed by the name and email of the individual associated with that company. Complete the full information for each contact in order to get the most out of this view.</p>

<p>Clients are ordered by their popularity for <?= Time::getPeriod() ?>, then for all time. Toggle the month dropdown to see how your top clients for this month compare with those of other months.</p>

<table>
<thead>
<tr>
	<th>&nbsp;</th>
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

		<td class="vertical-center count-wrap"><input type="hidden" value="<?=$client['clientID'];?>" name="clientID"/><div class="count<?= $client['active'] == 1 ? " active" : ""; ?>"><span><?= $i++; ?></span></div></td>
		<td class="vertical-center edit"><a href="/client?edit=<?= $client['clientID']; ?>">Edit</a></td>

		<td class="name"><?= $client['company_name']; ?><br/><?= $client['first'] . " " . $client['last'] ?> </td>
		<td><?= $client['billing_email']; ?><br/><?= $client['email']; ?></td>
		<td class="vertical-center">$<?= $client['rate']; ?></td>
		<td class="vertical-center"> <?= $client['secondarySort']; ?></td>
		<td class="vertical-center"><? if ($client['primarySort'] > 0 ) : ?>
			<a href="/view/?clientID=<?= $client['clientID']; ?>"><? endif; ?><?= $client['primarySort']; ?></a></td>

	</tr>
<? endforeach; 

require 'footer.php'; ?>

