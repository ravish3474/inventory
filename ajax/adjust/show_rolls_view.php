<?php
session_start();
if(!isset($_SESSION['employee_position_id'])){
	echo "SESSION Expired! Please refresh...";
	exit();
}
?>
<div class="col-12">
	<div class="row">
		<div class="col-2 text-left" style="position: relative; cursor: pointer;" onclick="showColorsView(<?php echo $_POST["cat_id"]; ?>,'<?php echo $_POST["cat_name_en"]; ?>');">
			<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back
		</div>

		<div class="col-5 text-right">
			<h5><?php echo base64_decode($_POST["cat_name_en"])." [".base64_decode($_POST["color_name"])."]"; ?></h5>
		</div>
		<?php
		if( isset($_POST["color_code"]) && $_POST["color_code"]!="" ){
		?>
		<div class="col-3 text-left"><div style="background-color: #<?php echo $_POST["color_code"]; ?>; width: 200px; border-radius: 15px; border:1px #999 solid;">&nbsp;</div></div>
		
		<?php
		}else{
			echo '<div class="col-3 text-left">&nbsp;</div>';
		}
		?>
		<div class="col-2 text-right" >
			<input type="hidden" id="hide_cat_id" value="<?php echo $_POST["cat_id"]; ?>">
			<input type="hidden" id="hide_color_name" value="<?php echo base64_decode($_POST["color_name"]); ?>">

			<input type="hidden" id="new_cat_id" value="<?php echo $_POST["cat_id"]; ?>">
			<input type="hidden" id="new_cat_name_en" value="<?php echo base64_decode($_POST["cat_name_en"]); ?>">
			<input type="hidden" id="new_color_name" value="<?php echo base64_decode($_POST["color_name"]); ?>">
			<input type="hidden" id="new_cat_name_en2" value="<?php echo $_POST["cat_name_en"]; ?>">
			<input type="hidden" id="new_color_name2" value="<?php echo $_POST["color_name"]; ?>">
			<input type="hidden" id="new_color_code2" value="<?php echo $_POST["color_code"]; ?>">
			<div class="btn btn-primary" style="height: 30px; line-height: 18px;" onclick="showNewRollForm('roll');" data-toggle="modal" data-target="#newRollModal"><i class="fa fa-plus"></i> New roll</div>
		</div>
	</div>
</div>
<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}

$a_adj_log = array();

$sql_adj = "SELECT DISTINCT tbl_adjust.fabric_id ";
$sql_adj .= "FROM tbl_adjust ";
$sql_adj .= "LEFT JOIN fabric ON fabric.fabric_id=tbl_adjust.fabric_id ";
$sql_adj .= "WHERE fabric.fabric_balance<>0 AND fabric.cat_id=".$_POST["cat_id"]." AND fabric.fabric_color='".addslashes(base64_decode($_POST["color_name"]))."' ";
$rs_adj = $conn->query($sql_adj);
while($row_adj = $rs_adj->fetch_assoc()){
	$a_adj_log[($row_adj["fabric_id"])] = 1;
}

$sql_fabric = "SELECT *,CAST(fabric_no AS UNSIGNED) AS fabric_no ";
$sql_fabric .= "FROM fabric ";
$sql_fabric .= "LEFT JOIN supplier ON fabric.supplier_id=supplier.supplier_id ";
$sql_fabric .= "WHERE fabric_balance<>0 AND cat_id=".$_POST["cat_id"]." AND fabric_color='".addslashes(base64_decode($_POST["color_name"]))."' ";
$sql_fabric .= "ORDER BY fabric_box ASC,fabric_no ASC ";
$rs_fabric = $conn->query($sql_fabric);


?>
<div class="col-1" >
	<input type="hidden" id="tmp_cat_id" value="<?php echo $_POST["cat_id"]; ?>">
	<input type="hidden" id="tmp_cat_name_en" value="<?php echo $_POST["cat_name_en"]; ?>">
	<input type="hidden" id="tmp_color_name" value="<?php echo $_POST["color_name"]; ?>">
</div>
<div class="col-10 text-center tbl-rolls" >
	<table width="100%">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Supplier</th>
				<th>Box</th>
				<th>No.</th>
				<th class="text-right">IN(Kg)</th>
				<th class="text-right">OUT(Kg)</th>
				<th class="text-right">Balance(Kg)</th>
				<th class="text-center" style="width:120px;">Save</th>
				<th class="text-right">Unit Price</th>
				<?php
				if( in_array(intval($_SESSION['employee_position_id']),array(1,2,99)) ){
				?>
					<th class="text-center" style="width: 90px;">
						Edit Price
						<div style="border-top: 2px solid #FFF; width: 90px;">
							<input type="checkbox" id="chk_all_rows" onclick="return checkAllRows();" style="line-height: 2; vertical-align: middle; width: 16px; height: 16px;" title="Check all rows">
							&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
							<i class="fa fa-pencil" style="cursor: pointer;" onclick="return editUnitPrice();" title="Click to edit prices of the rows you checked."></i>
						</div>
					</th>
				<?php
				}
				?>
				<th class="text-right">Amount</th>
				<th class="text-center">Adjust Log</th>
				<th class="text-center">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count_row = 1;
			while($row_fabric = $rs_fabric->fetch_assoc()){
			?>
			<tr class="row-rolls">
				<td class="text-center"><?php echo $count_row; ?></td>
				<td class="text-center">
					<span id="sp_supp_name<?php echo $row_fabric["fabric_id"]; ?>"><?php echo $row_fabric["supplier_name"]; ?></span>
					<i class="fa fa-pencil" style="cursor: pointer; color: #00F; float: right; font-size: 18px;" data-toggle="modal" data-target="#editSupplierModal" onclick="return editSupplier(<?php echo $row_fabric["fabric_id"]; ?>);"></i>
				</td>
				<td id="td_box<?php echo $row_fabric["fabric_id"]; ?>"><?php echo $row_fabric["fabric_box"]; ?></td>
				<td id="td_no<?php echo $row_fabric["fabric_id"]; ?>"><?php echo $row_fabric["fabric_no"]; ?></td>
				<td class="text-right" id="td_old_in<?php echo $row_fabric["fabric_id"]; ?>"><?php echo $row_fabric["fabric_in_piece"]; ?></td>
				<td class="text-right"><?php echo $row_fabric["fabric_used"]; ?></td>
				<td class="text-right">
					<input type="hidden" id="old_bal<?php echo $row_fabric["fabric_id"]; ?>" value="<?php echo $row_fabric["fabric_balance"]; ?>">
					<?php
					if($row_fabric["on_producing"]=="0"){
					?>
					<input type="number" id="in_bal<?php echo $row_fabric["fabric_id"]; ?>" value="<?php echo $row_fabric["fabric_balance"]; ?>" step="0.01" min="0.0" onkeyup="showSaveButton(<?php echo $row_fabric["fabric_id"]; ?>);" onchange="showSaveButton(<?php echo $row_fabric["fabric_id"]; ?>);">
					<?php
					}else{
						//echo '<input type="hidden" id="in_bal'.$row_fabric["fabric_id"].'" value="'.$row_fabric["fabric_balance"].'">';
						echo '<font color="red" title="These roll was marked as producing status, not allow to use the adjust function.">'.$row_fabric["fabric_balance"].' *</font>';
					}
					?>
				</td>
				<td class="text-center">
					<div id="btn_save<?php echo $row_fabric["fabric_id"]; ?>" style="display:none;">
						<button class="btn btn-primary btn-save" onclick="saveBalance(<?php echo $row_fabric["fabric_id"]; ?>);">Save</button>
					</div>
				</td>
				<td class="text-right" id="td_unit_price<?php echo $row_fabric["fabric_id"]; ?>">
					<span id="sp_inner_uprice<?php echo $row_fabric["fabric_id"]; ?>"><?php echo $row_fabric["fabric_in_price"]; ?></span>
					
				</td>
				<?php
				if( in_array(intval($_SESSION['employee_position_id']),array(1,2,99)) ){
				?>
					<td class="text-center">
						<input type="checkbox" class="normal_roll_row" style="width: 16px; height: 16px;" value="<?php echo $row_fabric["fabric_id"]; ?>">
					</td>
				<?php
				}
				?>
				<td class="text-right" id="td_amount<?php echo $row_fabric["fabric_id"]; ?>"><?php echo number_format((floatval($row_fabric["fabric_balance"])*floatval($row_fabric["fabric_in_price"])),2); ?></td>
				<td class="text-center">
					<div id="adj_log<?php echo $row_fabric["fabric_id"]; ?>" <?php if(!isset($a_adj_log[($row_fabric["fabric_id"])])){ ?>style="display: none;"<?php } ?>>
						<i class="fa fa-file-text-o" aria-hidden="true" onclick="showAdjustLog(<?php echo $row_fabric["fabric_id"]; ?>);" style="cursor:pointer;" data-toggle="modal" data-target="#showLogModal"></i>
					</div>
				</td>
				<td class="text-center">
					<?php
					if( $row_fabric["fabric_used"]==0 && $row_fabric["on_producing"]=="0" && $row_fabric["fabric_adjust"]==0){
					?>
					<button style="height: 25px; font-size: 14px; line-height: 10px;" type="button" class="btn btn-danger" onclick="returnFabric(<?php echo $row_fabric["fabric_id"]; ?>);" data-toggle="modal" data-target="#removeFabricModal">Remove</button>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
				$count_row++;
			}
			?>
		</tbody>
	</table>
</div>
<div class="col-1" >
</div>