<?php
include('db.php');
include('function.php');

if($_GET['op']=='type'){
	$type_id = $_POST['type_id'];

	echo '<option value="">=Select=</option>';
	$q_cat = 'SELECT cat_id,cat_name_en FROM cat WHERE type_id="'.$type_id.'" AND enable=1 ORDER BY cat_code ASC';
	$query_cat = $conn->query($q_cat);
	$cat_count = $query_cat->num_rows;
	if($cat_count!=0){
		while($rs_cat = $query_cat->fetch_assoc()){
			echo '<option value="'.$rs_cat['cat_id'].'">'.$rs_cat['cat_name_en'].'</option>';
		}
	}else{
		echo '<option value="">No data</option>';	
	}
}

if($_GET['op']=='color'){
	if($_POST['type_id']==1){
		echo '<option value="">=Select=</option>';
		$q_color = 'SELECT * FROM fabric WHERE cat_id="'.$_POST['cat_id'].'" GROUP BY fabric_color';
		$query_color = $conn->query($q_color);
		$color_count = $query_color->num_rows;
		if($color_count!=0){
			while($rs_color = $query_color->fetch_assoc()){
				echo '<option value="'.$rs_color['fabric_color'].'">'.$rs_color['fabric_color'].'</option>';
			}
		}else{
			echo '<option value="">No data</option>';	
		}
	}
}

if($_GET['op']=='color_id'){
	if($_POST['type_id']==1){
		echo '<option value="">=Select=</option>';
		$q_color = 'SELECT * FROM fabric WHERE cat_id="'.$_POST['cat_id'].'" GROUP BY fabric_color';
		$query_color = $conn->query($q_color);
		$color_count = $query_color->num_rows;
		if($color_count!=0){
			while($rs_color = $query_color->fetch_assoc()){
				echo '<option value="'.$rs_color['color_id'].'">'.$rs_color['fabric_color'].'</option>';
			}
		}else{
			echo '<option value="">No data</option>';	
		}
	}
}

if($_GET['op']=='balance'){
	if($_POST['type_id']==1){
		
		$q_balance = 'SELECT SUM(fabric_balance) AS sum_balance FROM fabric WHERE cat_id="'.$_POST['cat_id'].'" AND fabric_color="'.$_POST['color'].'" ';
		$query_balance = $conn->query($q_balance);
		$rs_balance = $query_balance->fetch_assoc();

		$q_type = 'SELECT fabric_type_unit FROM fabric WHERE cat_id="'.$_POST['cat_id'].'" AND fabric_color="'.$_POST['color'].'" ';
		$query_type = $conn->query($q_type);
		$rs_type = $query_type->fetch_assoc();
		echo '<input class="form-control" type="text" id="show_balance" name="used" value="'.$rs_balance['sum_balance'].' '.unitType($rs_type['fabric_type_unit']).'" readonly>';
		echo '<input type="hidden" id="select_balance" value="'.$rs_balance['sum_balance'].'">';
		echo '<input type="hidden" id="forecast_detail_unit_type" name="forecast_detail_unit_type" value="'.$rs_type['fabric_type_unit'].'">';
	}
}

if($_GET['op']=='balance_add'){
	
	$exp = explode('/',$_POST['forecast_detail_no']);
	$fabric_no=$exp[0];
	$type_id=$exp[1];
	$cat_id=$exp[2];
	$forecast_detail_color=$exp[3];
	$forecast_detail_size=$exp[4];

	if($type_id==1){
		
		$q_balance = 'SELECT * FROM fabric WHERE cat_id="'.$cat_id.'" AND fabric_color="'.$forecast_detail_color.'"  AND fabric_no="'.$fabric_no.'" AND fabric_balance!=0 ';
		$query_balance = $conn->query($q_balance);
		$rs_balance = $query_balance->fetch_assoc();

		echo $rs_balance['fabric_balance'].' '.unitType($rs_balance['fabric_type_unit']);
	}
}

if($_GET['op']=='search'){
	if($_POST['type_id']==1){
		$q_fab = 'SELECT * FROM fabric WHERE cat_id="'.$_POST['cat_id'].'" AND fabric_balance!=0 ';
		$query_fab = $conn->query($q_fab);
		$fab_count = $query_fab->num_rows;
?>
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="order-listing" class="table">
						<thead>
							<tr class="bg-dark text-white">
								<th>DATE</th>
								<th>PRODUCT</th>
								<th>COLOR</th>
								<th class="text-center">NO</th>
								<th class="text-center">BOX</th>
								<th class="text-center">BALANCE</th>
								<th class="text-center">USED</th>
								<th class="text-center">AMOUNT</th>
								<th class="text-center">SELECT</th>
							</tr>
						</thead>
						<tbody>
						<?php
						while($rs_fab = $query_fab->fetch_assoc()){
							$q_cat = 'SELECT * FROM cat WHERE cat_id="'.$rs_fab['cat_id'].'"';
							$query_cat = $conn->query($q_cat);
							$rs_cat = $query_cat->fetch_assoc();
						?>
							<tr>
								<td><?php echo Ndate($rs_fab['fabric_date_create']);?></td>
								<td><?php echo $rs_cat['cat_name_en'];?></td>
								<td><?php echo $rs_fab['fabric_color'];?></td>
								<td class="text-center"><?php echo $rs_fab['fabric_no'];?></td>
								<td class="text-center"><?php echo $rs_fab['fabric_box'];?></td>
								<td class="text-center">
									<?php 
									echo $rs_fab['fabric_balance'].' '.unitType($rs_fab['fabric_type_unit']);
									?>
								</td>
								<td class="text-center"><?php echo $rs_fab['fabric_used'];?></td>
								<td class="text-center"><?php echo $rs_fab['fabric_amount'];?></td>
								<td class="text-center">
									<input class="form-detail" type="checkbox" name="fabric_id[]" value="<?php echo $rs_fab['fabric_id'];?>">
									<input type="hidden" name="cat_id[]" value="<?php echo $_POST['cat_id'];?>">
									<input type="hidden" name="type_id[]" value="<?php echo $_POST['type_id'];?>">
								</td>
							</tr>
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
<?php
	}else if($_POST['type_id']==2){
		$q_acc = 'SELECT * FROM accessory WHERE cat_id="'.$_POST['cat_id'].'" ';
		$query_acc = $conn->query($q_acc);
		$acc_count = $query_acc->num_rows;
		if($acc_count!=0){
?>
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="order-listing" class="table">
						<thead>
							<tr class="bg-dark text-white">
								<th>DATE</th>
								<th>PRODUCT</th>
								<th>COLOR</th>
								<th class="text-center">SIZE</th>
								<th class="text-center">BALANCE</th>
								<th class="text-center">USED</th>
								<th class="text-center">AMOUNT</th>
								<th class="text-center">SELECT</th>
							</tr>
						</thead>
						<tbody>
						<?php
						while($rs_acc = $query_acc->fetch_assoc()){
							$q_cat = 'SELECT * FROM cat WHERE cat_id="'.$rs_acc['cat_id'].'"';
							$query_cat = $conn->query($q_cat);
							$rs_cat = $query_cat->fetch_assoc();
						?>
							<tr>
								<td><?php echo Ndate($rs_acc['accessory_date_create']);?></td>
								<td><?php echo $rs_cat['cat_name_en'];?></td>
								<td><?php echo $rs_acc['accessory_color'];?></td>
								<td class="text-center"><?php echo $rs_acc['accessory_size'];?></td>
								<td class="text-center"><?php echo number_format($rs_acc['accessory_balance'],2).' '.unitType($rs_acc['accessory_type_unit']);?></td>
								<td class="text-center"><?php echo $rs_acc['accessory_used'];?></td>
								<td class="text-center"><?php echo $rs_acc['accessory_total'];?></td>
								<td class="text-center">
									<input class="form-detail" type="checkbox" name="fabric_id[]" value="<?php echo $rs_acc['accessory_id'];?>">
									<input type="hidden" name="cat_id[]" value="<?php echo $_POST['cat_id'];?>">
									<input type="hidden" name="type_id[]" value="<?php echo $_POST['type_id'];?>">
								</td>
							</tr>
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?php			
		}
	}
	include('jquery.php');
}


?>
