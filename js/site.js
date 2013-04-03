var unload = false;



function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function isNumeric(number)
{
	if (isNaN(number / 1) == false) {
    return true;
	}
	return false;
}


jQuery(document).ready(function($) {
	if ($('body').is('.logged_out.landing') )
	{
		$.backstretch("../images/iStock_000006378858Medium.jpg");
	}

	setTimeout(function()
	{
		$('.account p.good-alert').fadeOut(1000);
	}, 1000);

	// When they want to delete their account
	$('.cancel').click(function()
	{
		$('.delete-section').show(50);
		setTimeout(function()
		{
			$('html,body').animate({ scrollTop: $('body').innerHeight() - window.innerHeight }, 500);
			$('.delete-account input[type="password"]').focus();
		}, 51 );
		return false;
	});

	if(window.location.href.indexOf("delete") > -1) 
	{
		$('.delete-section').show();
	}

	fancy = new Object();
	fancy.open = false;
	function get_fancy()
	{
		return fancy.open;
	}

	function set_fancy( bool )
	{
		fancy.open = bool;
	}

	$('#realfancy').fancybox({
			helpers : {
				overlay : {
				css : {
				//	'background' : 'url(/images/mochaGrunge.png)'
				}
			}
		},
			overlayColor: '#333',
			afterLoad : function() {
			set_fancy( true );

			this.content = '<form action="/login/" class="popup" method="post"><label for="email">Email</labeL><input type="text" class="normal" class="email" name="email"><input type="hidden" name="login" value="login"><label for="password">Password</label><input type="password" name="password"/><input type="submit" value="Login"></form>'
			},
			afterShow : function() {
				$('.popup').find('input').first().focus();
			},
			afterClose : function() {
				set_fancy( false );
				go_down();
			},
		});

	function go_up()
	{
			$('#wrap').animate({
			'marginTop': '-' + $('#wrap').outerHeight() + 'px',
			}, 300);
	}

	function go_down()
	{
			$('#wrap').animate({
			'marginTop': 0,
			}, 300);
	}

	// When they click "log in" they shouldn't have to load a brand new page. instead, just show them this fancybox
	$('.fancybox').click(function()
		{
		go_up();
		setTimeout(function()
		{
				$('#realfancy').trigger('click');
		}, 500);
		return false;
	});

	// Make the Fancybox happen automatically if ?logout=true in URL
	if(window.location.href.indexOf("logout") > -1) 
	{
		$('.fancybox').trigger('click');
	}

	// Validate the stuff when they try logging in
	$('.popup').live('submit', function()
	{
		var valid = true;
		$(this).find('input').each(function()
		{
			$(this).removeClass('error');
			if ( $(this).val() === "" )
			{
				$(this).addClass('error');
				valid = false;
			}
			if ( $(this).is('input[name="email"]') && !validateEmail($(this).val() ) )
			{
				$(this).addClass('error');
				valid = false;
			}

			if ( $(this).is('input[name="password"]') && $(this).val().length < 8 )
			{
				$(this).addClass('error');
				valid = false;
			}


		});
		return valid;

	});

	// Validate the Create free Account form on the home page
	$('form.signup').submit(function()
	{
		var valid = true;
		$(this).find('input').each(function()
		{
			$(this).removeClass('error');
			if ( $(this).val() === "" )
			{
				$(this).addClass('error');
				valid = false;
			}
			if ( $(this).is('input[name="email"]') && !validateEmail($(this).val() ) )
			{
				$(this).addClass('error');
				valid = false;
			}

			if ( $(this).is('input[name="password"]') && $(this).val().length < 8 )
			{
				$(this).addClass('error');
				valid = false;
			}


		});
		return valid;

	});


	// Validate the Create a client form
	$('form.add-client').submit(function()
	{
		var valid = true;
		$(this).find('input').each(function()
		{
			$(this).removeClass('error');
			if ( $(this).val() === "" )
			{
				$(this).addClass('error');
				valid = false;
			}
			if ( $(this).is('input[name="email"]') && !validateEmail($(this).val() ) )
			{
				$(this).addClass('error');
				valid = false;
			}
			var rate = $('input[name="rate"]');
			if (! isNumeric( $(rate).val() ) )
			{
				$(rate).addClass('error');
				valid = false;
			}
		});
		return valid;

	});


	// Validat the Create a Task form
	$('form.create-task').submit(function()
	{
		var valid = true;
		var taskName = $(this).find('input[name="taskName"]');
		var taskRate = $(this).find('input[name="taskRate"]');
		$(taskName).removeClass('error');
		$(taskRate).removeClass('error');
		if ( $(taskName).val() === "" )
		{
			$(taskName).addClass('error');
			valid = false;
		}/*
		if ( $(taskRate).val() !== "" && ! isNumeric( $(taskRate).val() ) )
		{
			$(taskRate).addClass('error');
			valid = false;
		}*/
		$(this).find('.error').first().focus();
		return valid;
	});

	
	// Validate the Create a Fee form
	$('form.create-fee').submit(function()
	{
		var textarea = $(this).find('textarea');
		var amount = $(this).find('input[name="amount"]');

		$(textarea).removeClass('error');
		$(amount).removeClass('error');

		var valid = true;
		
		if ( $(amount).val() === "" || ! isNumeric( $(amount).val() ) )
		{
				$(amount).addClass('error');
				valid = false;
		}
		if ($(textarea).val() === "" )
		{
			valid = false;
			$(textarea).addClass('error');
		}

		return valid;
	});

	// Validate the Create a Time form
	$('form.create-time').submit(function()
	{
		$(this).find('textarea').removeClass('error');
		var valid = true;
		var timeAmount = $(this).find('input[name="timeAmount"]');
		$(timeAmount).removeClass('error');
		if ($(timeAmount).val() === "" || ! isNumeric ( $(timeAmount).val() ) )
		{
				$(timeAmount).addClass('error');
				valid = false;
		}
		if ($(this).find('textarea').val() === "" )
		{	
			valid = false;
			$(this).find('textarea').addClass('error');
		}
		if (! valid )
		{
			unload = false;
		}
		return valid;
	});

	// Validate the Account Settings form
	$('form.account-settings').submit(function()
	{
		var valid = true;
		$(this).find('input').each(function()
		{
			$(this).removeClass('error');
			if ( $(this).val() === "" && ! $(this).is('input[type="password"]'))
			{
				$(this).addClass('error');
				valid = false;
			}

			if ( $(this).is('input[name="email"]') && !validateEmail($(this).val() ) )
			{
				$(this).addClass('error');
				valid = false;
			}

			if ( $(this).is('input[name="password"]') && $(this).val().length < 8 &&$(this).val().length > 0 )
			{
				$(this).addClass('error');
				valid = false;
			}

		});

		return valid;
	});

	// Process the delete account form 
	$('form.delete-account').submit(function()
	{
		var password = $(this).find('input[name="password"]');
		if ( $(password).val().length < 8 )
		{
			$(password).addClass('error');
			return false;		
		}
	});

	//$(".confs").fancybox();
	// Give some sanity to the tables
	$('table').each(function()
	{
		$(this).find('tr:even').addClass('grey');
	});
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
	/*$('.view .right').each(function()
	{
			$(this).find('p').first().css('display', 'inline');
			$(this).find('p:eq(1)').css('padding-top', '20px');
			$(this).find('p').last().css('display', 'inline');
	});*/

	// Get some defauls in there before you call the select2 function!
	$('input[type=text]').each(function()
	{
		$(this).addClass('normal');
	});

	$('select').each(function()
	{
		$(this).css('width', '200px');
		if ($(this).is('#nav select'))
		{
			$(this).css('width', '140px');
		}
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

	$('#nav li.client select').change(function()
	{
		if ($(this).val() !== "")
		{
			window.location = "/view/?clientID=" + $(this).val();
		}
	});

	function is_page( page )
	{
		page = "." + page;
		if ( $('body').is( page ) )
		{
			return true;
		}
		return false;
	}

	var h1 = $(this).find('h1').html();
	var h1 = h1.replace(/(<([^>]+)>)/ig,"");
	if ( ! is_page('landing') )
	{
		document.title = h1;
	}

});





