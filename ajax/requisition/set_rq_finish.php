<?php
require_once('../../db.php');

if( isset($_POST["finish_rq_id"]) && ($_POST["finish_rq_id"]!="") ){
	$sql_update = "UPDATE tbl_rq_form SET rq_status='finish',finish_date='".date("Y-m-d H:i:s")."' WHERE rq_id='".$_POST["finish_rq_id"]."'; ";
	$conn->query($sql_update);

	$sql_select = "SELECT order_code FROM tbl_rq_form WHERE rq_id='".$_POST["finish_rq_id"]."'; ";
	$rs_select = $conn->query($sql_select);
	$row_select = $rs_select->fetch_assoc();

	$sql_update2 = "UPDATE forecast_head SET is_produced=1 WHERE forecast_order='".$row_select["order_code"]."' ";
	$conn->query($sql_update2);
}
?>
<script type="text/javascript">
window.parent.location.replace("<?php echo $main_path."?vp=".base64_encode('rq_list')."&op=".base64_encode('finish'); ?>");
</script>