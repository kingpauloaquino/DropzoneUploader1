<!DOCTYPE html>
<html>
<body>
<?php
$img_ref = $_GET['reference']; // YardId-BoxId
$img_cat = $_GET['category']; // Image Category [SCA Unit, Barcode, Addition Photo]
$img_parent = IsSet($_GET['parent']) ? $_GET['parent'] : null; // Image Parent for Additional Photo and Barcode Photo
?>
<form action="/index.php?reference=<?php echo $img_ref; ?>&category=<?php echo $img_cat; ?>&parent=<?php echo $img_parent; ?>" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="filedata" id="filedata">
    <input type="submit" value="Upload Image" name="submit">
</form>
</body>
</html>
