<?php
require_once('../../db.php');

$a_data = json_decode($_POST["json_data"]);
$a_sql_select = array();

for($i=0;$i<sizeof($a_data);$i++){
	
	$a_chose_data = json_decode($a_data[$i]);

	$tmp_data = explode("#C#",$a_chose_data->color);
	foreach($tmp_data as $key => $value){
		if($value=="PRCEBL"){
			$tmp_data[$key] = "PROCESS BLUE";
		}else{
			$tmp_data[$key] = addslashes($value);
		}
		
	}
	$s_tmp_data = "'".implode("','",$tmp_data)."'";

	$a_sql_select[] = " ( fabric.cat_id=".$a_chose_data->cat_id." AND fabric.fabric_color IN (".$s_tmp_data.") ) ";
}

$sql_select = "SELECT * FROM fabric LEFT JOIN cat ON fabric.cat_id=cat.cat_id WHERE fabric.fabric_balance>0 AND fabric.on_producing=0 AND ";
$sql_select .= "(".implode("OR",$a_sql_select).") ORDER BY cat.cat_name_en ASC,fabric.fabric_color ASC,fabric.fabric_box ASC,fabric.fabric_no ASC";

// echo $sql_select;
// exit();

$rs_select = $conn->query($sql_select);
?>

<?php
if($rs_select){
?>
<div class="col-2"></div>
<div class="col-8 text-center"><h4>Adjust form</h4></div>
<div class="col-2 text-center" style="padding: 5px;"><input class="btn btn-primary" type="button" value="Print" onclick="printThis();"></div>
<div id="print_this" style="width: 100%;">
<style type="text/css">
.tbl_content{
	width: 100%;
	font-size: 14px;

}
.tbl_content th{
	text-align: center;
	font-weight: bold;
	background-color: #63f895;
	border:1px solid #AAA;
}
.tbl_content td{
	text-align: center;
	border:1px solid #AAA;
	height: 35px;
}
.tbl_content tr:hover{
	background-color: #EEE;
}
.outter-table{
	width:100%;
}
</style>
	<table class="outter-table" ><tr>
<?php
	$n_count = 0;
	$flag_add_more_row = false;

	$n_break = intval($rs_select->num_rows/2);
	if(($rs_select->num_rows%2)==1){
		$n_break++;
		$flag_add_more_row = true;
	}

	while($row_select = $rs_select->fetch_assoc()){
		if($n_count==0){
			?>
<td width="50%">
<table class="tbl_content" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th>Fabric</th><th>Color</th><th>Box</th><th>No.</th><th>Balance</th><th>Update</th>
		</tr>
	</thead>
	<tbody>
			<?php
		}
?>
		<tr>
			<td><?php echo $row_select["cat_name_en"]; ?></td>
			<td><?php echo $row_select["fabric_color"]; ?></td>
			<td><?php echo $row_select["fabric_box"]; ?></td>
			<td><?php echo $row_select["fabric_no"]; ?></td>
			<td style="text-align: right;"><?php echo $row_select["fabric_balance"]; ?></td>
			<td>&nbsp;</td>
		</tr>
<?php
		$n_count++;

		if($n_count==$n_break){
			?>
	</tbody>
</table>
</td>
			<?php
			$n_count=0;
		}

	}

	if($flag_add_more_row){
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	}
	echo '</tbody></table></td>';

}else{
	echo '<div class="col-12">Not found data</div>';
}
?>
</tr>
</table>
</div>