<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<style type="text/css">
.tbl_rq_form th{
	background-color: #333;
	text-align: center;
	color: #FFF;
	border: 1px #CCC solid; 
}

.tbl_rq_form td{
	
	text-align: center;
	border: 1px #CCC solid; 
}
#copy_content.modal-body{
	padding-top: 0px;
}
#remove_order.modal-body{

	border-top:1px solid #DDD;
}
.bg-red{
	color: #FFF;
	background-color: #F00;
}
</style>
<?php
//-----------Order code source here
$a_tmp_source = array();
$a_tmp_year = array();
$sql_addon = "SELECT rq_id,order_code,rq_date FROM tbl_rq_form WHERE rq_status='finish' AND enable=1 AND is_addon=0 ORDER BY order_code ASC; ";
$rs_addon = $conn->query($sql_addon);
while ($row_addon = $rs_addon->fetch_assoc()) {
	$tmp_year = substr($row_addon["rq_date"], 0,4);
	if( !in_array($tmp_year, $a_tmp_year) ){
		$a_tmp_year[] = $tmp_year;
	}
	$a_tmp_source["y".$tmp_year][] = $row_addon;
}
array_push($a_tmp_year,'2022');
//print_r($a_tmp_source);
?>
<h4 style="font-size:20px; font-weight: normal;">Material requisition form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<form  method="post">
					<div class="row">
						<div class="col-md-2" style="border-right:#CCC solid 1px;">
							<div class="form-group">
								<div>
								<input type="checkbox" id="is_add_on" onclick="tickAddOn();">
									Add-on | Year:
									<select id="select_year_source" onchange="return showAddonCodeSelect();" disabled>
										<?php
										$size_a_year = sizeof($a_tmp_year);
										for($i=($size_a_year-1);$i>=0;$i--){
											echo '<option value="'.$a_tmp_year[$i].'">'.$a_tmp_year[$i].'</option>';
										}
										?>
									</select>
								</div>
								<label class="col-form-label">Order Code</label>
								<select name="select_order" id="select_order" class="form-control" onchange="showOrderDetail();" style="width: 125px;" >
									<option value="none">=Select=</option>
								<?php
								$a_title_data = array();
								// $sql_order_code = "SELECT * FROM tbl_order_lkr_title WHERE to_producing='0' AND enable=1 ORDER BY order_title ASC; ";
								// $sql_order_code = "SELECT * FROM tbl_order_lkr_title WHERE enable=1 ORDER BY order_title ASC; ";
								$sql_order_code = "SELECT *
                                FROM tbl_order_lkr_title
                                WHERE enable = 1
                                  AND add_date >= '2022-01-01 00:00:00'
                                ORDER BY order_title ASC;
                                ";
								$rs_order_code = $conn->query($sql_order_code);
								while ($row_order_code = $rs_order_code->fetch_assoc()) {
									$a_title_data[] = $row_order_code;
									echo '<option ';
									if($row_order_code["have_order_form"]=="no"){
										echo 'class="bg-red"';
									}
									echo ' id="opt_order'.$row_order_code["order_lkr_title_id"].'" value="'.$row_order_code["order_title"].','.$row_order_code["folder_name"].','.$row_order_code["order_lkr_title_id"].','.$row_order_code["to_forecast"].'">'.$row_order_code["order_title"];
									if($row_order_code["to_forecast"]=="1"){
										echo ' *';
									}
									echo '</option>';
								}
								?>
								</select>
								<select name="select_order_addon" id="select_order_addon" class="form-control" style="width: 125px; background-color: #CFF;  display:none;" >
									
								</select>
								<i style="margin-top: -30px; padding-left: 10px; float: right; font-size: 24px; cursor: pointer;" class="fa fa-list" aria-hidden="true" data-toggle="modal" data-target="#removeOrderCodeModal"></i>
								<input type="hidden" name="use_order_code" id="use_order_code">
								<input type="hidden" id="use_order_lkr_title_id">
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="rq_date" class="form-control" value="<?php echo $strDate;?>" required readonly>
							</div>
							<div class="form-group" id="btn_copy_foc" style="display:none;">
								<hr>
								<input type="button" class="btn btn-warning" value="Copy from Forecast" data-toggle="modal" data-target="#copyFOCModal" onclick="showCopyInfo();">
							</div>
						</div>
						<div class="col-md-10" style="padding-left:0px; ">
							<div class="row">
								<div class="col-12" style="min-height: 200px;">
									<table class="tbl_rq_form" width="100%">
										<thead>
											<tr>
												<th>Material</th><th>Color</th><th>Box</th><th>No.</th><th>Balance</th><th>&nbsp;</th>
											</tr>
										</thead>
										<tbody id="tbo_content" >
											
										</tbody>
									</table>
								</div>
							</div>
							<hr>
							<div class="row" style="height: 50px;">
								<div class="col-3 text-center">
									Fabric: <select id="select_cat_id" onchange="catChange();">
										<option value="none">==Select==</option>
										<?php
										$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
										$rs_cat = $conn->query($sql_cat);
										while ($row_cat = $rs_cat->fetch_assoc()) {
											echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-4 text-center">
									Color:<br> <select id="select_color" onchange="colorChange();">
										<option value="none">=Select=</option>
									</select>
									<span id="select_color_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
								</div>
								<div class="col-3 text-center">
									Box/No.<br> <select id="select_fabric_id">
										<option value="none">=Select=</option>
									</select>
									<span id="select_fabric_id_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
								</div>
								<div class="col-2 text-center">
									<input type="hidden" name="fabric_id_list" id="fabric_id_list">
									<input class="btn btn-primary" type="button" id="btn_add" onclick="return addRQRow();" value=" Add ">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mt-2">
							<div class="form-group row">
								<button type="button" id="btn_submit_rq" class="btn btn-success" style="width: 100%; font-size: 18px;margin-top:35px;" onclick="return checkFormFill();">Submit Requisition form</button>
								
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div style="display: none;" id="order_code_source"><?php echo base64_encode(json_encode($a_tmp_source)); ?></div>

<!--Modal Remove Orders-->
<div class="modal fade" id="removeOrderCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:750px;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Removing Order code</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="remove_order">
				
				<form action="ajax/requisition/submit_remove_order.php" method="post" name="form2" target="hidden_submit_remove_order">
					<div class="row">
					<?php
					$tmp_order_name = "";
					$use_style = '';
					foreach($a_title_data as $key => $row_order_code2){
							if($tmp_order_name==$row_order_code2["order_title"]){
								$use_style = ' style="background-color:#FFEEBA;" ';
							}else{
								$use_style = '';
							}
						?>
						<div <?php echo $use_style; ?> class="col-3 " id="chk_order<?php echo $row_order_code2["order_lkr_title_id"]; ?>">
							<input type="checkbox" class="chk-order" name="order_lkr_title_id[]" value="<?php echo $row_order_code2["order_lkr_title_id"]; ?>"> <?php echo $row_order_code2["order_title"]; ?>
						</div>
						<?php
						$tmp_order_name = $row_order_code2["order_title"];
					}
					?>
					<br>
					</div>
					<hr>
					<div class="row">
						<div class="col-12">
							<center>
								<button id="btn_select_all" type="button" class="btn btn-primary mr-2" onclick="$('.chk-order').prop('checked',true); ">
									Select All
								</button>
								<button id="btn_deselect_all" type="button" class="btn btn-warning mr-2" onclick="$('.chk-order').prop('checked',false);">
									Unselect All
								</button>
								<button type="submit" class="btn btn-danger mr-2" onclick="return confirm('Confirm remove selected order code?');">Remove selected orders</button>
								
							</center>
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<iframe name="hidden_submit_remove_order" style="display: none;" width="0" height="0"></iframe>

<div class="row" id="show_order_detail" style="display: none;">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12" id="show_order_detail_top">

					</div>
				</div>
				<div class="row">
					<div class="col-12" id="show_order_detail_below">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal Copy-->
<div class="modal fade" id="copyFOCModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Copy data from Forecast</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="copy_content">
				
			</div>
			<div class="modal-footer text-center">
				<input type="button" class="btn btn-primary" style="width:100%" value="Add" onclick="addCopiedRow();">
			</div>
		</div>
	</div>
</div>
<!--Modal Copy-->

<script type="text/javascript">
function tickAddOn(){

	if($('#is_add_on').prop("checked")){
		$('#select_order_addon').show();
		$('#select_order_addon').select2();
		$('#select_order').hide();
		$('#select_order').val("none");
		$('#btn_copy_foc').hide();

		$('#select_year_source').attr("disabled",false);
		showAddonCodeSelect();

	}else{
		$('#select_order').show();
		$('#select_order_addon').hide();
		// Hide the Select2 dropdown
$('#select_order_addon').next('.select2-container').hide();
		$('#select_order_addon').val("none");

		$('#select_year_source').attr("disabled",true);
	}
}

function showAddonCodeSelect(){

	var select_year = $('#select_year_source').val();

	var obj_code = JSON.parse(window.atob($('#order_code_source').html()));

	var inner_select = '<option value="none">=Select=</option>';
	for(i=0; i<obj_code["y"+select_year].length; i++){
		inner_select += '<option value="'+obj_code["y"+select_year][i]["rq_id"]+'">'+obj_code["y"+select_year][i]["order_code"]+'</option>';
	}

	$('#select_order_addon').html(inner_select);

}

function addCopiedRow(){

	var s_fabric_copy = "";
	var c_index = 0;
	$('.fabric_select').each(function(){
		if(c_index>0){
			s_fabric_copy += ",";
		}
		s_fabric_copy += $(this).val();
		c_index++;
	});

	
	if($('#fabric_id_list').val()==""){
		$('#fabric_id_list').val(s_fabric_copy);

	}else{

		tmp_copy = s_fabric_copy.split(",");
		fabric_id_list = $('#fabric_id_list').val();
		tmp_list = fabric_id_list.split(",");

		tmp_new = "";

		var count_index = 0;
		for(var j=0;j<tmp_copy.length;j++){
			if( jQuery.inArray( tmp_copy[j], tmp_list ) == -1 ){
				if(count_index>0){
					tmp_new += ",";
				}
				tmp_new += tmp_copy[j];
				count_index++;
			}
		}
		
		s_fabric_copy = tmp_new;
		if(tmp_new!=""){
			$('#fabric_id_list').val($('#fabric_id_list').val()+","+tmp_new);
		}
	}

	if(s_fabric_copy!=""){

		tmp_row = s_fabric_copy.split(",");

		for(var i=0;i<tmp_row.length;i++){

			$.ajax({  
				type: "POST",
				dataType: "html",
				url:"ajax/requisition/get_balance.php" ,
				data:{
					"fabric_id":tmp_row[i]
				},
				success: function(resp){

					$('#tbo_content').append(resp);
					
				}  
			});	
		}
	}
	
}

function showCopyInfo(){
	var order_code = $('#use_order_code').val();

	$('#copy_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/copy_foc_info.php" ,
		data:{
			"order_code":order_code
		},
		success: function(resp){  
			
			$('#copy_content').html(resp);
		}  
	});
}

function removeOrderSuccess(id_list){

	window.location.reload();
	
}

function showOrderDetail(){
	var select_order = $('#select_order').val();

	var tmp_select = select_order.split(",");
	$('#use_order_code').val(tmp_select[0]);
	$('#use_order_lkr_title_id').val(tmp_select[2]);

	var to_forecast = tmp_select[3];
	if(to_forecast=="1"){
		$('#btn_copy_foc').show();
	}else{
		$('#btn_copy_foc').hide();
	}

	$('#show_order_detail_below').html('');
	$('#show_order_detail_top').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/forecast/get_order_detail.php" ,
		data:{
			"order_title":tmp_select[0],
			"folder_name":tmp_select[1]
		},
		success: function(resp){  
			
			$('#show_order_detail_top').html(resp);
			$('#show_order_detail').show();

		}  
	});
}

function showFile(order_lkr_id){
	var file_name = $('#td_id_'+order_lkr_id).html();
	var tmp_fname = file_name.split(".");
	var ext_name = tmp_fname[(tmp_fname.length-1)];
	var folder_name = $('#folder_name').val();

	var inner_below = '';
	var root_folder = '';
	var protocal = '';

	var other_path = '';
	if($('#td_file_type'+order_lkr_id).html()=="Other"){
		other_path = 'no_oform/';
	}

	if( (ext_name.toLowerCase()=="xlsx") || (ext_name.toLowerCase()=="xls") ){
		inner_below = '<br><iframe src="https://view.officeapps.live.com/op/embed.aspx?src=https://jogsports.com/lockerroom/files/'+other_path+folder_name+'/'+file_name+'" width="100%" height="700" frameborder="0"></iframe>';
	}else if(ext_name.toLowerCase()=="pdf"){
		if("localhost"=="<?php echo $_SERVER["SERVER_NAME"]; ?>"){
			root_folder = 'internal';
			protocal = 'http';
		}else{
			root_folder = 'lockerroom';
			protocal = 'https';
		}
		inner_below = '<br><iframe src="'+protocal+'://<?php echo $_SERVER["SERVER_NAME"]; ?>/'+root_folder+'/files/'+other_path+folder_name+'/'+file_name+'?<?php echo date("mdHis"); ?>" type="frame&amp;vlink=xx&amp;link=xx&amp;css=xxx&amp;bg=xx&amp;bgcolor=xx" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scorlling="yes" width="100%" height="700"></iframe>';
	}
	
	$('#show_order_detail_below').html(inner_below);

}

function catChange(){

	$('#select_color').html('');
	$('#select_fabric_id').html('');
	$('#select_color_loading').show();
	$('#select_fabric_id_loading').show();

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
			colorChange();
			
		}  
	});
	
}

function colorChange(){

	$('#select_fabric_id').html('');
	$('#select_fabric_id_loading').show();

	var cat_id = $('#select_cat_id').val();
	var color_name = $('#select_color').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_box_no_select.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name
		},
		success: function(resp){  
			
			$('#select_fabric_id_loading').hide();
			$('#select_fabric_id').html(resp);
			$('#select_fabric_id').select2();
			

		}  
	});
}

function addRQRow(){

	var fabric_id = $('#select_fabric_id').val();

	if(fabric_id=="none"){

		alert("Please select Box/No.");
		return false;
	}

	var fabric_id_list = $('#fabric_id_list').val();

	var tmp_id = fabric_id_list.split(",");
	for(var i=0; i<tmp_id.length; i++){
		if(tmp_id[i]==fabric_id){
			alert("Duplicate data!!");
			return false;
		}
	}

	$.ajax({  
		type: "POST",
		dataType: "html",
		url:"ajax/requisition/get_balance.php" ,
		data:{
			"fabric_id":fabric_id
		},
		success: function(resp){
			
			if(fabric_id_list!=""){
				fabric_id_list += ",";
			}
			fabric_id_list += fabric_id;
			$('#fabric_id_list').val(fabric_id_list);

			$('#tbo_content').append(resp);

		}  
	});
}

function checkFormFill(){

	if($('#fabric_id_list').val()==""){

		alert("Please Add material 1 row at least.");
		return false;
	}

	if($('#is_add_on').prop("checked")){
		if($('#select_order_addon').val()=="none"){

			alert("Please Select Order Code.");
			return false;
		}

		$('#btn_submit_rq').attr("disabled",true);

		var rq_id = $('#select_order_addon').val();

		$.ajax({  
			type: "POST",
			dataType: "json",
			url:"ajax/requisition/save_rq_form_addon.php" ,
			data:{
				"rq_id":rq_id,
				"fabric_id_list":$('#fabric_id_list').val()
			},
			success: function(resp){
				
				if(resp.result=="success"){
					window.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list'); ?>");
				}else{
					alert("Submit form fail...");
				}

			}  
		});
	}else{
		if($('#select_order').val()=="none"){

			alert("Please Select Order Code.");
			return false;
		}
		
		$('#btn_submit_rq').attr("disabled",true);

		$.ajax({  
			type: "POST",
			dataType: "json",
			url:"ajax/requisition/save_rq_form.php" ,
			data:{
				"order_lkr_title_id":$('#use_order_lkr_title_id').val(),
				"order_code":$('#use_order_code').val(),
				"fabric_id_list":$('#fabric_id_list').val()
			},
			success: function(resp){
				
				if(resp.result=="success"){
					window.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list'); ?>");
				}else{
					alert("Submit form fail...");
				}

			}  
		});
	}

	

}
$(document).ready(function() {
        $('#select_order').select2();
    });
    
$(document).ready(function() {
    $('#select_cat_id').select2();
});

$(document).ready(function() {
    $('#select_color').select2();
});

function removeMaterial(fabric_id,tmp_id){

	var fabric_id_list = $('#fabric_id_list').val();

	var tmp_list = fabric_id_list.split(",");

	if(tmp_list.length==1){
		fabric_id_list = fabric_id_list.replace(fabric_id, "");
	}else{
		fabric_id_list = fabric_id_list.replace(fabric_id+",", "");
		fabric_id_list = fabric_id_list.replace(","+fabric_id+",", ",");
		fabric_id_list = fabric_id_list.replace(","+fabric_id, "");
	}

	$('#fabric_id_list').val(fabric_id_list);
	
	$('#tr_row'+fabric_id+tmp_id).hide();
}
</script>