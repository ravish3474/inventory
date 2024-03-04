<?php
	/*if($_SESSION['employee_position_id']!='0'){
		if($_SESSION['employee_position_id']==3){
			include('dashboard-designer.php');
		}else if($_SESSION['employee_position_id']==2){
			include('dashboard-sale.php');
		}else if($_SESSION['employee_position_id']==1 OR $_SESSION['employee_position_id']==4 OR $_SESSION['employee_position_id']==99 OR $_SESSION['employee_position_id']==6 OR $_SESSION['employee_position_id']==5 OR $_SESSION['employee_position_id']==7){
			echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('order').'&op='.base64_encode('order_list').'">';
		}
	}else if($_SESSION['customer_id']!=0){
		include('profile.php');
	}*/
	include('profile.php');
?>