<?php

namespace KSS\Classes;

require 'classes/ImageUploader.php';
require 'env.php';

$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]

$env = new Environment();
$img = new ImageUploader($env, $img_ref, $img_cat);

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

  // var_dump($temp);
  // var_dump($filename);
  // var_dump($size);

  $arr = array(
    "status" => 500,
    "message" => "Fail"
  );

  if($img->upload($temp, $filename)) {
    $arr = array(
      "status" => 200,
      "message" => "Success"
    );
  }

  echo json_encode($arr);

}












?>
