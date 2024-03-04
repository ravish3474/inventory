<?php
if( !isset($_POST["cat_id"]) || !isset($_POST["color_id"]) ){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$cat_id = $_POST["cat_id"];
$color_id = $_POST["color_id"];

$sql_select = "SELECT * FROM forecast_detail ";
$sql_select .= " LEFT JOIN forecast_head ON forecast_detail.forecast_id=forecast_head.forecast_id ";
$sql_select .= " WHERE forecast_detail.cat_id='".$cat_id."' AND forecast_detail.color_id='".$color_id."' AND forecast_head.is_produced=0 ";
$sql_select .= " ORDER BY forecast_head.forecast_date ASC; ";
$rs_select = $conn->query($sql_select);

?>
<table class="tbl_show_fc_order_detail">
	<tr>
		<th>#</th><th>Date</th><th>Order code</th><th>By</th><th>QTY(Kg)</th>
	</tr>
	<?php 
	$count_row = 1;
	$f_sum_qty = 0.0;
	while( $row_select = $rs_select->fetch_assoc() ){
	?>
	<tr>
		<td><?php echo $count_row; ?></td>
		<td><?php echo $row_select["forecast_date"]; ?></td>
		<td><?php echo $row_select["forecast_order"]; ?></td>
		<td><?php echo $row_select["forecast_user"]; ?></td>
		<td style="text-align: right;"><?php echo $row_select["forecast_detail_used"]; ?></td>
	</tr>
	<?php
		$f_sum_qty += floatval($row_select["forecast_detail_used"]);
		$count_row++;
	}
	?>
	<tr class="total_row">
		<td colspan="4">Total</td>
		<td style="text-align: right;"><?php echo number_format($f_sum_qty,2); ?></td>
	</tr>
</table>