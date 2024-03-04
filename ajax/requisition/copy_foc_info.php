<?php
require_once('../../db.php');

if( !isset($_POST["order_code"]) ){
	echo "Invalid parameter.";
	exit();
}
?>
<table width="100%" class="tbl_rq_form">
	<tr>
		<th>Material</th>
		<th>Color</th>
		<th>Box / No. (Balance)</th>
	</tr>
<?php

$order_code = $_POST["order_code"];

$sql_foc_head = "SELECT forecast_id FROM forecast_head WHERE forecast_order='".$order_code."'; ";
$rs_foc_head = $conn->query($sql_foc_head);
while($row_foc_head = $rs_foc_head->fetch_assoc()){

	$sql_foc_detail = "SELECT cat.cat_id,cat.cat_name_en,forecast_detail.forecast_detail_color FROM forecast_detail LEFT JOIN cat ON cat.cat_id=forecast_detail.cat_id WHERE forecast_detail.forecast_id='".$row_foc_head["forecast_id"]."' ORDER BY cat.cat_name_en ASC,forecast_detail.forecast_detail_color ASC; ";
	$rs_foc_detail = $conn->query($sql_foc_detail);
	while($row_foc_detail = $rs_foc_detail->fetch_assoc()){
?>
		<tr>
			<td><?php echo $row_foc_detail["cat_name_en"]; ?></td>
			<td><?php echo $row_foc_detail["forecast_detail_color"]; ?></td>
			<td class="text-center">
				<select class="fabric_select">
				<?php
				$cat_id = $row_foc_detail["cat_id"];
				$color_name = $row_foc_detail["forecast_detail_color"];

				$sql_select = "SELECT fabric_id,fabric_box,fabric_no,fabric_balance FROM fabric WHERE cat_id=".$cat_id." AND fabric_color='".$color_name."' AND fabric_balance>0 AND on_producing=0  ORDER BY fabric_box ASC,fabric_no ASC; ";
				$rs_box_no = $conn->query($sql_select);
				while ($row_box_no = $rs_box_no->fetch_assoc()) {
					echo '<option value="'.$row_box_no["fabric_id"].'">'.$row_box_no['fabric_box'].'/'.$row_box_no['fabric_no'].' ('.$row_box_no['fabric_balance'].' Kg.)</option>';
				}
				?>
				</select>
			</td>
		</tr>
<?php
	}
}
?>
</table>