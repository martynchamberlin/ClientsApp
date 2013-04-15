<? 
require 'includes.php'; 
require 'header.php'; 

?>

<?= '<h1>' . Time::getPeriod() . '’s Clients</h1>';

$stuff = Time::showHomePeriod(); 

if (!empty($stuff))
{
	echo '
	<table class="main">
	<thead>
	<tr class="head">
	<th>&nbsp;</th>
	<th>Name</th>
	<th>Time</th>
	<th>Money</th>
	<th>Fees</th>
	</tr>
	</thead>';

$unpaid = 0;
$paid = 0;

foreach ($stuff as $row)
{
	static $i = 1;
	$expenses = !empty($row['expenseAmount']) ? $row['expenseAmount'] : 0;
	$money = $expenses + Time::calculateTotal($row['timeAmount'], $row['rate']);
	echo '<tr>';
	$paid_status = !empty($row['paid']) ? "paid" : "unpaid";
	if ( $paid_status == "unpaid" )
	{
		$unpaid += $money;
	}
	else if ( $paid_status == "paid" )
	{
		$paid += $money;
	}
	echo '<td class="checkboxes"><div class="checkbox ' . $paid_status . '"><span> ' . $i . '</span></div>
		<div class="info" style="display:none">
			<div class="clientID">' . $row['clientID'] . '</div>
			<div class="month">' . strtotime( Time::getPeriod( 'F o') ) . '</div>
		
	</td>';

	echo '<td><a href="/client/?edit=' . $row['clientID'] . '">';
	$link = "/client/?edit=" . $row['clientID'];
	echo Client::format_name( $row, $link ) . '</a></td>';

	echo '<td><a href="/view/?clientID=' . $row['clientID'] . '">';
	echo Time::roundToHours($row['timeAmount']) . ' hours</td>';
	echo '<td>$' . number_format($row['orderBY'], 2) . '</td>';

	echo '<td>';

	if ($expenses == 0)
	{
		echo '<span class="light">$0.00</span>';
	}
	else
	{
		echo '<a href="/view/?clientID=' . $row['clientID'] . '#expenses">$' . number_format($expenses, 2) . '</a>';
	}
	echo '</td>';
	echo '</tr>';

	$time = !isset($time) ? $row['timeAmount'] : $time + $row['timeAmount'];
	$totalMoney = !isset($totalMoney) ? $money : $totalMoney + $money;
		$i++;

} ?>

</table>

<br />
<table class="total">
<thead>
	<tr>
	<th>Total</th>
	<th class="summary">Amount</th>
	</tr>
</thead>
<tr>
	<td class="first">Hours</td>
	<td><?= Time::roundToHours($time) ?></td>
</tr>
<? if ( $paid > 0 ) : ?>
<tr>
	<td class="first">Paid invoices</td>
	<td>$<?= number_format($paid, 2); ?></td>
</tr>
<tr>
	<td class="first">Unpaid invoices</td>
	<td>$<?= number_format($unpaid, 2); ?></td>
</tr>
<? endif; ?>
<tr>
	<td class="first">Revenue</td>
	<td>$<?= number_format($totalMoney, 2) ?></td>
</tr>
</table>
<? } else { ?>
<p>There are no client records for <?= Time::getPeriod() ?>.</p>
<? } ?>


<? require 'footer.php'; ?>

