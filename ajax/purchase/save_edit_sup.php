<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['po_sup_id']) && $_POST['po_sup_id']!=""){

	$nationality = $conn->real_escape_string($_POST['nationality']);
	$sup_name = $conn->real_escape_string($_POST['sup_name']);
	$sup_address = $conn->real_escape_string($_POST['sup_address']);
	$sup_tel = $conn->real_escape_string($_POST['sup_tel']);
	$sup_fax = $conn->real_escape_string($_POST['sup_fax']);
	$sup_email = $conn->real_escape_string($_POST['sup_email']);
	$sale_name = $conn->real_escape_string($_POST['sale_name']);
	$sup_tax_id = $conn->real_escape_string($_POST['sup_tax_id']);
	$sup_payment = $conn->real_escape_string($_POST['sup_payment']);

	$sql = "UPDATE tbl_po_supplier SET sup_name='".$sup_name."',nationality='".$nationality."',sup_address='".$sup_address."',sup_tel='".$sup_tel."',";
	$sql .= "sup_fax='".$sup_fax."',sup_email='".$sup_email."',sale_name='".$sale_name."',sup_tax_id='".$sup_tax_id."',sup_payment='".$sup_payment."' ";
	$sql .= " WHERE po_sup_id=".$_POST['po_sup_id'].";" ;

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