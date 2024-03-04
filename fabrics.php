<?php 
	if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}
	include('respond-button.php');

	if($op=='fabrics_list'){
		if(isset($_GET['cat_id'])){$cat_id=base64_decode($_GET['cat_id']);}
?>
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
		        $('select').on('change', function() {
					var url = $(this).val();
					if (url) {
						window.location = url;
					}
					return false;
				});
		    });
		</script>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<h4 class="card-title">Fabrics</h4>
							</div>
							<div class="col-sm-3 text-right">
								<h4 class="card-title text-danger">Select Fabrics >></h4>
							</div>
							<div class="col-sm-3 text-right">
								<form>
									<select class="form-control" name="cat_id" id="cat_id" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
										<?php
										if(isset($_GET['cat_id'])){
											$q_cat_search = 'SELECT * FROM cat WHERE type_id="1" AND cat_id="'.$cat_id.'" ';
											$query_cat_search = $conn->query($q_cat_search);
											$rs_cat_search = $query_cat_search->fetch_assoc();
											echo '<option value="?vp='.base64_encode('fabrics').'&op='.base64_encode('fabrics_list').'&cat_id='.base64_encode($rs_cat_search['cat_id']).'&t_id=1">'.$rs_cat_search['cat_name_en'].'</option>';
											echo '<option value="?vp='.base64_encode('fabrics').'&op='.base64_encode('fabrics_list').'">View All</option>';
										}else{
											echo '<option value="">Search Type</option>';
										}
										$q_cat_list = 'SELECT * FROM cat WHERE type_id="1" ORDER BY cat_name_en ASC';
										$query_cat_list = $conn->query($q_cat_list);
										while ($rs_cat_list = $query_cat_list->fetch_assoc()) {
											echo '<option value="?vp='.base64_encode('fabrics').'&op='.base64_encode('fabrics_list').'&cat_id='.base64_encode($rs_cat_list['cat_id']).'&t_id=1">'.$rs_cat_list['cat_name_en'].'</option>';
										}
										?>
									</select>
								</form>						
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table id="order-listing" class="table table-hover">
										<thead>
											<tr class="bg-dark text-white">
												<th>PRODUCT</th>
												<th>COLOR</th>
												<th class="text-center">BALANCE</th>
												<th class="text-center">Forecast</th>
												<th class="text-center">USED</th>
												<th class="text-center">Unit<br><small>Type</small></th>
												<th width="20%" class="text-center">LAST USED</th>
												<th class="text-center">PROCESS</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												if(isset($_GET['cat_id'])){
													$q_fabric_list = 'SELECT * FROM fabric INNER JOIN cat ON fabric.cat_id = cat.cat_id WHERE fabric.cat_id="'.$cat_id.'" GROUP BY fabric.cat_id , fabric.fabric_color';
												}else{
													$q_fabric_list='SELECT * FROM fabric INNER JOIN cat ON fabric.cat_id = cat.cat_id GROUP BY fabric.cat_id , fabric.fabric_color';
												}
												
												$query_fabric_list = $conn->query($q_fabric_list);
												while($rs_fabric_list = $query_fabric_list->fetch_assoc()){
													
													$q_cat = 'SELECT * FROM cat WHERE cat_id="'.$rs_fabric_list['cat_id'].'"';
													$query_cat = $conn->query($q_cat);
													$rs_cat = $query_cat->fetch_assoc();
											?>
													<tr>
														<td><?php echo $rs_cat['cat_code'];?></td>
														<td><?php echo $rs_fabric_list['fabric_color'];?></td>
														<td class="text-center">
															<?php
															$q_sum_b = 'SELECT SUM(fabric_balance) as SumBalance FROM fabric WHERE cat_id="'.$rs_fabric_list['cat_id'].'" AND fabric_color="'.$rs_fabric_list['fabric_color'].'" GROUP BY cat_id , fabric_color';
															$query_sum_b = $conn->query($q_sum_b);
															$rs_sum_b = $query_sum_b->fetch_assoc();
															//echo $q_sum_b;
															if($rs_sum_b['SumBalance']<10){
																echo '<div class="badge badge-danger badge-pill">'.$rs_sum_b['SumBalance'].'</div>';
															}else{
																echo '<div class="badge badge-primary badge-pill">'.$rs_sum_b['SumBalance'].'</div>';
															}
															?>
														</td>
														<td class="text-center">
															<?php
															$q_sum_f = 'SELECT SUM(forecast_detail_used) as SumForecast FROM forecast_detail WHERE cat_id="'.$rs_fabric_list['cat_id'].'" AND forecast_detail_color="'.$rs_fabric_list['fabric_color'].'" ';
															$query_sum_f = $conn->query($q_sum_f);
															$rs_sum_f = $query_sum_f->fetch_assoc();
															
															echo $rs_sum_f['SumForecast'];
															?>
														</td>
														<td class="text-center">
															<?php
															$q_sum_t = 'SELECT SUM(fabric_used) as SumUsed FROM fabric WHERE cat_id="'.$rs_fabric_list['cat_id'].'" AND fabric_color="'.$rs_fabric_list['fabric_color'].'" GROUP BY cat_id , fabric_color';
															$query_sum_t = $conn->query($q_sum_t);
															$rs_sum_t = $query_sum_t->fetch_assoc();
															//echo $q_sum_t;
															echo $rs_sum_t['SumUsed'];
															?>
														</td>
														<td class="text-center">
															<?php
															$q_sum_type = 'SELECT fabric_type_unit FROM fabric WHERE cat_id="'.$rs_fabric_list['cat_id'].'" AND fabric_color="'.$rs_fabric_list['fabric_color'].'" GROUP BY cat_id , fabric_color';
															$query_sum_type = $conn->query($q_sum_type);
															$rs_sum_type = $query_sum_type->fetch_assoc();
															//echo $q_sum_type;
															
															switch ($rs_sum_type['fabric_type_unit']) {
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
														<th class="text-center">
															<?php
															$q_sum_u = 'SELECT used_id FROM used_detail WHERE type_id="1" AND cat_id="'.$rs_fabric_list['cat_id'].'" AND used_detail_color="'.$rs_fabric_list['fabric_color'].'" ORDER BY used_detail_id DESC';
															$query_sum_u = $conn->query($q_sum_u);
															$rs_sum_u = $query_sum_u->fetch_assoc();
															//echo $q_sum_u;
															if($rs_sum_u['used_id']!=''){
																$q_sum_uid = 'SELECT used_date FROM used_head WHERE used_id="'.$rs_sum_u['used_id'].'"';
																$query_sum_uid = $conn->query($q_sum_uid);
																$rs_sum_uid = $query_sum_uid->fetch_assoc();
																echo Ndate($rs_sum_uid['used_date']);
																$exd = explode(" ",$rs_sum_uid['used_date']);
																echo '<br>'.$exd[1];
															}
															?>
														</th>
														<td class="text-center">
															<a class="btn btn-light" href="?vp=<?php echo base64_encode('fabrics').'&op='.base64_encode('fabrics_detail').'&cat_id='.base64_encode($rs_fabric_list['cat_id']).'&fabric_color='.base64_encode($rs_fabric_list['fabric_color']);?>">
																<i class="mdi mdi-eye text-primary"></i>View 
															</a>
														</td>
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
			</div>
		</div>
<?php
	}

	if($op=='fabrics_detail'){
		if(isset($_GET['cat_id'])){$cat_id=base64_decode($_GET['cat_id']);}
		if(isset($_GET['fabric_color'])){$fabric_color=base64_decode($_GET['fabric_color']);}
		$q_cat = 'SELECT * FROM cat WHERE cat_id="'.$cat_id.'"';
		$query_cat = $conn->query($q_cat);
		$rs_cat = $query_cat->fetch_assoc();
?>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<h4 class="card-title">Fabrics - <?php echo $rs_cat['cat_code'];?></h4>
							</div>
							<div class="col-sm-6 text-right">
								
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table id="order-listing" class="table table-hover">
										<thead>
											<tr class="bg-dark text-white">
												<th>Received<br><small>Date</small></th>
												<th>Color</th>
												<th>No.</th>
												<th>Box</th>
												<th class="text-right">Used</th>
												<th class="text-right">Balance</th>
												<th class="text-right">Type<br><small>Unit</small></th>
												<th class="text-right">Amount</th>
												<th class="text-center">Last Used</th>
												<th class="text-center">View</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$q_fabric_list = 'SELECT * FROM fabric WHERE cat_id="'.$cat_id.'" AND fabric_color="'.$fabric_color.'"';
												$query_fabric_list = $conn->query($q_fabric_list);
												while($rs_fabric_list = $query_fabric_list->fetch_assoc()){
											?>
											<tr>
												<td><?php echo Ndate($rs_fabric_list['fabric_date_create']);?></td>
												<td><?php echo $rs_fabric_list['fabric_color'];?></td>
												<td><?php echo $rs_fabric_list['fabric_no'];?></td>
												<td><?php echo $rs_fabric_list['fabric_box'];?></td>
												<td class="text-right">
													<?php 
													if($rs_fabric_list['fabric_used']<0){
														echo '<div class="badge badge-danger badge-pill">'.number_format($rs_fabric_list['fabric_used'],2).'</div>';
													}else{
														echo '<div class="badge badge-primary badge-pill">'.number_format($rs_fabric_list['fabric_used'],2).'</div>';
													}
													?>
												</td>
												<td class="text-right">
													<?php 
													if($rs_fabric_list['fabric_balance']<10){
														echo '<div class="badge badge-danger badge-pill">'.number_format($rs_fabric_list['fabric_balance'],2).'</div>';
													}else{
														echo '<div class="badge badge-primary badge-pill">'.number_format($rs_fabric_list['fabric_balance'],2).'</div>';
													}
													?>
												</td>
												<td class="text-right">
													<?php 
													switch ($rs_fabric_list['fabric_type_unit']) {
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
												<td class="text-right">
													<?php 
													//$amount=$rs_fabric_list['fabric_in_total']-$rs_fabric_list['fabric_total'];
													$amount=$rs_fabric_list['fabric_balance']*$rs_fabric_list['fabric_in_price'];
													echo number_format($amount,2).'&nbsp;&nbsp;';
													?>
												</td>
												<td class="text-center">
													<?php
													$q_sum_u = 'SELECT used_id FROM used_detail WHERE type_id="1" AND cat_id="'.$cat_id.'" AND used_detail_color="'.$rs_fabric_list['fabric_color'].'" AND materials_id="'.$rs_fabric_list['fabric_id'].'" ORDER BY used_detail_id DESC';
													$query_sum_u = $conn->query($q_sum_u);
													$rs_sum_u = $query_sum_u->fetch_assoc();
													//echo $q_sum_u;
													if($rs_sum_u['used_id']!=''){
														$q_sum_uid = 'SELECT used_date FROM used_head WHERE used_id="'.$rs_sum_u['used_id'].'"';
														$query_sum_uid = $conn->query($q_sum_uid);
														$rs_sum_uid = $query_sum_uid->fetch_assoc();
														echo Ndate($rs_sum_uid['used_date']);
														$exd = explode(" ",$rs_sum_uid['used_date']);
														echo '<br>'.$exd[1];
													}
													?>
												</td>
												<td class="text-center">
													<a class="btn btn-light" href="?vp=<?php echo base64_encode('fabrics').'&op='.base64_encode('fabrics_no_detail').'&cat_id='.base64_encode($cat_id).'&fabric_color='.base64_encode($rs_fabric_list['fabric_color']).'&fab_id='.base64_encode($rs_fabric_list['fabric_id']);?>">
														<i class="mdi mdi-eye text-primary"></i>View 
													</a>
													<?php
													if($_SESSION['employee_position_id']==99){
													?>
													<a class="btn btn-danger" href="?vp=<?php echo base64_encode('fabrics_sql').'&ac='.base64_encode('fabrics_del').'&fab_id='.base64_encode($rs_fabric_list['fabric_id']).'&cat_id='.base64_encode($rs_fabric_list['cat_id']).'&fabric_color='.base64_encode($rs_fabric_list['fabric_color']);?>" onclick="return confirm('Are you want to delete Fabric <?php echo $rs_fabric_list['fabric_color'];?> ?')">
														Del 
													</a>
													<?php	
													}
													?>
												</td>
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
			</div>
		</div>	
<?php
	}

	if($op=='fabrics_no_detail'){
		if(isset($_GET['cat_id'])){$cat_id=base64_decode($_GET['cat_id']);}
		if(isset($_GET['fabric_color'])){$fabric_color=base64_decode($_GET['fabric_color']);}
		if(isset($_GET['fab_id'])){$fab_id=base64_decode($_GET['fab_id']);}

		$q_cat = 'SELECT * FROM cat WHERE cat_id="'.$cat_id.'"';
		$query_cat = $conn->query($q_cat);
		$rs_cat = $query_cat->fetch_assoc();

		$q_fab = 'SELECT * FROM fabric WHERE fabric_id="'.$fab_id.'"';
		$query_fab = $conn->query($q_fab);
		$rs_fab = $query_fab->fetch_assoc();

		$q_re = 'SELECT receipt_number FROM receipt WHERE receipt_id="'.$rs_fab['receipt_id'].'"';
		$query_re = $conn->query($q_re);
		$rs_re = $query_re->fetch_assoc();

		$q_po = 'SELECT po_no FROM po_head WHERE po_id="'.$rs_fab['po_id'].'"';
		$query_po = $conn->query($q_po);
		$rs_po = $query_po->fetch_assoc();
?>
		<div class="row">
			<div class="col-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								Fabrics
								<br><?php echo $rs_cat['cat_code'];?> 
								<div class="table-responsive">
									<table class="table table-bordered">
										<tr>
											<th class="bg-dark text-white">Instock Date</th>
											<td class="bg-danger text-white"><?php echo Ndate($rs_fab['fabric_date_create']);?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Received No.</th>
											<td>
												<a href="#" class="v_re" data-id="<?php echo $rs_fab['receipt_id'];?>"  data-toggle="modal" data-target="#viewReModal">
													<?php echo $rs_re['receipt_number'];?>
												</a>
											</td>
										</tr>
										<tr>
											<th class="bg-dark text-white">PO No.</th>
											<td><?php echo $rs_po['po_no'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Color</th>
											<td><?php echo $fabric_color;?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">No.</th>
											<td><?php echo $rs_fab['fabric_no'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Box</th>
											<td><?php echo $rs_fab['fabric_box'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Received</th>
											<td><?php echo $rs_fab['fabric_in_piece'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Type-<small>Unit</small></th>
											<td>
												<?php 
												switch ($rs_fab['fabric_type_unit']) {
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
										</tr>
										<tr>
											<th class="bg-dark text-white">Total</th>
											<td><?php echo number_format($rs_fab['fabric_in_total'],2);?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Used</th>
											<td><?php echo $rs_fab['fabric_used'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Adjust</th>
											<td><?php echo $rs_fab['fabric_adjust'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Balance</th>
											<td class="bg-primary text-white"><?php echo $rs_fab['fabric_balance'];?></td>
										</tr>
										<tr>
											<th class="bg-dark text-white">Total</th>
											<td><?php echo $rs_fab['fabric_total'];?></td>
										</tr>
										<tr>
											<td colspan="2"><a class="e_log btn btn-block btn-info text-white" data-toggle="modal" data-target="#logModal" data-id="<?php echo $rs_fab['fabric_id'];?>">Log</a></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-9">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="order-listing" class="table table-bordered">
								<thead>
									<tr class="bg-dark text-white text-center">
										<th>DATE</th>
										<th>ORDER</th>
										<th>USED</th>
										<th>TYPE</th>
										<th>TOTAL</th>
										<th>VIEW</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$q_de = 'SELECT * FROM used_detail WHERE cat_id="'.$cat_id.'" AND used_detail_color="'.$fabric_color.'" AND materials_id="'.$fab_id.'" ORDER BY used_detail_id DESC';
									$query_de = $conn->query($q_de);
									while ($rs_de = $query_de->fetch_assoc()) {
										
										$q_de_head = 'SELECT * FROM used_head WHERE used_id="'.$rs_de['used_id'].'"';
										$query_de_head = $conn->query($q_de_head);
										$rs_de_head = $query_de_head->fetch_assoc();
									?>
									<tr class="text-center">
										<td>
											<?php
											echo Ndate($rs_de_head['used_date']);
											$exd = explode(" ",$rs_de_head['used_date']);
											echo '<br>'.$exd[1];
											?>
										</td>
										<td>
											<?php echo $rs_de_head['used_order_code'];?>
										</td>
										<td>
											<?php echo $rs_de['used_detail_used'];?>
										</td>
										<td>
											<?php echo unitType($rs_de['used_detail_unit_type']);?>
										</td>
										<td>
											<?php echo $rs_de['used_detail_total'];?>
										</td>
										<td>
											<a class="v_po btn btn-light" data-id="<?php echo $rs_de['used_id'];?>"  data-toggle="modal" data-target="#viewDrawModal">
												<i class="mdi mdi-eye text-primary"></i>View 
											</a>
										</td>
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
		<!--Modal Log PO Detail-->
		<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Log Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="show_log">
					</div>
				</div>
			</div>
		</div>
		<!--Modal Log PO Detail-->

		<script type="text/javascript">
			$(document).on("click", ".e_log", function () {
				var fab_id = $(this).data('id');
				
				$.ajax({  
					type: "POST",  
					url:"get_log.php?ac=fab" ,
					data: "fab_id="+fab_id,
					success: function(data){  
						$("#show_log").html(data);  
					}  
				});
			});
		</script>
		<!--Modal Draw-->
		<div class="modal fade" id="viewDrawModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Materials Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="show_draw">
					</div>
				</div>
			</div>
		</div>
		<!--Modal Draw -->
		<script type="text/javascript">
			$(document).on("click", ".v_po", function () {
				var eventId = $(this).data('id');
				
				$.ajax({  
					type: "POST",  
					url:"get_draw.php" ,
					data: "used_id="+eventId,
					success: function(data){  
						$("#show_draw").html(data);  
					}  
				});
			});
		</script>

		<!--Modal View PO-->
		<div class="modal fade" id="viewReModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Recieved Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="show_re">
					</div>
				</div>
			</div>
		</div>
		<!--Modal View re-->
		<script type="text/javascript">
			$(document).on("click", ".v_re", function () {
				var eventId = $(this).data('id');
				
				$.ajax({  
					type: "POST",  
					url:"get_re.php" ,
					data: "re_id="+eventId,
					success: function(data){  
						$("#show_re").html(data);  
					}  
				});
			});
		</script>
<?php
	}
?>


	