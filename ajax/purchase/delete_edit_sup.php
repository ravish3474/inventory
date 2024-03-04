<?php
require_once('../../db.php');


$a_return = array();

if(isset($_POST['po_sup_id']) && $_POST['po_sup_id']!=""){
	$sql = "UPDATE tbl_po_supplier SET enable=0 WHERE po_sup_id=".$_POST['po_sup_id'].";" ;
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