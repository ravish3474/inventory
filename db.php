<?php
	ini_set('display_errors', 1);
	error_reporting(~0);
	
	date_default_timezone_set("Asia/Bangkok");
	
	$serverName = "localhost";
	$userName = "root";
	$userPassword = "";
	$dbName = "jogjoino_inventory";
	$dbName2 = "jogjoino_lockerroom";
	
	
	$conn = new mysqli($serverName,$userName,$userPassword,$dbName);
	
	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_errno) {
		echo $conn->connect_error;
		exit;
	}else{}

	$tmp_str = explode($_SERVER["SERVER_NAME"],".");
	$main_path = "";
	if($_SERVER["SERVER_NAME"]=="localhost"){
		$main_path = "http://localhost/inventory/";
	}else if( $tmp_str[0]=="192" ){
		$main_path = "http://".$_SERVER["SERVER_NAME"]."/";
	}else{
		$main_path = "https://".$_SERVER["SERVER_NAME"]."/";
	}
?>
