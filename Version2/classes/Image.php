<?php

namespace KSS\Classes;

class Image {

  public static $environment;
  public static $original;
  public static $compressed;
  public static $thumbnail;

  public static $original_old;
  public static $compressed_old;
  public static $thumbnail_old;

  public function __construct($env, $reference, $category)
  {
    self::$environment = $env;
    $subs = self::$environment->get_sub_dir();

    self::$original_old = self::$environment->get_old_path($reference, $subs["original"]);
    self::$compressed_old = self::$environment->get_old_path($reference, $subs["compressed"]);
    self::$thumbnail_old = self::$environment->get_old_path($reference, $subs["thumbnail"]);

    self::$original = self::$environment->get_path($reference, $category, $subs["original"]);
    self::$compressed = self::$environment->get_path($reference, $category, $subs["compressed"]);
    self::$thumbnail = self::$environment->get_path($reference, $category, $subs["thumbnail"]);
  }

  public function get()
  {
    $orig = array("original" => $this->scan_dir(self::$original_old, self::$original));
    $orig += array("compressed" => $this->scan_dir(self::$compressed_old, self::$compressed));
    $orig += array("thumbnail" => $this->scan_dir(self::$thumbnail_old, self::$thumbnail));

    return json_encode(array("images" => $orig));
  }

  public function scan_dir($old, $new)
  {
    $url_old = self::$environment::$based_url_old;
    $url = self::$environment::$based_url;

    $dir = opendir($old["path"]);

    $images = null;

    if($dir) {

      $url_old = $url_old . "/" . $old["reference"] . "/" . $old["name"] . "/";

      while ($file = readdir($dir)) {
          if ($file == '.' || $file == '..') {
              continue;
          }
          $images[] = array("name" => $file, "url" => $url_old . $file);
      }
      closedir($dir);

    }
    else {

      $dir = opendir($new["path"]);

      if($dir) {

        $url = $url . "/" . $new["reference"] . "/" . $new["name"] . "/" . $new["category"] . "/";

        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $images[] = array("name" => $file, "url" => $url . $file);
        }
        closedir($dir);

      }

    }

    return $images;
  }

}

?>
