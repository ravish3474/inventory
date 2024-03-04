<?php
session_start();

require_once('../../db.php');

if( !isset($_POST["fabric_id"]) || ($_POST["supplier_id"]=="") ){
	
	$a_result["result"] = 'fail';
	$a_result["msg"] = 'Invalid parameter';
	echo json_encode($a_result);
	exit();
}

$fabric_id = $_POST["fabric_id"];
$supplier_id = $_POST["supplier_id"];

$sql_update = "UPDATE fabric SET supplier_id='".$supplier_id."' WHERE fabric_id='".$fabric_id."';";

if($conn->query($sql_update)){

	$a_result["result"] = 'success';

}else{
	$a_result["result"] = 'fail';
	$a_result["msg"] = 'Error: Database update fail.';
	
}

echo json_encode($a_result);
?>