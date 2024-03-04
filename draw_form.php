<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	jQuery(function($) {
        jQuery('body').on('change','#type_id',function(){
            jQuery.ajax({
                'type':'POST',
                'url':'get_type.php?op=type',
                'cache':false,
                'data':{type_id:jQuery(this).val()},
                'success':function(html){
                    jQuery("#cat_id").html(html);
                }
            });
            return false;
        });
        $("#btnSearch").click(function() {
        	$.ajax({
				type: "POST",
        		url: "get_type.php?op=search",
        		data: $("#formSearch").serialize(),
        		success: function(html) {
        			jQuery("#show_materials").html(html);
        		}
        	});
        });
    });
</script>
<style type="text/css">
.container-fluid {
	padding-left: 0px;
}
div.dataTables_wrapper div.dataTables_length select{
	width: 50px;
}

.form-control{
	font-size: 12px;
}
.badge {
	font-size: 11px;
}
.dynamic-zone{
	border-bottom: solid 2px #88F;
}
.tbl-copy-content td{
	font-size: 14px;
	padding: 2px 4px;
}
</style>

<?php

if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}

if($op=='draw_edit'){
	$used_id=base64_decode($_GET['used_id']);

	$sql_draw = 'SELECT * FROM used_head where used_id="'.$used_id.'"';
	$query_draw = $conn->query($sql_draw);
	$rs_draw = $query_draw->fetch_assoc();

	$sql_sum_d = 'SELECT sum(used_detail_total) as sum_d FROM used_detail where used_id = "'.$used_id.'"';
	$query_sum_d = $conn->query($sql_sum_d);
	$rs_sum_d = $query_sum_d->fetch_assoc();
	if($rs_sum_d['sum_d']!=$rs_draw['used_total']){
		$sql_up = 'UPDATE used_head SET used_total = "'.$rs_sum_d['sum_d'].'" WHERE used_id = "'.$used_id.'" ';
		$query = mysqli_query($conn,$sql_up);
	}

	$b_is_new = false;

	if($rs_sum_d['sum_d']==""){
		$b_is_new = true;
	}

	
?>
<h4 style="font-size:20px; font-weight: normal;">No order code requisition Edit</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-2">
						<form action="?vp=<?php echo base64_encode('draw_sql');?>&ac=<?php echo base64_encode('draw_update');?>" method="post">
							
							<div class="form-group">
								<label class="col-form-label">Code</label>
								<input type="text" name="used_code" class="form-control" value="<?php echo $rs_draw['used_code'];?>" readonly>
								<input type="hidden" name="used_order_code" class="form-control" value="<?php echo $rs_draw['used_order_code'];?>" >
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="used_date" class="form-control" value="<?php echo $rs_draw['used_date'];?>" readonly>
							</div>
							<div class="form-group">
								<input type="hidden" name="used_id" value="<?php echo $rs_draw['used_id'];?>">
								<?php
								if($b_is_new){
								?>
								<a href="?vp=<?php echo base64_encode('draw_sql').'&ac='.base64_encode('draw_del').'&used_id='.base64_encode($used_id);?>" onclick="return confirm('Do you want to delete <?php echo $rs_draw['used_code'];?> ?')" class="btn btn-danger btn-block">
									<i class="mdi mdi-close text-white"></i>Delete
								</a>
								<?php		
								}
								?>

							</div>
						</form>	
					</div>
					<div class="col-md-10">
						<form action="?vp=<?php echo base64_encode('draw_sql');?>&ac=<?php echo base64_encode('draw_detail_update');?>" method="post">
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
										<th>Del</th>
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
											<?php echo number_format($balance,2).'&nbsp;'.unitType($unitType).'&nbsp;&nbsp;';?>
										</td>
										<td>
											<?php
											echo '<input type="text" class="form-detail" name="used_detail_used[]" value="'.$rs_used_fab['used_detail_used'].'">';
											?>
											<input type="hidden" name="type_id[]" value="<?php echo $rs_used_fab['type_id'];?>">
											<input type="hidden" name="used_detail_id[]" value="<?php echo $rs_used_fab['used_detail_id'];?>">
											<input type="hidden" name="materials_id[]" value="<?php echo $rs_used_fab['materials_id'];?>">
											<input type="hidden" name="used_detail_unit_type[]" value="<?php echo $rs_used_fab['used_detail_unit_type'];?>">
										</td>
										<td>
											<?php echo number_format($rs_used_fab['used_detail_price'],2);?>
										</td>
										<td>
											<?php echo number_format($rs_used_fab['used_detail_total'],2);?>
										</td>
										<td class="text-center">
											<a href="?vp=<?php echo base64_encode('draw_sql').'&ac='.base64_encode('draw_detail_del').'&used_id='.base64_encode($used_id).'&used_detail_id='.base64_encode($rs_used_fab['used_detail_id']);?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this row. Confirm?')">Del</a>
										</td>
									</tr>
									<?php	
									}
									?>
								</tbody>
							</table>
							<input type="hidden" name="used_id" value="<?php echo $used_id;?>">
							<button type="submit" class="btn btn-primary btn-block">Update Used data</button>
						</form>
						<hr>
						<form method="post" name="formSearch" id="formSearch">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										Select Type
										<select class="form-control" name="type_id" id="type_id">
											<option value="">Select Type</option>
											<option value="1">Fabrics</option>
											<option value="2">Accessory</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										Select Materials
										<select class="form-control" name="cat_id" id="cat_id">
											<option value="">Select Materials</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									Search Detail
									<input type="button" name="btnSearch" id="btnSearch" value="View" class="btn btn-warning btn-block">
								</div>
							</div>
						</form>

						<form action="?vp=<?php echo base64_encode('draw_sql');?>&ac=<?php echo base64_encode('draw_detail_add');?>" method="post">
							<div class="row" id="show_materials">
							</div>
							<hr>
							<input type="hidden" name="used_id" value="<?php echo $used_id;?>">
							<button type="submit" class="btn btn-success btn-block">Add selected rows</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php

}else{
	$strDate = date('Y-m-d H:i:s');
	$used_code = 'OR-'.date('ymdHi');
?>
<h4 style="font-size:20px; font-weight: normal;">Requisition with no order code</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<form action="?vp=<?php echo base64_encode('draw_sql');?>&ac=<?php echo base64_encode('draw_add');?>" method="post">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="col-form-label">Auto generate code</label>
								<input type="text" name="used_code" class="form-control" value="<?php echo $used_code;?>" required readonly>
								<input type="hidden" name="select_order" value="no-code">
							</div>
							<div class="form-group">
								<label class="col-form-label">Date</label>
								<input type="text" name="used_date" class="form-control" value="<?php echo $strDate;?>" required readonly>
							</div>
						</div>
						<div class="col-md-10">
							<div id="show_note_box" class="form-group">
								<label class="col-form-label">Note</label><br>
								<textarea name="no_order_note" id="no_order_note" rows="7" cols="100"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<button type="submit" class="btn btn-success" style="width: 100%;" onclick="return checkFormFill();">Please input note and click here to go to next step</button>
								
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php }?>

<script type="text/javascript">

function checkFormFill(){
	if($('#no_order_note').val()==""){
		alert("Please input Note");
		return false;
	}
}

</script>