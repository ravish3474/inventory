<?php
if( !isset($_POST["cat_id"]) || !isset($_POST["color_id"]) ){
	echo "<center><b><font color=red>Fail: Invalid parameter.</font></b></center>";
	exit();
}

require_once('../../db.php');

$cat_id = $_POST["cat_id"];
$color_id = $_POST["color_id"];

$sql_select = "SELECT * FROM supplier ";
$sql_select .= " WHERE supplier_id>0 AND supplier_name NOT LIKE 'STOCK-%' ";
$sql_select .= " ORDER BY ";

$a_supp_show_first = array();
if($_POST["supp_id_list"]!=""){
	$sql_select .= " supplier_id IN (".$_POST["supp_id_list"].") DESC, ";

	$a_supp_show_first = explode(",", $_POST["supp_id_list"]);
}
$sql_select .= " supplier_name ASC ";

$rs_select = $conn->query($sql_select);

$a_select_first = array();
$a_select_last = array();
$count_data = 0;
while( $row_select = $rs_select->fetch_assoc() ){

	$count_data++;
	if($count_data <= sizeof($a_supp_show_first) ){
		$a_select_first[] = $row_select;
	}else{
		$a_select_last[] = $row_select;
	}
}

$inner_form = '<div class="div_form col-6">';
$inner_form .= '<select name="select_supp[]" style="width: 100%; height: 40px;">';
$inner_form .= '<optgroup label="Used to be order">';
foreach($a_select_first as $tmp_key=>$row_supp){ 
	$inner_form .= '<option value="'.$row_supp["supplier_id"].'">'.$row_supp["supplier_name"].'</option>';
}
$inner_form .= '</optgroup>';
$inner_form .= '<optgroup label="Not used to be order">';
foreach($a_select_last as $tmp_key=>$row_supp){
	$inner_form .= '<option value="'.$row_supp["supplier_id"].'">'.$row_supp["supplier_name"].'</option>';
}
$inner_form .= '</optgroup>';
$inner_form .= '</select>';
$inner_form .= '</div>';
$inner_form .= '<div class="div_form col-6">';
$inner_form .= '<input name="qty_value[]" style="width: 100%; height: 40px; border: 1px solid #000; text-align: center;" type="number" min="0">';
$inner_form .= '</div>';

//$tmp_inner_form = base64_encode($inner_form);
?>
<div id="inner_form" style="display: none;">
	<?php echo $inner_form; ?>
</div>
<form id="form_add_foc">
<div class="row" id="foc_content_zone">
	<div class="div_form col-6">
		Supplier
	</div>
	<div class="div_form col-6">
		Quantity (Kg)
	</div>

	<input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
	<input type="hidden" name="color_id" value="<?php echo $color_id; ?>">
	<?php
	echo $inner_form;
	?>
</div>
<div class="row">	
	<div class="col-12">
		<hr>
	</div>
	<div class="col-12" style="text-align: center;">
		<button type="button" style="width:30%;" class="btn btn-primary" onclick="return addRowFOCPurchase();">Add Row</button>
		<button type="button" style="width:30%;" class="btn btn-success" onclick="return submitNewFOCPurchase(<?php echo $color_id; ?>);">Submit</button>
	</div>
</div>
</form>