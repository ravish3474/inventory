<div style="position: relative; cursor: pointer;" onclick="showFabricView();">
	<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back
</div>
<div class="col-12 text-center">
<h5><?php echo base64_decode($_POST["cat_name_en"]); ?></h5>
<a href="ajax/report/export_excel_colors.php?cat_id=<?=$_POST["cat_id"]?>&namer=<?=$_POST["cat_name_en"]?>">
    <button class="btn btn-success">
    <i class="fa fa-file-excel-o"></i> Export to Excel
    </button>
</a>
</div>
<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$a_empty_roll = array();

$sql_empty_roll = "SELECT fabric_color,COUNT(*) AS n_rolls ";
$sql_empty_roll .= "FROM fabric ";
$sql_empty_roll .= "WHERE fabric_balance<=0 AND cat_id=".$_POST["cat_id"]." ";
$sql_empty_roll .= "GROUP BY fabric_color ";
$sql_empty_roll .= "ORDER BY fabric_color ASC ";
$rs_empty_roll = $conn->query($sql_empty_roll);
while($row_empty_roll = $rs_empty_roll->fetch_assoc()){

	$a_empty_roll[($row_empty_roll["fabric_color"])] = $row_empty_roll["n_rolls"];
}

$a_active_roll = array();

$sql_active_roll = "SELECT fabric_color,COUNT(*) AS n_rolls,SUM(fabric_balance) AS f_bal ";
$sql_active_roll .= "FROM fabric ";
$sql_active_roll .= "WHERE fabric_balance>0 AND cat_id=".$_POST["cat_id"]." ";
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
$sql_fabric .= "WHERE fabric.cat_id=".$_POST["cat_id"]." ";
$sql_fabric .= "GROUP BY fabric.fabric_color ";
$sql_fabric .= "ORDER BY fabric.fabric_color ASC ";
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

$active_rolls = isset($a_active_roll[trim($row_fabric["fabric_color"])]) ? $a_active_roll[trim($row_fabric["fabric_color"])] : array();
$n_rolls = isset($active_rolls["n_rolls"]) ? $active_rolls["n_rolls"] : 0;


?>
<div class="col-2 text-center colors-board" >
	<div class="row colors-box" onclick="showRollsView(<?php echo $_POST["cat_id"]; ?>,'<?php echo base64_encode($row_fabric["fabric_color"]); ?>','<?php echo $_POST["cat_name_en"]; ?>','<?php echo $row_fabric["color_code"]; ?>');">
	<h6 class="col-12" style="background-color: #<?php echo $color_code; ?>; color:#<?php echo $font_color; ?>;"><?php echo $row_fabric["fabric_color"]; ?></h6>
	<div class="col-12">Balance: <font color="green"><?php echo (isset($a_active_roll[($row_fabric["fabric_color"])]["f_bal"])?number_format($a_active_roll[($row_fabric["fabric_color"])]["f_bal"],2):"0.0"); ?></font> Kg.</div>
	<div class="col-12">Active <font color="blue"><?php echo $n_rolls; ?></font> Rolls.</div>
	<div class="col-12">Empty <font color="red"><?php echo (isset($a_empty_roll[($row_fabric["fabric_color"])])?$a_empty_roll[($row_fabric["fabric_color"])]:0); ?></font> Rolls.</div>

	</div>
</div>
<?php
}
?>