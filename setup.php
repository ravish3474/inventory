<?php
	if(isset($_GET["op"])){$op = base64_decode($_GET["op"]);}
	if($op=='position'){
?><!--Position-->
	<h4>Position</h4>
	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add Position</h4>
					<form class="form-sample" action="?vp=<?php echo base64_encode('setup_sql').'&ac='.base64_encode('position_add').'';?>" method="post">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Position Name</label>
									<div class="col-sm-9">
										<input type="text" name="employee_position_name" class="form-control" required>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group row">
									<button type="submit" class="btn btn-success mr-2">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">All Position</h4>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Progress</th>
											<th>Sort</th>
											<th>Position name</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$q_position = 'SELECT * FROM employee_position ORDER BY employee_position_sort ASC';
											$query_position = $conn->query($q_position);
											
											while($rs_position = $query_position->fetch_assoc()){
										?>
											<form class="form-sample" action="?vp=<?php echo base64_encode('setup_sql').'&ac='.base64_encode('position_edit');?>" method="post">
												<tr>
													<td>
														<input type="submit" class="btn btn-success btn-fill" value="Edit">
														<a class="btn btn-danger btn-fill" onclick="return confirm('Are you want to delete user <?php echo $rs_position['employee_position_name']?> ?')" href="?vp=<?php echo base64_encode('setup_sql')?>&ac=<?php echo base64_encode('position_del')?>&employee_position_id=<?php echo base64_encode($rs_position['employee_position_id'])?>">Del</a>
													</td>
													<td>
														<input type="hidden" name="employee_position_id" value="<?php echo $rs_position['employee_position_id'];?>">
														<input type="text" name="employee_position_sort" value="<?php echo $rs_position['employee_position_sort'];?>">
													</td>
													<td>
														<input type="text" name="employee_position_name" value="<?php echo $rs_position['employee_position_name'];?>">
													</td>
													
												</tr>
											</form>
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
<?php 
	}else if($op=='materials'){
?>
	<h4>Materials</h4>
	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add Materials</h4>
					<form class="form-sample" action="?vp=<?php echo base64_encode('setup_sql').'&ac='.base64_encode('materials_add').'';?>" method="post">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-sm-3">
										Type
										<select name="type_id" class="form-control" required>
											<option value="">Type</option>
											<option value="1">Fabrics</option>
											<option value="2">Accessory</option>
										</select>
									</div>
									<div class="col-sm-3">
										Code
										<input type="text" name="cat_code" class="form-control" required>
									</div>
									<div class="col-sm-3">
										EN Name
										<input type="text" name="cat_name_en" class="form-control" required>
									</div>
									<div class="col-sm-3">
										TH Name
										<input type="text" name="cat_name_th" class="form-control">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<br><button type="submit" class="btn btn-success mr-2">Add</button>
							</div>
					</form>
					<form class="form-sample" action="?vp=<?php echo base64_encode('setup_sql').'&ac='.base64_encode('materials_import').'';?>" method="post" enctype="multipart/form-data">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<input type="file" name="fileUpload" class="form-control">
									</div>
									<div class="col-md-6">
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

	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">All Materials</h4>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr class="bg-dark text-white">
											<th>Category</th>
											<th>Code</th>
											<th>Name EN</th>
											<th>Name TH</th>
											<th>Process</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$q_cat = 'SELECT * FROM cat ORDER BY cat_id DESC';
											$query_cat = $conn->query($q_cat);
											while($rs_cat = $query_cat->fetch_assoc()){
												if($rs_cat['type_id']==1){
													$cat_name="Fabrics";
												}else if($rs_cat['type_id']==2){
													$cat_name="Accessory";
												}else{
													$cat_name="non";
												}
										?>
											<form class="form-sample" action="?vp=<?php echo base64_encode('setup_sql').'&ac='.base64_encode('materials_edit');?>" method="post">
												<tr>
													<td>
														<select name="type_id" class="form-control" required>
															<option value="<?php echo $rs_cat['type_id'];?>"><?php echo $cat_name;?></option>
															<option value="">Select Type</option>
															<option value="1">Fabrics</option>
															<option value="2">Accessory</option>
														</select>
													</td>
													<td>
														<input class="form-control" type="text" name="cat_code" value="<?php echo $rs_cat['cat_code'];?>" required>
													</td>
													<td>
														<input class="form-control" type="text" name="cat_name_en" value="<?php echo $rs_cat['cat_name_en'];?>" required>
													</td>
													<td>
														<input class="form-control" type="text" name="cat_name_th" value="<?php echo $rs_cat['cat_name_th'];?>">
													</td>
													<td>
														<input type="hidden" name="cat_id" value="<?php echo $rs_cat['cat_id'];?>">
														<input type="submit" class="btn btn-success btn-fill" value="Edit">
														<a class="btn btn-danger btn-fill" onclick="return confirm('Are you want to delete <?php echo $rs_cat['cat_name_en'];?> ?')" href="?vp=<?php echo base64_encode('setup_sql')?>&ac=<?php echo base64_encode('materials_del')?>&cat_id=<?php echo base64_encode($rs_cat['cat_id'])?>">Del</a>
													</td>
												</tr>
											</form>
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
<?php
	}
?>