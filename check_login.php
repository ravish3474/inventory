<?php
session_start();
ob_start();
include('db.php');
include('function.php');

if (isset($_POST["employee_email"])) {
	$strUser = $_POST["employee_email"];
}
if (isset($_POST["employee_password"])) {
	$strPass = md5($_POST["employee_password"]);
}

$sql = 'SELECT * FROM employee where employee_email="' . $strUser . '" and employee_password="' . $strPass . '"';
$query = $conn->query($sql);
$rs = $query->fetch_assoc();

$strDate = date('Y-m-d H:i:s');

if ($rs['employee_name'] == '') {
	echo '<meta http-equiv="refresh" content="0;URL=login.php?err=r">';
} else {
	$_SESSION['employee_id'] = $rs['employee_id'];
	$_SESSION['employee_name'] = $rs['employee_name'];
	$_SESSION['employee_email'] = $rs['employee_email'];
	$_SESSION['employee_tel'] = $rs['employee_tel'];
	$_SESSION['employee_position_id'] = $rs['employee_position_id'];
	$_SESSION['customer_id'] = $rs['customer_id'];
	$_SESSION['employee_image'] = $rs['employee_image'];


	if ((strtotime(date("Y-m-d H:i:s")) > strtotime(date("Y-m-d 18:00:00"))) || (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 08:00:00")))) {
		$exp_in = 3600; // 1 hour
	} else {
		$exp_in = 36000; // 10 hours
	}
	setcookie("JOG_login_info");
	setcookie("JOG_login_info", $rs['employee_id'], time() + $exp_in);


	session_write_close();

	$sql_up = 'UPDATE employee SET 
					employee_login_stat = "1" ,
					employee_login_time = "' . $strDate . '" , 
					employee_last_login = "' . $strDate . '" , 
					cookie_start = "' . $strDate . '" 
					WHERE employee_id = "' . $rs['employee_id'] . '" ';
	$query = mysqli_query($conn, $sql_up);

	//setcookie("employee_email",$rs['employee_email'],time()+3600*24*356);

	//echo '<meta http-equiv="refresh" content="0;URL=https://inv.jog-joinourgame.com/">';
	echo '<meta http-equiv="refresh" content="0;URL=http://' . $_SERVER["SERVER_NAME"] . '/inventory">';
}
