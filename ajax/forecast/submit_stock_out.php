<?php
if(!isset($_POST["fabric_id"])){
	?>
	<script type="text/javascript">
	alert("Invalid parameter");
	</script>
	<?php
	exit();
}

require_once('../../db.php');

$s_fabric_id = implode(",",$_POST["fabric_id"]);
$a_balance_now = array();

//----Check working in the same time
$chk_fabric = "SELECT fabric_id,fabric_balance FROM fabric WHERE fabric_id IN (".$s_fabric_id."); ";
$rs_chk_fabric = $conn->query($chk_fabric);
while($row_chk_fabric = $rs_chk_fabric->fetch_assoc()){
	$a_balance_now[($row_chk_fabric["fabric_id"])] = $row_chk_fabric["fabric_balance"];
}

$b_detect_same_time_use = false;
for($i=0;$i<sizeof($_POST["tmp_bal"]);$i++){

	if( $a_balance_now[($_POST["fabric_id"][$i])]!=$_POST["tmp_bal"][$i] ){
		$b_detect_same_time_use = true;
		break;
	}

}

if($b_detect_same_time_use){
	?>
	<script type="text/javascript">
	alert("Some of Balance records has been changed by other thread. Please check!!");
	</script>
	<?php
	exit();
}

$a_sql_update = array();

$sql_insert = "INSERT INTO used_detail (used_id,materials_id,type_id,cat_id,used_detail_color,used_detail_no,used_detail_unit_type,used_detail_price,used_detail_used,used_detail_total) VALUES ";

for($i=0;$i<sizeof($_POST["fabric_id"]);$i++){

	if($i!=0){
		$sql_insert .= ",";
	}
	$sql_insert .= "('".$_POST["used_id"]."','".$_POST["fabric_id"][$i]."','".$_POST["type_id"][$i]."','".$_POST["cat_id"][$i]."','".$_POST["used_detail_color"][$i]."','".$_POST["used_detail_no"][$i]."','".$_POST["used_detail_unit_type"][$i]."','".$_POST["used_detail_price"][$i]."','".$_POST["used_mat"][$i]."','".$_POST["used_detail_total"][$i]."')";

	$a_sql_update[] = "UPDATE fabric SET fabric_total=((fabric_used+".$_POST["used_mat"][$i].")*fabric_in_price),fabric_used=(fabric_used+".$_POST["used_mat"][$i]."),fabric_balance=(fabric_balance-".$_POST["used_mat"][$i].") WHERE fabric_id=".$_POST["fabric_id"][$i].";";
}

$conn->query($sql_insert);

foreach($a_sql_update as $tmp_key=>$sql_update){
	$conn->query($sql_update);
}

?>
<script type="text/javascript">
window.parent.submitDataSuccess();
</script>