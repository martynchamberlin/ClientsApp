<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
	<head>
		<title>Title of the Document</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="/reset.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/style.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/select2.css" type="text/css" media="screen" />
		<meta name='robots' content='noindex,nofollow' />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>	
		<script src="/js/jquery.text-expander.js"></script>
		<script src="/js/textarea.js"></script>
		<script src="/js/site.js"></script>
		<script src="/js/jquery.hotkeys-0.7.9.js"></script>
		<script src="/js/select2.js"></script>
		<script type="text/javascript" src="//use.typekit.net/nbv3jkv.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	</head>
	<body class="<? core::echoPageURL(); ?>">
		<div id="fakewrap">
		<div id="wrap">
	<div id="nav">
				<ul>
					<li>
						<a <? if (Core::isPage('')) echo 'class="current" '; ?>href="/">Home</a>
					</li>
					<li>
						<a <? if (Core::isPage('time')) echo 'class="current" '; ?>href="/time/">New Time</a>
					</li>
					<li>
						<a <? if (Core::isPage('task')) echo 'class="current" '; ?>href="/task/">New Task</a>
					</li>
					<li>
						<a <? if (Core::isPage('fee')) echo 'class="current" '; ?>href="/fee/">New Fee</a>
					</li>
					<li>
						<a <? if (Core::isPage('client')) echo 'class="current" '; ?>href="/client/">New Client</a>
					</li>
					<li class="logout">
						<a href="/logout/">Logout</a>
					</li>
				</ul>
			</div><! -- end #nav -->	
				<div id="content">
				<div class="entry-content">
