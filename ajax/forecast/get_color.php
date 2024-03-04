<?php
require_once('../../db.php');

$sql_select = "SELECT * FROM tbl_color WHERE color_id IN (SELECT DISTINCT color_id FROM fabric WHERE cat_id='".$_POST["cat_id"]."') ORDER BY color_name ASC;";
$rs_select = $conn->query($sql_select);

while($row_select = $rs_select->fetch_assoc()){
	echo '<option value="'.$row_select["color_id"].'">'.$row_select["color_name"].'</option>';
}
echo '<option value="=new=">==New color==</option>';
?>