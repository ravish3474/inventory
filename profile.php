<?php 
	$now = date('Y-m-d');
	$yesterday=date('Y-m-d', strtotime($now .' -1 day'));
?>
<div class="row profile-page">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="profile-header text-white">
					<div class="row">
						<div class="col-md-2 col-sm-12 d-none d-md-flex">
							
						</div>
						<div class="col-md-2 col-sm-12 d-none d-md-flex">
							<center>
							<?php
							if($_SESSION['employee_image']!=''){
								echo '<img class="rounded-circle img-lg" src="files/thumb/'.$_SESSION['employee_image'].'" alt="profile image">';
							}else{
								echo '<img class="rounded-circle img-lg" src="assets/images/carousel/login_2.jpg" alt="profile image">';
							}
							?>
							</center>
						</div>
						<div class="ml-3 col-md-4 col-sm-12">
							<p class="profile-user-name"><?php echo $_SESSION['employee_name'];?></p>
							<div class="wrapper d-flex align-items-center">
								<p class="profile-user-designation">
									<?php 
									if($_SESSION['employee_position_id']!='0'){
										$q_position = 'SELECT employee_position_name FROM employee_position WHERE employee_position_id="'.$_SESSION['employee_position_id'].'"';
										$query_position = $conn->query($q_position);
										$rs_position = $query_position->fetch_assoc();
										echo $rs_position['employee_position_name'];
									}else if($_SESSION['customer_id']!='0'){
										$q_customer = 'SELECT customer_name FROM customer WHERE customer_id="'.$_SESSION['customer_id'].'"';
										$query_customer = $conn->query($q_customer);
										$rs_customer = $query_customer->fetch_assoc();
										echo $rs_customer['customer_name'];
									}
									?>
									<br><strong>Phone :</strong> <?php echo $_SESSION['employee_tel'];?>
									<br><strong>Email :</strong> <?php echo $_SESSION['employee_email'];?>
								</p>
							</div>
						</div>
						<div class="ml-3 col-md-2 col-sm-12">
							<a type="button" data-toggle="modal" data-target="#editProfileModal" class="btn btn-primary">
								<i class="mdi mdi-cloud-download"></i>Edit Profile
							</a>
						</div>
					</div>
				</div>
				<div class="profile-body">
					<ul class="nav tab-switch" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="user-profile-info-tab" data-toggle="pill" href="#user-profile-info" role="tab" aria-controls="user-profile-info" aria-selected="true">Last Activity</a>
						</li>
					</ul>
					<div class="row">
						<div class="col-md-9">
							<div class="tab-content tab-body" id="profile-log-switch">
								<div class="tab-pane fade show active pr-3" id="user-profile-info" role="tabpanel" aria-labelledby="user-profile-info-tab">
									<div class="table-responsive">
										
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="horizontal-timeline">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Edit Profile Modal-->
<?php
	$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$_SESSION['employee_id'].'"';
	$query_em_list = $conn->query($q_em_list);
	$rs_em_list = $query_em_list->fetch_assoc();
?>
		<div class="modal modal-lg fade" id="editProfileModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Profile</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="?vp=<?php echo base64_encode('employee_sql');?>&ac=<?php echo base64_encode('employee_update_self');?>" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<?php
											if($rs_em_list['employee_image']!=''){
												echo '<img class="img-lg img-thumbnail rounded-circle" src="files/thumb/'.$rs_em_list['employee_image'].'" alt="image" />';
											}else{
												echo '<img class="img-lg img-thumbnail rounded-circle" src="assets/images/faces-clipart/pic-1.png" alt="image" />';
											}
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-3 col-form-label">Name</label>
										<div class="col-9">
											<input type="hidden" name="employee_id" value="<?php echo $rs_em_list['employee_id'];?>">
											<input type="text" name="employee_name" class="form-control" value="<?php echo $rs_em_list['employee_name'];?>" required>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Email</label>
										<div class="col-sm-9">
											<input type="text" name="employee_email" class="form-control" value="<?php echo $rs_em_list['employee_email'];?>" required>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Telephone</label>
										<div class="col-sm-9">
											<input type="number" name="employee_tel" class="form-control" value="<?php echo $rs_em_list['employee_tel'];?>" required>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Password</label>
										<div class="col-sm-9">
											<input type="password" name="employee_password" class="form-control" placeholder="If you need to change Password">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
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
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
<!--Edit Profile Modal-->