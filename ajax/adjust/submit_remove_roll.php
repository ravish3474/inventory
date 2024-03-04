<?php
session_start();

require_once('../../db.php');

if( !isset($_POST["r_fabric_id"]) || ($_POST["r_fabric_id"]=="") || !isset($_POST["remark_remove"]) || ($_POST["remark_remove"]=="") ){
	
	echo 'Invalid parameter';
	exit();
}

$fabric_id = $_POST["r_fabric_id"];
$remark = $_POST["remark_remove"];

$adj_date = date("Y-m-d H:i:s");
$user_adj_id = $_SESSION["employee_id"];		

$in_out = "OUT";
$adj_value = $_POST["r_old_balance"];
$new_bal = 0.0;

$sql_insert = "INSERT INTO tbl_adjust (fabric_id,in_out,adj_value,new_balance,adj_date,user_adj_id,remark) ";
$sql_insert .= "VALUES ('".$fabric_id."','".$in_out."','".$adj_value."','".$new_bal."','".$adj_date."','".$user_adj_id."','".$conn->real_escape_string($remark)."'); ";

//echo $sql_insert."<hr>";
if($conn->query($sql_insert)){

	$sql_update = "UPDATE fabric SET fabric_adjust=-".$adj_value.",fabric_balance=0,fabric_user_update=".$user_adj_id." ";
	$sql_update .= "WHERE fabric_id='".$fabric_id."'; ";
	//echo $sql_update."<hr>";
	$conn->query($sql_update);

}

echo 'success';
?>
<script type="text/javascript">
window.parent.successRemove();
</script>