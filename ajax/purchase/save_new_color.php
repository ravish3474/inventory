<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['color_name']) && $_POST['color_name']!=""){

	$color_name = $conn->real_escape_string($_POST['color_name']);

	$sql = "INSERT INTO tbl_color (color_name,date_add) VALUES ('".$color_name."','".date("Y-m-d H:i:s")."');" ;

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