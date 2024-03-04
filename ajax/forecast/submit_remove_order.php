<?php
require_once('../../db.php');

$n_count = sizeof($_POST["order_lkr_title_id"]);
$s_order_lkr_title_id = "";

for($i=0;$i<$n_count;$i++){
	if($i==0){
		$s_order_lkr_title_id = $_POST["order_lkr_title_id"][$i];
	}else{
		$s_order_lkr_title_id .= ",".$_POST["order_lkr_title_id"][$i];
	}
}

if($_POST["from_where"]=="forecast"){
	$sql_update = "UPDATE tbl_order_lkr_title SET to_forecast=2 WHERE order_lkr_title_id IN (".$s_order_lkr_title_id."); ";
	$conn->query($sql_update);
}else if($_POST["from_where"]=="withdrawal"){
	$sql_update = "UPDATE tbl_order_lkr_title SET enable=0 WHERE order_lkr_title_id IN (".$s_order_lkr_title_id."); ";
	$conn->query($sql_update);
}


?>
<script type="text/javascript">
window.parent.removeOrderSuccess("<?php echo $s_order_lkr_title_id; ?>");
</script>