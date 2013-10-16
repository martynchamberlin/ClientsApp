<? 
require 'includes.php'; 
require 'header.php'; 

// If they're not logged in and trying to access a sensitive page
if ( ! User::logged_in() )
{
	if ( ! Core::is_page( 'login' ) && !Core::is_page( 'landing' ) && ! Core::is_page( 'signup' ) && ! Core::is_home() )
	{
		header( 'location: /?logout&redirect=' . $_SERVER['REQUEST_URI'] );
		exit;
	}
	else if ( Core::is_home() )
	{ 
	?>
	<div class="landing">
<h1>Over 75% of Small Businesses Are Run by Solo Entrepreneurs<sup>1</sup></h1>
<h2>It’s time to get smart about tracking your time.</h2>

<div class="both">
<div class="left">
<p>ClientsApp is a web-based time tracking tool that allows you to keep track of the <em>what</em> and the <em>when</em> of your freelance projects. It’s built by a solo entrepreneur who grew frustrated with the bloated, antequated alternatives out there. Here’s some features that’ll make you use this app on a daily basis:</p>
<ul>
	<li>Create new clients, new times, new tasks, and new fees</li>
	<li>Keep track of your total money and hours each month</li>
	<li>Sort by your most popular clients and tasks</li>
	<li>View monthly and annual logs at a glance</li>
</ul>

<p>Creating a free account takes just ten seconds.</p>

</div>

<div class="right">
<h3>Sign up!</h3>
<form action="/signup/" method="post" class="signup">
<input type="text" autocomplete="off" name="first_name" placeholder="first name">
<input type="text" autocomplete="off" name="last_name" placeholder="last name">
<input type="text" autocomplete="off" name="email" placeholder="email address">

<input type="password" autocomplete="off" name="password" placeholder="new password">

<input type="submit" autocomplete="off" value="Create free account">
</form>

</div>
</div>

<div class="citation"><sup>1</sup>2012 statistic from the <a href="http://www.sba.gov/sites/default/files/FAQ_Sept_2012.pdf" target="_blank">U.S. Small Business Administration</a></div>
</div>
	<?php
	}
}
else // User::logged_in()
{
if ( isset( $_POST['set_timezone'] ) && ! empty( $_POST['timezone'] ) )
{
	$data = $_SESSION['loggedIn'];
	$data['timezone'] = $_POST['timezone'];
	$data['password'] = "";
	User::update( $data );
}


$timezone = get_user_meta( 'timezone' );
if ( empty( $timezone ) )
{

	echo '<h1>What time zone are you in?</h1>

	<p>Hi ' . get_user_meta( 'first_name' ) . ',</p>
	
	<p>We\'ve been making incremental improvements to ClientsApp and one of the most important ways you can help is by telling us what time zone you\'re in. This will apply to all time-sensitive data you enter into this app, ensuring you have a customized yet consistent experience. Just select your location from the dropdown below and click save.</p>

	<p>If you\'re traveling abroad, you can always update this setting from your Settings page. Just remember to change it back when you return!</p>
	
';
	
	$time_zones = Time::timezones();
	echo '<form action="" class="setting_timezone" method="post">
	<input type="hidden" name="set_timezone">
	<select name="timezone">
	<option value="">-- select --</option>';
	foreach ( $time_zones as $key=>$val )
	{
		echo '<option value="' . $key . "\">" . $val . "</option>";
	}
	echo '</select>
	<input type="submit" value="Save">
	</form>
	
		<div class="hint light"><p>Having difficulty locating your time zone? If you live in the United States, you want one of the following.<br/> Just paste the value into the dropdown and you\'ll pull it right up.</p>
	<ul><li><strong>PST</strong>: America/Phoenix</li>
	<li><strong>MST</strong>: America/Denver</li>
	<li><strong>CST</strong>: America/Chicago</li>
	<li><strong>EST</strong>: America/Detroit</li>
	</ul></div>';
}
else if ( empty( $clients ) && empty( $tasks ) )
{
	echo '<h1>Welcome!</h1>

	<p>Looks like you\'re new around here, ' . $_SESSION['loggedIn']['first_name'] . '. Let me just give you the quick on how this thing works.</p>
	
	<ol><li>First, you\'ll want to create some clients and some suitable tasks to perform on those clients.</li>
	<li>Second, you\'ll want to get some good use out of those clients and tasks by creating time entries. This is your way of saying, "on this day, I worked for this client on this particular task." You even get to specify how your time was spent.</li>
	<li>If you don\'t like working by the clock, you can create one-time fees as well.</li>
	<li>There are several different ways you can slice and dice this data you\'re creating. In fact, the longer you use this application, the more valuable it becomes to you. You\'ll notice there are suitable views for your annual income \'n\' such.</li>
	<li>Most tables in this app have a circled number on each row. Depending on the view, these do different things. My best advice is just for you to play around with them and see what they do.</li>
	</ol>
	
	<p>Sounds good? Let\'s get started!</p>';
}
else
{

echo '<h1>' . Time::getPeriod('F') . '’s Clients</h1>';

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
			<div class="month">' . strtotime( Time::getPeriod() ) . '</div>
		
	</td>';

	echo '<td><a href="/client/?edit=' . $row['clientID'] . '">';
	$link = "/client/?edit=" . $row['clientID'];
	echo Client::format_name( $row, $link ) . '</a></td>';

	echo '<td><a class="view-client-time" href="/view/?clientID=' . $row['clientID'] . '">';
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
<center><p>There are no client records for <?= Time::getPeriod() ?>.</p></center>
<? } 
}

}
require 'footer.php'; ?>

