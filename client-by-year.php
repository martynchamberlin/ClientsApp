<? 
require 'includes.php'; 
if (isset( $_GET['month'] ) )
{
	Time::updatePeriod( $_GET['month'] );
	header('location: /view/?clientID=' . $_GET['clientID']);
	exit;
}

require 'header.php'; 

echo '<h1>Client History — ' . substr($_SESSION['period'], -4) . '</h1>';

$clientList = Client::showClientList( true );
echo '<center><select name="clientList">';
if (empty($clientList))
{
	echo '<option></option>';
}
else
{
	echo '<option value="">Select Client</option>';
}
foreach ($clientList as $instance)
{
	echo '<option value="' . $instance['clientID'] . '"';
	echo isset( $_GET['clientID'] ) && $_GET['clientID'] == $instance['clientID'] ? 'selected' : '';
	echo '>';
	echo Client::format_name( $instance );
	echo '</option>';
}
echo '</select></center><br/>';

if ( isset ($_GET['clientID'])  )
{

	$client_id = $_GET['clientID'];
	$client = Client::history( $client_id );
	if ( empty( $client ) )
	{
	echo '<center>No history for this client in this year.</center>';
	}
	else
	{
	echo '
<table class="">
	<thead>
	<tr>
	<th style="width: 40px;"></th>
	<th>Month</th>
	<th>Time</th>
	<th class="summary">Total Time</th>

	<th>Income</th>
	<th>Total Income</th>

	</tr>
</thead>';

	$previous_month = ""; 
	$total_time = 0; 
	$total_expenses = 0;
	$time = 0;
	$income = 0;
	$expenses = 0;
	for ( $i = 0; $i < count($client); $i++ ) 
	{
		static $j = 0;
		$time += $client[$i]['timeAmount'];
		$total_time += $client[$i]['timeAmount'];
		$expenses += $client[$i]['expenseAmount'];
		$total_expenses += $client[$i]['expenseAmount'];
		$next_month_is_new = false;
		$current_month = date('M', $client[$i]['date'] );
		// This next couple of lines could be simplified but I'm very tired
		if ( $i + 1 == count($client) )
		{
			$next_month_is_new = true;
		}
		else
		{
			$next_month = date('M', $client[$i + 1]['date'] );
			if ( $current_month != $next_month ) 
			{
				$next_month_is_new = true;
			}
		}

		if ( $next_month_is_new )
		{
			echo '<tr>';
			echo '<td class="border count-wrap"><a class="circle" href="?clientID=' . $client_id . '&month=' . date('F Y', $client[$i]['date']) .'"><span>' . ++$j . '</span></a></td>';
			echo '<td>';
			echo date('M', $client[$i]['date'] ) . '</td>';
			echo '<td>' . Time::roundToHours( $time ) . ' hours</td>';
			echo '<td>' . Time::roundToHours( $total_time ) . ' hours</td>';
				echo '<td>$' . number_format($expenses + $time / 60 * $client[$i]['rate'], 2);

			echo '<td>$' . number_format($total_expenses + $total_time / 60 * $client[$i]['rate'], 2);
			echo '</tr>';
		}
		if ( $next_month_is_new )
		{
			$time = $income = $expenses = 0;
		}
	}
}
}

require 'footer.php'; ?>

