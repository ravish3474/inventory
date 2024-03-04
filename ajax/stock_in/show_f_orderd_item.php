<?php
if( !isset($_POST["for_id"]) ){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$for_id = $_POST["for_id"];

$sql_select = "SELECT tbl_f_ordered_item.*,cat.cat_id,cat.cat_name_en FROM tbl_f_ordered_item ";
$sql_select .= " LEFT JOIN cat ON cat.cat_id=tbl_f_ordered_item.cat_id ";
$sql_select .= " WHERE tbl_f_ordered_item.for_id='".$for_id."' ";
$sql_select .= " ORDER BY tbl_f_ordered_item.is_receive ASC,tbl_f_ordered_item.for_item_id ASC; ";
$rs_select = $conn->query($sql_select);

?>
<table class="tbl_show_fc_order_detail" style="width: 100%; margin-top: 15px;">
	<tr>
		<th>#</th><th>Fabric</th><th>Color</th><th>Unit price</th><th>Rolls</th><th>Start No.</th><th>Action</th>
		<th class="head_set_recieve">Order(Kg)</th><th class="head_set_recieve">Receive(Kg)</th><th class="head_set_recieve">Set</th>
	</tr>
	<?php 
	$count_row = 1;
	//$f_sum_qty = 0.0;
	while( $row_select = $rs_select->fetch_assoc() ){
	?>
	<tr <?php if($row_select["is_receive"]=="1"){ echo 'class="show_row_received"'; } ?>>
		<td><?php echo $count_row; ?></td>
		<td><?php echo $row_select["cat_name_en"]; ?></td>
		<td><?php echo $row_select["color"]; ?></td>

		<td>
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<input type="number" id="unit_price<?php echo $row_select["for_item_id"]; ?>" name="unit_price[]" value="" min="0">
			<?php
			}
			?>
		</td>
		<td>
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<select name="num_roll[]" id="num_roll<?php echo $row_select["for_item_id"]; ?>">
				<?php 
				for($i=1;$i<=80;$i++){
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
				?>
			</select>
			<?php
			}
			?>
		</td>
		<td>
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<input type="number" id="roll_start<?php echo $row_select["for_item_id"]; ?>" name="roll_start[]" value="" min="0">
			<?php
			}
			?>
		</td>
		<td>
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<button type="button" class="btn btn-primary" onclick="return addRollsBySelectPO(<?php echo $row_select["cat_id"].",'".$row_select["cat_name_en"]."','".$row_select["color"]."',".$row_select["for_item_id"]; ?>);">Add</button>
			<?php
			}
			?>
		</td>
		<td class="body_set_recieve" style="text-align: right;"><?php echo $row_select["qty"]; ?></td>
		<td class="body_set_recieve">
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<input type="number" id="receive_val<?php echo $row_select["for_item_id"]; ?>" value="" min="0" max="<?php echo floatval($row_select["qty"]); ?>">
			<?php
			}else{
				echo $row_select["qty"];
			}
			?>
		</td>
		<td class="body_set_recieve">
			<?php
			if($row_select["is_receive"]=="0"){
			?>
			<button type="button" class="btn btn-info" onclick="return setReceive(<?php echo $row_select["for_item_id"]; ?>);">Receive</button>
			<?php
			}
			?>
		</td>
	</tr>
	<?php
		//$f_sum_qty += floatval($row_select["forecast_detail_used"]);
		$count_row++;
	}
	?>
	
</table>