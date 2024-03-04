<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['item_name']) && $_POST['item_name']!=""){

	$item_name = $conn->real_escape_string($_POST['item_name']);
	$item_type = $_POST['item_type'];

	$sql = "INSERT INTO tbl_item (item_name,item_type,date_add) VALUES ('".$item_name."','".$item_type."','".date("Y-m-d H:i:s")."');" ;

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