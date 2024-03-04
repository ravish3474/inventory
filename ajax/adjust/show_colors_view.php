<div class="col-2 text-left" style="position: relative; cursor: pointer;" onclick="showFabricView();">
	<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back
</div>

<div class="col-8 text-center">
<h5><?php echo base64_decode($_POST["cat_name_en"]); ?></h5>
</div>
<div class="col-2 text-right" >
	<input type="hidden" id="new_cat_name_en" value="<?php echo base64_decode($_POST["cat_name_en"]); ?>">
	<input type="hidden" id="hide_cat_id" value="<?php echo $_POST["cat_id"]; ?>">
	<div class="btn btn-primary" style="height: 30px; line-height: 18px;" onclick="showNewRollForm('color');" data-toggle="modal" data-target="#newRollModal"><i class="fa fa-plus"></i> New roll</div>
</div>
<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$sql_fabric = "SELECT fabric.fabric_color,COUNT(*) AS n_rolls,SUM(fabric.fabric_balance) AS f_bal,SUM(fabric.fabric_balance*fabric.fabric_in_price) AS f_total,tbl_color.color_code ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN tbl_color ON fabric.fabric_color=tbl_color.color_name ";
$sql_fabric .= "WHERE fabric.fabric_balance<>0 AND fabric.cat_id=".$_POST["cat_id"]." ";
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
?>
<div class="col-2 text-center colors-board" >
	<div class="row colors-box" onclick="showRollsView(<?php echo $_POST["cat_id"]; ?>,'<?php echo base64_encode($row_fabric["fabric_color"]); ?>','<?php echo $_POST["cat_name_en"]; ?>','<?php echo $row_fabric["color_code"]; ?>');">
	<h6 class="col-12" style="background-color: #<?php echo $color_code; ?>; color:#<?php echo $font_color; ?>;"><?php echo $row_fabric["fabric_color"]; ?></h6>
	<div class="col-12">Balance: <font color="green"><?php echo number_format($row_fabric["f_bal"],2); ?></font> Kg.</div>
	<div class="col-5"><font color="blue"><?php echo $row_fabric["n_rolls"]; ?></font> Rolls.</div>
	<div class="col-7"><font color="red" class="num-total"><?php echo number_format($row_fabric["f_total"],2); ?></font>฿</div>

	</div>
</div>
<?php
}
?>