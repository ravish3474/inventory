<?php
$cat_id_list = $_POST["cat_id_list"];
if($cat_id_list==""){
	exit();
}

require_once('../../db.php');

$a_cat_supp = array();

//-----Select balance sum
$sql_select = "SELECT fabric.cat_id,cat.cat_name_en,fabric.fabric_color,fabric.supplier_id,supplier.supplier_name,fabric.fabric_type_unit,SUM(fabric.fabric_balance) AS sum_num ";
$sql_select .= "FROM fabric ";
$sql_select .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_select .= "LEFT JOIN supplier ON supplier.supplier_id=fabric.supplier_id ";
$sql_select .= "WHERE fabric.fabric_balance>0 AND fabric.cat_id IN (".$cat_id_list.")";
$sql_select .= "GROUP BY fabric.cat_id,fabric.fabric_color,fabric.supplier_id,fabric.fabric_type_unit ";
$sql_select .= "ORDER BY fabric.fabric_color ASC,cat.cat_name_en ASC,fabric.fabric_type_unit ASC,supplier.supplier_name ASC ";

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

	if(!isset($a_cat_supp[($row_select["cat_id"])]["supp_id"])){
		$a_cat_supp[($row_select["cat_id"])]["supp_id"] = array();
	}
	if(!in_array($row_select["supplier_id"], $a_cat_supp[($row_select["cat_id"])]["supp_id"])){
		$a_cat_supp[($row_select["cat_id"])]["supp_id"][] = $row_select["supplier_id"];
	}
}

/*echo "<pre>";
print_r($a_cat_supp);
echo "</pre>";
exit();*/

//-----Select producing sum
$sql_select2 = "SELECT fabric.cat_id,cat.cat_name_en,fabric.fabric_color,fabric.fabric_type_unit,SUM(fabric.fabric_balance) AS sum_prod ";
$sql_select2 .= "FROM fabric ";
$sql_select2 .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_select2 .= "WHERE fabric.fabric_balance>0 AND fabric.on_producing=1 AND fabric.cat_id IN (".$cat_id_list.")";
$sql_select2 .= "GROUP BY fabric.cat_id,fabric.fabric_color,fabric.fabric_type_unit ";
$sql_select2 .= "ORDER BY fabric.fabric_color ASC,cat.cat_name_en ASC,fabric.fabric_type_unit ASC ";

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
$sql_select2 .= "ORDER BY forecast_detail.forecast_detail_color ASC,cat.cat_name_en ASC,forecast_detail.forecast_detail_unit_type ASC ";

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
$sql_select3 .= "ORDER BY tbl_f_ordered_item.color ASC,cat.cat_name_en ASC ";

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
<style type="text/css">
.sticky_head1{
	position: sticky;
	top:0px;
	z-index: 2;
}
.sticky_head2{
	position: sticky;
	top:25px;
	z-index: 2;
}
.sticky_head3{
	position: sticky;
	top:50px;
	z-index: 2;
}
.sticky_col1{
	position: sticky;
	left:0px;
	z-index: 3;
	
}
.sticky_col2{
	position: sticky;
	z-index: 3;
	left:181px !important;
	
}
.sticky_corner1{
	position: sticky;
	z-index: 4;
	top:0px;
	left:0px;
	min-width: 181px;
	max-width: 181px;
	width:181px;
}
.sticky_corner2{
	position: sticky;
	z-index: 4;
	top:0px;
	left:181px !important;
}	
</style>

<div class="div-fabric-content" style="max-height:600px; overflow: scroll;">
<div id="d_print" >
<style type="text/css">
.fabric-content th{
	background-color: #599;
	font-size: 12px;
	font-weight: bold !important;
	color: #FFF;
	border:1px #7BB solid !important;
	text-align: center;
	height: 25px;
	padding: 3px;
}
.fabric-content td{
	/*background-color: #FFF;*/
	font-size: 12px;
	color: #333;
	border:1px #7BB solid;
	height: 40px;
	text-align: center;
}
.total_col_head{
	font-weight: bold !important;
	background-color: #555 !important;
	color: #FFF !important;
}
.in_use_col_head{
	font-weight: bold !important;
	background-color: #D52 !important;
	color: #FFF !important;
}
.forecast_col_head{
	font-weight: bold !important;
	background-color: #225 !important;
	color: #FFF !important;
}
.ordered_col_head{
	font-weight: bold !important;
	background-color: #522 !important;
	color: #FFF !important;
}
.result_col_head{
	font-weight: bold !important;
	background-color: #252 !important;
	color: #FFF !important;
}
.color_col_body{
	background-color: #DFD ;
}
.total_col_body{
	background-color: #DDD ;
}
.in_use_col_body{
	background-color: #EB9 ;
}
.forecast_col_body{
	background-color: #88D ;
}
.ordered_col_body{
	background-color: #D88 ;
}
.result_col_body{
	background-color: #8D8 ;
}
</style>
<table id="tbl_mat_show" class="table fabric-content" style="border-collapse: separate; border-spacing: 0;">
<?php
if($_POST["view_style"]=="L"){
?>
	<thead>
	<tr>
		<th rowspan="2" class="sticky_corner1">Material</th>
		<th rowspan="2" class="sticky_corner2">Unit</th>
		<?php foreach($a_color as $color_index => $color_name ){ ?>
		<th class="sticky_head1" colspan="5" ><?php echo $color_name; ?></th>
		<?php } ?>
	</tr>
	<tr>
		<?php for($i=0;$i<sizeof($a_color);$i++ ){ ?>
		<th class="sticky_head2 in_use_col_head" title="IN USE">IN USE</th>
		<th class="sticky_head2 total_col_head" title="BALANCE">BALANCE</th>
		<th class="sticky_head2 forecast_col_head" title="FORECAST">FORECAST</th>
		<th class="sticky_head2 ordered_col_head" title="ORDERED">ORDERED</th>
		<th class="sticky_head2 result_col_head" title="RESULT">RESULT</th>
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
		
		<td class="sticky_col1 color_col_body" rowspan="<?php echo $n_num_unit; ?>"><?php echo $cat_name_en; ?></td>
		<?php } ?>	
		<td class="sticky_col2 color_col_body"><?php echo $a_unit[$fabric_type_unit]; ?></td>
		<?php foreach($a_color as $color_index => $color_name ){ ?>
		<td class="in_use_col_body"><?php echo (isset($a_data2[$color_name]["producing"])?$a_data2[$color_name]["producing"]:"-"); ?></td>
		<td class="total_col_body"><?php echo (isset($a_data2[$color_name]["balance"])?$a_data2[$color_name]["balance"]:"-"); ?></td>
		<td class="forecast_col_body"><?php echo (isset($a_data2[$color_name]["forecast"])?$a_data2[$color_name]["forecast"]:"-"); ?></td>
		<td class="ordered_col_body"><?php echo (isset($a_data2[$color_name]["ordered"])?$a_data2[$color_name]["ordered"]:"-"); ?></td>
		<td class="result_col_body">
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
		<th rowspan="3" class="sticky_corner1" >Color</th>
		<th rowspan="3" class="sticky_corner2" >Unit</th>
		<?php 
		foreach( $a_cat as $cat_id => $cat_name_en ){

			$n_num_supp = sizeof($a_cat_supp[$cat_id]["supp_id"])+5;
		?>
		<th colspan="<?php echo $n_num_supp; ?>" class="sticky_head1"><?php echo $cat_name_en; ?></th>
		<?php 
		} 
		?>
	</tr>
	<tr>
		<?php 
		foreach( $a_cat as $cat_id => $cat_name_en ){
			$n_num_supp = sizeof($a_cat_supp[$cat_id]["supp_id"])+2;
		?>
		<th colspan="<?php echo $n_num_supp; ?>" class="sticky_head2 total_col_head">BALANCE</th>
		<th rowspan="2" class="sticky_head2 forecast_col_head">FORECAST</th>
		<th rowspan="2" class="sticky_head2 ordered_col_head">ORDERED</th>
		<th rowspan="2" class="sticky_head2 result_col_head">RESULT</th>
		<?php 
		} 
		?>
	</tr>
	<tr>
		<?php 
		foreach( $a_cat as $cat_id => $cat_name_en ){
			$n_num_supp = sizeof($a_cat_supp[$cat_id]["supp_id"]);
			$s_supp_id_list = implode(",", $a_cat_supp[$cat_id]["supp_id"]);
			$sql_select_supp = "SELECT supplier_id,supplier_name FROM supplier WHERE supplier_id IN (".$s_supp_id_list."); ";
			//echo $sql_select_supp;
			$rs_select_supp = $conn->query($sql_select_supp);

			$a_tmp_supp = array();
			while ($row_select_supp = $rs_select_supp->fetch_assoc()) {
				$a_tmp_supp[($row_select_supp["supplier_id"])] = $row_select_supp["supplier_name"];
			}

			for($j=0;$j<$n_num_supp;$j++){
				$tmp_supp_id2 = $a_cat_supp[$cat_id]["supp_id"][$j];
		?>
		<th class="sticky_head3"><?php echo ($tmp_supp_id2=="0")?"N/A":$a_tmp_supp[$tmp_supp_id2]; ?></th>
		<?php 
			}
		?>
		<th class="sticky_head3 total_col_head">TOTAL</th>
		<th class="sticky_head3 in_use_col_head">IN USE</th>
		<?php
		} 
		?>
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
		
		<td class="sticky_col1 color_col_body" rowspan="<?php echo $n_num_unit; ?>"><?php echo $color_name; ?></td>
		<?php } ?>	
		<td class="sticky_col2 color_col_body"><?php echo $a_unit[$fabric_type_unit]; ?></td>
		<?php 
		foreach($a_cat as $cat_id => $cat_name_en ){ 

			$sum_bal = 0;
			for($j=0;$j<sizeof($a_cat_supp[$cat_id]["supp_id"]);$j++){
				$tmp_supp_id = $a_cat_supp[$cat_id]["supp_id"][$j];
		?>

		<td>
			<?php 
				if( isset($a_data2[$cat_id]["by_supp"][$tmp_supp_id]["value"]) ){

					echo $a_data2[$cat_id]["by_supp"][$tmp_supp_id]["value"];

					$sum_bal += floatval($a_data2[$cat_id]["by_supp"][$tmp_supp_id]["value"]);
			
				}else{
					echo "-";
				}
			?>	
		</td>
		<?php
			}
		?>
		<td class="total_col_body" style=" font-size: 15px;"><?php echo (($sum_bal!=0)?number_format($sum_bal,2):"-"); ?></td>
		<td class="in_use_col_body" style=" font-size: 15px;"><?php echo (isset($a_data2[$cat_id]["producing"])?number_format($a_data2[$cat_id]["producing"],2):"-"); ?></td>

		<td class="forecast_col_body" style=" font-size: 15px;"><?php echo (isset($a_data2[$cat_id]["forecast"])?$a_data2[$cat_id]["forecast"]:"-"); ?></td>
		<td class="ordered_col_body" style=" font-size: 15px;"><?php echo (isset($a_data2[$cat_id]["ordered"])?$a_data2[$cat_id]["ordered"]:"-"); ?></td>
		<td class="result_col_body" style=" font-size: 15px;">
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
