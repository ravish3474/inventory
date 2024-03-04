<?php

$sql_up = 'UPDATE employee SET 
			employee_stat = "0" ,
			employee_login_time = "0000-00-00 00:00:00" 
			WHERE employee_id = "'.$_SESSION['employee_id'].'" ';
$query = mysqli_query($conn,$sql_up);

session_destroy();

setcookie("JOG_login_info");

echo "<meta http-equiv=\"refresh\" content=\"0;URL=https://inv.jog-joinourgame.com/login.php\">";
//echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://".$_SERVER["SERVER_NAME"]."/inventory/login.php\">";
?>