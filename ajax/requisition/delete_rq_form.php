<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["rq_id"]) ){
	
	$a_result["result"] = "Invalid parameter.";
	echo json_encode($a_result);
	exit();
}

$rq_id = $_POST["rq_id"];

$a_fabric_id = array();

$sql_select = "SELECT fabric_id FROM tbl_rq_form_item WHERE rq_id='".$rq_id."'; ";
$rs_select = $conn->query($sql_select);
while($row_select = $rs_select->fetch_assoc()){
	$a_fabric_id[] = $row_select["fabric_id"];
}
$s_fabric_id = implode(",",$a_fabric_id);

$sql_update1 = "UPDATE fabric SET on_producing=0 WHERE fabric_id IN (".$s_fabric_id."); ";
$conn->query($sql_update1);

$sql_select2 = "SELECT order_lkr_title_id FROM tbl_rq_form WHERE rq_id='".$rq_id."'; ";
$rs_select2 = $conn->query($sql_select2);
$row_select2 = $rs_select2->fetch_assoc();
$order_lkr_title_id = $row_select2["order_lkr_title_id"];

$sql_update2 = "UPDATE tbl_order_lkr_title SET to_producing=0 WHERE order_lkr_title_id='".$order_lkr_title_id."'; ";
$conn->query($sql_update2);

$sql_update3 = "UPDATE tbl_rq_form SET enable=0 WHERE rq_id='".$rq_id."'; ";
$conn->query($sql_update3);

$a_result["result"] = "success";

echo json_encode($a_result);
?>