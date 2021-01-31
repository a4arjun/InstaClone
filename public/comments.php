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

      <?php 

        if (isset($_GET['post']) and $_GET['post'] != '') {
          fetch_post_by_id($db, $_GET['post']);
          echo '

          <div class="card">
            <div class="card-body">
              <input id="postField" type="hidden" name="post" data-value="" value="">
              <textarea id="commentInput" data-value="" class="form-control" name="comment"></textarea>
            </div>
          </div>
          <br/>
          ';

          fetch_comments($db, $_GET['post']);
        }
        else{
          header("Location: index.php?err=no_posts");
        }
       

      ?>
    </div><br/><br/>
<?php } ?>