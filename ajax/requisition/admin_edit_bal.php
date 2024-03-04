<?php
session_start();
require_once('../../db.php');


$sql_update = "UPDATE tbl_rq_form_item SET balance_before='".$_POST["bal_before"]."',balance_after='".$_POST["bal_after"]."',used='".$_POST["bal_used"]."' WHERE rq_item_id='".$_POST["rq_item_id"]."'; ";
$conn->query($sql_update);

$a_result["result"] = "success";

echo json_encode($a_result);
?>