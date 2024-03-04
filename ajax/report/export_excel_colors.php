<?php
$data = '<table border="1">';
$data .= '<tr>';
$data .= '<th style="font-weight: bold;">COLORS</th>';
$data .= '<th style="font-weight: bold;">BALANCE (In Kg)</th>';
$data .= '<th style="font-weight: bold;">ACTIVE ROLLS</th>';
$data .= '<th style="font-weight: bold;">EMPTY ROLLS</th>';
$data .= '</tr>';
require_once('../../db.php');

if( !isset($_GET["cat_id"]) || ($_GET["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$a_empty_roll = array();

$sql_empty_roll = "SELECT fabric_color,COUNT(*) AS n_rolls ";
$sql_empty_roll .= "FROM fabric ";
$sql_empty_roll .= "WHERE fabric_balance<=0 AND cat_id=".$_GET["cat_id"]." ";
$sql_empty_roll .= "GROUP BY fabric_color ";
$sql_empty_roll .= "ORDER BY fabric_color ASC ";
$rs_empty_roll = $conn->query($sql_empty_roll);
while($row_empty_roll = $rs_empty_roll->fetch_assoc()){

	$a_empty_roll[($row_empty_roll["fabric_color"])] = $row_empty_roll["n_rolls"];
}

$a_active_roll = array();

$sql_active_roll = "SELECT fabric_color,COUNT(*) AS n_rolls,SUM(fabric_balance) AS f_bal ";
$sql_active_roll .= "FROM fabric ";
$sql_active_roll .= "WHERE fabric_balance>0 AND cat_id=".$_GET["cat_id"]." ";
$sql_active_roll .= "GROUP BY fabric_color ";
$sql_active_roll .= "ORDER BY fabric_color ASC ";
$rs_active_roll = $conn->query($sql_active_roll);
while($row_active_roll = $rs_active_roll->fetch_assoc()){
	$a_active_roll[($row_active_roll["fabric_color"])]["n_rolls"] = $row_active_roll["n_rolls"];
	$a_active_roll[($row_active_roll["fabric_color"])]["f_bal"] = $row_active_roll["f_bal"];
}

$sql_fabric = "SELECT fabric.fabric_color,tbl_color.color_code ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN tbl_color ON fabric.fabric_color=tbl_color.color_name ";
$sql_fabric .= "WHERE fabric.cat_id=".$_GET["cat_id"]." ";
$sql_fabric .= "GROUP BY fabric.fabric_color ";
$sql_fabric .= "ORDER BY fabric.fabric_color ASC ";
//echo $sql_fabric;
$rs_fabric = $conn->query($sql_fabric);

while($row_fabric = $rs_fabric->fetch_assoc()){

	$color_code = "f49502";
	if($row_fabric["color_code"]!=""){
		$color_code = $row_fabric["color_code"];
	}

	$a_check = array("0","1","2","3","4","5","6","7","8","9","a","A");
	$tmp_color1 = substr($color_code,0,1);
	$tmp_color2 = substr($color_code,2,1);
	$tmp_color3 = substr($color_code,4,1);
	$font_color = "000";

	if( in_array($tmp_color1,$a_check) && in_array($tmp_color2,$a_check) && in_array($tmp_color3,$a_check) ){
		$font_color = "FFF";
	}
        $fabricColor = trim($row_fabric["fabric_color"]);
        $fabricBalance = isset($a_active_roll[$fabricColor]["f_bal"]) ? number_format($a_active_roll[$fabricColor]["f_bal"], 2) : "0.0";
        $fabricNrolls = isset($a_active_roll[$fabricColor]["n_rolls"]) ? $a_active_roll[$fabricColor]["n_rolls"] : 0;
        // $fabricEmptyRolls = isset($a_empty_roll[$fabricColor]) ? $a_empty_roll[$fabricColor] : 0;
        $fabricEmptyRolls = isset($a_empty_roll[($row_fabric["fabric_color"])])?$a_empty_roll[($row_fabric["fabric_color"])]:0;

        $data .= '<tr>';
        $data .= '<td>' . $fabricColor . '</td>';
        $data .= '<td>' . $fabricBalance . '</td>';
        $data .= '<td>' . $fabricNrolls . '</td>';
        $data .= '<td>' . $fabricEmptyRolls . '</td>';
        $data .= '</tr>';
}
// Set headers for download
$filename = base64_decode($_GET["namer"]) . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");

// Output the Excel content
echo $data;

// Exit to prevent any further output
exit();
?>