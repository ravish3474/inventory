<?php
session_start();
require_once('../../db.php');

if( !isset($_POST["rq_id"]) ){
	echo "Invalid parameter.";
	exit();
}
$rq_id = $_POST["rq_id"];

$sql_rq_form = "SELECT order_code,rq_status FROM tbl_rq_form WHERE rq_id='".$rq_id."'; ";
$rs_rq_form = $conn->query($sql_rq_form);
$row_rq_form = $rs_rq_form->fetch_assoc();
$rq_status = $row_rq_form["rq_status"];

$sql_select = "SELECT tbl_rq_form_item.*,fabric.*,cat.cat_name_en FROM tbl_rq_form_item ";
$sql_select .= "LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_select .=" LEFT JOIN cat ON cat.cat_id=fabric.cat_id WHERE tbl_rq_form_item.rq_id=".$rq_id."; ";
$rs_balance = $conn->query($sql_select);

//$tmp_id = date("His");
$n_count = 0;
$fabric_id_list = "";

$grand_used = 0.0;
$grand_total = 0.0;

$count_list = 0;

while($row_balance = $rs_balance->fetch_assoc()){

	if($row_balance["mark_cut_stock"]=="0"){
		if($count_list>0){
			$fabric_id_list .= ",";
		}
		$fabric_id_list .= $row_balance["fabric_id"];
		$count_list++;
	}

	if( $rq_status=="new" || $rq_status=="update"){
		$tmp_id = $row_balance["rq_item_id"];
?>
<tr id="tr_row<?php echo $row_balance["fabric_id"].$tmp_id; ?>">
	<td><?php echo $row_balance["cat_name_en"]; ?></td>
	<td><?php echo $row_balance["fabric_color"]; ?></td>
	<td><?php echo $row_balance["fabric_box"]; ?></td>
	<td><?php echo $row_balance["fabric_no"]; ?></td>
	<td><?php echo number_format($row_balance["fabric_balance"],2); ?> Kg.</td>
	<td>
		<?php
		if($row_balance["mark_cut_stock"]=="0"){
		?>
		<input class="btn btn-danger" type="button" value="Remove" onclick="return removeMaterial(<?php echo $row_balance["fabric_id"]; ?>,<?php echo $tmp_id; ?>);">
		<?php
		}
		?>
	</td>
</tr>
<?php
	}else{

		$row_used = floatval($row_balance["balance_before"])-floatval($row_balance["balance_after"]);
		$row_total = floatval($row_balance["fabric_in_price"])*$row_used;
		$grand_used += $row_used;
		$grand_total += $row_total;
?>
<tr>
	<td><?php echo $row_balance["cat_name_en"]; ?></td>
	<td><?php echo $row_balance["fabric_color"]; ?></td>
	<td><?php echo $row_balance["fabric_box"]; ?></td>
	<td><?php echo $row_balance["fabric_no"]; ?></td>
	<td><?php echo number_format($row_balance["balance_before"],2); ?> Kg.</td>
	<td><?php echo number_format($row_balance["balance_after"],2); ?> Kg.</td>
	<td class="text-right"><?php echo number_format($row_used,2); ?></td>
	<td class="text-right"><?php echo number_format($row_balance["fabric_in_price"],2); ?></td>
	<td class="text-right"><?php echo number_format($row_total,2); ?></td>
	<td>
		<?php 
		echo $row_balance["item_note"]; 

		if($_SESSION["employee_position_id"]=="99"){
			echo '<span style="float:right;" onclick="editByAdminOnly('.$row_balance["rq_item_id"].');" data-toggle="modal" data-target="#showAdminEditModal"><i class="fa fa-pencil-square" aria-hidden="true"></i></span>';
		}
		?>
	</td>
</tr>
<?php
	}
	$n_count++;
}

if( $rq_status=="finish" ){

	$sql_select2 = "SELECT * FROM tbl_rq_form WHERE order_code='".$row_rq_form["order_code"]."' AND is_addon=1 AND rq_status='finish'; ";
	//echo "<tr><td>".$sql_select2."</td></tr>";
	$rs_select2 = $conn->query($sql_select2);

	if($rs_select2->num_rows > 0){

?>
<tr class="subtotal-row">
	<td colspan="6" class="text-right">Sub total:</td>
	<td class="text-right"><?php echo number_format($grand_used,2); ?></td>
	<td colspan="2" class="text-right"><?php echo number_format($grand_total,2); ?></td>
	<td>&nbsp;</td>
</tr>
<?php
		
		while($row_form = $rs_select2->fetch_assoc()){


?>
<tr class="subhead-row">
	<td colspan="10" class="text-center">Add-on date: <?php echo $row_form["rq_date"]; ?> | Finish: <?php echo $row_form["finish_date"]; ?></td>
</tr>
<?php
			$sql_select3 = "SELECT tbl_rq_form_item.*,fabric.*,cat.cat_name_en FROM tbl_rq_form_item ";
			$sql_select3 .= "LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
			$sql_select3 .=" LEFT JOIN cat ON cat.cat_id=fabric.cat_id WHERE tbl_rq_form_item.rq_id=".$row_form["rq_id"]."; ";
			$rs_balance2 = $conn->query($sql_select3);

			$sub_used = 0.0;
			$sub_total = 0.0;

			while($row_balance2 = $rs_balance2->fetch_assoc()){

				$row_used = floatval($row_balance2["balance_before"])-floatval($row_balance2["balance_after"]);
				$row_total = floatval($row_balance2["fabric_in_price"])*$row_used;
				$grand_used += $row_used;
				$grand_total += $row_total;
				$sub_used += $row_used;
				$sub_total += $row_total;
?>
<tr>
	<td><?php echo $row_balance2["cat_name_en"]; ?></td>
	<td><?php echo $row_balance2["fabric_color"]; ?></td>
	<td><?php echo $row_balance2["fabric_box"]; ?></td>
	<td><?php echo $row_balance2["fabric_no"]; ?></td>
	<td><?php echo number_format($row_balance2["balance_before"],2); ?> Kg.</td>
	<td><?php echo number_format($row_balance2["balance_after"],2); ?> Kg.</td>
	<td class="text-right"><?php echo number_format($row_used,2); ?></td>
	<td class="text-right"><?php echo number_format($row_balance2["fabric_in_price"],2); ?></td>
	<td class="text-right"><?php echo number_format($row_total,2); ?></td>
	<td>
		<?php echo $row_balance2["item_note"]; 
		if($_SESSION["employee_position_id"]=="99"){
			echo '<span style="float:right;" onclick="editByAdminOnly('.$row_balance2["rq_item_id"].');" data-toggle="modal" data-target="#showAdminEditModal"><i class="fa fa-pencil-square" aria-hidden="true"></i></span>';
		}
		?>
		
	</td>
</tr>
<?php
			}
?>
<tr class="subtotal-row">
	<td colspan="6" class="text-right">Sub total:</td>
	<td class="text-right"><?php echo number_format($sub_used,2); ?></td>
	<td colspan="2" class="text-right"><?php echo number_format($sub_total,2); ?></td>
	<td>&nbsp;</td>
</tr>
<?php
		}

	}
?>
<tr class="total-row">
	<td colspan="6" class="text-right">Grand total:</td>
	<td class="text-right"><?php echo number_format($grand_used,2); ?></td>
	<td colspan="2" class="text-right"><?php echo number_format($grand_total,2); ?></td>
	<td>&nbsp;</td>
</tr>
<?php
}
?>
<tr style="display: none;"><td colspan="6"><input type="hidden" id="fabric_id_list" value="<?php echo $fabric_id_list; ?>"></td></tr>