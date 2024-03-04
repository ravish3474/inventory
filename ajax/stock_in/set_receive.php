<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["for_item_id"]) || !isset($_POST["val_receive"]) ){
	$a_result["result"] = "fail";
	$a_result["msg"] = "Invalid parameter.";
	echo json_encode($a_result);
	exit();
}

$for_item_id = $_POST["for_item_id"];
$val_receive = floatval($_POST["val_receive"]);

$sql_select = "SELECT * FROM tbl_f_ordered_item WHERE for_item_id='".$for_item_id."';";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();
$qty = floatval($row_select["qty"]);

if($val_receive==$qty){

	$sql_update = "UPDATE tbl_f_ordered_item SET is_receive=1,receive_date='".date("Y-m-d")."' WHERE for_item_id='".$for_item_id."'; ";
	$conn->query($sql_update);

	$sql_update3 = "UPDATE tbl_f_purchase SET mark_ordered=2 WHERE for_id='".$row_select["for_id"]."' AND cat_id='".$row_select["cat_id"]."' AND color_id='".$row_select["color_id"]."';";
	$conn->query($sql_update3);

}else{

	$new_val = $qty-$val_receive;

	$sql_insert = "INSERT INTO tbl_f_ordered_item (for_id,cat_id,color,color_id,qty) ";
	$sql_insert .= "VALUES ('".$row_select["for_id"]."','".$row_select["cat_id"]."','".$row_select["color"]."','".$row_select["color_id"]."','".$new_val."'); ";
	$conn->query($sql_insert);

	$sql_update = "UPDATE tbl_f_ordered_item SET qty='".$val_receive."',is_receive=1,receive_date='".date("Y-m-d")."' WHERE for_item_id='".$for_item_id."'; ";
	$conn->query($sql_update);

	$sql_update3 = "UPDATE tbl_f_purchase SET fpu_value='".$new_val."' WHERE for_id='".$row_select["for_id"]."' AND cat_id='".$row_select["cat_id"]."' AND color_id='".$row_select["color_id"]."';";
	$conn->query($sql_update3);
}

$sql_check = "SELECT COUNT(*) AS num_item_row FROM tbl_f_ordered_item WHERE is_receive=0 AND for_id='".$row_select["for_id"]."';";
$rs_check = $conn->query($sql_check);
$row_check = $rs_check->fetch_assoc();
$num_item_row = intval($row_check["num_item_row"]);

$sql_update2 = "";
if($num_item_row==0){
	$sql_update2 = "UPDATE tbl_f_ordered SET po_status='All received' WHERE for_id='".$row_select["for_id"]."'; ";
	
}else{
	$sql_update2 = "UPDATE tbl_f_ordered SET po_status='Partially received' WHERE for_id='".$row_select["for_id"]."'; ";
	
}
$conn->query($sql_update2);


$a_result["result"] = "success";

echo json_encode($a_result);
?>