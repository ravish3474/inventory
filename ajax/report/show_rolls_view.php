<div style="position: relative; cursor: pointer;" onclick="showColorsView(<?php echo $_POST["cat_id"]; ?>,'<?php echo $_POST["cat_name_en"]; ?>');">
	<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back
</div>
<div class="col-12">
	<div class="row">

		<div class="col-6 text-right">
			<h5><?php echo base64_decode($_POST["cat_name_en"])." [".base64_decode($_POST["color_name"])."]"; ?></h5>
		</div>
		<?php
		if($_POST["color_code"]!=""){
		?>
		<div class="col-3 text-left"><div style="background-color: #<?php echo $_POST["color_code"]; ?>; width: 200px; border-radius: 15px; border:1px #999 solid;">&nbsp;</div></div>
		
		<?php
		}else{
			echo '<div class="col-3 text-left">&nbsp;</div>';
		}
		?>
		<div class="col-3 text-right" >&nbsp;</div>
	</div>
	<hr>
</div>
<?php
require_once('../../db.php');

if( !isset($_POST["cat_id"]) || ($_POST["cat_id"]=="") ){
	echo '<b>Invalid parameter</b>';
	exit();
}
?>

<div class="col-6 text-center tbl-rolls" >
	<h6 align="center">Active Rolls</h6>
	<table width="100%">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th>Box</th>
				<th>No.</th>
				<th class="text-right">Balance(Kg)</th>
				<th class="text-center">Transactions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql_fabric = "SELECT *,CAST(fabric_no AS UNSIGNED) AS fabric_no ";
			$sql_fabric .= "FROM fabric ";
			$sql_fabric .= "WHERE fabric_balance>0 AND cat_id=".$_POST["cat_id"]." AND fabric_color='".addslashes(base64_decode($_POST["color_name"]))."' ";
			$sql_fabric .= "ORDER BY fabric_box ASC,fabric_no ASC ";

			$rs_fabric = $conn->query($sql_fabric);
			$count_row = 1;
			while($row_fabric = $rs_fabric->fetch_assoc()){
			?>
			<tr class="row-rolls">
				<td class="text-center"><?php echo $count_row; ?></td>
				<td class="text-center"><?php echo $row_fabric["fabric_box"]; ?></td>
				<td class="text-center"><?php echo $row_fabric["fabric_no"]; ?></td>
				<td class="text-right"><?php echo $row_fabric["fabric_balance"]; ?></td>
				<td class="text-center">
					<i class="fa fa-file-text-o" aria-hidden="true" onclick="showTransactions(<?php echo $row_fabric["fabric_id"]; ?>,'<?php echo $row_fabric["fabric_box"]; ?>','<?php echo $row_fabric["fabric_no"]; ?>',<?php echo $row_fabric["fabric_balance"]; ?>);" style="cursor:pointer;" data-toggle="modal" data-target="#showTransactionModal"></i>
				</td>
			</tr>
			<?php
				$count_row++;
			}
			?>
		</tbody>
	</table>
</div>
<div class="col-6 text-center tbl-rolls-empty" >
	<h6 align="center">Empty Rolls</h6>
	<table width="100%">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th>Box</th>
				<th>No.</th>
				<th class="text-right">Balance(Kg)</th>
				<th class="text-center">Transactions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql_fabric = "SELECT *,CAST(fabric_no AS UNSIGNED) AS fabric_no ";
			$sql_fabric .= "FROM fabric ";
			$sql_fabric .= "WHERE fabric_balance<=0 AND cat_id=".$_POST["cat_id"]." AND fabric_color='".addslashes(base64_decode($_POST["color_name"]))."' ";
			$sql_fabric .= "ORDER BY fabric_box ASC,fabric_no ASC ";

			$rs_fabric = $conn->query($sql_fabric);
			$count_row = 1;
			while($row_fabric = $rs_fabric->fetch_assoc()){
			?>
			<tr class="row-rolls">
				<td class="text-center"><?php echo $count_row; ?></td>
				<td class="text-center"><?php echo $row_fabric["fabric_box"]; ?></td>
				<td class="text-center"><?php echo $row_fabric["fabric_no"]; ?></td>
				<td class="text-right"><?php echo $row_fabric["fabric_balance"]; ?></td>
				<td class="text-center">
					<i class="fa fa-file-text-o" aria-hidden="true" onclick="showTransactions(<?php echo $row_fabric["fabric_id"]; ?>,'<?php echo $row_fabric["fabric_box"]; ?>','<?php echo $row_fabric["fabric_no"]; ?>',<?php echo $row_fabric["fabric_balance"]; ?>);" style="cursor:pointer;" data-toggle="modal" data-target="#showTransactionModal"></i>
				</td>
			</tr>
			<?php
				$count_row++;
			}
			?>
		</tbody>
	</table>
</div>