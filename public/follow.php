<?php
require_once('../includes/config.php');
include 'functions.php';

if (isset($_GET['user']) and $_GET['user'] != '' and isset($_SESSION['user']) and $_SESSION['user'] != '') {
	follow_user($db, username(), $_GET['user']);
	echo username().'---'.$_GET['user'];
}else{
	echo "data err";
}

?>