<?php

namespace KSS\Classes;

require 'classes/ImageUploader.php';
require 'env.php';

$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]
$img_parent = IsSet($_GET['parent']) ? $_GET['parent'] : null; // Image Parent for Additional Photo and Barcode Photo

$env = new Environment();
$img = new ImageUploader($env, $img_ref, $img_cat, $img_parent);

if (!empty($_FILES)) {

  // limit file size up to 10MB
  if($_FILES['filedata']['size'] > 10000000) {
    return array(
      "status" => 401,
      "message" => "Sorry, your file is too large."
    );
  }

  $temp = $_FILES['filedata']['tmp_name'];
  $filename = $_FILES['filedata']['name'];
  $size = $_FILES['filedata']['size'];

  $result = $img->upload($temp, $filename);

  echo $result;

}












?>
