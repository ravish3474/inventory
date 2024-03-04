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

$a_fabric_id_old = array();
$a_fabric_id_new = array();

$sql_old = "SELECT fabric_id FROM tbl_rq_form_item WHERE rq_id='".$rq_id."' AND mark_cut_stock='0'; ";
$rs_old = $conn->query($sql_old);
while($row_old = $rs_old->fetch_assoc()){
	$a_fabric_id_old[] = $row_old["fabric_id"];
}

$a_fabric_id_new = explode(",",$fabric_id_list);

$a_delete_id = array();
$a_insert_id = array();

for($i=0;$i<sizeof($a_fabric_id_old);$i++){
	if(!in_array($a_fabric_id_old[$i], $a_fabric_id_new)){
		$a_delete_id[] = $a_fabric_id_old[$i];
	}
}

for($i=0;$i<sizeof($a_fabric_id_new);$i++){
	if(!in_array($a_fabric_id_new[$i], $a_fabric_id_old)){
		$a_insert_id[] = $a_fabric_id_new[$i];
	}
}


if(sizeof($a_delete_id)>0){
	$s_delete_id = implode(",", $a_delete_id);
	$sql_update1 = "UPDATE fabric SET on_producing=0 WHERE fabric_id IN (".$s_delete_id."); ";
	$conn->query($sql_update1);

	$sql_delete = "DELETE FROM tbl_rq_form_item WHERE rq_id='".$rq_id."' AND fabric_id IN (".$s_delete_id.") AND mark_cut_stock='0';";
	$conn->query($sql_delete);
}

if(sizeof($a_insert_id)>0){
	$s_insert_id = implode(",", $a_insert_id);
	$sql_update2 = "UPDATE fabric SET on_producing=1 WHERE fabric_id IN (".$s_insert_id."); ";
	$conn->query($sql_update2);

	for($i=0;$i<sizeof($a_insert_id);$i++){

		$fabric_id = $a_insert_id[$i];
		$sql_select = "SELECT fabric_balance FROM fabric WHERE fabric_id=".$fabric_id."; ";
		$rs_balance = $conn->query($sql_select);
		$row_balance = $rs_balance->fetch_assoc();

		$sql_insert_item = "INSERT INTO tbl_rq_form_item (rq_id,fabric_id,balance_before) VALUES ('".$rq_id."','".$fabric_id."','".$row_balance["fabric_balance"]."'); ";
		$conn->query($sql_insert_item);

	}
}

$a_result["result"] = "success";

echo json_encode($a_result);
?>