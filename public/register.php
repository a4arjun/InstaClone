<?php error_reporting(0);?>
<?php //include config
require_once('../includes/config.php');
if( $user->is_logged_in() ){ header('Location: index.php'); } ?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="styles/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>
  <body>
    <div class="container">

	<?php


	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);
		$username = strip_tags($_POST['username']);
		$firstname = strip_tags($_POST['firstname']);
		$lastname = strip_tags($_POST['lastname']);
		$password = strip_tags($_POST['password']);
		$passwordConfirm = strip_tags($_POST['passwordConfirm']);
		$email = strip_tags($_POST['email']);
		if($username ==''){
			$error[] = 'Please enter the username.';
		}

		if ($firstname == '') {
			$error[] = 'Please enter the username.';
		}

		if ($lastname == '') {
			$error[] = 'Please enter the username.';
		}		

		if($password ==''){
			$error[] = 'Please enter the password.';
		}

		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}

		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}

		if($email ==''){
			$error[] = 'Please enter the email address.';
		}

		if(!isset($error)){

			$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

			try{
				$stmt = $db->query('SELECT * FROM users WHERE username ="'.$username.'" ');
		        $count = $stmt->rowCount();

		        if($count < 1){
		        	//insert into database
							$stmt = $db->prepare('INSERT INTO users (username,password,email, user_level, firstname, lastname) VALUES (:username, :password, :email, :user_level, :firstname, :lastname)') ;
							$stmt->execute(array(
								':username' => $username,
								':password' => $hashedpassword,
								':email' => $email,
								':firstname' => $firstname,
								':lastname' => $lastname,
								':user_level' => 2
							));

							//redirect to index page
							header('Location: login.php?action=success');
							exit;
				    }
		        else{
		        	echo "user already exists";
		        }

			}catch(PDOException $e) 
			{
				echo $e->getMessage();
			}
		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>
	<h2>Login</h2>

	<form action='' method='post'>

		<p><label>First Name</label><br />
		<input class="form-control" type='text' name='firstname' value='<?php if(isset($error)){ echo $_POST['firstname'];}?>'></p>

		<p><label>Last Name</label><br />
		<input class="form-control" type='text' name='lastname' value='<?php if(isset($error)){ echo $_POST['lastname'];}?>'></p>
		
		<p><label>Username</label><br />
		<input class="form-control" type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

		<p><label>Password</label><br />
		<input class="form-control" type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

		<p><label>Confirm Password</label><br />
		<input class="form-control" type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

		<p><label>Email</label><br />
		<input class="form-control" type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>
		
		<p><input class="btn btn-primary" type='submit' name='submit' value='Register'></p>

	</form>

</div>