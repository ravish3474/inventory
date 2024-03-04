<?php
if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}

if($op=='employee_edit'){
	$em_list=base64_decode($_GET['employee_id']);
	
	$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$em_list.'"';
	$query_em_list = $conn->query($q_em_list);
	$rs_em_list = $query_em_list->fetch_assoc();
?>
<h4>Employee Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Employee info</h4>
				<form action="?vp=<?php echo base64_encode('employee_sql');?>&ac=<?php echo base64_encode('employee_update');?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group text-center">
								<?php
									if($rs_em_list['employee_image']!=''){
										echo '<img class="img-xl img-thumbnail rounded-circle" src="files/thumb/'.$rs_em_list['employee_image'].'" alt="image" />';
									}else{
										echo '<img class="img-xl img-thumbnail rounded-circle" src="assets/images/faces-clipart/pic-1.png" alt="image" />';
									}
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-3 col-form-label">Name</label>
								<div class="col-9">
									<input type="hidden" name="employee_id" value="<?php echo $rs_em_list['employee_id'];?>">
									<input type="text" name="employee_name" class="form-control" value="<?php echo $rs_em_list['employee_name'];?>" required>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Email</label>
								<div class="col-sm-9">
									<input type="text" name="employee_email" class="form-control" value="<?php echo $rs_em_list['employee_email'];?>" required>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Telephone</label>
								<div class="col-sm-9">
									<input type="number" name="employee_tel" class="form-control" value="<?php echo $rs_em_list['employee_tel'];?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Position</label>
								<div class="col-sm-9">
									<select class="form-control" name="employee_position_id" required>
										<?php
											$q_position = 'SELECT * FROM employee_position WHERE employee_position_id="'.$rs_em_list['employee_position_id'].'" ORDER BY employee_position_sort ASC';
											$query_position = $conn->query($q_position);
											$rs_position = $query_position->fetch_assoc();
											echo '<option value="'.$rs_position['employee_position_id'].'">'.$rs_position['employee_position_name'].'</option>';
											
											
											$q_position2 = 'SELECT * FROM employee_position WHERE employee_position_id!="'.$rs_em_list['employee_position_id'].'" ORDER BY employee_position_sort ASC';
											$query_position2 = $conn->query($q_position2);
											
											while($rs_position2 = $query_position2->fetch_assoc()){
												echo '<option value="'.$rs_position2['employee_position_id'].'">'.$rs_position2['employee_position_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Password</label>
								<div class="col-sm-9">
									<input type="password" name="employee_password" class="form-control" placeholder="If you need to change Password">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Change User Image</label>
								<div class="col-sm-9">
									<input type="file" name="fileUpload" class="file-upload">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<button type="submit" class="btn btn-success mr-2">Submit</button>
								<button class="btn btn-light">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
}else{
?><!--Customer Form-->
<h4>Employee Form</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Employee info</h4>
				<form action="?vp=<?php echo base64_encode('employee_sql');?>&ac=<?php echo base64_encode('employee_add');?>" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-3 col-form-label">Name</label>
								<div class="col-9">
									<input type="text" name="employee_name" class="form-control" required>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Email</label>
								<div class="col-sm-9">
									<input type="text" name="employee_email" class="form-control" required>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Telephone</label>
								<div class="col-sm-9">
									<input type="number" name="employee_tel" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Position</label>
								<div class="col-sm-9">
									<select class="form-control" name="employee_position_id" required>
										<option value="">Choose..</option>
										<?php
											$q_position = 'SELECT * FROM employee_position ORDER BY employee_position_sort ASC';
											$query_position = $conn->query($q_position);
											
											while($rs_position = $query_position->fetch_assoc()){
												echo '<option value="'.$rs_position['employee_position_id'].'">'.$rs_position['employee_position_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Password</label>
								<div class="col-sm-9">
									<input type="password" name="employee_password" class="form-control" />
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">User Image</label>
								<div class="col-sm-9">
									<input type="file" name="fileUpload" class="file-upload">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<button type="submit" class="btn btn-success mr-2">Submit</button>
								<button class="btn btn-light">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--Customer Form-->
<?php }?>