<div class="col-12 text-right" >
	<div class="btn btn-primary" style="height: 30px; line-height: 18px;" onclick="showNewRollForm('fabric');" data-toggle="modal" data-target="#newRollModal"><i class="fa fa-plus"></i> New roll</div>
</div>
<?php
require_once('../../db.php');

$sql_fabric = "SELECT cat.cat_name_en,fabric.cat_id,COUNT(DISTINCT fabric.fabric_color) AS n_colors,COUNT(*) AS n_rolls,SUM(fabric.fabric_balance) AS f_bal ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN cat ON fabric.cat_id=cat.cat_id ";
$sql_fabric .= "WHERE fabric.fabric_balance<>0 ";
$sql_fabric .= "GROUP BY fabric.cat_id ";
$sql_fabric .= "ORDER BY cat.cat_name_en ASC ";

$rs_fabric = $conn->query($sql_fabric);

while($row_fabric = $rs_fabric->fetch_assoc()){
?>
<div class="col-2 text-center fabric-board" >
	<div class="row fabric-box" onclick="showColorsView(<?php echo $row_fabric["cat_id"]; ?>,'<?php echo base64_encode($row_fabric["cat_name_en"]); ?>');">
	<h6 class="col-12"><?php echo $row_fabric["cat_name_en"]; ?></h6>
	<div class="col-12">Balance: <font class="num-bal" color="green"><?php echo number_format($row_fabric["f_bal"],2); ?></font> Kg.</div>
	<div class="col-6"><font color="blue"><?php echo $row_fabric["n_rolls"]; ?></font> Rolls.</div>
	<div class="col-6"><font color="red"><?php echo $row_fabric["n_colors"]; ?></font> Colors.</div>
	</div>
</div>
<?php
}
?>