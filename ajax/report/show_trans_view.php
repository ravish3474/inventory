<?php
if( !isset($_POST["fabric_id"]) || ($_POST["fabric_id"]=="") ){
	$a_result["result"] = 'fail';
	$a_result["msg"] = '<b>Invalid parameter</b>';
	exit();
}

require_once('../../db.php');

$fabric_id = $_POST["fabric_id"];

$sql_data = "SELECT fabric_date_create AS trans_date,'PO' AS trans_process,'IN' AS in_out,fabric_in_piece AS trans_value,po_id AS ref_id FROM fabric WHERE fabric_id=".$fabric_id." AND po_id<>0 AND new_form=0 ";
$sql_data .= "UNION ";
$sql_data .= "SELECT fabric.fabric_date_create AS trans_date,'STOCK-IN' AS trans_process,'IN' AS in_out,fabric.fabric_in_piece AS trans_value,tbl_packing_list.pac_id AS ref_id FROM fabric LEFT JOIN tbl_packing_list ON fabric.fabric_id=tbl_packing_list.fabric_id WHERE fabric.fabric_id=".$fabric_id." AND fabric.new_form=1 ";
$sql_data .= "UNION ";
$sql_data .= "SELECT adj_date AS trans_date,'ADJUST' AS trans_process,in_out,adj_value AS trans_value,adj_id AS ref_id FROM tbl_adjust WHERE fabric_id=".$fabric_id." ";
$sql_data .= "UNION ";
$sql_data .= "SELECT used_head.used_date AS trans_date,'NO-CODE' AS trans_process,'OUT' AS in_out,used_detail.used_detail_used AS trans_value,used_detail.used_id AS ref_id FROM used_detail ";
$sql_data .= "LEFT JOIN used_head ON used_detail.used_id=used_head.used_id WHERE materials_id=".$fabric_id." AND used_detail.used_detail_used<>0 ";
$sql_data .= "UNION ";
$sql_data .= "SELECT cut_date AS trans_date,'RQ' AS trans_process,'OUT' AS in_out,used AS trans_value,rq_id AS ref_id FROM tbl_rq_form_item WHERE fabric_id=".$fabric_id." AND mark_cut_stock=1 ";
$sql_data .= "ORDER BY trans_date ASC ";

$rs_data = $conn->query($sql_data);

$balance = 0.0;


?>
<div class="col-12 text-center" >
	<h5 align="center">Transaction</h5>
	<h6 align="center">Box: <?php echo $_POST["fabric_box"]; ?> | No. <?php echo $_POST["fabric_no"]; ?></h6>
</div>
<div class="col-1" ></div>
<div class="col-10" >
	<table width="100%" class="trans_tbl">
		<thead>
			<tr>
				<th class="text-center">Date</th>
				<th class="text-center">Process</th>
				<th class="text-center">IN</th>
				<th class="text-center">OUT</th>
				<th class="text-center">Balance</th>
				<th class="text-center">Document</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$flag_no_record = 0;
			$index_after_no_record = 1;
			$balance_before = 0.0;
			$have_after_info = 0;

			while($row_data = $rs_data->fetch_assoc()){

				$balance_before = $balance;

				if($row_data["in_out"]=="IN"){
					$balance += $row_data["trans_value"];
				}else{
					$balance -= $row_data["trans_value"];
				}

				if(strtotime($row_data["trans_date"]) > strtotime("2019-07-31 23:59:59")){
					$have_after_info = 1;

					if($flag_no_record==0){
						?>
						<tr style="display: none;" id="tr_no_record">
							<td class="text-center">2019-07-31 11:11:11</td>
							<td class="text-center">NO RECORD</td>
							<td class="text-center" id="td_no_record_in"></td>
							<td class="text-center" id="td_no_record_out"></td>
							<td class="text-center" id="td_no_record_balance"><?php echo number_format($balance_before,2); ?></td>
							<td class="text-center">&nbsp;</td>
						</tr>
						<?php
						$flag_no_record = 1;
					}
					
				}

				if($flag_no_record==1){
					$inner_record_in = 'id="td_record_in'.$index_after_no_record.'"';
					$inner_record_out = 'id="td_record_out'.$index_after_no_record.'"';
					$inner_record_bal = 'id="td_record_bal'.$index_after_no_record.'"';
				}else{
					$inner_record_in = '';
					$inner_record_out = '';
					$inner_record_bal = '';
				}
			?>
			<tr>
				<td class="text-center"><?php echo $row_data["trans_date"]; ?></td>
				<td class="text-center"><?php echo $row_data["trans_process"]; ?></td>
				<td class="text-center" <?php echo $inner_record_in; ?> ><?php echo ($row_data["in_out"]=="IN")?number_format($row_data["trans_value"],2):"-"; ?></td>
				<td class="text-center" <?php echo $inner_record_out; ?> ><?php echo ($row_data["in_out"]=="OUT")?number_format($row_data["trans_value"],2):"-"; ?></td>
				<td class="text-center" <?php echo $inner_record_bal; ?> ><?php echo number_format($balance,2); ?></td>
				<td class="text-center">
					<?php
					if($row_data["trans_process"]!="ADJUST"){
					?>
					<i class="fa fa-file-text-o show-doc" onclick="showTransDocument('<?php echo $row_data["trans_process"]; ?>',<?php echo $row_data["ref_id"]; ?>,'<?php echo $_POST["fabric_box"]; ?>','<?php echo $_POST["fabric_no"]; ?>');" data-toggle="modal" data-target="#showTransDocModal"></i>
					<?php
					}else{

						$sql_remark = "SELECT remark FROM tbl_adjust WHERE adj_id='".$row_data["ref_id"]."' ";
						$rs_remark = $conn->query($sql_remark);
						$row_remark = $rs_remark->fetch_assoc();

						if($row_remark["remark"]!=""){
					?>
					<i class="fa fa-file-text-o show-doc" onclick="showRemoveRemark(<?php echo $row_data["ref_id"]; ?>);" data-toggle="modal" data-target="#showRemarkModal"></i>
					<input type="hidden" id="h_remark<?php echo $row_data["ref_id"]; ?>" value="<?php echo $row_remark["remark"]; ?>">
					<?php
						}else{
							echo "&nbsp;";
						}
					}
					?>
				</td>
			</tr>
			<?php
				if($flag_no_record==1){

					$index_after_no_record++;
				}
			}

			if($have_after_info==0){
				?>
				<tr style="display: none;" id="tr_no_record">
					<td class="text-center">2019-07-31 11:11:11</td>
					<td class="text-center">NO RECORD</td>
					<td class="text-center" id="td_no_record_in"></td>
					<td class="text-center" id="td_no_record_out"></td>
					<td class="text-center" id="td_no_record_balance"><?php echo number_format($balance,2); ?></td>
					<td class="text-center">&nbsp;</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
<div class="col-1" ></div>
<?php
if(round($balance,2)!=round($_POST["fabric_balance"],2)){

	$bal_diff = $balance-$_POST["fabric_balance"];
	$tmp_in = 0.0;
	$tmp_out = 0.0;
	$tmp_in_out = "";
	if($bal_diff<0){
		$tmp_in = $bal_diff*(-1);
		$tmp_in_out = "IN";
	}else{
		$tmp_out = $bal_diff;
		$tmp_in_out = "OUT";
	}
?>
<script type="text/javascript">

$('#tr_no_record').show(3000);

setTimeout(function(){

	var last_bal = parseFloat($('#td_no_record_balance').html())-(<?php echo $bal_diff; ?>);

	tmp_in = parseFloat('<?php echo $tmp_in; ?>');
	tmp_out = parseFloat('<?php echo $tmp_out; ?>');
	if(tmp_in!=0){
		show_tmp_in = tmp_in.toFixed(2);
	}else{
		show_tmp_in = "-";
	}
	if(tmp_out!=0){
		show_tmp_out = tmp_out.toFixed(2);
	}else{
		show_tmp_out = "-";
	}
	
	$('#td_no_record_in').html(show_tmp_in);
	$('#td_no_record_out').html(show_tmp_out);
	$('#td_no_record_balance').html(last_bal.toFixed(2));

	for(var i=1; i < <?php echo $index_after_no_record; ?>; i++){

		if( $('#td_record_in'+i).html() != "-" ){
			last_bal += parseFloat($('#td_record_in'+i).html());
		}else if( $('#td_record_out'+i).html() != "-" ){
			last_bal -= parseFloat($('#td_record_out'+i).html());
		}

		$('#td_record_bal'+i).html(last_bal.toFixed(2));
	}

}, 3000);
</script>
<?php
}
?>