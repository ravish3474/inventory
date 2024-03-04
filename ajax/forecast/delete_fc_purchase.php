<?php
require_once('../../db.php');

$sql_delete = "DELETE FROM tbl_f_purchase WHERE fpu_id='".$_POST["fpu_id"]."'; ";
if($conn->query($sql_delete)){

	$a_return["result"] = "success";
}else{
	$a_return["result"] = "fail";
	$a_return["msg"] = "Fail to delete.";
}

echo json_encode($a_return);
?>