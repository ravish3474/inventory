<?php
require_once('../../db.php');

$sql_insert = "INSERT INTO tbl_f_ordered (po_number,po_date,supplier,supplier_id,add_date) VALUES ";
$sql_insert .= "('".$_POST["po_number"]."','".$_POST["po_date"]."','".$_POST["supplier_name"]."','".$_POST["supplier_id"]."','".date("Y-m-d H:i:s")."'); ";
if($conn->query($sql_insert)){
	$for_id = $conn->insert_id;

	$a_return["po_number"] = $_POST["po_number"];
	$a_return["po_date"] = $_POST["po_date"];
	$a_return["supplier"] = $_POST["supplier_name"];
	$a_return["for_id"] = $for_id;
	$a_return["result"] = "success";

}else{
	$a_return["result"] = "fail";
	$a_return["msg"] = "Error on update data!";
}

echo json_encode($a_return);
?>