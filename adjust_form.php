<?php
if( in_array("adjust_form",$a_allow) ){
?>
<style type="text/css">

.choose-title{
	border-width: 1px 1px 0px 1px;
	border-style: solid;
	border-color: #AAA;
	background-color: #c3f5f6;
	border-radius: 5px 5px 0px 0px;
	padding: 5px;
	text-align: center;
}

.choose-content{
	border-width: 0px 1px 1px 1px;
	border-style: solid;
	border-color: #AAA;
	background-color: #c3f5f6;
	border-radius: 0px 0px 5px 5px;
	padding: 10px;
}

.cat-item{
	padding: 3px;
	line-height: 22px;
	vertical-align: middle;
	cursor: pointer;
}
.cat-item.active{
	background-color: #f6f69b;
}

#choose_zone_cat{
	margin-right: 1px;
}

.choose-title-color{
	border-width: 1px 1px 0px 1px;
	border-style: solid;
	border-color: #AAA;
	background-color: #f6f69b;
	border-radius: 5px 5px 0px 0px;
	padding: 5px;
	text-align: center;
}

.choose-content-color{
	border-width: 0px 1px 1px 1px;
	border-style: solid;
	border-color: #AAA;
	background-color: #f6f69b;
	border-radius: 0px 0px 5px 5px;
	padding: 10px;
	
}
.color-item{
	padding-bottom:5px;
	font-size: 14px;
}

#content_zone{
	padding: 20px;
}


</style>
<h4 style="font-size:20px; font-weight: normal;">Adjust (Print form)</h4>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="row" id="choose_zone">
					<div class="col-6">
						<div class="row" id="choose_zone_cat">
							<div class="col-4 choose-title"><b>Fabric</b></div>
							<div class="col-8"></div>
							<div class="col-4" style="margin-top: -1px; background-color: #c3f5f6; border-left: 1px solid #AAA;"></div>
							<div class="col-8" style="height:5px; border-top: 1px solid #AAA; border-radius: 0px 5px 0px 0px; background-color: #c3f5f6;"></div>
							<div class="col-12 choose-content">
								<div class="row">
								
								
								<?php
								$sql_cat = "SELECT * FROM cat WHERE enable=1 ORDER BY cat_name_en ASC";
								$rs_cat = $conn->query($sql_cat);

								$n_count = 0;
								while ($row_cat = $rs_cat->fetch_assoc()){
									if( ($n_count%3)==2 ){
										$specific_set = 'style="margin-left: -3px;"';
									}else if( ($n_count%3)==0 ){
										$specific_set = 'style="margin-left: 3px;"';
									}else{
										$specific_set = 'style="margin-left: -2px; style="margin-right: -2px;"';
									}
								?>
									<div <?php echo $specific_set; ?> class="col-4 cat-item" id="d_choose_cat<?php echo $row_cat["cat_id"]; ?>" onclick="showColorInCat(<?php echo $row_cat["cat_id"]; ?>);">
										<input id="choose_cat<?php echo $row_cat["cat_id"]; ?>" class="in_checkbox_cat" type="checkbox" name="cat_check[]" value="<?php echo $row_cat["cat_id"]; ?>" onclick="chooseCat(<?php echo $row_cat["cat_id"]; ?>);">
										<input type="hidden" id="cat_color_chose<?php echo $row_cat["cat_id"]; ?>" >
										<span id="sp_cat_name<?php echo $row_cat["cat_id"]; ?>"><?php echo $row_cat["cat_name_en"]; ?></span>
									</div>
								<?php
									$n_count++;
								}
								?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="row" id="choose_zone_color">
							<div class="col-5 choose-title-color"><b>Color of </b><span id="sp_choose_cat"></span></div>
							<div class="col-7"></div>
							<div class="col-5" style="margin-top: -1px; background-color: #f6f69b; border-left: 1px solid #AAA;"></div>
							<div class="col-7" style="height:5px; border-top: 1px solid #AAA; border-radius: 0px 5px 0px 0px; background-color: #f6f69b;"></div>
							<div class="col-12 choose-content-color">
								<div class="row" id="color_content">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-4"></div>
					<div class="col-4" style="padding: 10px;">
						<input class="btn btn-success" type="button" value="Gen report" onclick="showContentBelow();" style="width: 100%;">
					</div>
					<div class="col-4"></div>
				</div>
				<div class="row" id="content_zone">
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function printThis(){
	var divContents = $("#print_this").html();
    var printWindow = window.open('', '', 'height=2000,width=1200');
    printWindow.document.write('<html><head><title>Adjust form @ <?php echo date("Y-m-d"); ?></title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function showColorInCat(cat_id){
	$('.cat-item').removeClass("active");
	$('#d_choose_cat'+cat_id).addClass("active");
	var cat_name = $('#sp_cat_name'+cat_id).html();
	$('#sp_choose_cat').html(cat_name);

	$('#color_content').html('&nbsp;&nbsp;<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_colors_choose.php" ,
		data:{
			"cat_id":cat_id
		},
		success: function(resp){
			$('#color_content').html(resp);
			restoreChecked(cat_id);
		}
	});

}

function restoreChecked(cat_id){

	var cat_color_chose = $('#cat_color_chose'+cat_id).val();

	if(cat_color_chose!=""){
		var tmp_chose = cat_color_chose.split("#C#");
		for(var i=0;i<tmp_chose.length;i++){
			$('.in_checkbox_color').each(function(){
				if( tmp_chose[i]==$(this).val() ){
					$(this).prop("checked",true);
				}
			});
		}
	}

}

function selectAllColor(cat_id){

	if($('#select_all_color').prop("checked")){
		$('.in_checkbox_color').prop("checked",true);
	}else{
		$('.in_checkbox_color').prop("checked",false);
		$('#choose_cat'+cat_id).prop("checked",false);
	}

	$('#cat_color_chose'+cat_id).val('');

	var is_list = "";
	$('.in_checkbox_color').each(function(){
		
		var content_id = $(this).attr("data-row_id");
		chooseColor(cat_id,content_id);
	});

}

function chooseCat(cat_id){

	if( !$('#choose_cat'+cat_id).prop("checked")){
		$('#cat_color_chose'+cat_id).val('');
	}

}

function chooseColor(cat_id,content_id){

	var cat_color_chose = $('#cat_color_chose'+cat_id).val();

	var use_color_name = "";
	if($('#choose_color_'+cat_id+"_"+content_id).val()=="PROCESS BLUE"){
		use_color_name = "PRCEBL";
	}else{
		use_color_name = $('#choose_color_'+cat_id+"_"+content_id).val();
	}

	if( $('#choose_color_'+cat_id+"_"+content_id).prop("checked") ){

		if(cat_color_chose!=""){
			cat_color_chose += "#C#"+use_color_name;
		}else{
			cat_color_chose = use_color_name;
		}

		$('#choose_cat'+cat_id).prop("checked",true);
		
	}else{

		var tmp_chose = cat_color_chose.split("#C#");
		if(tmp_chose.length>1){
			cat_color_chose = cat_color_chose.replace( "#C#"+use_color_name+"#C#","#C#" );
			cat_color_chose = cat_color_chose.replace( use_color_name+"#C#","" );
			cat_color_chose = cat_color_chose.replace( "#C#"+use_color_name,"" );
		}else{
			cat_color_chose = cat_color_chose.replace( use_color_name,"" );
		}

	}

	$('#cat_color_chose'+cat_id).val(cat_color_chose);

	var check_empty = true;
	$('.in_checkbox_color').each(function(){

		if( $(this).prop("checked") ){
			check_empty = false;
		}
	});

	if(check_empty){
		//selectAllColor(cat_id);
		$('#cat_color_chose'+cat_id).val('');
		$('#choose_cat'+cat_id).prop("checked",false);
	}

}

function showContentBelow(){
	
	$('#content_zone').html('&nbsp;&nbsp;<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	var n_index = 0;
	var a_data = new Array();

	$('.in_checkbox_cat').each(function(){

		cat_id = $(this).val();

		if( $('#cat_color_chose'+cat_id).val()!="" ){
			tmp_obj = { "cat_id":cat_id , "color":$('#cat_color_chose'+cat_id).val()  };
			a_data[n_index] = JSON.stringify(tmp_obj);

			n_index++;
		}
	});
	
	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/adjust/show_fabric_rolls.php" ,
		data:{
			"json_data":JSON.stringify(a_data)
		},
		success: function(resp){
			$('#content_zone').html(resp);
			
		}
	});
	
}
</script>
<?php
}
?>