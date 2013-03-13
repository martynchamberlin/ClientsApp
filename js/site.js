var unload = false;

$(document).ready(function() {
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

$(document).ready(function() {
	
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

});


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
//window.location = "/edit?id=" + $('#noteID').value


$(document).ready(function() {
	//$("textarea").tabby();

	/*
	setInterval(timer, 100);
	function timer() {
		var content = $('textarea').val();

		$.ajax({
		  type: "POST",
		  url: "/word_count.php",
			data: {
				'content': content
			},

		  success: function(html){
				$('#word_count')
				.html('')
				.append(html);
			}
		});
	}
	*/

	$('textarea').autosize(); 

	// Make the white background as tall as the window. This is just cool
	var h = $('#wrap').outerHeight();
	var padding = parseInt(jQuery(".inner").css("padding-top"));
	padding += parseInt(jQuery(".inner").css("padding-bottom"));
	padding += parseInt(jQuery(".inner").css("margin-top"));
	padding += parseInt(jQuery(".inner").css("margin-bottom"));
	padding += parseInt(jQuery("#wrap").css("padding-top"));
	padding += parseInt(jQuery("#wrap").css("padding-bottom"));
	padding += parseInt(jQuery("#wrap").css("margin-top"));
	padding += parseInt(jQuery("#wrap").css("margin-bottom"));

	if (h < $(window).height())
	{
		$('.inner').css("min-height", $(window).height() - padding + "px");
	}

	$('#content').css("min-height", $('.inner').innerHeight() - 87);

	$('textarea').css("height", document.getElementById('editTextarea').scrollHeight + "px");
});

/*	$('.add-submit').click(function() {
		var text = $(".contentEditable").html();

		var text = text.replace( /<div><br><\/div>/g, '\n');
		var text = text.replace( /<div>/g, '\n');
		var text = text.replace( "<br>\n", '<br>');

		var text = text.replace( /<\/div>/g, '');
		var text = text.replace( /<p>/g, '');
		var text = text.replace( /<\/p>/g, '<br><br>');
		var text = text.replace( /<br>/g, '\n' );
		var text = text.replace( "<span style=\"background-color: rgb(255, 255, 255); \">", '' );
		var text = text.replace("style=\"background-color: rgb(255, 255, 255); border-width: initial; border-color: initial; \"", '');
		var text = text.replace( /<\/span>/g, '');
		var text = text.replace( /<\/span>/g, '');
		var text = text.replace( /&lt;/g, '<');
		var text = text.replace( /&gt;/g, '>');
		var text = text.replace( /&amp;/g, '&');
		var text = text.replace( /&nbsp;/g, '');



		$("textarea.hidden").val(text);

		var textarea_val = $("textarea.hidden").val();


		alert(textarea_val);
		return true;
		});
*/
});

