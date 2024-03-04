<?php
session_start();

if(!isset($_SESSION['employee_position_id'])){
	echo "<center><b><font color=red>Fail: SESSION Expired! Please refresh page.</font></b></center>";
	exit();
}

if(!isset($_POST["cat_id"])){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$a_use_supp = array();
$a_use_color = array();

$a_data = array();
$sql_data = "SELECT color_id,supplier_id,SUM(fabric_balance) AS sum_bal FROM fabric WHERE cat_id='".$_POST["cat_id"]."' AND fabric_balance<>0 GROUP BY color_id,supplier_id ORDER BY fabric_color ASC; ";
$rs_data = $conn->query($sql_data);

while ($row_data = $rs_data->fetch_assoc()) {
	$a_data[($row_data["color_id"])][($row_data["supplier_id"])] = $row_data["sum_bal"];

	if(!in_array($row_data["color_id"], $a_use_color)){
		$a_use_color[] = $row_data["color_id"];
	}
	if(!in_array($row_data["supplier_id"], $a_use_supp)){
		$a_use_supp[] = $row_data["supplier_id"];
	}
}

$a_color = array();

if(sizeof($a_use_color)>0){
	$tmp_color_id = implode(",", $a_use_color);
	$sql_color = "SELECT * FROM tbl_color WHERE color_id IN (".$tmp_color_id."); ";
	$rs_color = $conn->query($sql_color);
	$a_color[0] = "N/A";
	while ($row_color = $rs_color->fetch_assoc()) {
		$a_color[($row_color["color_id"])] = $row_color["color_name"];
	}
}

$a_supp = array();

if(sizeof($a_use_supp)>0){
	$tmp_supp_id = implode(",", $a_use_supp);
	$sql_supp = "SELECT * FROM supplier WHERE supplier_id IN (".$tmp_supp_id."); ";
	$rs_supp = $conn->query($sql_supp);
	$a_supp[0] = "N/A";
	while ($row_supp = $rs_supp->fetch_assoc()) {
		$a_supp[($row_supp["supplier_id"])] = $row_supp["supplier_name"];
	}
}

$a_forder = array();
$sql_forder = "SELECT forecast_detail.color_id,SUM(forecast_detail.forecast_detail_used) AS sum_forcast_order ";
$sql_forder .= "FROM forecast_detail ";
$sql_forder .= "LEFT JOIN forecast_head ON forecast_detail.forecast_id=forecast_head.forecast_id ";
$sql_forder .= "WHERE forecast_detail.cat_id='".$_POST["cat_id"]."' AND forecast_head.is_produced=0 ";//----If orders has been produced this flag will be 1
$sql_forder .= "GROUP BY forecast_detail.color_id ";
$rs_forder = $conn->query($sql_forder);
while ($row_forder = $rs_forder->fetch_assoc()) {
	$a_forder[($row_forder["color_id"])] = $row_forder["sum_forcast_order"];
}

$a_fpurchase = array();
$sql_fpurchase = "SELECT color_id,SUM(fpu_value) AS sum_forcast_purchase ";
$sql_fpurchase .= "FROM tbl_f_purchase ";
$sql_fpurchase .= "WHERE cat_id='".$_POST["cat_id"]."' AND mark_ordered<>2 "; //--2 means received fabric that ordered
$sql_fpurchase .= "GROUP BY color_id ";
$rs_fpurchase = $conn->query($sql_fpurchase);
while ($row_fpurchase = $rs_fpurchase->fetch_assoc()) {
	$a_fpurchase[($row_fpurchase["color_id"])] = $row_fpurchase["sum_forcast_purchase"];
}

$a_except_supp = array();
$sql_chk_supp_stock = "SELECT supplier_id FROM supplier WHERE supplier_name LIKE 'STOCK-%'; ";
$rs_chk_supp_stock = $conn->query($sql_chk_supp_stock);
while ($row_chk_supp_stock = $rs_chk_supp_stock->fetch_assoc()) {
	$a_except_supp[] = $row_chk_supp_stock["supplier_id"];
}

/*echo "<pre>";
print_r($a_data);
echo "<hr>";
print_r($a_use_supp);
echo "</pre>";*/
?>
<input type="hidden" id="data_date" value="<?php echo date("Y-m-d H:i:s"); ?>">
<div id="d_forecast_po">
<table class="tbl_forecast_po">
	<tr>
		<th rowspan="2">#</th>
		<th rowspan="2" style="text-align: left;">Colors</th>
		<th colspan="<?php echo sizeof($a_use_supp)+1; ?>">Stock in-house(KG) <i style="color:#777;">Separated by Suppliers</i></th>
		<th rowspan="2">Forecast<br>Purchasing</th>
		<th rowspan="2">Forecast<br>Order</th>
		<th rowspan="2">Estmiated<br>Actual BAL.</th>
		<th rowspan="2">Estmiated<br>New BAL.</th>
	</tr>

	<tr>
		<?php
		$f_sum_col = array();
		for($i=0;$i<sizeof($a_use_supp);$i++){
			if($a_use_supp[$i]!=0){
		?>
		<th><?php echo $a_supp[($a_use_supp[$i])]; ?></th>
		<?php
			}

			$f_sum_col[($a_use_supp[$i])]=0.0;
		}

		if(in_array(0, $a_use_supp)){
		?>
		<th style="color: #777;"><?php echo $a_supp[0]; ?></th>
		<?php
		}
		?>
		<th class="total_col1">TOTAL</th>
	</tr>

	<?php
	$f_sum_val_total = 0.0;
	$count_row = 1;
	foreach($a_data as $color_id => $a_supp_data){

		$a_tmp_supp = array();
	?>
	<tr>
		<td><?php echo $count_row; ?></td>
		<td style="text-align: left;" id="td_color_name<?php echo $color_id; ?>"><?php echo $a_color[$color_id]; ?></td>
		<?php
		$f_sum_val = 0.0;
		for($j=0;$j<sizeof($a_use_supp);$j++){
			if($a_use_supp[$j]!=0){
				$show_val = "-";
				if( isset($a_supp_data[($a_use_supp[$j])]) ){
					$show_val = number_format($a_supp_data[($a_use_supp[$j])],2);
					$f_sum_val += floatval($a_supp_data[($a_use_supp[$j])]);

					$f_sum_col[($a_use_supp[$j])] += floatval($a_supp_data[($a_use_supp[$j])]);
				}
		?>
		<td>
			<?php
			if($show_val!="-"){

				if( !in_array($a_use_supp[$j], $a_except_supp) ){
					$a_tmp_supp[] = $a_use_supp[$j];
				}
			?>
			<div class="link_show_detail" onclick="return showStockDetail('<?php echo $color_id; ?>','<?php echo $a_use_supp[$j]; ?>');" data-toggle="modal" data-target="#showStockDetailModal">
			<?php echo $show_val; ?>
			</div>
			<?php
			}else{
				echo $show_val;
			}
			?>
		</td>
		<?php
			}else{
				if( isset($a_supp_data[0]) ){
					$f_sum_val += floatval($a_supp_data[0]);

					$f_sum_col[0] += floatval($a_supp_data[0]);
				}
			}

		}

		if(in_array(0, $a_use_supp)){
		?>
		<td>
			<?php
			if(isset($a_supp_data[0])){
			?>
			<div class="link_show_detail" onclick="return showStockDetail('<?php echo $color_id; ?>','0');" data-toggle="modal" data-target="#showStockDetailModal">
			<?php echo number_format($a_supp_data[0],2); ?>
			</div>
			<?php
			}else{
				echo "-";
			}
			?>
		</td>
		<?php
		}

		$sum_forder = 0;
		if(isset($a_forder[$color_id])){
			$sum_forder = $a_forder[$color_id];
		}
		?>
		<td class="total_col1">
			<div class="link_show_detail" onclick="return showStockDetail('<?php echo $color_id; ?>','all');" data-toggle="modal" data-target="#showStockDetailModal">
				<?php echo number_format($f_sum_val,2); $f_sum_val_total += $f_sum_val; ?>
			</div>
		</td>
		<td>
			<span id="sp_show_fop<?php echo $color_id; ?>" class="link_show_detail" onclick="return showFOCPurchaseDetail('<?php echo $color_id; ?>');" data-toggle="modal" data-target="#showForecastPurchaseModal">
				<?php echo (isset($a_fpurchase[$color_id])?number_format($a_fpurchase[$color_id],2)."&nbsp;":""); ?>
			</span>
			<?php
			if( in_array(intval($_SESSION['employee_position_id']), array(1,99,4)) ){

				$s_supp_id_list = "";
				if(sizeof($a_tmp_supp)>0){
					$s_supp_id_list = implode(",", $a_tmp_supp);
				}
				
				?>
				<span class="admin_edit" onclick="return inputFOCPurchase('<?php echo $color_id; ?>','<?php echo $s_supp_id_list; ?>');" data-toggle="modal" data-target="#editForecastPurchaseModal"><i class="fa fa-plus"></i></span>
				<?php
			}
			?>
		</td>
		<td>
			<?php
			if($sum_forder!=0){
			?>
			<div class="link_show_detail" onclick="return showFCOrderDetail('<?php echo $color_id; ?>');" data-toggle="modal" data-target="#showForecastOrderDetailModal">
				<?php echo number_format($a_forder[$color_id],2); ?>
			</div>
			<?php
			}else{
				echo "-";
			}
			?>
		</td>
		<td style="text-align: right;">
			<?php echo number_format(($f_sum_val-$sum_forder),2); ?>
			<input type="hidden" id="hidden_est_act_bal<?php echo $color_id; ?>" value="<?php echo ($f_sum_val-$sum_forder); ?>">
		</td>
		<td style="text-align: right;">
			<span id="sp_show_est_new_bal<?php echo $color_id; ?>">
				<?php 
				$sum_foc_purchase = 0.0;
				if(isset($a_fpurchase[$color_id])){
					$sum_foc_purchase = floatval($a_fpurchase[$color_id]);
				}
				echo number_format(($f_sum_val-$sum_forder+$sum_foc_purchase),2); 
				?>
			</span>
			<input type="hidden" id="hidden_est_new_bal<?php echo $color_id; ?>" value="<?php echo ($f_sum_val-$sum_forder+$sum_foc_purchase); ?>">
		</td>
	</tr>
	<?php
		$count_row++;
	}
	?>
	<tr class="total_row">
		<td colspan="2">Total</td>
		<?php
		for($k=0;$k<sizeof($a_use_supp);$k++){
			if($a_use_supp[$k]!=0){
		?>
		<td><?php echo number_format($f_sum_col[($a_use_supp[$k])],2); ?></td>
		<?php
			}
		}
		
		if(in_array(0, $a_use_supp)){
		?>
		<td><?php echo isset($f_sum_col[0])?number_format($f_sum_col[0],2):"-"; ?></td>
		<?php
		}
		?>
		<td class="total_col1"><?php echo number_format($f_sum_val_total,2); ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
</div>
<center style="margin-top: 10px; margin-bottom: -15px;">
	<button type="button" class="btn btn-success" style="width:120px;" onclick="return printFOCPurchaseInfo();">Print</button>
</center>