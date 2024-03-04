<?php
require_once('../../db.php');
$rq_id = $_POST['rq_id'];
$sql = "SELECT * FROM tbl_rq_form WHERE rq_id='$rq_id'";
$rs_select_all = $conn->query($sql);
$row_select_all = $rs_select_all->fetch_assoc();
die(json_encode(array('status'=>1,'data'=>$row_select_all)));
?>