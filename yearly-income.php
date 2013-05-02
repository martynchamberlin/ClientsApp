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
	<th></th>
	<th>Month</th>
	<th>Hours</th>
	<th class="summary">Revenue</th>
	<th>Total Hours</th>
	<th class="summary">Total Revenue</th>
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
	}
	else if ( $paid_status == "paid" )
	{
		$paid += $money;
	}
	
	$time = !isset($time) ? $row['timeAmount'] : $time + $row['timeAmount'];
	$total_month_money = !isset($total_month_money) ? $money : $total_month_money + $money;
} 	$total_money += round($total_month_money, 2);
	$total_hours += round($time, 2);
?>

<tr>
	<td></td>
	<td><?= substr($month, 0, -5); ?></td>
	<td><?= Time::roundToHours($time) ?></td>
	<td>$<?= number_format($total_month_money, 2) ?></td>
	<td><?= Time::roundToHours($total_hours); ?></td>
	<td>$<?= number_format($total_money, 2); ?></td>
</tr>
<? } else { ?>
<tr>
	<td></td>
	<td><?= substr($month, 0, -5); ?></td>
	<td>...</td>
	<td>...</td>
	<td>...</td>
	<td>...</td>
</tr>
<? } 
}

require 'footer.php'; ?>

