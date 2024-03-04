<?php
include('alert.php');
$strDate = date('Y-m-d H:i:s');
if(isset($_GET['ac'])){$ac=base64_decode($_GET['ac']);}else{$ac="";}

if($ac=='po_add'){
	$sql_add_re = 'INSERT INTO receipt (receipt_number,receipt_supplier,receipt_date,receipt_user) VALUES ("'.$_REQUEST['receipt_number'].'","'.$_REQUEST['supplier_id'].'","'.$_REQUEST['receipt_date'].'","'.$_SESSION['employee_id'].'")';
	$query = $conn->query($sql_add_re);

	$sql_re = 'SELECT receipt_id FROM receipt where receipt_number="'.$_REQUEST['receipt_number'].'"';
	$query_re = $conn->query($sql_re);
	$rs_re = $query_re->fetch_assoc();
	
	$po_no=trim($_REQUEST['po_no']);
	$sql_add = 'INSERT INTO po_head (po_no,receipt_id,supplier_id,po_date,po_user) VALUES ("'.$po_no.'","'.$rs_re['receipt_id'].'","'.$_REQUEST['supplier_id'].'","'.$_REQUEST['po_date'].'","'.$_SESSION['employee_id'].'")';
	$query = $conn->query($sql_add);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($rs_re['receipt_id']).'">';
}

if($ac=='po_add_other'){

	$po_no=trim($_REQUEST['po_no']);
	
	$sql_add = 'INSERT INTO po_head (po_no,receipt_id,supplier_id,po_date,po_user) VALUES ("'.$po_no.'","'.$_REQUEST['receipt_id'].'","'.$_REQUEST['supplier_id'].'","'.$_REQUEST['po_date'].'","'.$_SESSION['employee_id'].'")';
	$query = $conn->query($sql_add);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($_REQUEST['receipt_id']).'">';
}

if($ac=='po_edit'){
	$sql_up = 'UPDATE po_head SET 
					po_no = "'.$_REQUEST['po_no'].'",
					po_date = "'.$_REQUEST['po_date'].'",
					po_update = "'.$strDate.'",
					po_user_update = "'.$_SESSION['employee_id'].'"
					WHERE po_id = "'.$_REQUEST['po_id'].'" ';
	$query = mysqli_query($conn,$sql_up);
	
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($_REQUEST['receipt_id']).'">';
}

if($ac=='po_del'){
	if(isset($_GET['re_id'])){$re_id=base64_decode($_GET['re_id']);}else{$re_id="";}

	$sql_db = 'DELETE FROM receipt WHERE receipt_id = "'.$re_id.'"';
	$query = $conn->query($sql_db);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po').'&op='.base64_encode('po_list').'">';
}

if($ac=='po_detail_add'){
	include('up-session.php');
	if(!empty($_REQUEST['po_id']) AND !empty($_REQUEST['po_type_id']) AND !empty($_REQUEST['cat_id']) AND !empty($_REQUEST['po_detail_type_unit'])){
		
		$po_detail_total=str_replace(',','',$_REQUEST['po_detail_total']);
		if($_REQUEST['po_type_id']==1){
			$q_fab = 'SELECT * FROM fabric WHERE cat_id="'.$_REQUEST['cat_id'].'" AND fabric_color="'.$_REQUEST['po_detail_color'].'" AND fabric_no="'.$_REQUEST['po_detail_no'].'" ';
			$query_fab = $conn->query($q_fab);
			$rs_fab = $query_fab->fetch_assoc();
			$code_count = $query_fab->num_rows;

			if($code_count==''){
				$sql_add = 'INSERT INTO po_detail ( 
										po_id,
										receipt_id,
										supplier_id,
										po_type_id, 
										cat_id,
										po_detail_color,
										po_detail_no,
										po_detail_box,
										po_detail_piece,
										po_detail_type_unit,
										po_detail_price,
										po_detail_total,
										po_date
									) VALUES (
										"'.$_REQUEST['po_id'].'",
										"'.$_REQUEST['receipt_id'].'",
										"'.$_REQUEST['supplier_id'].'",
										"'.$_REQUEST['po_type_id'].'",
										"'.$_REQUEST['cat_id'].'",
										"'.$_REQUEST['po_detail_color'].'",
										"'.$_REQUEST['po_detail_no'].'",
										"'.$_REQUEST['po_detail_box'].'",
										"'.$_REQUEST['po_detail_piece'].'",
										"'.$_REQUEST['po_detail_type_unit'].'",
										"'.$_REQUEST['po_detail_price'].'",
										"'.$po_detail_total.'",
										"'.$_REQUEST['po_date'].'"
									)';
				$query = $conn->query($sql_add);
				
				$sql_add_fab = 'INSERT INTO fabric ( 
										po_id,
										receipt_id,
										supplier_id,
										cat_id,
										fabric_color,
										fabric_no,
										fabric_box,
										fabric_in_piece,
										fabric_type_unit,
										fabric_in_price,
										fabric_in_total,
										fabric_balance,
										fabric_date_create,
										fabric_user_create
									) VALUES (
										"'.$_REQUEST['po_id'].'",
										"'.$_REQUEST['receipt_id'].'",
										"'.$_REQUEST['supplier_id'].'",
										"'.$_REQUEST['cat_id'].'",
										"'.$_REQUEST['po_detail_color'].'",
										"'.$_REQUEST['po_detail_no'].'",
										"'.$_REQUEST['po_detail_box'].'",
										"'.$_REQUEST['po_detail_piece'].'",
										"'.$_REQUEST['po_detail_type_unit'].'",
										"'.$_REQUEST['po_detail_price'].'",
										"'.$po_detail_total.'",
										"'.$_REQUEST['po_detail_piece'].'",
										"'.$_REQUEST['po_date'].'",
										"'.$_SESSION['employee_id'].'"
									)';
				$query = $conn->query($sql_add_fab);
			}else{
				echo '<script language="javascript">';
				echo 'alert("Already use this code number")';  //not showing an alert box.
				echo '</script>';
			}
		}

		$sql_po_de_id = 'SELECT * FROM po_detail where po_id="'.$_REQUEST['po_id'].'" AND po_type_id="'.$_REQUEST['po_type_id'].'" AND cat_id="'.$_REQUEST['cat_id'].'" AND po_detail_color="'.$_REQUEST['po_detail_color'].'" AND po_detail_no="'.$_REQUEST['po_detail_no'].'" ';
		$query_po_de_id = $conn->query($sql_po_de_id);
		$rs_po_de_id = $query_po_de_id->fetch_assoc();

		$sql_fab_id = 'SELECT * FROM fabric where po_id="'.$_REQUEST['po_id'].'" AND cat_id="'.$_REQUEST['cat_id'].'" AND fabric_color="'.$_REQUEST['po_detail_color'].'" AND fabric_no="'.$_REQUEST['po_detail_no'].'" ';
		$query_fab_id = $conn->query($sql_fab_id);
		$rs_fab_id = $query_fab_id->fetch_assoc();

		$sql_add_log = 'INSERT INTO po_log (
									po_log_action,
									po_detail_id,
									po_id,
									po_type_id,
									materials_id,
									cat_id,
									po_log_color,
									po_log_no,
									po_log_box,
									po_log_piece,
									po_log_type_unit,
									po_log_price,
									po_log_total,
									po_log_date,
									po_log_user
								) VALUES (
									"Add",
									"'.$rs_po_de_id['po_detail_id'].'",
									"'.$_REQUEST['po_id'].'",
									"'.$_REQUEST['po_type_id'].'",
									"'.$rs_fab_id['fabric_id'].'",
									"'.$_REQUEST['cat_id'].'",
									"'.$_REQUEST['po_detail_color'].'",
									"'.$_REQUEST['po_detail_no'].'",
									"'.$_REQUEST['po_detail_box'].'",
									"'.$_REQUEST['po_detail_piece'].'",
									"'.$_REQUEST['po_detail_type_unit'].'",
									"'.$_REQUEST['po_detail_price'].'",
									"'.$_REQUEST['po_detail_total'].'",
									"'.$strDate.'",
									"'.$_SESSION['employee_name'].'"
								)';
		$query = $conn->query($sql_add_log);
	}else{
		echo '<script language="javascript">';
		echo 'alert("Please select type and product before click Add.")';  //not showing an alert box.
		echo '</script>';
	}

	$sql_po_de = 'SELECT * FROM po_detail where po_id="'.$_REQUEST['po_id'].'"';
	$query_po_de = $conn->query($sql_po_de);
	while ($rs_po_de = $query_po_de->fetch_assoc()) {
?>
	<tr class="text-right">
		<td class="text-center">
			<?php
			switch ($rs_po_de['po_type_id']) {
				case 1:
					$t_name='Fabrics';
					break;
				case 2:
					$t_name='Accessory';
					break;
				case 0:
					$t_name='No type';
					break;
			}
			echo $t_name;
			?>
		</td>
		<td class="text-center">
			<?php 
			$sql_cat = 'SELECT * FROM cat where cat_id="'.$rs_po_de['cat_id'].'"';
			$query_cat = $conn->query($sql_cat);
			$rs_cat = $query_cat->fetch_assoc();
			echo $rs_cat['cat_name_en'];
			?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de['po_detail_color'];?>
		</td>
		<td class="text-center">
			<?php 
			switch ($rs_po_de['po_type_id']) {
				case 1:
					$numb=$rs_po_de['po_detail_no'];
					break;
				case 2:
					$numb=$rs_po_de['po_detail_size'];
					break;
				case 0:
					$numb='-';
					break;
			}
			echo $numb;
			?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de['po_detail_box'];?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de['po_detail_piece'];?>
		</td>
		<td class="text-center">
			<?php
			switch ($rs_po_de['po_detail_type_unit']) {
				case 1:
					$tu_name='Piece';
					break;
				case 2:
					$tu_name='Yard';
					break;
				case 3:
					$tu_name='KG';
					break;
			}
			echo $tu_name;
			?>
		</td>
		<td>
			<?php echo number_format($rs_po_de['po_detail_price'],2).'&nbsp;&nbsp;';?>
		</td>
		<td>
			<?php echo number_format($rs_po_de['po_detail_total'],2).'&nbsp;&nbsp;';?>
		</td>
		<td class="text-center">
			<?php
			$sql_use = 'SELECT * FROM used_detail where type_id="'.$rs_po_de['po_type_id'].'" AND cat_id="'.$rs_po_de['cat_id'].'" AND used_detail_color="'.$rs_po_de['po_detail_color'].'" AND used_detail_no="'.$rs_po_de['po_detail_no'].'" ';
			$query_use = $conn->query($sql_use);
			$rs_use = $query_use->fetch_assoc();
			if($rs_use['used_detail_id']==''){
			?>
			<a href="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_detail_del').'&po_id='.base64_encode($rs_po['po_id']).'&po_detail_id='.base64_encode($rs_po_de['po_detail_id']).'&re_id='.base64_encode($re_id);?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you want to delete ?')">Del</a>
		<?php }?>
		<a class="e_po_de btn btn-sm btn-info text-white" data-toggle="modal" data-target="#editPoModal" data-id="<?php echo $rs_po_de['po_detail_id'];?>">Edit</a>
		</td>
	</tr>

<?php		
	}
?>
	<?php
	$sql_po_de_sum = 'SELECT * FROM po_detail where po_id="'.$_REQUEST['po_id'].'" ';
	echo $sql_po_de_sum ;
	$query_po_de_sum = $conn->query($sql_po_de_sum);
	$rs_po_de_sum = $query_po_de_sum->fetch_assoc();
	if($rs_po_de_sum['po_detail_id']!=''){
		$sql_po_sum = 'SELECT SUM(po_detail_piece) AS sum_piece , SUM(po_detail_total) AS sum_total FROM po_detail where po_id="'.$_REQUEST['po_id'].'" ';
		$query_po_sum = $conn->query($sql_po_sum);
		$rs_po_sum = $query_po_sum->fetch_assoc();
		?>
		<tr class="bg-dark text-white text-center">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo number_format($rs_po_sum['sum_piece'],2);?></td>
			<td></td>
			<td></td>
			<td><?php echo number_format($rs_po_sum['sum_total'],2);?></td>
			<td></td>
		</tr>
	<?php
	}
	?>
<?php
}

if($ac=='po_detail_add_acc'){
	include('up-session.php');
	if(!empty($_REQUEST['po_id']) AND !empty($_REQUEST['po_type_id']) AND !empty($_REQUEST['cat_id'])){
		$sql_acc_no = 'SELECT accessory_no FROM accessory ORDER BY accessory_id DESC ';
		$query_acc_no = $conn->query($sql_acc_no);
		$rs_acc_no = $query_acc_no->fetch_assoc();
		$accessory_no = $rs_acc_no['accessory_no']+1;

		$sql_add = 'INSERT INTO po_detail ( 
								po_id,
								receipt_id,
								supplier_id,
								po_type_id,
								cat_id,
								po_detail_color,
								po_detail_no,
								po_detail_size,
								po_detail_piece,
								po_detail_type_unit,
								po_detail_price,
								po_detail_total,
								po_date
							) VALUES (
								"'.$_REQUEST['po_id'].'",
								"'.$_REQUEST['receipt_id'].'",
								"'.$_REQUEST['supplier_id'].'",
								"'.$_REQUEST['po_type_id'].'",
								"'.$_REQUEST['cat_id'].'",
								"'.$_REQUEST['po_detail_color'].'",
								"'.$accessory_no.'",
								"'.$_REQUEST['po_detail_size'].'",
								"'.$_REQUEST['po_detail_piece'].'",
								"'.$_REQUEST['po_detail_type_unit'].'",
								"'.$_REQUEST['po_detail_price'].'",
								"'.$_REQUEST['po_detail_total'].'",
								"'.$strDate.'"
							)';
		$query = $conn->query($sql_add);

		if($_REQUEST['po_type_id']==2){
			$sql_add = 'INSERT INTO accessory ( 
									po_id,
									receipt_id,
									supplier_id,
									cat_id,
									accessory_no,
									accessory_size,
									accessory_color,
									accessory_in_price,
									accessory_in_total,
									accessory_piece,
									accessory_type_unit,
									accessory_balance,
									accessory_date_create,
									accessory_user_create
								) VALUES (
									"'.$_REQUEST['po_id'].'",
									"'.$_REQUEST['receipt_id'].'",
									"'.$_REQUEST['supplier_id'].'",
									"'.$_REQUEST['cat_id'].'",
									"'.$accessory_no.'",
									"'.$_REQUEST['po_detail_size'].'",
									"'.$_REQUEST['po_detail_color'].'",
									"'.$_REQUEST['po_detail_price'].'",
									"'.$_REQUEST['po_detail_total'].'",
									"'.$_REQUEST['po_detail_piece'].'",
									"'.$_REQUEST['po_detail_type_unit'].'",
									"'.$_REQUEST['po_detail_piece'].'",
									"'.$strDate.'",
									"'.$_SESSION['employee_id'].'"
								)';
			$query = $conn->query($sql_add);
		}
	}else{
		echo '<script language="javascript">';
		echo 'alert("Please select type and product before clock Add.")';  //not showing an alert box.
		echo '</script>';
	}

	$sql_po_de_acc = 'SELECT * FROM po_detail where po_id="'.$_REQUEST['po_id'].'"';
	$query_po_de_acc = $conn->query($sql_po_de_acc);
	while ($rs_po_de_acc = $query_po_de_acc->fetch_assoc()) {
?>
	<tr class="text-right">
		<td class="text-center">
			<?php
			switch ($rs_po_de_acc['po_type_id']) {
				case 1:
					$t_name='Fabrics';
					break;
				case 2:
					$t_name='Accessory';
					break;
				case 0:
					$t_name='No type';
					break;
			}
			echo $t_name;
			?>
		</td>
		<td class="text-center">
			<?php 
			$sql_cat = 'SELECT * FROM cat where cat_id="'.$rs_po_de_acc['cat_id'].'"';
			$query_cat = $conn->query($sql_cat);
			$rs_cat = $query_cat->fetch_assoc();
			echo $rs_cat['cat_name_en'];
			?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de_acc['po_detail_color'];?>
		</td>
		<td class="text-center">
			<?php 
			switch ($rs_po_de_acc['po_type_id']) {
				case 1:
					$numb=$rs_po_de_acc['po_detail_no'];
					break;
				case 2:
					$numb=$rs_po_de_acc['po_detail_size'];
					break;
				case 0:
					$numb='-';
					break;
			}
			echo $numb;
			?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de_acc['po_detail_box'];?>
		</td>
		<td class="text-center">
			<?php echo $rs_po_de_acc['po_detail_piece'];?>
		</td>
		<td class="text-center">
			<?php
			switch ($rs_po_de_acc['po_detail_type_unit']) {
				case 1:
					$tu_name='Piece';
					break;
				case 2:
					$tu_name='Yard';
					break;
				case 3:
					$tu_name='KG';
					break;
			}
			echo $tu_name;
			?>
		</td>
		<td>
			<?php echo number_format($rs_po_de_acc['po_detail_price'],2).'&nbsp;&nbsp;';?>
		</td>
		<td>
			<?php echo number_format($rs_po_de_acc['po_detail_total'],2).'&nbsp;&nbsp;';?>
		</td>
		<td class="text-center">
			<a href="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_detail_del').'&po_id='.base64_encode($po_list).'&po_detail_id='.base64_encode($rs_po_de_acc['po_detail_id']);?>" class="btn btn-sm btn-outline-danger">Del</a>
		</td>
	</tr>
<?php
	}
}

if($ac=='po_head_del'){
	if(isset($_GET['re_id'])){$re_id=base64_decode($_GET['re_id']);}else{$re_id="";}
	if(isset($_GET['po_id'])){$po_id=base64_decode($_GET['po_id']);}else{$po_id="";}

	$sql_db = 'DELETE FROM po_head WHERE po_id = "'.$po_id.'"';
	$query = $conn->query($sql_db);

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($re_id).'">';
}

if($ac=='po_detail_del'){
	
	if(isset($_GET['re_id'])){$re_id=base64_decode($_GET['re_id']);}else{$re_id="";}
	if(isset($_GET['po_id'])){$po_id=base64_decode($_GET['po_id']);}else{$po_id="";}
	if(isset($_GET['po_detail_id'])){$po_detail_id=base64_decode($_GET['po_detail_id']);}else{$po_detail_id="";}
	
	$sql_po_de = 'SELECT * FROM po_detail where po_detail_id="'.$po_detail_id.'"';
	$query_po_de = $conn->query($sql_po_de);
	$rs_po_de = $query_po_de->fetch_assoc();

	if($rs_po_de['po_type_id']=='1'){
		$sql_fab = 'SELECT fabric_id FROM fabric where cat_id="'.$rs_po_de['cat_id'].'" AND fabric_color="'.$rs_po_de['po_detail_color'].'" AND fabric_no="'.$rs_po_de['po_detail_no'].'" ';
		$query_fab = $conn->query($sql_fab);
		$rs_fab = $query_fab->fetch_assoc();

		if($rs_fab['fabric_id']!=''){
			$sql_add_log = 'INSERT INTO po_log (
										po_log_action,
										po_detail_id,
										po_id,
										po_type_id,
										materials_id,
										cat_id,
										po_log_color,
										po_log_no,
										po_log_box,
										po_log_piece,
										po_log_type_unit,
										po_log_price,
										po_log_total,
										po_log_date,
										po_log_user
									) VALUES (
										"Delete",
										"'.$rs_po_de['po_detail_id'].'",
										"'.$rs_po_de['po_id'].'",
										"'.$rs_po_de['po_type_id'].'",
										"'.$rs_fab['fabric_id'].'",
										"'.$rs_po_de['cat_id'].'",
										"'.$rs_po_de['po_detail_color'].'",
										"'.$rs_po_de['po_detail_no'].'",
										"'.$rs_po_de['po_detail_box'].'",
										"'.$rs_po_de['po_detail_piece'].'",
										"'.$rs_po_de['po_detail_type_unit'].'",
										"'.$rs_po_de['po_detail_price'].'",
										"'.$rs_po_de['po_detail_total'].'",
										"'.$strDate.'",
										"'.$_SESSION['employee_name'].'"
									)';
			$query = $conn->query($sql_add_log);

			$sql_df = 'DELETE FROM fabric WHERE fabric_id = "'.$rs_fab['fabric_id'].'"';
			$query = $conn->query($sql_df);	
		}
	}else if($rs_po_de['po_type_id']=='2'){
		$sql_acc = 'SELECT accessory_id FROM accessory where cat_id="'.$rs_po_de['cat_id'].'" AND accessory_color="'.$rs_po_de['po_detail_color'].'" AND accessory_no="'.$rs_po_de['po_detail_no'].'" ';
		$query_acc = $conn->query($sql_acc);
		$rs_acc = $query_acc->fetch_assoc();
		if($rs_acc['accessory_id']!=''){
			$sql_da = 'DELETE FROM accessory WHERE accessory_id = "'.$rs_acc['accessory_id'].'"';
			$query = $conn->query($sql_da);	
		}
	}

	if(!empty($po_id) AND !empty($po_detail_id)){
		$sql_db = 'DELETE FROM po_detail WHERE po_detail_id = "'.$po_detail_id.'"';
		$query = $conn->query($sql_db);
	}

	echo $saved;
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($re_id).'">';
}

if($ac=='po_detail_edit'){
	if($_REQUEST['po_type_id']==1){
		
		$q_po_de = 'SELECT * FROM po_detail INNER JOIN po_head ON po_detail.po_id=po_head.po_id WHERE po_detail_id="'.$_REQUEST['po_detail_id'].'" ';
		$query_po_de = $conn->query($q_po_de);
		$rs_po_de = $query_po_de->fetch_assoc();

		if($rs_po_de['po_detail_id']!=''){
			$q_fab = 'SELECT * FROM fabric WHERE po_id="'.$rs_po_de['po_id'].'" AND cat_id="'.$rs_po_de['cat_id'].'" AND fabric_color="'.$rs_po_de['po_detail_color'].'" AND fabric_no="'.$rs_po_de['po_detail_no'].'" ';
			$query_fab = $conn->query($q_fab);
			$rs_fab = $query_fab->fetch_assoc();

			$po_detail_total=str_replace(',','',$_REQUEST['po_detail_total']);

			$sql_up_po_de = 'UPDATE po_detail SET 
							cat_id = "'.$_REQUEST['cat_id'].'",
							po_detail_color = "'.$_REQUEST['po_detail_color'].'",
							po_detail_no = "'.$_REQUEST['po_detail_no'].'",
							po_detail_box = "'.$_REQUEST['po_detail_box'].'",
							po_detail_piece = "'.$_REQUEST['po_detail_piece'].'",
							po_detail_type_unit = "'.$_REQUEST['po_detail_type_unit'].'",
							po_detail_price = "'.$_REQUEST['po_detail_price'].'",
							po_detail_total = "'.$po_detail_total.'"
							WHERE po_detail_id = "'.$rs_po_de['po_detail_id'].'" ';
			$query = mysqli_query($conn,$sql_up_po_de);
			//echo $sql_up_po_de.'<br><br>';

			$fabric_balance=$_REQUEST['po_detail_piece']-$rs_fab['fabric_used'];
			$fabric_total=$_REQUEST['po_detail_price']*$rs_fab['fabric_used'];
			$sql_up_fab = 'UPDATE fabric SET 
							cat_id = "'.$_REQUEST['cat_id'].'",
							fabric_color = "'.$_REQUEST['po_detail_color'].'",
							fabric_no = "'.$_REQUEST['po_detail_no'].'",
							fabric_box = "'.$_REQUEST['po_detail_box'].'",
							fabric_in_piece = "'.$_REQUEST['po_detail_piece'].'",
							fabric_type_unit = "'.$_REQUEST['po_detail_type_unit'].'",
							fabric_in_price = "'.$_REQUEST['po_detail_price'].'",
							fabric_in_total = "'.$po_detail_total.'",
							fabric_balance = "'.$fabric_balance.'",
							fabric_total = "'.$fabric_total.'"
							WHERE fabric_id = "'.$rs_fab['fabric_id'].'" ';
			$query = mysqli_query($conn,$sql_up_fab);
			//echo $sql_up_fab.'<br><br>';

			$q_sum_head = 'SELECT SUM(po_detail_total) AS h_total FROM po_detail WHERE po_id="'.$rs_po_de['po_id'].'" ';
			$query_sum_head = $conn->query($q_sum_head);
			$rs_sum_head = $query_sum_head->fetch_assoc();
			//echo $q_sum_head.'<br><br>';

			$sql_up_head = 'UPDATE po_head SET po_total = "'.$rs_sum_head['h_total'].'" WHERE po_id = "'.$rs_po_de['po_id'].'" ';
			$query = mysqli_query($conn,$sql_up_head);
			//echo $sql_up_head.'<br><br>';

			$q_use = 'SELECT * FROM used_detail WHERE materials_id="'.$rs_fab['fabric_id'].'" AND type_id="'.$_REQUEST['po_type_id'].'" ';
			$query_use = $conn->query($q_use);
			$use_count = $query_use->num_rows;

			if($use_count!=''){
				while ($rs_use = $query_use->fetch_assoc()) {
					$used_detail_total=$_REQUEST['po_detail_price']*$rs_use['used_detail_used'];
					
					$sql_up_use = 'UPDATE used_detail SET 
										cat_id = "'.$_REQUEST['cat_id'].'",
										used_detail_color = "'.$_REQUEST['po_detail_color'].'",
										used_detail_no = "'.$_REQUEST['po_detail_no'].'",
										used_detail_price = "'.$_REQUEST['po_detail_piece'].'",
										used_detail_total = "'.$used_detail_total.'"
										WHERE used_detail_id = "'.$rs_use['used_detail_id'].'" ';
					$query = mysqli_query($conn,$sql_up_use);
					//echo $sql_up_use.'<br><br>';

					$q_sum_use_head = 'SELECT SUM(used_detail_total) AS uh_total FROM used_detail WHERE used_id="'.$rs_use['used_id'].'" ';
					$query_sum_use_head = $conn->query($q_sum_use_head);
					$rs_sum_use_head = $query_sum_use_head->fetch_assoc();

					$sql_up_use_head = 'UPDATE used_head SET used_total = "'.$rs_sum_use_head['uh_total'].'" WHERE used_id = "'.$rs_use['used_id'].'" ';
					$query = mysqli_query($conn,$sql_up_use_head);
					//echo $sql_up_use_head.'<br><br>';
				}
			}
			
			$sql_add_log = 'INSERT INTO po_log (
										po_log_action,
										po_detail_id,
										po_id,
										po_type_id,
										materials_id,
										cat_id,
										po_log_color,
										po_log_no,
										po_log_size,
										po_log_box,
										po_log_piece,
										po_log_type_unit,
										po_log_price,
										po_log_total,
										po_log_date,
										po_log_user
									) VALUES (
										"Edit from",
										"'.$rs_po_de['po_detail_id'].'",
										"'.$rs_po_de['po_id'].'",
										"'.$rs_po_de['po_type_id'].'",
										"'.$rs_fab['fabric_id'].'",
										"'.$rs_po_de['cat_id'].'",
										"'.$rs_po_de['po_detail_color'].'",
										"'.$rs_po_de['po_detail_no'].'",
										"'.$rs_po_de['po_detail_size'].'",
										"'.$rs_po_de['po_detail_box'].'",
										"'.$rs_po_de['po_detail_piece'].'",
										"'.$rs_po_de['po_detail_type_unit'].'",
										"'.$rs_po_de['po_detail_price'].'",
										"'.$rs_po_de['po_detail_total'].'",
										"'.$strDate.'",
										"'.$_SESSION['employee_name'].'"
									)';
			$query = $conn->query($sql_add_log);
		}
		echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($rs_po_de['receipt_id']).'">';
	}
}

if($ac=='re_import'){

	if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"],'files/'.$_FILES["fileUpload"]["name"])){
		$objCSV = fopen('files/'.$_FILES["fileUpload"]["name"], "r");

		while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
			$supplier_code=trim($objArr[2]);
			$q_sub = 'SELECT * FROM supplier WHERE supplier_code LIKE "%'.$supplier_code.'%"';
			$query_sub = $conn->query($q_sub);
			$rs_sub = $query_sub->fetch_assoc();
			//echo $q_sub.'- '.$rs_sub['supplier_id'].'<br>';

			if($rs_sub['supplier_id']!=''){
				$re_no=trim($objArr[1]);
				$sql_add = 'INSERT INTO receipt (receipt_number,receipt_supplier,receipt_date) VALUES ("'.$re_no.'","'.$rs_sub['supplier_id'].'","'.$objArr[3].'")';
				$query = $conn->query($sql_add);
				//echo $sql_add.'<br>';
			}
		}
		@unlink('files/'.$_FILES["fileUpload"]["name"]);
	}
	
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po').'&op='.base64_encode('po_list').'">';
}

if($ac=='po_import'){

	if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"],'files/'.$_FILES["fileUpload"]["name"])){
		$objCSV = fopen('files/'.$_FILES["fileUpload"]["name"], "r");

		while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
			$supplier_code=trim($objArr[2]);
			$q_sub = 'SELECT * FROM supplier WHERE supplier_code LIKE "'.$supplier_code.'"';
			$query_sub = $conn->query($q_sub);
			$rs_sub = $query_sub->fetch_assoc();
			//echo $q_sub.'- '.$rs_sub['supplier_id'].'<br>';

			if($rs_sub['supplier_id']!=''){
				$po_no=trim($objArr[1]);
				$sql_add = 'INSERT INTO po_head (po_no,receipt_id,supplier_id,po_date) VALUES ("'.$po_no.'","'.$objArr[4].'","'.$rs_sub['supplier_id'].'","'.$objArr[3].'")';
				$query = $conn->query($sql_add);
				//echo $sql_add.'<br><br>';
			}
		}
		@unlink('files/'.$_FILES["fileUpload"]["name"]);
	}
	
	echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po').'&op='.base64_encode('po_list').'">';
}

if($ac=='po_detail_import'){
	if(move_uploaded_file($_FILES["fileUploadDetail"]["tmp_name"],'files/'.$_FILES["fileUploadDetail"]["name"])){
		$objCSV = fopen('files/'.$_FILES["fileUploadDetail"]["name"], "r");
		while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {

			$po_no=trim($objArr[1]);
			$q_po = 'SELECT * FROM po_head WHERE po_no LIKE "'.$po_no.'"';
			$query_po = $conn->query($q_po);
			$rs_po = $query_po->fetch_assoc();

			$cat_code=trim($objArr[4]);
			$q_cat = 'SELECT * FROM cat WHERE cat_code="'.$cat_code.'"';
			$query_cat = $conn->query($q_cat);
			$rs_cat = $query_cat->fetch_assoc();

			$sql_add = 'INSERT INTO po_detail (
									po_id,
									supplier_id,
									po_type_id,
									cat_id,
									po_detail_color,
									po_detail_no,
									po_detail_size,
									po_detail_box,
									po_detail_piece,
									po_detail_type_unit,
									po_detail_price,
									po_detail_total
								) VALUES (
									"'.$rs_po['po_id'].'",
									"'.$rs_po['supplier_id'].'",
									"'.$objArr[3].'",
									"'.$rs_cat['cat_id'].'",
									"'.$objArr[5].'",
									"'.$objArr[6].'",
									"'.$objArr[7].'",
									"'.$objArr[8].'",
									"'.$objArr[9].'",
									"'.$objArr[10].'",
									"'.$objArr[11].'",
									"'.$objArr[12].'"
								)';
			$query = $conn->query($sql_add);

			if($objArr[3]==1){
				$sql_add_fab = 'INSERT INTO fabric (
											po_id,
											receipt_id,
											supplier_id,
											cat_id,
											fabric_color,
											fabric_no,
											fabric_box,
											fabric_in_piece,
											fabric_type_unit,
											fabric_in_price,
											fabric_in_total,
											fabric_used,
											fabric_balance,
											fabric_date_create
										) VALUES (
											"'.$rs_po['po_id'].'",
											"'.$rs_po['receipt_id'].'",
											"'.$rs_po['supplier_id'].'",
											"'.$rs_cat['cat_id'].'",
											"'.$objArr[5].'",
											"'.$objArr[6].'",
											"'.$objArr[8].'",
											"'.$objArr[9].'",
											"'.$objArr[10].'",
											"'.$objArr[11].'",
											"'.$objArr[12].'",
											"'.$objArr[13].'",
											"'.$objArr[14].'",
											"'.$objArr[15].'"
										)';
				$query = $conn->query($sql_add_fab);
			}else if($objArr[3]==2){
				$sql_add_acc = 'INSERT INTO accessory (
											po_id,
											supplier_id,
											cat_id,
											accessory_no,
											accessory_size,
											accessory_color,
											accessory_in_price,
											accessory_in_total,
											accessory_piece,
											accessory_type_unit,
											accessory_balance,
											accessory_date_create
										) VALUES (
											"'.$rs_po['po_id'].'",
											"'.$rs_po['supplier_id'].'",
											"'.$rs_cat['cat_id'].'",
											"'.$objArr[6].'",
											"'.$objArr[7].'",
											"'.$objArr[5].'",
											"'.$objArr[11].'",
											"'.$objArr[12].'",
											"'.$objArr[9].'",
											"'.$objArr[10].'",
											"'.$objArr[9].'",
											"'.$objArr[13].'"
										)';
				$query = $conn->query($sql_add_acc);
				//echo $sql_add_acc.'<br>';
			}
		}

		@unlink('files/'.$_FILES["fileUploadDetail"]["name"]);
		echo $saved;
		echo '<meta http-equiv="refresh" content="0;URL=?vp='.base64_encode('po').'&op='.base64_encode('po_list').'">';
	}
}
?>