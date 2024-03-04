<?php

require_once('../../db.php');



for($i=0;$i<sizeof($_POST["after_bal"]);$i++){
	
	if($_POST["after_bal"][$i]!=""){

		$sql_update = "UPDATE tbl_rq_form_item SET balance_after='".$_POST["after_bal"][$i]."',used=balance_before-".$_POST["after_bal"][$i].",mark_cut_stock=1,item_note='".$_POST["item_note"][$i]."',cut_date='".date("Y-m-d H:i:s")."' WHERE rq_item_id='".$_POST["rq_item_id"][$i]."'; ";
		$conn->query($sql_update);

		$used = floatval($_POST["before_bal"][$i])-floatval($_POST["after_bal"][$i]);

		$sql_update2 = "UPDATE fabric SET on_producing=0,fabric_used=fabric_used+".$used.",fabric_balance=".$_POST["after_bal"][$i].",fabric_total=(fabric_used*fabric_in_price),fabric_date_update='".date("Y-m-d H:i:s")."' WHERE fabric_id='".$_POST["fabric_id"][$i]."'; ";
		$conn->query($sql_update2);

	}
	
}

?>
<script type="text/javascript">
window.parent.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list')."&op=".base64_encode('new'); ?>");
</script>