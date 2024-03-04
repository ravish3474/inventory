<thead>
<tr class="bg-dark text-white text-center">
	<th>#</th>
	<th>Order</th>
	<th>Date Create</th>
	<th>Type</th>
	<th>Product</th>
	<th>Color</th>
	<th class="text-right">Balance</th>
	<th class="text-right" width="10%">Forecast</th>
	<th>View & Edit</th>
</tr>
</thead>
<tbody>
<?php
require_once('../../db.php');

function unitType($val){
	switch ($val) {
		case 1:
			$tu_name='Piece';
			break;
		case 2:
			$tu_name='Yard';
			break;
		case 3:
			$tu_name='KG';
			break;
		default : 
			$tu_name='';
			break;
	}
	return $tu_name;
}

$records_per_page = 10;

if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}
$op ="finish";
// Calculate the starting point for the query
$start_from = ($page - 1) * $records_per_page;

$n_row = $start_from+1;
$sql_detail = "SELECT forecast_detail.*,forecast_head.is_produced FROM forecast_detail LEFT JOIN forecast_head ON forecast_head.forecast_id=forecast_detail.forecast_id ORDER BY forecast_head.forecast_date DESC LIMIT 
    $start_from, $records_per_page";
$query_detail = $conn->query($sql_detail);
while ($rs_detail = $query_detail->fetch_assoc()) {

	$q_cat = 'SELECT cat_code FROM cat WHERE cat_id="'.$rs_detail['cat_id'].'" ';
	$query_cat = $conn->query($q_cat);
	$rs_cat = $query_cat->fetch_assoc();
	if($rs_detail['type_id']==1){
		$sql_sum_fab = 'SELECT SUM(fabric_balance) as sum_balance FROM fabric where cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'" ';
		$query_sum_fab = $conn->query($sql_sum_fab);
		$rs_sum_fab = $query_sum_fab->fetch_assoc();

		$q_type = 'SELECT fabric_type_unit FROM fabric WHERE cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'" ';
		$query_type = $conn->query($q_type);
		$rs_type = $query_type->fetch_assoc();

		$balance=$rs_sum_fab['sum_balance'].' '.unitType($rs_type['fabric_type_unit']);

		$sql_forecast_code = 'SELECT * FROM forecast_head WHERE forecast_id="'.$rs_detail['forecast_id'].'" ';
		$query_forecast_code = $conn->query($sql_forecast_code);
		$rs_forecast_code = $query_forecast_code->fetch_assoc();
	}

	$show_bg = '';
	if($rs_detail["is_produced"]=='1'){
		$show_bg = ' style="background-color:#DDD;" ';
	}
?>
	<tr <?php echo $show_bg; ?> class="text-center">
		<td><?php echo $n_row; ?></td>
		<td>
			<?php 
			if($rs_forecast_code['forecast_order']!=''){
				echo $rs_forecast_code['forecast_order'];
			}else{
				echo $rs_forecast_code['forecast_code'];
			}
			?>
		</td>
		<td>
			<div class="badge badge-primary"><?php echo $rs_forecast_code['forecast_date'];?></div>
		</td>
		<td>
			<?php 
			switch ($rs_detail['type_id']) {
				case 1:
					$type='Fabrics';
					break;
				case 2:
					$type='Accessory';
					break;
			}
			echo $type;
			?>
		</td>
		<td><?php echo $rs_cat['cat_code'];?></td>
		<td><?php echo $rs_detail['forecast_detail_color'];?></td>
		<td class="text-right"><?php echo $balance;?>&nbsp;&nbsp;</td>
		<td class="text-right"><?php echo $rs_detail['forecast_detail_used'];?>&nbsp;&nbsp;</td>
		<td>
			<a class="btn btn-light" href="?vp=<?php echo base64_encode('forecast_view_edit').'&forecast_id='.base64_encode($rs_detail['forecast_id']);?>">
				<i class="mdi mdi-eye text-primary"></i>View 
			</a>
		</td>
	</tr>
<?php	
	$n_row++;
}
?>

</tbody>
