<?php
if( !isset($_POST["used_id"]) || ($_POST["used_id"]=="") ){
	echo '<b>Error: Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$used_id = $_POST["used_id"];

$sql_head = "SELECT used_head.*,employee.employee_name FROM used_head LEFT JOIN employee ON used_head.used_user=employee.employee_id WHERE used_head.used_id=".$used_id ;
$rs_head = $conn->query($sql_head);
$row_head = $rs_head->fetch_assoc();

$sql_detail = "SELECT used_detail.*,cat.cat_name_en FROM used_detail LEFT JOIN cat ON used_detail.cat_id=cat.cat_id WHERE used_detail.used_id=".$used_id." ORDER BY used_detail.used_detail_id ASC";
$rs_detail = $conn->query($sql_detail);

?>
<div class="col-1" ></div>
<div class="col-10 text-center" >
	<h5 align="center">No-code [Request]</h5>
	<div class="ncode_head">
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">Doc No. <b><?php echo $row_head["used_code"]; ?></b></div>
			<div class="col-4 text-left">Doc Date: <b><?php echo $row_head["used_date"]; ?></b></div>
			<div class="col-2"></div>
		</div>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-4 text-left">Order code: <b><?php echo $row_head["used_order_code"]; ?></b></div>
			<div class="col-4 text-left">By <b><?php echo $row_head["employee_name"]; ?></b></div>
			<div class="col-2"></div>
		</div>
		<?php
		if($row_head["no_order_note"]!=""){
		?>
		<div class="row">
			<div class="col-2"></div>
			<div class="col-8 text-center">Note: <pre><?php echo $row_head["no_order_note"]; ?></pre></div>
			<div class="col-2"></div>
		</div>
		<?php
		}
		?>
	</div>
</div>
<div class="col-1" ></div>

<div class="col-1" ></div>
<div class="col-10" >
	<table width="100%" class="ncode_tbl">
		<thead>
			<tr>
				<th class="text-center">Material</th>
				<th class="text-center">Color</th>
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

				$sum_piece += $row_detail["used_detail_used"];
				$sum_total += $row_detail["used_detail_total"];
			?>
			<tr <?php if( $row_detail["used_detail_no"]==$_POST["fabric_no"] ){ echo 'class="hilight_roll"'; } ?>>
				<td class="text-center"><?php echo $row_detail["cat_name_en"]; ?></td>
				<td class="text-center"><?php echo $row_detail["used_detail_color"]; ?></td>
				<td class="text-center"><?php echo $row_detail["used_detail_no"]; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["used_detail_price"],2); ?></td>
				<td class="text-right"><?php echo number_format($row_detail["used_detail_used"],2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($row_detail["used_detail_total"],2); ?></td>
			</tr>
			<?php
			}
			?>
			<tr class="grand_total_row">
				<td colspan="5" class="text-right"><?php echo number_format($sum_piece,2)." Kg."; ?></td>
				<td class="text-right"><?php echo number_format($sum_total,2); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="col-1" ></div>
