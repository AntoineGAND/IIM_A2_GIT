<?php session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require('config/config.php');
require('model/functions.fn.php');

/*===============================
	Register
===============================*/

include 'view/_header.php';
include 'view/register.php';
include 'view/_footer.php';




