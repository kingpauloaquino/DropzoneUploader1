<?php

namespace KSS\Classes;

class ImageUploader {

  public static $environment;
  public static $image_sizes;
  public static $destinations_cache;

  public function __construct($env, $reference, $category)
  {
    self::$environment = $env;
    $subs = self::$environment->get_sub_dir();

    $path = self::$environment->get_path($reference, $category, $subs["original"]);
    $this->created_folder($path["path"]);

    $path = self::$environment->get_path($reference, $category, $subs["compressed"]);
    $this->created_folder($path["path"]);

    $path = self::$environment->get_path($reference, $category, $subs["thumbnail"]);
    $this->created_folder($path["path"]);
  }

  public function compressedImage($source, $destination, $quality = 50)
  {
      $info = getimagesize($source);
      if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
      elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
      imagecreatefromjpeg( $source );
      imagejpeg( $image, $destination, $quality );
  }

  public function resizeImage($source, $destination, $sizes)
  {
      require_once 'ImageManipulator.php';
      $manipulator = new ImageManipulator($source);
      $newImage = $manipulator->resample($sizes[0], $sizes[1]);
      $manipulator->save($destination);
  }

  public function created_folder($path)
  {
      if (!file_exists($path)) {
          mkdir($path, 0777, true);
      }
      self::$destinations_cache[] = $path;
  }

  public function get_location($catId, $filename)
  {
    $d = self::$destinations_cache[$catId] . "/" . $filename;
    return $d;
  }

  public function get_image_size()
  {
    $d = self::$environment::$image_resizes;
    return $d;
  }

  public function upload($source, $filename, $move = true)
  {
    if($move) {

      $file = $this->get_location(0, $filename);
      move_uploaded_file($source, $file);

      $path1 = $this->get_location(1, $filename);
      $this->compressedImage($file, $path1);

      $path2 = $this->get_location(2, $filename);
      $this->resizeImage($file, $path2, $this->get_image_size());

      return json_encode(array(
        "status" => 200,
        "message" => "Success"
      ));

    }
    else {
      copy($source, $destination);

      return json_encode(array(
        "status" => 200,
        "message" => "Success"
      ));
    }

    return json_encode(array(
      "status" => 500,
      "message" => "Fail"
    ));

  }
}

?>
