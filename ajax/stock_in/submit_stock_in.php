<?php
session_start();

if(!isset($_SESSION["employee_id"])){
?>
<script type="text/javascript">
window.parent.submitFail("Session expired please refresh page and try again.");
</script>
<?php
	exit();
}

if( !isset($_POST["for_id"]) || $_POST["for_id"]=="" ){
?>
<script type="text/javascript">
window.parent.submitFail("Error: Invalid parameter!");
</script>
<?php
	exit();
}

require_once('../../db.php');

//$po_no = $_POST["new_po_no"];
$inv_no = $_POST["new_inv_no"];
$supplier_id = $_POST["supplier_id"];
//$po_date = $_POST["new_po_date"];
$employee_id = $_SESSION["employee_id"];
$add_date = date("Y-m-d H:i:s");

$for_id = $_POST["for_id"];
$sql_select = "SELECT * FROM tbl_f_ordered WHERE for_id='".$for_id."'; ";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();

$po_no = $row_select["po_number"];
$po_date = $row_select["po_date"];

$sql_insert1 = "INSERT INTO tbl_packing (po_no,inv_no,supplier_id,po_date,employee_id,add_date) ";
$sql_insert1 .= "VALUES ('".$po_no."','".$inv_no."','".$supplier_id."','".$po_date."','".$employee_id."','".$add_date."'); ";
$conn->query($sql_insert1);
$pac_id = $conn->insert_id;

$a_tmp_color = array();
$tmp_color = "";
$tmp_color_id = 0;
for($j=0;$j<sizeof($_POST["color_name"]);$j++){

	if($_POST["color_name"][$j]!=$tmp_color){
		$tmp_color = $_POST["color_name"][$j];

		$chk_sql = "SELECT color_id FROM tbl_color WHERE color_name='".addslashes($tmp_color)."' LIMIT 0,1; ";
		$rs_chk = $conn->query($chk_sql);

		if($rs_chk->num_rows==0){

			$sql_insert4 = "INSERT INTO tbl_color (color_name,date_add) VALUES ('".addslashes($tmp_color)."','".$add_date."'); ";
			$conn->query($sql_insert4);
			$tmp_color_id = $conn->insert_id;

		}else{

			$row_chk = $rs_chk->fetch_assoc();
			$tmp_color_id = $row_chk["color_id"];

		}
	}

	$a_tmp_color[$j] = $tmp_color_id;

}

for($i=0;$i<sizeof($_POST["cat_id"]);$i++){

	$sql_insert2 = "INSERT INTO fabric (supplier_id,cat_id,color_id,fabric_color,fabric_no,fabric_box,fabric_in_piece,fabric_type_unit,fabric_in_price,fabric_in_total,fabric_balance,fabric_date_create,fabric_user_create,new_form) ";
	$sql_insert2 .= "VALUES ('".$supplier_id."','".$_POST["cat_id"][$i]."','".$a_tmp_color[$i]."','".$_POST["color_name"][$i]."','".$_POST["roll_no"][$i]."','".$_POST["box_name"][$i]."','".$_POST["amount_in"][$i]."','3','".$_POST["uprice_in"][$i]."','".$_POST["total_in"][$i]."','".$_POST["amount_in"][$i]."','".$add_date."','".$employee_id."','1');";

	$conn->query($sql_insert2);
	$fabric_id = $conn->insert_id;

	$sql_insert3 = "INSERT INTO tbl_packing_list (pac_id,fabric_id) VALUES ('".$pac_id."','".$fabric_id."');";
	$conn->query($sql_insert3);

}

?>
<script type="text/javascript">
window.parent.submitSuccess();
</script>