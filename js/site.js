var unload = false;

jQuery(document).ready(function($) {

	// Give some sanity to the tables
	$('table tr:odd').addClass('grey');
	// Handles people on the dropdown trying to go to either the "new time" or the "new fee" pages ... both of which require a client and in the case of time, also a task
	$('#nav .no-time').click(function()
	{
		alert('You must create a client and a task before you can create a time log.');	
		return false;
	});
	
	$('#nav .no-fee').click(function()
	{
		alert('You must create a client before you can create a fee.');
		return false;
	});

	// Implementing the Superfish jQuery plugin, which gives us our dropdown menus
	$('#nav ul').superfish({
		delay:       100,								// 0.1 second delay on mouseout 
		animation:   {opacity:'show',height:'show'},	// fade-in and slide-down animation 
		dropShadows: false								// disable drop shadows 
	});
	


	// Thanks to Markdown, it's time for some new jQuery magic
	$('.view .right').each(function()
	{
			$(this).find('p').first().css('display', 'inline');
			$(this).find('p:eq(1)').css('padding-top', '20px');
			$(this).find('p').last().css('display', 'inline');
	});

	// Get some defauls in there before you call the select2 function!
	$('input[type=text]').each(function()
	{
		$(this).addClass('normal');
	});

	$('select').each(function()
	{
		$(this).css('width', '200px');
	});

	$('select').select2();

	$('#getPeriod select').change(function()
	{
		$('#getPeriod').submit();
	});

	// Let us tab in a textarea
	$("textarea").tabby();

	// Focus on title when you're on a new deal
	$('.add .input_title, .edit textarea').focus();

	// Get rid of that ugly last border on the sidebar
	$("#sidebar .link").last().addClass('last');

	// Make sure you really want to delete that
	$(".delete").click(function() {
		var yes = window.confirm("Seriously?");
		if (!yes) {
			return false;
		}
	});

	
	var timer = false;
	var interval;

	var min = '';

	var sec = 0;

	updateSec();

	function setMin()
	{
		min = $('#minutes').val();
		if (min === '')
		{
			min = 0;
		}
		else
		{
			min = parseInt(min, 10);
		}
	}

	// This function takes an integer and outputs a string
	function formatSec(sec)
	{
		var str = sec.toString();
		if (str.length <= 1)
		{
			str = "0" + str;
		}
		return str;
	}

	function startTimer() {
		setMin();
		if (! timer)
		{
			interval = setInterval(tick, 1000);
		}
		timer = true;
	}

	function stopTimer()
	{
		clearInterval(interval);
		timer = false;
	}

	// A tick is a second
	function tick()
	{
		sec++;
		if (sec == 60)
		{
			tock();
		}
		updateSec();
	}

	// A tock is a minute
	function tock()
	{	
		setMin();
		min++;
		sec = 0;
		updateMin();
	}

	// These next two functions are responsible for showing the viewer what's going on
	function updateSec()
	{
			secString = formatSec(sec);
			$('#seconds').html(secString);
	}

	function updateMin()
	{
		$('#minutes').val(min);
	}


	$('#startstop').click(function()
	{
		if ($(this).val() == 'Start')
		{
			$(this).val('Stop');
			startTimer();
		}
		else if ($(this).val() == 'Stop')
		{
			$(this).val('Start');
			stopTimer();
		}
		return false;
	});
	

	
	$('input[type="submit"][value="Save"]').click(function()
	{
		unload = true;
	});

	window.onbeforeunload = function() {
		if ($('body').is('.time') && ! unload )
		{
			return "Sure you want to leave?\n\nYour time entry will be permanently lost if you do.";
		}
	};
	$(document).bind('keydown', 'ctrl+s', function(){ 
		unload = true;
		$('form').submit();

	});

	$(document).bind('keydown', 'ctrl+n', function(){ window.location = "/time";});
	$(document).bind('keydown', 'ctrl+w', function(){ window.location = "/";});
	var postID = $('#noteID').val();
	var postURL = $('#noteURL').val();
	$(document).bind('keydown', 'ctrl+e', function(){ window.location = "/edit?id=" + postID; });
	$(document).bind('keydown', 'ctrl+v', function(){ window.location = "/" + postURL; });
	var searchForm = $('.search_form input');
	$(document).bind('keydown', 'ctrl+f', function(){ searchForm.focus(); });

	
	$('textarea').autosize(); 

});





