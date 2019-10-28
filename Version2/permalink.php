<?php

namespace KSS\Classes;

require 'classes/Image.php';
require 'env.php';

$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]

$env = new Environment();
$img = new Image($env, $img_ref, $img_cat);

$data = $img->get();

echo $data;

?>
