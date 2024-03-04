<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<style type="text/css">
.container-fluid {
	padding-left: 0px;
}
.form-control{
	font-size: 12px;
}
.badge {
	font-size: 11px;
}


/* ----== #fabric_view ==----*/
.fabric-board{
	padding:0px;
}
.fabric-box h6{
	background-color: #63ab31;
	color: #000;
	height: 20px;
	font-size: 14px;
	padding-top: 2px;
}
.fabric-box{
	font-size: 13px;
	height: 80px;
	background-color: #c3f5f6;
	margin:3px;
	border-radius: 5px;
	border: 2px solid #AAA; 
	cursor: pointer;
}
.fabric-box font{
	font-weight: bold;
}
.fabric-box:hover h6{
	background-color: #c3f5f6;

	-webkit-animation-name: fabric_h_animation; /* Safari 4.0 - 8.0 */
  	-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  	animation-name: fabric_h_animation;
  	animation-duration: 2s;
}
.fabric-box:hover{
	background-color: #63ab31;
	border-color: #CCC;

	-webkit-animation-name: fabric_animation; /* Safari 4.0 - 8.0 */
  	-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
  	animation-name: fabric_animation;
  	animation-duration: 2s;
}

.fabric-box:hover .num-bal{
	color: #000;
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes fabric_animation {
  from  {background-color:#c3f5f6; }
  to {background-color:#63ab31; }
}

/* Standard syntax */
@keyframes fabric_animation {
  from  {background-color:#c3f5f6; }
  to {background-color:#63ab31; }
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes fabric_h_animation {
  from  {background-color:#63ab31; }
  to {background-color:#c3f5f6; }
}

/* Standard syntax */
@keyframes fabric_h_animation {
  from  {background-color:#63ab31; }
  to {background-color:#c3f5f6; }
}
/* ----== #fabric_view ==----*/

/* ----== #colors_view ==----*/
.colors-board{
	padding:0px;
}
.colors-box h6{
	background-color: #f49502;
	color: #000;
	height: 20px;
	font-size: 14px;
	padding-top: 2px;
}
.colors-box{
	font-size: 13px;
	height: 110px;
	background-color: #f6f69b;
	margin:3px;
	border-radius: 5px;
	border: 2px solid #AAA; 
	cursor: pointer;
}
.colors-box font{
	font-weight: bold;
}

.colors-box:hover{
	border-color: #CCC;
}

.colors-box:hover .num-total{
	color: #000;
}

/* ----== #colors_view ==----*/

/* ----== #rolls_view ==----*/
.tbl-rolls-empty thead{
	background-color: #7A0040;
}
.tbl-rolls-empty th{
	
	color:#FFF;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
}
.tbl-rolls-empty tbody{
	background-color: #DDDDDD;
}
.tbl-rolls-empty td{
	color:#222222;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
	height: 45px;
}


.tbl-rolls thead{
	background-color: #cf0495;
}
.tbl-rolls th{
	
	color:#FFF;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
}
.tbl-rolls tbody{
	background-color: #DDDDDD;
}
.tbl-rolls td{
	color:#222222;
	border:1px solid #ff99ff;
	padding: 5px;
	font-size: 14px;
	height: 45px;
}
.row-rolls:hover{
	background-color: #EEE;
}

.tbl-rolls input{
	width: 100px;
	text-align: center;
}
.btn-save{
	font-size: 14px;
	line-height: 14px;
}
/* ----== #rolls_view ==----*/

/* ----== trans view ==----*/
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

.po_head{
	border:2px solid #599;
	border-radius: 5px;
	padding: 10px;
	margin: 5px;
	background-color: #099;
	color: #FFF;
}

.po_tbl th{
	background-color: #39f;
	border:1px solid #7DF;
	color:#FFF;
}
.po_tbl td{
	
	border:1px solid #7DF;
}
.po_tbl tr:hover{
	background-color: #DDD;
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
/* ----== trans view ==----*/

#new_roll_content .row{
	padding: 5px;
}

.hilight_roll td{
	background-color: #FF0;
}


.grand_total_row td{
	background-color: #555;
	color: #FFF;
	font-weight: bold;
}

#tr_no_record{
	color:#F00;
	font-weight: bold;
}
</style>

<h4 style="font-size:20px; font-weight: normal;">Transaction Report</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
			    <div class="row">
			        <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="fabricSearch" class="form-control" placeholder="Search fabric...">
                        </div>
                    </div>
                </div>
				<div class="row" id="fabric_view">
				</div>
				<div class="row" id="colors_view" style="display: none;">
				</div>
				<div class="row" id="rolls_view" style="display: none;">
				</div>
			</div>
		</div>
	</div>
</div>

<!--Modal Transaction-->
<div class="modal fade" id="showTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_transactions" align="center">
				
			</div>
		</div>
	</div>
</div>

<!--Modal Transaction Document-->
<div class="modal fade" id="showTransDocModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			
			<div class="modal-body" id="show_trans_doc" align="center">
				
			</div>
		</div>
	</div>
</div>

<!--Modal Remove Remark-->
<div class="modal fade" id="showRemarkModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			
			<div class="modal-body" align="center">
				<pre id="show_remark_content" style="width: 100%;"></pre>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
showFabricView();

function showFabricView(){

	$('#fabric_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#colors_view').hide();
	$('#rolls_view').hide();
	$('#fabric_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_fabric_view.php" ,
		success: function(resp){
			$('#fabric_view').html(resp);

		}
	});

}

function showColorsView(cat_id,cat_name_en){

	$('#colors_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#fabric_view').hide();
	$('#rolls_view').hide();
	$('#colors_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_colors_view.php" ,
		data:{
			"cat_id":cat_id,
			"cat_name_en":cat_name_en
		},
		success: function(resp){
			$('#colors_view').html(resp);
		}  
	});

}

function showRollsView(cat_id,color_name,cat_name_en,color_code){

	$('#rolls_view').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');
	$('#fabric_view').hide();
	$('#colors_view').hide();
	$('#rolls_view').fadeIn(1000);

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_rolls_view.php" ,
		data:{
			"cat_id":cat_id,
			"color_name":color_name,
			"cat_name_en":cat_name_en,
			"color_code":color_code
		},
		success: function(resp){
			$('#rolls_view').html(resp);
		}  
	});

}

function showTransactions(fabric_id,fabric_box,fabric_no,fabric_balance){

	$('#show_transactions').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/report/show_trans_view.php" ,
		data:{
			"fabric_id":fabric_id,
			"fabric_box":fabric_box,
			"fabric_no":fabric_no,
			"fabric_balance":fabric_balance
		},
		success: function(resp){
			$('#show_transactions').html(resp);
		}  
	});

}

function showTransDocument(trans_process,ref_id,fabric_box,fabric_no){

	$('#show_trans_doc').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	if(trans_process=="PO"){
		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/report/show_trans_doc_po.php" ,
			data:{
				"po_id":ref_id,
				"fabric_box":fabric_box,
				"fabric_no":fabric_no
			},
			success: function(resp){
				$('#show_trans_doc').html(resp);
			}  
		});
	}else if(trans_process=="STOCK-IN"){
		$.ajax({  
			type: "POST",  
			dataType: "html",
			url:"ajax/report/show_trans_doc_stk_in.php" ,
			data:{
				"pac_id":ref_id,
				"fabric_box":fabric_box,
				"fabric_no":fabric_no
			},
			success: function(resp){
				$('#show_trans_doc').html(resp);
			}  
		});
	}else if(trans_process=="RQ"){
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

function showRemoveRemark(fabric_id){
	$('#show_remark_content').html($('#h_remark'+fabric_id).val());
}
</script>
<script>
$(document).ready(function() {
    // Function to perform the search and filter
    function searchFabrics() {
        var searchTerm = $('#fabricSearch').val().toLowerCase();

        $('.fabric-box').each(function() {
            var fabricName = $(this).find('h6').text().toLowerCase();

            if (fabricName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Initial search on page load
    searchFabrics();

    // Listen for input changes in the search bar
    $('#fabricSearch').on('input', searchFabrics);
});
</script>
