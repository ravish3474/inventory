<?php
include('alert.php');
$strDate = date('Y-m-d H:i:s');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='draw_add'){

	$used_order_code = "";

	if($_REQUEST['select_order']=="no-code"){

		$used_order_code = "NO-".date("ymdHis");
	}else{
		$used_order_code = $_REQUEST['used_order_code'];
	}


	$sql_add = 'INSERT INTO used_head (used_code,used_order_code,used_date,used_user';

	if($_REQUEST['select_order']=="no-code"){
		$sql_add .= ',no_order_note';
	}
	$sql_add .= ') VALUES ("'.$_REQUEST['used_code'].'","'.$used_order_code.'","'.$_REQUEST['used_date'].'","'.$_SESSION['employee_id'].'"';

	if($_REQUEST['select_order']=="no-code"){
		$sql_add .= ',"'.$_REQUEST['no_order_note'].'"';
	}

	$sql_add .= ');';
	$query = $conn->query($sql_add);

	$sql_draw = 'SELECT * FROM used_head where used_code="'.$_REQUEST['used_code'].'"';
	$query_draw = $conn->query($sql_draw);
	$rs_draw = $query_draw->fetch_assoc();

	if($_REQUEST['select_order']!="no-code"){
		$sql_update_order = "UPDATE tbl_order_lkr_title SET to_add_order=1 WHERE order_title='".$used_order_code."'; ";
		$conn->query($sql_update_order);

		$sql_update_forecast = "UPDATE forecast_head SET is_produced=1 WHERE forecast_order='".$used_order_code."'; ";
		$conn->query($sql_update_forecast);
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($rs_draw['used_id']).'">';
}


if($ac=='draw_detail_add'){
	$countFab = count($_REQUEST['fabric_id']);

	for($i=0;$i<$countFab;$i++){
		if($_REQUEST['type_id'][$i]==1){
			$sql_used = 'SELECT * FROM used_detail where materials_id="'.$_REQUEST['fabric_id'][$i].'" AND cat_id="'.$_REQUEST['cat_id'][$i].'" AND used_id="'.$_REQUEST['used_id'].'" ';
			$query_used = $conn->query($sql_used);
			$rs_used = $query_used->fetch_assoc();
			//echo $sql_used;

			$sql_fabric = 'SELECT * FROM fabric where fabric_id="'.$_REQUEST['fabric_id'][$i].'"';
			$query_fabric = $conn->query($sql_fabric);
			$rs_fabric = $query_fabric->fetch_assoc();

			if($rs_used['used_detail_id']==''){
				$sql_add = 'INSERT INTO used_detail (
										used_id,
										materials_id,
										type_id,
										cat_id,
										used_detail_color,
										used_detail_no,
										used_detail_unit_type,
										used_detail_price
									) VALUES (
										"'.$_REQUEST['used_id'].'",
										"'.$_REQUEST['fabric_id'][$i].'",
										"'.$_REQUEST['type_id'][$i].'",
										"'.$rs_fabric['cat_id'].'",
										"'.$rs_fabric['fabric_color'].'",
										"'.$rs_fabric['fabric_no'].'",
										"'.$rs_fabric['fabric_type_unit'].'",
										"'.$rs_fabric['fabric_in_price'].'"
									)';
				$query = $conn->query($sql_add);
				//echo $sql_add;
			}

		}else if($_REQUEST['type_id'][$i]==2){
			$sql_used = 'SELECT * FROM used_detail where materials_id="'.$_REQUEST['fabric_id'][$i].'" AND cat_id="'.$_REQUEST['cat_id'][$i].'" ';
			$query_used = $conn->query($sql_used);
			$rs_used = $query_used->fetch_assoc();
			
			$sql_acc = 'SELECT * FROM accessory where accessory_id="'.$_REQUEST['fabric_id'][$i].'"';
			$query_acc = $conn->query($sql_acc);
			$rs_acc = $query_acc->fetch_assoc();

			if($rs_used['used_detail_id']==''){
				$sql_add = 'INSERT INTO used_detail (
										used_id,
										materials_id,
										type_id,
										cat_id,
										used_detail_color,
										used_detail_no,
										used_detail_size,
										used_detail_unit_type,
										used_detail_price
									) VALUES (
										"'.$_REQUEST['used_id'].'",
										"'.$_REQUEST['fabric_id'][$i].'",
										"'.$_REQUEST['type_id'][$i].'",
										"'.$rs_acc['cat_id'].'",
										"'.$rs_acc['accessory_color'].'",
										"'.$rs_acc['accessory_no'].'",
										"'.$rs_acc['accessory_size'].'",
										"'.$rs_acc['accessory_type_unit'].'",
										"'.$rs_acc['accessory_in_price'].'"
									)';
				$query = $conn->query($sql_add);
				//echo $sql_add;
			}
		}
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($_REQUEST['used_id']).'">';
}

if($ac=='draw_detail_update'){
	$countDe = count($_REQUEST['used_detail_id']);

	for($i=0;$i<$countDe;$i++){
		if($_REQUEST['type_id'][$i]==1){

			//echo '<hr>Detail = '.$_REQUEST['used_detail_id'][$i].'<br>';

			$sql_fabric = 'SELECT * FROM fabric where fabric_id="'.$_REQUEST['materials_id'][$i].'"';
			$query_fabric = $conn->query($sql_fabric);
			$rs_fabric = $query_fabric->fetch_assoc();

			//echo $sql_fabric.'<br>';

			$sql_used_detail = 'SELECT * FROM used_detail where used_detail_id="'.$_REQUEST['used_detail_id'][$i].'"';
			$query_used_detail = $conn->query($sql_used_detail);
			$rs_used_detail = $query_used_detail->fetch_assoc();

			//echo $sql_used_detail.'<br>';

			$used_detail_total=$rs_used_detail['used_detail_price']*$_REQUEST['used_detail_used'][$i];

			//echo $used_detail_total.'='.$rs_used_detail['used_detail_price'].'*'.$_REQUEST['used_detail_used'][$i].'<br>';

			$fabric_used_old=$rs_fabric['fabric_used']-$rs_used_detail['used_detail_used'];
			$fabric_used=$fabric_used_old+$_REQUEST['used_detail_used'][$i];
			$fabric_balance=$rs_fabric['fabric_in_piece']-$fabric_used+$rs_fabric['fabric_adjust'];
			$fabric_total=$rs_fabric['fabric_in_price']*$fabric_used;

			//echo '<br>'.$fabric_used_old.'='.$rs_fabric['fabric_used'].'-'.$rs_used_detail['used_detail_used'];
			//echo '<br>'.$fabric_used.'='.$fabric_used_old.'-'.$_REQUEST['used_detail_used'][$i];
			//echo '<br>'.$fabric_balance.'='.$rs_fabric['fabric_in_piece'].'-'.$fabric_used;
			//echo '<br>'.$fabric_total.'='.$rs_fabric['fabric_in_price'].'*'.$fabric_used.'<br>';

			if($fabric_balance>=0){
				$sql_up = 'UPDATE used_detail SET 
								used_detail_used = "'.$_REQUEST['used_detail_used'][$i].'",
								used_detail_total = "'.$used_detail_total.'"
								WHERE used_detail_id = "'.$_REQUEST['used_detail_id'][$i].'" ';
				$query = mysqli_query($conn,$sql_up);
				//echo $sql_up.'<br>';
				

				$sql_up_fab = 'UPDATE fabric SET 
								fabric_used = "'.$fabric_used.'",
								fabric_balance = "'.$fabric_balance.'",
								fabric_total = "'.$fabric_total.'"
								WHERE fabric_id = "'.$_REQUEST['materials_id'][$i].'" ';
				$query = mysqli_query($conn,$sql_up_fab);
				//echo $sql_up_fab.'<br>';
			}

		}else if($_REQUEST['type_id'][$i]==2){

			//echo '<hr>Detail = '.$_REQUEST['used_detail_id'][$i].'<br>';

			$sql_acc = 'SELECT * FROM accessory where accessory_id="'.$_REQUEST['materials_id'][$i].'"';
			$query_acc = $conn->query($sql_acc);
			$rs_acc = $query_acc->fetch_assoc();

			//echo $sql_acc.'<br>';

			$sql_used_detail = 'SELECT * FROM used_detail where used_detail_id="'.$_REQUEST['used_detail_id'][$i].'"';
			$query_used_detail = $conn->query($sql_used_detail);
			$rs_used_detail = $query_used_detail->fetch_assoc();

			//echo $sql_used_detail.'<br>';

			$used_detail_total=$rs_used_detail['used_detail_price']*$_REQUEST['used_detail_used'][$i];

			//echo $used_detail_total.'='.$rs_used_detail['used_detail_price'].'*'.$_REQUEST['used_detail_used'][$i].'<br>';

			$acc_used_old=$rs_acc['accessory_used']-$rs_used_detail['used_detail_used'];
			$acc_used=$acc_used_old+$_REQUEST['used_detail_used'][$i];
			$acc_balance=$rs_acc['accessory_piece']-$acc_used;
			$acc_total=$rs_acc['accessory_in_price']*$acc_used;

			//echo '<br>'.$acc_used_old.'='.$rs_acc['accessory_used'].'-'.$rs_used_detail['used_detail_used'];
			//echo '<br>'.$acc_used.'='.$acc_used_old.'-'.$_REQUEST['used_detail_used'][$i];
			//echo '<br>'.$acc_balance.'='.$rs_acc['accessory_piece'].'-'.$acc_used;
			//echo '<br>'.$acc_total.'='.$rs_acc['accessory_in_price'].'*'.$acc_used.'<br>';

			if($acc_balance>=0){
				$sql_up = 'UPDATE used_detail SET 
								used_detail_used = "'.$_REQUEST['used_detail_used'][$i].'",
								used_detail_total = "'.$used_detail_total.'"
								WHERE used_detail_id = "'.$_REQUEST['used_detail_id'][$i].'" ';
				$query = mysqli_query($conn,$sql_up);
				//echo $sql_up.'<br>';
				

				$sql_up_acc = 'UPDATE accessory SET 
								accessory_used = "'.$acc_used.'",
								accessory_balance = "'.$acc_balance.'",
								accessory_total = "'.$acc_total.'"
								WHERE accessory_id = "'.$_REQUEST['materials_id'][$i].'" ';
				$query = mysqli_query($conn,$sql_up_acc);
				//echo $sql_up_acc.'<br>';
			}

		}
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($_REQUEST['used_id']).'">';
}

if($ac=='draw_detail_del'){
	if(isset($_GET['used_id'])){$used_id=base64_decode($_GET['used_id']);}else{$used_id="";}
	if(isset($_GET['used_detail_id'])){$used_detail_id=base64_decode($_GET['used_detail_id']);}else{$used_detail_id="";}
	
	$sql_used = 'SELECT * FROM used_detail where used_detail_id="'.$used_detail_id.'" ';
	$query_used = $conn->query($sql_used);
	$rs_used = $query_used->fetch_assoc();

	if($rs_used['type_id']==1){
		$sql_fabric = 'SELECT * FROM fabric where fabric_id="'.$rs_used['materials_id'].'"';
		$query_fabric = $conn->query($sql_fabric);
		$rs_fabric = $query_fabric->fetch_assoc();
		//echo $sql_fabric;

		$fabric_used=$rs_fabric['fabric_used']-$rs_used['used_detail_used'];
		$fabric_balance=$rs_fabric['fabric_balance']+$rs_used['used_detail_used'];
		$fabric_total=$rs_fabric['fabric_in_piece']*$fabric_used;

		$sql_up = 'UPDATE fabric SET 
							fabric_used = "'.$fabric_used.'",
							fabric_balance = "'.$fabric_balance.'",
							fabric_total = "'.$fabric_total.'"
							WHERE fabric_id = "'.$rs_fabric['fabric_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
		//echo '<br>'.$sql_up;
	}else if($rs_used['type_id']==2){
		$sql_acc = 'SELECT * FROM accessory where accessory_id="'.$rs_used['materials_id'].'"';
		$query_acc = $conn->query($sql_acc);
		$rs_acc = $query_acc->fetch_assoc();
		//echo $sql_acc;

		$acc_used=$rs_acc['accessory_used']-$rs_used['used_detail_used'];
		$acc_balance=$rs_acc['accessory_balance']+$rs_used['used_detail_used'];
		$acc_total=$rs_acc['accessory_piece']*$acc_used;

		$sql_up = 'UPDATE accessory SET 
							accessory_used = "'.$acc_used.'",
							accessory_balance = "'.$acc_balance.'",
							accessory_total = "'.$acc_total.'"
							WHERE accessory_id = "'.$rs_acc['accessory_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
		//echo '<br>'.$sql_up;
	}

	$sql_dD = 'DELETE FROM used_detail WHERE used_detail_id = "'.$used_detail_id.'"';
	$query = $conn->query($sql_dD);
	//echo $sql_dD;

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($used_id).'">';	
}

if($ac=='draw_del'){
	if(isset($_GET['used_id'])){$used_id=base64_decode($_GET['used_id']);}else{$used_id="";}

	$sql_dD = 'DELETE FROM used_head WHERE used_id = "'.$used_id.'"';
	$query = $conn->query($sql_dD);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw').'">';
}

if($ac=='draw_update'){
	
	$sql_up = 'UPDATE used_head SET 
					used_order_code = "'.$_REQUEST['used_order_code'].'",
					used_date = "'.$_REQUEST['used_date'].'",
					used_update = "'.$strDate.'",
					used_update_user = "'.$_SESSION['employee_id'].'"
					WHERE used_id = "'.$_REQUEST['used_id'].'" ';
	$query = mysqli_query($conn,$sql_up);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($_REQUEST['used_id']).'">';
}