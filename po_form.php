<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	function number_format( number, decimals, dec_point, thousands_sep ) {

      var n = number, prec = decimals, dec = dec_point, sep = thousands_sep;
      n = !isFinite(+n) ? 0 : +n;
      prec = !isFinite(+prec) ? 0 : Math.abs(prec);
      sep = sep == undefined ? ',' : sep;

      var s = n.toFixed(prec),
          abs = Math.abs(n).toFixed(prec),
          _, i;

      if (abs > 1000) {
          _ = abs.split(/\D/);
          i = _[0].length % 3 || 3;

          _[0] = s.slice(0,i + (n < 0)) +
                _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
          s = _.join(dec || '.');
      } else {
          s = abs.replace('.', dec_point);
      }
      return s;
    }
</script>
<?php
if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}

if($op=='po_edit'){
	$re_id=base64_decode($_GET['re_id']);
	
	$sql_re = 'SELECT * FROM receipt where receipt_id="'.$re_id.'"';
	$query_re = $conn->query($sql_re);
	$rs_re = $query_re->fetch_assoc();
?>
<h4>Received Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4 text-center">
						<div class="form-group">
							Received No : <b><?php echo $rs_re['receipt_number'];?></b>
						</div>
					</div>
					<div class="col-md-4 text-center">
						<div class="form-group">
							Received Date : <b><?php echo Ndate($rs_re['receipt_date']);?></b>
						</div>
					</div>
					<div class="col-md-4 text-center">
						<div class="form-group">
							Supplier : 
							<b>
								<?php 
								$sql_sup = 'SELECT supplier_name FROM supplier where supplier_id="'.$rs_re['receipt_supplier'].'"';
								$query_sup = $conn->query($sql_sup);
								$rs_sup = $query_sup->fetch_assoc();
								echo $rs_sup['supplier_name'];
								;?>
							</b>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$i=1;
		$sql_po = 'SELECT * FROM po_head where receipt_id="'.$re_id.'"';
		$query_po = $conn->query($sql_po);
		$po_count = $query_po->num_rows;
		if($po_count==''){
		?>
		<div class="row">
			<div class="col-md-6">
				<a data-toggle="modal" data-target="#addPoModal" class="text-white btn btn-dark btn-block mt-2 mb-2">Add More PO</a>
			</div>
			<div class="col-md-6">
				<a href="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_del').'&re_id='.base64_encode($re_id);?>" onclick="return confirm('Are you want to delete Received No <?php echo $rs_re['receipt_number'];?> ?')" class="text-white btn btn-danger btn-block mt-2 mb-2">Delete Received No</a>
			</div>
		</div>
		<?php	
		}else{ echo '<a data-toggle="modal" data-target="#addPoModal" class="text-white btn btn-dark btn-block mt-2 mb-2">Add More PO</a>';}
		while ($rs_po = $query_po->fetch_assoc()) {
		?>	
		<script type="text/javascript">
			jQuery(function($) {
		        $("#btnSend<?php echo $i;?>").click(function() {
		        	$.ajax({
						type: "POST",
		        		url: "po_sql.php?ac=<?php echo base64_encode('po_detail_add')?>",
		        		data: $("#formAdd<?php echo $i;?>").serialize(),
		        		success: function(html) {
		        			jQuery("#po_detail<?php echo $i;?>").html(html);
		        			//document.getElementById("formAdd<?php echo $i;?>").reset();
		        		}
		        	});
		        });
		        $("#btnSendAcc<?php echo $i;?>").click(function() {
		        	$.ajax({
						type: "POST",
		        		url: "po_sql.php?ac=<?php echo base64_encode('po_detail_add_acc')?>",
		        		data: $("#formAddAcc<?php echo $i;?>").serialize(),
		        		success: function(html) {
		        			jQuery("#po_detail<?php echo $i;?>").html(html);
		        			//document.getElementById("formAddAcc<?php echo $i;?>").reset();
		        		}
		        	});
		        });
		    });

		    function doCallAjax<?php echo $i;?>() {
				cat_id=document.formAdd<?php echo $i;?>.cat_id.value;
				po_detail_color=document.formAdd<?php echo $i;?>.po_detail_color.value;
				po_detail_no=document.formAdd<?php echo $i;?>.po_detail_no.value;
				//alert("message successfully sent"+cat_id+"/"+po_detail_color+"/"+po_detail_no);
				$("#show_code<?php echo $i;?>").html('<i class="fa fa-spin fa-circle-o-notch"></i>');  
				$.ajax({  
					type: "POST",  
					url:"getFab.php?op=add&cat_id="+cat_id+"&po_detail_color="+po_detail_color+"&po_detail_no="+po_detail_no ,
					success: function(data){  
						$("#show_code<?php echo $i;?>").html(data);  
					}  
				});
			}

			function fncCal<?php echo $i;?>(){
				var valSum = parseFloat(document.formAdd<?php echo $i;?>.po_detail_piece.value) * parseFloat(document.formAdd<?php echo $i;?>.po_detail_price.value);

		        document.formAdd<?php echo $i;?>.po_detail_total.value = number_format(valSum, 2, '.', ',');
		    }
		</script>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<form action="?vp=<?php echo base64_encode('po_sql');?>&ac=<?php echo base64_encode('po_edit');?>" method="post">
							<div class="form-group">
								<label class="col-form-label">PO NO.</label>
								<input type="text" name="po_no" value="<?php echo $rs_po['po_no'];?>" class="form-control" required>
							</div>
							<!--<div class="form-group">
								<label class="col-form-label">Supplier</label>
								<select class="form-control" name="supplier_id" required>
									<?php
									$q_subD = 'SELECT * FROM supplier WHERE supplier_id="'.$rs_po['supplier_id'].'" ';
									$query_subD = $conn->query($q_subD);
									$rs_subD = $query_subD->fetch_assoc();
									echo '<option value="'.$rs_subD['supplier_id'].'">'.$rs_subD['supplier_name'].'</option>';

									$q_sub = 'SELECT * FROM supplier WHERE supplier_id!="'.$rs_po['supplier_id'].'" ORDER BY supplier_name ASC';
									$query_sub = $conn->query($q_sub);
									while($rs_sub = $query_sub->fetch_assoc()){
										echo '<option value="'.$rs_sub['supplier_id'].'">'.$rs_sub['supplier_name'].'</option>';
									}
									?>
								</select>
							</div>
							-->
							<div class="form-group">
								<label class="col-form-label">PO date</label>
								<div id="datepicker-popup" class="input-group date datepicker">
									<input type="text" name="po_date" value="<?php echo $rs_po['po_date'];?>" class="form-control" required>
									<span class="input-group-addon input-group-append border-left">
										<span class="mdi mdi-calendar input-group-text"></span>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label">Total</label><br>
								<b>
									<?php 
									$sql_sum_po = 'SELECT sum(po_detail_total) AS sum_po FROM po_detail where po_id="'.$rs_po['po_id'].'"';
									$query_sum_po = $conn->query($sql_sum_po);
									$rs_sum_po = $query_sum_po->fetch_assoc();

									if($rs_sum_po['sum_po']!=$rs_po['po_total']){
										$sql_up = 'UPDATE po_head SET po_total = "'.$rs_sum_po['sum_po'].'" WHERE po_id = "'.$rs_po['po_id'].'" ';
										$query = mysqli_query($conn,$sql_up);
									}
									echo number_format($rs_sum_po['sum_po'],2);
									?>
								</b>
							</div>
							<div class="form-group">
								<input type="hidden" name="receipt_id" value="<?php echo $re_id;?>">
								<input type="hidden" name="po_id" value="<?php echo $rs_po['po_id'];?>">
								<button type="submit" class="btn btn-success mr-2">Update PO</button>
							</div>
							<?php
							$sql_po_de_num = 'SELECT po_detail_id FROM po_detail where po_id="'.$rs_po['po_id'].'" ';
							$query_po_de_num = $conn->query($sql_po_de_num);
							$rs_po_de_num = $query_po_de_num->fetch_assoc();
							if($rs_po_de_num['po_detail_id']==''){
							?>
								<div class="form-group">
									<a href="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_head_del').'&po_id='.base64_encode($rs_po['po_id']).'&po_detail_id='.base64_encode($rs_po_de_num['po_detail_id']).'&re_id='.base64_encode($re_id);?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you want to delete ?')">Del PO</a>
								</div>
							<?php	
							}
							?>
						</form>	
						<div class="form-group">
							<a class="e_log btn btn-sm btn-info text-white" data-toggle="modal" data-target="#logModal" data-id="<?php echo $rs_po['po_id'];?>">Log</a>
						</div>
					</div>
					<div class="col-md-10">
						<table width="100%" class="table-bordered">
							<thead>
								<tr class="bg-dark text-white text-center">
									<th width="120">Type</th>
									<th width="150">Product</th>
									<th width="100">Color</th>
									<th>No/Size</th>
									<th>Box</th>
									<th>Amount</th>
									<th>Type<br><small>Unit</small></th>
									<th>Price</th>
									<th>Total</th>
									<th>Del</th>
								</tr>
							</thead>
							<tbody id="po_detail<?php echo $i;?>">
								<?php
								$sql_po_de = 'SELECT * FROM po_detail where po_id="'.$rs_po['po_id'].'" ';
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
										
										<a class="e_po_de btn btn-sm btn-info text-white" data-toggle="modal" data-target="#editPoModal" data-id="<?php echo $rs_po_de['po_detail_id'];?>">Edit</a>
										<?php }?>
									</td>
								</tr>
								<?php	
								}
								?>
								<?php
								$sql_po_de_sum = 'SELECT * FROM po_detail where po_id="'.$rs_po['po_id'].'" ';
								$query_po_de_sum = $conn->query($sql_po_de_sum);
								$rs_po_de_sum = $query_po_de_sum->fetch_assoc();
								if($rs_po_de_sum['po_detail_id']!=''){
									$sql_po_sum = 'SELECT SUM(po_detail_piece) AS sum_piece , SUM(po_detail_total) AS sum_total FROM po_detail where po_id="'.$rs_po['po_id'].'" ';
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
							</tbody>
						</table>
						<hr>

						<h4 class="card-title">Add detail</h4>
						<ul class="nav nav-tabs tab-solid tab-solid-danger" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="tab-5-1<?php echo $i;?>" data-toggle="tab" href="#home-5-1<?php echo $i;?>" role="tab" aria-controls="home-5-1<?php echo $i;?>" aria-selected="true">
									Fabrics
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-5-2<?php echo $i;?>" data-toggle="tab" href="#profile-5-2<?php echo $i;?>" role="tab" aria-controls="profile-5-2<?php echo $i;?>" aria-selected="false">
									Accessory
								</a>
							</li>
						</ul>
						<div class="tab-content tab-content-solid">
							<div class="tab-pane fade show active" id="home-5-1<?php echo $i;?>" role="tabpanel" aria-labelledby="tab-5-1<?php echo $i;?>">
								<div class="row">
									<div class="col-md-12">
										<form method="post" name="formAdd<?php echo $i;?>" id="formAdd<?php echo $i;?>">
											<table width="100%" class="table-bordered">
												<thead>
													<tr class="bg-primary text-white text-center">
														<th width="150">Product</th>
														<th width="100">Color</th>
														<th>No</th>
														<th>Box</th>
														<th>Amount</th>
														<th>Type<br><small>Unit</small></th>
														<th>Price</th>
														<th>Total</th>
														<th>Add</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<select class="js-example-basic-single" name="cat_id" id="cat_id" style="width:100%" required>
																<option value="">Select Product</option>
																<?php
																$q_cat_acc = 'SELECT * FROM cat WHERE type_id="1" ORDER BY cat_code ASC';
																$query_cat_acc = $conn->query($q_cat_acc);
																while($rs_cat_acc = $query_cat_acc->fetch_assoc()){
																	echo '<option value="'.$rs_cat_acc['cat_id'].'">'.$rs_cat_acc['cat_name_en'].'</option>';
																}
																?>
															</select>
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_color" id="po_detail_color">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_no" id="po_detail_no" OnKeyUp="JavaScript:doCallAjax<?php echo $i;?>();">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_box">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_piece" id="po_detail_piece" OnKeyUp="fncCal<?php echo $i;?>();">
														</td>
														<td>
															<select class="js-example-basic-single" name="po_detail_type_unit" style="width:100%" required>
																<option value="">Select Product</option>
																<option value="2">Yard</option>
																<option value="3">KG</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_price" id="po_detail_price" OnKeyUp="fncCal<?php echo $i;?>();">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_total" value="" id="po_detail_total">
														</td>
														<td>
															<input type="hidden" name="receipt_id" value="<?php echo $re_id;?>">
															<input type="hidden" name="po_id" value="<?php echo $rs_po['po_id'];?>">
															<input type="hidden" name="po_type_id" value="1">
															<input type="hidden" name="supplier_id" value="<?php echo $rs_po['supplier_id'];?>">
															<input type="hidden" name="po_date" value="<?php echo $rs_po['po_date'];?>">
															<input type="button" name="btnSend<?php echo $i;?>" id="btnSend<?php echo $i;?>" value="Add" class="btn btn-danger btn-block">
														</td>
													</tr>
													<tr>
														<td></td>
														<td></td>
														<td><span id="show_code<?php echo $i;?>"></span></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="profile-5-2<?php echo $i;?>" role="tabpanel" aria-labelledby="tab-5-2<?php echo $i;?>">
								<div class="row">
									<div class="col-md-12">
										<form method="post" name="formAddAcc<?php echo $i;?>" id="formAddAcc<?php echo $i;?>">
											<table width="100%" class="table-bordered">
												<thead>
													<tr class="bg-primary text-white text-center">
														<th width="150">Product</th>
														<th width="100">Color</th>
														<th>Size</th>
														<th>Amount</th>
														<th>Type<br><small>Unit</small></th>
														<th>Price</th>
														<th>Total</th>
														<th>Add</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<select class="js-example-basic-single" name="cat_id" id="cat_id" style="width:100%" required>
																<option value="">Select Product</option>
																<?php
																$q_cat_acc = 'SELECT * FROM cat WHERE type_id="2" ORDER BY cat_code ASC';
																$query_cat_acc = $conn->query($q_cat_acc);
																while($rs_cat_acc = $query_cat_acc->fetch_assoc()){
																	echo '<option value="'.$rs_cat_acc['cat_id'].'">'.$rs_cat_acc['cat_name_en'].'</option>';
																}
																?>
															</select>
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_color">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_size">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_piece">
														</td>
														<td>
															<select class="js-example-basic-single" name="po_detail_type_unit" style="width:100%" required>
																<option value="">Select Product</option>
																<option value="1">Piece</option>
																<option value="2">Yard</option>
																<option value="3">KG</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_price">
														</td>
														<td>
															<input type="text" class="form-detail" name="po_detail_total">
														</td>
														<td>
															<input type="hidden" name="receipt_id" value="<?php echo $re_id;?>">
															<input type="hidden" name="po_id" value="<?php echo $rs_po['po_id'];?>">
															<input type="hidden" name="po_type_id" value="2">
															<input type="hidden" name="supplier_id" value="<?php echo $rs_po['supplier_id'];?>">
															<input type="button" name="btnSendAcc<?php echo $i;?>" id="btnSendAcc<?php echo $i;?>" value="Add" class="btn btn-danger btn-block">
														</td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<?php $i++;}?>
	</div>
</div>
<!--Modal View PO-->
<div class="modal fade" id="addPoModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add More PO</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="?vp=<?php echo base64_encode('po_sql');?>&ac=<?php echo base64_encode('po_add_other');?>" method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-form-label">PO NO.</label>
								<input type="text" name="po_no" placeholder="PO NO." class="form-control" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-form-label">PO date</label>
								<div id="duedate-popup" class="input-group date datepicker">
									<input type="text" name="po_date" placeholder="PO Date." class="form-control" required>
									<span class="input-group-addon input-group-append border-left">
										<span class="mdi mdi-calendar input-group-text"></span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="form-group">
								<input type="hidden" name="supplier_id" value="<?php echo $rs_re['receipt_supplier'];?>">
								<input type="hidden" name="receipt_id" value="<?php echo $re_id;?>">
								<button type="submit" class="btn btn-success mr-2">Add PO</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--Modal View PO-->

<!--Modal Edit PO Detail-->
<div class="modal fade" id="editPoModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="show_detail">
			</div>
		</div>
	</div>
</div>
<!--Modal Edit PO Detail-->

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
	$(document).on("click", ".e_po_de", function () {
		var po_detail_id = $(this).data('id');
		
		$.ajax({  
			type: "POST",  
			url:"get_edit_po_detail.php" ,
			data: "po_detail_id="+po_detail_id,
			success: function(data){  
				$("#show_detail").html(data);  
			}  
		});
	});
	$(document).on("click", ".e_log", function () {
		var po_id = $(this).data('id');
		
		$.ajax({  
			type: "POST",  
			url:"get_log.php?ac=log" ,
			data: "po_id="+po_id,
			success: function(data){  
				$("#show_log").html(data);  
			}  
		});
	});
</script>
<?php
}else{
	$recieved_code = 'RE-'.date('ymdHi');
?><!--PO Form-->
<h4>Receiving</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">ADD Received</h4>
				<form action="?vp=<?php echo base64_encode('po_sql');?>&ac=<?php echo base64_encode('po_add');?>" method="post">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="col-form-label">Recieved NO.</label>
								<input type="text" name="receipt_number" class="form-control" value="<?php echo $recieved_code;?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Recieved date</label>
								<div id="datepicker-popup" class="input-group date datepicker">
									<input type="text" name="receipt_date" placeholder="Recieved date" class="form-control" required>
									<span class="input-group-addon input-group-append border-left">
										<span class="mdi mdi-calendar input-group-text"></span>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-form-label">PO NO.</label>
								<input type="text" name="po_no" class="form-control" required>
							</div>
							<div class="form-group">
								<label class="col-form-label">Supplier</label>
								<select class="js-example-basic-multiple" multiple="multiple" style="width:100%,height:100%" name="supplier_id" required>
									<?php
									$q_sub = 'SELECT * FROM supplier ORDER BY supplier_name ASC';
									$query_sub = $conn->query($q_sub);
									while($rs_sub = $query_sub->fetch_assoc()){
										echo '<option value="'.$rs_sub['supplier_id'].'">'.$rs_sub['supplier_name'].'</option>';
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label class="col-form-label">PO date</label>
								<div id="duedate-popup" class="input-group date datepicker">
									<input type="text" name="po_date" placeholder="PO date" class="form-control" required>
									<span class="input-group-addon input-group-append border-left">
										<span class="mdi mdi-calendar input-group-text"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-10">
							Save PO number first.
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<button type="submit" class="btn btn-success mr-2">Submit</button>
								<button type="reset" class="btn btn-light">Cancel</button>
							</div>
						</div>
					</div>
				</form>

				<form class="form-sample" action="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('re_import').'';?>" method="post" enctype="multipart/form-data">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-3">
								<input type="file" name="fileUpload" class="form-control">
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-sm btn-success">Import Recieved</button>
							</div>
						</div>
					</div>
				</form>

				<form class="form-sample" action="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_import').'';?>" method="post" enctype="multipart/form-data">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-3">
								<input type="file" name="fileUpload" class="form-control">
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-sm btn-success">Import PO</button>
							</div>
						</div>
					</div>
				</form>
				
				<form class="form-sample" action="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_detail_import').'';?>" method="post" enctype="multipart/form-data">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-3">
								<input type="file" name="fileUploadDetail" class="form-control">
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-sm btn-success">Import Detail</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!--PO Form-->
<?php }?>