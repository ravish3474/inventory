<?php
session_start();
require_once('../../db.php');

$s_period = $_POST["s_period"];

$start_date = date("Y-m-d",strtotime("-".$s_period." days",strtotime(date("Y-m-d"))));

?>
<table class="tbl-f-ordered">
	<tr>
		<th>#</th><th>PO No.</th><th>PO Date</th><th>Supplier</th><th>Total Fabric</th><th>Total QTY</th><th>Status</th><th>Action</th>
	</tr>
	<?php
	$count_row = 1;
	$sql_fordered = "SELECT tbl_f_ordered.*,COUNT(tbl_f_ordered_item.for_item_id) AS fabric_num,SUM(tbl_f_ordered_item.qty) AS qty_sum FROM tbl_f_ordered LEFT JOIN tbl_f_ordered_item ON tbl_f_ordered.for_id=tbl_f_ordered_item.for_id WHERE po_date>='".$start_date."' GROUP BY tbl_f_ordered.for_id ORDER BY tbl_f_ordered.add_date DESC; ";
	$rs_fordered = $conn->query($sql_fordered);
	while ($row_fordered = $rs_fordered->fetch_assoc()){
		
	?>
	<tr>
		<td><?php echo $count_row; ?></td>
		<td><?php echo $row_fordered["po_number"]; ?></td>
		<td><?php echo $row_fordered["po_date"]; ?></td>
		<td><?php echo $row_fordered["supplier"]; ?></td>
		<td><?php echo $row_fordered["fabric_num"]; ?></td>
		<td><?php echo $row_fordered["qty_sum"]; ?></td>
		<td><?php echo $row_fordered["po_status"]; ?></td>
		<td>
			<?php if($row_fordered["po_status"]!="All received"){ ?>
			<button class="btn btn-primary" onclick="return viewItem(<?php echo $row_fordered["for_id"]; ?>);" data-toggle="modal" data-target="#addPOModal">Manage item</button>
			<?php }else{ ?>
			<button class="btn btn-info" onclick="return viewItemInfo(<?php echo $row_fordered["for_id"]; ?>);" data-toggle="modal" data-target="#addPOModal">View</button>
			<?php } ?>
			<?php if( in_array(intval($_SESSION['employee_position_id']), array(4,99)) && ($row_fordered["fabric_num"]=="0") ){ //---Only Administrator and Purchase Level ?>
			&nbsp;&nbsp;
			<button class="btn btn-danger" onclick="return deleteRow(<?php echo $row_fordered["for_id"]; ?>);" title="Delete row">Delete</button>
			<?php } ?>
		</td>
	</tr>
	<tbody id="tbo_<?php echo $row_fordered["for_id"]; ?>"></tbody>
	<?php
		
		$count_row++;
	}
	?>
</table>