<?php
session_start();

require_once('../../db.php');

$access_id = $_POST['access_id'];
$sql = "SELECT *,accessory_table.cat_id AS main_cat_id FROM accessory_table LEFT JOIN cat ON cat.cat_id=accessory_table.cat_id LEFT JOIN supplier ON supplier.supplier_id=accessory_table.sup_id WHERE access_id='".$access_id."'";
if($query=$conn->query($sql)){
    $fetcher = $query->fetch_assoc();
    die(json_encode(array('status'=>'1','data'=>$fetcher)));
}
else{
    die(json_encode(array('status'=>'0')));
}