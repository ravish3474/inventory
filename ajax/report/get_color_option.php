<?php
if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<option>Error: Invalid parameter</option>';
	exit();
}

require_once('../../db.php');

?>
<option value="=all=">==All==</option>
<?php
$sql_color = "SELECT DISTINCT fabric_color FROM fabric WHERE cat_id=".$_POST["cat_id"]." ORDER BY fabric_color ASC";
$rs_color = $conn->query($sql_color);

while($row_color = $rs_color->fetch_assoc()){
	echo '<option value="'.$row_color["fabric_color"].'">'.$row_color["fabric_color"].'</option>';
}
?>