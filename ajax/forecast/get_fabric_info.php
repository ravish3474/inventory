<?php
$a_return["result"] = "";

if(!isset($_POST["fabric_id"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$sql_select = "SELECT * FROM fabric WHERE fabric_id=".$_POST["fabric_id"]."; ";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();

$a_return = $row_select;
$a_return["result"] = "success";

echo json_encode($a_return);
?>