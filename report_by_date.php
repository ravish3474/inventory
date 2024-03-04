
<style type="text/css">
.div-report{
	width: 100%;
	max-height: 500px;
	overflow: scroll;
}
.tbl-report th{
	font-size: 14px;
	font-weight: bold;
	border:1px solid #555;
	text-align: center;
}
.tbl-report td{
	font-size: 14px;
	border:1px solid #555;
}

.cls-normal-head{
	background-color: #ffff00;
}
.cls-total{
	background-color: #83c45b;
	text-align: right !important;
}
.cls-used{
	background-color: #ffabfc;
	text-align: right !important;
}
.cls-adjust{
	background-color: #adc9fa;
	text-align: right !important;
}
.cls-balance{
	background-color: #ffc000;
	text-align: right !important;
}
.cls-number{
	text-align: right !important;
}

.cls-sunday-head{
	background-color: #FF0000 ;
	text-align: center !important;
}
.cls-normal-day-head{
	background-color: #ffdb69;
	text-align: center !important;
}
.cls-sunday{
	background-color: #FF0000 ;
	text-align: right !important;
}
.cls-normal-day{
	background-color: #ffdb69;
	text-align: right !important;
}
.have-info{
	cursor: pointer;
	color:#00F;
}
.title-info{
	border-radius: 5px;
	border:1px solid #027;
	background-color: #05A;
	color: #FFF;
	margin: 5px 5px;
	padding: 5px 0px;
}
.trans_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.trans_tbl td{
	
	border:1px solid #7DF;
}
.trans_tbl tr:hover{
	background-color: #DDD;
}
.show-doc{
	cursor: pointer;
}

.ncode_head{
	border:2px solid #995;
	border-radius: 5px;
	padding: 10px;
	margin: 5px;
	background-color: #990;
	color: #FFF;
}

.ncode_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.ncode_tbl td{
	
	border:1px solid #7DF;
}
.ncode_tbl tr:hover{
	background-color: #DDD;
}

.rq_head{
	border:2px solid #999;
	border-radius: 5px;
	padding: 10px;
	margin: 5px;
	background-color: #222;
	color: #FFF;
}

.rq_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.rq_tbl td{
	
	border:1px solid #7DF;
}
.rq_tbl tr:hover{
	background-color: #DDD;
}

.hilight_roll td{
	background-color: #FF0;
}

.content_data:hover td{
	background-color: #FF8 !important;
}

.total-row td{
	background-color: #FBB;
	font-weight: bold;
}

#btn_xls{
	width: 80px;
	padding: 5px;
}

/* ----== #log_view ==----*/
.tbl-log th{
	background-color: #06f;
	color: #FFF;
	font-size: 14px;
	font-weight: bold;
	padding: 3px;
	text-align: center;
	border:1px solid #59f;
}
.tbl-log tr{
	background-color: #CCC;
}
.tbl-log tr:hover{
	background-color: #EEE;
}
.tbl-log td{
	border:1px solid #59f;
	font-size: 14px;
	padding: 3px;
	text-align: center;
}
.log-title{
	font-weight: bold;
	font-size: 15px;
}
/* ----== #log_view ==----*/
</style>
<h4 style="font-size:20px; font-weight: normal;">Summary Report</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						Year: 
						<select id="year_select" onchange="showReportView();" style="line-height: 25px; vertical-align: middle;">
							<?php
							$num_year = intval(date("Y"));
							for($y=$num_year;$y>=2015;$y--){
								echo '<option value="'.$y.'">'.$y.'</option>';
							}
							?>
						</select>
						&nbsp;
						Month: 
						<select id="month_select" onchange="showReportView();" style="line-height: 25px; vertical-align: middle;">
							<option value="01" <?php if(date("m")=="01"){ echo "selected"; } ?>>January</option>
							<option value="02" <?php if(date("m")=="02"){ echo "selected"; } ?>>Febuary</option>
							<option value="03" <?php if(date("m")=="03"){ echo "selected"; } ?>>March</option>
							<option value="04" <?php if(date("m")=="04"){ echo "selected"; } ?>>April</option>
							<option value="05" <?php if(date("m")=="05"){ echo "selected"; } ?>>May</option>
							<option value="06" <?php if(date("m")=="06"){ echo "selected"; } ?>>June</option>
							<option value="07" <?php if(date("m")=="07"){ echo "selected"; } ?>>July</option>
							<option value="08" <?php if(date("m")=="08"){ echo "selected"; } ?>>August</option>
							<option value="09" <?php if(date("m")=="09"){ echo "selected"; } ?>>September</option>
							<option value="10" <?php if(date("m")=="10"){ echo "selected"; } ?>>October</option>
							<option value="11" <?php if(date("m")=="11"){ echo "selected"; } ?>>November</option>
							<option value="12" <?php if(date("m")=="12"){ echo "selected"; } ?>>December</option>
						</select>
						&nbsp;
						<button class="btn btn-success" type="button" id="btn_xls" onclick="exportToExcel();">Export <i class="fa fa-file-excel-o"></i></button>
					</div>
					
					<div class="col-3">Fabric: 
						<select onchange="changeFabric();" id="select_fabric">
							<option value="=all=">==All==</option>
						<?php
						$sql_cat = "SELECT cat_id,cat_name_en FROM cat ORDER BY cat_name_en ASC";
						$rs_cat = $conn->query($sql_cat);

						while($row_cat = $rs_cat->fetch_assoc()){
							
							echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
						}
						?>
						</select>
					</div>
					<div class="col-3">Color: 
						<select onchange="showReportView();" id="select_color">
							<option value="=all=">==All==</option>
						</select>
					</div>
					<div class="col-2" style=" font-size: 12px;">
						<input type="checkbox" id="chk_have_data" onclick="showReportView();" checked>
						
						There is transaction
						<br> 
						<input type="checkbox" id="chk_in_this_month" onclick="showReportView();">
						
						Only Stock IN
						
					</div>

					<div class="col-12"><hr></div>
					<div class="col-12">
						<div id="show_report">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<form id="export_form" action="ajax/report/get_mat_data.php" method="post" target="hidden_frame" style="display: none;">
	<input type="hidden" name="year_select" id="year_select_ex" value="">
	<input type="hidden" name="month_select" id="month_select_ex" value="">
	<input type="hidden" name="cat_id" id="cat_id_ex" value="">
	<input type="hidden" name="select_color" id="select_color_ex" value="">
	<input type="hidden" name="have_data" id="have_data_ex" value="">
	<input type="hidden" name="in_this_month" id="in_this_month_ex" value="">
	<input type="hidden" name="export_flag" id="export_flag_ex" value="">
</form>
<iframe name="hidden_frame" style="display: none;"></iframe>

<!--Modal Transaction-->
<div class="modal fade" id="showTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_transactions" align="center">
				
			</div>
		</div>
	</div>
</div>

<!--Modal Transaction Document-->
<div class="modal fade" id="showTransDocModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_trans_doc" align="center">
				
			</div>
		</div>
	</div>
</div>

<!--Modal Log adjust-->
<div class="modal fade" id="showLogModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_log_content" align="center">
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
showReportView();
function showReportView(){

	$('#show_report').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var have_data = 0;
	if($('#chk_have_data').prop("checked")){
		have_data = 1;
	}

	var in_this_month = 0;
	if($('#chk_in_this_month').prop("checked")){
		in_this_month = 1;
	}

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/get_mat_data.php" ,
		data:{
			"year_select":$('#year_select').val(),
			"month_select":$('#month_select').val(),
			"cat_id":$('#select_fabric').val(),
			"select_color":$('#select_color').val(),
			"have_data":have_data,
			"in_this_month":in_this_month
		},
		success: function(resp){
			$('#show_report').html(resp);
			//showChooseFabricColor();
		}
	});

}

function exportToExcel(){

	var have_data = 0;
	if($('#chk_have_data').prop("checked")){
		have_data = 1;
	}

	var in_this_month = 0;
	if($('#chk_in_this_month').prop("checked")){
		in_this_month = 1;
	}

	$('#year_select_ex').val($('#year_select').val());
	$('#month_select_ex').val($('#month_select').val());
	$('#cat_id_ex').val($('#select_fabric').val());
	$('#select_color_ex').val($('#select_color').val());
	$('#have_data_ex').val(have_data);
	$('#in_this_month_ex').val(in_this_month);
	$('#export_flag_ex').val(1);

	
	$('#export_form').submit();
}

function changeFabric(){

	var cat_id = $('#select_fabric').val();

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/get_color_option.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){
			$('#select_color').html(resp);
			showReportView();
		}
	});

}

function showTransInfo(fabric_id,trans_date){

	$('#show_transactions').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_trans_roll_date.php" ,
		data:{
			"fabric_id":fabric_id,
			"trans_date":trans_date
		},
		success: function(resp){
			$('#show_transactions').html(resp);
		}
	});

}

function showTransDocument(trans_process,ref_id,fabric_box,fabric_no){

	$('#show_trans_doc').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	if(trans_process=="RQ"){
		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/report/show_trans_doc_rq.php" ,
			data:{
				"rq_id":ref_id,
				"fabric_box":fabric_box,
				"fabric_no":fabric_no
			},
			success: function(resp){
				$('#show_trans_doc').html(resp);
			}  
		});
	}else if(trans_process=="NO-CODE"){ 
		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/report/show_trans_doc_ncode.php" ,
			data:{
				"used_id":ref_id,
				"fabric_no":fabric_no
			},
			success: function(resp){
				$('#show_trans_doc').html(resp);
			}  
		});
	}else{
		$('#show_trans_doc').html('Error : Cannot open reference document.');
	}

}

function showAdjustLog(fabric_id,fabric_box,fabric_no){

	$('#show_log_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_log_view.php" ,
		data:{
			"fabric_id":fabric_id,
			"log_box":fabric_box,
			"log_no":fabric_no
		},
		success: function(resp){
			$('#show_log_content').html(resp);
		}  
	});
}
</script>