<?php
session_start();

require_once('../../db.php');

$a_result = array();

if( !isset($_POST["edit_price"]) || ($_POST["edit_price"]=="") || !isset($_POST["fabric_id_list"]) || ($_POST["fabric_id_list"]=="") ){
	
	$a_result["result"] = "fail";
	$a_result["msg"] = "Invalid parameter";
	echo json_encode($a_result);
	exit();
}

$sql_update = "UPDATE fabric SET fabric_in_price='".$_POST["edit_price"]."' ";
$sql_update .= "WHERE fabric_id IN (".$_POST["fabric_id_list"]."); ";

if($conn->query($sql_update)){

	$a_result["result"] = "success";
	
}else{

	$a_result["result"] = "fail";
	$a_result["msg"] = "Update data fail!";

}

echo json_encode($a_result);
?>
