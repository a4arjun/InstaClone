<?php
require_once('../includes/config.php');
include 'functions.php';

if (isset($_POST['post']) and $_POST['post'] != '') {
	like_post($db, username(), $_POST['post']);
  echo "done";
  header('Location: index.php');
  exit;
}
  header('Location: index.php');
  exit;

?>