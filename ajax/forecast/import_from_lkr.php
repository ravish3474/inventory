<?php
$a_return["result"] = "";

if(!isset($_POST["search_val"])){
	$a_return["result"] = "fail:Invalid param";
	echo json_encode($a_return);
	exit();
}

require_once('../../db.php');

$sql_inven = "SELECT * FROM tbl_order_lkr_title WHERE order_title='".$_POST["search_val"]."' AND enable=1 ";
$rs_inven = $conn->query($sql_inven);
if($rs_inven->num_rows == 0){

	$conn2 = new mysqli($serverName,$userName,$userPassword,$dbName2);

	$select_lkr = "SELECT order_main_file.*,employee.employee_name,order_main.order_main_code AS folder_name FROM order_main_file LEFT JOIN employee ON order_main_file.order_main_file_user=employee.employee_id LEFT JOIN order_main ON order_main_file.order_main_id=order_main.order_main_id WHERE order_main_file.order_main_file_title='".$_POST["search_val"]."' AND (order_main_file.order_main_file_type='Order Form' OR order_main_file.order_main_file_type='Design'); ";
	$rs_lkr = $conn2->query($select_lkr);
	while($row_lkr = $rs_lkr->fetch_assoc()){

		$insert_sql = "INSERT INTO tbl_order_lkr (order_main_id,file_name,order_title,file_type,user_add,file_date,add_date) ";
		$insert_sql .= " VALUES ('".$row_lkr['order_main_id']."','".$row_lkr['order_main_file_name']."','".$row_lkr['order_main_file_title']."','".$row_lkr['order_main_file_type']."','".$row_lkr["employee_name"]."','".$row_lkr["order_main_file_date"]."','".date("Y-m-d H:i:s")."'); ";
		mysqli_query($conn,$insert_sql);

		$sql_inven_title = "SELECT * FROM tbl_order_lkr_title WHERE order_title='".$row_lkr['order_main_file_title']."' AND folder_name='".$row_lkr['folder_name']."' AND enable=1 ";
		$rs_inven_title = $conn->query($sql_inven_title);
		if($rs_inven_title->num_rows == 0){

			$insert_sql2 = "INSERT INTO tbl_order_lkr_title (order_title,folder_name,add_date) ";
			$insert_sql2 .= " VALUES ('".$row_lkr['order_main_file_title']."','".$row_lkr['folder_name']."','".date("Y-m-d H:i:s")."'); ";
			mysqli_query($conn,$insert_sql2);
		}
	}

	$a_return["result"] = "success";

}else{

	$a_return["result"] = "fail:Duplicate data";
	
}

echo json_encode($a_return);
?>