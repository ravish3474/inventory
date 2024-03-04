<?php
if( !isset($_POST["fabric_id"]) || ($_POST["fabric_id"]=="") || !isset($_POST["trans_date"]) || ($_POST["trans_date"]=="") ){
	
	echo '<b>Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$fabric_id = $_POST["fabric_id"];

$sql_fabric = "SELECT fabric.*,cat.cat_name_en FROM fabric LEFT JOIN cat ON cat.cat_id=fabric.cat_id ";
$sql_fabric .= "WHERE fabric.fabric_id='".$fabric_id."' ";
$rs_fabric = $conn->query($sql_fabric);
$row_fabric = $rs_fabric->fetch_assoc();

$sql_data = "SELECT used_head.used_date AS trans_date,'NO-CODE' AS trans_process,'OUT' AS in_out,used_detail.used_detail_used AS trans_value,used_detail.used_id AS ref_id FROM used_detail ";
$sql_data .= "LEFT JOIN used_head ON used_detail.used_id=used_head.used_id WHERE materials_id=".$fabric_id." AND used_detail.used_detail_used<>0 AND used_head.used_date LIKE '".$_POST["trans_date"]."%' ";
$sql_data .= "UNION ";
$sql_data .= "SELECT cut_date AS trans_date,'RQ' AS trans_process,'OUT' AS in_out,used AS trans_value,rq_id AS ref_id FROM tbl_rq_form_item WHERE fabric_id=".$fabric_id." AND mark_cut_stock=1 AND cut_date LIKE '".$_POST["trans_date"]."%' ";
$sql_data .= "ORDER BY trans_date ASC ";

$rs_data = $conn->query($sql_data);

//echo $sql_data;
?>
<div class="col-12 text-center" >
	<h4 align="center">Transaction</h4>
	<div class="row title-info">
		<h6 class="col-6" align="left">Fabric: <b><?php echo $row_fabric["cat_name_en"]; ?></b></h6>
		<h6 class="col-6" align="left">Color: <b><?php echo $row_fabric["fabric_color"]; ?></b></h6>
		<h6 class="col-6" align="left">Box: <b><?php echo $row_fabric["fabric_box"]; ?></b></h6>
		<h6 class="col-6" align="left">No. <b><?php echo $row_fabric["fabric_no"]; ?></b></h6>
	</div>
	
</div>

<div class="col-12" >
	<table width="100%" class="trans_tbl">
		<thead>
			<tr>
				<th class="text-center">Date</th>
				<th class="text-center">Process</th>
				<th class="text-center">USED</th>
				<th class="text-center">Document</th>
			</tr>
		</thead>
		<tbody>
			<?php

			while($row_data = $rs_data->fetch_assoc()){

			?>
			<tr>
				<td class="text-center"><?php echo $row_data["trans_date"]; ?></td>
				<td class="text-center"><?php echo $row_data["trans_process"]; ?></td>
				<td class="text-center"><?php echo number_format($row_data["trans_value"],2); ?></td>
				<td class="text-center">
					<i class="fa fa-file-text-o show-doc" onclick="showTransDocument('<?php echo $row_data["trans_process"]; ?>',<?php echo $row_data["ref_id"]; ?>,'<?php echo $row_fabric["fabric_box"]; ?>','<?php echo $row_fabric["fabric_no"]; ?>');" data-toggle="modal" data-target="#showTransDocModal"></i>
				</td>
			</tr>
			<?php
			}

			?>
		</tbody>
	</table>
</div>
