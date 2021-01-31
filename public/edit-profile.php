<?php
//include config
require_once('../includes/config.php');
include 'functions.php';


//if not logged in redirect to login page
if(!$user->is_logged_in()){
  header('Location: login.php');
  echo 'No redirect won\'t work anymore. Try something else that you can do';

}else{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    

    <title>Social</title>
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <style type="text/css">
      
    </style>

  </head>
  <body style="margin-bottom:80px">
    <nav class="navbar navbar-light bg-white" style="border-bottom: 0.5px solid #eee">
      <a class="nav-link">Edit profile</a>
      <div>
        <a onclick="goBack()" class="btn btn-md" style="border:1px solid #ccc">discard</a>
      </div>
    </nav>
    <br/>

    <div class="container">
    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

      //collect form data
      extract($_POST);
      $firstname = strip_tags($_POST['firstname']);
      $lastname = strip_tags($_POST['lastname']);
      $password = strip_tags($_POST['password']);
      $passwordConfirm = strip_tags($_POST['passwordConfirm']);
      $email = strip_tags($_POST['email']);
      //very basic validation
      if($firstname ==''){
        $error[] = '<div class="alert alert-danger">Please enter the firstname.</div>';
      }
      if ($lastname == '') {
        $error[] = '<div class="alert alert-danger">Please enter the firstname.</div>';
      }

      if( strlen($password) > 0){

        if($password ==''){
          $error[] = '<div class="alert alert-danger">Please enter the password.</div>';
        }

        if($passwordConfirm ==''){
          $error[] = '<div class="alert alert-danger">Please confirm the password.</div>';
        }

        if($password != $passwordConfirm){
          $error[] = '<div class="alert alert-danger">Passwords do not match.</div>';
        }

      }
      

      if($email ==''){
        $error[] = '<div class="alert alert-danger">Please enter the email address.</div>';
      }

      if(!isset($error)){

        try {

          if(isset($password)){

            $hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

            //update into database
            $stmt = $db->prepare('UPDATE users SET firstname = :firstname, lastname= :lastname, password = :password, email = :email WHERE username = :username') ;
            $stmt->execute(array(
              ':firstname' => $firstname,
              ':lastname' => $lastname,
              ':password' => $hashedpassword,
              ':email' => $email,
              ':username' => $username
            ));


          } else {

            //update database
            $stmt = $db->prepare('UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email WHERE username = :username') ;
            $stmt->execute(array(
              ':firstname' => $firstname,
              ':lastname' => $lastname,
              ':email' => $email,
              ':username' => $username
            ));

          }
          

          //redirect to index page
          header('Location: index.php?action=profile_updated');
          exit;

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

      }

    }

  if(isset($error)){
    foreach($error as $error){
      echo $error.'<br />';
    }
  }


    ?>

      <form action="" method="post">
        <input type="hidden" name="username" value="<?php echo $_SESSION['user']?>">
        <label>Firstname</label>
        <p><input placeholder="Firstname" class="form-control" type="text" name="firstname" value="<?php echo user_firstname($db, $_SESSION['user']); ?>"></p>

        <label>Lastname</label>
        <p><input placeholder="Lastname" class="form-control" type="text" name="lastname" value="<?php echo user_lastname($db, $_SESSION['user']); ?>"></p>

        <label>Email</label>
        <p><input placeholder="Email" type="text" class="form-control" name="email" value="<?php echo user_email($db, $_SESSION['user'])?>"></p>

        <label>Password</label><br />
        <p><input placeholder="New password" class="form-control" type="password" name="password" value=''></p>

        <label>Confirm Password</label>
        <p><input placeholder="Repeat password" class="form-control" type="password" name="passwordConfirm" value=''></p>

        <br/>
        <button name="submit" class="btn btn-md btn-success btn-block">Save</button>
      </form>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
      function goBack() {
        window.history.back();
      }
    </script>
  </body>
</html>
<?php } ?>