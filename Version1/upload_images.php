<?php
   $user_path = IsSet($_GET["account"]) ? $_GET["account"] : null;
   $remaining_quota = IsSet($_GET["quota"]) ? $_GET["quota"] : null;
   
   if(IsSet($_GET["ref"])) {
        $ref = $_GET["ref"];
   }
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<script>
      $(function () {        
        Dropzone.options.myAwesomeDropzone = {
          paramName: "file", // The name that will be used to transfer the file
          maxFilesize: 2, // MB
          accept: function(file, done) {
            if (file.name == "justinbieber.jpg") {
              done("Naha, you don't.");
            }
            else { done(); }
          }
        };
    });
</script> 
<link href="dropzone/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="dropzone/10/dropzone-amd-module.js"></script>
<form action="upload_process.php?ref=<?php echo $ref; ?>" class="dropzone">
    <!-- <input type="file" name="file" /> -->
</form>



