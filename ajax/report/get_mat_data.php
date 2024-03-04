<?php
if( !isset($_POST["year_select"]) || ($_POST["year_select"]=="") || !isset($_POST["month_select"]) || ($_POST["month_select"]=="") ){
	echo '<b>Error: Invalid parameter</b>';
	exit();
}

if( isset($_POST["export_flag"]) && $_POST["export_flag"]==1 ){
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=SummaryReport".date("Ymd").".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	echo '<html><head><title>Summary Report '.date("Y-m-d").'</title>';
?>
<style type="text/css">

.tbl-report th{
	font-size: 14px;
	font-weight: bold;
	border:1px solid #555;
	text-align: center;
}
.tbl-report td{
	font-size: 14px;
	border:1px solid #555;
}

.cls-normal-head{
	background-color: #ffff00;
}
.cls-total{
	background-color: #83c45b;
	text-align: right !important;
}
.cls-used{
	background-color: #ffabfc;
	text-align: right !important;
}
.cls-adjust{
	background-color: #adc9fa;
	text-align: right !important;
}
.cls-balance{
	background-color: #ffc000;
	text-align: right !important;
}
.cls-number{
	text-align: right !important;
}

.cls-sunday-head{
	background-color: #FF0000 ;
	text-align: center !important;
}
.cls-normal-day-head{
	background-color: #ffdb69;
	text-align: center !important;
}
.cls-sunday{
	background-color: #FF0000 ;
	text-align: right !important;
}
.cls-normal-day{
	background-color: #ffdb69;
	text-align: right !important;
}
.have-info{
	cursor: pointer;
	color:#00F;
}
.title-info{
	border-radius: 5px;
	border:1px solid #027;
	background-color: #05A;
	color: #FFF;
	margin: 5px 5px;
	padding: 5px 0px;
}
.trans_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.trans_tbl td{
	
	border:1px solid #7DF;
}
.trans_tbl tr:hover{
	background-color: #DDD;
}
.show-doc{
	cursor: pointer;
}

.ncode_head{
	border:2px solid #995;
	border-radius: 5px;
	padding: 10px;
	margin: 5px;
	background-color: #990;
	color: #FFF;
}

.ncode_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.ncode_tbl td{
	
	border:1px solid #7DF;
}
.ncode_tbl tr:hover{
	background-color: #DDD;
}

.rq_head{
	border:2px solid #999;
	border-radius: 5px;
	padding: 10px;
	margin: 5px;
	background-color: #222;
	color: #FFF;
}

.rq_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.rq_tbl td{
	
	border:1px solid #7DF;
}
.rq_tbl tr:hover{
	background-color: #DDD;
}

.hilight_roll td{
	background-color: #FF0;
}

.content_data:hover{
	background-color: #DDD !important;
}

.total-row td{
	background-color: #FBB;
	font-weight: bold;
}

.text-center{
	text-align: center;
}
.text-right{
	text-align: right;
}
.text-left{
	text-align: left;
}


</style>
<?php
}else{
?>
<style type="text/css">
#sum_report{
	position: relative;
	border-collapse: unset;

}
#sum_report th{
	position: sticky;
	top: 0;
	border-color: #DDD;
}
#sum_report td{
	border-color: #DDD;
}
</style>
<?php
}

$year = $_POST["year_select"];
$month = $_POST["month_select"];



$focus_date = $year."-".$month."-01 00:00:00";

require_once('../../db.php');

//----Before
$a_used_before = array();
$sql_used_before = "SELECT used_detail.materials_id AS fabric_id,SUM(used_detail.used_detail_used) AS used_val FROM used_detail ";
$sql_used_before .= "LEFT JOIN used_head ON used_detail.used_id=used_head.used_id WHERE used_head.used_date<'".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_used_before .= "AND used_detail.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_used_before .= "AND used_detail.used_detail_color='".addslashes($_POST["select_color"])."' ";
}
$sql_used_before .= "GROUP BY fabric_id ORDER BY fabric_id ASC";
$rs_used_before = $conn->query($sql_used_before);

while($row_used_before = $rs_used_before->fetch_assoc()){
	$a_used_before[($row_used_before["fabric_id"])] = $row_used_before["used_val"];
}

$sql_rq_before = "SELECT tbl_rq_form_item.fabric_id,SUM(tbl_rq_form_item.used) AS used_val FROM tbl_rq_form_item ";
$sql_rq_before .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";
$sql_rq_before .= "WHERE tbl_rq_form_item.mark_cut_stock=1 AND tbl_rq_form_item.cut_date<'".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_rq_before .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_rq_before .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_rq_before .= "GROUP BY tbl_rq_form_item.fabric_id ORDER BY tbl_rq_form_item.fabric_id ASC";
$rs_rq_before = $conn->query($sql_rq_before);

while($row_rq_before = $rs_rq_before->fetch_assoc()){

	if(isset($a_used_before[($row_rq_before["fabric_id"])])){
		$a_used_before[($row_rq_before["fabric_id"])] += $row_rq_before["used_val"];
	}else{
		$a_used_before[($row_rq_before["fabric_id"])] = $row_rq_before["used_val"];
	}
	
}


$a_adj_before = array();
$sql_adj_before = "SELECT tbl_adjust.fabric_id,tbl_adjust.in_out,SUM(tbl_adjust.adj_value) AS adj_val FROM tbl_adjust ";
$sql_adj_before .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_adjust.fabric_id ";
$sql_adj_before .= "WHERE tbl_adjust.adj_date<'".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_adj_before .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_adj_before .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_adj_before .= "GROUP BY tbl_adjust.fabric_id,tbl_adjust.in_out ORDER BY tbl_adjust.fabric_id ASC,tbl_adjust.in_out ASC";
$rs_adj_before = $conn->query($sql_adj_before);

while($row_adj_before = $rs_adj_before->fetch_assoc()){

	$tmp_val = $row_adj_before["adj_val"];
	if($row_adj_before["in_out"]=="OUT"){
		$tmp_val = "-".$tmp_val;
	}
	
	if(isset($a_adj_before[($row_adj_before["fabric_id"])])){
		$a_adj_before[($row_adj_before["fabric_id"])] += $tmp_val;
	}else{
		$a_adj_before[($row_adj_before["fabric_id"])] = $tmp_val;
	}	
	
}

//----After
$a_used_after = array();
$sql_used_after = "SELECT used_detail.materials_id AS fabric_id,SUM(used_detail.used_detail_used) AS used_val FROM used_detail ";
$sql_used_after .= "LEFT JOIN used_head ON used_detail.used_id=used_head.used_id WHERE used_head.used_date>='".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_used_after .= "AND used_detail.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_used_after .= "AND used_detail.used_detail_color='".addslashes($_POST["select_color"])."' ";
}
$sql_used_after .= "GROUP BY fabric_id ORDER BY fabric_id ASC";
$rs_used_after = $conn->query($sql_used_after);

while($row_used_after = $rs_used_after->fetch_assoc()){
	$a_used_after[($row_used_after["fabric_id"])] = $row_used_after["used_val"];
}

$sql_rq_after = "SELECT tbl_rq_form_item.fabric_id,SUM(tbl_rq_form_item.used) AS used_val FROM tbl_rq_form_item ";
$sql_rq_after .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";
$sql_rq_after .= "WHERE tbl_rq_form_item.mark_cut_stock=1 AND tbl_rq_form_item.cut_date>='".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_rq_after .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_rq_after .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_rq_after .= "GROUP BY tbl_rq_form_item.fabric_id ORDER BY tbl_rq_form_item.fabric_id ASC";
$rs_rq_after = $conn->query($sql_rq_after);

while($row_rq_after = $rs_rq_after->fetch_assoc()){

	if(isset($a_used_after[($row_rq_after["fabric_id"])])){
		$a_used_after[($row_rq_after["fabric_id"])] += $row_rq_after["used_val"];
	}else{
		$a_used_after[($row_rq_after["fabric_id"])] = $row_rq_after["used_val"];
	}
	
}

/*echo "===a_used_after===<pre>";
print_r($a_used_after);
echo "</pre><hr>";*/

$a_adj_after = array();
$sql_adj_after = "SELECT tbl_adjust.fabric_id,tbl_adjust.in_out,SUM(tbl_adjust.adj_value) AS adj_val FROM tbl_adjust ";
$sql_adj_after .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_adjust.fabric_id ";
$sql_adj_after .= "WHERE tbl_adjust.adj_date>='".$focus_date."' ";
if($_POST["cat_id"]!="=all="){
	$sql_adj_after .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_adj_after .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_adj_after .= "GROUP BY tbl_adjust.fabric_id,tbl_adjust.in_out ORDER BY tbl_adjust.fabric_id ASC,tbl_adjust.in_out ASC";
$rs_adj_after = $conn->query($sql_adj_after);

while($row_adj_after = $rs_adj_after->fetch_assoc()){

	$tmp_val = $row_adj_after["adj_val"];
	if($row_adj_after["in_out"]=="OUT"){
		$tmp_val = "-".$tmp_val;
	}
	
	if(isset($a_adj_after[($row_adj_after["fabric_id"])])){
		$a_adj_after[($row_adj_after["fabric_id"])] += $tmp_val;
	}else{
		$a_adj_after[($row_adj_after["fabric_id"])] = $tmp_val;
	}	
	
}

/*echo "===a_adj_after===<pre>";
print_r($a_adj_after);
echo "</pre><hr>";*/

//---In month
if($month=="12"){
	$focus_date2 = (intval($year)+1)."-01-01 00:00:00";
}else{
	if(intval($month)<9){
		$focus_date2 = $year."-0".(intval($month)+1)."-01 00:00:00";
	}else{
		$focus_date2 = $year."-".(intval($month)+1)."-01 00:00:00";
	}
	
}

$a_used_month = array();
$sql_used_month = "SELECT used_detail.materials_id AS fabric_id,SUBSTR(used_date,1,10) AS tmp_date,SUM(used_detail.used_detail_used) AS used_val FROM used_detail ";
$sql_used_month .= "LEFT JOIN used_head ON used_detail.used_id=used_head.used_id WHERE used_head.used_date>='".$focus_date."' AND used_head.used_date<'".$focus_date2."' ";
if($_POST["cat_id"]!="=all="){
	$sql_used_month .= "AND used_detail.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_used_month .= "AND used_detail.used_detail_color='".addslashes($_POST["select_color"])."' ";
}
$sql_used_month .= "GROUP BY fabric_id,tmp_date ORDER BY fabric_id ASC";
$rs_used_month = $conn->query($sql_used_month);

while($row_used_month = $rs_used_month->fetch_assoc()){
	$a_used_month[($row_used_month["fabric_id"])][($row_used_month["tmp_date"])] = $row_used_month["used_val"];
	if(isset($a_used_month[($row_used_month["fabric_id"])]["used_total"])){
		$a_used_month[($row_used_month["fabric_id"])]["used_total"] += $row_used_month["used_val"];
	}else{
		$a_used_month[($row_used_month["fabric_id"])]["used_total"] = $row_used_month["used_val"];
	}
}

$sql_rq_month = "SELECT tbl_rq_form_item.fabric_id,SUBSTR(tbl_rq_form_item.cut_date,1,10) AS tmp_date,SUM(tbl_rq_form_item.used) AS used_val FROM tbl_rq_form_item ";
$sql_rq_month .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";
$sql_rq_month .= "WHERE tbl_rq_form_item.mark_cut_stock=1 AND tbl_rq_form_item.cut_date>='".$focus_date."' AND tbl_rq_form_item.cut_date<'".$focus_date2."' ";
if($_POST["cat_id"]!="=all="){
	$sql_rq_month .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_rq_month .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_rq_month .= "GROUP BY tbl_rq_form_item.fabric_id,tmp_date ORDER BY tbl_rq_form_item.fabric_id ASC";
$rs_rq_month = $conn->query($sql_rq_month);

while($row_rq_month = $rs_rq_month->fetch_assoc()){

	if(isset($a_used_month[($row_rq_month["fabric_id"])][($row_rq_month["tmp_date"])])){
		$a_used_month[($row_rq_month["fabric_id"])][($row_rq_month["tmp_date"])] += $row_rq_month["used_val"];
	}else{
		$a_used_month[($row_rq_month["fabric_id"])][($row_rq_month["tmp_date"])] = $row_rq_month["used_val"];
	}
	
	if(isset($a_used_month[($row_rq_month["fabric_id"])]["used_total"])){
		$a_used_month[($row_rq_month["fabric_id"])]["used_total"] += $row_rq_month["used_val"];
	}else{
		$a_used_month[($row_rq_month["fabric_id"])]["used_total"] = $row_rq_month["used_val"];
	}
	
}

/*echo "===a_used_month===<pre>";
print_r($a_used_month);
echo "</pre><hr>";*/

$a_adj_month = array();
$sql_adj_month = "SELECT tbl_adjust.fabric_id,SUBSTR(tbl_adjust.adj_date,1,10) AS tmp_date,tbl_adjust.in_out,SUM(tbl_adjust.adj_value) AS adj_val FROM tbl_adjust ";
$sql_adj_month .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_adjust.fabric_id ";
$sql_adj_month .= "WHERE tbl_adjust.adj_date>='".$focus_date."' AND tbl_adjust.adj_date<'".$focus_date2."' ";
if($_POST["cat_id"]!="=all="){
	$sql_adj_month .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_adj_month .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_adj_month .= "GROUP BY tbl_adjust.fabric_id,tmp_date,tbl_adjust.in_out ORDER BY tbl_adjust.fabric_id ASC,tbl_adjust.in_out ASC";
$rs_adj_month = $conn->query($sql_adj_month);

while($row_adj_month = $rs_adj_month->fetch_assoc()){

	$tmp_val = $row_adj_month["adj_val"];
	if($row_adj_month["in_out"]=="OUT"){
		$tmp_val = $tmp_val*(-1);
	}
	
	if(isset($a_adj_month[($row_adj_month["fabric_id"])][($row_adj_month["tmp_date"])])){
		$a_adj_month[($row_adj_month["fabric_id"])][($row_adj_month["tmp_date"])] += $tmp_val;
	}else{
		$a_adj_month[($row_adj_month["fabric_id"])][($row_adj_month["tmp_date"])] = $tmp_val;
	}

	if(isset($a_adj_month[($row_adj_month["fabric_id"])]["adj_total"])){
		$a_adj_month[($row_adj_month["fabric_id"])]["adj_total"] += $tmp_val;
	}else{
		$a_adj_month[($row_adj_month["fabric_id"])]["adj_total"] = $tmp_val;
	}

}

/*echo "===a_adj_month===<pre>";
print_r($a_adj_month);
echo "</pre><hr>";*/

?>

<?php
if( isset($_POST["export_flag"]) && $_POST["export_flag"]==1 ){
	echo '</head><body>';
}
?>
<div class="div-report">
<table id="sum_report" class="tbl-report" cellspacing="0" style="width: 2800px; ">
	<thead >
		<tr>
			<th rowspan="2" class="cls-normal-head">Date IN</th>
			<th rowspan="2" class="cls-normal-head">Supplier</th>
			<th rowspan="2" class="cls-normal-head">PO#</th>
			<th rowspan="2" class="cls-normal-head">RECEIPT#</th>
			<th rowspan="2" class="cls-normal-head">Materials</th>
			<th rowspan="2" class="cls-normal-head">Color</th>
			<th rowspan="2" class="cls-normal-head">Box</th>
			<th rowspan="2" class="cls-normal-head">No.</th>
			<th rowspan="2" class="cls-normal-head">Brought<br>Forward</th>
			<th rowspan="2" class="cls-normal-head">Stock<br>IN</th>
			<th rowspan="2" class="cls-normal-head">Unit<br>Price</th>
			<th rowspan="2" class="cls-total">Total<br>Price</th>
			<th rowspan="2" class="cls-used">Used<br>(Kg.)</th>
			<th rowspan="2" class="cls-adjust">Adjust</th>
			<th rowspan="2" class="cls-balance">Material<br>Balance</th>
			<th rowspan="2" class="cls-total">Total Price<br>Used</th>
			<th rowspan="2" class="cls-total">Total Price<br>Adjust</th>
			<th rowspan="2" class="cls-total">Balance<br>Amount</th>
			<?php
			$num_date = intval(date("t",strtotime($focus_date)));
			?>
			<th colspan="<?php echo $num_date; ?>" ><?php echo date("F Y",strtotime($focus_date)); ?></th>
			
		</tr>
		<tr>
			<?php
			$tmp_month = date("Y-m-",strtotime($focus_date));
			for($i=1;$i<=$num_date;$i++){

				if($i<10){
					$tmp_d = "0".$i;
				}else{
					$tmp_d = $i;
				}

				$day = date("w",strtotime($tmp_month.$tmp_d));
				$cls_sunday = "cls-normal-day-head";
				if($day=="0"){
					$cls_sunday = "cls-sunday-head";
				}

			?>
			<th class="<?php echo $cls_sunday; ?>"><?php echo $i; ?></th>
			<?php
			}
			?>
		</tr>
	</thead>
	
	<tbody>
<?php
//-----Show
$sql_info = "SELECT fabric.*,cat.cat_name_en,supplier.supplier_name,po_head.po_no AS po_no1,tbl_packing.po_no AS po_no2,receipt.receipt_number FROM fabric ";
$sql_info .= "LEFT JOIN cat ON cat.cat_id=fabric.cat_id ";
$sql_info .= "LEFT JOIN supplier ON supplier.supplier_id=fabric.supplier_id ";
$sql_info .= "LEFT JOIN po_head ON po_head.po_id=fabric.po_id ";
$sql_info .= "LEFT JOIN receipt ON receipt.receipt_id=fabric.receipt_id ";
$sql_info .= "LEFT JOIN tbl_packing_list ON tbl_packing_list.fabric_id=fabric.fabric_id ";
$sql_info .= "LEFT JOIN tbl_packing ON tbl_packing.pac_id=tbl_packing_list.pac_id ";
$sql_info .= "WHERE fabric.fabric_date_create<'".$focus_date2."' ";
if($_POST["cat_id"]!="=all="){
	$sql_info .= "AND fabric.cat_id='".$_POST["cat_id"]."' ";
}
if($_POST["select_color"]!="=all="){
	$sql_info .= "AND fabric.fabric_color='".addslashes($_POST["select_color"])."' ";
}
$sql_info .= "ORDER BY cat.cat_name_en ASC,fabric.fabric_color ASC,fabric.fabric_box ASC,fabric.fabric_no ASC,fabric.fabric_date_create ASC";
$rs_info = $conn->query($sql_info);

$total_bforward = 0.0;
$total_stockin = 0.0;

$total_tprice = 0.0;
$total_used_kg = 0.0;
$total_adj_kg = 0.0;
$total_mbalance = 0.0;
$total_tp_used = 0.0;
$total_tp_adj = 0.0;
$total_bamount = 0.0;

while($row_info = $rs_info->fetch_assoc()){

	$tmp_fabric_id = $row_info["fabric_id"];

	if( !isset($a_used_after[$tmp_fabric_id]) && !isset($a_adj_after[$tmp_fabric_id]) && ($row_info["fabric_balance"]==0) ){

		//---ignore

	}else{

		if(!isset($a_used_before[$tmp_fabric_id])){ $a_used_before[$tmp_fabric_id] = 0.0; }
		if(!isset($a_adj_before[$tmp_fabric_id])){ $a_adj_before[$tmp_fabric_id] = 0.0; }
		if(!isset($a_used_after[$tmp_fabric_id])){ $a_used_after[$tmp_fabric_id] = 0.0; }
		if(!isset($a_adj_after[$tmp_fabric_id])){ $a_adj_after[$tmp_fabric_id] = 0.0; }
		if(!isset($a_used_month[$tmp_fabric_id])){ $a_used_month[$tmp_fabric_id] = 0.0; }
		if(!isset($a_adj_month[$tmp_fabric_id])){ $a_adj_month[$tmp_fabric_id] = 0.0; }
		
		if($a_adj_before[$tmp_fabric_id]<0){
			$tmp_adj_before = abs($a_adj_before[$tmp_fabric_id]);
			$stock_in_start = ($row_info["fabric_in_piece"]-$tmp_adj_before)-$a_used_before[$tmp_fabric_id];
		}else{
			$stock_in_start = ($row_info["fabric_in_piece"]+$a_adj_before[$tmp_fabric_id])-$a_used_before[$tmp_fabric_id];
		}

		
		$total_in_price = $stock_in_start*$row_info["fabric_in_price"];
		$material_balance = $stock_in_start-$a_used_month[$tmp_fabric_id]["used_total"]+($a_adj_month[$tmp_fabric_id]["adj_total"]);
		$total_price_used = $a_used_month[$tmp_fabric_id]["used_total"]*$row_info["fabric_in_price"];
		$total_price_adj = $a_adj_month[$tmp_fabric_id]["adj_total"]*$row_info["fabric_in_price"];
		$balance_amount = $material_balance*$row_info["fabric_in_price"];

		/*$mat_class = str_replace(" ", "_", $row_info["cat_name_en"]);
		$mat_class = "mat_".str_replace("+", "_", $mat_class);
		$color_class = "color_".str_replace(" ", "_", $row_info["fabric_color"]);

		$zero_class = "";
		if( number_format($a_used_month[$tmp_fabric_id]["used_total"],2)=="0.00" || number_format($a_used_month[$tmp_fabric_id]["used_total"],2)=="-0.00" ){
			$zero_class = "used_zero";
		}*/

		$this_month_class = "";
		if( date("Y-m",strtotime($row_info["fabric_date_create"]))==date("Y-m",strtotime($focus_date)) ){
			$this_month_class = "this_month";
		}


		$b_show = true;
		if( $_POST["have_data"]==1 && $a_used_month[$tmp_fabric_id]["used_total"]==0.0 ){
			$b_show = false;
		}

		if( $_POST["in_this_month"]==1 && $this_month_class=="" ){
			$b_show = false;
		}

		if($b_show){
	?>
		<tr class="content_data">
			<td class="text-left"><?php echo date("Y-m-d",strtotime($row_info["fabric_date_create"])); ?></td>
			<td class="text-center"><?php echo $row_info["supplier_name"]; ?></td>
			<td class="text-center">
			<?php 
				if($row_info["new_form"]=="0"){
					echo $row_info["po_no1"]; 
				}else{
					echo $row_info["po_no2"]; 
				}
			?>
			</td>
			<td class="text-center"><?php echo $row_info["receipt_number"]; ?></td>
			<td class="text-center"><?php echo $row_info["cat_name_en"]; ?></td>
			<td class="text-center"><?php echo $row_info["fabric_color"]; ?></td>
			<td class="text-center"><?php echo $row_info["fabric_box"]; ?></td>
			<td class="text-center"><?php echo $row_info["fabric_no"]; ?></td>
			<td class="cls-number">
			<?php 
			if($this_month_class==""){
				echo (number_format($stock_in_start,2)=="-0.00")?"0.00":number_format($stock_in_start,2); 
				$total_bforward += $stock_in_start;
			}else{
				echo "&nbsp;";
			}
			?>
			</td>
			<td class="cls-number">
			<?php 
			if($this_month_class=="this_month"){
				echo (number_format($stock_in_start,2)=="-0.00")?"0.00":number_format($stock_in_start,2); 
				$total_stockin += $stock_in_start;
			}else{
				echo "&nbsp;";
			}
			?>
			</td>
			<?php
				
				$total_tprice += $total_in_price;
				$total_used_kg += $a_used_month[$tmp_fabric_id]["used_total"];
				$total_adj_kg += $a_adj_month[$tmp_fabric_id]["adj_total"];
				$total_mbalance += $material_balance;
				$total_tp_used += $total_price_used;
				$total_tp_adj += $total_price_adj;
				$total_bamount += $balance_amount;
			?>
			<td class="cls-number"><?php echo number_format($row_info["fabric_in_price"],2); ?></td>
			<td class="cls-total"><?php echo (number_format($total_in_price,2)=="-0.00")?"0.00":number_format($total_in_price,2); ?></td>
			<td class="cls-used"><?php echo number_format($a_used_month[$tmp_fabric_id]["used_total"],2); ?></td>
			<td class="cls-adjust">
				<?php
				if($a_adj_month[$tmp_fabric_id]["adj_total"]!=0){
				?>
				<div onclick="showAdjustLog(<?php echo $row_info["fabric_id"]; ?>,'<?php echo $row_info["fabric_box"]; ?>','<?php echo $row_info["fabric_no"]; ?>');" style="cursor: pointer;" data-toggle="modal" data-target="#showLogModal">
				<?php 
					if($a_adj_month[$tmp_fabric_id]["adj_total"]<0){
						echo '<font color=red>';
					}else{
						echo '<font color=green>';
					}
					echo number_format($a_adj_month[$tmp_fabric_id]["adj_total"],2); 
					echo '</font>';
				?>
				</div>
				<?php
				}else{
					echo "0.00";
				}
				?>
			</td>
			<td class="cls-balance"><?php echo (number_format($material_balance,2)=="-0.00")?"0.00":number_format($material_balance,2); ?></td>
			<td class="cls-total"><?php echo (number_format($total_price_used,2)=="-0.00")?"0.00":number_format($total_price_used,2); ?></td>
			<td class="cls-total"><?php echo (number_format($total_price_adj,2)=="-0.00")?"0.00":number_format($total_price_adj,2); ?></td>
			<td class="cls-total"><?php echo (number_format($balance_amount,2)=="-0.00")?"0.00":number_format($balance_amount,2); ?></td>
			<?php
			$tmp_month = date("Y-m-",strtotime($focus_date));

			for($i=1;$i<=$num_date;$i++){

				if($i<10){
					$tmp_d = "0".$i;
				}else{
					$tmp_d = $i;
				}

				$day = date("w",strtotime($tmp_month.$tmp_d));
				$cls_sunday = "";
				if($day=="0"){
					$cls_sunday = "cls-sunday";
				}

				$tmp_focus = $tmp_month.$tmp_d;
				$tmp_used = "";

				$have_info = false;

				if(isset($a_used_month[$tmp_fabric_id][$tmp_focus])){
					$tmp_used = number_format($a_used_month[$tmp_fabric_id][$tmp_focus],2);
					$have_info = true;
				}
			?>
			<td class="<?php echo $cls_sunday; ?>" style="width:30px;">
				<?php 
				if($tmp_used>0){
					if($have_info){
						?>
						<div class="have-info" onclick="showTransInfo(<?php echo $tmp_fabric_id; ?>,'<?php echo $tmp_month.$tmp_d; ?>');" data-toggle="modal" data-target="#showTransactionModal">
						<?php
					}
					echo $tmp_used; 
					if($have_info){
						?>
						</div>
						<?php
					}
				}
				?>	
			</td>
			<?php
			}
			?>
		</tr>
	<?php
		}
	}
}
?>
		<tr class="total-row">
			<td colspan="8" align="right"> Total: </td>
			<td align="right"><?php echo (number_format($total_bforward,2)=="-0.00")?"0.00":number_format($total_bforward,2); ?></td>
			<td align="right"><?php echo (number_format($total_stockin,2)=="-0.00")?"0.00":number_format($total_stockin,2); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo (number_format($total_tprice,2)=="-0.00")?"0.00":number_format($total_tprice,2); ?></td>
			<td align="right"><?php echo (number_format($total_used_kg,2)=="-0.00")?"0.00":number_format($total_used_kg,2); ?></td>
			<td align="right"><?php echo (number_format($total_adj_kg,2)=="-0.00")?"0.00":number_format($total_adj_kg,2); ?></td>
			<td align="right"><?php echo (number_format($total_mbalance,2)=="-0.00")?"0.00":number_format($total_mbalance,2); ?></td>
			<td align="right"><?php echo (number_format($total_tp_used,2)=="-0.00")?"0.00":number_format($total_tp_used,2); ?></td>
			<td align="right"><?php echo (number_format($total_tp_adj,2)=="-0.00")?"0.00":number_format($total_tp_adj,2); ?></td>
			<td align="right"><?php echo (number_format($total_bamount,2)=="-0.00")?"0.00":number_format($total_bamount,2); ?></td>
			<td colspan="<?php echo $num_date; ?>">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>
<?php
if( isset($_POST["export_flag"]) && $_POST["export_flag"]==1 ){
	echo '</body></html>';
}
?>