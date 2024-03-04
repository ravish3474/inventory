<?php
session_start();
if( !isset($_POST["cat_id"]) || !isset($_POST["color_id"]) ){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$cat_id = $_POST["cat_id"];
$color_id = $_POST["color_id"];

$sql_select = "SELECT * FROM tbl_f_purchase ";
$sql_select .= " LEFT JOIN supplier ON supplier.supplier_id=tbl_f_purchase.supplier_id ";
$sql_select .= " WHERE tbl_f_purchase.cat_id='".$cat_id."' AND tbl_f_purchase.color_id='".$color_id."' AND tbl_f_purchase.mark_ordered<>2 ";
$sql_select .= " ORDER BY tbl_f_purchase.mark_ordered ASC; ";
$rs_select = $conn->query($sql_select);

?>
<style type="text/css">
	.admin_edit_col{
		border-width: 0px !important;
		background-color: #FFF !important;
	}
	.admin_edit_col i{
		cursor: pointer;
	}
</style>
<table class="tbl_show_fc_purchase_detail">
	<tr>
		<th>#</th><th>Supplier</th><th>Status</th><th>QTY(Kg)</th>
		<?php
		if( in_array(intval($_SESSION['employee_position_id']), array(1,99,4)) ){
			?>
			<th class="admin_edit_col"></th>
			<?php
		}
		?>
	</tr>
	<?php 
	$count_row = 1;
	$f_sum_qty = 0.0;
	while( $row_select = $rs_select->fetch_assoc() ){
		$show_status = "";
		switch($row_select["mark_ordered"]){
			case "0" : $show_status = "Not ordered yet"; break;
			case "1" : $show_status = "Already ordered"; break;
		}
	?>
	<tr>
		<td><?php echo $count_row; ?></td>
		<td><?php echo $row_select["supplier_name"]; ?></td>
		<td><?php echo $show_status; ?></td>
		<td style="text-align: right;"><?php echo number_format($row_select["fpu_value"],2); ?></td>
		<?php
		if( in_array(intval($_SESSION['employee_position_id']), array(1,99,4)) ){
			
			if($row_select["mark_ordered"]=="0"){
			?>
			<td class="admin_edit_col">
			<span style="color:#E55; font-size: 19px;">
				<i class="fa fa-times" onclick="return deleteForcastPurchase(<?php echo $row_select["fpu_id"]; ?>,<?php echo $color_id; ?>);"></i>
			</span>
			</td>
			<?php
			}else{
			?>
			<th class="admin_edit_col"></th>
			<?php
			}
			
		}
		?>
	</tr>
	<?php
		$f_sum_qty += floatval($row_select["fpu_value"]);
		$count_row++;
	}
	?>
	<tr class="total_row">
		<td colspan="3">Total</td>
		<td id="inside_detail_total<?php echo $color_id; ?>" style="text-align: right;"><?php echo number_format($f_sum_qty,2); ?></td>
		<?php
		if( in_array(intval($_SESSION['employee_position_id']), array(1,99,4)) ){
			?>
			<td class="admin_edit_col"></td>
			<?php
		}
		?>
	</tr>
</table>