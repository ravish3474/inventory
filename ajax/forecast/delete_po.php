<?php
require_once('../../db.php');

$sql_delete = "DELETE FROM tbl_f_ordered_item WHERE for_id='".$_POST["for_id"]."'; ";
if($conn->query($sql_delete)){

	$sql_delete2 = "DELETE FROM tbl_f_ordered WHERE for_id='".$_POST["for_id"]."'; ";
	$conn->query($sql_delete2);

	$a_return["result"] = "success";
}else{
	$a_return["result"] = "fail";
}

echo json_encode($a_return);
?>