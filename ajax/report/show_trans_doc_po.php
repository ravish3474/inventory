<?php
if( !isset($_POST["po_id"]) || ($_POST["po_id"]=="") ){
	echo '<b>Error: Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$po_id = $_POST["po_id"];

$sql_head = "SELECT po_head.*,employee.employee_name,supplier.supplier_name FROM po_head LEFT JOIN employee ON po_head.po_user=employee.employee_id LEFT JOIN supplier ON supplier.supplier_id=po_head.supplier_id WHERE po_head.po_id=".$po_id ;
$rs_head = $conn->query($sql_head);
$row_head = $rs_head->fetch_assoc();

$sql_detail = "SELECT po_detail.*,cat.cat_name_en FROM po_detail LEFT JOIN cat ON po_detail.cat_id=cat.cat_id WHERE po_detail.po_id=".$po_id." ORDER BY po_detail.po_detail_id ASC";
$rs_detail = $conn->query($sql_detail);

?>
<div class="col-1" ></div>
<div class="col-10 text-center" >
	<h5 align="center">Purchase Order [Stock IN]</h5>
	<div class="po_head">
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">PO No. <b><?php echo $row_head["po_no"]; ?></b></div>
			<div class="col-4 text-left">PO Date: <b><?php echo $row_head["po_date"]; ?></b></div>
			<div class="col-2"></div>
		</div>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">Supplier: <b><?php echo $row_head["supplier_name"]; ?></b></div>
			<div class="col-4 text-left">By <b><?php echo $row_head["employee_name"]; ?></b></div>
			<div class="col-2"></div>
		</div>
	</div>
</div>
<div class="col-1" ></div>

<div class="col-1" ></div>
<div class="col-10" >
	<table width="100%" class="po_tbl">
		<thead>
			<tr>
				<th class="text-center">Material</th>
				<th class="text-center">Color</th>
				<th class="text-center">Box</th>
				<th class="text-center">No.</th>
				<th class="text-center">Unit price</th>
				<th class="text-center">Stock IN</th>
				<th class="text-center">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sum_piece = 0.0;
			$sum_total = 0.0;

			while($row_detail = $rs_detail->fetch_assoc()){

				$sum_piece += $row_detail["po_detail_piece"];
				$sum_total += $row_detail["po_detail_total"];
			?>
			<tr <?php if( ($row_detail["po_detail_box"]==$_POST["fabric_box"]) && ($row_detail["po_detail_no"]==$_POST["fabric_no"]) ){ echo 'class="hilight_roll"'; } ?>>
				<td class="text-center"><?php echo $row_detail["cat_name_en"]; ?></td>
				<td class="text-center"><?php echo $row_detail["po_detail_color"]; ?></td>
				<td class="text-center"><?php echo $row_detail["po_detail_box"]; ?></td>
				<td class="text-center"><?php echo $row_detail["po_detail_no"]; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["po_detail_price"],2); ?></td>
				<td class="text-right"><?php echo number_format($row_detail["po_detail_piece"],2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["po_detail_total"],2); ?></td>
			</tr>
			<?php
			}
			?>
			<tr class="grand_total_row">
				<td colspan="6" class="text-right"><?php echo number_format($sum_piece,2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($sum_total,2); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="col-1" ></div>
