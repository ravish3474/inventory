<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["pac_id"]) || !isset($_POST["new_inv_no"]) ){
	echo "fail";
	exit();
}

$pac_id = $_POST["pac_id"];
$inv_no = $_POST["new_inv_no"];

$sql_update = "UPDATE tbl_packing SET inv_no='".$inv_no."' WHERE pac_id='".$pac_id."'; ";
$conn->query($sql_update);

echo "success";

?>