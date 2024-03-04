<?php
session_start();
require_once('../../db.php');

$sql_select = "SELECT * FROM tbl_f_ordered WHERE for_id='".$_POST["for_id"]."'; ";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();

$a_return["po_number"] = $row_select["po_number"];
$a_return["po_date"] = $row_select["po_date"];
$a_return["supplier"] = $row_select["supplier"];
$a_return["for_id"] = $row_select["for_id"];

$sql_select2 = "SELECT tbl_f_ordered_item.*,cat.cat_name_en FROM tbl_f_ordered_item LEFT JOIN cat ON cat.cat_id=tbl_f_ordered_item.cat_id WHERE tbl_f_ordered_item.for_id='".$_POST["for_id"]."' ; ";
$rs_select2 = $conn->query($sql_select2);

$count_row = 1;
$a_return["inner_table"] = "";
while($row_select2 = $rs_select2->fetch_assoc()){

	$a_return["inner_table"] .= '<tr><td>'.$count_row.'</td><td>'.$row_select2["cat_name_en"].'</td><td>'.$row_select2["color"].'</td><td >'.$row_select2["qty"].'</td>';
	$a_return["inner_table"] .= '<td >';

	if( in_array(intval($_SESSION['employee_position_id']), array(4,99)) && ($row_select2["is_receive"]=="0") ){ //---Only Administrator and Purchase Level

		/*if($row_select2["is_receive"]=="0"){
			$a_return["inner_table"] .= '<button class="btn btn-info" onclick="return setReceiveItem('.$row_select2["for_id"].','.$row_select2["for_item_id"].');">Received</button>&nbsp;&nbsp;&nbsp;';
		}*/
		$a_return["inner_table"] .= '<button class="btn btn-danger" onclick="return deleteItem('.$row_select2["for_id"].','.$row_select2["for_item_id"].');">Delete</button>';
	}else{
		$a_return["inner_table"] .= '<div class="status_received" >RECEIVED</div>';
	}

	$a_return["inner_table"] .= '</td></tr>';

	$count_row++;
}

$a_return["result"] = "success";


echo json_encode($a_return);
?>