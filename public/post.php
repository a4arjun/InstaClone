<?php
//include config
require_once('../includes/config.php');
include 'functions.php';
include 'upload-class.php';

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

  </head>
  <body>
    <nav class="navbar navbar-light bg-white" style="border-bottom: 0.5px solid #eee">
      <a class="nav-link" href="index.php#profile"><i class="fa fa-arrow-left" aria-hidden="true"></i>
 Back</a>
    </nav>
    <br/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php } ?>