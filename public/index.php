<?php
//include config
require_once('../includes/config.php');
include 'functions.php';

//if not logged in redirect to login page
if(!$user->is_logged_in()){
  header('Location: login.php');
  echo 'No redirect won\'t work anymore. Try something else that you can do';

}else{

//show message from add / edit page
if(isset($_GET['delpost'])){ 

  $stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID');
  $stmt->execute(array(':postID' => $_GET['delpost']));

  header('Location: index.php?action=deleted');
  exit;
} 
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <style type="text/css">
      #overlay{ 
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
        background: rgba(255,255,255,0.8);
      }
      .o-cont {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;  
      }
      .is-hide{
        display:none;
      }
    </style>
  </head>
  <body>
    <nav id="titlebar" class="navbar navbar-light bg-white" style="border-bottom: 0.5px solid #eee">
      <a id="pageTitle" class="nav-link">Social</a>
    </nav>
    <nav class="navbar fixed-bottom navbar-light bg-white" style="border-top: 0.5px solid #eee">
      <a href="#home" class="nav-link text-primary" id="home"><i class="fa fa-home fa-lg"></i></a>
      <a href="#search" class="nav-link text-dark" id="search"><i class="fa fa-search fa-lg"></i></a>
      <a href="new-post.php" class="nav-link text-dark"><i class="fa fa-plus fa-lg"></i></a>
      <a href="notifications.php" class="nav-link text-dark"><i class="fa fa-bell fa-lg"></i></a>
      <a href="#profile" class="nav-link text-dark" id="profile"><i class="fa fa-user fa-lg"></i></a>
    </nav>
      <div class="wrapper mx-auto" id="content">
      </div>
      <div id="overlay">
        <div class="o-cont">
          <i id="bigLove" class="fa fa-heart fa-4x text-danger is-hide">
        </div>
      </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){

          $("#pageTitle").text("Home")
          load_loader();
          load_posts();

          function load_posts()
          {
            $.ajax({
              url: 'load_posts.php',
              method: 'POST',
              success:function(data)
              {
                $('#content').html(data);
              }
            })
          }

          function load_profile()
          {
            $.ajax({
              url: 'profile.php',
              method: 'POST',
              success:function(data)
              {
                $('#content').html(data);
              }
            })
          }


          function load_search_page()
          {
            $.ajax({
              url: 'search.php',
              method: 'POST',
              success:function(data)
              {
                $('#content').html(data);
              }
            })
          }

          function load_following_page()
          {
            $.ajax({
              url: 'following.php',
              method: 'POST',
              success:function(data)
              {
                $('#content').html(data);
                console.log("following list has been loaded");
              }
            })
          }

          function load_followers_page()
          {
            $.ajax({
              url: 'followers.php',
              method: 'POST',
              success:function(data)
              {
                $('#content').html(data);
                console.log("follower list has been loaded");
              }
            })
          }


          function load_follower_profile(user)
          {

            $.ajax({
                url: 'profile.php',
                method: 'GET',
                data: {user:user},
                success:function(data){
                  console.log(data);
                  $('#content').html(data);
                }
              })

          }

          function load_loader(){
            $('#content').html('');
            $('#content').html('<div class="d-flex justify-content-center" style="display:flex; align-items:center; min-height:50%; min-height:50vh;"><div class="spinner-grow text-danger" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-success" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
          }


          $(document).on('click', '.likeBtn', function(){
            var post = $(this).data('post');
            $.ajax({
              url: 'like.php',
              method: 'POST',
              data: {post:post},
              success:function(data){
                load_posts();
              }
            })
          });

          $(document).on('dblclick', '#postImg', function(){
            console.log('Post image double clicked');
            var post = $(this).data('post');
            $("#overlay").fadeIn(300);
            $("#bigLove").removeClass('is-hide');
            $.ajax({
              url: 'like.php',
              method: 'POST',
              data: {post:post},
              success:function(data){
                $("#overlay").fadeOut(300);
                load_posts();
                $("#bigLove").addClass('is-hide');
              }
            })              
          });

          $(document).on('click', '#profileFollowBtn', function(){
            var post = $(this).data('post');
            var user = $(this).data("user");
            console.log(user);
            $.ajax({
              url: 'follow.php',
              method: 'GET',
              data: {user:user},
              success:function(data){
                console.log(user);
                load_follower_profile(user);
              }
            })
          });


          $(document).on('click', '#followBtnFollower', function(){
            var post = $(this).data('post');
            var user = $(this).data("user");
            console.log(user);
            $.ajax({
              url: 'follow.php',
              method: 'GET',
              data: {user:user},
              success:function(data){
                load_followers_page();
              }
            })
          });


          $(document).on('click', '#followBtnFollowing', function(){
            var post = $(this).data('post');
            var user = $(this).data("user");
            console.log(user);
            $.ajax({
              url: 'follow.php',
              method: 'GET',
              data: {user:user},
              success:function(data){
                load_following_page();
              }
            })
          });


          $(document).on('click', '#profile', function(){
            $(".nav-link").addClass("text-dark");
            $("#profile").removeClass("text-dark");
            $("#profile").addClass("text-primary");
            load_loader();
            $("#pageTitle").text("Profile");
            load_profile();
          });


          $(document).on('click', '#home', function(){
            $(".nav-link").addClass("text-dark");
            $("#home").removeClass("text-dark");
            $("#home").addClass("text-primary");
            load_loader();
            $('#pageTitle').text("Home");
            load_posts();          
          });


          $(document).on('click', '#search', function(){
            $(".nav-link").addClass("text-dark");
            $("#search").removeClass("text-dark");
            $("#search").addClass("text-primary");
            load_loader();
            $("#pageTitle").text("Search");
            load_search_page();          
          });

          $(document).on('click', '#following', function(){
            $(".nav-link").addClass("text-dark");
            $("#profile").removeClass("text-dark");
            $("#profile").addClass("text-primary");
            load_loader();
            $("#pageTitle").text("Following");
            load_followers_page();
          });      

          $(document).on('click', '#followers', function(){
            $(".nav-link").addClass("text-dark");
            $("#profile").removeClass("text-dark");
            $("#profile").addClass("text-primary");
            load_loader();
            $("#pageTitle").text("Followers");
            load_following_page();     
          });

          $(document).on('click', '#user', function(){
            $(".nav-link").addClass("text-dark");
            $("#profile").removeClass("text-dark");
            $("#profile").addClass("text-primary");
            var user = $(this).data("user");
            load_loader();
            $.ajax({
                url: 'profile.php',
                method: 'GET',
                data: {user:user},
                success:function(data){
                  $('#content').html(data);
                  $('#pageTitle').text(user);
                }
              })

          });


          $(document).on('click', '#commentBtn', function(){
            $(".nav-link").addClass("text-dark");
            $("#home").removeClass("text-dark");
            $("#home").addClass("text-primary");
            $('#pageTitle').text("Post comments");
            var post = $(this).data("post");
            load_loader();
            $.ajax({
                url: 'comments.php',
                method: 'GET',
                data: {post:post},
                success:function(data){
                  console.log(post);
                  $('#content').html(data);
                  $('input[name="post"]').val(post);
                }
              })

          });


        $(document).on('keypress','#commentInput', function(e) { 
          if (e.which == 13) {
            console.clear();
            var comment=$("#commentInput").val();
            var post=$("#postField").val();
            console.log(post)
            load_loader();
              $.ajax({
                url: 'new-comment.php',
                method: 'POST',
                data: {comment:comment, post:post},
                success:function(data){
                  console.log(data);
                  $.ajax({
                    url: 'comments.php',
                    method: 'GET',
                    data: {post:post},
                    success:function(data){
                      console.log(post);
                      $('#content').html(data);
                      $('input[name="post"]').val(post);
                    }
              })
                }
              })
            return false;
          }

        });


        $(document).on('keypress','#searchField', function(e) { 
          if (e.which == 13) {
            console.clear();
            var q=$("#searchField").val();
            load_loader();
              $.ajax({
                url: 'search.php',
                method: 'GET',
                data: {q:q},
                success:function(data){
                  console.log(data);
                  $('#content').html(data);
                }
              })
            return false;
          }

        });

      $(document).on("click", '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
      });

      $(document).on("contextmenu", '[data-toggle="lightbox"]', function(e) {
        var post = $(this).data('post');
        var postTitle = $(this).data('title');
        e.preventDefault();
        var targetModal = $(this).data('target');
        $(targetModal).modal("show");
        console.log(post);
      });


      });
    </script>
  </body>
</html>
<?php } ?>