<?php
$a_return["result"] = "";

if(!isset($_POST["search_val"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$conn2 = new mysqli($serverName,$userName,$userPassword,$dbName2);

$sql_select = "SELECT COUNT(*) AS num_found FROM order_main_file WHERE order_main_file_title='".$_POST["search_val"]."' AND (order_main_file_type='Order Form' OR order_main_file_type='Design'); ";
$rs_select = $conn2->query($sql_select);
$row_select = $rs_select->fetch_assoc();

$a_return["num_found"] = 0;

if(intval($row_select["num_found"])>0){
	$a_return["inner_text"] = ' <button type="button" class="btn btn-success" onclick="importData();">Import</button>';
	$a_return["num_found"] = $row_select["num_found"];
}
$a_return["result"] = "success";

echo json_encode($a_return);
?>