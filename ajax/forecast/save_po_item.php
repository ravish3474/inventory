<?php
require_once('../../db.php');

$sql_insert = "INSERT INTO tbl_f_ordered_item (for_id,cat_id,color,color_id,qty) VALUES ";
$sql_insert .= "('".$_POST["for_id"]."','".$_POST["cat_id"]."','".$_POST["color_name"]."','".$_POST["color_id"]."','".$_POST["qty"]."'); ";
if($conn->query($sql_insert)){

	$a_return["result"] = "success";

}else{
	$a_return["result"] = "fail";
	$a_return["msg"] = "Error on update data!";
}

echo json_encode($a_return);
?>