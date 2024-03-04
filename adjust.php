<?php
if( in_array("adjust",$a_allow) ){
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<style type="text/css">
.container-fluid {
	padding-left: 0px;
}
.form-control{
	font-size: 12px;
}
.badge {
	font-size: 11px;
}


/* ----== #fabric_view ==----*/
.fabric-board{
	padding:0px;
}
.fabric-box h6{
	background-color: #63ab31;
	color: #000;
	height: 20px;
	font-size: 14px;
	padding-top: 2px;
}
.fabric-box{
	font-size: 13px;
	height: 80px;
	background-color: #c3f5f6;
	margin:3px;
	border-radius: 5px;
	border: 2px solid #AAA; 
	cursor: pointer;
}
.fabric-box font{
	font-weight: bold;
}
.fabric-box:hover h6{
	background-color: #c3f5f6;

	-webkit-animation-name: fabric_h_animation; /* Safari 4.0 - 8.0 */
  	-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  	animation-name: fabric_h_animation;
  	animation-duration: 2s;
}
.fabric-box:hover{
	background-color: #63ab31;
	border-color: #CCC;

	-webkit-animation-name: fabric_animation; /* Safari 4.0 - 8.0 */
  	-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  	animation-name: fabric_animation;
  	animation-duration: 2s;
}

.fabric-box:hover .num-bal{
	color: #000;
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes fabric_animation {
  from  {background-color:#c3f5f6; }
  to {background-color:#63ab31; }
}

/* Standard syntax */
@keyframes fabric_animation {
  from  {background-color:#c3f5f6; }
  to {background-color:#63ab31; }
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes fabric_h_animation {
  from  {background-color:#63ab31; }
  to {background-color:#c3f5f6; }
}

/* Standard syntax */
@keyframes fabric_h_animation {
  from  {background-color:#63ab31; }
  to {background-color:#c3f5f6; }
}
/* ----== #fabric_view ==----*/

/* ----== #colors_view ==----*/
.colors-board{
	padding:0px;
}
.colors-box h6{
	background-color: #f49502;
	color: #000;
	height: 20px;
	font-size: 14px;
	padding-top: 2px;
}
.colors-box{
	font-size: 13px;
	height: 110px;
	background-color: #f6f69b;
	margin:3px;
	border-radius: 5px;
	border: 2px solid #AAA; 
	cursor: pointer;
}
.colors-box font{
	font-weight: bold;
}

.colors-box:hover{
	border-color: #CCC;
}

.colors-box:hover .num-total{
	color: #000;
}

/* ----== #colors_view ==----*/

/* ----== #rolls_view ==----*/

.tbl-rolls thead{
	background-color: #cf0495;
}
.tbl-rolls th{
	
	color:#FFF;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
}
.tbl-rolls tbody{
	background-color: #DDDDDD;
}
.tbl-rolls td{
	color:#222222;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
	height: 45px;
}
.row-rolls:hover{
	background-color: #EEE;
}

.tbl-rolls input{
	width: 100px;
	text-align: center;
}
.btn-save{
	font-size: 14px;
	line-height: 14px;
}
/* ----== #rolls_view ==----*/

/* ----== #log_view ==----*/
.tbl-log th{
	background-color: #06f;
	color: #FFF;
	font-size: 14px;
	font-weight: bold;
	padding: 3px;
	text-align: center;
	border:1px solid #59f;
}
.tbl-log tr{
	background-color: #CCC;
}
.tbl-log tr:hover{
	background-color: #EEE;
}
.tbl-log td{
	border:1px solid #59f;
	font-size: 14px;
	padding: 3px;
	text-align: center;
}
.log-title{
	font-weight: bold;
	font-size: 15px;
}
/* ----== #log_view ==----*/

#new_roll_content .row{
	padding: 5px;
}
#edit_supp_content .row{
	padding: 5px;
}
</style>
<h4 style="font-size:20px; font-weight: normal;">Adjust Fabric Stock</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row" id="fabric_view">
				</div>
				<div class="row" id="colors_view" style="display: none;">
				</div>
				<div class="row" id="rolls_view" style="display: none;">
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal Order Detail-->
<div class="modal fade" id="showLogModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_log_content" align="center">
				
			</div>
		</div>
	</div>
</div>

<?php
$sql_cat = "SELECT cat_id,cat_name_en ";
$sql_cat .= "FROM cat ";
$sql_cat .= "WHERE enable=1 ";
$sql_cat .= " AND type_id=1 ";
$sql_cat .= "ORDER BY cat_name_en ASC ";

$rs_cat = $conn->query($sql_cat);
?>
<!--Modal new roll-->
<div class="modal fade" id="newRollModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">New fabric roll</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="new_roll_content" align="center">
				<div class="row">
					<div class="col-6 text-left">Supplier: </div>
					<div class="col-6 text-left">
						<select id="select_new_supplier" style="width: 100%;">
							<option value="=none=">== Select Supplier ==</option>
							<?php
							$sql_supplier = "SELECT * FROM supplier WHERE supplier_name NOT LIKE 'STOCK-%' ORDER BY supplier_name ASC; ";
							$rs_supplier = $conn->query($sql_supplier);
							while($row_supplier = $rs_supplier->fetch_assoc()){
								echo '<option value="'.$row_supplier["supplier_id"].'">'.$row_supplier["supplier_name"].'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">Fabric: </div>
					<div class="col-6 text-left" id="d_cat_name_en">
						<select id="select_new_cat_name_en" onchange="return newRollChangeCat();" style="width: 100%;">
							<option value="=none=">== Select Fabric ==</option>
							<?php
							while($row_cat = $rs_cat->fetch_assoc()){
								echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
							}
							?>
							<option value="=new=">== New ==</option>
						</select>
						<input type="text" id="input_new_cat_name_en" style="display: none;">
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">Color: </div>
					<div class="col-6 text-left" >
						<div id="d_color_name"><i>Please select Fabric before.</i></div>
						<input type="text" id="input_new_color" style="display: none; width: 100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">Box: </div>
					<div class="col-6 text-left">
						<input type="text" id="new_fabric_box" style="width: 100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">No. </div>
					<div class="col-6 text-left">
						<input type="text" id="new_fabric_no" style="width: 100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">IN (Kg.): </div>
					<div class="col-6 text-left">
						<input type="number" id="new_fabric_in" style="width: 100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-6 text-left">Unit price: </div>
					<div class="col-6 text-left">
						<input type="number" id="new_fabric_u_price" style="width: 100%;">
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center">
						<hr>
						<input type="hidden" id="hide_show_level">
						<div class="btn btn-success" onclick="submitNewFabric();" style="width: 50%;">Submit NEW roll</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal edit roll (Supplier Name)-->
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document" style="width: 400px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editing Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="edit_supp_content" align="center">
				
			</div>
		</div>
	</div>
</div>

<!--Modal Remove Fabric-->
<div class="modal fade" id="removeFabricModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form action="ajax/adjust/submit_remove_roll.php" method="post" target="hidden_frame">
			<div class="modal-header" style="padding:5px 15px;">
				<h5 class="modal-title h5-modal-input-title" id="modal_input_title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding:5px 15px; background-color: #FFF;">
				Remark : <br>
				<textarea name="remark_remove" id="remark_remove" style="width: 100%; height: 100px;"></textarea>
			</div>
			<div class="modal-footer" style="padding:5px 15px;">
				<input type="hidden" name="r_fabric_id" id="r_fabric_id" >
				<input type="hidden" name="r_old_balance" id="r_old_balance" >
				
				<input class="btn btn-danger" id="btn_manage_submit" type="submit" value="Submit remove" onclick="return checkRemark();">
			</div>
			</form>
		</div>
	</div>
</div>
<iframe name="hidden_frame" style="display: none;"></iframe>



<script type="text/javascript">
showFabricView();

function showFabricView(){

	$('#fabric_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#colors_view').hide();
	$('#rolls_view').hide();
	$('#fabric_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_fabric_view.php" ,
		success: function(resp){
			$('#fabric_view').html(resp);

		}
	});

}

function showColorsView(cat_id,cat_name_en){

	$('#colors_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#fabric_view').hide();
	$('#rolls_view').hide();
	$('#colors_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_colors_view.php" ,
		data:{
			"cat_id":cat_id,
			"cat_name_en":cat_name_en
		},
		success: function(resp){
			$('#colors_view').html(resp);
		}  
	});

}

function showRollsView(cat_id,color_name,cat_name_en,color_code){

	$('#rolls_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#fabric_view').hide();
	$('#colors_view').hide();
	$('#rolls_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_rolls_view.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name,
			"cat_name_en":cat_name_en,
			"color_code":color_code
		},
		success: function(resp){
			$('#rolls_view').html(resp);
		}  
	});

}

function showSaveButton(fabric_id){

	if( parseFloat($('#in_bal'+fabric_id).val()) == parseFloat($('#old_bal'+fabric_id).val()) ){
		$('#btn_save'+fabric_id).hide();
	}else{
		$('#btn_save'+fabric_id).show();
	}

}

function saveBalance(fabric_id){

	if(confirm("Adjust balance from "+$('#old_bal'+fabric_id).val()+" to "+$('#in_bal'+fabric_id).val())){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/adjust/save_new_balance.php" ,
			data:{
				"fabric_id":fabric_id,
				"old_bal":$('#old_bal'+fabric_id).val(),
				"new_bal":$('#in_bal'+fabric_id).val(),
				"unit_price":$('#td_unit_price'+fabric_id).html(),
				"old_in":$('#td_old_in'+fabric_id).html()
			},
			success: function(resp){
				if(resp.result=="success"){

					if($('#in_bal'+fabric_id).val()==0){
						showRollsView($('#tmp_cat_id').val(),$('#tmp_color_name').val(),$('#tmp_cat_name_en').val());
					}else{
						$('#old_bal'+fabric_id).val($('#in_bal'+fabric_id).val());
						$('#td_amount'+fabric_id).html(resp.new_amount);
						$('#td_old_in'+fabric_id).html(resp.new_in);
						showSaveButton(fabric_id);
						$('#adj_log'+fabric_id).show();
					}
				}
			}  
		});
	}

}

function editUnitPrice(fabric_id){
	
}

function showAdjustLog(fabric_id){
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_log_view.php" ,
		data:{
			"fabric_id":fabric_id,
			"log_box":$('#td_box'+fabric_id).html(),
			"log_no":$('#td_no'+fabric_id).html()
		},
		success: function(resp){
			$('#show_log_content').html(resp);
		}  
	});
}

function showNewRollForm(show_level){

	if(show_level=="fabric"){
		$('#select_new_cat_name_en').val("=none=");
		newRollChangeCat();
	}else if(show_level=="color"){
		$('#select_new_cat_name_en').val($('#hide_cat_id').val());
		newRollChangeCat();
	}else if(show_level=="roll"){
		$('#select_new_cat_name_en').val($('#hide_cat_id').val());
		newRollChangeCat();
		setTimeout(function() {
			$('#select_new_color_name').val($('#hide_color_name').val());
		}, 1000);
		
	}

	$('#new_fabric_box').val('');
	$('#new_fabric_no').val('');
	$('#new_fabric_in').val('');
	$('#new_fabric_u_price').val('');

	$('#input_new_cat_name_en').val('');
	$('#input_new_color').val('');

	$('#hide_show_level').val(show_level);

	/*$('#d_cat_name_en').html($('#new_cat_name_en').val());
	$('#d_color_name').html($('#new_color_name').val());*/

}

function submitNewFabric(){

	/*var cat_id = $('#new_cat_id').val();
	var color_name = $('#new_color_name2').val();*/
	
	if( $('#select_new_supplier').val()=="=none=" ){
		alert("Please select Supplier.");
		return false;
	}

	if( $('#select_new_cat_name_en').val()=="=none=" ){
		alert("Please select Fabric before.");
		return false;
	}else if( $('#select_new_cat_name_en').val()=="=new=" && $('#input_new_cat_name_en').val()=="" ){
		alert("Please input new Fabric name.");
		return false;
	}

	if( $('#select_new_color_name').val()=="=new=" && $('#input_new_color').val()=="" ){
		alert("Please input new Color name.");
		return false;
	}

	var supplier_id = $('#select_new_supplier').val();
	var cat_id = $('#select_new_cat_name_en').val();
	var color_name = $('#select_new_color_name').val();
	var new_cat_name = $('#input_new_cat_name_en').val();
	var new_color_name = $('#input_new_color').val();

	if($('#new_fabric_no').val()==""){

		alert("Please input No.");
		return false;
	}
	if($('#new_fabric_in').val()==""){

		alert("Please input IN (Kg.)");
		return false;
	}
	if($('#new_fabric_u_price').val()==""){

		alert("Please input Unit price");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/adjust/save_new_roll.php" ,
		data:{
			"supplier_id":supplier_id,
			"cat_id":cat_id,
			"color_name":window.btoa(color_name),
			"new_cat_name":window.btoa(new_cat_name),
			"new_color_name":window.btoa(new_color_name),
			"fabric_no":$('#new_fabric_no').val(),
			"fabric_box":$('#new_fabric_box').val(),
			"fabric_in":$('#new_fabric_in').val(),
			"fabric_u_price":$('#new_fabric_u_price').val()
		},
		success: function(resp){
			if(resp.result=="success"){
				if($('#hide_show_level').val()=="fabric"){
					location.reload();
				}else if($('#hide_show_level').val()=="color"){
					showColorsView(cat_id,window.btoa($('#new_cat_name_en').val()));
				}else if($('#hide_show_level').val()=="roll"){
					showRollsView(cat_id,window.btoa(color_name),$('#new_cat_name_en2').val(),$('#new_color_code2').val());
				}
				
				$('#newRollModal').modal("toggle");
			}else{
				alert(resp.msg);
			}
		}  
	});

}

function returnFabric(fabric_id){

	$('#r_fabric_id').val(fabric_id);
	$('#r_old_balance').val($('#td_old_in'+fabric_id).html());
	$('#remark_remove').val('');
	
	var s_title = "Box: "+$('#td_box'+fabric_id).html()+" No. "+$('#td_no'+fabric_id).html();

	$('#modal_input_title').html(s_title);

}

function checkRemark(){
	if($('#remark_remove').val()==""){
		alert("Please remark!");
		return false;
	}
}

function successRemove(){
	$('#removeFabricModal').modal("toggle");
	showRollsView($('#new_cat_id').val(),$('#new_color_name2').val(),$('#new_cat_name_en2').val(),$('#new_color_code2').val());
}

function newRollChangeCat(){
	if($('#select_new_cat_name_en').val()=="=new="){
		$('#input_new_cat_name_en').show();
		$('#d_color_name').html('<select id="select_new_color_name"><option value="=new=">== New Color ==</option></select>');
		$('#input_new_color').show();
	}else if($('#select_new_cat_name_en').val()=="=none="){
		$('#input_new_cat_name_en').hide().val('');

		$('#d_color_name').html('<i>Please select Fabric before.</i>');
		$('#input_new_color').hide();
	}else{
		$('#input_new_cat_name_en').hide().val('');
		$('#input_new_color').hide();
		$('#d_color_name').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/adjust/get_color.php" ,
			data:{
				"cat_id":$('#select_new_cat_name_en').val()
			},
			success: function(resp){
				$('#d_color_name').html(resp);
			} 
		});
		
	}
}

function newRollChangeColor(){
	
	if( $('#select_new_color_name').val()=="=new=" ){
		$('#input_new_color').show();
	}else{
		$('#input_new_color').hide();
	}
	
}

function checkAllRows(){
	if($('#chk_all_rows').prop("checked")){
		$('.normal_roll_row').prop("checked",true);
	}else{
		$('.normal_roll_row').prop("checked",false);
	}
}

function editUnitPrice(){

	var edit_price = prompt("Change prices to : ");

	if( edit_price=="" || edit_price=="." ){
		alert("Please input price.");
		return false;
	}

	if(edit_price==null){
		return false;
	}

	var reg = /^\d*\.?\d*$/
	if(!reg.test(edit_price)){
		alert("Please input number only.");
		return false;
	}

	var fabric_id_list = "";
	$('.normal_roll_row').each(function(){

		if($(this).prop("checked")){
			if(fabric_id_list != ""){
				fabric_id_list += ",";
			}
			fabric_id_list += $(this).val();
		}
	});

	if(fabric_id_list==""){
		alert("Please select row at least one.");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/adjust/edit_price_submit.php" ,
		data:{
			"edit_price":edit_price,
			"fabric_id_list":fabric_id_list
		},
		success: function(resp){
			if(resp.result=="success"){

				var a_fabric_id = fabric_id_list.split(",");
				for(var i=0; i<a_fabric_id.length; i++){
					$('#sp_inner_uprice'+a_fabric_id[i]).html(edit_price);

					var old_bal = $('#old_bal'+a_fabric_id[i]).val();
					var new_price = parseFloat(edit_price);
					var row_amount = 0.0;
					row_amount = (old_bal*new_price).toFixed(2);

					$('#td_amount'+a_fabric_id[i]).html(row_amount);
				}

			}else{
				alert(resp.msg);
			}
		}
	});

}

function editSupplier(fabric_id){

	$('#edit_supp_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_edit_supplier_view.php" ,
		data:{
			"fabric_id":fabric_id
		},
		success: function(resp){
			$('#edit_supp_content').html(resp);
		} 
	});

}

function submitEditSupplier(fabric_id){

	var supplier_id = $('#select_edit_supplier').val();
	var supplier_name = $('#select_edit_supplier option:selected').text();

	if(supplier_id=="=none="){
		alert("Please select Supplier.");
		return false;
	}else{
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/adjust/submit_edit_supplier.php" ,
			data:{
				"fabric_id":fabric_id,
				"supplier_id":supplier_id
			},
			success: function(resp){
				if(resp.result=="success"){
					$('#sp_supp_name'+fabric_id).html(supplier_name);
					$('#editSupplierModal').modal("toggle");
				}else{
					alert(resp.msg);
				}
			} 
		});
	}
}
</script>
<?php
}
?>