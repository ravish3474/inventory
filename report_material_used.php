<style type="text/css">
	.tbl_mat_used{
		position: relative;
		border-collapse: unset;

		text-align: center;
		width:2700px;
		border-spacing: 0px;
	}
	.tbl_mat_used th{
		position: sticky;
		top: 0;

		text-align: center;
		font-size: 13px;
		font-weight: bold;
		color: #FFF;
		background-color: #5A5;
		padding: 3px;
		border: 1px solid #AFA;
	}
	.tbl_mat_used tr:hover td{
		background-color: #FD9 !important;
	}
	.th_mat_used2{
		position: sticky;
		top: 28px !important;
	}
	.sticky_left1{
		position: sticky;
		left: 0 !important;
		width:180px !important;
	}
	.sticky_left2{
		position: sticky;
		left: 180px !important;
	}

	.tbl_mat_used td{
		font-size: 13px;
		color: #000;
		padding: 3px;
		border: 1px solid #AFA;
	}


	.used_col{
		width: 75px !important;
	}
	.amount_col{
		width: 90px !important;
		text-align: right !important;
	}
	.opt_disable{
		background-color: #FAA !important;
	}
	select {
	   text-align-last: center;
	}

	.total_cell{
		background-color: #AAF !important;
		font-weight: bold !important;
		color: #000 !important;
	}
</style>
<h4 style="font-size:20px; font-weight: normal;">Material Used Report of 
<select id="year_select" onchange="showUsedReport();" style="line-height: 25px; vertical-align: middle;">
	<?php
	$num_year = intval(date("Y"));
	for($y=$num_year;$y>=2019;$y--){
		echo '<option value="'.$y.'">'.$y.'</option>';
	}
	?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="return submitExportXLS();" class="btn btn-success">Export XLS</button>
</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card" style="overflow: scroll; min-height:300px; max-height: 600px; ">
			<table class="tbl_mat_used">
				<tr>
					<th rowspan="2" style="width:180px; z-index: 2;" class="sticky_left1">Fabric: <br>
						<select onchange="showUsedReport();" id="select_fabric">
							<option value="=all=">==All==</option>
						<?php
						$sql_cat = "SELECT cat_id,cat_name_en,enable FROM cat ORDER BY enable DESC,cat_name_en ASC";
						$rs_cat = $conn->query($sql_cat);

						while($row_cat = $rs_cat->fetch_assoc()){

							echo '<option ';
							if($row_cat["enable"]=="0"){
								echo 'class="opt_disable"';
							}
							echo ' value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
						}
						?>
						</select>
					</th>
					<th rowspan="2" class="sticky_left2" style="z-index: 2;">Color: <br>
						<select onchange="showUsedReport();" id="select_color">
							<option value="=all=">==All==</option>
						<?php
						$sql_color = "SELECT color_id,color_name,enable FROM tbl_color WHERE enable=1 ORDER BY enable DESC,color_name ASC";
						$rs_color = $conn->query($sql_color);

						while($row_color = $rs_color->fetch_assoc()){
							
							echo '<option ';
							if($row_color["enable"]=="0"){
								echo 'class="opt_disable"';
							}
							echo ' value="'.$row_color["color_id"].'">'.$row_color["color_name"].'</option>';
						}
						?>
						</select>
					</th>
					<?php
					for($i=1;$i<=12;$i++){
						?>
						<th colspan="2" style="z-index: 1;"><?php echo date("M.",strtotime("2020-$i-01")); ?></th>
						<?php
					}
					?>
					<th class="total_cell" colspan="2" style="z-index: 1;">Summary Used</th>
				</tr>
				<tr>
					<?php
					for($i=1;$i<=13;$i++){
						?>
						<th class="used_col th_mat_used2 <?php if($i==13){ echo "total_cell"; } ?>">USED(KG)</th>
						<th class="amount_col th_mat_used2 <?php if($i==13){ echo "total_cell"; } ?>">AMOUNT</th>
						<?php
					}
					?>
				</tr>

				<tbody id="tb_content">

				</tbody>
			</table>
		</div>
	</div>
</div>

<form id="form_export" name="form_export" action="ajax/report/show_material_used_xls.php" method="post" target="_blank">
	<input type="hidden" name="year" id="year_tmp" >
	<input type="hidden" name="cat_id" id="cat_id_tmp" >
	<input type="hidden" name="color_id" id="color_id_tmp" >
</form>

<script type="text/javascript">
	showUsedReport();

	function showUsedReport(){

		var year = $('#year_select').val();
		var cat_id = $('#select_fabric').val();
		var color_id = $('#select_color').val();

		$('#tb_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/report/show_material_used.php" ,
			data:{
				"year":year,
				"cat_id":cat_id,
				"color_id":color_id
			},
			success: function(resp){
				$('#tb_content').html(resp);
			}
		});
	}

	function submitExportXLS(){

		$('#year_tmp').val($('#year_select').val());
		$('#cat_id_tmp').val($('#select_fabric').val());
		$('#color_id_tmp').val($('#select_color').val());

		$('#form_export').submit();

	}

</script>