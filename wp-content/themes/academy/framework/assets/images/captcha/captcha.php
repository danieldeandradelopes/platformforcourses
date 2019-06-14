<?php 
session_start();

$enc_num = rand(0, 9999);
$key_num = rand(0, 24);
$hash_string = substr(md5($enc_num), $key_num, 5);
$hash_md5 = md5($hash_string);

$_SESSION['captcha'] = $hash_md5;

//image background
$bgs = array('bg.png');
$background = array_rand($bgs, 1);

//generate code
$img_handle = imagecreatefrompng($bgs[$background]);
$text_colour = imagecolorallocate($img_handle, 255, 255, 255);
$font_size = 5;

$size_array = getimagesize($bgs[$background]);
$img_w = $size_array[0];
$img_h = $size_array[1];

$horiz = round(($img_w/2)-((strlen($hash_string)*imagefontwidth(5))/2), 1);
$vert = round(($img_h/2)-(imagefontheight($font_size)/2));

//create image
imagestring($img_handle, $font_size, $horiz, $vert, $hash_string, $text_colour);
imagepng($img_handle);

//destroy image
imagedestroy($img_handle);