<style type="text/css">
.head-input input{
	width: 180px;
}
.operate-zone{
	border-radius: 25px;
	border:solid 2px #595;
	background-color: #7A7;
	color: #FFF;
	padding: 10px;
}
.tbl-input-board th{
	background-color: #EEA;
	border:1px #996 solid;
	text-align: center;

}
.tbl-input-board td{
	background-color: #FFF;
	border:1px #996 solid;
}
#in_board_body input{
	width: 90px;
}
#in_board_body td{
	text-align: center;
	background-color: #DDD;
}

.tbl_show_fc_order_detail th{
	background-color: #7A7;
	border:1px #595 solid;
	text-align: center;

}
.tbl_show_fc_order_detail td{
	background-color: #FFF;
	border:1px #595 solid;
	text-align: center;
	padding: 5px;
}
.tbl_show_fc_order_detail input{
	width: 100px;
	text-align: right;
	border: 1px solid #000; 
}
.tbl_show_fc_order_detail input::-webkit-outer-spin-button, 
input::-webkit-inner-spin-button { margin-left: 10px; }

.head_set_recieve{
	background-color: #833 !important;
	color: #FFF;
}
.body_set_recieve{
	background-color: #FBB !important;
}
.show_row_received td{
	background-color: #DDD !important;
	color: #555;
}
</style>
<h4>Stock IN Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<form id="form1" target="hidden_frame" method="post">
				<div class="row head-input">
					<div class="col-2">PO No.<br>
						<!-- <input type="text" id="new_po_no" name="new_po_no" onkeyup="notCheckBefore();"> -->
						<?php
						$a_po_data = array();
						$s_po_no_option = "";
						$q_f_ordered = "SELECT * FROM tbl_f_ordered WHERE enable=1 AND po_status<>'All received' ORDER BY po_number ASC";
						$rs_f_ordered = $conn->query($q_f_ordered);
						while($row_f_ordered = $rs_f_ordered->fetch_assoc()){
							$a_po_data[($row_f_ordered['for_id'])] = $row_f_ordered;
							$s_po_no_option .= '<option value="'.$row_f_ordered['for_id'].'">'.$row_f_ordered['po_number'].'</option>';
						}
						?>
						<input type="hidden" id="json_f_ordered" value="<?php echo base64_encode(json_encode($a_po_data)); ?>">
						<select id="for_id" name="for_id" onchange="return purchaseOrderNumberChange();" style="width: 100%;">
							<option value="">==Select PO==</option>
							<?php
							echo $s_po_no_option;
							?>
						</select>
					</div>
					<div class="col-2">PO Date: <br>
						<input type="date" id="po_date" name="po_date" readonly style="border: 1px solid #000; background-color: #DDD; text-align: center;">
					</div>
					<div class="col-2">Supplier: <br>
						<input type="hidden" id="supplier_id" name="supplier_id" >
						<input type="text" id="supplier_name" name="supplier_name" readonly style="border: 1px solid #000; background-color: #DDD; text-align: center;">
					</div>
					<div class="col-2">INV No.<br>
						<input type="text" id="new_inv_no" name="new_inv_no" style="border: 1px solid #000; text-align: center;">
					</div>
					<div class="col-2">Stock Date: <br>
						<input type="date" id="stock_date" name="stock_date" value="<?php echo date("Y-m-d"); ?>" onkeyup="notCheckBefore();" onchange="notCheckBefore();" style="border: 1px solid #000; text-align: center;">
					</div>
					
					<div class="col-2">&nbsp;</div>
					<div class="col-12" id="d_po_item_list" style="display: none;">

					</div>
					<div class="col-12">&nbsp;</div>
					<div class="col-12">
						<div class="row operate-zone">

							<div class="col-3">Fabric: 
								<select id="select_cat_id" onchange="catChange();">
									<?php
									$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
									$rs_cat = $conn->query($sql_cat);
									while ($row_cat = $rs_cat->fetch_assoc()) {
										echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
									}
									?>
								</select>
								
								<span style="cursor: pointer;" data-toggle="modal" data-target="#addCatModal"><i class="fa fa-plus-circle"></i></span>
							</div>
							<div class="col-3">Color: 
								<select id="select_color">
									<option value="none">=Select=</option>
								</select>
								<input type="text" id="in_new_color" style="display: none; width: 120px;">
								<span >New<input type="checkbox" id="chk_new_color" style="width: 20px;" onclick="tickNewColor();"></span>
								<span id="select_color_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i></span>
							</div>
							<div class="col-2">Unit price: 
								<input type="number" id="in_uprice" style="width: 80px;">
							</div>
							<div class="col-3">Rolls: 
								<select id="select_num_roll">
									<?php 
									for($i=1;$i<=80;$i++){
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
									?>
								</select> 
								Start No. 
								<input type="number" id="in_no_start" style="width: 70px;">
							</div>
							<div class="col-1">
								<button type="button" class="btn btn-primary" onclick="addRolls();">Add</button>
								<input type="hidden" id="num_increment" value="0">
								<input type="hidden" id="num_row_active" value="0">
							</div>
						</div>
					</div>
				</div>
				<div class="row below-input">
					<div class="col-12">&nbsp;</div>
					<div class="col-12">
					<table width="100%" class="tbl-input-board">
						<thead>
							<tr>
								<th>Material</th><th>Color</th><th>Box</th><th>No.</th><th>Amount<br>(Kg.)</th><th>Unit price<br>(THB)</th><th>Total<br>(THB)</th><th>Action</th>
							</tr>
						</thead>
						<tbody id="in_board_body">
						</tbody>
					</table>
					</div>
				</div>
				<div class="row btn-zone">
					<div class="col-12">&nbsp;</div>
					<div class="col-5">
						<button type="button" class="btn btn-warning" style="width: 100%;" onclick="return checkBeforeSubmit();">Check</button>
					</div>
					<div class="col-7">
						<button id="btn_submit" type="button" class="btn btn-success" style="width: 100%;" onclick="submitForm();" disabled>Submit form</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>


<!--Modal Add new Cat-->
<div class="modal fade" id="addCatModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">New Fabric</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="text" id="in_new_cat" name="in_new_cat" style="width: 250px;">
				<button type="button" class="btn btn-success" onclick="return submitNewFabric();">Submit new Fabric</button>
			</div>
		</div>
	</div>
</div>

<iframe name="hidden_frame" style="display: none;"></iframe>

<script type="text/javascript">
	catChange();

function catChange(){

	$('#select_color').html('');
	$('#select_color_loading').show();

	var cat_id = $('#select_cat_id').val();
	
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_color_select.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){  
			
			$('#select_color_loading').hide();
			$('#select_color').html(resp);
			
		}  
	});
	
}

function addRolls(){
	var num_increment = $('#num_increment').val();
	var num_roll = $('#select_num_roll').val();

	var tmp_num = parseInt($('#num_row_active').val())+parseInt(num_roll);
	$('#num_row_active').val(tmp_num);

	var cat_id = $('#select_cat_id').val();
	var cat_name = $("#select_cat_id option:selected").html();

	var color_name = '';
	if($('#chk_new_color').prop("checked")){
		color_name = $('#in_new_color').val();
	}else{
		color_name = $('#select_color').val();
	}

	var in_no_start = 0;
	if($('#in_no_start').val()!=""){
		in_no_start = $('#in_no_start').val();
	}

	var uprice = $('#in_uprice').val();

	var inner_t = '';

	for(var i=0; i<num_roll;i++){
		num_increment++;
		inner_t += '<tr id="row_in_'+num_increment+'">';
		inner_t += '<td><input type="hidden" name="cat_id[]" value="'+cat_id+'">'+cat_name+'</td>';
		inner_t += '<td><input type="hidden" name="color_name[]" value="'+color_name+'">'+color_name+'</td>';
		inner_t += '<td><input type="text" name="box_name[]" value=""></td>';

		if($('#in_no_start').val()!=""){
			inner_t += '<td><input type="text" class="chk_blank" name="roll_no[]" id="roll_no_'+num_increment+'" value="'+in_no_start+'" onkeyup="notCheckBefore();"></td>';
			in_no_start++;
		}else{
			inner_t += '<td><input type="text" class="chk_blank" name="roll_no[]" id="roll_no_'+num_increment+'" value="" onkeyup="notCheckBefore();"></td>';
		}

		inner_t += '<td><input type="number" class="chk_blank" name="amount_in[]" id="amount_in_'+num_increment+'" onkeyup="calRow('+num_increment+');" step="0.01" onchange="calRow('+num_increment+');"></td>';
		inner_t += '<td><input type="number" class="chk_blank" name="uprice_in[]" id="uprice_in_'+num_increment+'" onkeyup="calRow('+num_increment+');" value="'+uprice+'" step="0.01" onchange="calRow('+num_increment+');"></td>';
		inner_t += '<td><input type="number" name="total_in[]" id="total_in_'+num_increment+'" value="" step="0.01" readonly></td>';
		inner_t += '<td><button class="btn btn-danger" onclick="removeRoll('+num_increment+');">Remove</button></td>';
		inner_t += '</tr>';
	}

	$('#num_increment').val(num_increment);

	$('#in_board_body').append(inner_t);
}

function addRollsBySelectPO(cat_id,cat_name,color_name,for_item_id){

	var num_increment = $('#num_increment').val();
	var num_roll = $('#num_roll'+for_item_id).val();

	var tmp_num = parseInt($('#num_row_active').val())+parseInt(num_roll);
	$('#num_row_active').val(tmp_num);

	var in_no_start = 0;
	in_no_start = parseInt($('#roll_start'+for_item_id).val());
	

	var uprice = $('#unit_price'+for_item_id).val();

	var inner_t = '';

	for(var i=0; i<num_roll;i++){
		num_increment++;
		inner_t += '<tr id="row_in_'+num_increment+'">';
		inner_t += '<td><input type="hidden" name="cat_id[]" value="'+cat_id+'">'+cat_name+'</td>';
		inner_t += '<td><input type="hidden" name="color_name[]" value="'+color_name+'">'+color_name+'</td>';
		inner_t += '<td><input type="text" name="box_name[]" value=""></td>';

		if($('#roll_start'+for_item_id).val()!=""){
			inner_t += '<td><input type="text" class="chk_blank" name="roll_no[]" id="roll_no_'+num_increment+'" value="'+in_no_start+'" onkeyup="notCheckBefore();"></td>';
			in_no_start++;
		}else{
			inner_t += '<td><input type="text" class="chk_blank" name="roll_no[]" id="roll_no_'+num_increment+'" value="" onkeyup="notCheckBefore();"></td>';
		}

		inner_t += '<td><input type="number" class="chk_blank" name="amount_in[]" id="amount_in_'+num_increment+'" onkeyup="calRow('+num_increment+');" step="0.01" onchange="calRow('+num_increment+');"></td>';
		inner_t += '<td><input type="number" class="chk_blank" name="uprice_in[]" id="uprice_in_'+num_increment+'" onkeyup="calRow('+num_increment+');" value="'+uprice+'" step="0.01" onchange="calRow('+num_increment+');"></td>';
		inner_t += '<td><input type="number" name="total_in[]" id="total_in_'+num_increment+'" value="" step="0.01" readonly></td>';
		inner_t += '<td><button class="btn btn-danger" onclick="removeRoll('+num_increment+');">Remove</button></td>';
		inner_t += '</tr>';
	}

	$('#num_increment').val(num_increment);

	$('#in_board_body').append(inner_t);
}

function removeRoll(row_no){
	$('#row_in_'+row_no).remove();
	var tmp_num = parseInt($('#num_row_active').val())-1;
	$('#num_row_active').val(tmp_num);
}

function tickNewColor(){
	if($('#chk_new_color').prop("checked")){
		$('#select_color').hide();
		$('#in_new_color').show();
	}else{
		$('#in_new_color').hide();
		$('#select_color').show();
	}
}


function calRow(row_no){
	var amount_in = $('#amount_in_'+row_no).val();
	var uprice_in = $('#uprice_in_'+row_no).val();

	var total_in = amount_in*uprice_in;

	$('#total_in_'+row_no).val(total_in.toFixed(2));

	notCheckBefore();
}

function checkBeforeSubmit(){

	if($('#new_po_no').val()==""){
		alert("Please input PO No.");
		return false;
	}
	
	if($('#new_po_date').val()==""){
		alert("Please input PO Date");
		return false;
	}

	if($('#stock_date').val()==""){
		alert("Please input Stock Date");
		return false;
	}

	if($('#supplier_id').val()=="=none="){
		alert("Please select Supplier");
		return false;
	}

	if($('#num_row_active').val()==0){
		alert("Please input at least 1 row.");
		return false;
	}
	
	var flag_return = 0;
	$('.chk_blank').each(function(){
		if($(this).val()==""){
			alert("Please fill all No., Amount and Unit Price.");
			flag_return = 1;
			return false;
		}
	});

	if(flag_return==0){

		$('#form1').attr("action","ajax/stock_in/check_stock_in.php");
		$('#form1').submit();
	}

}

function notCheckBefore(){
	$('#btn_submit').attr("disabled",true);
}

function checkNotPass(){
	alert("Duplicated No. with not empty roll.");
	notCheckBefore();
}

function checkPass(){
	alert("Checking PASS!");
	$('#btn_submit').removeAttr("disabled");
}

function submitForm(){

	$('#btn_submit').attr("disabled",true);

	$('#form1').attr("action","ajax/stock_in/submit_stock_in.php");
	$('#form1').submit();
}

function submitSuccess(){
	window.parent.location.replace("<?php echo $main_path."?vp=".base64_encode('po')."&op=".base64_encode('po_list'); ?>");
}

function submitFail(msg="Unknown ERROR!!"){
	alert(msg);
}

function submitNewFabric(){

	var new_cat = $('#in_new_cat').val();

	if(new_cat==""){
		alert("Please input data.");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/stock_in/save_new_cat.php" ,
		data:{
			"cat_name":window.btoa(new_cat)
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				$('#select_cat_id').html(resp.inner_html);

				alert("Add success");
				$('#in_new_cat').val('');

				$('#addCatModal').modal("toggle");

			}else{
				alert(resp.msg);
				return false;
			}

		}
	});
}

function purchaseOrderNumberChange(){

	if($('#for_id').val()==""){

		$('#d_po_item_list').hide();
		$('#d_po_item_list').html('');

		$('#po_date').val('');
		$('#supplier_id').val('');
		$('#supplier_name').val('');

	}else{

		$('#d_po_item_list').show();
		$('#d_po_item_list').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

		var for_id = parseInt($('#for_id').val());
		var obj_po = $.parseJSON(window.atob($('#json_f_ordered').val()));

		$('#po_date').val(obj_po[for_id].po_date);
		$('#supplier_id').val(obj_po[for_id].supplier_id);
		$('#supplier_name').val(obj_po[for_id].supplier);
		
		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/stock_in/show_f_orderd_item.php" ,
			data:{
				"for_id":for_id
			},
			success: function(resp){  
				
				$('#d_po_item_list').html(resp);

			}
		});
	}

}

function setReceive(for_item_id){

	var max_receive = parseFloat($('#receive_val'+for_item_id).attr("max"));

	var val_receive = 0.0;
	if( $('#receive_val'+for_item_id).val()!="" ){
		val_receive = parseFloat($('#receive_val'+for_item_id).val());
	}

	if( (val_receive<=0.0) || (val_receive>max_receive) ){
		alert("Receive amount is zero or more than the Ordered. Please check.");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/stock_in/set_receive.php" ,
		data:{
			"for_item_id":for_item_id,
			"val_receive":val_receive
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				purchaseOrderNumberChange();
			}else{
				alert(resp.msg);
			}

		}
	});

}
</script>