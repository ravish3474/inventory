<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<style type="text/css">
.container-fluid {
	padding-left: 0px;
}
.content-wrapper{
	padding-bottom: 0px !important;
}
.footer{
	padding-top: 0px !important;
}
.form-control{
	font-size: 12px;
}
.card-body{
	padding: 15px !important;
}
.badge {
	font-size: 11px;
}
.show-border{
	border:1px solid #EEE;
	height: 100%;
	padding: 2px;
}
#show_fabric{
	max-height: 400px;
	overflow-y: scroll;
	
}
.select-cat{
	margin:0px;
	font-size: 14px;
	padding: 0px;
}
.obj-cat{
	border:2px solid #88D;
	background-color: #AAF;
	padding: 5px;
}
.selected-cat{
	float:left;
	border:3px solid #88D;
	background-color: #AAF;
	padding: 2px 5px;
	border-radius: 10px;
	margin:1px 2px 1px 0px; 
	font-size: 12px;
	font-weight: bold;
	height: 25px;
	line-height: 14px;
	vertical-align: middle;
}
/*.fabric-content th{
	background-color: #888;
	font-size: 12px;
	font-weight: bold;
	color: #FFF;
	border:1px #000 solid;
	text-align: center;
}
.fabric-content td{
	background-color: #FFF;
	font-size: 12px;
	color: #333;
	border:1px #000 solid;
}*/
.tr-data:hover td{
	background-color: #FFA;
}
.tr-data td{
	text-align: center;
	font-weight: bold;
}
#d_select_zone button{
	padding:2px 5px;
	margin-bottom: 10px; 
}
</style>
<?php
$strDate = date('Y-m-d');

?>
<h4 style="font-size:20px; font-weight: normal;">Forecast Report</h4>
<div class="row" id="d_select_zone">
	<div class="col-12" style="margin-bottom: 5px;">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-2 text-center">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#selectMaterialModal">Material select</button><br>
						<button type="button" class="btn btn-success" onclick="printReport();">Print</button>
						<button type="button" id="btn_switch_view" class="btn btn-info" onclick="switchView();">
							View: <span id="sp_view_icon"><i class="fa fa-text-height" aria-hidden="true"></i></span>
						</button>
						<input type="hidden" id="view_style" value="P">
						<input type="hidden" id="select_cat_id_list" value="">
					</div>
					<div class="col-10" >
						<div class="show-border">
							<div id="show_select_cat" ></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" id="show_report_card" style="display: none;">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12" id="show_report_content">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal select material-->
<div class="modal fade" id="selectMaterialModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Please select materials</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >
				<div class="row" id="show_fabric">
				<?php
				$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
				$rs_cat = $conn->query($sql_cat);
				while ($row_cat = $rs_cat->fetch_assoc()) {
				?>
					<div class="col-2 select-cat" onclick="selectCat(<?php echo $row_cat["cat_id"]; ?>);">
						<div class="obj-cat">
						<input type="checkbox" id="cat_name<?php echo $row_cat["cat_id"]; ?>" class="cat_name" value="<?php echo $row_cat["cat_id"]; ?>" onclick="selectCat(<?php echo $row_cat["cat_id"]; ?>);">
						<span id="sp_cat_name<?php echo $row_cat["cat_id"]; ?>"><?php echo $row_cat["cat_name_en"]; ?></span>
						</div>
					</div>
				<?php
				}
				?>
				</div>
				<hr>
				<div class="row">
					<div class="col-12">
						<center>
							<button id="btn_select_all" type="button" class="btn btn-primary mr-2" onclick="$('.cat_name').prop('checked',true); ">
								Select All
							</button>
							<button id="btn_deselect_all" type="button" class="btn btn-warning mr-2" onclick="$('.cat_name').prop('checked',false);">
								Unselect All
							</button>
							<button type="button" class="btn btn-success mr-2" onclick="showReport();">Submit</button>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<iframe name="hidden_submit_remove_order" style="display: none;" width="0" height="0"></iframe>

<script type="text/javascript">
autoCheckCloseModal();

function autoCheckCloseModal(){

	//console.log($('body').prop("class"));

	if($('body').prop("class")==""){
		$('#selectMaterialModal .close').click();
		$('.modal-backdrop').hide();
	}

	setTimeout(function(){
		
		autoCheckCloseModal();
		
	}, 5000);
}

function switchView(){
	if($('#view_style').val()=="P"){
		$('#view_style').val("L");
		$('#sp_view_icon').html('<i class="fa fa-text-width" aria-hidden="true"></i>');
		
	}else{
		$('#view_style').val("P");
		$('#sp_view_icon').html('<i class="fa fa-text-height" aria-hidden="true"></i>');
	}

	var cat_id_list = $('#select_cat_id_list').val();
	showReportContent(cat_id_list);
}

function selectCat(cat_id){
	if($('#cat_name'+cat_id).prop('checked')){
		$('#cat_name'+cat_id).prop('checked',false);
	}else{
		$('#cat_name'+cat_id).prop('checked',true);
	}
	
}

function showReport(){

	$('#selectMaterialModal .close').click();
	$('.modal-backdrop').hide();

	var cat_id_list = '';
	var cat_count = 0;
	var inner_cat_select = '';

	$('.cat_name').each(function(){
		if($(this).prop('checked')){

			if(cat_count!=0){
				cat_id_list += ',';
			}
			cat_id_list += $(this).val();
			cat_count++;

			//inner_cat_select += '<div class="select-cat">';
			inner_cat_select += '<div class="selected-cat">'+$('#sp_cat_name'+$(this).val()).html()+'</div>';
			//inner_cat_select += '</div>';
		}

	});

	$('#show_select_cat').html(inner_cat_select);

	$('#select_cat_id_list').val(cat_id_list);

	showReportContent(cat_id_list);
}

function showReportContent(cat_id_list){

	if(cat_id_list!=""){

		$('#show_report_card').show();
		$('#show_report_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

		var view_style = $('#view_style').val();

		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/forecast/show_report_content_v2.php" ,
			data:{
				"cat_id_list":cat_id_list,
				"view_style":view_style
			},
			success: function(resp){
				$('#show_report_content').html(resp);

			}  
		});
	}else{

		$('#show_report_content').html('');
		$('#show_report_card').hide();
	}
}

function printReport(){

	var count_cat = 0;
	$('.cat_name').each(function(){
		if($(this).prop("checked")){
			count_cat++;
		}
	});

	if(count_cat==0){

		alert("Please select Material first");
		return false;
	}

	var divContents = $("#d_print").html();
    var printWindow = window.open('', '', 'height=1500,width=900');
    printWindow.document.write('<html><head><title>Forecast Report</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();

}
</script>
