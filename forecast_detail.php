<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	jQuery(function($) {
        jQuery('body').on('change','#type_id',function(){
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
        });
        jQuery('body').on('change','#cat_id',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=color',
                'cache':false,
                'data': $("#formSearch").serialize(),
                'success':function(html){
                    jQuery("#color").html(html);
                }
            });
            return false;
        });
        jQuery('body').on('change','#color',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=balance',
                'cache':false,
                'data': $("#formSearch").serialize(),
                'success':function(html){
                    jQuery("#balance").html(html);
                }
            });
            return false;
        });
    });
</script>
<?php
$strDate = date('Y-m-d');

if(isset($_GET['forecast_id'])){$forecast_id=base64_decode($_GET['forecast_id']);}else{$forecast_id="";}

$sql_head = 'SELECT * FROM forecast_head where forecast_id="'.$forecast_id.'" ';
$query_head = $conn->query($sql_head);
$rs_head = $query_head->fetch_assoc();

?>
<h4>Forecast Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('head_update');?>" method="post">
							<div class="form-group">
								<label class="col-form-label">Forecast Code</label>
								<input type="text" name="forecast_code" class="form-control" value="<?php echo $rs_head['forecast_code'];?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Order Code</label>
								<input type="text" name="forecast_order" class="form-control" value="<?php echo $rs_head['forecast_order'];?>">
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="forecast_date" class="form-control" value="<?php echo $rs_head['forecast_date'];?>" required>
							</div>
							<div class="form-group row">
								<input type="hidden" name="forecast_id" value="<?php echo $forecast_id;?>">
								<button type="submit" class="btn btn-success btn-block">Save</button>
							</div>
						</form>	
						<div class="form-group row">
							<a href="?vp=<?php echo base64_encode('forecast_add');?>&forecast_id=<?php echo base64_encode($forecast_id);?>" class="btn btn-dark btn-block">Create Order</a>
							<a href="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('delete_all');?>&forecast_id=<?php echo base64_encode($forecast_id);?>" onclick="return confirm('Are you want to delete ?')" class="btn btn-danger btn-block">Delete</a>
						</div>
					</div>
					<div class="col-md-10">
						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('fc_detail_update');?>" method="post">
							<table width="100%" class="table-bordered">
								<thead>
									<tr class="bg-dark text-white text-center">
										<th>Type</th>
										<th>Product</th>
										<th>Color</th>
										<th>Balance</th>
										<th width="10%">Forecast</th>
										<th>Del</th>
									</tr>
								</thead>
								<tbody id="used_detail">
									<?php
									$sql_detail = 'SELECT * FROM forecast_detail where forecast_id="'.$forecast_id.'" ';
									$query_detail = $conn->query($sql_detail);
									while ($rs_detail = $query_detail->fetch_assoc()) {

										$q_cat = 'SELECT cat_code FROM cat WHERE cat_id="'.$rs_detail['cat_id'].'" ';
										$query_cat = $conn->query($q_cat);
										$rs_cat = $query_cat->fetch_assoc();
										if($rs_detail['type_id']==1){
											$sql_sum_fab = 'SELECT SUM(fabric_balance) as sum_balance FROM fabric where cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'" ';
											$query_sum_fab = $conn->query($sql_sum_fab);
											$rs_sum_fab = $query_sum_fab->fetch_assoc();

											$q_type = 'SELECT fabric_type_unit FROM fabric WHERE cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'" ';
											$query_type = $conn->query($q_type);
											$rs_type = $query_type->fetch_assoc();

											$balance=$rs_sum_fab['sum_balance'].' '.unitType($rs_type['fabric_type_unit']);
										}
									?>
										<tr class="text-center">
											<td>
												<?php 
												switch ($rs_detail['type_id']) {
													case 1:
														$type='Fabrics';
														break;
													case 2:
														$type='Accessory';
														break;
												}
												echo $type;
												?>
											</td>
											<td><?php echo $rs_cat['cat_code'];?></td>
											<td><?php echo $rs_detail['forecast_detail_color'];?></td>
											<td class="text-right"><?php echo $balance;?>&nbsp;&nbsp;</td>
											<td>
												<input type="text" name="forecast_detail_used[]" value="<?php echo $rs_detail['forecast_detail_used'];?>" class="form-detail">
												<input type="hidden" name="forecast_detail_id[]" value="<?php echo $rs_detail['forecast_detail_id'];?>">
											</td>
											<td>
												<a href="?vp=<?php echo base64_encode('forecast_sql').'&ac='.base64_encode('fc_detail_del').'&forecast_detail_id='.base64_encode($rs_detail['forecast_detail_id']).'&forecast_id='.base64_encode($forecast_id);?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you want to delete ?')">Del</a>
											</td>
										</tr>
									<?php	
									}
									?>
								</tbody>
							</table>
							<input type="hidden" name="forecast_id" value="<?php echo $forecast_id;?>">
							<button type="submit" class="btn btn-outline-primary btn-block">Update Detail</button>
						</form>
						<hr>

						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('fc_detail_add');?>" method="post" name="formSearch" id="formSearch">
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										Select Type
										<select class="form-control" name="type_id" id="type_id">
											<option value="">Select Type</option>
											<option value="1">Fabrics</option>
											<option value="2">Accessory</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Select Materials
										<select class="form-control" name="cat_id" id="cat_id">
											<option value="">Select Materials</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										Select Color
										<select class="form-control" name="color" id="color">
											<option value="">Select Color</option>
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
										<input type="text" name="used" id="used" class="form-control" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<br>
										<button type="submit" class="btn btn-success">Add</button>
										<input type="hidden" name="forecast_id" value="<?php echo $forecast_id;?>">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>