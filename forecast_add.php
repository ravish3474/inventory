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
$used_code = 'OR-'.date('ymdHi');

if(isset($_GET['forecast_id'])){$forecast_id=base64_decode($_GET['forecast_id']);}else{$forecast_id="";}

$sql_head = 'SELECT * FROM forecast_head where forecast_id="'.$forecast_id.'" ';
$query_head = $conn->query($sql_head);
$rs_head = $query_head->fetch_assoc();

?>
<h4>Forecast add order</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('fc_add_order');?>" method="post">
							<div class="form-group">
								<label class="col-form-label">Code</label>
								<input type="text" name="used_code" class="form-control" value="<?php echo $used_code;?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Order Code</label>
								<input type="text" name="used_order_code" class="form-control" value="<?php echo $rs_head['forecast_order'];?>">
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="used_date" class="form-control" value="<?php echo $rs_head['forecast_date'];?>" required>
							</div>
							<div class="form-group row">
								<input type="hidden" name="forecast_id" value="<?php echo $forecast_id;?>">
								<button type="submit" class="btn btn-success btn-block">Save</button>
							</div>
						</form>
					</div>
					<div class="col-md-10">
						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('fc_add_detail_update');?>" method="post" id="formAdd">
							<table width="100%" class="table-bordered">
								<thead>
									<tr class="bg-dark text-white text-center">
										<th>Type</th>
										<th>Product</th>
										<th>Color</th>
										<th>No/Size</th>
										<th>Balance</th>
										<th width="10%">Used</th>
										<th>Del</th>
									</tr>
								</thead>
								<tbody id="used_detail">
									<?php
									$i=0;
									$sql_detail = 'SELECT * FROM forecast_detail where forecast_id="'.$forecast_id.'" ';
									$query_detail = $conn->query($sql_detail);
									while ($rs_detail = $query_detail->fetch_assoc()) {

										$q_cat = 'SELECT cat_code FROM cat WHERE cat_id="'.$rs_detail['cat_id'].'" ';
										$query_cat = $conn->query($q_cat);
										$rs_cat = $query_cat->fetch_assoc();
										
									?>
										<script type="text/javascript">
										jQuery(function($) {
									        jQuery('body').on('change','#no_size<?php echo $i;?>',function(){
									            jQuery.ajax({
									                'type':'POST',
									                'url':'get_type.php?op=balance_add',
									                'cache':false,
									                'data':{forecast_detail_no:jQuery(this).val()},
									                'success':function(html){
									                    jQuery("#balance_add<?php echo $i;?>").html(html);
									                }
									            });
									            return false;
									        });
									    });
									</script>
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
											<td>
												<select class="form-control" name="forecast_detail_no[]" id="no_size<?php echo $i;?>">
													<?php
													if($rs_detail['forecast_detail_no']!='' OR $rs_detail['forecast_detail_size']!=''){
														echo '<option value="'.$rs_detail['forecast_detail_no'].'/'.$rs_detail['type_id'].'/'.$rs_detail['cat_id'].'/'.$rs_detail['forecast_detail_color'].'/'.$rs_detail['forecast_detail_size'].'">'.$rs_detail['forecast_detail_no'].'</option>';
													}
													echo '<option>Select No</option>';
													if($rs_detail['type_id']==1){
														$q_fab = 'SELECT * FROM fabric WHERE cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'" AND fabric_balance>="0" ORDER BY fabric_no ASC';
														$query_fab = $conn->query($q_fab);
														while ($rs_fab = $query_fab->fetch_assoc()) {
															echo '<option value="'.$rs_fab['fabric_no'].'/'.$rs_detail['type_id'].'/'.$rs_fab['cat_id'].'/'.$rs_detail['forecast_detail_color'].'/'.$rs_detail['forecast_detail_size'].'">'.$rs_fab['fabric_no'].'</option>';
														}
													}else{

													}
													?>
												</select>
											</td>
											<td class="text-right">
												<span id="balance_add<?php echo $i;?>">
													<?php
													if($rs_detail['forecast_detail_no']!='' OR $rs_detail['forecast_detail_size']!=''){
														if($rs_detail['type_id']==1){
															$q_balance = 'SELECT * FROM fabric WHERE cat_id="'.$rs_detail['cat_id'].'" AND fabric_color="'.$rs_detail['forecast_detail_color'].'"  AND fabric_no="'.$rs_detail['forecast_detail_no'].'" ';
															$query_balance = $conn->query($q_balance);
															$rs_balance = $query_balance->fetch_assoc();

															$balance=$rs_balance['fabric_balance']-$rs_detail['forecast_detail_used'];
															echo $balance.' '.unitType($rs_balance['fabric_type_unit']);
														}
													}
													?>
												</span>
											</td>
											<td>
												<input type="text" name="forecast_detail_used[]" value="<?php echo $rs_detail['forecast_detail_used'];?>" class="form-detail">
												<input type="hidden" name="forecast_detail_id[]" value="<?php echo $rs_detail['forecast_detail_id'];?>">
											</td>
											<td>
												<a href="?vp=<?php echo base64_encode('forecast_sql').'&ac='.base64_encode('fc_add_detail_del').'&forecast_detail_id='.base64_encode($rs_detail['forecast_detail_id']).'&forecast_id='.base64_encode($forecast_id);?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you want to delete ?')">Del</a>
											</td>
										</tr>
									<?php
									$i++;	
									}
									?>
								</tbody>
							</table>
							<input type="hidden" name="forecast_id" value="<?php echo $forecast_id;?>">
							<button type="submit" class="btn btn-outline-primary btn-block">Update Detail</button>
						</form>
						<hr>

						<form action="?vp=<?php echo base64_encode('forecast_sql');?>&ac=<?php echo base64_encode('fc_add_detail_add');?>" method="post" name="formSearch" id="formSearch">
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
										Used
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