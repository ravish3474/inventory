<?php
require_once('../../db.php');

$search_type = $_POST["search_type"];
$search_value = $_POST["search_value"];

$num_data_per_page = 50;
if($_POST["select_page"]==""){
	$select_page = 1;
}else{
	$select_page = intval($_POST["select_page"]);
}


$sql_select = "SELECT COUNT(*) AS page_num FROM tbl_po_content  ";
$sql_select .= " WHERE tbl_po_content.enable=1 ";
if( ($search_type!="all") && ($search_value!="") ){
	$sql_select .= " AND ".$search_type." LIKE '%".$search_value."%' ";
}else if( ($search_type=="all") && ($search_value!="") ){
	$sql_select .= " AND (po_number LIKE '%".$search_value."%' OR po_date LIKE '%".$search_value."%' OR sup_name LIKE '%".$search_value."%') ";
}
$sql_select .= " ;" ;

$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();
$n_page_num = intval(intval($row_select["page_num"])/$num_data_per_page);
if( (intval($row_select["page_num"])%$num_data_per_page)>0 ){
	$n_page_num++;
}

for($i=1;$i<=$n_page_num;$i++){
?><option value="<?php echo $i;?>" <?php if($i==$select_page){ echo "selected"; } ?>><?php echo $i;?></option><?php
	$show_row_no++;
}
?>