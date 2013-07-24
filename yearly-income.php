<? 
require 'includes.php'; 
require 'header.php'; 

echo '<h1>Total Income — ' . substr($_SESSION['period'], -4) . '</h1>';
$months = Time::showMonths(0, substr($_SESSION['period'], -4) );
$months = array_reverse($months);

?>
<table class="">
<thead>
	<tr>
	<th>Month</th>
	<th>Monthly Hours</th>
	<th>Total Hours</th>
	<th class="summary">Monthly</th>
	<th class="summary">Cumulative</th>
	</tr>
</thead>
<?

$unpaid = 0;
$paid = 0;
$total_money = 0;
$total_hours = 0;
foreach ($months as $month)
{
$time = 0;
$total_month_money = 0;
$unpaid_monthly = 0;
$paid_monthly = 0;

$stuff = Time::get_month_totals( $month ); 

if (!empty($stuff))
{

foreach ($stuff as $row)
{
	
	$expenses = !empty($row['expenseAmount']) ? $row['expenseAmount'] : 0;
	$money = $expenses + Time::calculateTotal($row['timeAmount'], $row['rate']);
	$paid_status = !empty($row['paid']) ? "paid" : "unpaid";
	if ( $paid_status == "unpaid" )
	{
		$unpaid += $money;
		$unpaid_monthly += $money;
	}
	else if ( $paid_status == "paid" )
	{
		$paid += $money;
		$paid_monthly += $money;
	}
	
	$time = !isset($time) ? $row['timeAmount'] : $time + $row['timeAmount'];
	$total_month_money = !isset($total_month_money) ? $paid_monthly : $total_month_money + $paid_monthly;
}
	$total_money += round($total_month_money, 2);
	$total_hours += round($time, 2);
?>

<tr>
	<td><?= substr($month, 0, -5); ?></td>
	<td><?= Time::roundToHours($time) ?></td>
	<td><?= Time::roundToHours($total_hours); ?></td>
	<td>$<?= number_format($paid_monthly, 2) ?></td>
	<td>$<?= number_format($paid, 2); ?></td>
</tr>
<? } else { ?>
<tr>
	<td><?= substr($month, 0, -5); ?></td>
	<td>...</td>
	<td>...</td>
	<td>...</td>
	<td>...</td>
</tr>
<? } 
}

require 'footer.php'; ?>

