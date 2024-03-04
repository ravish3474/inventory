<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['item_id']) && $_POST['item_id']!=""){

	$item_name = $conn->real_escape_string($_POST['item_name']);
	$item_type = $_POST['item_type'];

	$sql = "UPDATE tbl_item SET item_name='".$item_name."',item_type='".$item_type."' ";
	$sql .= " WHERE item_id=".$_POST['item_id'].";" ;

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