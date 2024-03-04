<?php
require_once('../../db.php');

if( !isset($_POST["fabric_id"]) || ($_POST["fabric_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$sql_fabric = "SELECT fabric.*,cat.cat_name_en,supplier.supplier_name,CAST(fabric_no AS UNSIGNED) AS fabric_no ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_fabric .= "LEFT JOIN supplier ON fabric.supplier_id=supplier.supplier_id ";
$sql_fabric .= "WHERE fabric.fabric_id='".$_POST["fabric_id"]."' ";

$rs_fabric = $conn->query($sql_fabric);
$row_fabric = $rs_fabric->fetch_assoc();

$sql_supplier = "SELECT * FROM supplier WHERE supplier_name NOT LIKE 'STOCK-%' ORDER BY supplier_name ASC; ";
$rs_supplier = $conn->query($sql_supplier);

?>
<div class="row">
	<div class="col-4 text-left">Supplier: </div>
	<div class="col-8 text-left">
		<select id="select_edit_supplier" onchange="return editChangeSupplier();">
			<option value="=none=">== Select Supplier ==</option>
			<?php
			while($row_supplier = $rs_supplier->fetch_assoc()){
				echo '<option value="'.$row_supplier["supplier_id"].'" ';
				if( intval($row_supplier["supplier_id"])==intval($row_fabric["supplier_id"]) ){
					echo 'selected';
				}
				echo '>'.$row_supplier["supplier_name"].'</option>';
			}
			?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-4 text-left">Fabric: </div>
	<div class="col-8 text-left"><?php echo $row_fabric["cat_name_en"]; ?></div>
</div>
<div class="row">
	<div class="col-4 text-left">Color: </div>
	<div class="col-8 text-left"><?php echo $row_fabric["fabric_color"]; ?></div>
</div>
<div class="row">
	<div class="col-4 text-left">Box: </div>
	<div class="col-8 text-left"><?php echo $row_fabric["fabric_box"]; ?></div>
</div>
<div class="row">
	<div class="col-4 text-left">No. </div>
	<div class="col-8 text-left"><?php echo $row_fabric["fabric_no"]; ?></div>
</div>
<div class="row">
	<div class="col-12 text-center">
		<hr>
		<div class="btn btn-success" onclick="submitEditSupplier(<?php echo $_POST["fabric_id"]; ?>);" >Submit</div>
	</div>
</div>