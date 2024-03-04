<?php
include('alert.php');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='supplier_add'){
	
	$q_sub = 'SELECT * FROM supplier WHERE supplier_name="'.$_REQUEST['supplier_name'].'" OR supplier_code="'.$_REQUEST['supplier_code'].'"';
	$query_sub = $conn->query($q_sub);
	$rs_sub = $query_sub->fetch_assoc();

	if(!empty($rs_sub['supplier_id'])){
		echo $no_saved;
		echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('supplier').'&op='.base64_encode('supplier_form').'">';
	}else{
		$sql_add = 'INSERT INTO supplier (
								supplier_code,
								supplier_name,
								supplier_address
								) VALUES (
								"'.$_REQUEST['supplier_code'].'",
								"'.$_REQUEST['supplier_name'].'",
								"'.$_REQUEST['supplier_address'].'"
								)';
		$query = $conn->query($sql_add);

		echo $saved;
		echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('supplier').'&op='.base64_encode('supplier_list').'">';
	}
}

if($ac=='supplier_update'){
	
	$sql_up = 'UPDATE supplier SET 
					supplier_code = "'.$_REQUEST['supplier_code'].'",
					supplier_name = "'.$_REQUEST['supplier_name'].'",
					supplier_address = "'.$_REQUEST['supplier_address'].'"
					WHERE supplier_id = "'.$_REQUEST['supplier_id'].'" ';
	$query = mysqli_query($conn,$sql_up);

	echo $saved;
	echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('supplier').'&op='.base64_encode('supplier_detail').'&supplier_id='.base64_encode($_REQUEST['supplier_id']).'">';
	
}

if($ac=='supplier_import'){

	if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"],'files/'.$_FILES["fileUpload"]["name"])){
		$objCSV = fopen('files/'.$_FILES["fileUpload"]["name"], "r");

		while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
			$supplier_code=trim($objArr[0]);
			$q_sub = 'SELECT * FROM supplier WHERE supplier_code LIKE "%'.$supplier_code.'%"';
			$query_sub = $conn->query($q_sub);
			$rs_sub = $query_sub->fetch_assoc();

			if($rs_sub['supplier_id']==''){
				$sql_add = 'INSERT INTO supplier (supplier_code,supplier_name,supplier_address) VALUES ("'.$supplier_code.'","'.$objArr[1].'","'.$objArr[2].'")';
				$query = $conn->query($sql_add);
			}
		}
	}
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('supplier').'&op='.base64_encode('supplier_list').'">';
}
?>