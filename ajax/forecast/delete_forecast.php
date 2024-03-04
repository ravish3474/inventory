<?php
$a_return["result"] = "";

if(!isset($_POST["forecast_id"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$sql_delete = "DELETE FROM forecast_detail WHERE forecast_id=".$_POST["forecast_id"]."; ";
if( $conn->query($sql_delete) ){

	$sql_delete2 = "DELETE FROM forecast_head WHERE forecast_id=".$_POST["forecast_id"]."; ";
	if( $conn->query($sql_delete2) ){

		if($_POST["order_code"]!=""){
			$sql_update = "UPDATE tbl_order_lkr_title SET to_forecast=0 WHERE order_title='".$_POST["order_code"]."' AND enable=1; ";
			$conn->query($sql_update);
		}

		$a_return["result"] = "success";
		
	}else{
		$a_return["result"] = "fail:Data cannot delete!!";	
	}
}else{
	$a_return["result"] = "fail:Data cannot delete!!";
}

echo json_encode($a_return);
?>