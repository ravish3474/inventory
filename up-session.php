<?php
	session_start();
	ob_start();
	include('db.php');
	include('function.php');
	$strDate = date('Y-m-d H:i:s');
	$intervalT=7200;
	
	if( isset($_SESSION['employee_id']) || isset($_COOKIE["JOG_login_info"]) ){


		if(isset($_COOKIE["JOG_login_info"])){

			$employee_id = $_COOKIE["JOG_login_info"];

		}else{
			$employee_id = $_SESSION["employee_id"];
		}

		if( strtotime(date("Y-m-d H:i:s")) > strtotime(date("Y-m-d 18:00:00"))  ){
			$exp_in = 3600; // 1 hour
		}else{
			$exp_in = 36000; // 10 hours
		}
		setcookie("JOG_login_info");
		setcookie("JOG_login_info",$employee_id,time()+$exp_in);
		
		$sql_log = 'SELECT * FROM employee where employee_id="'.$employee_id.'"';
		$query_log = $conn->query($sql_log);
		$rs_log = $query_log->fetch_assoc();

		$p_name = $rs_log['employee_name'];
		
		$t_log = strtotime($rs_log['employee_login_time']);
		$n_date = strtotime($strDate);

		$n_log = $n_date-$t_log;
		
		

		if(isset($_COOKIE["JOG_login_info"])){

			$_SESSION['employee_id'] = $rs_log['employee_id'];
			$_SESSION['employee_name'] = $rs_log['employee_name'];
			$_SESSION['employee_email'] = $rs_log['employee_email'];
			$_SESSION['employee_tel'] = $rs_log['employee_tel'];
			$_SESSION['employee_position_id'] = $rs_log['employee_position_id'];
			$_SESSION['customer_id'] = $rs_log['customer_id'];
			$_SESSION['employee_image'] = $rs_log['employee_image'];
			$_SESSION['magnifier_mode'] = $rs_log['magnifier_mode'];
			
			$sql_up = 'UPDATE employee SET 
						employee_login_stat = "1" ,
						cookie_start = "'.$strDate.'"  
						WHERE employee_id = "'.$employee_id.'" ';
			mysqli_query($conn,$sql_up);

		}else{
			
			if($n_log>=$intervalT){
				$sql_up = 'UPDATE employee SET 
							employee_login_stat = "0" ,
							employee_login_time = "0000-00-00 00:00:00"
							WHERE employee_id = "'.$employee_id.'" ';
				$query = mysqli_query($conn,$sql_up);
				session_destroy();
			}else{
				$sql_up = 'UPDATE employee SET 
							employee_login_time = "'.$strDate.'"
							WHERE employee_id = "'.$employee_id.'" ';
				$query = mysqli_query($conn,$sql_up);
			}
		}
	}
	
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
		$pageURL = 'https';
	}else{
		$pageURL = 'http';
	}
	
	$pageURL .= '://';

	if($_SERVER['SERVER_PORT']!='80'){
		$pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].''.$_SERVER['REQUEST_URI'];
	}else{
		$pageURL .= $_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'];
	}

?>