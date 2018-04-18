<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors','true');

require('config/config.php');
require('model/functions.fn.php');

/*===============================
	Dashboard
===============================*/

if(!isset($_SESSION) OR empty($_SESSION)){
    header('Location: login.php');
    exit();
}

$musics = listMusics($db);

include 'view/_header.php';
include 'view/music.php';
include 'view/_footer.php';