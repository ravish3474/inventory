<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

<style type="text/css">
.container-fluid {
	padding-left: 0px;
}
.tbl_forecast_po{
	width: 100%;
	margin-top: 10px;
}
.tbl_forecast_po th{
	background-color: #AFA;
	border: 1px solid #9D9;
	text-align: center;
	padding: 3px;
}
.tbl_forecast_po td{
	border: 1px solid #9D9;
	text-align: center;
	padding: 3px;
}
.tbl_forecast_po tr:hover td{
	background-color: #EEE;
}
.tbl_forecast_po tr:hover .total_col1{
	background-color: #8DD !important;
}
.total_col1{
	background-color: #9EE !important;
	text-align: right !important;
}
.total_row{
	font-weight: bold;
	background-color: #BBF;
}
.total_row:hover td{
	background-color: #99E !important;
}
.link_show_detail{
	cursor: pointer;
	color: #00F;
}
.tbl_show_stock_detail{
	width: 100%;
}
.tbl_show_stock_detail th{
	background-color: #FFA;
	border: 1px solid #DD9;
	text-align: center;
	padding: 3px;
}
.tbl_show_stock_detail td{
	border: 1px solid #DD9;
	text-align: center;
	padding: 3px;
}
.tbl_show_stock_detail tr:hover td{
	background-color: #EEE !important;
}
.tbl_show_fc_order_detail{
	width: 100%;
}
.tbl_show_fc_order_detail th{
	background-color: #AFF;
	border: 1px solid #9DD;
	text-align: center;
	padding: 3px;
}
.tbl_show_fc_order_detail td{
	border: 1px solid #9DD;
	text-align: center;
	padding: 3px;
}
.tbl_show_fc_order_detail tr:hover td{
	background-color: #EEE !important;
}

.tbl_show_fc_purchase_detail{
	width: 100%;
}
.tbl_show_fc_purchase_detail th{
	background-color: #F9D;
	border: 1px solid #D7A;
	text-align: center;
	padding: 3px;
}
.tbl_show_fc_purchase_detail td{
	border: 1px solid #D7A;
	text-align: center;
	padding: 3px;
}
.tbl_show_fc_purchase_detail tr:hover td{
	background-color: #EEE !important;
}

#title_fabric,#title_fabric_fd,#title_fabric_fp,#title_fabric_fpd{
	font-size: 15px;
}
#title_color,#title_color_fd,#title_color_fp,#title_color_fpd{
	font-size: 15px;
}
.admin_edit{
	cursor: pointer;
}
.div_form{
	width: 100%;
	font-size: 16px;
	margin-top: 5px;
}
</style>
<?php
$strDate = date('Y-m-d');
//data-toggle="modal" data-target="#selectMaterialModal"
?>
<h4 style="font-size:20px; font-weight: normal;">Forecast Purchase</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 text-left">
						Fabric: &nbsp;
						<select id="select_cat" onchange="return showFOCPOContent();">
							<option value="">==Please select==</option>
							<?php
							$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC; ";
							$rs_cat = $conn->query($sql_cat);
							while ($row_cat = $rs_cat->fetch_assoc()) {
								echo '<option value="'.$row_cat["cat_id"].'">'.$row_cat["cat_name_en"].'</option>';
							}
							?>
						</select>
						&nbsp;&nbsp;
						<span style="font-size: 12px; color: #00F; font-weight: bold;">**Click on the blue number to seeing details.</span>
					</div>
					<div class="col-12" id="show_foc_po_content">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal show stock detail-->
<div class="modal fade" id="showStockDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Stock detail: <span id="title_fabric"></span> - <span id="title_color"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 15px;">
				<div id="show_stock_detail">
				
				</div>
				
			</div>
		</div>
	</div>
</div>

<!--Modal show Forecast order detail-->
<div class="modal fade" id="showForecastOrderDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Forecast detail: <span id="title_fabric_fd"></span> - <span id="title_color_fd"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 15px;">
				<div id="show_fc_order_detail">
				
				</div>
				
			</div>
		</div>
	</div>
</div>

<!--Modal edit Forecast purchase-->
<div class="modal fade" id="editForecastPurchaseModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Forecast purchasing: <span id="title_fabric_fp"></span> - <span id="title_color_fp"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 15px;">
				<div id="show_fc_purchase">
				
				</div>
				
			</div>
		</div>
	</div>
</div>

<!--Modal show Forecast purchase detail-->
<div class="modal fade" id="showForecastPurchaseModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="padding: 15px;">
				<h5 class="modal-title">Purchasing detail: <span id="title_fabric_fpd"></span> - <span id="title_color_fpd"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="padding: 15px;">
				<div id="show_fc_purchase_detail">
				
				</div>
				
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
function showFOCPOContent(){

	$('#show_foc_po_content').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/show_foc_po_content.php" ,
		data:{
			"cat_id":$('#select_cat').val()
		},
		success: function(resp){  
			$('#show_foc_po_content').html(resp);
		}  
	});
}

function showStockDetail(color_id,supp_id){

	$('#show_stock_detail').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var cat_id = $('#select_cat').val();
	if(cat_id==""){
		alert("Please select Fabric.");
		return false;
	}

	var cat_name = $('#select_cat option:selected').text();
	var color_name = $('#td_color_name'+color_id).html();

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/show_stock_detail.php" ,
		data:{
			"cat_id": cat_id,
			"color_id": color_id,
			"supp_id": supp_id
		},
		success: function(resp){  

			$('#title_fabric').html(cat_name);
			$('#title_color').html(color_name);

			$('#show_stock_detail').html(resp);
		}  
	});

}

function showFCOrderDetail(color_id){

	$('#show_fc_order_detail').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var cat_id = $('#select_cat').val();
	if(cat_id==""){
		alert("Please select Fabric.");
		return false;
	}

	var cat_name = $('#select_cat option:selected').text();
	var color_name = $('#td_color_name'+color_id).html();

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/show_fc_order_detail.php" ,
		data:{
			"cat_id": cat_id,
			"color_id": color_id
		},
		success: function(resp){  

			$('#title_fabric_fd').html(cat_name);
			$('#title_color_fd').html(color_name);

			$('#show_fc_order_detail').html(resp);
		}  
	});

}

function showFOCPurchaseDetail(color_id,req_update_num=false){

	$('#show_fc_purchase_detail').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var cat_id = $('#select_cat').val();
	if(cat_id==""){
		alert("Please select Fabric.");
		return false;
	}

	var cat_name = $('#select_cat option:selected').text();
	var color_name = $('#td_color_name'+color_id).html();

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/show_fc_purchase_detail.php" ,
		data:{
			"cat_id": cat_id,
			"color_id": color_id
		},
		success: function(resp){  

			$('#title_fabric_fpd').html(cat_name);
			$('#title_color_fpd').html(color_name);

			$('#show_fc_purchase_detail').html(resp);

			if(req_update_num){
				updateTotalNumber(color_id);
			}
		}  
	});

}

function inputFOCPurchase(color_id,supp_id_list){

	$('#show_fc_purchase').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var cat_id = $('#select_cat').val();
	if(cat_id==""){
		alert("Please select Fabric.");
		return false;
	}

	var cat_name = $('#select_cat option:selected').text();
	var color_name = $('#td_color_name'+color_id).html();

	$.ajax({  
		type: "POST",  
		dataType: "html", 
		url:"ajax/forecast/show_fc_purchase.php" ,
		data:{
			"cat_id": cat_id,
			"color_id": color_id,
			"supp_id_list":supp_id_list
		},
		success: function(resp){  

			$('#title_fabric_fp').html(cat_name);
			$('#title_color_fp').html(color_name);

			$('#show_fc_purchase').html(resp);
		}  
	});

}

function submitNewFOCPurchase(color_id){

	$.ajax({  
		type: "POST",  
		dataType: "json", 
		url:"ajax/forecast/submit_fc_purchase.php" ,
		data: $('#form_add_foc').serialize(),
		success: function(resp){  

			if(resp.result=="success"){
				$('#sp_show_fop'+color_id).html(resp.sum_value+"&nbsp;");

				var est_act_bal = parseFloat($('#hidden_est_act_bal'+color_id).val());
				var new_sum_val = parseFloat(resp.sum_value);

				var est_new_bal = est_act_bal+new_sum_val;

				$('#hidden_est_new_bal'+color_id).val(est_new_bal);
				$('#sp_show_est_new_bal'+color_id).html(est_new_bal);

				$('#editForecastPurchaseModal').modal("toggle");
			}

		}  
	});

}

function addRowFOCPurchase(){

	$('#foc_content_zone').append($('#inner_form').html());
}

function printFOCPurchaseInfo(){

	var fabric_name = $('#select_cat option:selected').text();
	var data_date = $('#data_date').val();

	var divContents = $("#d_forecast_po").html();
    var printWindow = window.open('', '', 'height=2000,width=1200');
    printWindow.document.write('<html><head><title>Forecast Purchase</title>');

    printWindow.document.write('<style type="text/css">');
    printWindow.document.write('.tbl_forecast_po{ max-width: 95%; margin-top: 10px; border-spacing: 0px; } ');
	printWindow.document.write('.tbl_forecast_po th{ background-color: #AFA; border: 1px solid #9D9; text-align: center; padding: 3px; } ');
	printWindow.document.write('.tbl_forecast_po td{ border: 1px solid #9D9; text-align: center; padding: 3px; } ');
	printWindow.document.write('.total_col1{ background-color: #9EE !important; text-align: right !important; } ');
	printWindow.document.write('.total_row{ font-weight: bold; background-color: #BBF; } ');
	printWindow.document.write('</style>');

    printWindow.document.write('</head><body>');
    printWindow.document.write('<h4 style="margin-left:40px;">'+fabric_name+'</h4>');
    printWindow.document.write('<h5 style="margin-left:40px;">'+data_date+'</h5>');
    printWindow.document.write('<center>'+divContents+'<center>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();

}

function deleteForcastPurchase(fpu_id,color_id){

	if(confirm("Deleting confirm?")){
		$.ajax({  
			type: "POST",  
			dataType: "json", 
			url:"ajax/forecast/delete_fc_purchase.php" ,
			data: {
				"fpu_id": fpu_id
			},
			success: function(resp){  

				if(resp.result=="success"){
					showFOCPurchaseDetail(color_id,true);

				}else{
					alert(resp.msg);
				}

			}  
		});
	}
}

function updateTotalNumber(color_id){
	
	$('#sp_show_fop'+color_id).html($('#inside_detail_total'+color_id).html());
}
</script>
