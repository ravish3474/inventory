<?php
session_start();

if(isset($_SESSION["employee_name"])){

	require_once('../../db.php');

	$forecast_id = $_POST["forecast_id"];
	$forecast_date = $_POST["forecast_date"];
	$forecast_user = $_SESSION["employee_name"];
	$forecast_update = date("Y-m-d H:i:s");
	$forecast_total = 0.0;

	for($i=0;$i<sizeof($_POST["row_used"]);$i++){
		$forecast_total += floatval($_POST["row_used"][$i]);
	}

	$sql_update = "UPDATE forecast_head SET forecast_total='".$forecast_total."',forecast_date='".$forecast_date."',forecast_update_user='".$forecast_user."',forecast_update='".$forecast_update."' ";
	$sql_update .= " WHERE forecast_id='".$forecast_id."'; ";
	$conn->query($sql_update);

	$sql_delete = "DELETE FROM forecast_detail WHERE forecast_id='".$forecast_id."'; ";
	$conn->query($sql_delete);

	if(sizeof($_POST["row_used"])>0 ){

		$sql_insert = "INSERT INTO forecast_detail (forecast_id,type_id,cat_id,color_id,forecast_detail_color,forecast_detail_used,forecast_detail_unit_type) VALUES ";

		for($i=0;$i<sizeof($_POST["row_used"]);$i++){

			if($i>0){
				$sql_insert .= ",";
			}
			$sql_insert .= "(";
			$sql_insert .= "'".$forecast_id."','".$_POST["row_type_id"][$i]."','".$_POST["row_cat_id"][$i]."','".$_POST["row_select_color_id"][$i]."','".$_POST["row_select_color"][$i]."','".$_POST["row_used"][$i]."','".$_POST["row_unit"][$i]."')";
			
		}

		$sql_insert .= ";";
		$conn->query($sql_insert);
	}

	?>
	<script type="text/javascript">
	window.parent.saveForecastSuccess();
	</script>
<?php

}else{
?>
	<script type="text/javascript">
	window.parent.saveForecastFail('Login Session Expired! Please login again.');
	</script>
<?php
}
?>