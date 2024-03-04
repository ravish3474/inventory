<?php
require_once('../../db.php');


$a_return = array();

$sql = "SELECT * FROM tbl_unit WHERE enable=1 ORDER BY unit_name ASC;" ;

$rs_unit = $conn->query($sql);

while($row_unit = $rs_unit->fetch_assoc()){
	$a_return["result"][] = $row_unit;
}


echo json_encode($a_return);
?>