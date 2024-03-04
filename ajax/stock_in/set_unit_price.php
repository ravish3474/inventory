<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["fabric_id_list"]) || !isset($_POST["new_unit_price"]) ){
	echo "fail";
	exit();
}

$fabric_id_list = $_POST["fabric_id_list"];
$new_unit_price = $_POST["new_unit_price"];
$employee_id = $_SESSION["employee_id"];
$add_date = date("Y-m-d H:i:s");

//$a_uprice = array();
$sql_select = "SELECT fabric_id,fabric_in_price FROM fabric WHERE fabric_id IN (".$fabric_id_list.")";
$rs_select = $conn->query($sql_select);
while($row_select = $rs_select->fetch_assoc()){

	$a_uprice[($row_select["fabric_id"])] = $row_select["fabric_in_price"];

	$sql_insert = "INSERT INTO tbl_adj_uprice (fabric_id,adj_from,adj_to,employee_id,add_date) ";
	$sql_insert .= "VALUES ('".$row_select["fabric_id"]."','".$row_select["fabric_in_price"]."','".$new_unit_price."','".$employee_id."','".$add_date."'); ";
	$conn->query($sql_insert);

	$sql_update = "UPDATE fabric SET fabric_in_price='".$new_unit_price."' WHERE fabric_id='".$row_select["fabric_id"]."'; ";
	$conn->query($sql_update);

	$sql_update2 = "UPDATE fabric SET fabric_in_total=(fabric_in_piece*fabric_in_price) WHERE fabric_id='".$row_select["fabric_id"]."'; ";
	$conn->query($sql_update2);

}

echo "success";

?>