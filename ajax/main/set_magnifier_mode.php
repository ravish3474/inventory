<?php
session_start();
require_once('../../db.php');

if( !isset($_SESSION["employee_id"]) && $_SESSION["employee_id"]=="" ){
	$a_result["result"] = "fail";
	$a_result["msg"] = "Error: Login session expired!";
	echo json_encode($a_result);
	exit();
}

$magnifier_mode = $_POST["magnifier_mode"];

$sql_update = "UPDATE employee SET magnifier_mode='".$magnifier_mode."' WHERE employee_id='".$_SESSION["employee_id"]."';";

$a_result = array();

if($conn->query($sql_update)){

	$_SESSION["magnifier_mode"] = $magnifier_mode;
	$a_result["result"] = "success";
}else{

	$a_result["result"] = "fail";
	$a_result["msg"] = "Error: Setting magnifier mode fail!!";

}

echo json_encode($a_result);
?>
