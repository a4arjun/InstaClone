<?php
//include config
require_once('../includes/config.php');

//check if already logged in
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="styles/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">

	<?php

	//process login form if submitted
	if(isset($_POST['submit'])){

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		if($user->login($username,$password)){ 

			$_SESSION['user'] = $username;

			//logged in return to index page
			header('Location: index.php');
			exit;
		

		} else {
			$message = '<p class="error">Wrong username or password</p>';
		}

	}//end if submit

	if(isset($message)){ echo $message; }
	?>
	<h2>Login</h2>
	<form action="" method="post">
	<p><label>Username</label><br/><input class="form-control" size="45%" type="text" name="username" value=""  /></p>
	<p><label>Password</label><br/><input class="form-control" size="45%" type="password" name="password" value=""  /></p>
	<p><label></label><input class="btn" type="submit" name="submit" value="Login"  /></p>
	</form>

	<a href="register.php">Register</a>

</div>
</body>
</html>
