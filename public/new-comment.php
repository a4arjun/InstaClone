<?php
//include config
require_once('../includes/config.php');
include 'functions.php';

//if not logged in redirect to login page
if(!$user->is_logged_in()){
  header('Location: login.php');
  echo 'No redirect won\'t work anymore. Try something else that you can do';

}else{

	if (isset($_POST['post']) and $_POST['post'] != '' and isset($_POST['comment']) and $_POST['comment'] != '' and $user->is_logged_in()) {
		new_comment($db, $_POST['post'], username(), $_POST['comment']);
		echo "done";
	}
	else{
		echo "Missing params";
	}

} 

?>