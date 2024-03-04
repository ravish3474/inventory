<?php
if( !isset($_POST["pac_id"]) || ($_POST["pac_id"]=="") ){
	echo '<b>Error: Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$pac_id = $_POST["pac_id"];

$sql_head = "SELECT tbl_packing.*,employee.employee_name,supplier.supplier_name,CONCAT('PAC-',RIGHT(CONCAT('00000',tbl_packing.pac_id),6)) AS pack_no FROM tbl_packing LEFT JOIN employee ON tbl_packing.employee_id=employee.employee_id LEFT JOIN supplier ON supplier.supplier_id=tbl_packing.supplier_id WHERE tbl_packing.pac_id=".$pac_id ;
$rs_head = $conn->query($sql_head);
$row_head = $rs_head->fetch_assoc();

$sql_detail = "SELECT fabric.*,cat.cat_name_en FROM tbl_packing_list LEFT JOIN fabric ON fabric.fabric_id=tbl_packing_list.fabric_id LEFT JOIN cat ON fabric.cat_id=cat.cat_id WHERE tbl_packing_list.pac_id=".$pac_id." ORDER BY tbl_packing_list.pac_list_id ASC";
$rs_detail = $conn->query($sql_detail);

?>
<div class="col-1" ></div>
<div class="col-10 text-center" >
	<h5 align="center">Stock IN</h5>
	<div class="po_head">
		<div class="row">
			<div class="col-4 text-left">PO No. <b><?php echo $row_head["po_no"]; ?></b></div>
			<div class="col-4 text-left">PO Date: <b><?php echo $row_head["po_date"]; ?></b></div>
			<div class="col-4 text-left">INV No. <b><?php echo $row_head["inv_no"]; ?></b></div>
		</div>
		<div class="row">
			<div class="col-4 text-left">Pack No. <b><?php echo $row_head["pack_no"]; ?></b></div>
			<div class="col-4 text-left">Supplier: <b><?php echo $row_head["supplier_name"]; ?></b></div>
			<div class="col-4 text-left">By <b><?php echo $row_head["employee_name"]; ?></b></div>
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

				$sum_piece += $row_detail["fabric_in_piece"];
				$sum_total += $row_detail["fabric_in_total"];
			?>
			<tr <?php if( ($row_detail["fabric_box"]==$_POST["fabric_box"]) && ($row_detail["fabric_no"]==$_POST["fabric_no"]) ){ echo 'class="hilight_roll"'; } ?>>
				<td class="text-center"><?php echo $row_detail["cat_name_en"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_color"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_box"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_no"]; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["fabric_in_price"],2); ?></td>
				<td class="text-right"><?php echo number_format($row_detail["fabric_in_piece"],2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["fabric_in_total"],2); ?></td>
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
