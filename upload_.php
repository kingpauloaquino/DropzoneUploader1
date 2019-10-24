<?php
   $user_path = IsSet($_GET["account"]) ? $_GET["account"] : null;
   $remaining_quota = IsSet($_GET["quota"]) ? $_GET["quota"] : null;
   if(IsSet($_GET["ref"])) { $ref = $_GET["ref"]; }
   if(IsSet($_GET["uid"])) { $uid = $_GET["uid"]; }
   if(IsSet($_GET["part"])) { $part = $_GET["part"]; }
   if(IsSet($_GET["edited"])) { $edited = $_GET["edited"]; }
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>  var quota_max_filesize = 10; </script> 
<link href="dropzone/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="dropzone/10/dropzone-amd-module.js"></script>
<form action="upload_process.php?ref=<?php echo $ref; ?>&uid=<?php echo $uid; ?>&part=<?php echo $part; ?>&edited=<?php echo $edited; ?>" class="dropzone"></form>



