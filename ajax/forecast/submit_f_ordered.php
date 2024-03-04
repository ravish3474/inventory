<?php

require_once('../../db.php');

$supplier_id = $_POST["select_supplier_id"];
$po_number = $_POST["po_number_add"];
$po_date = $_POST["po_date_add"];

$a_fpu_id = $_POST["a_fpu_id"];

$sql_supp_name = "SELECT supplier_name FROM supplier WHERE supplier_id='".$supplier_id."'; ";
$rs_supp_name = $conn->query($sql_supp_name);
$row_supp_name = $rs_supp_name->fetch_assoc();
$supplier_name = $row_supp_name["supplier_name"];

$date_now = date("Y-m-d");

$sql_insert = "INSERT INTO tbl_f_ordered (po_number,po_date,supplier,supplier_id,add_date) VALUES ('".$po_number."','".$po_date."','".$supplier_name."','".$supplier_id."','".$date_now."'); ";
$conn->query($sql_insert);
$for_id = $conn->insert_id;

for($i=0;$i<sizeof($a_fpu_id);$i++){

	$sql_insert2 = "INSERT INTO tbl_f_ordered_item (for_id,cat_id,color,color_id,qty) SELECT '".$for_id."',tbl_f_purchase.cat_id,tbl_color.color_name,tbl_f_purchase.color_id,tbl_f_purchase.fpu_value ";
	$sql_insert2 .= " FROM tbl_f_purchase LEFT JOIN tbl_color ON tbl_f_purchase.color_id=tbl_color.color_id WHERE fpu_id='".$a_fpu_id[$i]."'; ";
	$conn->query($sql_insert2);

	$sql_update = "UPDATE tbl_f_purchase SET mark_ordered=1,for_id='".$for_id."' WHERE fpu_id='".$a_fpu_id[$i]."'; ";
	$conn->query($sql_update);
}

$a_result["result"] = "success";
echo json_encode($a_result);
?>
