<?php
include('alert.php');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='employee_add'){
	
	if($_REQUEST["employee_password"]!=''){$strPass = md5($_REQUEST["employee_password"]);}
	
	if(trim($_FILES["fileUpload"]["tmp_name"]) != ""){
		$tmp_file = explode(".",$_FILES['fileUpload']['name']);
		$dateUpFile=date("YmdHis");
	
		$images = $_FILES["fileUpload"]["tmp_name"];
		$new_images = $dateUpFile.".".$tmp_file['1'];
		
		copy($_FILES["fileUpload"]["tmp_name"],"files/".$new_images);
		$width=400;
		$size=GetimageSize($images);
		$height=round($width*$size[1]/$size[0]);
		$images_orig = ImageCreateFromJPEG($images);
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		ImageJPEG($images_fin,"files/thumb/".$new_images);
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);
	}else{
		$new_images='';
	}
	
	$sql_add = 'INSERT INTO employee (
							employee_name,
							employee_email,
							employee_tel,
							employee_position_id,
							employee_password,
							employee_auth,
							employee_image
							) VALUES (
							"'.$_REQUEST['employee_name'].'",
							"'.$_REQUEST['employee_email'].'",
							"'.$_REQUEST['employee_tel'].'",
							"'.$_REQUEST['employee_position_id'].'",
							"'.$strPass.'",
							"'.$_REQUEST['employee_password'].'",
							"'.$new_images.'"
							)';
	$query = $conn->query($sql_add);

	echo $saved;
	echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('employee').'&op='.base64_encode('employee_list').'">';
}else if($ac=='employee_update'){
	
	if(trim($_FILES["fileUpload"]["tmp_name"]) != ""){
		$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$_REQUEST['employee_id'].'"';
		$query_em_list = $conn->query($q_em_list);
		$rs_em_list = $query_em_list->fetch_assoc();
		
		if(isset($rs_em_list['employee_image'])){
			@unlink('files/'.$rs_em_list['employee_image']);
			@unlink('files/thumb/'.$rs_em_list['employee_image']);
		}
	}
	
	if(trim($_FILES["fileUpload"]["tmp_name"]) != ""){
		$tmp_file = explode(".",$_FILES['fileUpload']['name']);
		$dateUpFile=date("YmdHis");
	
		$images = $_FILES["fileUpload"]["tmp_name"];
		$new_images = $dateUpFile.".".$tmp_file['1'];
		
		copy($_FILES["fileUpload"]["tmp_name"],"files/".$new_images);
		$width=400;
		$size=GetimageSize($images);
		$height=round($width*$size[1]/$size[0]);
		$images_orig = ImageCreateFromJPEG($images);
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		ImageJPEG($images_fin,"files/thumb/".$new_images);
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);
		
		$sql_up = 'UPDATE employee SET employee_image = "'.$new_images.'" WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}
	
	if($_REQUEST['employee_password']!=''){
		$strPass = md5($_REQUEST["employee_password"]);
		
		$sql_up = 'UPDATE employee SET 
					employee_name = "'.$_REQUEST['employee_name'].'",
					employee_email = "'.$_REQUEST['employee_email'].'",
					employee_tel = "'.$_REQUEST['employee_tel'].'",
					employee_position_id = "'.$_REQUEST['employee_position_id'].'",
					employee_password = "'.$strPass.'",
					employee_auth = "'.$_REQUEST['employee_password'].'"
					WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}else{
		$sql_up = 'UPDATE employee SET 
					employee_name = "'.$_REQUEST['employee_name'].'",
					employee_email = "'.$_REQUEST['employee_email'].'",
					employee_tel = "'.$_REQUEST['employee_tel'].'",
					employee_position_id = "'.$_REQUEST['employee_position_id'].'"
					WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('employee').'&op='.base64_encode('employee_list').'">';
	
}else if($ac=='employee_del'){
	
	if(isset($_GET['employee_id'])){
		$em_id=base64_decode($_GET['employee_id']);
		
		$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$em_id.'"';
		$query_em_list = $conn->query($q_em_list);
		$rs_em_list = $query_em_list->fetch_assoc();
		
		if(isset($rs_em_list['employee_image'])){
			@unlink('files/'.$rs_em_list['employee_image']);
			@unlink('files/thumb/'.$rs_em_list['employee_image']);
		}
		
		$sql_db = 'DELETE FROM employee WHERE employee_id = "'.$em_id.'"';
		$query = $conn->query($sql_db);
		
		echo $saved;
		echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('employee').'&op='.base64_encode('employee_list').'">';
	}else{
		echo $no_saved;
		echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('employee').'&op='.base64_encode('employee_list').'">';
	}
	
}else if($ac=='employee_update_password'){
	
	if($_REQUEST['password']!=''){$strPass = md5($_REQUEST['password']);}
	
	$sql_up = 'UPDATE employee SET employee_password = "'.$strPass.'" , employee_auth="'.$_REQUEST['password'].'" WHERE employee_id = "'.$_SESSION['employee_id'].'" ';
	$query = mysqli_query($conn,$sql_up);
	
	echo '<meta http-equiv="refresh" content="0;URL='.$_REQUEST['bPage'].'">';
}else if($ac=='employee_update_self'){
	
	if(trim($_FILES["fileUpload"]["tmp_name"]) != ""){
		$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$_REQUEST['employee_id'].'"';
		$query_em_list = $conn->query($q_em_list);
		$rs_em_list = $query_em_list->fetch_assoc();
		
		if(isset($rs_em_list['employee_image'])){
			@unlink('files/'.$rs_em_list['employee_image']);
			@unlink('files/thumb/'.$rs_em_list['employee_image']);
		}
	}
	
	if(trim($_FILES["fileUpload"]["tmp_name"]) != ""){
		$tmp_file = explode(".",$_FILES['fileUpload']['name']);
		$dateUpFile=date("YmdHis");
	
		$images = $_FILES["fileUpload"]["tmp_name"];
		$new_images = $dateUpFile.".".$tmp_file['1'];
		
		copy($_FILES["fileUpload"]["tmp_name"],"files/".$new_images);
		$width=400;
		$size=GetimageSize($images);
		$height=round($width*$size[1]/$size[0]);
		$images_orig = ImageCreateFromJPEG($images);
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		ImageJPEG($images_fin,"files/thumb/".$new_images);
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);
		
		$sql_up = 'UPDATE employee SET employee_image = "'.$new_images.'" WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}
	
	if($_REQUEST['employee_password']!=''){
		$strPass = md5($_REQUEST["employee_password"]);
		
		$sql_up = 'UPDATE employee SET 
					employee_name = "'.$_REQUEST['employee_name'].'",
					employee_email = "'.$_REQUEST['employee_email'].'",
					employee_tel = "'.$_REQUEST['employee_tel'].'",
					employee_password = "'.$strPass.'",
					employee_auth = "'.$_REQUEST['employee_password'].'"
					WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}else{
		$sql_up = 'UPDATE employee SET 
					employee_name = "'.$_REQUEST['employee_name'].'",
					employee_email = "'.$_REQUEST['employee_email'].'",
					employee_tel = "'.$_REQUEST['employee_tel'].'"
					WHERE employee_id = "'.$_REQUEST['employee_id'].'" ';
		$query = mysqli_query($conn,$sql_up);
	}
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="1;URL=?vp='.base64_encode('profile').'">';
	
}

?>