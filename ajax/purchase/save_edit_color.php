<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['color_id']) && $_POST['color_id']!=""){
	$sql = "UPDATE tbl_color SET color_name='".$_POST['color_name']."' WHERE color_id=".$_POST['color_id'].";" ;
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