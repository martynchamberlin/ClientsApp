<? 

require 'includes.php'; 
require 'header.php'; 

?>

<h1>78.3% of US businesses are run by solo entrepreneurs.</h1>
<h2>It's time to get smart about tracking your time.</h2>
<div class="left">
<p>Clients <strong>App</strong> is a web-based time tracking tool that allows you to keep track of the <em>what</em> and the <em>when</em> of your freelance projects. It's built by a solo entrepreneur who grew frustrated with the bloated, antequated alternatives out there. Here's some featurs that'll make you use this app on a daily basis:</p>
<ul>
	<li>Create new clients, new times, new tasks, and new fees</li>
	<li>Keep track of your total money and hours each month</li>
	<li>Live javascript timer ticks every second you work</li>
	<li>Sort by your most popular clients</li>
</ul>

<p>Signing up just takes thirty seconds »</p>

</div>

<div class="right">
<form action="/signup/" method="post">
<input type="text" autocomplete="off" name="first_name" placeholder="first name">
<input type="text" autocomplete="off" name="last_name" placeholder="last name">
<input type="text" autocomplete="off" name="email" placeholder="email address">

<input type="password" autocomplete="off" name="password" placeholder="new password">

<input type="submit" autocomplete="off" value="Create free account">
</form>

</div>

<? require 'footer.php'; ?>

