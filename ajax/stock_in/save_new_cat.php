<?php
session_start();
require_once('../../db.php');

$cat_name = base64_decode($_POST["cat_name"]);

$sql_select = "SELECT * FROM cat WHERE cat_name_en='".$cat_name."' ";
$rs_select = $conn->query($sql_select);

$a_result = array();

if($rs_select->num_rows > 0){
	$a_result["result"] = "fail";
	$a_result["msg"] = "Duplicate name!!";
}else{

	$sql_insert = "INSERT INTO cat (type_id,cat_code,cat_name_en,cat_name_th) VALUES ('1','".$cat_name."','".$cat_name."','-');";
	$conn->query($sql_insert);

	$s_new_option = "";
	$sql_cat = "SELECT * FROM cat ORDER BY cat_name_en ASC; ";
	$rs_cat = $conn->query($sql_cat);
	while ($row_cat = $rs_cat->fetch_assoc()) {
		$s_new_option .= '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
	}

	$a_result["result"] = "success";
	$a_result["inner_html"] = $s_new_option;

}

echo json_encode($a_result);
?>
