<?php
require_once('../../db.php');

if( !isset($_POST["y_select"]) || $_POST["y_select"]=="" ){
	echo "Fail: Invalid parameter.";
	exit();
}

$y_select = $_POST["y_select"];
$a_data = array();

for($i=1; $i<=12; $i++){

	$tmp_month = "";
	if($i<10){
		$tmp_month = "0";
	}

	$tmp_index = $y_select."-".$tmp_month.$i;
	$a_data[$tmp_index] = 0;

}

/*$sql_sum = "SELECT SUBSTR(tbl_rq_form.rq_date,1,7) AS month_used, SUM(tbl_rq_form_item.used) AS sum_used, SUM(tbl_rq_form_item.used*fabric.fabric_in_price) AS sum_price ";
$sql_sum .= " FROM tbl_rq_form_item ";
$sql_sum .= " LEFT JOIN tbl_rq_form ON tbl_rq_form_item.rq_id=tbl_rq_form.rq_id ";
$sql_sum .= " LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_sum .= " WHERE tbl_rq_form.enable=1 AND tbl_rq_form.rq_status='finish' AND tbl_rq_form.rq_date LIKE '".$y_select."-%' ";
$sql_sum .= " GROUP BY SUBSTR(tbl_rq_form.rq_date,1,7) ORDER BY SUBSTR(tbl_rq_form.rq_date,1,7) ASC; ";*/
$sql_sum = "SELECT SUBSTR(tbl_rq_form_item.cut_date,1,7) AS month_used, SUM(tbl_rq_form_item.used) AS sum_used, SUM(tbl_rq_form_item.used*fabric.fabric_in_price) AS sum_price ";
$sql_sum .= " FROM tbl_rq_form_item ";
$sql_sum .= " LEFT JOIN tbl_rq_form ON tbl_rq_form_item.rq_id=tbl_rq_form.rq_id ";
$sql_sum .= " LEFT JOIN fabric ON tbl_rq_form_item.fabric_id=fabric.fabric_id ";
$sql_sum .= " WHERE tbl_rq_form.enable=1 AND tbl_rq_form_item.mark_cut_stock=1 AND tbl_rq_form_item.cut_date LIKE '".$y_select."-%' ";
$sql_sum .= " GROUP BY SUBSTR(tbl_rq_form_item.cut_date,1,7) ORDER BY SUBSTR(tbl_rq_form_item.cut_date,1,7) ASC; ";

$rs_sum = $conn->query($sql_sum);
while($row_sum = $rs_sum->fetch_assoc()){
	$a_data[($row_sum["month_used"]."")] = array();
	$a_data[($row_sum["month_used"]."")]["used"] = floatval($row_sum["sum_used"]);
	$a_data[($row_sum["month_used"]."")]["cost"] = floatval($row_sum["sum_price"]);
}
?>

<table class="report_tbl" style="margin-top: 10px;">
	<tr>
		<th>Month</th><th style="text-align: right;">Used(KG)</th><th style="text-align: right;">Cost</th>
	</tr>
<?php
$total_used = 0;
$total_cost = 0;
foreach($a_data as $pre_date=>$sum_value){

	$show_month = date("M",strtotime($pre_date."-01 00:00:00"));
	$total_used += $sum_value["used"];
	$total_cost += $sum_value["cost"];
?>
	<tr>
		<td><?php echo $show_month; ?></td>
		<td style="text-align: right;"><?php echo ($sum_value["used"]!=0)?number_format($sum_value["used"],2):"-"; ?></td>
		<td style="text-align: right;"><?php echo ($sum_value["cost"]!=0)?number_format($sum_value["cost"],2):"-"; ?></td>
	</tr>
<?php
}
?>
	<tr>
		<td><b>Total</b></td>
		<td style="text-align: right;"><?php echo number_format($total_used,2); ?></td>
		<td style="text-align: right;"><?php echo number_format($total_cost,2); ?></td>
	</tr>
</table>