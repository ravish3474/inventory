<?php
session_start();

require_once('../../db.php');


$a_result = array();

if( !isset($_POST["supplier_id"]) || !isset($_POST["cat_id"]) || ($_POST["color_name"]=="") || ($_POST["fabric_no"]=="") || ($_POST["fabric_in"]=="") || ($_POST["fabric_u_price"]=="") ){
	$a_result["result"] = 'fail';
	$a_result["msg"] = 'Invalid parameter';
	echo json_encode($a_result);
	exit();
}

$new_cat_id = 0;

if($_POST["cat_id"]!="=new="){
	$sql_check = "SELECT fabric_id FROM fabric WHERE cat_id=".$_POST["cat_id"]." AND fabric_color='".base64_decode($_POST["color_name"])."' AND fabric_no='".$_POST["fabric_no"]."' AND fabric_balance>0 ";
	$rs_check = $conn->query($sql_check);
	if($rs_check->num_rows > 0){

		$a_result["result"] = 'fail';
		$a_result["msg"] = 'Duplicate Fabric No. that is not empty.';
		echo json_encode($a_result);
		exit();
	}
}else{
	$insert_cat = "INSERT INTO cat (type_id,cat_code,cat_name_en,cat_name_th) VALUES (1,'".base64_decode($_POST["new_cat_name"])."','".base64_decode($_POST["new_cat_name"])."','-'); ";
	$conn->query($insert_cat);
	$new_cat_id = $conn->insert_id;
}


$fabric_in_total = floatval($_POST["fabric_in"])*floatval($_POST["fabric_u_price"]);

$adj_date = date("Y-m-d H:i:s");
$user_adj_id = $_SESSION["employee_id"];

$tmp_color_name = "";

$sql_insert = "INSERT INTO fabric (supplier_id,cat_id,fabric_color,fabric_no,fabric_box,fabric_in_piece,fabric_adjust,fabric_type_unit,fabric_in_price,fabric_in_total,fabric_balance,fabric_date_create,fabric_user_create) ";
if($new_cat_id!=0){
	$sql_insert .= "VALUES ('".$_POST["supplier_id"]."','".$new_cat_id."','".base64_decode($_POST["new_color_name"])."','".$_POST["fabric_no"]."','".$_POST["fabric_box"]."','0.0','".$_POST["fabric_in"]."',3,'".$_POST["fabric_u_price"]."','".$fabric_in_total."','".$_POST["fabric_in"]."','".$adj_date."','".$user_adj_id."'); ";

	$tmp_color_name = base64_decode($_POST["new_color_name"]);
}else{

	if(base64_decode($_POST["color_name"])!="=new="){

		$sql_insert .= "VALUES ('".$_POST["supplier_id"]."','".$_POST["cat_id"]."','".base64_decode($_POST["color_name"])."','".$_POST["fabric_no"]."','".$_POST["fabric_box"]."','0.0','".$_POST["fabric_in"]."',3,'".$_POST["fabric_u_price"]."','".$fabric_in_total."','".$_POST["fabric_in"]."','".$adj_date."','".$user_adj_id."'); ";

		$tmp_color_name = base64_decode($_POST["color_name"]);
	}else{
		
		$sql_insert .= "VALUES ('".$_POST["supplier_id"]."','".$_POST["cat_id"]."','".base64_decode($_POST["new_color_name"])."','".$_POST["fabric_no"]."','".$_POST["fabric_box"]."','0.0','".$_POST["fabric_in"]."',3,'".$_POST["fabric_u_price"]."','".$fabric_in_total."','".$_POST["fabric_in"]."','".$adj_date."','".$user_adj_id."'); ";

		$tmp_color_name = base64_decode($_POST["new_color_name"]);
	}

}
$conn->query($sql_insert);
$fabric_id = $conn->insert_id;

$tmp_color_id = 0;

$sql_chk = "SELECT color_id FROM tbl_color WHERE color_name='".addslashes($tmp_color_name)."'; ";
$rs_chk = $conn->query($sql_chk);
if($rs_chk->num_rows > 0){
	$row_chk = $rs_chk->fetch_assoc();
	$tmp_color_id = $row_chk["color_id"];
}else{
	$sql_insert3 = "INSERT INTO tbl_color (color_name,date_add) VALUES ('".addslashes($tmp_color_name)."','".$adj_date."'); ";
	$conn->query($sql_insert3);
	$tmp_color_id = $conn->insert_id;
}

$sql_update = "UPDATE fabric SET color_id='".$tmp_color_id."' WHERE fabric_id='".$fabric_id."'; ";
$conn->query($sql_update);

$sql_insert2 = "INSERT INTO tbl_adjust (fabric_id,in_out,adj_value,new_balance,adj_date,user_adj_id) ";
$sql_insert2 .= "VALUES ('".$fabric_id."','IN','".$_POST["fabric_in"]."','".$_POST["fabric_in"]."','".$adj_date."','".$user_adj_id."'); ";

if($conn->query($sql_insert2)){

	$a_result["result"] = 'success';
}

echo json_encode($a_result);
?>