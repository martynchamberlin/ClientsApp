<? 
require 'includes.php'; 
require 'header.php'; 

?>

<?= '<h1>' . Time::getPeriod() . '\'s Clients</h1>';

$stuff = Time::showHomePeriod(); 

if (!empty($stuff))
{
	echo '
	<table class="main">
	<thead>
	<tr class="head">
	<th></th>

	<th>Name</th>
	<th>Time</th>
	<th>Money</th>
	<th>Fees</th>
	</tr>
	</thead>';



foreach ($stuff as $row)
{
	static $i = 1;
	$expenses = !empty($row['expenseAmount']) ? $row['expenseAmount'] : 0;
	$money = $expenses + Time::calculateTotal($row['timeAmount'], $row['rate']);
	echo '<tr>';
	echo '<td>' . $i . '</td>';
	$i++;

	echo '<td><a href="/client/?edit=' . $row['clientID'] . '">';
	echo $row['first'] . ' ' . $row['last'] . '</a></td>';

	echo '<td><a href="/view/?clientID=' . $row['clientID'] . '">';
	echo Time::roundToHours($row['timeAmount']) . ' hours</td>';
	echo '<td>$' . number_format($money, 2) . '</td>';

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
} ?>

</table>

<hr/>
<table class="total">
<tr>
	<td><strong>Total time</strong>:</td>
	<td><?= Time::roundToHours($time) ?> hours</td>
</tr>
<tr>
	<td><strong>Total money</strong>:</td>
	<td>$<?= number_format($totalMoney, 2) ?></td>
</tr>
</table>
<? } else { ?>
<p>There are no client records for <?= Time::getPeriod() ?>.</p>
<? } ?>


<? require 'footer.php'; ?>

