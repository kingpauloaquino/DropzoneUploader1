<?php
class Uploads {
    
    public static $file_count = 1;
    public static $folder_name = "";
    public static $original = "ORGL";
    public static $edited = "EDIT";
    public static $compressed = "COMP";
    public static $thumb = "THMB";
    public static $watermarkFile = "cpg-watermark.png";
    
    public static $last_Id;
    public static $imgFilename;

    public static $connection;
    public static $server = "localhost";
    public static $user = "cpg_user";
    public static $password = "@CPG12cpg@";
    public static $database = "ckt3_cpg_dblive";

    public static $root_folder = "D:/CKT-Cloud/Dropbox/CPG-WS-Active/CPGI"; //D:\CKT-Cloud\Dropbox\CPG-WS-Active\CPGI
//    public static $root_folder = "C:/inetpub/dd";

    public function checkpoint($path, $filename, $img_reference)
    {
        $filenames = explode(".", $filename);
        $filename = $img_reference ."_". $filenames[0] .".". $filenames[1];
        $fileInfo = $path . $filename;
        if(file_exists($fileInfo)) {
            unlink($fileInfo);
        }
        return $filename;
    }

    public function compressedImage($destination, $source, $quality = 50)
    {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
        imagecreatefromjpeg( $source );
        imagejpeg( $image, $destination, $quality );
    }
    
    public function resizeImage($tempFile, $destination)
    {
        require_once 'ImageManipulator.php';
        $manipulator = new ImageManipulator($tempFile);
        $newImage = $manipulator->resample(150, 113);
        $manipulator->save($destination);
    }
    
    public function saveInfo($arrays)
    {
        $sql = "INSERT INTO kpadb_images (img_reference_code, img_parent, img_sub_parent, img_filename, img_order_number) 
        VALUES ('". $arrays['img_ref'] ."', '". $arrays['img_part'] ."', '". $arrays['img_item'] ."', '". $arrays['newfilename'] ."', ". (int)$arrays['order_number'] .");";
        $this->Execute($sql);
    }
    
    public function saveLog($arrays)
    {
        require_once 'function.php';
        $f = new Functions;
        
        $dt = $f->dtGetCurrTimeZone($f::$timezone_taipei, FALSE);
        
        $uid = (int)$arrays['admin_id'];
        
        $sql = "INSERT INTO kpadb_images_logs (img_ref, admin_id, dateTime) 
        VALUES ('". $arrays['img_ref'] ."', $uid, '$dt');";
        $this->Execute($sql);
    }
    
    public function CreateDB() {
        $this::$connection = mysql_connect($this::$server, $this::$user, $this::$password) or die("could not connect".mysql_error());
        return mysql_select_db($this::$database, $this::$connection);
    }
    
    public function Execute($query)
    {
        try
        {
            $this->CreateDB();
            mysql_query($query);
            $this::$last_Id = mysql_insert_id();
            mysql_close($this::$connection);
            return TRUE;
        }
        catch(exception $ex)
        { }
        return FALSE;
    }

    public function curlProcess($Url)
    {
        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();
        // Now set some options (most are optional)
        curl_setopt($ch, CURLOPT_URL, $Url);  // Set URL to download
        curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm"); // Set a referer
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0"); // User agent
        curl_setopt($ch, CURLOPT_HEADER, 0); // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout in seconds
        $output = curl_exec($ch); // Download the given URL, and return output
        curl_close($ch); // Close the cURL resource, and free system resources
        return json_decode($output, true);
    }

    public function created_folder($path) {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function count_file($amount) {
        $files = array();
        for($i = 0; $i < $amount; $i++) {
            array_push($files, 'file'. ($i + 1));
        }
        return $files;
    }
}

    $file_count = 0;
    $upload = new Uploads;
    if(IsSet($_GET['part']))
    {
        $part = $_GET['part'];
    }
    if(IsSet($_GET['ref']))
    {
        $img_ref = $_GET['ref'];
    }
    if(IsSet($_GET['uid']))
    {
        $uid = $_GET['uid'];
    }
    if(IsSet($_GET['edited']))
    {
        $edited = $_GET['edited'];
    }
    if(IsSet($_GET['file_count']))
    {
        $file_count = (int)$_GET['file_count'];
    }
    
    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];

        $dir_name = $part;
        $root_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$original;
        $newFilename = $upload->checkpoint($path, $filename, $img_ref);

        if($edited == "NOT") {
            $destination  = $root_path ."/". $newFilename;
            move_uploaded_file($tempFile, $destination);

            $edited_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$edited ."/". $newFilename;
            copy($destination, $edited_path);
        }
        elseif($edited == "CATPAL") {

            $names = explode("_", $dir_name);
            if(count($names) > 2) {

                $dir_name = $names[0] ."-". $names[1];
                $upload->created_folder($upload::$root_folder ."/". $dir_name);
                $upload->created_folder($upload::$root_folder ."/". $dir_name ."/". $upload::$original);
                $upload->created_folder($upload::$root_folder ."/". $dir_name ."/". $upload::$edited);
                $upload->created_folder($upload::$root_folder ."/". $dir_name ."/". $upload::$compressed);
                $upload->created_folder($upload::$root_folder ."/". $dir_name ."/". $upload::$thumb);

                $root_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$original;
                $destination  = $root_path ."/". $newFilename;
                move_uploaded_file($tempFile, $destination);

                $edited_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$edited ."/". $newFilename;
                copy($destination, $edited_path);
            }
        }
        else {
            $destination = $upload::$root_folder ."/". $dir_name ."/".  $upload::$edited ."/". $newFilename;
            move_uploaded_file($tempFile, $destination);
        }

        $compressed_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$compressed ."/". $newFilename;
        $upload->compressedImage($compressed_path, $destination);

        $thumb_path = $upload::$root_folder ."/". $dir_name ."/".  $upload::$thumb ."/". $newFilename;
        $upload->resizeImage($destination, $thumb_path);
    }

?> 














