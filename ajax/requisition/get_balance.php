<?php
require_once('../../db.php');

if( !isset($_POST["fabric_id"]) ){
	echo "Invalid parameter.";
	exit();
}
$fabric_id = $_POST["fabric_id"];

$sql_select = "SELECT fabric.*,cat.cat_name_en FROM fabric LEFT JOIN cat ON cat.cat_id=fabric.cat_id WHERE fabric.fabric_id=".$fabric_id."; ";
$rs_balance = $conn->query($sql_select);
$row_balance = $rs_balance->fetch_assoc();

$tmp_id = date("His");
?>
<tr id="tr_row<?php echo $row_balance["fabric_id"].$tmp_id; ?>">
	<td><?php echo $row_balance["cat_name_en"]; ?></td>
	<td><?php echo $row_balance["fabric_color"]; ?></td>
	<td><?php echo $row_balance["fabric_box"]; ?></td>
	<td><?php echo $row_balance["fabric_no"]; ?></td>
	<td><?php echo number_format($row_balance["fabric_balance"],2); ?> Kg.</td>
	<td><input class="btn btn-danger" type="button" value="Remove" onclick="return removeMaterial(<?php echo $row_balance["fabric_id"]; ?>,<?php echo $tmp_id; ?>);"></td>
</tr>