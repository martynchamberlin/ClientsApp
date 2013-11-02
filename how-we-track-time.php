<? require 'includes.php'; ?>
<? require 'header.php'; ?>


<div id="special-entry-content">

<h1>How Clients App Tracks Your Time</h1>


<p><strong>Time is the most important resource you have.</strong> Clients App keeps precise track of your time. But not only does the software work properly, it looks terrific, bringing the kind of addictive user experience that will keep you coming back again and again. You can watch the seconds as they tick by (a feature no other web-based time tracking software does, that I've seen). This article explains the nitty gritty of how Clients App works, so you can rest at ease that your time is being treated right.</p>

<p>When this software began its development in September 2012, I used the most logical solution available to a novice front-end developer: Javascript intervals. In essence, that looked like this:</p>

<pre>setInterval(function()
{
  update_clock();
}, 1000);
</pre>

<p>I understand that certain Javascript purists believe that <code>setInterval()</code> should never be used, and that <code>setTimeout()</code> should be used recursively instead:</p>

<pre>setTimeout('tick', 1000);

function tick()
{
  update_clock();
  setTimeout('tick', 1000);
}
</pre>

<p>I don't want to enter the arguments for and against these two functions, because they have two things in common: they do a good job of executing the same code over and over again, and they are unreliable at executing that code over even time segments. John Resig makes a good argument that <a href="http://ejohn.org/blog/how-javascript-timers-work/"><code>setTimeout()</code> is more unreliable than <code>setInterval()</code></a>, but the important takeaway here is that they're both unreliable.</p> 

<p>During time logs that spanned multiple hours, depending on the browser and the amount of peripheral activities, I noticed that my Javascript intervals were unreliable. In some cases, I found them to be significantly unreliable, twenty or so minutes away from their proper values. If you bill hourly, this is unacceptable. 

<h2>The Solution</h2>

<p>This flow seemed to be the best alternative:</p>

<ol>	
	<li>User clicks the start button, and a hidden input stores the Unix Timestamp of when that event occurred.</li>
	<li>User clicks the stop button or save button. Javascript compares the timestamp of this event, subtracts from it the timestamp of step 1, and stores this value in another hidden input.</li>
	<li>If the user clicks start again, the program goes back to step 1.</li> 
</ol>
	
<p>In essence, this made the original GUI clock a big dummy that didn't do anything other than make you smile. It was roughly on par with the fake thermostats that give corporate employees the illusion of comfort. The sweet side was that assuming <code>setInterval()</code> functioned as expected (not an unreasonable assumption in most scenarios) the actual time value you'd see in your saved entry would be the same as what you saw in the GUI clock.</p>

<h2>Application Enhancement</h2>

<p>Using timestamps to authoritatively track time within a second while still enjoying the GUI clock made for a much improved user experience, but there was still a complication: what if you wanted to edit the time entry directly yourself? Just because oDesk doesn't let you do that in its Desktop application doesn't mean Clients App shouldn't.</p>  

<p>Adding this feature added a layer of sophistication and potential confusion to the code base, but it was worth it. When you manually update the clock, Clients App will grab your inputted value from the dummy clock and stow it away in its hidden inputs, quietly resuming its timestamp algorithm for computing future values.</p>

<h2>Closing thoughts</h2>

<p>Javascript timeouts and intervals are unreliable because the language is single threaded. Until we have a way to guarantee that intervals will enqueue and callback at the precise time we state, they cannot be used to track time reliably.</p>

<p>I discovered all of this through trial and error. In fact, I did zero research about this until I sat down to write this article. If you're a developer, here's a key takeaway: before embarking on anything that suggests complexity, do some research. You'll save yourself hours of frustration and 12 months of experimentation. Not that experimentation is a bad thing, but it will let you focus on the things that are more pertinent to your specific problems.</p>

<p>So there you have it. You're in good hands. We're keeping perfect track of your time, while providing you with a perfect GUI and the ability to manually make changes should you see fit.</p>

<p>Martyn Chamberlin<br/>
Founder, Perfection Coding</p>
</div>

<? require 'footer.php'; ?>


