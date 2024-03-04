<?php
$cat_id_list = $_POST["cat_id_list"];
if($cat_id_list==""){
	exit();
}

require_once('../../db.php');

//-----Select balance sum
$sql_select = "SELECT fabric.cat_id,cat.cat_name_en,fabric.fabric_color,fabric.supplier_id,supplier.supplier_name,fabric.fabric_type_unit,SUM(fabric.fabric_balance) AS sum_num ";
$sql_select .= "FROM fabric ";
$sql_select .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_select .= "LEFT JOIN supplier ON supplier.supplier_id=fabric.supplier_id ";
$sql_select .= "WHERE fabric.fabric_balance>0 AND fabric.cat_id IN (".$cat_id_list.")";
$sql_select .= "GROUP BY fabric.cat_id,fabric.fabric_color,fabric.supplier_id,fabric.fabric_type_unit ";
$sql_select .= "ORDER BY cat.cat_name_en ASC,fabric.fabric_color ASC,fabric.fabric_type_unit ASC ";

$rs_select = $conn->query($sql_select);

$a_data = array();
$a_cat = array();
$a_color = array();

while ($row_select = $rs_select->fetch_assoc()) {

	$a_cat[($row_select["cat_id"])] = $row_select["cat_name_en"];

	if($_POST["view_style"]=="L"){

		if( isset($a_data[($row_select["cat_id"])][($row_select["fabric_type_unit"])][($row_select["fabric_color"])]["balance"]) ){
			$a_data[($row_select["cat_id"])][($row_select["fabric_type_unit"])][($row_select["fabric_color"])]["balance"] += $row_select["sum_num"];
		}else{
			$a_data[($row_select["cat_id"])][($row_select["fabric_type_unit"])][($row_select["fabric_color"])]["balance"] = $row_select["sum_num"];
		}
		
	}else{

		$a_data[($row_select["fabric_color"])][($row_select["fabric_type_unit"])][($row_select["cat_id"])]["by_supp"][($row_select["supplier_id"])]["supp_name"] = $row_select["supplier_name"];
		$a_data[($row_select["fabric_color"])][($row_select["fabric_type_unit"])][($row_select["cat_id"])]["by_supp"][($row_select["supplier_id"])]["value"] = $row_select["sum_num"];

		if( isset($a_data[($row_select["fabric_color"])][($row_select["fabric_type_unit"])][($row_select["cat_id"])]["balance"]) ){
			$a_data[($row_select["fabric_color"])][($row_select["fabric_type_unit"])][($row_select["cat_id"])]["balance"] += $row_select["sum_num"];
		}else{
			$a_data[($row_select["fabric_color"])][($row_select["fabric_type_unit"])][($row_select["cat_id"])]["balance"] = $row_select["sum_num"];
		}
		
	}

	if(!in_array($row_select["fabric_color"],$a_color)){
		$a_color[] = $row_select["fabric_color"];
	}
}

//-----Select producing sum
$sql_select2 = "SELECT fabric.cat_id,cat.cat_name_en,fabric.fabric_color,fabric.fabric_type_unit,SUM(fabric.fabric_balance) AS sum_prod ";
$sql_select2 .= "FROM fabric ";
$sql_select2 .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_select2 .= "WHERE fabric.fabric_balance>0 AND fabric.on_producing=1 AND fabric.cat_id IN (".$cat_id_list.")";
$sql_select2 .= "GROUP BY fabric.cat_id,fabric.fabric_color,fabric.fabric_type_unit ";
$sql_select2 .= "ORDER BY cat.cat_name_en ASC,fabric.fabric_color ASC,fabric.fabric_type_unit ASC ";

$rs_select2 = $conn->query($sql_select2);

while ($row_select2 = $rs_select2->fetch_assoc()) {

	if($_POST["view_style"]=="L"){
		$a_data[($row_select2["cat_id"])][($row_select2["fabric_type_unit"])][($row_select2["fabric_color"])]["producing"] = $row_select2["sum_prod"];
	}else{
		$a_data[($row_select2["fabric_color"])][($row_select2["fabric_type_unit"])][($row_select2["cat_id"])]["producing"] = $row_select2["sum_prod"];
	}

}

$a_unit = array();
$a_unit["1"] = 'Piece';
$a_unit["2"] = 'Yard';
$a_unit["3"] = 'KG';

//----Select forcast sum
$sql_select2 = "SELECT forecast_detail.cat_id,cat.cat_name_en,forecast_detail.forecast_detail_color,forecast_detail.forecast_detail_unit_type,SUM(forecast_detail.forecast_detail_used) AS sum_forcast ";
$sql_select2 .= "FROM forecast_detail ";
$sql_select2 .= "LEFT JOIN cat ON forecast_detail.cat_id=cat.cat_id ";
$sql_select2 .= "LEFT JOIN forecast_head ON forecast_detail.forecast_id=forecast_head.forecast_id ";
$sql_select2 .= "WHERE forecast_head.is_produced=0 ";//----If orders has been produced this flag will be 1
$sql_select2 .= "GROUP BY forecast_detail.cat_id,forecast_detail.forecast_detail_color,forecast_detail.forecast_detail_unit_type ";
$sql_select2 .= "ORDER BY cat.cat_name_en ASC,forecast_detail.forecast_detail_color ASC,forecast_detail.forecast_detail_unit_type ASC ";

$rs_select2 = $conn->query($sql_select2);

while ($row_select2 = $rs_select2->fetch_assoc()) {

	if($_POST["view_style"]=="L"){
		$a_data[($row_select2["cat_id"])][($row_select2["forecast_detail_unit_type"])][($row_select2["forecast_detail_color"])]["forecast"] = $row_select2["sum_forcast"];
	}else{
		$a_data[($row_select2["forecast_detail_color"])][($row_select2["forecast_detail_unit_type"])][($row_select2["cat_id"])]["forecast"] = $row_select2["sum_forcast"];
	}

}

//----Select ordered sum
$sql_select3 = "SELECT tbl_f_ordered_item.cat_id,cat.cat_name_en,tbl_f_ordered_item.color,'3' AS unit_type,SUM(tbl_f_ordered_item.qty) AS sum_ordered ";
$sql_select3 .= "FROM tbl_f_ordered_item ";
$sql_select3 .= "LEFT JOIN cat ON tbl_f_ordered_item.cat_id=cat.cat_id ";
$sql_select3 .= "WHERE tbl_f_ordered_item.is_receive=0 AND tbl_f_ordered_item.cat_id IN (".$cat_id_list.") ";//----If orders has been received this flag will be 1
$sql_select3 .= "GROUP BY tbl_f_ordered_item.cat_id,tbl_f_ordered_item.color ";
$sql_select3 .= "ORDER BY cat.cat_name_en ASC,tbl_f_ordered_item.color ASC ";

$rs_select3 = $conn->query($sql_select3);

while ($row_select3 = $rs_select3->fetch_assoc()) {

	if($_POST["view_style"]=="L"){
		$a_data[($row_select3["cat_id"])][($row_select3["unit_type"])][($row_select3["color"])]["ordered"] = $row_select3["sum_ordered"];
	}else{
		$a_data[($row_select3["color"])][($row_select3["unit_type"])][($row_select3["cat_id"])]["ordered"] = $row_select3["sum_ordered"];
	}

	if(!in_array($row_select3["color"],$a_color)){
		$a_color[] = $row_select3["color"];
	}
}

?>
<script src="<?php echo $main_path; ?>assets/js/jquery-stickytable.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $main_path; ?>assets/css/jquery-stickytable.css">


<script type="text/javascript">
$(function() {
	//load stickyTable with overflowy option
	$('#tbl_mat_show').stickyTable({overflowy: true});

});
</script>
<button style="float: right; margin-top:-15px;" class="btn btn-success" onclick="printReport();">Print</button>
<div class="div-fabric-content" style="max-height:460px; width:100%;">
<div id="d_print">
<style type="text/css">
.fabric-content th{
	background-color: #AFF;
	font-size: 12px;
	font-weight: bold;
	color: #000;
	border:1px #000 solid;
	text-align: center;
	height: 40px;
}
.fabric-content td{
	background-color: #FFF;
	font-size: 12px;
	color: #333;
	border:1px #000 solid;
	height: 40px;
	text-align: center;
}
</style>
<table id="tbl_mat_show" class="table fabric-content" >
<?php
if($_POST["view_style"]=="L"){
?>
	<thead>
	<tr>
		<th rowspan="2"><div style="min-width: 130px;">Material</div></th>
		<th rowspan="2">Unit</th>
		<?php foreach($a_color as $color_index => $color_name ){ ?>
		<th colspan="4" ><?php echo $color_name; ?></th>
		<?php } ?>
	</tr>
	<tr>
		<?php for($i=0;$i<sizeof($a_color);$i++ ){ ?>
		<th title="BALANCE (Producing)">BAL<br>(Producing)</th>
		<th title="FORECAST">FORE</th>
		<th title="ORDERED">ORD</th>
		<th title="RESULT">RES</th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
	<?php 
	foreach($a_cat as $cat_id => $cat_name_en ){ 
		
		$n_num_unit = sizeof($a_data[$cat_id]);
		$count_unit = 0;

		foreach($a_data[$cat_id] as $fabric_type_unit => $a_data2 ){ 
	?>
	<tr class="tr-data">
		<?php if($count_unit==0){ ?>
		
		<th rowspan="<?php echo $n_num_unit; ?>"><?php echo $cat_name_en; ?></th>
		<?php } ?>	
		<td><?php echo $a_unit[$fabric_type_unit]; ?></td>
		<?php foreach($a_color as $color_index => $color_name ){ ?>
		<td><?php echo (isset($a_data2[$color_name]["balance"])?$a_data2[$color_name]["balance"]:"-").(isset($a_data2[$color_name]["producing"])?'<br>('.$a_data2[$color_name]["producing"].')':""); ?></td>
		<td><?php echo (isset($a_data2[$color_name]["forecast"])?$a_data2[$color_name]["forecast"]:"-"); ?></td>
		<td><?php echo (isset($a_data2[$color_name]["ordered"])?$a_data2[$color_name]["ordered"]:"-"); ?></td>
		<td>
			<?php 
			$tmp_balance = (isset($a_data2[$color_name]["balance"])?floatval($a_data2[$color_name]["balance"]):0);
			$tmp_forecast = (isset($a_data2[$color_name]["forecast"])?floatval($a_data2[$color_name]["forecast"]):0);
			$tmp_ordered = (isset($a_data2[$color_name]["ordered"])?floatval($a_data2[$color_name]["ordered"]):0);
			$tmp_result = $tmp_balance-$tmp_forecast+$tmp_ordered;

			$use_color = "#00F";

			if($tmp_result<0){
				$use_color = "#F00";
			}

			echo '<span style="color:'.$use_color.';">'.number_format($tmp_result,2).'</span>';
			?>
		</td>
		<?php } ?>
	</tr>
	<?php 
			$count_unit++;
		}
	} 
	?>
	</tbody>
<?php
}else{
?>
	<thead>
	<tr>
		<th rowspan="2"><div style="min-width: 130px;">Color</div></th>
		<th rowspan="2">Unit</th>
		<?php foreach( $a_cat as $cat_id => $cat_name_en ){ ?>
		<th colspan="4" ><?php echo $cat_name_en; ?></th>
		<?php } ?>
	</tr>
	<tr>
		<?php for($i=0;$i<sizeof($a_cat);$i++ ){ ?>
		<th >BALANCE</th>
		<th >FORECAST</th>
		<th >ORDERED</th>
		<th >RESULT</th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
	<?php 
	foreach( $a_color as $color_index => $color_name ){ 
		
		$n_num_unit = sizeof($a_data[$color_name]);
		$count_unit = 0;

		foreach($a_data[$color_name] as $fabric_type_unit => $a_data2 ){ 
	?>
	<tr class="tr-data">
		<?php if($count_unit==0){ ?>
		
		<td rowspan="<?php echo $n_num_unit; ?>"><?php echo $color_name; ?></td>
		<?php } ?>	
		<td><?php echo $a_unit[$fabric_type_unit]; ?></td>
		<?php foreach($a_cat as $cat_id => $cat_name_en ){ ?>

		<td>
			<?php //echo (isset($a_data2[$cat_id]["balance"])?$a_data2[$cat_id]["balance"]:"-").(isset($a_data2[$cat_id]["producing"])?'<br>('.$a_data2[$cat_id]["producing"].')':""); 
			if( isset($a_data2[$cat_id]["balance"]) ){
				/*echo "<pre>";
				print_r($a_data2[$cat_id]["balance"]);
				echo "</pre>";*/
				
			?>
				<table align="right">
					<tr style="height: 26px;">
						<?php 
						foreach( $a_data2[$cat_id]["by_supp"] as $supp_id => $a_value ){
						?>
						<td style="height: 26px; max-width:160px; padding: 5px; font-size: 15px;"><?php echo $a_value["supp_name"]; ?></td>
						<?php
						}
						?>
						<td style="width:80px; padding: 5px; font-size: 15px; background-color: #AAA;">Total</td>
						<td style="width:80px; padding: 5px; font-size: 15px; background-color: #AAA;">In use</td>
					</tr>
					<tr>
						<?php 
						$sum_total = 0.0;
						foreach( $a_data2[$cat_id]["by_supp"] as $supp_id2 => $a_value2 ){
						?>
						<td style=" font-size: 15px;"><?php echo $a_value2["value"]; ?></td>
						<?php
							$sum_total += floatval($a_value2["value"]);
						}
						?>
						<td style=" font-size: 15px; background-color: #DDD;"><?php echo number_format($sum_total,2); ?></td>
						<td style=" font-size: 15px; background-color: #DDD;"><?php echo (isset($a_data2[$cat_id]["producing"])?number_format($a_data2[$cat_id]["producing"],2):"-"); ?></td>
					</tr>
				</table>
			<?php
			}else{
				echo "-";
			}
			?>	
		</td>

		<td style=" font-size: 15px;"><?php echo (isset($a_data2[$cat_id]["forecast"])?$a_data2[$cat_id]["forecast"]:"-"); ?></td>
		<td style=" font-size: 15px;"><?php echo (isset($a_data2[$cat_id]["ordered"])?$a_data2[$cat_id]["ordered"]:"-"); ?></td>
		<td style=" font-size: 15px;">
			<?php 
			$tmp_balance = (isset($a_data2[$cat_id]["balance"])?floatval($a_data2[$cat_id]["balance"]):0);
			$tmp_forecast = (isset($a_data2[$cat_id]["forecast"])?floatval($a_data2[$cat_id]["forecast"]):0);
			$tmp_ordered = (isset($a_data2[$cat_id]["ordered"])?floatval($a_data2[$cat_id]["ordered"]):0);
			$tmp_result = $tmp_balance-$tmp_forecast+$tmp_ordered;

			$use_color = "#00F";

			if($tmp_result<0){
				$use_color = "#F00";
			}

			echo '<span style="color:'.$use_color.';">'.number_format($tmp_result,2).'</span>';
			?>
		</td>
		<?php } ?>
	</tr>
	<?php 
			$count_unit++;
		}
	} 
	?>
	</tbody>
<?php
}
?>
</table>
</div>
</div>
<script type="text/javascript">
	
function printReport(){

	var divContents = $("#d_print").html();
    var printWindow = window.open('', '', 'height=1500,width=900');
    printWindow.document.write('<html><head><title>Forecast Report</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();

}
</script>