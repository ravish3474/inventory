<?php
include('alert.php');
$strDate = date('Y-m-d H:i:s');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='fabrics_del'){
	if(isset($_GET['fab_id'])){$fab_id=base64_decode($_GET['fab_id']);}else{$fab_id="";}
	if(isset($_GET['cat_id'])){$cat_id=base64_decode($_GET['cat_id']);}else{$cat_id="";}
	if(isset($_GET['fabric_color'])){$fabric_color=base64_decode($_GET['fabric_color']);}else{$fabric_color="";}
	
	$sql_dFab = 'DELETE FROM fabric WHERE fabric_id = "'.$fab_id.'"';
	$query = $conn->query($sql_dFab);
	
	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('fabrics').'&op='.base64_encode('fabrics_detail').'&cat_id='.base64_encode($cat_id).'&fabric_color='.base64_encode($fabric_color).'">';
}

?>