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
<div class="container-fluid" style="padding-top:30px; background:#fff; min-height:85vh; height:auto;">
      <input id="searchField" type="text" placeholder="Search..." class="form-control" name="q">
      <?php

        if (isset($_GET['q']) and $_GET['q'] != '' and isset($_SESSION['user']) and $_SESSION['user'] != '')
        {
          echo user_search($db, $_SESSION['user'], $_GET['q']);
        }

      ?>
</div>
   
<?php } ?>