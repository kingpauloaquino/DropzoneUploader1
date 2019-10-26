<?php

namespace KSS\Classes;

class Environment {

  public static $root_dir = "C:/Users/king/Documents/GitHub/DropzoneUploader1/Version2/DemoStorage";
  public static $image_quality = 50;
  public static $image_resizes = [150, 113];
  public static $watermark = "cpg-watermark.png";

  public function get_path($reference, $category, $sub) {
    if($category == null) {
      return self::$root_dir . "/" . $reference . "/" . $sub;
    }
    return self::$root_dir . "/" . $reference . "/" . $category . "/" . $sub;
  }

  public function get_sub_dir() {
    return array(
      "original" => "ORGL",
      "edited" => "EDIT",
      "compressed" => "COMP",
      "thumbnail" => "THMB",
    );
  }

}
