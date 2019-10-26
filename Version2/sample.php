<!DOCTYPE html>
<html>
<body>
<?php
$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]
?>
<form action="/index.php?reference=<?php echo $img_ref; ?>&category=<?php echo $img_cat; ?>" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="filedata" id="filedata">
    <input type="submit" value="Upload Image" name="submit">
</form>
</body>
</html>
