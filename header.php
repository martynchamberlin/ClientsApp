<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
	<head>
		<title>Time Tracking Software for Solo Entrepreneurs</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />

<script type='text/javascript' src='/js/jquery.js'></script>
<!-- Add jQuery library -->

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script src="/select2-3.3.2/select2.js"></script>
<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<link rel="stylesheet" href="/select2-3.3.2/select2.css" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

		<script src="/js/jquery.text-expander.js"></script>
		<script src="/js/textarea.js"></script>
		<script type='text/javascript' src='/js/superfish.js'></script>
		<script src="/js/backstretch.js"></script>
		<script src="/js/site.js"></script>
		<script src="/js/keymaster.js"></script>
		<script type="text/javascript" src="//use.typekit.net/nbv3jkv.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

	<link rel="shortcut icon" href="/images/favicon.ico?v=005" />	

	</head>
	<body class="<?= core::get_page_url() . " " . User::get_color_scheme() . " "; echo User::logged_in() ? "logged_in" : "logged_out"; ?>">
		<div id="fakewrap">
		<div id="wrap">
	<div id="nav">
		<? if ( User::logged_in() === true ) : ?>
				<ul>
					<li class="first">
						<a id="home" <? if (Core::isPage('')) echo 'class="current" '; ?>href="/">Clients <strong>App</strong></a>
					</li>
					<li>
						<a href="#">+ New</a>
						<ul>
							<? $clients = Client::showClientList(); $tasks = Task::showTaskList(); ?>
							<li ><a href="/<?= ! empty($clients) && !empty($tasks) ? 'time/">' : '#time" class="no-time dead">' ?>Time Log</a></li>
							<li><a href="/client/">Client</a></li>

							<li><a href="/task/">Frequent Task</a></li>
							<li><a href="/<?= ! empty($clients) ? 'fee/">' : '#fee" class="no-fee dead">' ?>One-time Fee</a></li>
						</ul>

					</li>


						<li>
						<a href="#">View...</a>
						<ul>
								<li>
						<a <? if (Core::isPage('clients')) echo 'class="current" '; ?>href="/clients/">Clients</a>
					</li>
				
					<li>
						<a <? if (Core::isPage('tasks')) echo 'class="current" '; ?>href="/tasks/">Tasks</a>
					</li>		
					<li>
						<a <? if (Core::isPage('statistics')) echo 'class="current" '; ?>href="<?= ! empty($tasks) ? "/yearly-income/\"" : "#\" class=\"dead\"" ?>>Annual Income</a>
					<a <? if (Core::isPage('statistics')) echo 'class="current" '; ?>href="<?= ! empty($tasks) ? "/task-by-year/\"" : "#\" class=\"dead\"" ?>>Annual Task History</a>
					<a <? if (Core::isPage('statistics')) echo 'class="current" '; ?>href="<?= ! empty($clients) ? "/client-by-year/\"" : "#\" class=\"dead\"" ?>>Annual Client History</a>
					</li>		
						</ul>

					</li>

					<li class="account-settings">
						<a <? if (Core::isPage('account')) echo 'class="current" '; ?> href="/account/">Account</a>
						<ul>
					<li class="logout">
						<a href="/logout/">Logout</a>
					</li>

				</ul>
			</li>

	<li class="period">
									<form action="/landing/" method="POST" id="getPeriod">
									<input type="hidden" name="in_header" />
							<select name="monthSelect" style="width: 150px">
							<? $months = Time::showMonths(12);
							for ($i = 0; $i < count($months); $i++)
							{
							echo '<option ';
							echo ($months[$i] == Time::getPeriod('F Y')) ? 'selected="selected" ' : '';
							echo 'value="' . $months[$i] . '">' . $months[$i] . '</option>';
						}
						?>
						</select>
						</form>
					</li>


	<li class="client">
	<form>
	<select name="clientID" tabindex="501">
<? $clientList = Client::showClientList(); 

echo '<option value="">Select Client</option>';

foreach ($clientList as $instance)
{

	echo '<option value="' . $instance['clientID'] . '"';
	echo Core::is_page('view') && isset($_GET['clientID']) && $_GET['clientID'] == $instance['clientID'] ? ' selected="selected"' : '';
	echo '>';
	echo Client::format_name( $instance );
	echo '</option>';

}
?>
</select>

</form>



				</ul>
		<? else: ?>
<a style="display:none" id="realfancy" href="#"></a>
	<ul>
					<li>
						<a id="home" <? if (Core::isPage('')) echo 'class="current" '; ?>href="/">Clients <strong>App</strong></a>
					<li class="login">
						<a href="/login/" class="fancybox">Login</a>
					</li>
				</ul>
		<? endif; ?>
			</div><! -- end #nav -->	
				<div id="content">
				<div class="entry-content">
