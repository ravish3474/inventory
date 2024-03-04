<?php
$a_return["result"] = "";

if(!isset($_POST["search_val"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$sql_select = "SELECT COUNT(*) AS num_found FROM tbl_rq_form WHERE order_code LIKE '%".$_POST["search_val"]."%'; ";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();

$a_return["num_found"] = $row_select["num_found"];
$a_return["result"] = "success";

echo json_encode($a_return);
?>