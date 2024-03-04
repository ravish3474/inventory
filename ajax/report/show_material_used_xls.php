<?php
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Report_MAT_Used".date("Ymd").".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

require_once('../../db.php');

$y_select = $_POST["year"];
$a_data = array();

//-----Used from RQ
$sql_sum = "SELECT cat.cat_name_en AS cat_name,fabric.cat_id,fabric.color_id,fabric.fabric_color,SUBSTR(tbl_rq_form_item.cut_date,1,7) AS month_used, SUM(tbl_rq_form_item.used) AS sum_used, SUM(tbl_rq_form_item.used*fabric.fabric_in_price) AS sum_price ";
$sql_sum .= " FROM tbl_rq_form_item ";
$sql_sum .= " LEFT JOIN tbl_rq_form ON tbl_rq_form_item.rq_id=tbl_rq_form.rq_id ";
$sql_sum .= " LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_sum .= " LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_sum .= " WHERE tbl_rq_form.enable=1 AND tbl_rq_form_item.mark_cut_stock=1 AND tbl_rq_form_item.cut_date LIKE '".$y_select."-%' ";
if($_POST["cat_id"]!="=all="){
	$sql_sum .= " AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["color_id"]!="=all="){
	$sql_sum .= " AND fabric.color_id='".$_POST["color_id"]."' ";
}
$sql_sum .= " GROUP BY fabric.cat_id,fabric.color_id,SUBSTR(tbl_rq_form_item.cut_date,1,7) ";

//-----Used from No-code RQ
$sql_sum2 = "SELECT cat.cat_name_en AS cat_name,fabric.cat_id,fabric.color_id,fabric.fabric_color,SUBSTR(used_head.used_date,1,7) AS month_used, SUM(used_detail.used_detail_used) AS sum_used, SUM(used_detail.used_detail_used*fabric.fabric_in_price) AS sum_price ";
$sql_sum2 .= " FROM used_detail ";
$sql_sum2 .= " LEFT JOIN used_head ON used_detail.used_id=used_head.used_id ";
$sql_sum2 .= " LEFT JOIN fabric ON used_detail.materials_id=fabric.fabric_id ";
$sql_sum2 .= " LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_sum2 .= " WHERE used_detail.type_id=1 AND used_head.used_date LIKE '".$y_select."-%' ";
if($_POST["cat_id"]!="=all="){
	$sql_sum2 .= " AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["color_id"]!="=all="){
	$sql_sum2 .= " AND fabric.color_id='".$_POST["color_id"]."' ";
}
$sql_sum2 .= " GROUP BY fabric.cat_id,fabric.color_id,SUBSTR(used_head.used_date,1,7) ";

//-----Merge sql
$sql_query = $sql_sum." UNION ".$sql_sum2;
$sql_query .= " ORDER BY cat_name ASC,fabric_color ASC; ";

/*echo $sql_query;
exit();*/

$rs_sum = $conn->query($sql_query);
while($row_sum = $rs_sum->fetch_assoc()){
	
	if(!isset($a_data[($row_sum["cat_id"])][($row_sum["color_id"])])){
		//$a_data[($row_sum["cat_id"])][($row_sum["color_id"])][($row_sum["month_used"]."")]

		for($i=1; $i<=12; $i++){

			$tmp_month = "";
			if($i<10){
				$tmp_month = "0";
			}

			$tmp_index = $y_select."-".$tmp_month.$i;
			$a_data[($row_sum["cat_id"])][($row_sum["color_id"])][$tmp_index]["used"] = 0;
			$a_data[($row_sum["cat_id"])][($row_sum["color_id"])][$tmp_index]["cost"] = 0;

		}

	}
	//$a_data[($row_sum["cat_id"])][($row_sum["color_id_id"])][($row_sum["month_used"]."")] = array();
	$a_data[($row_sum["cat_id"])][($row_sum["color_id"])][($row_sum["month_used"]."")]["used"] += floatval($row_sum["sum_used"]);
	$a_data[($row_sum["cat_id"])][($row_sum["color_id"])][($row_sum["month_used"]."")]["cost"] += floatval($row_sum["sum_price"]);


}


//----- Fabric
$a_cat = array();
$sql_cat = "SELECT cat_id,cat_name_en FROM cat ORDER BY enable DESC,cat_name_en ASC";
$rs_cat = $conn->query($sql_cat);
while($row_cat = $rs_cat->fetch_assoc()){
	$a_cat[($row_cat["cat_id"])] = $row_cat["cat_name_en"];
}

//----- Color
$a_color = array();
$sql_color = "SELECT color_id,color_name FROM tbl_color ORDER BY enable DESC,color_name ASC";
$rs_color = $conn->query($sql_color);
while($row_color = $rs_color->fetch_assoc()){
	$a_color[($row_color["color_id"])] = $row_color["color_name"];
}

$a_row_total = array();
for($i=1; $i<=12; $i++){

	$tmp_month = "";
	if($i<10){
		$tmp_month = "0";
	}

	$tmp_index = $y_select."-".$tmp_month.$i;
	$a_row_total[$tmp_index]["used"] = 0.0;
	$a_row_total[$tmp_index]["cost"] = 0.0;

}
?>
<html>
<body>
<style type="text/css">
	.tbl_mat_used{
		border-collapse: unset;

		text-align: center;
		width:2700px;
		border-spacing: 0px;
	}
	.tbl_mat_used th{

		text-align: center;
		font-size: 13px;
		font-weight: bold;
		color: #FFF;
		background-color: #5A5;
		padding: 3px;
		border: 1px solid #AFA;
	}
	
	.th_mat_used2{
	}
	.sticky_left1{
		width:180px !important;
	}
	.sticky_left2{
	}

	.tbl_mat_used td{
		font-size: 13px;
		color: #000;
		padding: 3px;
		border: 1px solid #AFA;
	}


	.used_col{
		width: 75px !important;
	}
	.amount_col{
		width: 90px !important;
		text-align: right !important;
	}
	.opt_disable{
		background-color: #FAA !important;
	}
	

	.total_cell{
		background-color: #AAF !important;
		font-weight: bold !important;
		color: #000 !important;
	}
</style>
<h4 style="font-size:20px; font-weight: normal;">Material Used Report of <?php echo $y_select; ?></h4>
<table class="tbl_mat_used" border="1">
	<tr>
		<th rowspan="2" style="width:180px; background-color: #5A5;" >Fabric</th>
		<th rowspan="2" class="sticky_left2" style="background-color: #5A5;">Color</th>
		<?php
		for($i=1;$i<=12;$i++){
			?>
			<th colspan="2" style="background-color: #5A5;"><?php echo date("M.",strtotime("2020-$i-01")); ?></th>
			<?php
		}
		?>
		<th class="total_cell" colspan="2" style="background-color: #AAF; font-weight: bold;">Summary Used</th>
	</tr>
	<tr>
		<?php
		for($i=1;$i<=13;$i++){
			?>
			<th style="<?php if($i==13){ echo "background-color: #AAF; font-weight: bold;"; }else{ echo "background-color: #5A5;"; } ?>" class="used_col th_mat_used2 ">USED(KG)</th>
			<th style="<?php if($i==13){ echo "background-color: #AAF; font-weight: bold;"; }else{ echo "background-color: #5A5;"; } ?>" class="amount_col th_mat_used2 ">AMOUNT</th>
			<?php
		}
		?>
	</tr>

	<tbody id="tb_content">


<?php

foreach($a_data as $cat_id => $a_data2){

	foreach($a_data2 as $color_id => $a_data3){

	?>
	<tr>
		<td class="sticky_left1" style="background-color: #FFF; width: 180px;"><?php echo $a_cat[$cat_id]; ?></td>
		<td class="sticky_left2" style="background-color: #FFF;"><?php echo $a_color[$color_id]; ?></td>
	<?php
		$f_col_total_used = 0.0;
		$f_col_total_amount = 0.0;
		foreach($a_data3 as $month => $a_data4){
			$f_col_total_used+=floatval($a_data4["used"]);
			$f_col_total_amount+=floatval($a_data4["cost"]);

			$a_row_total[$month]["used"] += floatval($a_data4["used"]);
			$a_row_total[$month]["cost"] += floatval($a_data4["cost"]);
		?>
			<td class="amount_col"><?php echo ($a_data4["used"]==0)?"-":number_format($a_data4["used"],2); ?></td>
			<td class="amount_col"><?php echo ($a_data4["cost"]==0)?"-":number_format($a_data4["cost"],2); ?></td>
		<?php
		}
		?>
		<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold;"><?php echo number_format($f_col_total_used,2); ?></td>
		<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold;"><?php echo number_format($f_col_total_amount,2); ?></td>
	</tr>
		<?php
	}
}
?>
<tr>
	<td colspan="2" class="amount_col sticky_left1 total_cell" style="background-color: #AAF; font-weight: bold;">Grand Total:</td>
	<?php
	$grand_used = 0.0;
	$grand_cost = 0.0;
	for($i=1; $i<=12; $i++){

		$tmp_month = "";
		if($i<10){
			$tmp_month = "0";
		}

		$tmp_index = $y_select."-".$tmp_month.$i;

		$grand_used += floatval($a_row_total[$tmp_index]["used"]);
		$grand_cost += floatval($a_row_total[$tmp_index]["cost"]);
		?>
		<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold; text-align: right;"><?php echo ($a_row_total[$tmp_index]["used"]==0)?"-":number_format($a_row_total[$tmp_index]["used"],2); ?></td>
		<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold; text-align: right;"><?php echo ($a_row_total[$tmp_index]["cost"]==0)?"-":number_format($a_row_total[$tmp_index]["cost"],2); ?></td>
		<?php
	}
	?>
	<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold; text-align: right;"><?php echo number_format($grand_used,2); ?></td>
	<td class="amount_col total_cell" style="background-color: #AAF; font-weight: bold; text-align: right;"><?php echo number_format($grand_cost,2); ?></td>
</tr>

</tbody>
</table>
</body>
</html>