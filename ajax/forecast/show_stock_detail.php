<?php
if( !isset($_POST["cat_id"]) || !isset($_POST["color_id"]) || !isset($_POST["supp_id"]) ){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$cat_id = $_POST["cat_id"];
$color_id = $_POST["color_id"];
$supp_id = $_POST["supp_id"];

$sql_select = "SELECT * FROM fabric ";
$sql_select .= " LEFT JOIN supplier ON fabric.supplier_id=supplier.supplier_id ";
$sql_select .= " WHERE fabric.cat_id='".$cat_id."' AND fabric.color_id='".$color_id."' AND fabric.fabric_balance<>0 ";
if($supp_id!="all"){
	$sql_select .= " AND fabric.supplier_id='".$supp_id."' ";
}
$sql_select .= " ORDER BY fabric.fabric_date_create ASC; ";
$rs_select = $conn->query($sql_select);

?>
<table class="tbl_show_stock_detail">
	<tr>
		<th>#</th><th>Date IN</th><th>Supplier</th><th>Roll No.</th><th>Balance</th>
	</tr>
	<?php 
	$count_row = 1;
	$f_sum_bal = 0.0;
	while( $row_select = $rs_select->fetch_assoc() ){
	?>
	<tr>
		<td><?php echo $count_row; ?></td>
		<td><?php echo date("Y-m-d",strtotime($row_select["fabric_date_create"])); ?></td>
		<td><?php echo $row_select["supplier_name"]; ?></td>
		<td><?php echo (($row_select["fabric_box"]!="")?$row_select["fabric_box"]."/":"").$row_select["fabric_no"]; ?></td>
		<td style="text-align: right;"><?php echo $row_select["fabric_balance"]; ?></td>
	</tr>
	<?php
		$f_sum_bal += floatval($row_select["fabric_balance"]);
		$count_row++;
	}
	?>
	<tr class="total_row">
		<td colspan="4">Total</td>
		<td style="text-align: right;"><?php echo number_format($f_sum_bal,2); ?></td>
	</tr>
</table>