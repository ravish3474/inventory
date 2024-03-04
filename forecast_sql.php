<?php
include('alert.php');
$strDate = date('Y-m-d H:i:s');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='forecast_add'){

	$sql_head = 'INSERT INTO forecast_head (
							forecast_code,
							forecast_order,
							forecast_date,
							forecast_user
						) VALUES (
							"'.$_REQUEST['forecast_code'].'",
							"'.$_REQUEST['forecast_order'].'",
							"'.$_REQUEST['forecast_date'].'",
							"'.$_SESSION['employee_id'].'")';
	$query = $conn->query($sql_head);

	$sql_h_fc = 'SELECT * FROM forecast_head where forecast_code="'.$_REQUEST['forecast_code'].'" ';
	$query_h_fc = $conn->query($sql_h_fc);
	$rs_h_fc = $query_h_fc->fetch_assoc();

	$sql_temp = 'SELECT * FROM temp_forcast where forecast_code="'.$_REQUEST['forecast_code'].'" ';
	$query_temp = $conn->query($sql_temp);
	while ($rs_temp = $query_temp->fetch_assoc()) {
		$sql_detail = 'INSERT INTO forecast_detail (
								forecast_id,
								type_id,
								cat_id,
								forecast_detail_color,
								forecast_detail_size,
								forecast_detail_used
							) VALUES (
								"'.$rs_h_fc['forecast_id'].'",
								"'.$rs_temp['type_id'].'",
								"'.$rs_temp['cat_id'].'",
								"'.$rs_temp['temp_forcast_color'].'",
								"'.$rs_temp['temp_forcast_size'].'",
								"'.$rs_temp['temp_forcast_used'].'")';
		$query = $conn->query($sql_detail);
	}

	$sql_dD = 'DELETE FROM temp_forcast WHERE forecast_code = "'.$_REQUEST['forecast_code'].'"';
	$query = $conn->query($sql_dD);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_detail').'&forecast_id='.base64_encode($rs_h_fc['forecast_id']).'">';
}

if($ac=='head_update'){
	$sql_up = 'UPDATE forecast_head SET 
						forecast_order = "'.$_REQUEST['forecast_order'].'",
						forecast_update = "'.$strDate.'",
						forecast_update_user = "'.$_SESSION['employee_name'].'"
						WHERE forecast_id = "'.$_REQUEST['forecast_id'].'" ';
	$query = mysqli_query($conn,$sql_up);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_detail').'&forecast_id='.base64_encode($_REQUEST['forecast_id']).'">';
}

if($ac=='fc_detail_add'){
	if($_REQUEST['type_id']==1){
		if($_REQUEST['forecast_id']!=''){
			$sql_add = 'INSERT INTO forecast_detail (
									forecast_id,
									type_id,
									cat_id,
									forecast_detail_color,
									forecast_detail_used
								) VALUES (
									"'.$_REQUEST['forecast_id'].'",
									"'.$_REQUEST['type_id'].'",
									"'.$_REQUEST['cat_id'].'",
									"'.$_REQUEST['color'].'",
									"'.$_REQUEST['used'].'"
								)';
			$query = $conn->query($sql_add);
		}
	}else if($_REQUEST['type_id'][$i]==2){
		
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_detail').'&forecast_id='.base64_encode($_REQUEST['forecast_id']).'">';
}

if($ac=='fc_detail_update'){
	$countDe = count($_REQUEST['forecast_detail_id']);

	for($i=0;$i<$countDe;$i++){
		$sql_up = 'UPDATE forecast_detail SET forecast_detail_used = "'.$_REQUEST['forecast_detail_used'][$i].'" WHERE forecast_detail_id = "'.$_REQUEST['forecast_detail_id'][$i].'" ';
		$query = mysqli_query($conn,$sql_up);
	}
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_detail').'&forecast_id='.base64_encode($_REQUEST['forecast_id']).'">';
}

if($ac=='delete_all'){
	
	if(isset($_GET['forecast_id'])){$forecast_id=base64_decode($_GET['forecast_id']);}else{$forecast_id="";}
	
	$sql_dD = 'DELETE FROM forecast_detail WHERE forecast_id = "'.$forecast_id.'"';
	$query = $conn->query($sql_dD);

	$sql_dH = 'DELETE FROM forecast_head WHERE forecast_id = "'.$forecast_id.'"';
	$query = $conn->query($sql_dH);
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast').'">';
}

if($ac=='fc_detail_del'){
	if(isset($_GET['forecast_detail_id'])){$forecast_detail_id=base64_decode($_GET['forecast_detail_id']);}else{$forecast_detail_id="";}
	if(isset($_GET['forecast_id'])){$forecast_id=base64_decode($_GET['forecast_id']);}else{$forecast_id="";}
	
	$sql_dD = 'DELETE FROM forecast_detail WHERE forecast_detail_id = "'.$forecast_detail_id.'"';
	$query = $conn->query($sql_dD);
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_detail').'&forecast_id='.base64_encode($forecast_id).'">';
}

if($ac=='fc_temp_detail_add'){
	if($_REQUEST['type_id']==1){
		if($_REQUEST['used_code']!=''){
			$sql_add = 'INSERT INTO temp_forcast (
									forecast_code,
									type_id,
									cat_id,
									temp_forcast_color,
									temp_forcast_used
								) VALUES (
									"'.$_REQUEST['used_code'].'",
									"'.$_REQUEST['type_id'].'",
									"'.$_REQUEST['cat_id'].'",
									"'.$_REQUEST['color'].'",
									"'.$_REQUEST['used'].'"
								)';
			$query = $conn->query($sql_add);
		}
	}else if($_REQUEST['type_id'][$i]==2){
		
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_form').'&op='.base64_encode('forecast_add').'&used_code='.base64_encode($_REQUEST['used_code']).'">';
}

if($ac=='fc_temp_detail_update'){
	$countDe = count($_REQUEST['temp_forcast_id']);

	for($i=0;$i<$countDe;$i++){
		$sql_up = 'UPDATE temp_forcast SET temp_forcast_used = "'.$_REQUEST['temp_forcast_used'][$i].'" WHERE temp_forcast_id = "'.$_REQUEST['temp_forcast_id'][$i].'" ';
		$query = mysqli_query($conn,$sql_up);
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_form').'&op='.base64_encode('forecast_add').'&used_code='.base64_encode($_REQUEST['used_code']).'">';
}

if($ac=='fc_temp_detail_del'){
	if(isset($_GET['temp_forcast_id'])){$temp_forcast_id=base64_decode($_GET['temp_forcast_id']);}else{$temp_forcast_id="";}
	if(isset($_GET['used_code'])){$used_code=base64_decode($_GET['used_code']);}else{$used_code="";}
	
	$sql_dD = 'DELETE FROM temp_forcast WHERE temp_forcast_id = "'.$temp_forcast_id.'"';
	$query = $conn->query($sql_dD);
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_form').'&op='.base64_encode('forecast_add').'&used_code='.base64_encode($used_code).'">';	
}

if($ac=='fc_add_detail_update'){
	$countDe = count($_REQUEST['forecast_detail_id']);

	for($i=0;$i<$countDe;$i++){

		$exp = explode('/',$_POST['forecast_detail_no'][$i]);
		$fabric_no=$exp[0];
		$type_id=$exp[1];
		$cat_id=$exp[2];
		$forecast_detail_color=$exp[3];
		$forecast_detail_size=$exp[4];

		$q_balance = 'SELECT * FROM fabric WHERE cat_id="'.$cat_id.'" AND fabric_color="'.$forecast_detail_color.'"  AND fabric_no="'.$fabric_no.'" ';
		$query_balance = $conn->query($q_balance);
		$rs_balance = $query_balance->fetch_assoc();

		$balance=$rs_balance['fabric_balance']-$_REQUEST['forecast_detail_used'][$i];

		$forecast_detail_total=$rs_balance['fabric_in_price']*$_REQUEST['forecast_detail_used'][$i];

		if($balance>=0){
			$sql_up = 'UPDATE forecast_detail SET 
								materials_id="'.$rs_balance['fabric_id'].'",
								forecast_detail_color="'.$forecast_detail_color.'",
								forecast_detail_no="'.$fabric_no.'",
								forecast_detail_size="'.$forecast_detail_size.'" ,
								forecast_detail_used = "'.$_REQUEST['forecast_detail_used'][$i].'",
								forecast_detail_unit_type = "'.$rs_balance['fabric_type_unit'].'",
								forecast_detail_price = "'.$rs_balance['fabric_in_price'].'",
								forecast_detail_total = "'.$forecast_detail_total.'"
								WHERE forecast_detail_id = "'.$_REQUEST['forecast_detail_id'][$i].'" ';
			$query = mysqli_query($conn,$sql_up);
		}
	}
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_add').'&forecast_id='.base64_encode($_REQUEST['forecast_id']).'">';
}

if($ac=='fc_add_detail_add'){
	if($_REQUEST['type_id']==1){
		if($_REQUEST['forecast_id']!=''){
			$sql_add = 'INSERT INTO forecast_detail (
									forecast_id,
									type_id,
									cat_id,
									forecast_detail_color,
									forecast_detail_used
								) VALUES (
									"'.$_REQUEST['forecast_id'].'",
									"'.$_REQUEST['type_id'].'",
									"'.$_REQUEST['cat_id'].'",
									"'.$_REQUEST['color'].'",
									"'.$_REQUEST['used'].'"
								)';
			$query = $conn->query($sql_add);
		}
	}else if($_REQUEST['type_id'][$i]==2){
		
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_add').'&forecast_id='.base64_encode($_REQUEST['forecast_id']).'">';
}

if($ac=='fc_add_detail_del'){
	if(isset($_GET['forecast_detail_id'])){$forecast_detail_id=base64_decode($_GET['forecast_detail_id']);}else{$forecast_detail_id="";}
	if(isset($_GET['forecast_id'])){$forecast_id=base64_decode($_GET['forecast_id']);}else{$forecast_id="";}
	
	$sql_dD = 'DELETE FROM forecast_detail WHERE forecast_detail_id = "'.$forecast_detail_id.'"';
	$query = $conn->query($sql_dD);
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('forecast_add').'&forecast_id='.base64_encode($forecast_id).'">';
}

if($ac=='fc_add_order'){

	$sql_head = 'INSERT INTO used_head (
							used_code,
							used_order_code,
							used_date,
							used_user
						) VALUES (
							"'.$_REQUEST['used_code'].'",
							"'.$_REQUEST['used_order_code'].'",
							"'.$_REQUEST['used_date'].'",
							"'.$_SESSION['employee_id'].'")';
	$query = $conn->query($sql_head);

	$sql_dH = 'DELETE FROM forecast_head WHERE forecast_id = "'.$_REQUEST['forecast_id'].'"';
	$query = $conn->query($sql_dH);

	$sql_use_head = 'SELECT * FROM used_head where used_code="'.$_REQUEST['used_code'].'" ';
	$query_use_head = $conn->query($sql_use_head);
	$rs_use_head = $query_use_head->fetch_assoc();

	$sql_detail = 'SELECT * FROM forecast_detail where forecast_id="'.$_REQUEST['forecast_id'].'" ';
	$query_detail = $conn->query($sql_detail);
	while ($rs_detail = $query_detail->fetch_assoc()) {

		if($rs_detail['type_id']==1){
			$sql_fab = 'SELECT * FROM fabric where fabric_id="'.$rs_detail['materials_id'].'" ';
			$query_fab = $conn->query($sql_fab);
			$rs_fab = $query_fab->fetch_assoc();

			$fabric_used=$rs_fab['fabric_used']+$rs_detail['forecast_detail_used'];
			$fabric_balance=$rs_fab['fabric_balance']-$rs_detail['forecast_detail_used'];
			$fabric_total=$rs_fab['fabric_total']+$rs_detail['forecast_detail_total'];

			$sql_up_fab = 'UPDATE fabric SET 
								fabric_used="'.$fabric_used.'",
								fabric_balance="'.$fabric_balance.'",
								fabric_total="'.$fabric_total.'",
								fabric_date_update="'.$strDate.'" ,
								fabric_user_update = "'.$_SESSION['employee_id'].'"
								WHERE fabric_id = "'.$rs_detail['materials_id'].'" ';
			$query = mysqli_query($conn,$sql_up_fab);
			//echo $sql_up_fab.'<br>';
		}
		
		$sql_add_detail = 'INSERT INTO used_detail (
								used_id,
								materials_id,
								type_id,
								cat_id,
								used_detail_color,
								used_detail_no,
								used_detail_size,
								used_detail_used,
								used_detail_unit_type,
								used_detail_price,
								used_detail_total
							) VALUES (
								"'.$rs_use_head['used_id'].'",
								"'.$rs_detail['materials_id'].'",
								"'.$rs_detail['type_id'].'",
								"'.$rs_detail['cat_id'].'",
								"'.$rs_detail['forecast_detail_color'].'",
								"'.$rs_detail['forecast_detail_no'].'",
								"'.$rs_detail['forecast_detail_size'].'",
								"'.$rs_detail['forecast_detail_used'].'",
								"'.$rs_detail['forecast_detail_unit_type'].'",
								"'.$rs_detail['forecast_detail_price'].'",
								"'.$rs_detail['forecast_detail_total'].'"
							)';
		$query = $conn->query($sql_add_detail);
		//echo $sql_add_detail.'<br>';
	}

	$sql_dD = 'DELETE FROM forecast_detail WHERE forecast_id="'.$_REQUEST['forecast_id'].'" ';
	$query = $conn->query($sql_dD);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($rs_use_head['used_id']).'">';
}

?>