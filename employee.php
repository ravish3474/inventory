<?php 
	if(isset($_GET['op'])){$op=$vp=base64_decode($_GET['op']);}else{}
	
	if($op=='employee_list'){
?>
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-10 grid-margin stretch-card">
							<h3>Employee List</h3>
						</div>
						<div class="col-md-2 grid-margin stretch-card">
							<button type="button" class="btn btn-inverse-primary btn-rounded btn-fw" onclick="window.location.href='?vp=<?php echo base64_encode('employee_form');?>'">+ Employee</button>
						</div>
					</div>
					<div class="table-responsive">
						<table id="order-listing" class="table table-striped">
							<thead>
								<tr class="bg-primary text-white">
									<th>User</th>
									<th>Name</th>
									<th>Position</th>
									<th>Telephone</th>
									<th>Email</th>
									<th>Process</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$q_em_list = 'SELECT * FROM employee WHERE customer_id="0"';
									$query_em_list = $conn->query($q_em_list);
											
									while($rs_em_list = $query_em_list->fetch_assoc()){
								?>
								<tr>
									<td class="py-1">
										<?php
											if($rs_em_list['employee_image']!=''){
												echo '<img src="files/thumb/'.$rs_em_list['employee_image'].'" alt="image" />';
											}else{
												echo '<img src="assets/images/faces-clipart/pic-1.png" alt="image" />';
											}
										?>
									</td>
									<td><a href="?vp=<?php echo base64_encode('employee_detail');?>&employee_id=<?php echo base64_encode($rs_em_list['employee_id']);?>"><?php echo $rs_em_list['employee_name'];?></a></td>
									<td>
										<p class="text-dark font-weight-semibold mr-2 mb-0">
											<?php 
												$q_em_po = 'SELECT employee_position_name FROM employee_position Where employee_position_id="'.$rs_em_list['employee_position_id'].'"';
												$query_em_po = $conn->query($q_em_po);
												$rs_em_po = $query_em_po->fetch_assoc();
												echo $rs_em_po['employee_position_name'];
											?>
										</p>
									</td>
									<td><?php echo $rs_em_list['employee_tel'];?></td>
									<td><?php echo $rs_em_list['employee_email'];?></td>
									<td>
										<a href="?vp=<?php echo base64_encode('employee_form');?>&op=<?php echo base64_encode('employee_edit');?>&employee_id=<?php echo base64_encode($rs_em_list['employee_id']);?>">Edit</a>
										&nbsp;&nbsp;<a href="?vp=<?php echo base64_encode('employee_sql');?>&ac=<?php echo base64_encode('employee_del');?>&employee_id=<?php echo base64_encode($rs_em_list['employee_id']);?>" onclick="return confirm('Are you want to delete user <?php echo $rs_em_list['employee_name']?> ?')">Del</a>
									</td>
								</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

<?php
	}else{
		echo 'No One';
	}
?>
