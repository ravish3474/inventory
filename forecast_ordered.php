<style type="text/css">
.tbl-f-ordered{
	width: 100%;
}
.tbl-f-ordered th{
	border:1px solid #FAA;
	background-color: #A77;
	color: #000;
	font-weight: bold;
	padding: 5px;
}
.tbl-f-ordered td{
	border:1px solid #FAA;
	background-color: #DDD;
	color: #000;
	padding: 5px;
}
.tbl-f-ordered tr:hover td{
	background-color: #EEE;
}
.table-show-info{
	width: 100%;
	background-color: #FFD;
}
.table-show-info th,td{
	padding: 5px;
	text-align: left;
}
.table-show-info th{
	width: 100px;
}
.table-show-info td{
	border-bottom: dashed 1px #555;
}
.table-add-fabric{
	width: 100%;
}
.table-add-fabric th{
	background-color: #975;
	color: #FFF;
	text-align: center;
	font-size: 16px;
	border:1px solid #ECA;
}
.table-add-fabric td{
	background-color: #FFF;
	color: #000;
	font-size: 16px;
	border:1px solid #ECA;
	text-align: center;
}
.table-add-fabric select,option{
	font-size: 14px;
}
.table-add-fabric button{
	padding: 5px;
	width: 75px;
}

.tbl_show_fc_purchase_list{
	width: 100%;
}
.tbl_show_fc_purchase_list th{
	background-color: #FFA;
	border: 1px solid #DD9;
	text-align: center;
	padding: 3px;
}
.tbl_show_fc_purchase_list td{
	border: 1px solid #DD9;
	text-align: center;
	padding: 3px;
	background-color: #E9E9E9;
}
.tbl_show_fc_purchase_list tr:hover td{
	background-color: #FFF;
}
.total_row td{
	background-color: #AAD;
}
.total_row:hover td{
	background-color: #CCF !important;
}
.fpu_noti{
	float: right;
	margin-top: -8px;
	margin-right: -21px;
	margin-left: 3px;
	margin-bottom: -5px;
	border: 2px solid #D00;
	background-color: #F00;
	border-radius: 20px;
	color: #FFF;
	padding: 3px 4px 4px 4px;
	font-size: 11px;
	font-weight: bold;
	height: 20px;
}
.chk_fpu{
	width: 18px;
	height: 18px;
}
.status_received{
	border: 5px double #55AADD;
	border-radius: 15px;
	background-color: #77CCFF;
	padding: 0px;
	margin: 0px;
	color: #005599;
	font-size: 12px;
	font-weight: bold;
}
</style>
<?php
$sql_fpu_num = "SELECT COUNT(*) AS fpu_num FROM tbl_f_purchase ";
$sql_fpu_num .= " WHERE tbl_f_purchase.mark_ordered=0 ";
$rs_fpu_num = $conn->query($sql_fpu_num);
$row_fpu_num = $rs_fpu_num->fetch_assoc();

$n_fpu_num = intval($row_fpu_num["fpu_num"]);
?>
<h4 style="font-size:20px; font-weight: normal;">Forecast Ordered</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12" style=" padding: 10px; ">
						<div style="float: left; display: none;">
							Year: 
							<select id="select_year" onchange="showForecastOrdered();">
								<?php
								for($y=intval(date("Y"));$y>=2020;$y--){
								?>
								<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
								<?php
								}
								?>
							</select>
							Month:
							<select id="select_month" onchange="showForecastOrdered();">
								<option value="all">=All=</option>
								<?php

								for($m=1;$m<=12;$m++){
									$tmp_month = "2020-".$m."-01";
									$show_month = date("F",strtotime($tmp_month));
								?>
								<option value="<?php echo $m; ?>"><?php echo $show_month; ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div style="float: left;">
							Show data for last 
							<select id="select_period" onchange="showForecastOrdered();">
								<option value="30">30</option>
								<option value="60" selected>60</option>
								<option value="90">90</option>
								<option value="180">180</option>
								<option value="365">365</option>
							</select>
							 Days.
						</div>
						<?php
						if( in_array(intval($_SESSION['employee_position_id']), array(4,99)) ){ //---Only Administrator and Purchase Level
						?>
						
						<button style="float: right;" class="btn btn-warning"  onclick="showAddPO();" data-toggle="modal" data-target="#addPOModal"><i class="fa fa-plus"></i>Manual add PO</button>
						<button style="float: right; margin-right: 10px;" class="btn btn-success"  onclick="selectToAddPO();" data-toggle="modal" data-target="#selectToAddPOModal">
							<i class="fa fa-plus"></i>Select from Forecast purchasing
							<?php if($n_fpu_num>0){ ?>
							<div class="fpu_noti"><?php echo $n_fpu_num; ?></div>
							<?php } ?>
						</button>
						<?php
						}
						?>
					</div>
					<div class="col-12 show-border" id="d_show_for_content">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!--Modal show Forecast purchase list-->
<div class="modal fade" id="selectToAddPOModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width: 600px;">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Forecast Purchasing</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 0px 15px 15px 15px;">
				<form id="fpu_list_form">
					<div id="show_fc_purchase_list">
					
					</div>
					<hr>
					<div class="row">
						<div class="col-6" style="text-align: left;">
							PO No. <input type="text" id="po_number_add" name="po_number_add" style="border: 1px solid #000;">
						</div>
						<div class="col-6" style="text-align: left;">
							PO Date: <input type="date" id="po_date_add" name="po_date_add">
						</div>
					</div>
				</form>
				<hr>
				<center>
					<button type="button" class="btn btn-success" onclick="return addPOBySelectedItem();">Add PO by selected items</button>
				</center>
			</div>
		</div>
	</div>
</div>

<!--Modal select material-->
<div class="modal fade" id="addPOModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" style="max-width: 700px;">
		<div class="modal-content">
			<div class="modal-header" style="padding-bottom: 0px;">
				<h5 class="modal-title">Purchase Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding-top: 5px;">
				<div id="d_first_step">
					<center>
						<table class="table" style="width: 60%;">
							<tr><th>PO No. </th><td><input style="width: 100%;" type="text" id="add_po_number" ></td></tr>
							<tr><th>PO Date </th><td><input style="width: 100%;" type="date" id="add_po_date" ></td></tr>
							<tr>
								<th>Supplier </th>
								<td>
									
									<select style="width: 100%;" id="add_supplier">
										<?php
										$sql_supp = "SELECT * FROM supplier WHERE supplier_id>0 AND supplier_name NOT LIKE 'STOCK-%' ORDER BY supplier_name ASC";
										$rs_supp = $conn->query($sql_supp);
										while($row_supp = $rs_supp->fetch_assoc()){
											echo '<option value="'.$row_supp["supplier_id"].'">'.$row_supp["supplier_name"].'</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr><td colspan="2" style="text-align: center;"><button class="btn btn-success" onclick="return saveAddPO();">Submit and Go to Add items</button></td></tr>
						</table>
					</center>
				</div>
				<div id="show_first_step_info" style="display: none; background-color: #FFD; padding: 0px 30px 15px 10px;">
					<table class="table-show-info">
						<tr><th>PO No. </th><td id="show_po_number"></td></tr>
						<tr><th>PO Date </th><td id="show_po_date"></td></tr>
						<tr><th>Supplier </th><td id="show_supplier"></td></tr>
					</table>
				</div>
				<div id="d_second_step" style="display: none; padding: 10px 0px 0px 0px;">
					<input type="hidden" id="hidden_for_id">
					<table class="table-add-fabric">
						<tr><th>#</th><th>Fabric</th><th>Color</th><th >QTY(KG)</th><th >Action</th></tr>
						<tbody id="tb_second_step">
							
						</tbody>
						<tbody id="tb_add_zone">
						<?php
						if( in_array(intval($_SESSION['employee_position_id']), array(4,99)) ){ //---Only Administrator and Purchase Level
						?>
						<tr>
							<td style="text-align: center; background-color: #CCF;"></td>
							<td style="text-align: center; background-color: #CCF;">
								<select id="cat_select" onchange="return catChange();">
								<?php
								$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
								$rs_cat = $conn->query($sql_cat);
								while ($row_cat = $rs_cat->fetch_assoc()) {
								?>
									<option value="<?php echo $row_cat["cat_id"]; ?>">
										<?php echo $row_cat["cat_name_en"]; ?>
									</option>
								<?php
								}
								?>
								</select>
							</td>
							<td style="text-align: center; background-color: #CCF;">
								<select id="color_select" onchange="return colorChange();">
									
								</select>
								<div id="d_new_color" style="display: none;">
									<input type="text" style="width: 100%;" id="new_color_name">
								</div>
							</td>
							<td style="text-align: center; width: 90px; background-color: #CCF;">
								<input style="width:100%;" type="number" id="add_qty">
							</td>
							<td style="text-align: center; background-color: #CCF;"><button id="btn_add_row" class="btn btn-success" onclick="return addRow();">Add</button></td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

showForecastOrdered();

function showForecastOrdered(){
	$('#d_show_for_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/get_for_content.php" ,
		data:{
			"s_period":$('#select_period').val()
		},
		success: function(resp){  
			$('#d_show_for_content').html(resp);
		}  
	});
}

function showAddPO(){
	$('#d_first_step').show();
	$('#show_first_step_info').hide();
	$('#d_second_step').hide();

	$('#tb_second_step').html('');
	$('#add_qty').val('');
}

function saveAddPO(){

	if( $('#add_po_number').val()=="" || $('#add_po_date').val()=="" ){
		alert("Please input all data");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/save_add_po.php" ,
		data:{
			"po_number":$('#add_po_number').val(),
			"po_date":$('#add_po_date').val(),
			"supplier_id":$('#add_supplier').val(),
			"supplier_name":$('#add_supplier option:selected').text()
		},
		success: function(resp){
			
			//alert(resp.result);
			if(resp.result=="success"){
				$('#show_po_number').html(resp.po_number);
				$('#show_po_date').html(resp.po_date);
				$('#show_supplier').html(resp.supplier);

				$('#hidden_for_id').val(resp.for_id);

				$('#d_first_step').fadeOut(500);
				setTimeout(function() {
					$('#show_first_step_info').fadeIn(500);

				}, 500);
				setTimeout(function() {
					$('#d_second_step').fadeIn(500);

				}, 500);
				showForecastOrdered();
				catChange();
			}else{
				alert(resp.msg);
			}

		}  
	});
}

function viewItem(for_id){

	catChange();

	$('#tb_second_step').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#btn_add_row').attr("disabled",true);

	$('#d_first_step').hide();
	$('#show_first_step_info').show();
	$('#d_second_step').show();
	$('#tb_add_zone').show();

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/get_po_item.php" ,
		data:{
			"for_id":for_id
		},
		success: function(resp){

			if(resp.result=="success"){
				$('#show_po_number').html(resp.po_number);
				$('#show_po_date').html(resp.po_date);
				$('#show_supplier').html(resp.supplier);

				$('#hidden_for_id').val(for_id);

				$('#tb_second_step').html(resp.inner_table);

				$('#add_qty').val('');
				$('#btn_add_row').removeAttr("disabled");
				
			}else{
				alert(resp.msg);
			}

		}  
	});
}

function viewItemInfo(for_id){

	$('#tb_second_step').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#btn_add_row').attr("disabled",true);

	$('#d_first_step').hide();
	$('#show_first_step_info').show();
	$('#d_second_step').show();

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/get_po_item.php" ,
		data:{
			"for_id":for_id
		},
		success: function(resp){

			if(resp.result=="success"){
				$('#show_po_number').html(resp.po_number);
				$('#show_po_date').html(resp.po_date);
				$('#show_supplier').html(resp.supplier);

				$('#hidden_for_id').val(for_id);

				$('#tb_second_step').html(resp.inner_table);

				$('#add_qty').val('');
				$('#btn_add_row').removeAttr("disabled");

				$('#tb_add_zone').hide();
				
			}else{
				alert(resp.msg);
			}

		}  
	});
}

function catChange(){

	var cat_id = $('#cat_select').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/forecast/get_color.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){

			$('#color_select').html(resp);
			$('#d_new_color').hide();
			$('#new_color_name').val('');
		}  
	});

}

function colorChange(){

	if($('#color_select').val()=="=new="){
		$('#d_new_color').show();
	}else{
		$('#d_new_color').hide();
	}

}

function addRow(){

	var for_id = $('#hidden_for_id').val();
	var qty = $('#add_qty').val();

	if(for_id==""){
		alert("Error : invalid parameter.");
		return false;
	}

	if(qty==""){
		alert("Please input QTY");
		return false;
	}

	var color_id = $('#color_select').val();
	if(color_id=="=new="){
		color_name = $('#new_color_name').val();
	}else{
		color_name = $('#color_select option:selected').text();
	}

	if(color_name==""){
		alert("Please input new Color name");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/save_po_item.php" ,
		data:{
			"for_id":for_id,
			"cat_id":$('#cat_select').val(),
			"color_id":color_id,
			"color_name":color_name,
			"qty":qty
		},
		success: function(resp){

			viewItem(for_id);
			showForecastOrdered();
			$('#add_qty').val('');

		}  
	});

}

function deleteRow(for_id){

	if(confirm("Confirm delete this row.")){

		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/forecast/delete_po.php" ,
			data:{
				"for_id":for_id
			},
			success: function(resp){

				viewItem(for_id);
				showForecastOrdered();

			}  
		});
	}

}

function deleteItem(for_id,for_item_id){

	if(confirm("Confirm deleting item?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/forecast/delete_po_item.php" ,
			data:{
				"for_id":for_id,
				"for_item_id":for_item_id
			},
			success: function(resp){
				if(resp.result=="success"){
					viewItem(for_id);
					showForecastOrdered();
					if(resp.all_re=="yes"){
						$('#addPOModal').modal("toggle");
					}
				}
			}  
		});
	}

}

function setReceiveItem(for_id,for_item_id){
	$.ajax({
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/receive_po_item.php" ,
		data:{
			"for_id":for_id,
			"for_item_id":for_item_id
		},
		success: function(resp){
			if(resp.result=="success"){
				viewItem(for_id);
				showForecastOrdered();
				if(resp.all_re=="yes"){
					$('#addPOModal').modal("toggle");
				}
			}
		}  
	});
}

function selectToAddPO(){

	$.ajax({
		type: "POST",  
		dataType: "html",
		url:"ajax/forecast/show_fc_purchase_list.php" ,
		success: function(resp){
			$('#show_fc_purchase_list').html(resp);
		}  
	});

}

function changeSuppGroup(){

	var supplier_id = $('#select_supp').val();
	$('.supp_group').hide();
	$('.supp_group_id'+supplier_id).show();
	$('.chk_fpu').prop("checked",false);

	var sum_supp = 0.0;
	$('.fpu_value'+supplier_id).each(function(){
		sum_supp += parseFloat($(this).html());
	});

	$('#show_total').html(sum_supp.toFixed(2));

	$('#po_number_add').val('');
	$('#po_date_add').val('');
	
}

function addPOBySelectedItem(){

	if( $('#po_number_add').val()=="" || $('#po_date_add').val()=="" ){
		alert("Please input PO No. and PO Date.");
		return false;
	}

	var n_select = 0;
	$('.chk_fpu').each(function(){
		if($(this).prop("checked")){
			n_select++;
		}
	});
	if(n_select==0){
		alert("Please select at least one.");
		return false;
	}

	$.ajax({
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/submit_f_ordered.php" ,
		data: $('#fpu_list_form').serialize(),
		success: function(resp){
			if(resp.result=="success"){
				location.reload();
			}else{
				alert(resp.msg);
			}
		}  
	});

}
</script>