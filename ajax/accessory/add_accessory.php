<?php
session_start();

require_once('../../db.php');

$temp = explode(".", $_FILES["add_picture"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$uploadFile = "../../files/images/" . $newfilename;
if(move_uploaded_file($_FILES["add_picture"]["tmp_name"], $uploadFile)){
$info = getimagesize($uploadFile);

// Check if the uploaded file is an image
if ($info !== false) {
    $imageType = $info[2];

    // Check the image type and create an image resource accordingly
    if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_JPEG2000) {
        $source = imagecreatefromjpeg($uploadFile);
        $quality = 70; // Change the quality value as needed (0 - 100)

        // Save the compressed image back to the same file
        imagejpeg($source, $uploadFile, $quality);
        // Free up memory
        imagedestroy($source);
            $cat_id = $_POST['cat_name'];
            $sup_id = $_POST['sup_name'];
            $sup_code = addslashes($_POST['sup_code']);
            $recvd_date = $_POST['recv_date'];
            $location = addslashes($_POST['location']);
            $product_name = addslashes($_POST['product_name']);
            $size_colour = addslashes($_POST['size_colour']);
            $stock = addslashes($_POST['stock']);
            $unit_type = addslashes($_POST['unit_type']);
            $po_number = $_POST['po_number'];
            $picture = $newfilename;
            $emp_id = $_SESSION['employee_id'];
            $emp_name = $_SESSION['employee_name'];
            $sql = "INSERT INTO `accessory_table`(`product_name`, `size_colour`, `stock`,`balance`, `unit_type`, `po_number`, `picture_name`, `emp_id`, `emp_name`,`cat_id`,`sup_id`,`sup_code`,`recv_date`,`location`) VALUES ('".$product_name."','".$size_colour."','".$stock."','".$stock."','".$unit_type."','".$po_number."','".$picture."','".$emp_id."','".$emp_name."','".$cat_id."','".$sup_id."','".$sup_code."','".$recvd_date."','".$location."')";
            if($conn->query($sql)){
                die(json_encode(array('status'=>'1','msg'=>'success')));
            }
            else{
                die(json_encode(array('status'=>0)));
            }
        }
        else{
            die(json_encode(array('status'=>0)));
        }
    } else {
        //echo "File is not a JPEG image.";
        die(json_encode(array('status'=>0)));
    }
} else {
    die(json_encode(array('status'=>0)));
    // echo "File is not an image.";
}

?>