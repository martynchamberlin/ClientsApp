<? 

require 'includes.php'; 
require 'header.php'; 

$clientArray = Time::showSinglePeriod($_GET['clientID']);
$client = Client::retrieveClient($_GET['clientID']);
$client;

if (isset($_SESSION['print']) && isset($_POST['print']))
{
	unset($_SESSION['print']);
}
else if (!isset($_SESSION['print']) && isset($_POST['print']))
{
	$_SESSION['print'] = 'print';
}

?>

<h1><?= $client['first'] . ' ' . $client['last'] ?></h1>

<?

$totalTime = 0;
$tasks = array();
$lastdate = "";
foreach ($clientArray as $row)
{
	if (!isset($tasks[$row['taskID']]))
	{
		$tasks[$row['taskID']]['total'] = $row['timeAmount'];
	}
	else
	{
		$tasks[$row['taskID']]['total'] += $row['timeAmount'];
	}
	$tasks[$row['taskID']]['taskID'] = $row['taskID'];

	$tasks[$row['taskID']]['name'] = $row['taskName'];

	//$time = Time::roundToHours(($row['timeAmount']));
	$time = $row['timeAmount'];
	$date = $row['date'];

	$totalTime = !isset($totalTime) ? $row['timeAmount'] : $totalTime + $row['timeAmount'];

	echo '<div class="overflow_hidden">';
	if ($lastdate != $date): 
	echo'

	<div class="calendar"><span class="month">'
	. date('M', $row['date']) . 
	'</span><span class="day">' . date('j', $row['date']) . '</span></div>';
	endif;
	echo '
	<div class="right">' . markdown('<strong> ' . $row['taskName'] . '</strong> — ' . $row['comments']);
	if (!isset($_SESSION['print']))
	{
		echo '<div class="action-buttons"> <a href="/time/?time=' . $row['post_id'] . '&redirect=' . urlencode($_SERVER['REQUEST_URI']) . '">(edit)</a> <a href="/delete/?time=' . $row['post_id'] . '&redirect=' . urlencode($_SERVER['REQUEST_URI']) . '" class="delete">(delete)</a></div>';
	}
	echo '<span class="minutes"> ' . $time . ' minutes</span>';

	echo '</div><!-- end .right --></div><!-- end .overflow_hidden -->';
	$lastdate = $date;
}

if ( !empty($clientArray))
{
echo '<hr/>';

echo '<h2 class="breakdown">Task breakdown</h2>';

echo '<table class="breakdown">';
foreach ($tasks as $task)
{
	echo '<tr><td class="name"><a href="/task/?edit=' . $task['taskID'] . '&redirect=' . urlencode($_SERVER['REQUEST_URI']). '">' . $task['name'] . '</a></td><td class="hour">' . Time::roundToHours($task['total'])  . ' </td><td>hours</td><td> &nbsp;&nbsp;&nbsp;$' . number_format(Time::calculateTotal($task['total'], $client['rate']), 2) . '</td></tr>';
}

echo '</table><hr/>';
}

$expenses = Expense::showPeriod($_GET['clientID']); 
	$expenseTotal = 0;
if (!empty($expenses))
{
	echo '<div class="expenses" id="expenses"><h2>Expenses</h2>';

	foreach ($expenses as $e)
	{
		echo '<div class="overflow_hidden"><div class="left-column"> ' . date('M j', $e['date'])  . ' &bull; ';
		echo '$' . number_format($e['amount'], 2) . '</div> ';
		echo '<div class="comments"> &bull; ' . $e['comments'] . '';
		if (!isset($_SESSION['print']))
		{
			echo ' <a href="/fee/?expense=' . $e['post_id'] . '&redirect=' . urlencode($_SERVER['REQUEST_URI']) . '">(edit)</a> <a href="/delete/?expense=' . $e['post_id'] . '&redirect=' . urlencode($_SERVER['REQUEST_URI']) . '" class="delete">(delete)</a>';
		}
	echo '</div></div><!-- end .overflow_hidden -->';
	$expenseTotal += $e['amount'];

	}
	echo '</div>';
}
?>
<table class="total">

	<?= $totalTime > 0 ? '<tr><td><strong>Total time</strong>:</td>
	<td>' . Time::roundToHours($totalTime) . ' hours</td></tr>': ''; ?>
<tr>
	<td><strong>Total money</strong>:</td>
	<td>$<?= number_format($expenseTotal + Time::calculateTotal($totalTime, $client['rate']), 2); ?></td>
</tr>
</table>

<hr/>

<form action="" method="post">
<input type="submit" name="print" value="Toggle Print"/>
</form>


<? require 'footer.php'; ?>
