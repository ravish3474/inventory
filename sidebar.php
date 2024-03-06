<script src="assets/jquery-latest.js"></script>
<?php
/*
01 CEO
02 Finance
03 Inventory
04 Purchase
99 Administrator
*/
$a_allow = array();

//,"adjust","adjust_form"

switch (intval($_SESSION['employee_position_id'])) {
	case 99:
		$a_allow = array(
			"profile", "materials", "draw", "forecast", "forecast_ordered", "po", "adjust", "adjust_form", "rq_list", "purchase", "setup", "report_by_roll", "report_by_date", "report_material_used", "report_estimate_used", "test"
		);
		break;
	case 1:
		$a_allow = array(
			"profile", "materials", "draw", "forecast", "forecast_ordered", "po", "adjust", "adjust_form", "rq_list", "purchase", "setup", "report_by_roll", "report_by_date", "report_material_used", "report_estimate_used"
		);
		break;
	case 2:
		$a_allow = array("profile", "materials", "draw", "forecast", "po", "adjust", "adjust_form", "rq_list", "purchase", "report_by_roll", "report_by_date", "report_material_used", "report_estimate_used");
		break;
	case 3:
		$a_allow = array("profile", "materials", "draw", "forecast", "po", "adjust", "adjust_form", "supplier", "rq_list", "report_by_roll", "report_estimate_used");
		break;
	case 4:
		$a_allow = array("profile", "purchase", "forecast", "forecast_ordered", "report_estimate_used");
		break;
}
?>
<?php $vp = "";
if (isset($_GET['vp'])) {
	$vp = base64_decode($_GET['vp']);
} ?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
		<span class="mdi mdi-menu"></span>
	</button>
	<div class="admin-profile">
		<figure>
			<img src="http://localhost/inv/inventory/files/thumb/20190919100354.jpeg" alt="">
		</figure>
		<h6 class="sidebar-icon-only-none mt-3">Administrator</h6>
		<h6 class="sidebar-icon-only-view">AD</h6>
		<div class="input-group">
			<div class="form-outline" data-mdb-input-init>
				<input type="search" id="form1" placeholder="Search" class="form-control" />
			</div>

		</div>

	</div>
	<ul class="nav">

		<?php
		if (in_array("profile", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'profile') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('profile'); ?>">
					<i class="menu-icon mdi mdi-trackpad"></i>
					<span class="menu-title">Profile</span>
				</a>
			</li>
		<?php
		}

		if (in_array("materials", $a_allow)) {
		?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="collapse" href="#materials" aria-expanded="<?php if ($vp == 'fabrics' or $vp == 'accessory') {
																								echo 'true';
																							} else {
																								echo 'false';
																							} ?>" aria-controls="materials">
					<i class="menu-icon fa fa-cubes"></i>
					<span class="menu-title">Materials</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse <?php if ($vp == 'fabrics' or $vp == 'accessory') {
											echo 'show';
										} ?>" id="materials">
					<ul class="nav flex-column sub-menu">
						<li class="nav-item <?php if ($vp == 'fabrics') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('fabrics') . '&op=' . base64_encode('fabrics_list'); ?>">Fabrics</a>
						</li>
						<li class="nav-item <?php if ($vp == 'accessory') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('accessory') . '&op=' . base64_encode('accessory_list'); ?>">Accessory</a>
						</li>
					</ul>
				</div>
			</li>
		<?php
		}

		if (in_array("purchase", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'purchase') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('purchase'); ?>">
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-title">Purchase Order</span>
				</a>
			</li>
		<?php
		}

		if (in_array("po", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'po') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('po') . '&op=' . base64_encode('po_list'); ?>">
					<i class="menu-icon fa fa-th"></i>
					<span class="menu-title">Stock IN list</span>
				</a>
			</li>
		<?php
		}

		if (in_array("forecast", $a_allow)) {
		?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="collapse" href="#forecast" aria-expanded="<?php if ($vp == 'forecast' or $vp == 'forecast_ordered' or $vp == 'forecast_detail' or $vp == 'forecast_report1') {
																								echo 'true';
																							} else {
																								echo 'false';
																							} ?>" aria-controls="forecast">
					<i class="menu-icon fa fa-linode"></i>
					<span class="menu-title">Forecast</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse <?php if ($vp == 'forecast' or $vp == 'forecast_detail' or $vp == 'forecast_report1' or $vp == 'forecast_ordered') {
											echo 'show';
										} ?>" id="forecast">
					<ul class="nav flex-column sub-menu">
						<li class="nav-item <?php if ($vp == 'forecast_purchase') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('forecast_purchase'); ?>">Purchase</a>
						</li>
						<li class="nav-item <?php if ($vp == 'forecast' or $vp == 'forecast_detail') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('forecast'); ?>">List from Orders</a>
						</li>
						<?php if (in_array("forecast_ordered", $a_allow)) { ?>
							<li class="nav-item <?php if ($vp == 'forecast_ordered') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('forecast_ordered'); ?>">Ordered to supplier</a>
							</li>
						<?php } ?>
						<li class="nav-item <?php if ($vp == 'forecast_report1') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('forecast_report1'); ?>">Report</a>
						</li>
					</ul>
				</div>
			</li>
		<?php
		}

		if (in_array("rq_list", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'rq_list') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('rq_list'); ?>&op=<?php echo base64_encode('new'); ?>">
					<i class="menu-icon fa fa-file-text-o"></i>
					<span class="menu-title">Material RQ list</span>
				</a>
			</li>
		<?php
		}

		if (in_array("draw", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'draw') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('draw'); ?>">
					<i class="menu-icon fa fa-file-o"></i>
					<span class="menu-title">No code RQ list</span>
				</a>
			</li>
		<?php
		}
		/*if(in_array("confirm",$a_allow)){
			?>
			<li class="nav-item <?php if($vp=='confirm_order'){echo 'active';}?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('confirm_order');?>">
					<i class="menu-icon fa fa-check-circle-o"></i>
					<span class="menu-title">Confirm Orders</span>
				</a>
			</li>
			<?php
			}*/

		if (in_array("supplier", $a_allow)) {
		?>
			<li class="nav-item <?php if ($vp == 'supplier' or $vp == 'supplier_detail') {
									echo 'active';
								} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('supplier') . '&op=' . base64_encode('supplier_list'); ?>">
					<i class="menu-icon fa fa-address-book-o"></i>
					<span class="menu-title">Supplier</span>
				</a>
			</li>
		<?php
		}
		/*
			if(in_array("employee",$a_allow)){
			?>
			<li class="nav-item <?php if($vp=='employee' OR $vp=='employee_detail'){echo 'active';}?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('employee').'&op='.base64_encode('employee_list');?>">
					<i class="menu-icon fa fa-grav"></i>
					<span class="menu-title">Employee</span>
				</a>
			</li>
			<?php
			}*/

		if (in_array("adjust", $a_allow) || in_array("adjust_form", $a_allow)) {
		?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="collapse" href="#adjust" aria-expanded="<?php if ($vp == 'adjust' || $vp == 'adjust_form') {
																								echo 'true';
																							} else {
																								echo 'false';
																							} ?>" aria-controls="adjust">
					<i class="menu-icon fa fa-balance-scale"></i>
					<span class="menu-title">Adjust</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse <?php if ($vp == 'adjust' || $vp == 'adjust_form') {
											echo 'show';
										} ?>" id="adjust">
					<ul class="nav flex-column sub-menu">
						<?php
						if (in_array("adjust", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'adjust') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('adjust'); ?>">Fabric List</a>
							</li>
						<?php
						}

						if (in_array("adjust_form", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'adjust_form') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('adjust_form'); ?>">Print Form</a>
							</li>
						<?php
						}
						?>
					</ul>
				</div>
			</li>
		<?php
		}

		if (in_array("report_by_roll", $a_allow) || in_array("report_by_date", $a_allow) || in_array("report_material_used", $a_allow) || in_array("report_estimate_used", $a_allow)) {
		?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="collapse" href="#report" aria-expanded="<?php if ($vp == 'report_by_roll' || $vp == 'report_by_date' || $vp == 'report_material_used' || $vp == 'report_estimate_used') {
																								echo 'true';
																							} else {
																								echo 'false';
																							} ?>" aria-controls="report">
					<i class="menu-icon fa fa-bar-chart"></i>
					<span class="menu-title">Report</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse <?php if ($vp == 'report_by_roll' || $vp == 'report_by_date' || $vp == 'report_material_used' || $vp == 'report_estimate_used') {
											echo 'show';
										} ?>" id="report">
					<ul class="nav flex-column sub-menu">
						<?php
						if (in_array("report_by_roll", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'report_by_roll') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('report_by_roll'); ?>">Transaction</a>
							</li>
						<?php
						}

						if (in_array("report_by_date", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'report_by_date') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('report_by_date'); ?>">Summary</a>
							</li>
						<?php
						}

						if (in_array("report_material_used", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'report_material_used') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('report_material_used'); ?>">Material Used</a>
							</li>
						<?php
						}

						if (in_array("report_estimate_used", $a_allow)) {
						?>
							<li class="nav-item <?php if ($vp == 'report_estimate_used') {
													echo 'active';
												} ?>">
								<a class="nav-link" href="?vp=<?php echo base64_encode('report_estimate_used'); ?>">Estimate Used</a>
							</li>
						<?php
						}
						?>
					</ul>
				</div>
			</li>
		<?php
		}

		if (in_array("setup", $a_allow)) {
		?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="collapse" href="#setup" aria-expanded="<?php if (($vp == 'setup') || ($vp == 'employee') || ($vp == 'supplier')) {
																							echo 'true';
																						} else {
																							echo 'false';
																						} ?>" aria-controls="setup">
					<i class="menu-icon fa fa-cogs"></i>
					<span class="menu-title">Setup</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse <?php if (($vp == 'setup') || ($vp == 'employee') || ($vp == 'supplier')) {
											echo 'show';
										} ?>" id="setup">
					<ul class="nav flex-column sub-menu">
						<li class="nav-item <?php if ($vp == 'supplier' or $vp == 'supplier_detail') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('supplier') . '&op=' . base64_encode('supplier_list'); ?>">
								<!-- <i class="menu-icon fa fa-address-book-o"></i> -->
								<span class="menu-title">Supplier</span>
							</a>
						</li>
						<li class="nav-item <?php if ($vp == 'setup' and $op == 'position') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('setup') . '&op=' . base64_encode('position'); ?>">Position</a>
						</li>
						<li class="nav-item <?php if ($vp == 'setup' and $op == 'materials') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('setup') . '&op=' . base64_encode('materials'); ?>">Category</a>
						</li>
						<li class="nav-item <?php if ($vp == 'employee' or $vp == 'employee_detail') {
												echo 'active';
											} ?>">
							<a class="nav-link" href="?vp=<?php echo base64_encode('employee') . '&op=' . base64_encode('employee_list'); ?>">
								<!-- <i class="menu-icon fa fa-grav"></i> -->
								<span class="menu-title">Employee</span>
							</a>
						</li>
					</ul>
				</div>
			</li>
		<?php
		}
		if (in_array("test", $a_allow)) {
		?>
			<?php //admin menu

			?>
			<li class="nav-item  <?php if ($vp == 'test') {
										echo 'active';
									} ?>">
				<a class="nav-link" href="?vp=<?php echo base64_encode('test'); ?>">
					<i class="menu-icon mdi mdi-backup-restore"></i>
					<span class="menu-title">test</span>
				</a>
			</li>
		<?php
		}
		?>
	</ul>
</nav>