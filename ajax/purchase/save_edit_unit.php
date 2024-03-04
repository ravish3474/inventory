<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['unit_id']) && $_POST['unit_id']!=""){
	$sql = "UPDATE tbl_unit SET unit_name='".$_POST['unit_name']."' WHERE unit_id=".$_POST['unit_id'].";" ;
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