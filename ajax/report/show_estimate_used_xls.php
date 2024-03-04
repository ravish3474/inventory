<?php
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Report_EST_Used".date("Ymd").".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

require_once('../../db.php');

$y_select = date("Y");
$compare_year = $_POST["in_year_select"];

$a_cat_id = $_POST["cat_id"];
$n_cat_size = sizeof($a_cat_id);
$s_cat_list = implode(",", $a_cat_id);

$a_data = array();

if(!isset($_POST["cat_id"])){
	exit();
}

//-----Used from RQ
$sql_sum = "SELECT cat.cat_name_en AS cat_name,fabric.cat_id,fabric.color_id,tbl_color.color_name,SUBSTR(tbl_rq_form_item.cut_date,1,7) AS month_used, ROUND(SUM(tbl_rq_form_item.used),2) AS sum_used ";
$sql_sum .= " FROM tbl_rq_form_item ";
$sql_sum .= " LEFT JOIN tbl_rq_form ON tbl_rq_form_item.rq_id=tbl_rq_form.rq_id ";
$sql_sum .= " LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_sum .= " LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_sum .= " LEFT JOIN tbl_color ON fabric.color_id=tbl_color.color_id ";
$sql_sum .= " WHERE tbl_rq_form.enable=1 AND tbl_rq_form_item.mark_cut_stock=1 AND (tbl_rq_form_item.cut_date LIKE '".$compare_year."-%' OR tbl_rq_form_item.cut_date LIKE '".$y_select."-%') ";
if($n_cat_size>0){
	$sql_sum .= " AND fabric.cat_id IN (".$s_cat_list.") ";
}
$sql_sum .= " GROUP BY fabric.cat_id,fabric.color_id,SUBSTR(tbl_rq_form_item.cut_date,1,7) ";

//-----Used from No-code RQ
$sql_sum2 = "SELECT cat.cat_name_en AS cat_name,fabric.cat_id,fabric.color_id,tbl_color.color_name,SUBSTR(used_head.used_date,1,7) AS month_used, ROUND(SUM(used_detail.used_detail_used),2) AS sum_used ";
$sql_sum2 .= " FROM used_detail ";
$sql_sum2 .= " LEFT JOIN used_head ON used_detail.used_id=used_head.used_id ";
$sql_sum2 .= " LEFT JOIN fabric ON used_detail.materials_id=fabric.fabric_id ";
$sql_sum2 .= " LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_sum2 .= " LEFT JOIN tbl_color ON fabric.color_id=tbl_color.color_id ";
$sql_sum2 .= " WHERE used_detail.type_id=1 AND (used_head.used_date LIKE '".$compare_year."-%' OR used_head.used_date LIKE '".$y_select."-%') ";
if($n_cat_size>0){
	$sql_sum2 .= " AND fabric.cat_id IN (".$s_cat_list.") ";
}
$sql_sum2 .= " GROUP BY fabric.cat_id,fabric.color_id,SUBSTR(used_head.used_date,1,7) ";

//-----Merge sql
$sql_query = $sql_sum." UNION ".$sql_sum2;
$sql_query .= " ORDER BY color_name ASC,cat_name ASC; ";

/*echo $sql_query;
exit();*/

$a_color_id = array();

$rs_sum = $conn->query($sql_query);
while($row_sum = $rs_sum->fetch_assoc()){
	
	if(!isset($a_data[($row_sum["color_id"])][($row_sum["cat_id"])])){

		for($i=1; $i<=12; $i++){

			$tmp_month = "";
			if($i<10){
				$tmp_month = "0";
			}

			$tmp_index = $compare_year."-".$tmp_month.$i;
			$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["month_used"][$tmp_index]["used"] = 0;

		}

		$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["year_select_used"] = 0;
		$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["bal_now"] = 0;
		$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["ordered"] = 0;

	}
	

	if(substr($row_sum["month_used"],0,4)==$y_select){
		$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["year_select_used"] += floatval($row_sum["sum_used"]);
	}else{
		$a_data[($row_sum["color_id"])][($row_sum["cat_id"])]["month_used"][($row_sum["month_used"]."")]["used"] += floatval($row_sum["sum_used"]);
	}
	
	if($row_sum["color_id"]!="" && $row_sum["color_id"]!="0"){
		if(!in_array($row_sum["color_id"], $a_color_id)){
			$a_color_id[] = $row_sum["color_id"];
		}
	}
}

//---Find present Stock balance
$sql_sum_bal = "SELECT color_id,cat_id,SUM(fabric_balance) AS sum_bal FROM fabric WHERE fabric_balance>0 AND cat_id IN (".$s_cat_list.") GROUP BY color_id,cat_id;";
$rs_sum_bal = $conn->query($sql_sum_bal);
while($row_sum_bal = $rs_sum_bal->fetch_assoc()){

	if(!isset($a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])])){

		for($i=1; $i<=12; $i++){

			$tmp_month = "";
			if($i<10){
				$tmp_month = "0";
			}

			$tmp_index = $compare_year."-".$tmp_month.$i;
			$a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])]["month_used"][$tmp_index]["used"] = 0;

		}

		$a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])]["year_select_used"] = 0;
		$a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])]["bal_now"] = 0;
		$a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])]["ordered"] = 0;

	}

	if($row_sum_bal["color_id"]!="" && $row_sum_bal["color_id"]!="0"){
		if(!in_array($row_sum_bal["color_id"], $a_color_id)){
			$a_color_id[] = $row_sum_bal["color_id"];
		}
	}

	$a_data[($row_sum_bal["color_id"])][($row_sum_bal["cat_id"])]["bal_now"] = floatval($row_sum_bal["sum_bal"]);
}

//---Find purchase and ordered qty
$sql_f_order = "SELECT color_id,cat_id,SUM(qty) AS sum_qty FROM tbl_f_ordered_item WHERE is_receive=0 AND cat_id IN (".$s_cat_list.") AND color_id<>'0' AND color_id<>'' AND color_id IS NOT NULL GROUP BY color_id,cat_id;";
$rs_f_order = $conn->query($sql_f_order);
while($row_f_order = $rs_f_order->fetch_assoc()){

	if(!isset($a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])])){

		for($i=1; $i<=12; $i++){

			$tmp_month = "";
			if($i<10){
				$tmp_month = "0";
			}

			$tmp_index = $compare_year."-".$tmp_month.$i;
			$a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])]["month_used"][$tmp_index]["used"] = 0;

		}

		$a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])]["year_select_used"] = 0;
		$a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])]["bal_now"] = 0;
		$a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])]["ordered"] = 0;

	}

	if($row_f_order["color_id"]!="" && $row_f_order["color_id"]!="0"){
		if(!in_array($row_f_order["color_id"], $a_color_id)){
			$a_color_id[] = $row_f_order["color_id"];
		}
	}

	$a_data[($row_f_order["color_id"])][($row_f_order["cat_id"])]["ordered"] = floatval($row_f_order["sum_qty"]);
}

//-----Select cat
$a_cat = array();

$sql_cat = "SELECT cat_id,cat_name_en FROM cat WHERE cat_id IN (".$s_cat_list."); ";
$rs_cat = $conn->query($sql_cat);
while($row_cat = $rs_cat->fetch_assoc()){
	$a_cat[($row_cat["cat_id"])] = $row_cat["cat_name_en"];
}

//-----Select color
$a_color = array();

$s_color_id_list = implode(",", $a_color_id);

$sql_color = "SELECT color_id,color_name FROM tbl_color WHERE color_id IN (".$s_color_id_list.") ORDER BY color_name ASC; ";
$rs_color = $conn->query($sql_color);
while($row_color = $rs_color->fetch_assoc()){
	$a_color[($row_color["color_id"])] = $row_color["color_name"];
}
//$a_color["0"] = "N/A";

$a_row_total = array();
for($i=1; $i<=12; $i++){

	$tmp_month = "";
	if($i<10){
		$tmp_month = "0";
	}

	$tmp_index = $y_select."-".$tmp_month.$i;
	$a_row_total[$tmp_index]["used"] = 0.0;

}

$tmp_color = "";
?>
<html>
<body>
<style type="text/css">
.tbl_mat_used{
	position: relative;
	border-collapse: unset;

	text-align: center;
	width:1800px;
	border-spacing: 0px;
}
.tbl_mat_used th{
	position: sticky;
	top: 0;

	text-align: center;
	font-size: 13px;
	font-weight: bold;
	color: #000;
	background-color: #e3e40a;
	padding: 3px;
	border: 1px solid #CCC;
}
.tbl_mat_used td{
	font-size: 13px;
	color: #000;
	padding: 3px;
	border: 1px solid #CCC;
}
.used_col{
	width: 70px !important;
}
.amount_col{
	width: 65px !important;
}
.total_cell{
	background-color: #AFA !important;
	font-weight: bold !important;
	color: #000 !important;
	width: 90px;
}
.total_cell2{
	background-color: #FAA !important;
	font-weight: bold !important;
	color: #000 !important;
	width: 100px;
}
.total_cell3{
	background-color: #DDF !important;
	font-weight: bold !important;
	width: 100px;
}
</style>
<table id="tbl_content" class="tbl_mat_used" border="1">
	<tr>
		<th style="width:200px; " class="sticky_left1">Color</th>
		<th style="width:170px; ">Fabric: <span style="color:#00F;" id="sp_fabric_select_num"></span><br>
			<button type="button" class="btn_select_fabric" data-toggle="modal" data-target="#selectFabricModal">Select</button>
		</th>
		<?php
		for($i=1;$i<=12;$i++){
			?>
			<th><?php echo date("M.",strtotime("2020-$i-01")); ?></th>
			<?php
		}
		?>
		<th class="total_cell" >Total Used <br> <?php echo $compare_year; ?></th>
		<th class="total_cell" >Used so far <br> <?php echo $y_select; ?></th>
		<th class="total_cell2" >Stock<br>balance</th>
		<th class="total_cell2" >Purchase<br>amount</th>
		<th class="total_cell2" >Estimated<br>new balance</th>
		<th class="total_cell3" >Estimated<br>used remaining</th>
	</tr>

			
<?php
foreach($a_color as $color_id => $color_name){

	$a_data2 = $a_data[$color_id];
	?>
	<tbody class="tbo_inner">
	<?php

	foreach($a_data2 as $cat_id => $a_data3){

	?>
	<tr>
		<?php
		if($color_id!=$tmp_color){
			$tmp_color = $color_id;
			$merge_row = sizeof($a_data2);
		?>
		<td <?php if($merge_row>1){ echo 'rowspan="'.$merge_row.'"'; } ?> class="sticky_left1" style="background-color: #FFF; width: 150px;"><?php echo $color_name; ?></td>
		<?php
		}
		?>
		<td style="background-color: #FFF;"><?php echo $a_cat[$cat_id]; ?></td>
		
	<?php
		$f_col_total_used = 0.0;
		foreach($a_data3["month_used"] as $month => $a_data4){
			$f_col_total_used+=floatval($a_data4["used"]);

			if(isset($a_row_total[$month]["used"]) && isset($a_data4["used"]) ){
				$a_row_total[$month]["used"] += floatval($a_data4["used"]);
			}
		?>
			<td class="amount_col"><?php echo ($a_data4["used"]==0)?"-":number_format($a_data4["used"],2); ?></td>
		<?php
		}
		$est_new_bal = $a_data2[$cat_id]["bal_now"]+$a_data2[$cat_id]["ordered"];
		$est_use_remain = $est_new_bal+$a_data2[$cat_id]["year_select_used"]-$f_col_total_used;

		$use_font_color = "#000";
		if($est_use_remain<0){
			$use_font_color = "#C00";
		}else{
			$use_font_color = "#090";
		}
		?>
		<td class="total_cell"><?php echo number_format($f_col_total_used,2); ?></td>
		<td class="total_cell"><?php echo number_format($a_data2[$cat_id]["year_select_used"],2); ?></td>
		<td class="total_cell2"><?php echo number_format($a_data2[$cat_id]["bal_now"],2); ?></td>
		<td class="total_cell2"><?php echo number_format($a_data2[$cat_id]["ordered"],2); ?></td>
		<td class="total_cell2"><?php echo number_format($est_new_bal,2); ?></td>
		<td class="total_cell3" style="color:<?php echo $use_font_color; ?>;"><?php echo number_format($est_use_remain,2); ?></td>
	</tr>
		<?php
	}
	?>
	</tbody>
	<?php
}
?>
</table>
</body>
</html>