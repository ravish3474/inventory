<?php
require_once('db.php');

$sql_check = "SELECT fabric_id,fabric_in_piece,fabric_balance FROM fabric ORDER BY fabric_id ASC;";
$rs_check = $conn->query($sql_check);

$count = 1;
$a_fabric_id = array();
$a_fabric_id_plus = array();

while($row_check = $rs_check->fetch_assoc()){

	$fabric_id = $row_check["fabric_id"];
	$fabric_in_piece = floatval($row_check["fabric_in_piece"]);
	$fabric_balance = floatval($row_check["fabric_balance"]);

	/*$sql_data = "SELECT 'IN' AS in_out,fabric_in_piece AS trans_value FROM fabric WHERE fabric_id=".$fabric_id." AND po_id<>0 AND new_form=0 ";
	$sql_data .= "UNION ";
	$sql_data .= "SELECT 'IN' AS in_out,fabric.fabric_in_piece AS trans_value FROM fabric LEFT JOIN tbl_packing_list ON fabric.fabric_id=tbl_packing_list.fabric_id WHERE fabric.fabric_id=".$fabric_id." AND fabric.new_form=1 ";
	$sql_data .= "UNION ";*/
	$sql_data = "SELECT in_out,adj_value AS trans_value,adj_id AS ref_id,'AD' AS trans_process FROM tbl_adjust WHERE fabric_id=".$fabric_id." ";
	$sql_data .= " UNION ";
	$sql_data .= "SELECT 'OUT' AS in_out,used_detail_used AS trans_value,used_id AS ref_id,'NO' AS trans_process FROM used_detail WHERE materials_id=".$fabric_id." AND used_detail_used<>0 ";
	$sql_data .= " UNION ";
	$sql_data .= "SELECT 'OUT' AS in_out,used AS trans_value,rq_item_id AS ref_id,'RQ' AS trans_process FROM tbl_rq_form_item WHERE fabric_id=".$fabric_id." AND mark_cut_stock=1 ";
	$rs_data = $conn->query($sql_data);



	$tmp_check = 0;
	$tmp_str = "";
	$plus_mark = 0;

	while($row_data = $rs_data->fetch_assoc()){
		$in_out = strtolower($row_data["in_out"]);
		$trans_value = floatval($row_data["trans_value"]);

		if($in_out=="in"){
			$tmp_check += $trans_value;
			$tmp_str .= "+".$trans_value;
			$plus_mark = 1;
		}else{
			$tmp_check -= $trans_value;
			$tmp_str .= "-".$trans_value;
		}

	}


	if( ( (($fabric_in_piece+$tmp_check)."A")!=(($fabric_balance)."A") ) && ($fabric_balance!=0) ){
		//echo "<font color=red>{Abnormal Balance}</font>";
		echo $count."). [ID=".$fabric_id."] IN=".$fabric_in_piece.",TRANSACTION=".$tmp_check."(".$tmp_str."),BALANCE(Record)=".$fabric_balance.",BALANCE(Real)=".($fabric_in_piece+$tmp_check);
		echo "<br>";
		if($plus_mark==1){
			$a_fabric_id_plus[] = $fabric_id;
		}else{
			$a_fabric_id[] = $fabric_id;
		}

		$count++;
	}
	
}

if(sizeof($a_fabric_id)>0){
	echo "<hr>Normal====".implode(",", $a_fabric_id);
}
if(sizeof($a_fabric_id_plus)>0){
	echo "<hr>Plus====".implode(",", $a_fabric_id_plus);
}
?>