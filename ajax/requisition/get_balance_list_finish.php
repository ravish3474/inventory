<?php
require_once('../../db.php');

if( !isset($_POST["rq_id"]) ){
	echo "Invalid parameter.";
	exit();
}
$rq_id = $_POST["rq_id"];

$sql_select = "SELECT tbl_rq_form_item.*,fabric.*,cat.cat_name_en FROM tbl_rq_form_item ";
$sql_select .= "LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_select .=" LEFT JOIN cat ON cat.cat_id=fabric.cat_id WHERE tbl_rq_form_item.rq_id=".$rq_id."; ";
$rs_balance = $conn->query($sql_select);

$not_mark_cut = 0;

while($row_balance = $rs_balance->fetch_assoc()){

?>
<tr>
	<td><?php echo $row_balance["cat_name_en"]; ?></td>
	<td><?php echo $row_balance["fabric_color"]; ?></td>
	<td><?php echo $row_balance["fabric_box"]; ?></td>
	<td><?php echo $row_balance["fabric_no"]; ?></td>
	<td><?php echo number_format($row_balance["balance_before"],2); ?> Kg.</td>
	<td>
		<?php
		if($row_balance["mark_cut_stock"]=="0"){
			$not_mark_cut++;
		?>
		<input type="hidden" name="rq_item_id[]" value="<?php echo $row_balance["rq_item_id"]; ?>">
		<input type="hidden" name="fabric_id[]" value="<?php echo $row_balance["fabric_id"]; ?>">
		<input type="hidden" name="before_bal[]" value="<?php echo $row_balance["balance_before"]; ?>">
		<input class="input_after_bal" style="width:100px;" type="number" name="after_bal[]" step="0.01" min="0" max="<?php echo $row_balance["balance_before"]; ?>"> Kg.
		<?php
		}else{
			echo number_format($row_balance["balance_after"],2)." Kg.";
		}
		?>
	</td>
	<td>
		<?php
		if($row_balance["mark_cut_stock"]=="0"){
		?>
		<input type="text" name="item_note[]" maxlength="100">
		<?php
		}else{
			echo $row_balance["item_note"];
		}
		?>
	</td>
</tr>
<?php

}
?>
<tr style="display:none;"><td colspan="7"><input type="hidden" id="not_mark_cut" value="<?php echo $not_mark_cut; ?>"></td></tr>