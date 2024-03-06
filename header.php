		<!--Change password Modal-->
		<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Change Password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="?vp=<?php echo base64_encode('employee_sql');?>&ac=<?php echo base64_encode('employee_update_password');?>">
							<fieldset>
								<div class="form-group">
									<label for="password">Password</label>
									<input type="hidden" name="bPage" value="<?php echo $pageURL;?>">
									<input class="form-control" name="password" type="password" required>
								</div>
								<input class="btn btn-primary" type="submit" value="Submit"> 
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--Change password Modal-->

<nav class="navbar navbar-danger default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
	<div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
		<a class="navbar-brand brand-logo" href="#">
			<img src="assets/images/logo.svg" alt="logo" />
		</a>
		<a class="navbar-brand brand-logo-mini" href="#">
			<img src="assets/images/logo.svg" alt="logo" />
		</a>
	</div>
	<div class="navbar-menu-wrapper d-flex align-items-center">
		<!-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
			<span class="mdi mdi-menu"></span>
		</button> -->
		<ul class="navbar-nav navbar-nav-left header-links d-none d-md-flex">
			<?php if( $_SESSION['employee_position_id']!='0' && intval($_SESSION['employee_position_id'])!=4){?>
			
			<li class="nav-item">
				<a href="?vp=<?php echo base64_encode('stock_in');?>" class="nav-link">Stock
					<span class="badge badge-primary ml-1">IN</span>
				</a>
			</li>
			<li class="nav-item" style="width:180px;">
				<a href="?vp=<?php echo base64_encode('rq_form');?>" class="nav-link">Material
					<span class="badge badge-primary ml-1">REQUEST</span>
				</a>
			</li>
			<li class="nav-item" style="width:180px;">
				<a href="?vp=<?php echo base64_encode('draw_form').'&op='.base64_encode('draw_add');?>" class="nav-link">No code
					<span class="badge badge-primary ml-1">REQUEST</span>
				</a>
			</li>
			<li class="nav-item" style="width:180px;">
				<a href="?vp=<?php echo base64_encode('forecast_form').'&op='.base64_encode('forecast_add');?>" class="nav-link">Add
					<span class="badge badge-primary ml-1">FORECAST</span>
				</a>
			</li>
			<?php }?>
		</ul>
		<ul class="navbar-nav navbar-nav-right">
			<li class="nav-item dropdown d-none d-xl-inline-block">
				<!--<div id="sp_magni_btn" style="cursor: pointer; position: fixed; margin: 10px 20px 0px -35px; font-size:20px;" onclick="return switchMagnifierMode();"><i class="fa fa-search-plus" aria-hidden="true"></i></div>-->
				<a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
					<span class="profile-text">Hello, <?php echo $_SESSION['employee_name'];?> !</span>
					<?php
						if($_SESSION['employee_image']!=''){
							echo '<img class="img-xs rounded-circle" src="files/thumb/'.$_SESSION['employee_image'].'" alt="Profile image" />';
						}else{
							echo '<img class="img-xs rounded-circle" src="assets/images/faces-clipart/pic-1.png" alt="Profile image" />';
						}
					?>
				</a>
				<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
					<a href="#" class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal"> Change Password </a>
					<a href="?vp=<?php echo base64_encode('logout');?>" class="dropdown-item"> Sign Out </a>
				</div>
			</li>
		</ul>
		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
			<span class="icon-menu"></span>
		</button>
	</div>
</nav>

