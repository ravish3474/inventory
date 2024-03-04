<?php
require_once('../../function.php');

$a_return = array();

$a_return["result"] = number_to_string($_POST["s_number"]);

echo json_encode($a_return);
?>