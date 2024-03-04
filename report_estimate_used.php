<style type="text/css">
	.tbl_mat_used{
		position: relative;
		border-collapse: unset;

		text-align: center;
		width:1800px;
		border-spacing: 0px;
	}
	.tbl_mat_used th{
		position: sticky;
		top: 0;

		text-align: center;
		font-size: 13px;
		font-weight: bold;
		color: #000;
		background-color: #e3e40a;
		padding: 3px;
		border: 1px solid #CCC;
	}
	.tbl_mat_used td{
		font-size: 13px;
		color: #000;
		padding: 3px;
		border: 1px solid #CCC;
	}

	#tbl_content tbody:hover tr>td{
		background-color: #FFD !important;
	}
	.th_mat_used2{
		position: sticky;
		top: 28px !important;
	}
	.sticky_left1{
		position: sticky;
		left: 0 !important;
		width:200px !important;
		background-color: #EEE !important;
	}
	.sticky_left2{
		position: sticky;
		left: 200px !important;
		background-color: #EEE !important;
	}

	.used_col{
		width: 70px !important;
	}
	.amount_col{
		width: 65px !important;
		text-align: right !important;
	}
	.opt_disable{
		background-color: #FAA !important;
	}
	select {
	   text-align-last: center;
	}

	.total_cell{
		background-color: #AFA !important;
		font-weight: bold !important;
		color: #000 !important;
		width: 90px;
	}
	.total_cell2{
		background-color: #FAA !important;
		font-weight: bold !important;
		color: #000 !important;
		width: 100px;
	}
	.total_cell3{
		background-color: #DDF !important;
		font-weight: bold !important;
	}

	.btn_select_fabric{
		border:2px solid #559;
		background-color: #77B;
		color: #FFF;
		border-radius: 10px;
		cursor: pointer;
	}
	.btn_select_fabric:hover{
		background-color: #88C;
		border-color: #66A;
	}
</style>
<h4 style="font-size:20px; font-weight: normal;">Estimate Used compare with year: 
<select id="year_select" onchange="return submitSelectFabric();" style="line-height: 25px; vertical-align: middle;">
	<?php
	$num_year = intval(date("Y"))-1;
	for($y=$num_year;$y>=2019;$y--){
		echo '<option value="'.$y.'">'.$y.'</option>';
	}
	?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="return submitSelectFabricAndExportXLS();" class="btn btn-success">Export XLS</button>
</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card" style="overflow: scroll; min-height:300px; max-height: 600px; ">
			<table id="tbl_content" class="tbl_mat_used">
				<tr>
					<th style="width:200px; z-index: 2;" class="sticky_left1">Color</th>
					<th class="sticky_left2" style="width:170px; z-index: 2;">Fabric: <span style="color:#00F;" id="sp_fabric_select_num"></span><br>
						<button type="button" class="btn_select_fabric" data-toggle="modal" data-target="#selectFabricModal">Select</button>
					</th>
					<?php
					for($i=1;$i<=12;$i++){
						?>
						<th style="z-index: 1;"><?php echo date("M.",strtotime("2020-$i-01")); ?></th>
						<?php
					}
					?>
					<th class="total_cell" style="z-index: 1;">Total Used <br><span class="show_last_year"></span></th>
					<th class="total_cell" style="z-index: 1;">Used so far<br><span class="show_focus_year"></span></th>
					<th class="total_cell2" style="z-index: 1;">Stock<br>balance</th>
					<th class="total_cell2" style="z-index: 1;">Purchase<br>amount</th>
					<th class="total_cell2" style="z-index: 1;">Estimated<br>new balance</th>
					<th class="total_cell3" style="z-index: 1;">Estimated<br>used remaining</th>
				</tr>

			</table>
		</div>
	</div>
</div>

<!--Modal select Fabric-->
<div class="modal fade" id="selectFabricModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" style="min-width: 700px;">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Please select Fabrics</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 0px 15px 15px 15px;">
				<form id="select_fabric_form" method="post">
					<div class="row" >
						<?php
						$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
						$rs_cat = $conn->query($sql_cat);
						while($row_cat = $rs_cat->fetch_assoc()){
						?>
						<div class="col-4 " style="margin-top: 3px;">
							<input class="select_cat" type="checkbox" name="cat_id[]" value="<?php echo $row_cat["cat_id"]; ?>"> <?php echo $row_cat["cat_name_en"]; ?>
						</div>
						<?php
						}
						?>
					</div>
					<input type="hidden" name="in_year_select" id="in_year_select">
				</form>
				<hr>
				<center>
					<button type="button" class="btn btn-success" onclick="return submitSelectFabric();">Submit selected</button>
				</center>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

function submitSelectFabric(){

	var year = $('#year_select').val();

	var num_cat_select = 0;

	$('.select_cat').each(function(){
		if($(this).prop("checked")){
			num_cat_select++;
		}
	});

	if(num_cat_select==0){
		alert("Please select at least one.");
		return false;
	}

	$('#sp_fabric_select_num').html(num_cat_select+' selected.');

	$('#in_year_select').val(year);

	$('#selectFabricModal').modal("hide");

	$('.tbo_inner').remove();
	$('#tbl_content').append('<tbody class="tbo_inner"><tr><td colspan="20"><i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...</td></tr></tbody>');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_estimate_used.php" ,
		data: $('#select_fabric_form').serialize(),
		success: function(resp){

			$('.tbo_inner').remove();
			$('#tbl_content').append(resp);

			$('.show_last_year').html(year);
			$('.show_focus_year').html('<?php echo date("Y"); ?>');
		}
	});
}

function submitSelectFabricAndExportXLS(){

	var year = $('#year_select').val();

	var num_cat_select = 0;

	$('.select_cat').each(function(){
		if($(this).prop("checked")){
			num_cat_select++;
		}
	});

	if(num_cat_select==0){
		alert("Please select at least one.");
		return false;
	}
	$('#in_year_select').val(year);

	$('#select_fabric_form').attr("target","_blank").attr("action","ajax/report/show_estimate_used_xls.php").submit();

}
</script>