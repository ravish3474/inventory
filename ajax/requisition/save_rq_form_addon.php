<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["rq_id"]) || !isset($_POST["fabric_id_list"]) ){
	
	$a_result["result"] = "Invalid parameter.";
	echo json_encode($a_result);
	exit();
}

$rq_id = $_POST["rq_id"];
$fabric_id_list = $_POST["fabric_id_list"];

$sql_select2 = "SELECT * FROM tbl_rq_form WHERE rq_id='".$rq_id."'; ";
$rs_old = $conn->query($sql_select2);
$row_old = $rs_old->fetch_assoc();
$order_lkr_title_id = $row_old["order_lkr_title_id"];
$order_code = $row_old["order_code"];

$sql_insert = "INSERT INTO tbl_rq_form (order_lkr_title_id,order_code,is_addon,rq_date,employee_id) VALUES ('".$order_lkr_title_id."','".$order_code."',1,'".date("Y-m-d H:i:s")."','".$_SESSION["employee_id"]."'); ";
$conn->query($sql_insert);
$new_rq_id = $conn->insert_id;

$tmp_fabric_id = explode(",",$fabric_id_list);
for($i=0;$i<sizeof($tmp_fabric_id);$i++){

	$fabric_id = $tmp_fabric_id[$i];
	$sql_select = "SELECT fabric_balance FROM fabric WHERE fabric_id=".$fabric_id."; ";
	$rs_balance = $conn->query($sql_select);
	$row_balance = $rs_balance->fetch_assoc();

	$sql_insert_item = "INSERT INTO tbl_rq_form_item (rq_id,fabric_id,balance_before) VALUES ('".$new_rq_id."','".$fabric_id."','".$row_balance["fabric_balance"]."'); ";
	$conn->query($sql_insert_item);

}

$sql_update = "UPDATE fabric SET on_producing=1 WHERE fabric_id IN (".$fabric_id_list."); ";
$conn->query($sql_update);

$a_result["result"] = "success";

echo json_encode($a_result);
?>