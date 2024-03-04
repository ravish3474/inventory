<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["pac_id"]) || !isset($_POST["fabric_id"]) ){
	echo "fail";
	exit();
}

$pac_id = $_POST["pac_id"];
$fabric_id = $_POST["fabric_id"];

$sql_delete1 = "DELETE FROM tbl_packing_list WHERE fabric_id=".$fabric_id." AND pac_id=".$pac_id;
$conn->query($sql_delete1);

$sql_delete2 = "DELETE FROM fabric WHERE fabric_id=".$fabric_id;
$conn->query($sql_delete2);

echo "success";

?>