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


	function toggle_task( obj )
	{
		var id = $(obj).val();
		$.ajax({
			type: "GET",
			url: "/time/",
			data: {
				'ajax': '',
				'client_id': id
			},

			success: function(html){
				$('form.ajax select[name="taskID"] option[value="' + html + '"]').attr('selected', 'selected');
					$('select').select2();

			}
		});
	}



	$('form.ajax select[name="clientID"]').change(function()
	{	
		toggle_task( $(this) );
	});

	toggle_task( 'form.ajax.new select[name="clientID"]' );


	if ( $('body').is('.task-by-year') || $('body').is('.client-by-year'))
	{
		$('select[name="taskList"]').change(function()
		{
			var id = $(this).val(); 
			if ( id !== "")
			{
				window.location = '/task-by-year/?taskID=' + id;
			}
		});
		$('select[name="clientList"]').change(function()
		{
			var id = $(this).val(); 
			if ( id !== "")
			{
				window.location = '/client-by-year?clientID=' + id;
			}
		});
		$('.new').first().removeClass('new');
	}

	$('blockquote').each(function()
	{
		$(this).find('*').last().addClass('last');
	});



	function send_paid( elem, action ) {
		var content = $(elem).next(".info");
		var clientID = $(content).find('.clientID').html();
		var month = $(content).find('.month').html();
		// fix for iPads, in which it tries to turn unix timestamp into a telephone number
		month = month.replace(/(<([^>]+)>)/ig,"");
	//alert(clientID + '\n' + month + '\n' + action);	
		$.ajax({
			type: "POST",
			url: "/ajax/",
			data: {
				'clientID': clientID,
				'month': month,
				'action': action,
			},

			success: function(html){
			}
		});
	}

	function update_client_status( elem, action ) {
		var clientID = $(elem).closest('td').find('input[name="clientID"]').val();

		$.ajax({
			type: "POST",
			url: "/ajax/",
			data: {
				'clientID': clientID,
				'status': action,
			},

			success: function(html){
			}
		});
	}

	function update_task_status( elem, action ) {
		var taskID = $(elem).closest('td').find('input[name="taskID"]').val();
		taskID = taskID.replace(/(<([^>]+)>)/ig,"");

		$.ajax({
			type: "POST",
			url: "/ajax/",
			data: {
				'taskID': taskID,
				'status': action,
			},

			success: function(html){
			}
		});
	}


	$('.checkbox.unpaid').live('click', function()
	{
		$(this).addClass('paid').removeClass('unpaid');
		send_paid( $(this), "insert" );
	});
	$('.checkbox.paid').live('click', function()
	{
		$(this).removeClass('paid').addClass('unpaid');
		send_paid( $(this), "delete" );
	});

	$('.clients .count').click(function()
	{
		if ( $(this).is('.active') )
		{
			$(this).removeClass('active');
			update_client_status( $(this), 0 );
		}
		else
		{
			$(this).addClass('active');
			update_client_status( $(this), 1 );
		}
	});

	$('.tasks .count').click(function()
	{
		if ( $(this).is('.active') )
		{
			$(this).removeClass('active');
			update_task_status( $(this), 0 );
		}
		else
		{
			$(this).addClass('active');
			update_task_status( $(this), 1 );
		}
	});


	if ($('body').is('.logged_out') )
	{
		$.backstretch("../images/iStock_000006378858Medium.jpg");
	}

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
		
		var redirect = "";
		if ($('input[name="redirect-login"]').length > 0 )
		{
			redirect = '<input type="hidden" name="redirect-login" value="' + $('input[name="redirect-login"]').val() + '">'
		}

		this.content = '<form action="/login/" class="popup" method="post"><label for="email">Email</labeL><input type="text" class="normal" class="email" name="email"><input type="hidden" name="login" value="login"><label for="password">Password</label>' + redirect + '<input type="password" name="password"/><input type="submit" value="Login"></form>'
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
		$('#wrap').css('margin-top',  '-' + parseInt($('#wrap').outerHeight() + 20 ) + 'px');
		$('#realfancy').trigger('click');
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
		var first = $(this).find('input[name="first"]');
		var last = $(this).find('input[name="last"]');
		var email = $(this).find('input[name="email"]');
		var rate = $(this).find('input[name="rate"]');
		var billing_email = $(this).find('input[name="billing_email"]');

		$(this).find('input').each(function()
		{
			$(this).removeClass('error');
		});

		if ( $(first).val() === "" )
		{
			$(first).addClass('error');
			valid = false;
		}
		if ( $(last).val() === "" )
		{
			$(last).addClass('error');
			valid = false;
		}
		if ( !validateEmail( $(billing_email).val() ) )
		{
			$(billing_email).addClass('error');
			valid = false;
		}
		if ( $(rate).val() === "" || ! isNumeric( $(rate).val() ) )
		{
			$(rate).addClass('error');
			valid = false;
		}

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
		updateTimeAmount();
		var minutes = parseInt($('input[name="timeAmount"]').val()) / 60;
		var minutes = Math.ceil(minutes);
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
		if ( valid === true )
		{
			$('input[name="timeAmount"]').val( minutes );
			$('body #fakewrap').hide( 500 );
		}
		return valid;
	});

	// Validate the Account Settings form
	$('form.account-settings').submit(function()
	{
		var valid = true;
		var password = $('input[name="password"]');
		var firstname = $('input[name="first_name"]');
		var lastname = $('input[name="last_name"]');
		var email = $('input[name="email"]');
	
		$('form.account-settings').removeClass('error');

		if ( $(firstname).val() === "" ) 
		{
			$(firstname).addClass('error');
			valid = false;
		}

		if ( $(lastname).val() === "" ) 
		{
			$(lastname).addClass('error');
			valid = false;
		}

		if ( $(email).val() === "" || ! validateEmail( $(email).val() ) ) 
		{
			$(email).addClass('error');
			valid = false;
		}

		if ( $(password).val().length < 8 &&$(password).val().length > 0 )
		{
			$(password).addClass('error');
			valid = false;
		}

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
		var ts = Math.round((new Date()).getTime() / 1000);
		$('input[name="startTime"]').val( ts );
		if (! timer)
		{
			interval = setInterval(tick, 1000);
		}
		timer = true;
	}

	function stopTimer()
	{
		clearInterval(interval);
		updateTimeAmount();
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

	function updateTimeAmount()
	{
		var startTime = $('input[name="startTime"]').val();
		/** 
		 * When would startTime NOT be greater than zero? When the 
		 * user hits the submit button without ever hitting the Stop
		 * button
		 */
		if ( startTime > 0 )
		{
			var ts = Math.round((new Date()).getTime() / 1000);
			var delta = ts - $('input[name="startTime"]').val();
			$('input[name="timeAmount"]').val( parseInt( $('input[name="timeAmount"]').val() ) + delta );
		}
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
	
	
	$('input[type="submit"][value="Save"], .cancel-time').click(function()
	{
		unload = true;
	});

	$('.cancel-time').click(function()
	{
		window.history.back();
		return false;
	});

	window.onbeforeunload = function() 
	{
		if ($('body').is('.time') && ! unload )
		{
			return "Sure you want to leave?\n\nYour time entry will be permanently lost if you do.";
		}
	};

	key.filter = function(event)
	{
		var tagName = (event.target || event.srcElement).tagName;
		//return jQuery('textarea').is(':focus');
		return true;
	}

	key('⌘+s, ⌘+enter', function()
	{
		unload = true;
		$('.create-time').submit();
		$('.create-fee').submit();
		return false;
	});

	if ( $('body').is('.view') )
	{
		key('ctrl+e', function()
		{
			window.location = $('.right').first().find('.action-buttons a').first().attr('href');
		});
	}

	if ( $('body').is('.home') )
	{
		key('ctrl+v', function()
		{
			window.location = $('a.view-client-time').first().attr('href');
		});
	}

	key('s, space', function()
	{
		if ( ! $('input').is(':focus') && ! $('textarea').is(':focus') )
		{
			$('#startstop').trigger('click');
		}
	});

	key('ctrl+n', function()
	{
		window.location = "/time";
	});

	key('ctrl+w', function()
	{
		window.location = "/";
	});
	
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
	if ( ! $('body').is('.logged_out') )
	{
		document.title = h1;
	}

});





