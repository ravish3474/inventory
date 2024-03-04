<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["order_lkr_title_id"]) || !isset($_POST["order_code"]) || !isset($_POST["fabric_id_list"]) ){
	
	$a_result["result"] = "Invalid parameter.";
	echo json_encode($a_result);
	exit();
}

$order_lkr_title_id = $_POST["order_lkr_title_id"];
$order_code = $_POST["order_code"];
$fabric_id_list = $_POST["fabric_id_list"];

$sql_insert = "INSERT INTO tbl_rq_form (order_lkr_title_id,order_code,rq_date,employee_id) VALUES ('".$order_lkr_title_id."','".$order_code."','".date("Y-m-d H:i:s")."','".$_SESSION["employee_id"]."'); ";
$conn->query($sql_insert);
$rq_id = $conn->insert_id;

$tmp_fabric_id = explode(",",$fabric_id_list);
for($i=0;$i<sizeof($tmp_fabric_id);$i++){

	$fabric_id = $tmp_fabric_id[$i];
	$sql_select = "SELECT fabric_balance FROM fabric WHERE fabric_id=".$fabric_id."; ";
	$rs_balance = $conn->query($sql_select);
	$row_balance = $rs_balance->fetch_assoc();

	$sql_insert_item = "INSERT INTO tbl_rq_form_item (rq_id,fabric_id,balance_before) VALUES ('".$rq_id."','".$fabric_id."','".$row_balance["fabric_balance"]."'); ";
	$conn->query($sql_insert_item);

}

$sql_update = "UPDATE tbl_order_lkr_title SET to_producing=1 WHERE order_lkr_title_id='".$order_lkr_title_id."'; ";
$conn->query($sql_update);

$sql_update3 = "UPDATE tbl_order_lkr_title SET to_forecast=2 WHERE order_lkr_title_id='".$order_lkr_title_id."' AND to_forecast=0; ";
$conn->query($sql_update3);

$sql_update2 = "UPDATE fabric SET on_producing=1 WHERE fabric_id IN (".$fabric_id_list."); ";
$conn->query($sql_update2);

$a_result["result"] = "success";

echo json_encode($a_result);
?>