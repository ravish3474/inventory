<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['unit_name']) && $_POST['unit_name']!=""){

	$unit_name = $conn->real_escape_string($_POST['unit_name']);

	$sql = "INSERT INTO tbl_unit (unit_name,date_add) VALUES ('".$unit_name."','".date("Y-m-d H:i:s")."');" ;

	if($conn->query($sql)){
		$a_return["result"] = "success";
	}else{
		$a_return["result"] = "fail 1";
	}
}else{
	$a_return["result"] = "fail 2";
}

echo json_encode($a_return);
?>