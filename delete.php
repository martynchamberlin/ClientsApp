<? require 'includes.php'; 

if(isset($_GET['time']))
	Time::deleteTime($_GET['time'], urldecode($_GET['redirect']));

if(isset($_GET['expense']))
	Expense::deleteExpense($_GET['expense'], urldecode($_GET['redirect']));
