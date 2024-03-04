<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	jQuery(function($) {
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
	.form-control{
		font-size: 12px;
	}

	.badge {
		font-size: 11px;
	}
</style>
<?php

if(isset($_GET['forecast_id'])){
	$forecast_id=base64_decode($_GET['forecast_id']);
}else{
	echo "ERROR: Invalid parameter.";
	exit();
}
$a_unit = array();
$a_unit["1"] = 'Piece';
$a_unit["2"] = 'Yard';
$a_unit["3"] = 'KG';

$a_type = array();
$a_type["1"] = 'Fabrics';
$a_type["2"] = 'Accessorys';

$sql_forecast = "SELECT * FROM forecast_head WHERE forecast_id='".$forecast_id."'; ";
$rs_forecast = $conn->query($sql_forecast);
$row_forecast = $rs_forecast->fetch_assoc();
$is_produced = $row_forecast["is_produced"];

$sql_foc_detail = "SELECT forecast_detail.*,cat.cat_name_en FROM forecast_detail ";
$sql_foc_detail .= " LEFT JOIN cat ON forecast_detail.cat_id=cat.cat_id";
$sql_foc_detail .= " WHERE forecast_detail.forecast_id='".$forecast_id."'; ";
$rs_foc_detail = $conn->query($sql_foc_detail);
$detail_num_rows = $rs_foc_detail->num_rows;

?>
<h4 style="font-size:20px; font-weight: normal;">Forecast View & Edit</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<form action="ajax/forecast/save_forecast_edit.php" method="post" target="hidden_frame">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="col-form-label">Forecast Code</label>
								<input type="text" name="forecast_code" class="form-control" value="<?php echo $row_forecast['forecast_code'];?>" readonly>
								<input type="hidden" id="count_row" value="<?php echo ($detail_num_rows+1); ?>">
								<input type="hidden" id="real_num_row" value="<?php echo $detail_num_rows; ?>">
								<input type="hidden" id="forecast_id" name="forecast_id" value="<?php echo $forecast_id; ?>">
							</div>
							<div class="form-group">
								<label class="col-form-label">Order Code</label>
								<input type="text" id="forecast_order" name="forecast_order" class="form-control" value="<?php echo $row_forecast['forecast_order'];?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="date" name="forecast_date" id="forecast_date" class="form-control" value="<?php echo $row_forecast['forecast_date'];?>" required <?php if($is_produced=="1"){ echo "readonly"; } ?>>
							</div>
						</div>
						<div class="col-md-10">
						
							
								<table width="100%" class="table-bordered">
									<thead>
										<tr class="bg-dark text-white text-center">
											<th>Type</th>
											<th>Materials</th>
											<th>Color</th>
											<th>Balance</th>
											<th width="10%">Forecast</th>
											<?php if($is_produced=="0"){  ?>
											<th>Del</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody id="used_detail">
										<?php
										$row_count = 1;
										while($row_foc_detail = $rs_foc_detail->fetch_assoc()){
											$q_balance = 'SELECT SUM(fabric_balance) AS sum_balance FROM fabric WHERE cat_id="'.$row_foc_detail['cat_id'].'" AND fabric_color="'.$row_foc_detail['forecast_detail_color'].'" AND fabric_type_unit="'.$row_foc_detail['forecast_detail_unit_type'].'"; ';
											$rs_balance = $conn->query($q_balance);
											$row_balance = $rs_balance->fetch_assoc();
											$n_sum_balance = (($row_balance['sum_balance']=="")?"0":$row_balance['sum_balance']);
										?>
											<tr id="row_mat<?php echo $row_count; ?>" class="text-center">
												<td><?php echo $a_type[($row_foc_detail['type_id'])]; ?>
													<input type="hidden" name="row_type_id[]" value="<?php echo $row_foc_detail['type_id']; ?>">
												</td>
												<td><?php echo $row_foc_detail['cat_name_en']; ?>
													<input type="hidden" name="row_cat_id[]" value="<?php echo $row_foc_detail['cat_id']; ?>">
												</td>
												<td><?php echo $row_foc_detail['forecast_detail_color']; ?>
													<input type="hidden" name="row_select_color[]" value="<?php echo $row_foc_detail['forecast_detail_color']; ?>">
													<input type="hidden" name="row_select_color_id[]" value="<?php echo $row_foc_detail['color_id']; ?>">
												</td>
												<td><?php echo $row_balance['sum_balance']." ".$a_unit[($row_foc_detail['forecast_detail_unit_type'])]; ?>
													<input type="hidden" name="row_balance[]" value="<?php echo $n_sum_balance; ?>">
													<input type="hidden" name="row_unit[]" value="<?php echo $row_foc_detail['forecast_detail_unit_type']; ?>">
												</td>
												<td>
													<input type="number" name="row_used[]" class="form-control" step=any required value="<?php echo $row_foc_detail['forecast_detail_used']; ?>" <?php if($is_produced=="1"){ echo "readonly"; } ?> >
												</td>
												<?php if($is_produced=="0"){  ?>
												<td>
													<input type="button" class="btn btn-danger" value="Del" onclick="deleteRowMat(<?php echo $row_count; ?>);">
												</td>
												<?php } ?>
											</tr>
										<?php
											$row_count++;
										}
										?>
									</tbody>
								</table>
							
							<?php if($is_produced=="0"){  ?>
							<hr>
						
						
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
										Used
										<input type="number" name="used" id="used" class="form-control">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<br>
										<button type="button" class="btn btn-secondary" onclick="addForecastRow();">Add</button>
										
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					
					</div>
					<div class="row">
						<div class="col-2">
							<button type="button" class="btn btn-info" style="width: 100%;" onclick="return cancelEditing();"><i class="fa fa-caret-left" aria-hidden="true"></i> Cancel</button>
						</div>
						<?php if($is_produced=="0"){  ?>
						<div class="col-2">
							<button type="button" class="btn btn-dark" style="width: 100%;" onclick="return resetForm();">Reset</button>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-success" style="width: 100%;" onclick="return checkFormFill();">Save Editing</button>
						</div>
						<div class="col-2">
							<button type="button" class="btn btn-danger" style="width: 100%;" onclick="return deleteForecast();">Delete Forecast</button>
						</div>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<iframe name="hidden_frame" style="display: none;" width="0" height="0"></iframe>

<script type="text/javascript">
getMaterials();

function getMaterials(){
	$.ajax({
        type:'POST',
        dataType: "html",
        url:'get_type.php?op=type',
        cache:false,
        data:{ "type_id":$("#type_id").val()},
        success:function(resp){
            $("#cat_id").html(resp);
        }
    });
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
	new_row += '<input type="number" name="row_used[]" class="form-control" step=any required value="'+$('#used').val()+'">';
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


	if($('#forecast_date').val()==""){
		alert("Please input Date.");
		return false;
	}

	if( parseInt($('#real_num_row').val())<1){
		alert("Please input forecast data at least one row.");
		return false;
	}
	
}


function saveForecastSuccess(){
	window.location.replace("<?php echo $main_path."?vp=".base64_encode('forecast');?>");
}

function saveForecastFail(msg){	
	alert(msg);
}

function resetForm(){
	window.location.reload();
}

function cancelEditing(){
	window.location.replace("<?php echo $main_path."?vp=".base64_encode('forecast');?>");
}

function deleteForecast(){

	if(confirm("Do you want to delete this forecast permanently?")){
		$.ajax({
	        type:'POST',
	        dataType: "json",
	        url:'ajax/forecast/delete_forecast.php',
	        cache:false,
	        data:{ 
	        	"forecast_id":$("#forecast_id").val(), 
	        	"order_code":$("#forecast_order").val()
	    	},
	        success:function(resp){
	            if(resp.result=="success"){
	            	window.location.replace("<?php echo $main_path."?vp=".base64_encode('forecast');?>");
	            }else{
	            	alert(resp.result);
	            }
	        }
	    });
	}

}

</script>