<?php
if( !isset($_POST["rq_id"]) || ($_POST["rq_id"]=="") ){
	echo '<b>Error: Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$rq_id = $_POST["rq_id"];

$sql_head = "SELECT tbl_rq_form.*,employee.employee_name FROM tbl_rq_form LEFT JOIN employee ON tbl_rq_form.employee_id=employee.employee_id WHERE tbl_rq_form.rq_id=".$rq_id ;
$rs_head = $conn->query($sql_head);
$row_head = $rs_head->fetch_assoc();

$sql_detail = "SELECT tbl_rq_form_item.*,cat.cat_name_en,fabric.fabric_color,fabric.fabric_box,fabric.fabric_no,fabric.fabric_in_price FROM tbl_rq_form_item ";
$sql_detail .= "LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_detail .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_detail .= "WHERE tbl_rq_form_item.rq_id=".$rq_id." ORDER BY tbl_rq_form_item.rq_item_id ASC";
$rs_detail = $conn->query($sql_detail);

?>
<div class="col-1" ></div>
<div class="col-10 text-center" >
	<h5 align="center">Material [Request]</h5>
	<div class="rq_head">
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">Order code: <b><?php echo $row_head["order_code"].(($row_head["is_addon"]=="1")?" (Add-on)":""); ?></b></div>
			<div class="col-4 text-left">Doc Date: <b><?php echo $row_head["rq_date"]; ?></b></div>
			<div class="col-2"></div>
		</div>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">Status: <b><?php echo strtoupper($row_head["rq_status"]); ?></b></div>
			<div class="col-4 text-left">By <b><?php echo $row_head["employee_name"]; ?></b></div>
			<div class="col-2"></div>
		</div>
	</div>
</div>
<div class="col-1" ></div>

<div class="col-1" ></div>
<div class="col-10" >
	<table width="100%" class="rq_tbl">
		<thead>
			<tr>
				<th class="text-center">Material</th>
				<th class="text-center">Color</th>
				<th class="text-center">Box</th>
				<th class="text-center">No.</th>
				<th class="text-center">Unit price</th>
				<th class="text-center">Used</th>
				<th class="text-center">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sum_piece = 0.0;
			$sum_total = 0.0;

			while($row_detail = $rs_detail->fetch_assoc()){

				$sum_piece += $row_detail["used"];

				$row_total = ($row_detail["used"]*$row_detail["fabric_in_price"]);
				$sum_total += $row_total;
			?>
			<tr <?php if( ($row_detail["fabric_box"]==$_POST["fabric_box"]) && ($row_detail["fabric_no"]==$_POST["fabric_no"]) ){ echo 'class="hilight_roll"'; } ?>>
				<td class="text-center"><?php echo $row_detail["cat_name_en"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_color"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_box"]; ?></td>
				<td class="text-center"><?php echo $row_detail["fabric_no"]; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["fabric_in_price"],2); ?></td>
				<td class="text-right"><?php echo number_format($row_detail["used"],2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($row_total,2); ?></td>
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
