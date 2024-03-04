<?php
session_start();

require_once('../../db.php');

$a_result = array();

if( !isset($_POST["fabric_id"]) || ($_POST["fabric_id"]=="") ){
	$a_result["result"] = 'fail';
	$a_result["msg"] = '<b>Invalid parameter</b>';
	exit();
}

$old_bal = $_POST["old_bal"];
$new_bal = $_POST["new_bal"];

$fabric_id = $_POST["fabric_id"];
$adj_date = date("Y-m-d H:i:s");
$user_adj_id = $_SESSION["employee_id"];		

if($old_bal>$new_bal){
	$in_out = "OUT";
	$adj_value = floatval($old_bal)-floatval($new_bal);
}else{
	$in_out = "IN";
	$adj_value = floatval($new_bal)-floatval($old_bal);
}

$sql_insert = "INSERT INTO tbl_adjust (fabric_id,in_out,adj_value,new_balance,adj_date,user_adj_id) ";
$sql_insert .= "VALUES ('".$fabric_id."','".$in_out."','".$adj_value."','".$new_bal."','".$adj_date."','".$user_adj_id."'); ";

if($conn->query($sql_insert)){

	$f_new_in = 0.0;
	if($old_bal>$new_bal){
		$fabric_adjust_post = "-".$adj_value;
		$f_new_in = floatval($_POST["old_in"])-$adj_value;
	}else{
		$fabric_adjust_post = "+".$adj_value;
		$f_new_in = floatval($_POST["old_in"])+$adj_value;
	}

	$sql_update = "UPDATE fabric SET fabric_adjust=fabric_adjust".$fabric_adjust_post.",fabric_balance=".$new_bal." ";
	$sql_update .= "WHERE fabric_id='".$fabric_id."'; ";

	$conn->query($sql_update);

	$a_result["result"] = 'success';
	$a_result["new_amount"] = number_format((floatval($_POST["unit_price"])*floatval($new_bal)),2);
	$a_result["new_in"] = number_format($f_new_in,2);
}

echo json_encode($a_result);
?>