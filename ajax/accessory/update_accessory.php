<?php
session_start();

require_once('../../db.php');


$access_id = $_POST['access_id'];
$stock = $_POST['stock'];
$stock_used = $_POST['stock_used'];
$balance = $_POST['balance'];
$product_name = $_POST['product_name'];
$size_colour = $_POST['size_colour'];
$unit_type = $_POST['unit_type'];
$last_ex = $_POST['last_ex'];
$emp_id = $_SESSION['employee_id'];
$emp_name = $_SESSION['employee_name'];
$po_number = $_POST['po_number'];

$sql = "INSERT INTO `accessory_log`(`access_id`, `product_name`, `size_colour`, `stock`, `stock_used`, `balance`, `unit_type`, `last_ex`, `po_number`, `emp_id`, `emp_name`) VALUES ('".$access_id."','".$product_name."','".$size_colour."','".$stock."','".$stock_used."','".$balance."','".$unit_type."','".$last_ex."','".$po_number."','".$emp_id."','".$emp_name."')";

if($conn->query($sql)){
    $update_sql = "UPDATE `accessory_table` SET `size_colour`='".$size_colour."',`stock`='".$balance."',balance='".$balance."',`stock_used`='".$stock_used."',`unit_type`='".$unit_type."',`last_ex`='".$last_ex."',`po_number`='".$po_number."' WHERE access_id='".$access_id."'";
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
?>