<?php
require_once('../../db.php');

if( !isset($_POST["fabric_id"]) || ($_POST["fabric_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$sql_fabric = "SELECT fabric.fabric_color,cat.cat_name_en ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_fabric .= "WHERE fabric.fabric_id='".$_POST["fabric_id"]."' ";
$rs_fabric = $conn->query($sql_fabric);
$row_fabric = $rs_fabric->fetch_assoc();

?>
<h5 class="text-center"><?php echo $row_fabric["cat_name_en"]." [".$row_fabric["fabric_color"]."]"; ?></h5>
<div class="text-center log-title">Box: <?php echo $_POST["log_box"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No: <?php echo $_POST["log_no"]; ?></div>
<table class="tbl-log" width="100%">
	<tr>
		<th>User</th><th>Adjust</th><th>Value</th><th>From</th><th>To</th><th>Time</th>
	</tr>
<?php
$sum_adj = 0.0;

$sql_adjust = "SELECT tbl_adjust.*,employee.employee_name ";
$sql_adjust .= "FROM tbl_adjust ";
$sql_adjust .= "LEFT JOIN employee ON employee.employee_id=tbl_adjust.user_adj_id ";
$sql_adjust .= "WHERE tbl_adjust.fabric_id=".$_POST["fabric_id"]." ";

$rs_adjust = $conn->query($sql_adjust);

while($row_adjust = $rs_adjust->fetch_assoc()){
?>
	<tr>
		<td><?php echo $row_adjust["employee_name"]; ?></td>
		<td><?php echo $row_adjust["in_out"]; ?></td>
		<td><?php echo $row_adjust["adj_value"]; ?></td>
		<td>
		<?php
		if($row_adjust["in_out"]=="OUT"){
			echo number_format( (floatval($row_adjust["new_balance"])+floatval($row_adjust["adj_value"])), 2 );
			$sum_adj -= $row_adjust["adj_value"];
		}else{
			echo number_format( (floatval($row_adjust["new_balance"])-floatval($row_adjust["adj_value"])), 2 );
			$sum_adj += $row_adjust["adj_value"];
		}
		?>
		</td>
		<td><?php echo $row_adjust["new_balance"]; ?></td>
		<td><?php echo $row_adjust["adj_date"]; ?></td>
	</tr>
<?php
}
?>
	<tr style="font-weight: bold; background-color: #555; color: #FFF;">
		<td colspan="2">Total</td>
		<td><?php echo number_format($sum_adj,2); ?></td>
		<td colspan="3">&nbsp;</td>
	</tr>
</table>