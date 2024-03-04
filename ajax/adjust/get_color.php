<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$sql_color = "SELECT DISTINCT fabric_color ";
$sql_color .= "FROM fabric ";
$sql_color .= "WHERE cat_id='".$_POST["cat_id"]."' ORDER BY fabric_color ASC; ";
$rs_color = $conn->query($sql_color);
?>
<select id="select_new_color_name" onchange="return newRollChangeColor();" style="width: 100%;">				
<?php

while($row_color = $rs_color->fetch_assoc()){
?>
	<option value="<?php echo $row_color["fabric_color"]; ?>"><?php echo $row_color["fabric_color"]; ?></option>
<?php
}
?>
<option value="=new=">== New ==</option>
</select>