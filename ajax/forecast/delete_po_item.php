<?php
require_once('../../db.php');

$a_return["all_re"] = "no";

$sql_item = "SELECT tbl_f_ordered.supplier_id,tbl_f_ordered_item.cat_id,tbl_f_ordered_item.color_id FROM tbl_f_ordered_item LEFT JOIN tbl_f_ordered ON tbl_f_ordered.for_id=tbl_f_ordered_item.for_id WHERE tbl_f_ordered_item.for_item_id='".$_POST["for_item_id"]."' ";
$rs_item = $conn->query($sql_item);
$row_item = $rs_item->fetch_assoc();

$sql_delete = "DELETE FROM tbl_f_ordered_item WHERE for_item_id='".$_POST["for_item_id"]."'; ";
if($conn->query($sql_delete)){

	$sql_update = "UPDATE tbl_f_purchase SET mark_ordered=0,for_id=NULL WHERE for_id='".$_POST["for_id"]."' AND supplier_id='".$row_item["supplier_id"]."' AND cat_id='".$row_item["cat_id"]."' AND color_id='".$row_item["color_id"]."';";
	$conn->query($sql_update);

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