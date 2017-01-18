<?php

session_start();

# I create the image (Width x Height)
$image = imageCreate(73,20);

#set the background color of the image
$fundo = imagecolorallocate($image, 220, 220, 220);

$_SESSION['captcha_expira'] = date('Y-m-d H:i:s', strtotime('+1 minute', strtotime(date('H:i:s'))));

#Generates a random alphanumeric text
$captcha_random = md5(rand());

$captcha_palavra = substr($captcha_random, 0,4);

#Path of the font
$fonte = 'fonts/'.mt_rand(1, 3).'.gst'; 

#Draw the text
for ($i = 0; $i <=3; $i++){
	imagettftext($image, mt_rand(12, 14), mt_rand(1, 2), $i*mt_rand(18, 19)+mt_rand(2,4), mt_rand(16,18), imagecolorallocate($image,70,130,180), $fonte, $captcha_palavra{$i});
}

#Store the random number in session
$_SESSION["captcha"] = $captcha_palavra;

#Avoid client-side caching
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache");

#Send the image to the browser
header("Content-type: image/PNG");

imagePNG($image);
imagedestroy($image);