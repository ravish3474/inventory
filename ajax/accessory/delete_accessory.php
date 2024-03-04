<?php
session_start();

require_once('../../db.php');

$access_id = $_POST['access_id'];
$emp_id = $_SESSION['employee_id'];
$emp_name = $_SESSION['employee_name'];

$sql = "INSERT INTO `accessory_log`(`access_id`, `product_name`, `size_colour`, `stock`, `stock_used`, `balance`, `unit_type`, `last_ex`, `po_number`, `emp_id`, `emp_name`) VALUES ('".$access_id."','deleted','deleted','0','0','0','deleted','deleted','deleted','".$emp_id."','".$emp_name."')";

if($conn->query($sql)){
    $del_sql = "DELETE FROM accessory_table WHERE access_id = '".$access_id."'";
    if($conn->query($del_sql)){
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