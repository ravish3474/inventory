<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");


require_once('../../db.php');

if( !isset($_GET["id"]) || ($_GET["id"]=="") || ($_GET["id"]=="0") ){
	exit();
}

$po_con_id = $_GET["id"];
$type = $_GET["type"];


$sql_select = "SELECT * FROM tbl_po_content WHERE po_con_id='".$po_con_id."' ";
$rs_select = $conn->query($sql_select);
$row_select = $rs_select->fetch_assoc();

header("Content-Disposition: attachment;filename=".$row_select["po_number"].".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

echo "<html><body>TEST</body></html>";

exit();
/*
?>

<div class="card-body" id="card-po-new" style="display: none;">
	<form id="new_po_form" name="new_po_form" method="post" target="save_new_po_frame" action="ajax/purchase/save_new_po.php">
		<div class="row">
			<h4 class="card-title col-6" style="margin-top:25px;">New Purchase Order Form</h4>
			<div class="col-6" style="margin-top:25px;" align="right"><input type="button" class="btn btn-danger" value="Cancel" onclick="closeNewPOCard();"></div>
		</div>
		<div class="row">
			<div class="col-8" style="line-height: 35px;">
				<?php
				$sql_po_header = 'SELECT * FROM tbl_po_header ORDER BY comp_name ASC ';
				$rs_po_header = $conn->query($sql_po_header);
				$a_header_info = array();
				while ($row_po_header = $rs_po_header->fetch_assoc()) {
					$a_header_info[($row_po_header["po_h_id"])] = $row_po_header;
				}
				?>
				Header: 
				<select name="new_po_h_id" id="new_po_h_id" onchange="newPOChangeHeader();">
					<option value="0">==Please select==</option>
					<?php
					foreach($a_header_info as $s_po_h_id => $a_row_header){
						echo '<option value="'.$s_po_h_id.','.$a_row_header["pre_code"].'">'.$a_row_header["comp_name"].'</option>';
					}
					?>
				</select>
				<input type="hidden" name="hidden_comp_data" id="hidden_comp_data">
				&nbsp;
				Type: 
				<select name="new_po_type" id="new_po_type" onchange="newPOChangeType();">
					<option value="F">Fabric</option>
					<option value="A">Accessory</option>
				</select>
				<hr>
			</div>
			<div class="col-4" style="line-height: 35px;">
				&nbsp;<input type="checkbox" id="chk_lang" name="chk_lang" value="TH" onclick="showTH();"> Thai
				&nbsp;<input type="checkbox" id="chk_include_vat" name="chk_include_vat" value="yes" onclick="showVAT();"> Include VAT
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-3">
				<div class="new-po-header-logo" id="new_head_comp_logo"></div>
			</div>
			<div class="col-9">
				<div class="new-po-header" id="new_head_comp_info"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-12" align="center">
				<h4 class="d-po-title">
				Purchase Order
				<span class="mark-th" style="display:none;"><br>ใบสั่งซื้อ</span>
				</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-7">
				<table>
					<tr>
						<td>
							Supplier Name<span class="mark-th" style="display:none;"> / ชื่อผู้ขาย</span>: 
						</td>
						<td>
							<input name="show_supplier_new" class="auto-complete-box" type="text" id="show_supplier_new" size="30" maxlength="150" />
			  				<input name="h_supplier_id_new" type="hidden" id="h_supplier_id_new" value="" />
						</td>
					</tr>
					<!-- po_sup_id,sup_name,nationality,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment -->
					<tr>
						<td>
							Address<span class="mark-th" style="display:none;"> / ที่อยู่</span>: 
						</td>
						<td>
							<textarea name="new_sup_address" id="new_sup_address" cols="50" rows="3"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							TAX ID: 
						</td>
						<td>
							<input type="text" name="new_sup_tax_id" id="new_sup_tax_id" value="" maxlength="20">
						</td>
					</tr>
					<tr>
						<td>
							Email<span class="mark-th" style="display:none;"> / อีเมลล์</span>: 
						</td>
						<td>
							<input type="text" name="new_sup_email" id="new_sup_email" value="" maxlength="120">
						</td>
					</tr>
				</table>
			</div>
			<div class="col-5" align="right">
				<table>
					<tr>
						<td>DATE<span class="mark-th" style="display:none;"> / วันที่่</span>:</td>
						<td><input type="date" name="new_po_date" id="new_po_date" value="<?php echo date("Y-m-d");?>"></td>
					</tr>
					<tr>
						<td>PO No.</td>
						<td><span id="sp_new_po_number"></span><input type="hidden" name="new_po_number" id="new_po_number"></td>
					</tr>
					<tr>
						<td>Sale name<span class="mark-th" style="display:none;"> / ผู้ติดต่อ</span>:</td>
						<td><input type="text" name="new_sale_name" id="new_sale_name" value="" maxlength="100"></td>
					</tr>
					<tr>
						<td>Telephone<span class="mark-th" style="display:none;"> / โทรฯ</span>:</td>
						<td>
							<input type="text" name="new_sup_tel" id="new_sup_tel" value="" maxlength="20">
							<input type="hidden" name="new_sup_fax" id="new_sup_fax" >
						</td>
					</tr>
					<tr>
						<td>Payment</td>
						<td><input type="text" name="new_sup_payment" id="new_sup_payment" value="" size="10" maxlength="2"> Days</td>
					</tr>
					<tr>
						<td>Delivery</td>
						<td><input type="date" name="new_delivery_date" id="new_delivery_date" value=""></td>
					</tr>
				</table>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-12">
				<table class="new-po-detail-zone" width="100%" border="1">
					<tr>
						<th>No.</th>
						<th>Code</th>
						<th>Item</th>
						<th>Color</i></th>
						<th>QTY</th>
						<th>Unit</th>
						<th>Price/Unit</th>
						<th>Amount</th>
					</tr>
					<?php

					//----------SET number of rows that want to generate for new PO form-----------------------------//
					$gen_row = 8;
					//----------SET number of rows that want to generate for new PO form-----------------------------//

					for($i=1;$i<=$gen_row;$i++){
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><input name="new_po_code[]" id="new_po_code<?php echo $i; ?>" type="text" size="12" maxlength="80"></td>
						<td class="td-detail">
							<input name="new_po_item[]" class="auto-complete-box" id="new_po_item<?php echo $i; ?>" type="text" size="30" maxlength="255">
							<input name="h_item_id_new_po[]" id="h_item_id_new_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td>
							<input name="new_po_color[]" class="auto-complete-box" id="new_po_color<?php echo $i; ?>" type="text" size="15" maxlength="100">
							<input name="h_color_id_new_po[]" id="h_color_id_new_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td><input name="new_po_qty[]" id="new_po_qty<?php echo $i; ?>" type="number" min="0" max="10000" step="1.0" onkeyup="calculateQTY(<?php echo $gen_row; ?>); calculateRow(<?php echo $i; ?>);" onchange="calculateQTY(<?php echo $gen_row; ?>); calculateRow(<?php echo $i; ?>);"></td>
						<td id="td_new_po_unit<?php echo $i; ?>"></td>
						<td><input name="new_po_ppu[]" id="new_po_ppu<?php echo $i; ?>" type="number" class="show-currency currency" min="0" max="100000" step="1.0" onkeyup="calculateRow(<?php echo $i; ?>);" onchange="calculateRow(<?php echo $i; ?>);" size="5"></td>
						<td><input name="new_po_amount[]" id="new_po_amount<?php echo $i; ?>" type="text" class="show-currency currency" size="6" readonly></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="4"><div align="right">Total: </div></td>
						<td><div id="d_total_qty"></div><input name="new_po_total_qty" type="hidden" id="new_po_total_qty"></td>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" rowspan="3" align="center" >
							<div id="d_total_string" class="mark-th" style="display:none;">=<span id="sp_total_string"></span>=
								<i class="fa fa-refresh" aria-hidden="true" style="cursor: pointer; color:#00F;" onclick="showNumberToString();"></i>
							</div>
							Note<span class="mark-th" style="display:none;"> / หมายเหตุ</span>: <textarea name="new_po_note" rows="2" cols="50"></textarea>
						</td>
						<td colspan="3"><div><span class="mark-vat" style="display:none;">Sub Total</span>&nbsp;</div></td>
						<td><input name="new_po_sub_total" type="text" id="new_po_sub_total" class="show-currency currency" size="6" readonly></td>
					</tr>
					<tr>
						<td colspan="3" ><div class="mark-vat" style="display:none;">VAT 7%<span class="mark-th" style="display:none;"><br>ภาษีมูลค่าเพิ่ม</span></div></td>
						<td><input name="new_po_vat_value" type="text" id="new_po_vat_value" class="show-currency currency" size="6" readonly></td>
					</tr>
					<tr>
						<td colspan="3">Net Total<span class="mark-th" style="display:none;"><br>รวมเงินสุทธิ</span></td>
						<td><input name="new_po_net_total" type="text" id="new_po_net_total" class="show-currency currency" size="6" readonly></td>
					</tr>
				</table>
			</div>
			
		</div>
		<br>
		<div class="row" style="height:100px; padding: 10px;">
			<div class="col-6">
				Prepare by: <?php echo $_SESSION["employee_name"]; ?>
			</div>
			<div class="col-6">
				Authorized by: 
			</div>
		</div>
		<div class="row">
			<div class="col-3"></div>
			<input class="col-6 btn btn-primary" type="button" onclick="return checkBeforeSubmitNewPO();" value="Submit new Purchase Order">
			<div class="col-3"></div>
		</div>
	</form>
</div>
<?php
*/
?>