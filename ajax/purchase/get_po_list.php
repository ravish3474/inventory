<?php
require_once('../../db.php');

$select_page = $_POST["select_page"];
$search_type = $_POST["search_type"];
$search_value = $_POST["search_value"];

$num_data_per_page = 50;
if($select_page==""){
	$select_page = 1;
}
$start_index = (intval($select_page)-1)*$num_data_per_page;

$sql_select = "SELECT tbl_po_content.*,COUNT(*) AS num_item FROM tbl_po_content LEFT JOIN tbl_po_item ON tbl_po_content.po_con_id=tbl_po_item.po_con_id ";
$sql_select .= " WHERE tbl_po_content.enable=1 AND tbl_po_item.enable=1 ";
if( ($search_type!="all") && ($search_value!="") ){
	$sql_select .= " AND tbl_po_content.".$search_type." LIKE '%".$search_value."%' ";
}else if( ($search_type=="all") && ($search_value!="") ){
	$sql_select .= " AND (tbl_po_content.po_number LIKE '%".$search_value."%' OR tbl_po_content.po_date LIKE '%".$search_value."%' OR tbl_po_content.sup_name LIKE '%".$search_value."%') ";
}
$sql_select .=" GROUP BY tbl_po_content.po_con_id ORDER BY tbl_po_content.date_modified DESC,tbl_po_content.date_add DESC LIMIT ".$start_index.",".$num_data_per_page.";" ;

$rs_select = $conn->query($sql_select);

$show_row_no = $start_index+1;
while($row_select = $rs_select->fetch_assoc()){
?>
	<tr>
		<td><?php echo $show_row_no; ?></td>
		<td><?php echo $row_select["po_number"]; ?></td>
		<td><?php echo $row_select["po_date"]; ?></td>
		<td><?php echo $row_select["sup_name"]; ?></td>
		<td><?php echo $row_select["num_item"]; ?></td>
		<td><?php echo number_format($row_select["net_total"],2); ?></td>
		<td><?php echo $row_select["po_status"]; ?></td>
		<td width="220">
			<button class="btn btn-info" title="View PO detail" data-toggle="modal" data-target="#showPOModal" onclick="showPOdetail(<?php echo $row_select["po_con_id"]; ?>);">
				<i class="fa fa-file-text-o" style="margin-right:0px;"></i>
			</button> 
			<?php
			if($row_select["po_status"]=="NEW"){
			?>
			<button class="btn btn-danger" title="Edit PO" data-toggle="modal" data-target="#showPOModal" onclick="showPOdetail(<?php echo $row_select["po_con_id"]; ?>,'edit');">
				<i class="fa fa-pencil-square-o" style="margin-right:0px;"></i>
			</button> 
			<?php
			}
			?>
			<button class="btn btn-dark" title="Print" data-toggle="modal" data-target="#showPOModal" onclick="showPOdetail(<?php echo $row_select["po_con_id"]; ?>,'download');">
				<i class="fa fa-print" style="margin-right:0px;"></i>
			</button> 
			<!-- <a href="ajax/purchase/get_po_detail.php?action=download&type=excel&po_con_id=<?php echo $row_select["po_con_id"]; ?>">
				<button class="btn btn-dark" title="Export to Excel"><i class="fa fa-file-excel-o" style="margin-right:0px;"></i></button>
			</a> -->
		</td>
	</tr>
<?php
	$show_row_no++;
}
?>