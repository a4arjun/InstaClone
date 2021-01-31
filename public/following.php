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
    <div class="alert alert-info">People you are following are shown in this page</div>
      <?php user_following($db, username()); ?>
<?php } ?>