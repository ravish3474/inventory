<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{
	width: 50px;
}
.tab_status-active{
	background-color: #28a745 !important;
}
.tab_status-active:hover{
	background-color: #48C765 !important;
}
.tab_status{
	width: 100%;
	font-weight: bold;
	background-color: #999;
	color: #FFF;
}
.tab_status:hover{
	background-color: #BBB;
	color: #FFF;
}
.act-btn{
	padding: 10px !important;
	color: #FFF !important;
	font-weight: bold;
}
.tbl_rq_form th{
	background-color: #333;
	text-align: center;
	color: #FFF;
	border: 1px #CCC solid; 
}

.tbl_rq_form td{
	
	text-align: center;
	border: 1px #CCC solid; 
}
#rq_content.modal-body{
	padding-top: 0px;
}

.show-content{
	border-bottom: 1px dashed #AAA;
	padding: 5px;
}
.total-row{
	font-weight: bold;
	border-bottom: 3px #000 double;
}
.subtotal-row{
	font-weight: bold;
	font-size: 15px;
	border-top: 2px #000 solid;
	border-bottom: 3px #000 double;
	background-color: #F6F69B;
}
.subhead-row{
	font-weight: bold;
	font-size: 15px;
	background-color: #777;
	color: #FFF;
	height: 40px;
}
.tbl-search-content th{
	background-color: #333;
	color: #FFF;
	border:#999 1px solid;
	padding: 4px;
}
.tbl-search-content td{
	border:#999 1px solid;
	padding: 4px;
	font-size: 13px;
}
.tbl-search-content tr{
	background-color: #FFF;
}
.tbl-search-content tr:hover{
	background-color: #DDD;
}
#right_panel{
	border:1px solid #999;
	box-shadow: 0 5px 5px -1px rgba(0, 0, 0, 0.4);
	padding: 5px;
}
.report_tbl{
	width: 100%;

}
.report_tbl th{
	background-color: #AEE;
	border-bottom: solid 1px #DDD;
	text-align: center;
	font-size: 12px;
	font-weight: bold;
	padding: 5px 10px;
}
.report_tbl td{
	border-bottom: solid 1px #DDD;
	text-align: center;
	font-size: 12px;
	padding: 15px 10px;
}
#rq-listing th{
	padding: 10px 5px;
	text-align: center;
}
#rq-listing td{
	padding: 10px 5px;
	text-align: center;
}
</style>
<?php
if( !isset($op) || ($op=="") ){
	$op = "new";
}

?>
<h4 style="font-size:20px; font-weight: normal;">Material request list</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<fieldset>
					<legend>
						<div class="row">
							<div class="col-2" style="padding: 0px; margin:0px;">
								<a class="btn tab_status <?php if($op=="new"){ echo "tab_status-active"; } ?>" href="?vp=<?php echo base64_encode('rq_list');?>&op=<?php echo base64_encode('new');?>">PRODUCING</a>
							</div>
							<div class="col-2" style="padding: 0px 0px 0px 3px; margin:0px;">
								<a class="btn tab_status <?php if($op=="finish"){ echo "tab_status-active"; } ?>" href="?vp=<?php echo base64_encode('rq_list_finish');?>&op=<?php echo base64_encode('finish');?>">FINISH</a>
							</div>
							<!--<div class="col-2" style="padding: 0px 0px 0px 3px; margin:0px;">-->
							<!--	<a class="btn tab_status <?php if($op=="finish"){ echo "tab_status-active"; } ?>" href="?vp=<?php echo base64_encode('rq_list');?>&op=<?php echo base64_encode('finish');?>">FINISH</a>-->
							<!--</div>-->
							<div class="col-1">
								<?php
								if($op=="new"){
								?>
								<button class="btn btn-secondary" data-toggle="modal" data-target="#searchRQModal" onclick="searchBoxChange();"><i class="fa fa-search "></i></button>
								<?php
								}
								?>
							</div>
							<div class="col-7">
							</div>
						</div>
					</legend>
					<div class="row">
						<div id="left_panel" class="col-9">
							<div class="table-responsive">
							<table width="100%" id="rq-listing" class="table">
								<thead>
									<tr class="bg-dark text-white">
										<th >#</th>
										<th style="text-align: left;">Order Code</th>
										<th >RQ Date</th>
										<?php
										if( $op=="finish" ){
										?>
										<th >Finish</th>
										<?php
										}
										?>
										<th >Items</th>
										<?php
										if( $op=="finish" ){
										?>
										<th style="text-align: right;">Used(Kg)</th>
										<th style="text-align: right;">Total</th>
										<?php
										}
										?>
										<th >Status</th>
										<th >User</th>
										<?php
										if( $op=="finish" ){
										?>
										<th>Add-on</th>
										<?php
										}
										?>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$n_count_row = 1;
								$sql_select = "SELECT tbl_rq_form.*,COUNT(*) AS item_num,employee.employee_name ";
								if( $op=="finish" ){
									$sql_select .= " ,SUM((tbl_rq_form_item.balance_before-tbl_rq_form_item.balance_after)) AS g_used ";
									$sql_select .= " ,SUM((tbl_rq_form_item.balance_before-tbl_rq_form_item.balance_after)*fabric.fabric_in_price) AS g_total ";
								}
								$sql_select .= " FROM tbl_rq_form LEFT JOIN tbl_rq_form_item ON tbl_rq_form.rq_id=tbl_rq_form_item.rq_id ";
								$sql_select .= " LEFT JOIN employee ON employee.employee_id=tbl_rq_form.employee_id ";
								if( $op=="finish" ){
									$sql_select .= " LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";
								}

								$sql_select .= " WHERE tbl_rq_form.enable=1 ";

								if( $op=="new" ){
									$sql_select .= " AND (tbl_rq_form.rq_status='new' OR tbl_rq_form.rq_status='update') ";
								}else{
									$sql_select .= " AND tbl_rq_form.rq_status='".$op."' AND tbl_rq_form.is_addon=0 ";
								}
								$sql_select .= " GROUP BY tbl_rq_form.rq_id ORDER BY tbl_rq_form.finish_date DESC,tbl_rq_form.rq_date DESC; ";
								//echo $sql_select;
								$rs_select = $conn->query($sql_select);
								$a_rq = array();
								while ($row_select = $rs_select->fetch_assoc()) {

									?>
									<tr>
										<td ><?php echo $n_count_row; ?></td>
										<td style="text-align: left;" id="show_order_code<?php echo $row_select["rq_id"]; ?>">
											<?php 
											echo $row_select["order_code"]; 
											$a_rq[($row_select["rq_id"])]["order_code"] = $row_select["order_code"];
											if($row_select["is_addon"]=="1"){
												echo " (Add-on)";
												$a_rq[($row_select["rq_id"])]["order_code"] .= " (Add-on)";
											}

											$a_rq[($row_select["rq_id"])]["rq_date"] = $row_select["rq_date"];
											$a_rq[($row_select["rq_id"])]["rq_status"] = strtoupper($row_select["rq_status"]);
											?>
										</td>
										<td ><div id="show_rq_date<?php echo $row_select["rq_id"]; ?>" class="badge badge-primary"><?php echo $row_select["rq_date"]; ?></div></td>
										<?php
										if( $op=="finish" ){
										?>
										<td ><div id="show_finish_date<?php echo $row_select["rq_id"]; ?>" class="badge badge-primary"><?php echo $row_select["finish_date"]; ?></div></td>
										<?php
										}
										?>
										<td ><?php echo $row_select["item_num"]; ?></td>
										<?php
										if( $op=="finish" ){
										?>
										<td style="text-align: right;"><?php echo number_format($row_select["g_used"],2); ?></td>
										<td style="text-align: right;"><?php echo number_format($row_select["g_total"],2); ?></td>
										<?php
										}
										?>
										<td id="show_rq_status<?php echo $row_select["rq_id"]; ?>"><?php echo strtoupper($row_select["rq_status"]); ?></td>
										<td ><?php echo $row_select["employee_name"]; ?></td>
										<?php
										if( $op=="finish" ){
											$sql_select2 = "SELECT COUNT(*) AS addon_num FROM tbl_rq_form WHERE order_code='".$row_select["order_code"]."' AND is_addon=1 AND rq_status='finish'; ";
											$rs_select2 = $conn->query($sql_select2);
											$row_select2 = $rs_select2->fetch_assoc();

										?>
										<td ><?php echo $row_select2["addon_num"]; ?></td>
										<?php
										}
										?>
										<td >
											<a class="btn btn-primary act-btn" data-toggle="modal" data-target="#editRQModal" onclick="editRQ(<?php echo $row_select["rq_id"]; ?>);">
											View
											<?php if( $row_select["rq_status"]=="new" || $row_select["rq_status"]=="update" ){ echo " | Edit"; }?>
											</a>
										<?php
										if( $row_select["rq_status"]=="new" || $row_select["rq_status"]=="update" ){
										?>
											<a class="btn btn-success act-btn" data-toggle="modal" data-target="#finishRQModal" onclick="finishRQ(<?php echo $row_select["rq_id"]; ?>);">Finish</a>
										<?php
											
										}
										?>
										</td>
									</tr>
									<?php
									$n_count_row++;
								}
								?>
								</tbody>
							</table>
							</div>
						</div>
						<div id="right_panel" class="col-3" style="overflow-x: scroll;">
							<div style="float: left;" id="y_select">
								<select id="see_year" onchange="return changeSeeYear();">
									<?php //for($y=intval(date("Y")); $y>=2019; $y--){
									for($y=2022; $y>=2019; $y--){
									?>
									<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
									<?php } ?>
								</select> USED Total
							</div>
							<input type="hidden" id="switch_hide_onoff" value="on">
							<button style="float: right;" class="btn btn-secondary" onclick="return hidePanel();" id="btn_arrow"><i class="fa fa-arrow-right"></i></button>
							<div style="height: 40px;"></div>
							<div id="report_table" ></div>
						</div>
					</div>
				</fieldset>
				
			</div>
		</div>
	</div>
</div>

<!--Modal Edit-->
<div class="modal fade" id="editRQModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99997;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Material Requisition Form</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="rq_content">
				
				<div class="row">
					<div class="col-2" style="border-right:#CCC solid 1px;">
						<div class="row">
							<div class="col-12 text-left"><b>Order Code</b></div>
							<div class="col-12 text-center show-content" id="d_order_code"></div>
							<div class="col-12 text-left"><b>Date</b></div>
							<div class="col-12 text-center show-content" id="d_rq_date"></div>
							<div class="col-12 text-left finish_date_zone"><b>Finish</b></div>
							<div class="col-12 text-center show-content finish_date_zone" id="d_finish_date"></div>
							<div class="col-12 text-left"><b>Status</b></div>
							<div class="col-12 text-center show-content" id="d_rq_status"></div>
							
						</div>
					</div>
					<div class="col-10" style="min-height: 200px;">
						<input type="hidden" id="edit_rq_id" value="">
						<table class="tbl_rq_form" width="100%">
							<thead>
								<tr>
									<th>Material</th><th>Color</th><th>Box</th><th>No.</th>
									<?php 
									if($op=="new"){
									?>
									<th>Balance</th><th>&nbsp;</th>
									<?php
									}else{
										echo '<th>BAL before</th><th>BAL after</th><th>Used (Kg)</th><th>Unit Price</th><th>Total</th><th>Note</th>';
									}
									?>

								</tr>
							</thead>
							<tbody id="tbo_content" >
								
							</tbody>
						</table>
						<?php 
						$s_cat_option = "";
						if($op=="new"){
						?>
						<hr>
						<div class="row" style="height: 50px;">
							<div class="col-3 text-center">
								Fabric: <select id="select_cat_id" onchange="catChange();">
									<option value="none">==Select==</option>
									<?php
									
									$sql_cat = "SELECT * FROM cat ORDER BY cat_name_en ASC; ";
									$rs_cat = $conn->query($sql_cat);
									while ($row_cat = $rs_cat->fetch_assoc()) {
										echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
										$s_cat_option .= '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-4 text-center">
								Color: <select id="select_color" onchange="colorChange();">
									<option value="none">=Select=</option>
								</select>
								<span id="select_color_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
							</div>
							<div class="col-3 text-center">
								Box/No. <select id="select_fabric_id">
									<option value="none">=Select=</option>
								</select>
								<span id="select_fabric_id_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
							</div>
							<div class="col-2 text-center">
								<input class="btn btn-primary" type="button" id="btn_add" onclick="return addRQRow();" value=" Add ">
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>
				<?php 
				if($op=="new"){
				?>
				<div class="row">
					<div class="col-2 text-center">
						<div class="form-group">
							<button id="btn_del_rq" type="button" class="btn btn-danger" style="width: 100%; font-size: 18px;" onclick="return deleteRQEditing();">Delete RQ</button>
						</div>
					</div>
					<div class="col-10 text-center">
						<div class="form-group">
							
							<button type="button" class="btn btn-success" style="width: 100%; font-size: 18px;" onclick="return saveRQEditing();">Submit Requisition form editing</button>
							
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<!--Modal Edit-->

<!--Modal Finish-->
<div class="modal fade" id="finishRQModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99997;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Finish requisition Form</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="rq_content_finish">
				<form id="form_rq_finish" method="post" action="" target="hidden_frame">
				<div class="row">
					<div class="col-2" style="border-right:#CCC solid 1px;">
						<div class="row">
							<div class="col-12 text-left"><b>Order Code</b></div>
							<div class="col-12 text-center show-content" id="d_order_code_finish"></div>
							<div class="col-12 text-left"><b>Date</b></div>
							<div class="col-12 text-center show-content" id="d_rq_date_finish"></div>
							<div class="col-12 text-left"><b>Status</b></div>
							<div class="col-12 text-center show-content" id="d_rq_status_finish"></div>
							
						</div>
					</div>
					<div class="col-10" style="min-height: 200px;">
						<input type="hidden" name="finish_rq_id" id="finish_rq_id" value="">
						<table class="tbl_rq_form" width="100%">
							<thead>
								<tr>
									<th>Material</th><th>Color</th><th>Box</th><th>No.</th><th>Balance before</th><th>Balance after</th><th>Note</th>
								</tr>
							</thead>
							<tbody id="tbo_content_finish" >
								
							</tbody>
						</table>
						
					</div>
				</div>
				<hr>
				<div class="row" id="d_btn_zone">
					<div class="col-1 text-center">
						&nbsp;
					</div>
					<div class="col-3 text-center">
						<div class="form-group">
							
							<button type="button" class="btn btn-warning" style="width: 100%; font-size: 18px;" onclick="return savePartialCut();">Save partial cut stock</button>
							
						</div>
					</div>
					
					<div class="col-4 text-center">
						<div class="form-group">
							
							<button type="button" class="btn btn-info" style="width: 100%; font-size: 18px;" onclick="return saveRQNotFinish();">Cut stock all but not finished</button>
							
						</div>
					</div>
					<div class="col-3 text-center">
						<div class="form-group">
							
							<button type="button" class="btn btn-success" style="width: 100%; font-size: 18px;" onclick="return saveRQFinishing();">Cut stock all and set Finish</button>
							
						</div>
					</div>
					<div class="col-1 text-center">
						&nbsp;
					</div>
				</div>
				<div class="row" id="d_btn_zone_just_finish" style="display: none;">
					
					<div class="col-3 text-center">
						&nbsp;
					</div>
					<div class="col-6 text-center">
						<div class="form-group">
							
							<button type="button" class="btn btn-primary" style="width: 100%; font-size: 18px;" onclick="return setRQStatusFinish();">Set Finish</button>
							
						</div>
					</div>
					<div class="col-3 text-center">
						&nbsp;
					</div>
				</div>

				</form>
			</div>
		</div>
	</div>
</div>
<!--Modal Finish-->
<iframe name="hidden_frame" width="0" height="0" style="display: none;"></iframe>

<!--Modal Search-->
<div class="modal fade" id="searchRQModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99996;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="padding-bottom: 10px;">
				<h4 class="modal-title">Search materials in PRODUCING</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="producing_mat_content" style="padding: 5px 5px;">
				<div class="row" style="padding: 5px 5px; border: solid 1px #999; border-radius: 5px; margin: 0px 20px; background-color: #DDD;">
					<div class="col-12 text-center" style="margin-bottom: 5px;">
						Fabric: 
						<select id="s_select_cat_id" onchange="searchCatChange();">
							<option value="=all=">=All=</option>
							<?php
							echo $s_cat_option;
							?>
						</select>
					</div>
					<div class="col-7">
						Color: 
						<select id="s_select_color" onchange="searchColorChange();">
							<option value="=all=">=All=</option>
						</select>
						<span id="s_select_color_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
					</div>
					<div class="col-5">
						
						Box: 
						<select id="s_select_box" onchange="searchBoxChange();">
							<option value="=all=">=All=</option>
						</select>
						<span id="s_select_box_loading" style="display: none;"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</span>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-left" >
						<div style="height: 450px; overflow-y: scroll;">
							<br>
							<table width="100%" class="tbl-search-content">
								<thead>
									<tr>
										<th>Material</th><th>Color</th><th>Box</th><th>No.</th><th>Order code</th>
									</tr>
								</thead>
								<tbody id="tbod_s_content">

									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Modal Search-->

<script type="text/javascript">
changeSeeYear();

function changeSeeYear(){
	
	var y_select = $('#see_year').val();

	$('#report_table').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_yearly_used.php" ,
		data:{
			"y_select":y_select
		},
		success: function(resp){  
			
			$('#report_table').html(resp);
			
		}  
	});
	
}

function hidePanel(){
	var switch_value = $('#switch_hide_onoff').val();

	if(switch_value=="on"){
		$('#right_panel').removeClass("col-3").addClass("col-1");
		$('#left_panel').removeClass("col-9").addClass("col-11");
		$('#switch_hide_onoff').val("off");

		$('#y_select').hide();
		//$('#report_table').hide();
		$('#btn_arrow').html('<i class="fa fa-arrow-left"></i>');

	}else{

		$('#right_panel').removeClass("col-1").addClass("col-3");
		$('#left_panel').removeClass("col-11").addClass("col-9");
		$('#switch_hide_onoff').val("on");

		$('#y_select').show();
		//$('#report_table').show();
		$('#btn_arrow').html('<i class="fa fa-arrow-right"></i>');
	}
}

function searchCatChange(){

	$('#s_select_color').html('');
	$('#s_select_color_loading').show();

	var cat_id = $('#s_select_cat_id').val();
	
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_color_select.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){  
			
			$('#s_select_color_loading').hide();
			$('#s_select_color').html('<option value="=all=">=All=</option>');
			$('#s_select_color').append(resp);
			searchColorChange();
			
		}  
	});
	
}

function searchColorChange(){

	$('#s_select_box').html('');
	$('#s_select_box_loading').show();

	var cat_id = $('#s_select_cat_id').val();
	var color_name = $('#s_select_color').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_box_select.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name
		},
		success: function(resp){  
			
			$('#s_select_box_loading').hide();
			$('#s_select_box').html('<option value="=all=">=All=</option>');
			$('#s_select_box').append(resp);
			searchBoxChange();

		}  
	});
}

function searchBoxChange(){

	$('#tbod_s_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var cat_id = $('#s_select_cat_id').val();
	var color_name = $('#s_select_color').val();
	var box = $('#s_select_box').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_producing_roll.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name,
			"fabric_box":box
		},
		success: function(resp){  
			
			$('#tbod_s_content').html(resp);

		}  
	});
}
	
function catChange(){

	$('#select_color').html('');
	$('#select_fabric_id').html('');
	$('#select_color_loading').show();
	$('#select_fabric_id_loading').show();

	var cat_id = $('#select_cat_id').val();
	
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_color_select.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){  
			
			$('#select_color_loading').hide();
			$('#select_color').html(resp);
			colorChange();
			
		}  
	});
	
}

function colorChange(){

	$('#select_fabric_id').html('');
	$('#select_fabric_id_loading').show();

	var cat_id = $('#select_cat_id').val();
	var color_name = $('#select_color').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/requisition/get_box_no_select.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name
		},
		success: function(resp){  
			
			$('#select_fabric_id_loading').hide();
			$('#select_fabric_id').html(resp);

		}  
	});
}

function addRQRow(){

	var fabric_id = $('#select_fabric_id').val();

	if(fabric_id=="none"){

		alert("Please select Box/No.");
		return false;
	}

	var fabric_id_list = $('#fabric_id_list').val();

	var tmp_id = fabric_id_list.split(",");
	for(var i=0; i<tmp_id.length; i++){
		if(tmp_id[i]==fabric_id){
			alert("Duplicate data!!");
			return false;
		}
	}

	$.ajax({  
		type: "POST",
		dataType: "html",
		url:"ajax/requisition/get_balance.php" ,
		data:{
			"fabric_id":fabric_id
		},
		success: function(resp){
			
			if(fabric_id_list!=""){
				fabric_id_list += ",";
			}
			fabric_id_list += fabric_id;
			$('#fabric_id_list').val(fabric_id_list);

			$('#tbo_content').append(resp);

		}  
	});
}

function removeMaterial(fabric_id,tmp_id){

	var fabric_id_list = $('#fabric_id_list').val();

	var tmp_list = fabric_id_list.split(",");

	if(tmp_list.length==1){
		fabric_id_list = fabric_id_list.replace(fabric_id, "");
	}else{
		fabric_id_list = fabric_id_list.replace(fabric_id+",", "");
		fabric_id_list = fabric_id_list.replace(","+fabric_id+",", ",");
		fabric_id_list = fabric_id_list.replace(","+fabric_id, "");
	}

	$('#fabric_id_list').val(fabric_id_list);
	
	$('#tr_row'+fabric_id+tmp_id).hide();
}

function editRQ(rq_id){

	var rq_data = {

	<?php
	foreach($a_rq as $key_rq_id => $val_data){

		echo $key_rq_id; ?>:{ 
			order_code:"<?php echo $val_data["order_code"]; ?>",rq_date:"<?php echo $val_data["rq_date"]; ?>",rq_status:"<?php echo $val_data["rq_status"]; ?>" 
		},
		<?php
	}
	?>

	};

	var order_code = rq_data[rq_id].order_code;
	var rq_date = rq_data[rq_id].rq_date;
	var rq_status = rq_data[rq_id].rq_status;

	if(rq_status=="UPDATE"){
		$('#btn_del_rq').hide();
	}else{
		$('#btn_del_rq').show();
	}

	$('#edit_rq_id').val(rq_id);

	$('#d_order_code').html(order_code);
	$('#d_rq_date').html(rq_date);
	$('#d_rq_status').html(rq_status);
	if(rq_status=="FINISH"){
		$('#d_finish_date').html($('#show_finish_date'+rq_id).html());
		$('.finish_date_zone').show();
	}else{
		$('#d_finish_date').html('');
		$('.finish_date_zone').hide();

	}

	$('#tbo_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",
		dataType: "html",
		url:"ajax/requisition/get_balance_list.php" ,
		data:{
			"rq_id":rq_id
		},
		success: function(resp){

			$('#tbo_content').html(resp);

		}  
	});

}

function saveRQEditing(){

	var fabric_id_list = $('#fabric_id_list').val();
	var rq_id = $('#edit_rq_id').val();

	//alert("fabric_id_list="+fabric_id_list+"\nrq_id="+rq_id);

	$.ajax({  
		type: "POST",
		dataType: "json",
		url:"ajax/requisition/save_rq_form_edit.php" ,
		data:{
			"rq_id":rq_id,
			"fabric_id_list":fabric_id_list
		},
		success: function(resp){

			if(resp.result=="success"){
				window.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list'); ?>");
			}else{
				alert(resp.result);
			}
			
		}  
	});

}

function deleteRQEditing(){
	var rq_id = $('#edit_rq_id').val();

	if(confirm("Are you sure?")){
		$.ajax({  
			type: "POST",
			dataType: "json",
			url:"ajax/requisition/delete_rq_form.php" ,
			data:{
				"rq_id":rq_id
			},
			success: function(resp){

				if(resp.result=="success"){
					window.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list'); ?>");
				}else{
					alert(resp.result);
				}
				
			}  
		});
	}
}

function finishRQ(rq_id){

	var order_code = $('#show_order_code'+rq_id).html();
	var rq_date = $('#show_rq_date'+rq_id).html();
	var rq_status = $('#show_rq_status'+rq_id).html();

	$('#finish_rq_id').val(rq_id);

	$('#d_order_code_finish').html(order_code);
	$('#d_rq_date_finish').html(rq_date);
	$('#d_rq_status_finish').html(rq_status);

	$('#tbo_content_finish').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$('#d_btn_zone_just_finish').hide();
	$('#d_btn_zone').hide();

	$.ajax({  
		type: "POST",
		dataType: "html",
		url:"ajax/requisition/get_balance_list_finish.php" ,
		data:{
			"rq_id":rq_id
		},
		success: function(resp){

			$('#tbo_content_finish').html(resp);

			setTimeout(function(){
				var not_mark_cut = $('#not_mark_cut').val();
				if(not_mark_cut==0){
					
					$('#d_btn_zone_just_finish').show();
					$('#d_btn_zone').hide();
				}else{
					$('#d_btn_zone_just_finish').hide();
					$('#d_btn_zone').show();
				}

			}, 1000);
		}  
	});

}

function setRQStatusFinish(){
	$('#form_rq_finish').attr("action","ajax/requisition/set_rq_finish.php");
	$('#form_rq_finish').submit();
}

function saveRQFinishing(){

	var check_pass = true;

	$('.input_after_bal').each(function(){

		if($(this).val()==""){

			alert("Please input data!");
			$(this).focus();
			check_pass = false;
			return false;
		}else if( parseFloat($(this).val())>parseFloat($(this).attr("max")) ){

			alert("Not allow input more than maximum!");
			$(this).focus();
			check_pass = false;
			return false;
		}

	});

	if(check_pass){
		$('#form_rq_finish').attr("action","ajax/requisition/submit_rq_finish.php");
		$('#form_rq_finish').submit();
	}

}

function saveRQNotFinish(){

	var check_pass = true;

	$('.input_after_bal').each(function(){

		if($(this).val()==""){

			alert("Please input data!");
			$(this).focus();
			check_pass = false;
			return false;
		}else if( parseFloat($(this).val())>parseFloat($(this).attr("max")) ){

			alert("Not allow input more than maximum!");
			$(this).focus();
			check_pass = false;
			return false;
		}

	});

	if(check_pass){
		$('#form_rq_finish').attr("action","ajax/requisition/submit_rq_not_finish.php");
		$('#form_rq_finish').submit();
	}
}

function savePartialCut(){

	var empty_one = false;
	var not_empty_one = false;
	var not_submit = false;

	$('.input_after_bal').each(function(){

		if($(this).val()==""){
			empty_one = true;
		}else{
			not_empty_one = true;
			if( parseFloat($(this).val())>parseFloat($(this).attr("max")) ){

				alert("Not allow input more than maximum!");
				$(this).focus();
				not_submit = true;
				return false;
			}
		}

	});

	if(not_submit){
		return false;
	}

	if( empty_one && not_empty_one ){

		$('#form_rq_finish').attr("action","ajax/requisition/submit_rq_partial.php");
		$('#form_rq_finish').submit();

	}else{
		alert("Not allow to use this function!");
		return false;
	}

}

function updateStatus(rq_id){
	$('#show_rq_status'+rq_id).html("UPDATE");
}

</script>

<?php
if($_SESSION["employee_position_id"]=="99"){
?>
<!--Modal Admin edit-->
<div class="modal fade" id="showAdminEditModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99998;">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			
			<div class="modal-body" align="center">
				<input type="text" id="admin_rq_item_id" readonly>
				<hr>
				BAL Before : <input type="text" id="bal_before">
				<hr>
				BAL After : <input type="text" id="bal_after">
				<hr>
				Used : <input type="text" id="bal_used">
				<hr>
				<input type="button" onclick="editByAdminSave();" value=" SAVE ">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function editByAdminOnly(rq_item_id){
	$('#admin_rq_item_id').val(rq_item_id);
}

function editByAdminSave(){
	var rq_item_id = $('#admin_rq_item_id').val();

	$.ajax({  
		type: "POST",
		dataType: "json",
		url:"ajax/requisition/admin_edit_bal.php" ,
		data:{
			"rq_item_id":rq_item_id,
			"bal_before":$('#bal_before').val(),
			"bal_after":$('#bal_after').val(),
			"bal_used":$('#bal_used').val()
		},
		success: function(resp){

			alert("Saved!!!");

		}  
	});
}
</script>
<?php
}
?>