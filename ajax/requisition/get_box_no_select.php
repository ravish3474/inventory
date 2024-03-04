<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) ){
	echo "Invalid parameter.";
	exit();
}
$cat_id = $_POST["cat_id"];
$color_name = $_POST["color_name"];

$sql_select = "SELECT fabric_id,fabric_box,fabric_no,fabric_balance FROM fabric WHERE cat_id=".$cat_id." AND fabric_color='".addslashes($color_name)."' AND fabric_balance>0 AND on_producing=0  ORDER BY fabric_box ASC,fabric_no ASC; ";
$rs_box_no = $conn->query($sql_select);
while ($row_box_no = $rs_box_no->fetch_assoc()) {
	echo '<option value="'.$row_box_no["fabric_id"].'">'.$row_box_no['fabric_box'].'/'.$row_box_no['fabric_no'].' ('.$row_box_no['fabric_balance'].' Kg.)</option>';
}

?>