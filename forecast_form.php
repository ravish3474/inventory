<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	jQuery(function($) {
        /*jQuery('body').on('change','#type_id',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=type',
                'cache':false,
                'data':{type_id:jQuery(this).val()},
                'success':function(html){
                    jQuery("#cat_id").html(html);
                }
            });
            return false;
        });*/
        jQuery('body').on('change','#cat_id',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=color_id',
                'cache':false,
                'data': {
					"type_id":$('#type_id').val(),
					"cat_id":$('#cat_id').val()
				},
                'success':function(html){
                    jQuery("#select_color").html(html);
                }
            });
            return false;
        });

        jQuery('body').on('change','#select_color',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=balance',
                'cache':false,
                'data': {
					"type_id":$('#type_id').val(),
					"cat_id":$('#cat_id').val(),
					"color":$('#select_color option:selected').html()
				},
                'success':function(html){

                    jQuery("#balance").html(html);
                }
            });
            return false;
        });
    });
</script>

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
.searh-in-lkr div{
	padding: 0px 5px 5px 5px;
}
.bg-red{
	color: #FFF;
	background-color: #F00;
}
</style>
<?php
$strDate = date('Y-m-d');

if(isset($_GET['used_code'])){
	$used_code=base64_decode($_GET['used_code']);
}else{
	$used_code = 'FC-'.date('ymdHi');
}
?>
<h4 style="font-size:20px; font-weight: normal;">Forecast Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<form id="form_save_forecast" action="ajax/forecast/save_forecast.php" method="post" target="hidden_frame">
					<div class="row">
						<div class="col-md-2">
						
							<div class="form-group">
								<label class="col-form-label">Forecast Code</label>
								<input type="text" name="forecast_code" class="form-control" value="<?php echo $used_code;?>" readonly>
								<input type="hidden" id="count_row" value="1">
								<input type="hidden" id="real_num_row" value="0">
							</div>
							<div class="form-group">
								<label class="col-form-label">Order Code</label>
								
								<select name="select_order" id="select_order" class="form-control" onchange="showOrderDetail();" style="width: 125px;">
									<option value="none">=Select=</option>
								<?php
								$a_title_data = array();
								$sql_order_code = "SELECT * FROM tbl_order_lkr_title WHERE to_forecast='0' AND enable=1 ORDER BY order_title ASC; ";
								$rs_order_code = $conn->query($sql_order_code);
								while ($row_order_code = $rs_order_code->fetch_assoc()) {
									$a_title_data[] = $row_order_code;
									echo '<option ';
									if($row_order_code["have_order_form"]=="no"){
										echo 'class="bg-red"';
									}
									echo ' id="opt_order'.$row_order_code["order_lkr_title_id"].'" value="'.$row_order_code["order_title"].','.$row_order_code["folder_name"].'">'.$row_order_code["order_title"].'</option>';
								}
								?>
								</select>
								<i style="margin-top: -30px; padding-left: 10px; float: right; font-size: 24px; cursor: pointer;" class="fa fa-list" aria-hidden="true" data-toggle="modal" data-target="#deleteOrderCodeModal" onclick="resetSearchForm();"></i>
								<input type="hidden" name="forecast_order" id="forecast_order">
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="date" name="forecast_date" id="forecast_date" class="form-control" value="<?php echo $strDate;?>" required>
							</div>
							
						
						</div>
						<div class="col-md-10">
						<?php
						//if(isset($_GET['used_code'])){
						?>
							
								<table width="100%" class="table-bordered">
									<thead>
										<tr class="bg-dark text-white text-center">
											<th>Type</th>
											<th>Materials</th>
											<th>Color</th>
											<th>Balance</th>
											<th>Forecast</th>
											<th>Del</th>
										</tr>
									</thead>
									<tbody id="used_detail">
										
									</tbody>
								</table>
								<input type="hidden" name="used_code" value="<?php echo $used_code;?>">
								
							
							<hr>
						<?php	
						
						//}
						?>
						
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										Type
										<select class="form-control" name="type_id" id="type_id">
											<option value="1">Fabrics</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Materials
										<select class="form-control" name="cat_id" id="cat_id">
											<option value="">=Select=</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Color
										<select class="form-control" name="select_color" id="select_color">
											<option value="">=Select=</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Balance
										<span id="balance"></span>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Forecast
										<input type="number" name="used" id="used" min="0" style="width: 100px;"> Kg.
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<br>
										<button type="button" class="btn btn-secondary" onclick="addForecastRow();">Add</button>
										<input type="hidden" name="used_code" value="<?php echo $used_code;?>">
									</div>
								</div>
							</div>
						
						</div>
					
					</div>
					<div class="row">
						<div class="col-12">
							<button id="btn_save_forecast" type="submit" class="btn btn-success" style="width: 100%;" onclick="return checkFormFill();">Save forecast</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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

<iframe name="hidden_frame" style="display: none;" width="0" height="0"></iframe>

<!--Modal Remove Orders-->
<div class="modal fade" id="deleteOrderCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">You can remove Order Code that no need to Forecast
					<i class="fa fa-get-pocket" aria-hidden="true" style="cursor: pointer;" onclick="showSearchZone();"></i>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="remove_order">
				<div id="d_search_zone" class="searh-in-lkr row" style="display:none;">
					<div class="col-4">Search in RQ form: </div>
					<div class="col-4">
						<input type="text" id="s_in_rq">
						<i class="fa fa-search" aria-hidden="true" style="cursor: pointer;" onclick="return searchInRQ();"></i>
					</div>
					<div class="col-4" id="d_result_search_rq">
						
					</div>

					<div class="col-4">Search in Locker room: </div>
					<div class="col-4">
						<input type="text" id="s_in_lkr">
						<i class="fa fa-search" aria-hidden="true" style="cursor: pointer;" onclick="return searchInLKR();"></i>
					</div>
					<div class="col-4" id="d_result_search_lkr">
						
					</div>
				</div>
				<hr>
				<form action="ajax/forecast/submit_remove_order.php" method="post" name="form2" target="hidden_submit_remove_order">
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
								<input type="hidden" name="from_where" value="forecast">
							</center>
						</div>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

<iframe name="hidden_submit_remove_order" style="display: none;" width="0" height="0"></iframe>

<script type="text/javascript">
getMaterials();

function getMaterials(){
	$.ajax({
        type:'POST',
        dataType: "html",
        url:'get_type.php?op=type',
        cache:false,
        data:{type_id:$("#type_id").val()},
        success:function(resp){
            $("#cat_id").html(resp);
        }
    });
}

function showOrderDetail(){
	var select_order = $('#select_order').val();
	var tmp_select = select_order.split(",");
	$('#forecast_order').val(tmp_select[0]);

	$('#show_order_detail_below').html('');
	$('#show_order_detail_top').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/forecast/get_order_detail.php" ,
		data:{
			"order_title":tmp_select[0],
			"folder_name":tmp_select[1],
			"show_remove":"yes"
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

function removeFile(order_lkr_id){
	if(confirm("Confirm remove this file?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/forecast/remove_file_data.php" ,
			data:{
				"order_lkr_id":order_lkr_id
			},
			success: function(resp){  
				
				if(resp.result=="success"){
					$('#tr_show_file'+order_lkr_id).fadeOut(500);
				}

			}  
		});
	}
}

function removeOrderSuccess(id_list){

	/*var s_id = id_list.split(",");

	for(var i=0;i<s_id.length;i++){
		$('#opt_order'+s_id[i]).hide();
		$('#chk_order'+s_id[i]).hide();
	}
	
	alert("Remove success");

	$('#deleteOrderCodeModal .close').click();

	$('.modal-backdrop').hide();

	$('#select_order').val("none");*/

	window.location.reload();
	
}

function addForecastRow(){

	if( $('#cat_id').val()=="" || $('#select_color').val()=="" || $('#used').val()=="" ){

		alert("Please select and input all data.");
		return false;
	}

	var row_id = $('#count_row').val();

	var new_row = '';

	new_row += '<tr id="row_mat'+row_id+'" class="text-center">';
	new_row += '<td>'+$('#type_id option:selected').html();
	new_row += '<input type="hidden" name="row_type_id[]" value="'+$('#type_id').val()+'">';
	new_row += '</td>';
	new_row += '<td>'+$('#cat_id option:selected').html();
	new_row += '<input type="hidden" name="row_cat_id[]" value="'+$('#cat_id').val()+'">';
	new_row += '</td>';
	new_row += '<td>'+$('#select_color option:selected').html();
	new_row += '<input type="hidden" name="row_select_color[]" value="'+$('#select_color option:selected').html()+'">';
	new_row += '<input type="hidden" name="row_select_color_id[]" value="'+$('#select_color').val()+'">';
	new_row += '</td>';
	new_row += '<td>'+$('#show_balance').val();
	new_row += '<input type="hidden" name="row_balance[]" value="'+$('#select_balance').val()+'">';
	new_row += '<input type="hidden" name="row_unit[]" value="'+$('#forecast_detail_unit_type').val()+'">';
	new_row += '</td>';
	new_row += '<td>';
	new_row += '<input type="number" min="0" name="row_used[]" step=any required value="'+$('#used').val()+'" style="width:90px;"> Kg.';
	new_row += '</td>';
	new_row += '<td>';
	new_row += '<input type="button" class="btn btn-danger" value="Del" onclick="deleteRowMat('+row_id+');">';
	new_row += '</td>';
	new_row += '</tr>';

	$('#used_detail').append(new_row);

	row_id = parseInt(row_id)+1;
	$('#count_row').val(row_id);
	$('#used').val('');

	var real_num_row = parseInt($('#real_num_row').val())+1;
	$('#real_num_row').val(real_num_row);

}

function deleteRowMat(row_id){

	if(confirm("Confirm delete row?")){
		$('#row_mat'+row_id).hide();
		$('#row_mat'+row_id).html('');
		var real_num_row = parseInt($('#real_num_row').val())-1;
		$('#real_num_row').val(real_num_row);
	}

}


function checkFormFill(){

	if($('#select_order').val()=="none"){
		alert("Please select Order Code.");
		return false;
	}

	if($('#forecast_date').val()==""){
		alert("Please input Date.");
		return false;
	}

	if( parseInt($('#real_num_row').val())<1){
		alert("Please input forecast data at least one row.");
		return false;
	}

	$('#btn_save_forecast').attr("disabled",true);
	$('#form_save_forecast').submit();
	
}

function saveForecastSuccess(){
	
	window.location.replace("<?php echo $main_path."?vp=".base64_encode('forecast');?>");

}

function saveForecastFail(msg){
	
	alert(msg);

}

function showSearchZone(){
	$('#d_search_zone').fadeIn(1000);
}

function searchInRQ(){
	var search_val = $('#s_in_rq').val();

	if(search_val==''){
		return false;
	}

	$('#d_result_search_rq').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/search_in_rq.php" ,
		data:{
			"search_val":search_val
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				var tmp_inner = "";

				if(resp.num_found==0){
					tmp_inner = "<font color=blue>Not found</font>" ;
				}else{
					tmp_inner = "<font color=red>Found "+resp.num_found+" Records.</font>" ;
				}
				
				
				$('#d_result_search_rq').html(tmp_inner);

			}else{
				alert(resp.result);
			}

		}  
	});
}

function resetSearchForm(){
	$('#d_search_zone').hide();
	$('#d_result_search_rq').html('');
	$('#d_result_search_lkr').html('');
	$('#s_in_rq').val('');
	$('#s_in_lkr').val('');
}

function searchInLKR(){
	var search_val = $('#s_in_lkr').val();

	if(search_val==''){
		return false;
	}

	$('#d_result_search_lkr').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/search_in_lkr.php" ,
		data:{
			"search_val":search_val
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				var tmp_inner = "";

				if(resp.num_found==0){
					tmp_inner = "<font color=red>Not found</font>" ;
				}else{
					tmp_inner = "<font color=blue>Found "+resp.num_found+" Records.</font>";
					tmp_inner += resp.inner_text;
				}
				
				
				$('#d_result_search_lkr').html(tmp_inner);

			}else{
				alert(resp.result);
			}

		}
	});
}

function importData(){

	var search_val = $('#s_in_lkr').val();

	if(search_val==''){
		return false;
	}

	$('#d_result_search_lkr').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/forecast/import_from_lkr.php" ,
		data:{
			"search_val":search_val
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Import completed!");
				window.location.replace("<?php echo $main_path."?vp=".base64_encode('forecast_form');?>");

			}else{
				alert(resp.result);
				$('#d_result_search_lkr').html('');
			}

		}  
	});

	
}


</script>