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

<center><p>In the last two columns, clients are ordered by their usage for all time, then for your selected month.</p></center>

<table>
<thead>
<tr>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>Name</th>
	<th>Email</th>
	<th class="rate">Rate</th>
	<th colspan="2">Usage</th>
	 

</tr>
</thead>
<? foreach ( $clients as $client ) : static $i = 1;?>
	<tr>

		<td class="vertical-center count-wrap"><input type="hidden" value="<?=$client['clientID'];?>" name="clientID"/><div class="count<?= $client['active'] == 1 ? " active" : ""; ?>"><span><?= $i++; ?></span></div></td>
		<td class="vertical-center edit"><a href="/client?edit=<?= $client['clientID']; ?>">Edit</a></td>

		<td class="name"><?= Client::format_name( $client ) ?> </td>
		<td><?= $client['billing_email']; ?></td>
		<td class="vertical-center">$<?= $client['rate']; ?></td>
		<td class="vertical-center"> <?= $client['secondarySort']; ?></td>
		<td class="vertical-center last"><? if ($client['primarySort'] > 0 ) : ?>
			<a href="/view/?clientID=<?= $client['clientID']; ?>"><? endif; ?><?= $client['primarySort']; ?></a></td>

	</tr>
<? endforeach; 

require 'footer.php'; ?>

