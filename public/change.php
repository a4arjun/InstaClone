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
    <style type="text/css">
      @import url(https://fonts.googleapis.com/icon?family=Material+Icons);
      @import url("https://fonts.googleapis.com/css?family=Raleway");

      .wrapper {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
      }

      .box {
        display: block;
        min-width: 300px;
        height: 300px;
        margin: 10px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
      }

      .box-0 {
        display: block;
        min-width: 300px;
        margin: 10px;
        background-color: white;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
      }

      .upload-options {
        position: relative;
        height: 75px;
        background-color: cadetblue;
        cursor: pointer;
        overflow: hidden;
        text-align: center;
        transition: background-color ease-in-out 150ms;
      }
      .upload-options:hover {
        background-color: #7fb1b3;
      }
      .upload-options input {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
      }
      .upload-options label {
        display: flex;
        align-items: center;
        width: 100%;
        height: 100%;
        font-weight: 400;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        overflow: hidden;
      }
      .upload-options label::after {
        content: "photo_camera";
        font-family: "Material Icons";
        position: absolute;
        font-size: 2.5rem;
        color: #e6e6e6;
        top: calc(50% - 2.5rem);
        left: calc(50% - 1.25rem);
        z-index: 0;
      }
      .upload-options label span {
        display: inline-block;
        width: 50%;
        height: 100%;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        vertical-align: middle;
        text-align: center;
      }
      .upload-options label span:hover i.material-icons {
        color: lightgray;
      }

      .js--image-preview {
        height: 225px;
        width: 100%;
        position: relative;
        overflow: hidden;
        background-image: url("");
        background-color: white;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
      }
      .js--image-preview::after {
        content: "photo_size_select_actual";
        font-family: "Material Icons";
        position: relative;
        font-size: 4.5em;
        color: #e6e6e6;
        top: calc(50% - 3rem);
        left: calc(50% - 2.25rem);
        z-index: 0;
      }
      .js--image-preview.js--no-default::after {
        display: none;
      }
      .js--image-preview:nth-child(2) {
        background-image: url("http://bastianandre.at/giphy.gif");
      }

      i.material-icons {
        transition: color 100ms ease-in-out;
        font-size: 2.25em;
        line-height: 55px;
        color: white;
        display: block;
      }

      .drop {
        display: block;
        position: absolute;
        background: rgba(95, 158, 160, 0.2);
        border-radius: 100%;
        transform: scale(0);
      }

      .animate {
        animation: ripple 0.4s linear;
      }

      @keyframes ripple {
        100% {
          opacity: 0;
          transform: scale(2.5);
        }
      }
    </style>

  </head>
  <body>
    <nav class="navbar navbar-light bg-white" style="border-bottom: 0.5px solid #eee">
      <a class="nav-link">New Post</a>
      <div>
        <a onclick="goBack()" class="btn btn-md" style="border:1px solid #ccc">discard</a>
      </div>
    </nav>
    <br/>

    <?php
      include_once 'upload-class.php';

      $file_name = 'file';
        // ---------- SIMPLE UPLOAD ----------
        $dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : 'tmp');
        $dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);

        if (isset($_FILES['image_field']) and isset($_SESSION['user']) and $_SESSION['user'] != '') 
        {
          // we create an instance of the class, giving as argument the PHP object
          // corresponding to the file field from the form
          // All the uploads are accessible from the PHP object $_FILES
          $handle = new Upload($_FILES['image_field']);

          // then we check if the file has been uploaded properly
          // in its *temporary* location in the server (often, it is /tmp)
          if ($handle->uploaded) {

              // yes, the file is on the server
              // now, we start the upload 'process'. That is, to copy the uploaded file
              // from its temporary location to the wanted location
              // It could be something like $handle->process('/home/www/my_uploads/');
              $handle->image_convert = 'jpg';
              $handle->jpeg_quality = 50;
              $handle->process($dir_dest);

              // we check if everything went OK
              if ($handle->processed) {
                  // everything was fine !
                  echo '<p class="result">';
                  echo '  <b>File uploaded with success</b><br />';
                  $url = 'tmp/'.$handle->file_dst_name;
                  //echo '   (' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
                  echo '</p>';
                  change_dp($db, username(), $url);
                  header('Location: index.php?action=dp_updated');
                  exit;
              } else {
                  // one error occured
                  echo '<p class="result">';
                  echo ' <div class="alert alert-danger"> Error: ' . $handle->error . '</div>';
                  echo '</p>';
              }

              // we delete the temporary files
              $handle-> clean();

          } else {
              // if we're here, the upload file failed for some reasons
              // i.e. the server didn't receive the file
              echo '<p class="result">';
              echo '  <b>File not uploaded on the server</b><br />';
              echo '  <div class="alert alert-danger">Error: ' . $handle->error . '</div>';
              echo '</p>';
          }
        }    
    ?>
    <form name="form1" enctype="multipart/form-data" method="post" action="">
      <div class="wrapper">
        <div class="box">
          <div class="js--image-preview"></div>
          <div class="upload-options">
            <label>
              <input type="file" class="image-upload" accept="image/*" name="image_field" />
            </label>
          </div>
        </div>
      </div>
      <div class="wrapper">
        <div class="box-0">
          <input class="btn btn-block btn-success" type="submit" name="Submit" value="SAVE" />
        </div>
      </div>
    </form>
    <!--form name="form1" enctype="multipart/form-data" method="post" action="" />
      <p><input type="file" size="32" name="image_field" value="" /></p>
      <p class="button"><input type="hidden" name="action" value="simple" />
      <input type="submit" name="Submit" value="upload" /></p>
    </form-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
      function initImageUpload(box) {
        let uploadField = box.querySelector('.image-upload');

        uploadField.addEventListener('change', getFile);

        function getFile(e){
          let file = e.currentTarget.files[0];
          checkType(file);
        }
        
        function previewImage(file){
          let thumb = box.querySelector('.js--image-preview'),
              reader = new FileReader();

          reader.onload = function() {
            thumb.style.backgroundImage = 'url(' + reader.result + ')';
          }
          reader.readAsDataURL(file);
          thumb.className += ' js--no-default';
        }

        function checkType(file){
          let imageType = /image.*/;
          if (!file.type.match(imageType)) {
            throw 'Datei ist kein Bild';
          } else if (!file){
            throw 'Kein Bild gewählt';
          } else {
            previewImage(file);
          }
        }
        
      }

      // initialize box-scope
      var boxes = document.querySelectorAll('.box');

      for (let i = 0; i < boxes.length; i++) {
        let box = boxes[i];
        initDropEffect(box);
        initImageUpload(box);
      }



      /// drop-effect
      function initDropEffect(box){
        let area, drop, areaWidth, areaHeight, maxDistance, dropWidth, dropHeight, x, y;
        
        // get clickable area for drop effect
        area = box.querySelector('.js--image-preview');
        area.addEventListener('click', fireRipple);
        
        function fireRipple(e){
          area = e.currentTarget
          // create drop
          if(!drop){
            drop = document.createElement('span');
            drop.className = 'drop';
            this.appendChild(drop);
          }
          // reset animate class
          drop.className = 'drop';
          
          // calculate dimensions of area (longest side)
          areaWidth = getComputedStyle(this, null).getPropertyValue("width");
          areaHeight = getComputedStyle(this, null).getPropertyValue("height");
          maxDistance = Math.max(parseInt(areaWidth, 10), parseInt(areaHeight, 10));

          // set drop dimensions to fill area
          drop.style.width = maxDistance + 'px';
          drop.style.height = maxDistance + 'px';
          
          // calculate dimensions of drop
          dropWidth = getComputedStyle(this, null).getPropertyValue("width");
          dropHeight = getComputedStyle(this, null).getPropertyValue("height");
          
          // calculate relative coordinates of click
          // logic: click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center
          x = e.pageX - this.offsetLeft - (parseInt(dropWidth, 10)/2);
          y = e.pageY - this.offsetTop - (parseInt(dropHeight, 10)/2) - 30;
          
          // position drop and animate
          drop.style.top = y + 'px';
          drop.style.left = x + 'px';
          drop.className += ' animate';
          e.stopPropagation();
          
        }
      }

      function goBack() {
        window.history.back();
      }
    </script>
  </body>
</html>
<?php } ?>