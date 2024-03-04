<?php
if( !isset($_POST["cat_id"]) || !isset($_POST["color_id"]) ){

	$a_result["result"] = "fail";
	$a_result["msg"] = "Invalid parameter.";

	echo json_encode($a_result);
	exit();
}

require_once('../../db.php');

$cat_id = $_POST["cat_id"];
$color_id = $_POST["color_id"];

$a_select_supp = $_POST["select_supp"];
$a_qty_value = $_POST["qty_value"];

for($i=0;$i<sizeof($a_qty_value);$i++){

	if( $a_qty_value[$i]!="" && intval($a_qty_value[$i])!=0 ){

		$sql_insert = "INSERT INTO tbl_f_purchase (cat_id,color_id,supplier_id,fpu_value) VALUES ('".$cat_id."','".$color_id."','".$a_select_supp[$i]."','".$a_qty_value[$i]."'); ";
		$conn->query($sql_insert);

	}

}

$sql_fpurchase = "SELECT SUM(fpu_value) AS sum_forcast_purchase ";
$sql_fpurchase .= "FROM tbl_f_purchase ";
$sql_fpurchase .= "WHERE cat_id='".$cat_id."' AND color_id='".$color_id."' AND mark_ordered<>2 "; //--2 means received fabric that ordered
$sql_fpurchase .= "GROUP BY color_id ";
$rs_fpurchase = $conn->query($sql_fpurchase);
$row_fpurchase = $rs_fpurchase->fetch_assoc();

$a_result["sum_value"] = $row_fpurchase["sum_forcast_purchase"];

$a_result["result"] = "success";
echo json_encode($a_result);
?>
