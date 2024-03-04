<?php

require_once('../../db.php');

$sql_select = "SELECT * FROM tbl_f_purchase ";
$sql_select .= " LEFT JOIN supplier ON supplier.supplier_id=tbl_f_purchase.supplier_id ";
$sql_select .= " LEFT JOIN cat ON cat.cat_id=tbl_f_purchase.cat_id ";
$sql_select .= " LEFT JOIN tbl_color ON tbl_color.color_id=tbl_f_purchase.color_id ";

$sql_select .= " WHERE tbl_f_purchase.mark_ordered=0 ";
$sql_select .= " ORDER BY supplier.supplier_name ASC,cat.cat_name_en ASC,tbl_color.color_name ASC; ";
$rs_select = $conn->query($sql_select);

$a_row_select = array();
$a_supp = array();

while( $row_select = $rs_select->fetch_assoc() ){
	$a_row_select[] = $row_select;
	$a_supp[($row_select["supplier_id"])]["name"] = $row_select["supplier_name"];
	if(isset($a_supp[($row_select["supplier_id"])]["num_item"])){
		$a_supp[($row_select["supplier_id"])]["num_item"]++;
	}else{
		$a_supp[($row_select["supplier_id"])]["num_item"] = 1;
	}
}

?>
Supplier: <select id="select_supp" name="select_supplier_id" style="margin-bottom: 5px;" onchange="return changeSuppGroup();">
<?php
foreach($a_supp as $supplier_id => $supp_data){

?>
	<option value="<?php echo $supplier_id; ?>"><?php echo $supp_data["name"]." ( ".$supp_data["num_item"]." )"; ?></option>
<?php
}
?>
</select>

<table class="tbl_show_fc_purchase_list">
	<tr>
		<th>Supplier</th><th>Fabric</th><th>Color</th><th>QTY(Kg)</th><th>Select</th>
	</tr>
	<tbody id="tbo_fpu_list">
		<?php 
		
		foreach( $a_row_select as $tmp_key => $row_select ){	
		?>
		<tr class="supp_group supp_group_id<?php echo $row_select["supplier_id"]; ?>">
			
			<td><?php echo $row_select["supplier_name"]; ?></td>
			<td><?php echo $row_select["cat_name_en"]; ?></td>
			<td><?php echo $row_select["color_name"]; ?></td>
			<td style="text-align: right;" class="fpu_value<?php echo $row_select["supplier_id"]; ?>"><?php echo $row_select["fpu_value"]; ?></td>
			<td><input class="chk_fpu" type="checkbox" name="a_fpu_id[]" value="<?php echo $row_select["fpu_id"]; ?>"></td>
		</tr>
		<?php
			
		}
		?>
		<tr class="total_row">
			<td colspan="3"><b>Total</b></td>
			<td style="text-align: right; font-style: bold;" id="show_total"></td>
			<td></td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
	changeSuppGroup();

</script>