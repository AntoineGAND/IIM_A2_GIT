<?php
session_start();
require('config/config.php');
require('model/functions.fn.php');

if(Verify::files(['image' => ''])){
	User::setAvatar($_FILES['image']['tmp_name']);
}

include 'view/_header.php';
include 'view/edit_image.php';
include 'view/_footer.php';