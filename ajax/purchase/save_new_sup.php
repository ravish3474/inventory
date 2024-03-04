<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['sup_name']) && $_POST['sup_name']!=""){

	$nationality = $conn->real_escape_string($_POST['nationality']);
	$sup_name = $conn->real_escape_string($_POST['sup_name']);
	$sup_address = $conn->real_escape_string($_POST['sup_address']);
	$sup_tel = $conn->real_escape_string($_POST['sup_tel']);
	$sup_fax = $conn->real_escape_string($_POST['sup_fax']);
	$sup_email = $conn->real_escape_string($_POST['sup_email']);
	$sale_name = $conn->real_escape_string($_POST['sale_name']);
	$sup_tax_id = $conn->real_escape_string($_POST['sup_tax_id']);
	$sup_payment = $conn->real_escape_string($_POST['sup_payment']);

	$sql = "INSERT INTO tbl_po_supplier (nationality,sup_name,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment,date_add) VALUES ('".$nationality."','".$sup_name."','".$sup_address."','".$sup_tel."','".$sup_fax."','".$sup_email."','".$sale_name."','".$sup_tax_id."','".$sup_payment."','".date("Y-m-d H:i:s")."');" ;

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