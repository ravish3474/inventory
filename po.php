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
.tbl-input-board th{
	background-color: #EEA;
	border:1px #996 solid;
	text-align: center;

}
.tbl-input-board td{
	background-color: #FFF;
	border:1px #996 solid;
}
.total-row td{
	background-color: #777;
	color: #FFF;
	font-weight: bold;

}
</style>
<?php 
	if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}
	include('respond-button.php');
	$nYear = date('Y');
	if(isset($_GET['y'])){
		$y=base64_decode($_GET['y']);
		$ys=$y;
	}else{
		$ys=$nYear;
	}
	
	$sql_po = 'SELECT * FROM po_head WHERE po_date Like "'.$ys.'%" ';
	$query_po = $conn->query($sql_po);
	while ($rs_po = $query_po->fetch_assoc()) {
		$sql_sum_po = 'SELECT sum(po_detail_total) AS sum_po FROM po_detail where po_id="'.$rs_po['po_id'].'"';
		$query_sum_po = $conn->query($sql_sum_po);
		$rs_sum_po = $query_sum_po->fetch_assoc();

		if($rs_sum_po['sum_po']!=$rs_po['po_total']){
			$sql_up = 'UPDATE po_head SET po_total = "'.$rs_sum_po['sum_po'].'" WHERE po_id = "'.$rs_po['po_id'].'" ';
			$query = mysqli_query($conn,$sql_up);
		}
	}

	if($op=='po_list'){
?>
	<script src="assets/jquery-latest.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
	        $('select').on('change', function() {
				var url = $(this).val();
				if (url) {
					window.location = url;
				}
				return false;
			});
	    });
	</script>
		<div class="row">
			<div class="col-9">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-8">
								<h4 class="card-title">Stock IN</h4>
							</div>
							<div class="col-sm-2 text-right">
								<h4 class="card-title">Other Year</h4>
							</div>
								<div class="col-sm-2 text-right">
								<form>
									<select class="form-control" name="year" id="year" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
										<?php
										$tmp_year = intval(date("Y"));
										if( isset($_GET['y']) ){
											$tmp_year = intval(base64_decode($_GET['y']));
										}
										if( isset($_GET['all']) && $_GET['all']=="y" ){
											$tmp_year = 0;
										}
										echo '<option value="?vp='.base64_encode('po').'&op='.base64_encode('po_list').'&all=y">View All</option>';
										for($i=intval(date("Y"));$i>=2015;$i--){
											echo '<option value="?vp='.base64_encode('po').'&op='.base64_encode('po_list').'&y='.base64_encode($i).'" ';
											if($tmp_year==$i){
												echo ' selected ';
											}
											echo '>'.$i.'</option>';
										}
										
										?>
									</select>
								</form>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table id="order-listing" class="table table-hover">
										<thead>
											<tr class="bg-dark text-white">
												<th>#</th>
												<th>PO No.</th>
												<th>Pack No.</th>
												<th>INV No.</th>
												<th>SUPPLIER</th>
												<th>DATE</th>
												<th class="text-right">TOTAL</th>
												<th class="text-center">View</th>
											</tr>
										</thead>
										<tbody>
											<?php 

												$q_re_list = "SELECT receipt.receipt_id,po_head.po_no,'-' AS pack_no,'-' AS inv_no,supplier.supplier_name,receipt.receipt_date,SUM(po_head.po_total) AS sum_po,'0' AS pac_id ";
												$q_re_list .= ' FROM receipt INNER JOIN supplier ON receipt.receipt_supplier = supplier.supplier_id ';
												$q_re_list .= ' LEFT JOIN po_head ON po_head.receipt_id = receipt.receipt_id ';

												if($tmp_year!=0){
													$q_re_list .= ' WHERE receipt.receipt_date LIKE "'.$tmp_year.'-%" ';
												}
												$q_re_list .= ' GROUP BY receipt_id ';

												$q_re_list .= " UNION ";

												$q_re_list .= " SELECT '0' AS receipt_id,tbl_packing.po_no,CONCAT('PAC-',RIGHT(CONCAT('00000',tbl_packing.pac_id),6)) AS pack_no,tbl_packing.inv_no,supplier.supplier_name,LEFT(tbl_packing.add_date,10) AS receipt_date,SUM(fabric.fabric_in_total) AS sum_po,tbl_packing.pac_id ";
												$q_re_list .= ' FROM tbl_packing INNER JOIN supplier ON tbl_packing.supplier_id = supplier.supplier_id ';
												$q_re_list .= ' LEFT JOIN tbl_packing_list ON tbl_packing.pac_id = tbl_packing_list.pac_id ';
												$q_re_list .= ' LEFT JOIN fabric ON tbl_packing_list.fabric_id = fabric.fabric_id ';

												if($tmp_year!=0){
													$q_re_list .= ' WHERE tbl_packing.add_date LIKE "'.$tmp_year.'-%" ';
												}

												$q_re_list .= ' GROUP BY pack_no ';
												$q_re_list .= ' ORDER BY receipt_date DESC ';

												//echo $q_re_list;

												$query_re_list = $conn->query($q_re_list);
												$n_row = 1;
												while($rs_re_list = $query_re_list->fetch_assoc()){
											?>
													<tr>
														<td><?php echo $n_row;?></td>
														<td><?php echo $rs_re_list['po_no'];?></td>
														<td><?php echo $rs_re_list['pack_no'];?></td>
														<td><?php echo $rs_re_list['inv_no'];?></td>
														<td><?php echo $rs_re_list['supplier_name'];?></td>
														<td><?php echo '<div class="badge badge-primary">'.$rs_re_list['receipt_date'].'</div>';?></td>
														<td class="text-right">
															<?php 
															/*$sql_sum_re = 'SELECT sum(po_total) as sum_po FROM po_head where receipt_id="'.$rs_re_list['receipt_id'].'"';
															$query_sum_re = $conn->query($sql_sum_re);
															$rs_sum_re = $query_sum_re->fetch_assoc();*/
															echo number_format($rs_re_list['sum_po'],2);
															?>
																
															</td>
														<td class="text-center">
														<?php
														if($rs_re_list['pac_id']=="0"){
														?>
															<a class="v_re btn btn-light" data-id="<?php echo $rs_re_list['receipt_id'];?>"  data-toggle="modal" data-target="#viewReModal">
																<i class="mdi mdi-eye text-primary"></i>View 
															</a>
															<?php
															$sql_po = 'SELECT * FROM po_head where receipt_id="'.$rs_re_list['receipt_id'].'"';
															$query_po = $conn->query($sql_po);
															$po_count = $query_po->num_rows;
															if($po_count==''){
															?>
															<a href="?vp=<?php echo base64_encode('po_sql').'&ac='.base64_encode('po_del').'&re_id='.base64_encode($rs_re_list['receipt_id']);?>" onclick="return confirm('Are you want to delete Received No <?php echo $rs_re_list['receipt_number'];?> ?')" class="btn btn-light">
																<i class="mdi mdi-close text-danger"></i>Remove
															</a>
															<?php	
															}
														}else{
															?>
															<a class="v_pack btn btn-light" onclick="showPackDetail(<?php echo $rs_re_list["pac_id"]; ?>);" data-toggle="modal" data-target="#viewPackDocModal">
																<i class="mdi mdi-eye text-primary"></i>View 
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

								$sql_sum = 'SELECT SUBSTR(po_head.po_date,1,7) AS s_month,SUM(po_detail.po_detail_total) AS sum_total,SUM(po_detail.po_detail_piece) AS sum_recieve FROM po_head LEFT JOIN po_detail ON po_head.po_id=po_detail.po_id WHERE po_head.po_date LIKE "'.$s_year.'-%" GROUP BY s_month';
								$rs_sum = $conn->query($sql_sum);

								$a_total = array();
								for($i=1;$i<=12;$i++){
									$tmp_m = "";
									if($i<10){
										$tmp_m = "0";
									}
									$tmp_m .= $i;
									$a_total[($s_year."-".$tmp_m)]["sum_total"] = 0.0;
									$a_total[($s_year."-".$tmp_m)]["sum_recieve"] = 0;
								}

								$f_total = 0.0;
								while($row_sum = $rs_sum->fetch_assoc()){
									$a_total[($row_sum["s_month"])]["sum_total"] = $row_sum["sum_total"];
									$a_total[($row_sum["s_month"])]["sum_recieve"] = $row_sum["sum_recieve"];
									$f_total += floatval($row_sum["sum_total"]);
								}


								$sql_sum2 = "SELECT SUBSTR(tbl_packing.add_date,1,7) AS s_month,SUM(fabric.fabric_in_total) AS sum_total,SUM(fabric.fabric_in_piece) AS sum_recieve ";
								$sql_sum2 .= " FROM tbl_packing_list LEFT JOIN tbl_packing ON tbl_packing_list.pac_id=tbl_packing.pac_id ";
								$sql_sum2 .= " LEFT JOIN fabric ON tbl_packing_list.fabric_id=fabric.fabric_id ";
								$sql_sum2 .= " WHERE tbl_packing.add_date LIKE '".$s_year."-%' GROUP BY s_month ";
								$rs_sum2 = $conn->query($sql_sum2);

								while($row_sum2 = $rs_sum2->fetch_assoc()){
									$a_total[($row_sum2["s_month"])]["sum_total"] += $row_sum2["sum_total"];
									$a_total[($row_sum2["s_month"])]["sum_recieve"] += $row_sum2["sum_recieve"];
									$f_total += floatval($row_sum2["sum_total"]);
								}
								?> 
								INV Total
							</h4>
							<table class="table table-hover stat-font" >
								<thead>
									<tr>
										<th class="text-left">Month</th>
										<th class="text-right">Receive(Kg)</th>
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
										<td class="text-right"><?php echo number_format($a_total[$tmp_m]["sum_recieve"],2); ?></td>
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

		<!--Modal View Pack document-->
		<div class="modal fade" id="viewPackDocModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Packing Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="show_pack_content">
					</div>
				</div>
			</div>
		</div>

		<!--Modal View PO-->
		<div class="modal fade" id="viewReModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Recieved Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="show_re">
					</div>
				</div>
			</div>
		</div>
		<!--Modal View re-->
		<script type="text/javascript">
			$(document).on("click", ".v_re", function () {
				var eventId = $(this).data('id');
				
				$.ajax({  
					type: "POST",  
					url:"get_re.php" ,
					data: "re_id="+eventId,
					success: function(data){  
						$("#show_re").html(data);  
					}  
				});
			});

			function showPackDetail(pack_id){
				
				if(pack_id!="0"){
					$('#show_pack_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
					
					$.ajax({  
						type: "POST",  
						url:"ajax/stock_in/get_pack_detail.php" ,
						data:{
							"pac_id":pack_id
						},
						success: function(resp){  
							$("#show_pack_content").html(resp);  
						}  
					});
				}
			}

			function checkAllRows(elm){

				if($(elm).prop("checked")){
					$('.e_fabric_id').prop("checked",true);
				}else{
					$('.e_fabric_id').prop("checked",false);
				}

			}

			function editUnitPrice(pack_id){

				var num_checked = 0;
				var fabric_id_list = "";

				$('.e_fabric_id').each(function(){
					if($(this).prop("checked")){
						if(fabric_id_list==""){
							fabric_id_list = $(this).val();
						}else{
							fabric_id_list += ","+$(this).val();
						}
						num_checked++;
					}
				});

				if(num_checked==0){
					alert("Please checked at least one row.");
					return false;
				}

				if(new_unit_price = prompt("New unit price :","")){
					$.ajax({  
						type: "POST",  
						url:"ajax/stock_in/set_unit_price.php" ,
						data:{
							"fabric_id_list":fabric_id_list,
							"new_unit_price":new_unit_price
						},
						success: function(resp){
							if(resp!="fail"){
								showPackDetail(pack_id);
							}else{
								alert("ERROR: Cannot update new unit price!");
							}
						}  
					});
				}else{
					
					return false;
				}

			}

			function removeFabricRoll(pack_id,fabric_id){
				if(confirm("This action will remove Fabric roll permanently. Are you sure?")){
					$.ajax({  
						type: "POST",  
						url:"ajax/stock_in/remove_fabric.php" ,
						data:{
							"pac_id":pack_id,
							"fabric_id":fabric_id
						},
						success: function(resp){
							if(resp!="fail"){
								showPackDetail(pack_id);
							}else{
								alert("ERROR: Cannot remove this roll!");
							}
						}  
					});
				}
			}

			function editINVNo(pack_id,old_value){

				if( new_inv_no = prompt("Update INV No.",old_value) ){
					$.ajax({  
						type: "POST",  
						url:"ajax/stock_in/update_inv_no.php" ,
						data:{
							"pac_id":pack_id,
							"new_inv_no":new_inv_no
						},
						success: function(resp){
							if(resp!="fail"){
								showPackDetail(pack_id);
							}else{
								alert("ERROR: Cannot update INV No.!");
							}
						}  
					});
				}

			}

		</script>
<?php
	}
?>


	