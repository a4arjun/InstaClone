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

  <div class="container-fluid" style="background: #fff; padding-top:30px;">
    <div class="row">
      <div class="col text-center">

        <?php
          
          if (isset($_GET['user']) and $_GET['user'] != '' and $_GET['user'] != username()) {
            echo '<img src="'.user_avatar($db, $_GET['user']).'" class="img-responsive rounded-circle profile-pic" width="160px"><br/><br/>';
          }else{
            echo '<a href="change.php"><img src="'.user_avatar($db, username()).'" class="img-responsive rounded-circle profile-pic" width="160px"></a><br/><br/>';
          }

        ?>
 
        <h3 class="text-dark"><?php if (isset($_GET['user']) and $_GET['user'] != ''){ echo user_details_name($db, $_GET['user']); } else{ echo user_details_name($db, username()); }?></h3>
        <p class="text-dark"><?php if (isset($_GET['user']) and $_GET['user'] != ''){echo '<i>@'.$_GET['user'].'</i>';} else { echo '<i>@'.username().' </i><a href="edit-profile.php"><i class="fa fa-edit"></i></a>'; } ?></p>
        
        <p>
            <?php if (isset($_GET['user']) and $_GET['user'] != '' and $_GET['user'] != username()){

                 if(is_following($db, username(), $_GET['user'])){
                    echo '<a id="profileFollowBtn" data-user="'.$_GET['user'].'" class="btn btn-sm btn-danger text-white">UNFOLLOW</a>';
                 }
                 else{
                    echo '<a id="profileFollowBtn" data-user="'.$_GET['user'].'" class="btn btn-sm btn-success text-white">FOLLOW</a>';
                 }

            }?>
        </p>
        <div class="row">
          <div class="col">
            <?php

              if (isset($_GET['user']) and $_GET['user'] != '' and $_GET['user'] != username()) {
                echo '<b>'.user_posts_count($db, $_GET['user']).'</b> Posts';
              }else{
                echo '<b>'.user_posts_count($db, username()).'</b> Posts';
              }

            ?>            
          </div>
          <div class="col">
            <?php 
            if(isset($_GET['user']) and $_GET['user'] != '') {
                echo '
                <b>'.user_following_count($db, $_GET['user']).'</b> Followers
                ';} 
            else{
                echo '<a class="text-dark" id="followers"><b>'.user_following_count($db, username()).'</b> Followers</a>';
            }?>
          </div>
          <div class="col">
            <?php 
            if(isset($_GET['user']) and $_GET['user'] != '') {
                echo '
                <b>'.user_follower_count($db, $_GET['user']).'</b> Following
                ';} 
            else{
                echo '<a class="text-dark" id="following"><b>'.user_follower_count($db, username()).'</b> Following</a>';
            }?>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="tz-gallery">
        <div class="row">
            <?php
            if(isset($_GET['user']) and $_GET['user'] != '') {
                if (is_following($db, username(), $_GET['user'])) {
                  fetch_user_posts_protected($db, $_GET['user']);
                }
                else{
                  echo '<div class="col-md-12"><div class="alert alert-danger">Follow '.user_details_name($db, $_GET['user']).' to see his/her posts </div></div>';
                }
               } 
            else{
                fetch_user_posts_all($db, username());
            }
            ?>
        </div>

    </div>
  </div>


<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
 
      <div class="modal-body">
        <ul class="list-group list-group-flush" style="border:0px solid #fff;">
          <li class="list-group-item">Share..</li>
          <li class="list-group-item">Use as Profile Picture</li>
          <?php if (!isset($_GET['user'])) {
            echo '<li class="list-group-item text-danger">Delete</li>';
          }?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php } ?>