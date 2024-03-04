<?php

if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='position_add'){
		
	$sql_add = 'INSERT INTO employee_position (employee_position_name) VALUES ("'.$_REQUEST['employee_position_name'].'")';
	$query = $conn->query($sql_add);

	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('position').'">';
}

if($ac=='position_edit'){
		
	$sql_up = 'UPDATE employee_position SET 
					employee_position_name = "'.$_REQUEST['employee_position_name'].'",
					employee_position_sort = "'.$_REQUEST['employee_position_sort'].'"
					WHERE employee_position_id = "'.$_REQUEST['employee_position_id'].'" ';
	$query = mysqli_query($conn,$sql_up);
	
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('position').'">';
}

if($ac=='position_del'){
	
	if(isset($_GET['employee_position_id'])){
		$em_id=base64_decode($_GET['employee_position_id']);
		$sql_db = 'DELETE FROM employee_position WHERE employee_position_id = "'.$em_id.'"';
		$query = $conn->query($sql_db);
		echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('position').'">';
	}else{
		$t_id="";
	}
}

if($ac=='materials_add'){
//product add
	$sql_add = 'INSERT INTO cat (type_id,cat_code,cat_name_en,cat_name_th) VALUES ("'.$_REQUEST['type_id'].'","'.$_REQUEST['cat_code'].'","'.$_REQUEST['cat_name_en'].'","'.$_REQUEST['cat_name_th'].'")';
	$query = $conn->query($sql_add);

	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('materials').'">';
}

if($ac=='materials_edit'){
	$sql_up = 'UPDATE cat SET 
					type_id = "'.$_REQUEST['type_id'].'",
					cat_code = "'.$_REQUEST['cat_code'].'",
					cat_name_en = "'.$_REQUEST['cat_name_en'].'",
					cat_name_th = "'.$_REQUEST['cat_name_th'].'"
					WHERE cat_id = "'.$_REQUEST['cat_id'].'" ';
	$query = mysqli_query($conn,$sql_up);
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('materials').'">';
}

if($ac=='materials_del'){
	
	if(isset($_GET['cat_id'])){
		$cat_id=base64_decode($_GET['cat_id']);
		$sql_db = 'DELETE FROM cat WHERE cat_id = "'.$cat_id.'"';
		$query = $conn->query($sql_db);
		echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('materials').'">';
	}else{
		$t_id="";
	}
}

if($ac=='materials_import'){

	if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"],'files/'.$_FILES["fileUpload"]["name"])){
		$objCSV = fopen('files/'.$_FILES["fileUpload"]["name"], "r");

		while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
			$cat_code=trim($objArr[1]);
			$q_cat = 'SELECT * FROM cat WHERE cat_code="'.$cat_code.'"';
			$query_cat = $conn->query($q_cat);
			$rs_cat = $query_cat->fetch_assoc();

			if($rs_cat['cat_id']==''){
				$sql_add = 'INSERT INTO cat (type_id,cat_code,cat_name_en,cat_name_th) VALUES ("'.$objArr[0].'","'.$cat_code.'","'.$objArr[2].'","'.$objArr[3].'")';
				$query = $conn->query($sql_add);
			}
		}
	}
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('setup').'&op='.base64_encode('materials').'">';
}
?>