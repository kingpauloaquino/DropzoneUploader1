<?php


class ImgCompressor
{

  public static $quality = 155;

  public static $root_folder = "D:/CKT-Cloud/Dropbox/CPG-WS-Active/CPGI";

  public function compressedImage($part_number, $filename)
  {
      $source = $this::$root_folder . "/" . $part_number . "/COMP/" . $filename;

      $destination = $this::$root_folder . "/" . $part_number . "/TEMP/";

      $destination2 = $this::$root_folder . "/" . $part_number . "/TEMP2/";

      $this->created_folder($destination);

      $this->created_folder($destination2);

      $destination = $destination . $filename;

      $destination2 = $destination2 . $filename;

      if(file_exists($destination2)) {
        $array = array(
            "original_size" => "http://img.scrapcatapp.com/". $part_number ."/TEMP/". $filename,
            "resized" => "http://img.scrapcatapp.com/". $part_number ."/TEMP2/". $filename
          );
    
          return json_encode($array);
      }

      $info = getimagesize($source);

      if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);

      elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);

      elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);

      imagecreatefromjpeg( $source );

      imagejpeg( $image, $destination, $this::$quality );

      $this->resizeImage($destination, $destination2);

      $array = array(
        "original_size" => "http://img.scrapcatapp.com/". $part_number ."/TEMP/". $filename,
        "resized" => "http://img.scrapcatapp.com/". $part_number ."/TEMP2/". $filename
      );

      return json_encode($array);
  }

  public function resizeImage($tempFile, $destination)
  {
      require_once 'ImageManipulator.php';
      $manipulator = new ImageManipulator($tempFile);
      $newImage = $manipulator->resample(770, 605);
      $manipulator->save($destination);
  }

  public function created_folder($path) {
      if (!file_exists($path)) {
          mkdir($path, 0777, true);
      }
  }

}

$part_number = $_GET["part"];
$filename = $_GET["filename"];
$img_comp = new ImgCompressor();
$data = $img_comp->compressedImage($part_number, $filename);
echo $data;
