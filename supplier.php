<?php
if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}

if($op=='supplier_edit'){
?>

<?php
}else if($op=='supplier_form'){
?><!--supplier Form-->
<h4>Supplier Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Supplier info</h4>
				<form action="?vp=<?php echo base64_encode('supplier_sql');?>&ac=<?php echo base64_encode('supplier_add');?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-3 col-form-label">Supplier Name</label>
								<div class="col-9">
									<input type="text" name="supplier_name" class="form-control" required>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Supplier code</label>
								<div class="col-sm-9">
									<input type="text" name="supplier_code" class="form-control" required>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class=" col-form-label">Address</label>
								<input type="text" name="supplier_address" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<button type="submit" class="btn btn-success mr-2">Save</button>
								<button class="btn btn-light">Cancel</button>
							</div>
						</div>
					</div>
				</form>

				<form class="form-sample" action="?vp=<?php echo base64_encode('supplier_sql').'&ac='.base64_encode('supplier_import').'';?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3">
									<input type="file" name="fileUpload" class="form-control">
								</div>
								<div class="col-md-3">
									<button type="submit" class="btn btn-sm btn-success">Import</button>
								</div>
							</div>
						</div>		
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--supplier Form-->
<?php 
}else if($op=='supplier_detail'){
	if(isset($_GET['supplier_id'])){$supplier_id=base64_decode($_GET['supplier_id']);}else{$supplier_id='';}
	$q_sub = 'SELECT * FROM supplier WHERE supplier_id="'.$supplier_id.'" ';
	$query_sub = $conn->query($q_sub);
	$rs_sub = $query_sub->fetch_assoc();
?>
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<form action="?vp=<?php echo base64_encode('supplier_sql');?>&ac=<?php echo base64_encode('supplier_update');?>" method="post" enctype="multipart/form-data">
						<b>Code</b>
						<input type="text" name="supplier_code" value="<?php echo $rs_sub['supplier_code'];?>" class="form-control">
						<b>Name</b>
						<input type="text" name="supplier_name" value="<?php echo $rs_sub['supplier_name'];?>" class="form-control">
						<b>Address</b>
						<input type="text" name="supplier_address" value="<?php echo $rs_sub['supplier_address'];?>" class="form-control">
						<b>Buy Total</b>
						<br>
						<?php
						$q_sum_t = 'SELECT SUM(po_total) as SumTotal FROM po_head WHERE supplier_id="'.$supplier_id.'" ';
						$query_sum_t = $conn->query($q_sum_t);
						$rs_sum_t = $query_sum_t->fetch_assoc();
						echo number_format($rs_sum_t['SumTotal'],2);
						?>
						<input type="hidden" name="supplier_id" value="<?php echo $rs_sub['supplier_id'];?>">
						<br><button type="submit" class="btn btn-success mr-2">Update</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<table id="order-listing" class="table table-hover">
						<thead>
							<tr class="bg-dark text-white">
								<th>PO No.</th>
								<th>Date</th>
								<th class="text-center">Total</th>
								<th class="text-center">View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$q_po_head = 'SELECT * FROM po_head WHERE supplier_id="'.$supplier_id.'"';
							$query_po_head = $conn->query($q_po_head);
							while($rs_po_head = $query_po_head->fetch_assoc()){
							?>
							<tr>
								<td><?php echo $rs_po_head['po_no'];?></td>
								<td><?php echo Ndate($rs_po_head['po_date']);?></td>
								<td class="text-right"><?php echo number_format($rs_po_head['po_total'],2).'&nbsp;&nbsp;';?></td>
								<td class="text-center">
									<a class="v_po btn btn-light" data-id="<?php echo $rs_po_head['po_id'];?>"  data-toggle="modal" data-target="#viewPoModal">
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
	<!--Modal View PO-->
	<div class="modal fade" id="viewPoModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">PO Detail</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="show_po">
				</div>
			</div>
		</div>
	</div>
	<!--Modal View PO-->
	<script type="text/javascript">
		$(document).on("click", ".v_po", function () {
			var eventId = $(this).data('id');

			$.ajax({
				type: "POST",
				url:"get_po.php" ,
				data: "po_id="+eventId,
				success: function(data){  
					$("#show_po").html(data);  
				}  
			});
		});
	</script>
<?php
}else{
?>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="card-title">Supplier</h4>
						</div>
						<div class="col-sm-6 text-right"></div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="table-responsive">
								<table id="order-listing" class="table table-hover">
									<thead>
										<tr class="bg-dark text-white">
											<th>Name</th>
											<th>Code</th>
											<th class="text-center">Buy Total</th>
											<th class="text-center">Process</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$q_sub = 'SELECT * FROM supplier';
											$query_sub = $conn->query($q_sub);
											while($rs_sub = $query_sub->fetch_assoc()){
										?>
										<tr>
											<td><?php echo $rs_sub['supplier_name'];?></td>
											<td><?php echo $rs_sub['supplier_code'];?></td>
											<td class="text-right">
												<?php
												$q_sum_t = 'SELECT SUM(po_total) as SumTotal FROM po_head WHERE supplier_id="'.$rs_sub['supplier_id'].'" ';
												$query_sum_t = $conn->query($q_sum_t);
												$rs_sum_t = $query_sum_t->fetch_assoc();
												echo '<div class="badge badge-primary"><h6>'.number_format($rs_sum_t['SumTotal'],2).'</h6></div>';
												?>
											</td>
											<td class="text-center">
												<a class="btn btn-light" href="?vp=<?php echo base64_encode('supplier').'&op='.base64_encode('supplier_detail').'&supplier_id='.base64_encode($rs_sub['supplier_id']);?>">
													<i class="mdi mdi-eye text-primary"></i>View 
												</a>
												<a href="#" onclick="return confirm('Are you want to delete this ?')" class="btn btn-light">
													<i class="mdi mdi-close text-danger"></i>Remove
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
?>
<hr>
<a href="?vp=<?php echo base64_encode('supplier').'&op='.base64_encode('supplier_form');?>" class="btn btn-success">
	<i class="mdi mdi-plus"></i>Add Supplier
</a>
