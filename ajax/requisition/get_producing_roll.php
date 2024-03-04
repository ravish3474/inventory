<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || !isset($_POST["color_name"]) || !isset($_POST["fabric_box"])){
	echo "Invalid parameter.";
	exit();
}
$cat_id = $_POST["cat_id"];
$color_name = $_POST["color_name"];
$fabric_box = $_POST["fabric_box"];

$sql_select = "SELECT cat.cat_name_en,fabric.fabric_color,fabric.fabric_box,fabric.fabric_no,tbl_rq_form.order_code,tbl_rq_form.is_addon,tbl_rq_form.rq_id FROM tbl_rq_form_item ";
$sql_select .= " LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";
$sql_select .= " LEFT JOIN cat ON cat.cat_id=fabric.cat_id ";
$sql_select .= " LEFT JOIN tbl_rq_form ON tbl_rq_form.rq_id=tbl_rq_form_item.rq_id ";
$sql_select .= " WHERE tbl_rq_form_item.mark_cut_stock=0 AND tbl_rq_form.enable=1 ";
if($cat_id!="=all="){
	$sql_select .= " AND cat.cat_id=".$cat_id;
}
if($color_name!="=all="){
	$sql_select .= " AND fabric.fabric_color='".$color_name."'";
}
if($fabric_box!="=all="){
	$sql_select .= " AND fabric.fabric_box='".$fabric_box."'";
}
$sql_select .=" ORDER BY cat.cat_name_en ASC,fabric.fabric_color ASC,fabric.fabric_box ASC,fabric.fabric_no ASC,tbl_rq_form.order_code ASC; ";
$rs_data = $conn->query($sql_select);
while ($row_data = $rs_data->fetch_assoc()) {
	?>
	<tr>
		<td><?php echo $row_data["cat_name_en"]; ?></td>
		<td><?php echo $row_data["fabric_color"]; ?></td>
		<td><?php echo $row_data["fabric_box"]; ?></td>
		<td><?php echo $row_data["fabric_no"]; ?></td>
		<td><div style="cursor: pointer; text-decoration: underline; color:#00F;" data-toggle="modal" data-target="#editRQModal" onclick="editRQ(<?php echo $row_data["rq_id"]; ?>);"><?php echo $row_data["order_code"].(($row_data["is_addon"]=="1")?"(Add-on)":""); ?></div></td>
	</tr>
	<?php
}
?>