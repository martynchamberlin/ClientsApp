<? 

require 'includes.php'; 
require 'header.php'; 

?>


<h1>Log In</h1>

<form action="" method="post">
<input type="hidden" name="login">
<label for="email">Email</labeL>
<input type="text" name="email">

<label for="password">Password</label>
<input type="password" name="password"/>



<input type="submit" value="Login">

<? require 'footer.php'; ?>

