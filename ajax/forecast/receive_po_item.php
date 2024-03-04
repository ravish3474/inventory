<?php
require_once('../../db.php');

$a_return["all_re"] = "no";

$sql_update = "UPDATE tbl_f_ordered_item SET is_receive=1 WHERE for_item_id='".$_POST["for_item_id"]."'; ";
if($conn->query($sql_update)){

	$sql_select = "SELECT COUNT(*) AS num_data,SUM(is_receive) AS sum_re FROM tbl_f_ordered_item WHERE for_id='".$_POST["for_id"]."' ";
	$rs_select = $conn->query($sql_select);
	$row_select = $rs_select->fetch_assoc();

	$num_data = intval($row_select["num_data"]);
	$sum_re = intval($row_select["sum_re"]);

	$po_status = "new";
	
	if($num_data==$sum_re){
		$po_status = "All received";
		$a_return["all_re"] = "yes";
	}else if( $num_data>$sum_re && $sum_re!=0 ){
		$po_status = "Partially received";
	}

	$sql_update2 = "UPDATE tbl_f_ordered SET po_status='".$po_status."' WHERE for_id='".$_POST["for_id"]."'; ";
	$conn->query($sql_update2);

	$a_return["result"] = "success";
}else{
	$a_return["result"] = "fail";
}

echo json_encode($a_return);
?>