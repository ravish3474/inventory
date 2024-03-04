<?php
if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}
$nYear = date('Y');

?>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{
	width: 50px;
}
.stat-font td{
	font-size: 12px;
	padding: 5px;
}
.stat-font th{
	font-size: 13px;
	font-weight: bold;
	padding: 5px;
}
.note-balloon{
	border: 3px solid #FFA;
	background-color: #EEE;

}

</style>
<?php

if($op=='draw_view'){
	$used_id=base64_decode($_GET['used_id']);

	$sql_draw = 'SELECT * FROM used_head where used_id="'.$used_id.'" ORDER BY used_date DESC';
	$query_draw = $conn->query($sql_draw);
	$rs_draw = $query_draw->fetch_assoc();

	$sql_sum_d = 'SELECT sum(used_detail_total) as sum_d FROM used_detail where used_id = "'.$used_id.'"';
	$query_sum_d = $conn->query($sql_sum_d);
	$rs_sum_d = $query_sum_d->fetch_assoc();
	if($rs_sum_d['sum_d']!=$rs_draw['used_total']){
		$sql_up = 'UPDATE used_head SET used_total = "'.$rs_sum_d['sum_d'].'" WHERE used_id = "'.$used_id.'" ';
		$query = mysqli_query($conn,$sql_up);
	}
?>
	<h4 style="font-size:20px; font-weight: normal;">No order code request form</h4>
	<div class="row">
		<div class="col-12 grid-margin">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="col-form-label">Code</label>
								<input type="text" name="used_code" class="form-control" value="<?php echo $rs_draw['used_code'];?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Order Code</label>
								<input type="text" name="used_order_code" class="form-control" value="<?php echo $rs_draw['used_order_code'];?>" readonly>
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="used_date" class="form-control" value="<?php echo $rs_draw['used_date'];?>" readonly>
							</div>
							<?php /* ?>
							<div class="form-group">
								<a href="?vp=<?php echo base64_encode('draw_form').'&op='.base64_encode('draw_edit').'&used_id='.base64_encode($rs_draw['used_id']);?>" class="btn btn-danger">Edit</a>
							</div>
							<?php */ ?>
						</div>
						<div class="col-md-10">
							<?php
							$sql_check_fab = 'SELECT * FROM used_detail where used_id="'.$used_id.'" AND type_id="1" ';
							$query_check_fab = $conn->query($sql_check_fab);
							$rs_check_fab = $query_check_fab->fetch_assoc();
							if($rs_check_fab['used_detail_id']!=''){
							?>
							Note: <pre style="border:1px solid #AAA;"><?php echo $rs_draw['no_order_note']; ?></pre>
							<table width="100%" class="table-bordered">
								<thead>
									<tr class="bg-dark text-white text-center">
										<th width="120">Type</th>
										<th width="150">Product</th>
										<th width="100">Color</th>
										<th>No/Size</th>
										<th>Balance</th>
										<th>Used</th>
										<th>Price</th>
										<th>Total</th>
									</tr>
									</thead>
									<tbody id="used_detail">
										<?php
										$sql_used_fab = 'SELECT * FROM used_detail where used_id="'.$used_id.'"';
										$query_used_fab = $conn->query($sql_used_fab);
										while ($rs_used_fab = $query_used_fab->fetch_assoc()) {
											if($rs_used_fab['type_id']==1){
												$sql_fab = 'SELECT * FROM fabric where fabric_id="'.$rs_used_fab['materials_id'].'"';
												$query_fab = $conn->query($sql_fab);
												$rs_fab = $query_fab->fetch_assoc();

												$cat_id=$rs_fab['cat_id'];
												$balance=$rs_fab['fabric_balance'];
												$unitType=$rs_fab['fabric_type_unit'];
											}else{
												$sql_acc = 'SELECT * FROM accessory where accessory_id="'.$rs_used_fab['materials_id'].'"';
												$query_acc = $conn->query($sql_acc);
												$rs_acc = $query_acc->fetch_assoc();	

												$cat_id=$rs_acc['cat_id'];
												$balance=$rs_acc['accessory_balance'];
												$unitType=$rs_acc['accessory_type_unit'];
											}
											
										?>
										<tr class="text-right">
											<td class="text-center">
												<?php 
												switch ($rs_used_fab['type_id']) {
													case 1:
														$type='Fabrics';
														break;
													case 2:
														$type='Accessory';
														break;
												}
												echo $type;
												?>
											</td>
											<td class="text-center">
												<?php 
												$sql_cat = 'SELECT * FROM cat where cat_id="'.$cat_id.'"';
												$query_cat = $conn->query($sql_cat);
												$rs_cat = $query_cat->fetch_assoc();
												echo $rs_cat['cat_name_en'];
												?>
											</td>
											<td class="text-center">
												<?php echo $rs_used_fab['used_detail_color'];?>
											</td>
											<td class="text-center">
												<?php 
												if($rs_used_fab['type_id']==1){echo $rs_used_fab['used_detail_no'];}else{echo $rs_used_fab['used_detail_size'];};?>
											</td>
											<td class="text-right">
												<?php echo $balance.'&nbsp;'.unitType($unitType).'&nbsp;&nbsp;';?>
											</td>
											<td>
												<?php
												echo $rs_used_fab['used_detail_used'];
												?>
											</td>
											<td>
												<?php echo number_format($rs_used_fab['used_detail_price'],2);?>
											</td>
											<td>
												<?php echo number_format($rs_used_fab['used_detail_total'],2);?>
											</td>
										</tr>
										<?php	
										}
										?>
									</tbody>
									<tr class="bg-dark text-white text-center">
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th class="text-right">
											<?php
											$sql_sum_used = 'SELECT sum(used_detail_used) as sum_used FROM used_detail where used_id="'.$used_id.'"';
											$query_sum_used = $conn->query($sql_sum_used);
											$rs_sum_used = $query_sum_used->fetch_assoc();
											if($rs_sum_used['sum_used']!=0){ echo number_format($rs_sum_used['sum_used'],2).'&nbsp;&nbsp;'; }
											?>
										</th>
										<th></th>
										<th class="text-right">
											<?php
											$sql_sum_total = 'SELECT sum(used_detail_total) as sum_total FROM used_detail where used_id="'.$used_id.'" AND type_id="1" ';
											$query_sum_total = $conn->query($sql_sum_total);
											$rs_sum_total = $query_sum_total->fetch_assoc();
											if($rs_sum_total['sum_total']!=0){ echo number_format($rs_sum_total['sum_total'],2).'&nbsp;&nbsp;'; }
											?>
										</th>
									</tr>
								</tbody>
							</table>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php 
}else{
?>

<h4 style="font-size:20px; font-weight: normal;">No order code request list</h4>
	<div class="row">
		<div class="col-9">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-8">
							
						</div>
						<div class="col-sm-2 text-right">
							<h4 class="card-title">Other Year</h4>
						</div>
						<div class="col-sm-2 text-right">
							<form>
								<select class="form-control" name="year" id="year" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
									<?php
									if(isset($_GET['y'])){
										if(isset($_GET['y'])){$y=base64_decode($_GET['y']);}
										echo '<option value="?vp='.base64_encode('draw').'&y='.base64_encode($y).'">'.$y.'</option>';
										echo '<option value="?vp='.base64_encode('draw').'&all=y">View All</option>';
									}else if(isset($_GET['all'])){
										echo '<option value="?vp='.base64_encode('draw').'&all=y">View All</option>';
									}else{
										echo '<option value="?vp='.base64_encode('draw').'&y='.base64_encode($nYear).'">'.$nYear.'</option>';
									}
									$q_year_list = 'SELECT YEAR(used_date) AS used_year from used_head GROUP BY YEAR(used_date) ORDER BY YEAR(used_date) DESC';
									$query_year_list = $conn->query($q_year_list);
									while ($rs_year_list = $query_year_list->fetch_assoc()) {
										echo '<option value="?vp='.base64_encode('draw').'&y='.base64_encode($rs_year_list['used_year']).'">'.$rs_year_list['used_year'].'</option>';
									}
									?>
								</select>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="table-responsive">
								<table id="order-listing" class="table">
									<thead>
										<tr class="bg-dark text-white text-center">
											<th>#</th>
											<th>Code</th>
											<th>Note</th>
											<th>Date</th>
											<th>Total</th>
											<th class="text-center">Process</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$q_used = 'SELECT * FROM used_head ORDER BY used_date DESC';
											$query_used = $conn->query($q_used);
											$n_row = 1;
											while($rs_used = $query_used->fetch_assoc()){
										?>
										<tr class="text-center">
											<td><?php echo $n_row; ?></td>
											<td><?php echo '<b>'.$rs_used['used_code'].'</b><br>'.$rs_used['used_order_code']; ?></td>
											<td><pre><?php echo $rs_used['no_order_note']; ?></pre></td>
											<td>
												<?php 
												echo '<div class="badge badge-primary">'.$rs_used['used_date'].'</div>';
												//$exd = explode(' ', $rs_used['used_date']);
												//echo '<br><div class="badge badge-outline-danger mt-1">'.$exd[1].'</div>';
												?>
											</td>
											<td>
												<?php
													$q_sum_t = 'SELECT SUM(used_detail_total) as SumTotal FROM used_detail WHERE used_id="'.$rs_used['used_id'].'" ';
													$query_sum_t = $conn->query($q_sum_t);
													$rs_sum_t = $query_sum_t->fetch_assoc();

													$sql_up = 'UPDATE used_head SET used_total = "'.$rs_sum_t['SumTotal'].'" WHERE used_id = "'.$rs_used['used_id'].'" ';
													$query = mysqli_query($conn,$sql_up);

													echo '<b>'.number_format($rs_sum_t['SumTotal'],2).'</b>';
												?>
											</td>
											<td class="text-center">
												<a class="btn btn-light" href="?vp=<?php echo base64_encode('draw').'&op='.base64_encode('draw_view').'&used_id='.base64_encode($rs_used['used_id']);?>">
													<i class="mdi mdi-eye text-primary"></i>View 
												</a>
												<?php 
													$q_t = 'SELECT used_detail_id FROM used_detail WHERE used_id="'.$rs_used['used_id'].'" ';
													$query_t = $conn->query($q_t);
													$rs_t = $query_t->fetch_assoc();
													if($rs_t['used_detail_id']==''){
												?>
													<a href="?vp=<?php echo base64_encode('draw_sql').'&ac='.base64_encode('draw_del').'&used_id='.base64_encode($rs_used['used_id']);?>" onclick="return confirm('Do you want to delete <?php echo $rs_used['used_code'];?> ?')" class="btn btn-light">
														<i class="mdi mdi-close text-danger"></i>Delete
													</a>
												<?php		
													}
												?>
											</td>
										</tr>
										<?php
												$n_row++;
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
		<div class="col-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<h4 class="card-title">
								<?php 
								if(isset($_GET['y'])){$s_year=$y;}else{$s_year=$nYear;}
								echo $s_year;

								$sql_sum = 'SELECT SUBSTR(used_head.used_date,1,7) AS s_month,SUM(used_detail.used_detail_total) AS sum_total,SUM(used_detail.used_detail_used) AS sum_used FROM used_head LEFT JOIN used_detail ON used_head.used_id=used_detail.used_id WHERE used_head.used_date LIKE "'.$s_year.'-%" GROUP BY s_month';
								$rs_sum = $conn->query($sql_sum);

								$a_total = array();
								for($i=1;$i<=12;$i++){
									$tmp_m = "";
									if($i<10){
										$tmp_m = "0";
									}
									$tmp_m .= $i;
									$a_total[($s_year."-".$tmp_m)]["sum_total"] = 0.0;
									$a_total[($s_year."-".$tmp_m)]["sum_used"] = 0;
								}

								$f_total = 0.0;
								while($row_sum = $rs_sum->fetch_assoc()){
									$a_total[($row_sum["s_month"])]["sum_total"] = $row_sum["sum_total"];
									$a_total[($row_sum["s_month"])]["sum_used"] = $row_sum["sum_used"];
									$f_total += floatval($row_sum["sum_total"]);
								}
								?> 
								USED Total
							</h4>
							<table class="table table-hover stat-font">
								<thead>
									<tr>
										<th class="text-center">Month</th>
										<th class="text-right">Used(Kg)</th>
										<th class="text-right">Cost</th>
									</tr>
								</thead>
								<tbody>
									
									<?php
									for($i=1;$i<=12;$i++){
										$tmp_m = "";
										if($i<10){
											$tmp_m = "0";
										}
										$tmp_m .= $i;
										$tmp_m = $s_year."-".$tmp_m;

										$show_month = date("M",strtotime($tmp_m."-01"));
									?>
									<tr>
										<td class="text-center"><?php echo $show_month; ?></td>
										<td class="text-right"><?php echo number_format($a_total[$tmp_m]["sum_used"],2); ?></td>
										<td class="text-right"><?php echo number_format($a_total[$tmp_m]["sum_total"],2); ?></td>
									</tr>
									<?php
									}
									?>
									<tr>
										<td colspan="2" class="text-right">&nbsp;&nbsp;Total&nbsp;&nbsp; </td>
										<td class="text-right"><b><?php echo number_format($f_total,2); ?></b></td>
									</tr>
								</tbody>
							</table>
							
						</div>
					</div>
				</div>
			</div>
	</div>
<script type="text/javascript">
function showNote(used_id){
	$('#note'+used_id).fadeIn(100);
}

function hideNote(used_id){
	$('#note'+used_id).fadeOut(100);
}
</script>
<?php
}
?>