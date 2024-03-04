<?php
$a_return["result"] = "";

if(!isset($_POST["order_lkr_id"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$sql_update = "UPDATE tbl_order_lkr SET enable=0 WHERE order_lkr_id=".$_POST["order_lkr_id"]."; ";
if($conn->query($sql_update) ){
	$a_return["result"] = "success";
}else{
	$a_return["result"] = "fail:Data cannot update!!";
	
}

echo json_encode($a_return);
?>