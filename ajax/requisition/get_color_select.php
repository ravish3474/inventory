<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) ){
	echo "Invalid parameter.";
	exit();
}
$cat_id = $_POST["cat_id"];

$sql_select = "SELECT DISTINCT fabric_color FROM fabric WHERE cat_id=".$cat_id." AND fabric_balance>0 AND on_producing=0 ORDER BY fabric_color ASC; ";
$rs_color = $conn->query($sql_select);
while ($row_color = $rs_color->fetch_assoc()) {
	echo '<option value="'.$row_color["fabric_color"].'">'.$row_color['fabric_color'].'</option>';
}

?>