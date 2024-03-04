<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || !isset($_POST["color_name"]) ){
	echo "Invalid parameter.";
	exit();
}
$cat_id = $_POST["cat_id"];
$color_name = $_POST["color_name"];

$sql_select = "SELECT DISTINCT fabric_box FROM fabric WHERE cat_id=".$cat_id." AND fabric_color='".addslashes($color_name)."' AND on_producing=1  ORDER BY fabric_box ASC; ";
$rs_box = $conn->query($sql_select);
while ($row_box = $rs_box->fetch_assoc()) {
	echo '<option value="'.$row_box["fabric_box"].'">'.$row_box['fabric_box'].'</option>';
}

?>