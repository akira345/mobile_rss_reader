<?php
require("./component/qrcode_img.php");


Header("Content-type: image/png");

$data=str_replace('qr.php','','http://'.$_SERVER["HTTP_HOST"]. $_SERVER['REQUEST_URI']);

$z=new Qrcode_image;

#$z->set_qrcode_version(1);           # set qrcode version 1
$z->set_qrcode_error_correct("H");   # set ecc level H
$z->set_module_size(2);              # set module size 3pixel
#$z->set_quietzone(5);                # set quietzone width 5 modules

$z->qrcode_image_out($data,"png");

#$z->image_out($z->cal_qrcode($data),"png");   #old style

?>
