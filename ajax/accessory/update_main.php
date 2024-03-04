<?php
session_start();

require_once('../../db.php');

$access_id = $_POST['access_id'];
$product_name = addslashes($_POST['product_name']);
$size_colour = addslashes($_POST['size_colour']);
$stock = addslashes($_POST['stock']);
$unit_type = addslashes($_POST['unit_type']);
$po_number = addslashes($_POST['po_number']);
$emp_id = $_SESSION['employee_id'];
$emp_name = $_SESSION['employee_name'];
$cat_id = $_POST['cat_name'];
$sup_id = $_POST['sup_name'];
$sup_code = addslashes($_POST['sup_code']);
$recvd_date = $_POST['recv_date'];
$location = addslashes($_POST['location']);
if($_FILES['add_picture']['name']==""){
    $sql = "INSERT INTO `accessory_log`(`access_id`, `product_name`, `size_colour`, `stock`, `stock_used`, `balance`, `unit_type`, `po_number`, `emp_id`, `emp_name`) VALUES ('".$access_id."','".$product_name."','".$size_colour."','".$stock."','0','".$stock."','".$unit_type."','".$po_number."','".$emp_id."','".$emp_name."')";

    if($conn->query($sql)){
        $update_sql = "UPDATE `accessory_table` SET `size_colour`='".$size_colour."',`product_name`='".$product_name."',`stock`='".$stock."',balance='".$stock."',`unit_type`='".$unit_type."',`po_number`='".$po_number."',`cat_id`='".$cat_id."',`sup_id`='".$sup_id."',`sup_code`='".$sup_code."',`recv_date`='".$recvd_date."',`location`='".$location."' WHERE access_id='".$access_id."'";
        if($conn->query($update_sql)){
            die(json_encode(array('status'=>'1')));
        }
        else{
            die(json_encode(array('status'=>'0')));
        }
    }
    else{
        die(json_encode(array('status'=>'0')));
    }
}
else{
    $fetch_sql = "SELECT picture_name as pic FROM accessory_table WHERE access_id='$access_id'";
    if($query=$conn->query($fetch_sql)){
        $fetcher = $query->fetch_assoc();
        $picture = $fetcher['pic'];
        $del_pic = "../../files/images/" . $picture;
    }
    
    
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
                $sql = "INSERT INTO `accessory_log`(`access_id`, `product_name`, `size_colour`, `stock`, `stock_used`, `balance`, `unit_type`, `po_number`, `emp_id`, `emp_name`) VALUES ('".$access_id."','".$product_name."','".$size_colour."','".$stock."','0','".$stock."','".$unit_type."','".$po_number."','".$emp_id."','".$emp_name."')";
        
                if($conn->query($sql)){
                    $update_sql = "UPDATE `accessory_table` SET `size_colour`='".$size_colour."',`product_name`='".$product_name."',`stock`='".$stock."',balance='".$stock."',`unit_type`='".$unit_type."',`po_number`='".$po_number."',picture_name='".$newfilename."',`cat_id`='".$cat_id."',`sup_id`='".$sup_id."',`sup_code`='".$sup_code."',`recv_date`='".$recvd_date."',`location`='".$location."' WHERE access_id='".$access_id."'";
                    if($conn->query($update_sql)){
                        if (file_exists($del_pic)) {
                            unlink($del_pic);
                        }
                        die(json_encode(array('status'=>'1')));
                    }
                    else{
                        die(json_encode(array('status'=>'0')));
                    }
                }
                else{
                    die(json_encode(array('status'=>'0')));
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
}

?>