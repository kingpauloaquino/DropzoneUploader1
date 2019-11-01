<?php

namespace KSS\Classes;

require 'classes/Image.php';
require 'env.php';

$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]
$img_parent = IsSet($_GET['parent']) ? $_GET['parent'] : null; // Image Parent for Additional Photo and Barcode Photo

$env = new Environment();
$img = new Image($env, $img_ref, $img_cat, $img_parent);

$result = $img->get();

echo $result;

?>
