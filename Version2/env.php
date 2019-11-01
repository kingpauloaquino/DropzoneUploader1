<?php

namespace KSS\Classes;

class Environment {

  public static $based_url_old = "http://img.scrapcatapp.com";
  public static $root_dir_old = "C:/SCA-Images";

  public static $based_url = "http://localhost:8007/permalink";
  public static $root_dir = "F:/Virtual-Storage";

  public static $image_quality = 50;
  public static $image_resizes = [150, 113];
  public static $watermark = "cpg-watermark.png";

  public function get_old_path($reference, $sub) {
    return [
      "reference" => $reference,
      "name" => $sub,
      "path" => self::$root_dir_old . "/" . $reference . "/" . $sub,
    ];
  }

  public function get_path($reference, $category, $sub) {
    return [
      "reference" => $reference,
      "name" => $sub,
      "category" => $category,
      "path" => self::$root_dir . "/" . $reference . "/" . $category . "/" . $sub
    ];
  }

  public function get_sub_dir($parent = null) {

    if($parent != null) {
      return array(
        "original" => $parent . "/ORGL",
        "compressed" => $parent . "/COMP",
        "thumbnail" => $parent . "/THMB",
      );
    }
    return array(
      "original" => "ORGL",
      "compressed" => "COMP",
      "thumbnail" => "THMB",
    );
  }

}
