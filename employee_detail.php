<?php 

	if(isset($_GET['employee_id'])){
	$em_id=base64_decode($_GET['employee_id']);
	
	$q_em_list = 'SELECT * FROM employee WHERE employee_id="'.$em_id.'"';
	$query_em_list = $conn->query($q_em_list);
	$rs_em_list = $query_em_list->fetch_assoc();
?>
<!--Employee Info-->
<div class="row">
	<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="row ticket-card">
					<div class="col-2">
						<?php
							if($rs_em_list['employee_image']!=''){
								echo '<img class="img-fluid rounded-circle" src="files/thumb/'.$rs_em_list['employee_image'].'" alt="image" />';
							}else{
								echo '<img class="img-fluid rounded-circle" src="assets/images/faces-clipart/pic-1.png" alt="image" />';
							}
						?>
					</div>
					<div class="ticket-details col-10">
						<div class="d-flex">
							<p class="text-dark font-weight-semibold mr-2 mb-0">
								<small class="Last-responded mr-2 mb-0 text-muted">Name : </small><?php echo $rs_em_list['employee_name'];?>
							</p>
						</div>
                        <div class="row d-md-flex">
							<div class="col-12 d-flex">
								<p class="text-dark font-weight-semibold mr-2 mb-0"><small class="Last-responded mr-2 mb-0 text-muted">Email : </small>
								<?php echo $rs_em_list['employee_email'];?></p>
							</div>
                        </div>
						<div class="row d-md-flex">
							<div class="col-12 d-flex">
								<p class="text-dark font-weight-semibold mr-2 mb-0">
									<small class="Last-responded mr-2 mb-0 text-muted">Telephone : </small><?php echo $rs_em_list['employee_tel'];?>
								</p>
							</div>
                        </div>
						<div class="row d-md-flex">
							<div class="col-12 d-flex">
								<p class="text-dark font-weight-semibold mr-2 mb-0 no-wrap">
									<small class="Last-responded mr-2 mb-0 text-muted">Position : </small>
									<?php 
									$q_em_po = 'SELECT * FROM employee_position WHERE employee_position_id="'.$rs_em_list['employee_position_id'].'"';
									$query_em_po = $conn->query($q_em_po);
									$rs_em_po = $query_em_po->fetch_assoc();
									echo $rs_em_po['employee_position_name'];
									?>
								</p>
							</div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
		<div class="card card-statistics">
			<div class="card-body">
				<div class="clearfix">
					<div class="float-left">
						<h5>All Job</h5>
                    </div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<?php 
	}else{}
?>