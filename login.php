<?php
	session_start();
	if(isset($_SESSION['employee_id'])){
		echo '<meta http-equiv="refresh" content="0;URL=index.php">';
	}else{
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>JOGSPORTWEAR - INVENTORY</title>
		<!-- plugins:css -->
		<link rel="stylesheet" href="assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="assets/vendors/iconfonts/puse-icons-feather/feather.css">
		<link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
		<link rel="stylesheet" href="assets/vendors/css/vendor.bundle.addons.css">
		<!-- endinject -->
		<!-- plugin css for this page -->
		<!-- End plugin css for this page -->
		<!-- inject:css -->
		<link rel="stylesheet" href="assets/css/shared/style.css">
		<!-- endinject -->
		<link rel="shortcut icon" href="assets/images/favicon.png" /> 
	</head>
	<body>
		<div class="container-scroller">
			<div class="container-fluid page-body-wrapper full-page-wrapper">
				<div class="content-wrapper auth p-0 theme-two">
					<div class="row d-flex align-items-stretch">
						<div class="col-md-4 banner-section d-none d-md-flex align-items-stretch justify-content-center">
							<div class="slide-content bg-1"> </div>
						</div>
						<div class="col-12 col-md-8 h-100 bg-white">
							<div class="auto-form-wrapper d-flex align-items-center justify-content-center flex-column">
								
								<form action="check_login.php" method="post">
									<h3 class="mr-auto">Hello! let's get started.</h3>
									<p class="mb-5 mr-auto">JOG Inventory.</p>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="mdi mdi-account-outline"></i>
												</span>
											</div>
											<input type="text" name="employee_email" class="form-control" placeholder="Email">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">
													<i class="mdi mdi-lock-outline"></i>
												</span>
											</div>
											<input type="password" name="employee_password" class="form-control" placeholder="Password">
										</div>
										<?php
											if(isset($_GET['err'])){
												echo '<p class="footer-text text-danger">User Name Or Password Incorrect</p>';
											}
										?>
									</div>
									<div class="form-group">
										<button class="btn btn-primary submit-btn">SIGN IN</button>
									</div>
									<div class="wrapper mt-5 text-gray">
										<p class="footer-text">
											Copyright © 2019 JOGSPORTS. All rights reserved.
										</p>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- content-wrapper ends -->
			</div>
			<!-- page-body-wrapper ends -->
		</div>
		<!-- container-scroller -->
		<!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="assets/js/shared/off-canvas.js"></script>
    <script src="assets/js/shared/hoverable-collapse.js"></script>
    <script src="assets/js/shared/misc.js"></script>
    <script src="assets/js/shared/settings.js"></script>
    <script src="assets/js/shared/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->
  </body>
</html>
<?php }?>