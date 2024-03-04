<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$sql_fabric = "SELECT fabric.fabric_color,COUNT(*) AS n_rolls,SUM(fabric.fabric_balance) AS f_bal,SUM(fabric.fabric_balance*fabric.fabric_in_price) AS f_total,tbl_color.color_code ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN tbl_color ON fabric.fabric_color=tbl_color.color_name ";
$sql_fabric .= "WHERE fabric.fabric_balance>0 AND fabric.cat_id=".$_POST["cat_id"]." AND fabric.fabric_color<>'' ";
$sql_fabric .= "GROUP BY fabric.fabric_color ";
$sql_fabric .= "ORDER BY fabric.fabric_color ASC ";
//echo $sql_fabric;
$rs_fabric = $conn->query($sql_fabric);
?>
<div class="col-12 text-right" style="padding-bottom: 15px;">
	<input type="checkbox" id="select_all_color" onclick="selectAllColor(<?php echo $_POST["cat_id"]; ?>);"> <span style="color:#090;"><b>Select all</b></span>
</div>
<?php
$n_count = 1;
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
<div class="col-4 color-item">
	<input id="choose_color_<?php echo $_POST["cat_id"]; ?>_<?php echo $n_count; ?>" class="in_checkbox_color" type="checkbox" name="color_check[]" value="<?php echo $row_fabric["fabric_color"]; ?>" onclick="chooseColor(<?php echo $_POST["cat_id"]; ?>,<?php echo $n_count; ?>);" data-row_id="<?php echo $n_count; ?>">
	<?php echo $row_fabric["fabric_color"]; ?>
</div>
<?php
	$n_count++;
}
?>