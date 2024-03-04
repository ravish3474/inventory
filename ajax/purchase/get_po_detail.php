<?php
require_once('../../db.php');

$po_con_id = $_REQUEST["po_con_id"];
$action = $_REQUEST["action"];
if($po_con_id==""){
	$po_con_id = "0";
}

$sql_select = "SELECT * FROM tbl_po_content WHERE po_con_id=".$po_con_id;
$rs_select = $conn->query($sql_select);
$row_content = $rs_select->fetch_assoc();

if($action=="download"){

	$full_path = 'http://'.$_SERVER["SERVER_NAME"].'/inventory/';
	
?>
<button onclick="printPDF('<?php echo $row_content["po_number"]; ?>');"><i class="fa fa-print" aria-hidden="true"></i>Print</button>
<div id="print_this">
<table border="0" width="100%" style="border-spacing: 0px;">
	<tr>
		<td colspan="3"><img src="<?php echo $full_path; ?>assets/images/po-logo/<?php echo $row_content["comp_logo"]; ?>"></td>
		<td colspan="5">
			<table border="0">
				<tbody>
					<tr><td><?php echo $row_content["comp_name"]; ?></td></tr>
					<tr><td><?php echo $row_content["comp_address"]; ?></td></tr>
					<tr><td>Tel: <?php echo $row_content["comp_tel"]; ?> <?php if($row_content["comp_fax"]!=""){ echo "Fax: ".$row_content["comp_fax"]; } ?></td></tr>
					<?php if($row_content["website"]!=""){ echo "<tr><td>Website: ".$row_content["website"]."</td></tr>"; } ?>
					<?php if($row_content["comp_tax_id"]!=""){ echo "<tr><td>Company Tax ID: ".$row_content["comp_tax_id"]."</td></tr>"; } ?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="8">
			<center>
			<h4 style="border: solid 2px #000; border-radius: 5px 5px; width: 250px; ">
			Purchase Order
			<?php if($row_content["nationality"]=="TH"){ ?><span><br>ใบสั่งซื้อ</span><?php } ?>
			</h4>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="5">
			<table width="100%">
				<tr>
					<td>
						Supplier Name<?php if($row_content["nationality"]=="TH"){ ?><span> / ชื่อผู้ขาย</span><?php } ?>: 
					</td>
					<td>
						<?php echo $row_content["sup_name"]; ?>
					</td>
				</tr>
				<tr>
					<td>
						Address<?php if($row_content["nationality"]=="TH"){ ?><span> / ที่อยู่</span><?php } ?>: 
					</td>
					<td>
						<pre><?php echo $row_content["sup_address"]; ?></pre>
					</td>
				</tr>
				<tr>
					<td>
						TAX ID: 
					</td>
					<td>
						<?php echo $row_content["sup_tax_id"]; ?>
					</td>
				</tr>
				<tr>
					<td>
						Email<?php if($row_content["nationality"]=="TH"){ ?><span> / อีเมลล์</span><?php } ?>: 
					</td>
					<td>
						<?php echo $row_content["sup_email"]; ?>
					</td>
				</tr>
			</table>
		</td>
		<td colspan="3" >
			<div align="right">
			<table width="100%">
				<tr>
					<td>DATE<?php if($row_content["nationality"]=="TH"){ ?><span> / วันที่่</span><?php } ?>:</td>
					<td><?php echo $row_content["po_date"]; ?></td>
				</tr>
				<tr>
					<td>PO No.</td>
					<td><?php echo $row_content["po_number"]; ?></td>
				</tr>
				<tr>
					<td>Sale name<?php if($row_content["nationality"]=="TH"){ ?><span> / ผู้ติดต่อ</span><?php } ?>:</td>
					<td><?php echo $row_content["sale_name"]; ?></td>
				</tr>
				<tr>
					<td>Telephone<?php if($row_content["nationality"]=="TH"){ ?><span> / โทรฯ</span><?php } ?>:</td>
					<td>
						<?php echo $row_content["sup_tel"]; ?>
					</td>
				</tr>
				<tr>
					<td>Payment</td>
					<td><?php echo $row_content["sup_payment"]; ?> Days</td>
				</tr>
				<tr>
					<td>Delivery</td>
					<td><?php echo $row_content["delivery_date"]; ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	
				<tr>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">No.</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Code</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Item</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Color</i></th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">QTY</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Unit</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Price/Unit</th>
					<th style="text-align: center; background-color: #3A3; color: #FFF; border-width: 1px; border-style: solid; border-color: #000;">Amount</th>
				</tr>
				<?php
				if($row_content["nationality"]=="TH"){
					$currency = "฿";
				}else{
					$currency = "$";
				}

				$sql_item = "SELECT * FROM tbl_po_item WHERE po_con_id='".$po_con_id."' AND enable=1";
				$rs_item = $conn->query($sql_item);

				$n_row = 1;
				$n_total_qty = 0.0;
				$n_sub_total = 0.0;
				while($row_item = $rs_item->fetch_assoc()){
				?>
				<tr>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $n_row; ?></td>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $row_item["code"]; ?></td>
					<td style="text-align: left; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $row_item["detail"]; ?></td>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $row_item["color"]; ?></td>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $row_item["qty"]; ?></td>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $row_item["unit"]; ?></td>
					<td style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $currency." ".number_format($row_item["price_per_unit"],2); ?></td>
					<td style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $currency." ".number_format($row_item["amount"],2); ?></td>
				</tr>
				<?php
					$n_total_qty += floatval($row_item["qty"]);
					$n_sub_total += floatval($row_item["amount"]);
					$n_row++;
				}
				$n_vat_value = ($n_sub_total*0.07)."";
				?>
				<tr>
					<td colspan="4" style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><div align="right">Total: </div></td>
					<td style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo number_format($n_total_qty,2); ?></td>
					<td colspan="3" style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" rowspan="<?php if($row_content["inc_vat"]=="yes"){ ?>3<?php }else{ echo "2"; } ?>" align="center" style="text-align: center; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;">
						<?php if($row_content["nationality"]=="TH"){ ?><div>=<?php echo $row_content["convert_to_word"]; ?>=</div><?php } ?>
						Note<?php if($row_content["nationality"]=="TH"){ ?><span> / หมายเหตุ</span><?php } ?>: 
						<pre><?php echo $row_content["note"]; ?></pre>
					</td>
					<td colspan="3" style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><div><?php if($row_content["inc_vat"]=="yes"){ ?><span>Sub Total</span><?php } ?>&nbsp;</div></td>
					<td  style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php if($row_content["inc_vat"]=="yes"){ echo $currency." ".number_format($n_sub_total,2); }else{ echo "&nbsp;"; }?></td>
				</tr>
				<?php if($row_content["inc_vat"]=="yes"){ ?>
				<tr>
					<td colspan="3" style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;">
						<div>VAT 7%<?php if($row_content["nationality"]=="TH"){ ?><span><br>ภาษีมูลค่าเพิ่ม</span><?php } ?></div>
					</td>
					<td style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $currency." ".number_format($n_vat_value,2); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="3" style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;">Net Total<?php if($row_content["nationality"]=="TH"){ ?><span><br>รวมเงินสุทธิ</span><?php } ?></td>
					<td style="text-align: right; padding: 0px; margin: 0px; border-width: 1px; border-style: solid; border-color: #000;"><?php echo $currency." ".number_format($row_content["net_total"],2); ?></td>
				</tr>
			
	<tr style="height:100px; padding: 10px;">
		<td colspan="4">
			Prepare by: <?php echo $row_content["prepare_emp_name"]; ?>
		</td>
		<td colspan="4">
			Authorized by:
		</td>
	</tr>
</table>
</div>
<?php
	
}else if($action=="view"){

?>
<div class="detail-po">
		
	<div class="row">
		<div class="col-3">
			<div class="new-po-header-logo"><img src="assets/images/po-logo/<?php echo $row_content["comp_logo"]; ?>"></div>
		</div>
		<div class="col-9">
			<div class="new-po-header">
				<table>
					<tbody>
						<tr><td><?php echo $row_content["comp_name"]; ?></td></tr>
						<tr><td><?php echo $row_content["comp_address"]; ?></td></tr>
						<tr><td>Tel: <?php echo $row_content["comp_tel"]; ?> <?php if($row_content["comp_fax"]!=""){ echo "Fax: ".$row_content["comp_fax"]; } ?></td></tr>
						<?php if($row_content["website"]!=""){ echo "<tr><td>Website: ".$row_content["website"]."</td></tr>"; } ?>
						<?php if($row_content["comp_tax_id"]!=""){ echo "<tr><td>Company Tax ID: ".$row_content["comp_tax_id"]."</td></tr>"; } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12" align="center">
			<h4 class="d-po-title">
			Purchase Order
			<?php if($row_content["nationality"]=="TH"){ ?><span><br>ใบสั่งซื้อ</span><?php } ?>
			</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-7">
			<table>
				<tr>
					<td>
						Supplier Name<?php if($row_content["nationality"]=="TH"){ ?><span> / ชื่อผู้ขาย</span><?php } ?>: 
					</td>
					<td>
						<?php echo $row_content["sup_name"]; ?>
					</td>
				</tr>
				<tr>
					<td>
						Address<?php if($row_content["nationality"]=="TH"){ ?><span> / ที่อยู่</span><?php } ?>: 
					</td>
					<td>
						<pre><?php echo $row_content["sup_address"]; ?></pre>
					</td>
				</tr>
				<tr>
					<td>
						TAX ID: 
					</td>
					<td>
						<?php echo $row_content["sup_tax_id"]; ?>
					</td>
				</tr>
				<tr>
					<td>
						Email<?php if($row_content["nationality"]=="TH"){ ?><span> / อีเมลล์</span><?php } ?>: 
					</td>
					<td>
						<?php echo $row_content["sup_email"]; ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-5" align="right">
			<table>
				<tr>
					<td>DATE<?php if($row_content["nationality"]=="TH"){ ?><span> / วันที่่</span><?php } ?>:</td>
					<td><?php echo $row_content["po_date"]; ?></td>
				</tr>
				<tr>
					<td>PO No.</td>
					<td><?php echo $row_content["po_number"]; ?></td>
				</tr>
				<tr>
					<td>Sale name<?php if($row_content["nationality"]=="TH"){ ?><span> / ผู้ติดต่อ</span><?php } ?>:</td>
					<td><?php echo $row_content["sale_name"]; ?></td>
				</tr>
				<tr>
					<td>Telephone<?php if($row_content["nationality"]=="TH"){ ?><span> / โทรฯ</span><?php } ?>:</td>
					<td>
						<?php echo $row_content["sup_tel"]; ?>
					</td>
				</tr>
				<tr>
					<td>Payment</td>
					<td><?php echo $row_content["sup_payment"]; ?> Days</td>
				</tr>
				<tr>
					<td>Delivery</td>
					<td><?php echo $row_content["delivery_date"]; ?></td>
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
				if($row_content["nationality"]=="TH"){
					$currency = "฿";
				}else{
					$currency = "$";
				}

				$sql_item = "SELECT * FROM tbl_po_item WHERE po_con_id='".$po_con_id."' AND enable=1";
				$rs_item = $conn->query($sql_item);

				$n_row = 1;
				$n_total_qty = 0.0;
				$n_sub_total = 0.0;
				while($row_item = $rs_item->fetch_assoc()){
				?>
				<tr>
					<td><?php echo $n_row; ?></td>
					<td><?php echo $row_item["code"]; ?></td>
					<td class="td-detail"><?php echo $row_item["detail"]; ?></td>
					<td><?php echo $row_item["color"]; ?></td>
					<td><?php echo $row_item["qty"]; ?></td>
					<td><?php echo $row_item["unit"]; ?></td>
					<td class="text-right"><?php echo $currency." ".number_format($row_item["price_per_unit"],2); ?></td>
					<td class="text-right"><?php echo $currency." ".number_format($row_item["amount"],2); ?></td>
				</tr>
				<?php
					$n_total_qty += floatval($row_item["qty"]);
					$n_sub_total += floatval($row_item["amount"]);
					$n_row++;
				}
				$n_vat_value = ($n_sub_total*0.07)."";
				?>
				<tr>
					<td colspan="4"><div align="right">Total: </div></td>
					<td><?php echo number_format($n_total_qty,2); ?></td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" rowspan="<?php if($row_content["inc_vat"]=="yes"){ ?>3<?php }else{ echo "2"; } ?>" align="center" >
						<?php if($row_content["nationality"]=="TH"){ ?><div>=<?php echo $row_content["convert_to_word"]; ?>=</div><?php } ?>
						Note<?php if($row_content["nationality"]=="TH"){ ?><span> / หมายเหตุ</span><?php } ?>: 
						<pre><?php echo $row_content["note"]; ?></pre>
					</td>
					<td colspan="3"><div><?php if($row_content["inc_vat"]=="yes"){ ?><span>Sub Total</span><?php } ?>&nbsp;</div></td>
					<td class="text-right"><?php if($row_content["inc_vat"]=="yes"){ echo $currency." ".number_format($n_sub_total,2); }else{ echo "&nbsp;"; }?></td>
				</tr>
				<?php if($row_content["inc_vat"]=="yes"){ ?>
				<tr>
					<td colspan="3" >
						<div>VAT 7%<?php if($row_content["nationality"]=="TH"){ ?><span><br>ภาษีมูลค่าเพิ่ม</span><?php } ?></div>
					</td>
					<td class="text-right"><?php echo $currency." ".number_format($n_vat_value,2); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="3">Net Total<?php if($row_content["nationality"]=="TH"){ ?><span><br>รวมเงินสุทธิ</span><?php } ?></td>
					<td class="text-right"><?php echo $currency." ".number_format($row_content["net_total"],2); ?></td>
				</tr>
			</table>
		</div>
		
	</div>
	<br>
	<div class="row" style="height:100px; padding: 10px;">
		<div class="col-6">
			Prepare by: <?php echo $row_content["prepare_emp_name"]; ?>
		</div>
		<div class="col-6">
			Authorized by: 
		</div>
	</div>
</div>
<?php
	
}else if($action=="edit"){
?>
<!-- -----------------------------------Edit PO ZONE----------------------------------- -->
<div class="card-body detail-po" id="card-po-edit" >
	<form id="edit_po_form" name="edit_po_form" method="post" target="save_new_po_frame" action="ajax/purchase/save_edit_po.php">
		<div class="row">
			<h4 class="card-title col-6" style="margin-top:25px;">Edit Purchase Order Form</h4>
			<input type="hidden" name="edit_po_con_id" value="<?php echo $row_content["po_con_id"]; ?>">
			<div class="col-6" style="margin-top:25px;" align="right"><input type="reset" class="btn btn-danger" value="Reset"></div>
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
				Header: <?php echo $row_content["comp_name"]; ?>
				&nbsp;
				<?php
				$tmp_po_number = explode("-",$row_content["po_number"]);
				$po_type = substr($tmp_po_number[1],0,1);
				?>
				Type: <?php if($po_type=="F"){ echo "Fabric"; }else{ echo "Accessory"; } ?>
				<hr>
			</div>
			<div class="col-4" style="line-height: 35px;">
				&nbsp;<input type="checkbox" id="edit_chk_lang" name="edit_chk_lang" value="TH" onclick="editShowTH();" <?php if($row_content["nationality"]=="TH"){ echo "checked"; } ?> > Thai
				&nbsp;<input type="checkbox" id="edit_chk_include_vat" name="edit_chk_include_vat" value="yes" onclick="editShowVAT();" <?php if($row_content["inc_vat"]=="yes"){ echo "checked"; } ?> > Include VAT
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-3">
				<div class="new-po-header-logo" id="edit_head_comp_logo">
					<img src="assets/images/po-logo/<?php echo $row_content["comp_logo"]; ?>">
				</div>
			</div>
			<div class="col-9">
				<div class="new-po-header" id="edit_head_comp_info">
					<table>
					<?php if($row_content["comp_name"]!=""){ ?> <tr><td><?php echo $row_content["comp_name"]; ?></td></tr><?php } ?>
					<?php if($row_content["comp_address"]!=""){ ?> <tr><td><?php echo $row_content["comp_address"]; ?></td></tr><?php } ?>
					<?php if($row_content["comp_tel"]!=""){ ?> <tr><td>Tel: <?php echo $row_content["comp_tel"]; if($row_content["comp_fax"]){ echo " Fax: ".$row_content["comp_fax"]; } ?></td></tr><?php } ?>
					<?php if($row_content["website"]!=""){ ?> <tr><td>Website: <?php echo $row_content["website"]; ?></td></tr><?php } ?>
					<?php if($row_content["comp_tax_id"]!=""){ ?> <tr><td>Company Tax ID: <?php echo $row_content["comp_tax_id"]; ?></td></tr><?php } ?>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12" align="center">
				<h4 class="d-po-title">
				Purchase Order
				<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> ><br>ใบสั่งซื้อ</span>
				</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-7">
				<table>
					<tr>
						<td>
							Supplier Name<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / ชื่อผู้ขาย</span>: 
						</td>
						<td>
							<input name="show_supplier_edit" type="text" id="show_supplier_edit" size="30" maxlength="150" value="<?php echo $row_content["sup_name"]; ?>" />
			  				<input name="h_supplier_id_edit" type="hidden" id="h_supplier_id_edit" value="<?php echo $row_content["po_sup_id"]; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							Address<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / ที่อยู่</span>: 
						</td>
						<td>
							<textarea name="edit_sup_address" id="edit_sup_address" cols="50" rows="3"><?php echo $row_content["sup_address"]; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							TAX ID: 
						</td>
						<td>
							<input type="text" name="edit_sup_tax_id" id="edit_sup_tax_id" value="<?php echo $row_content["sup_tax_id"]; ?>" maxlength="20">
						</td>
					</tr>
					<tr>
						<td>
							Email<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / อีเมลล์</span>: 
						</td>
						<td>
							<input type="text" name="edit_sup_email" id="edit_sup_email" value="<?php echo $row_content["sup_email"]; ?>" maxlength="120">
						</td>
					</tr>
				</table>
			</div>
			<div class="col-5" align="right">
				<table>
					<tr>
						<td>DATE<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / วันที่่</span>:</td>
						<td><input type="date" name="edit_po_date" id="edit_po_date" value="<?php echo $row_content["po_date"];?>"></td>
					</tr>
					<tr>
						<td>PO No.</td>
						<td><span id="sp_edit_po_number"><?php echo $row_content["po_number"];?></span></td>
					</tr>
					<tr>
						<td>Sale name<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / ผู้ติดต่อ</span>:</td>
						<td><input type="text" name="edit_sale_name" id="edit_sale_name" value="<?php echo $row_content["sale_name"];?>" maxlength="100"></td>
					</tr>
					<tr>
						<td>Telephone<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / โทรฯ</span>:</td>
						<td>
							<input type="text" name="edit_sup_tel" id="edit_sup_tel" value="<?php echo $row_content["sup_tel"];?>" maxlength="20">
							<input type="hidden" name="edit_sup_fax" id="edit_sup_fax" value="<?php echo $row_content["sup_fax"];?>">
						</td>
					</tr>
					<tr>
						<td>Payment</td>
						<td><input type="number" name="edit_sup_payment" id="edit_sup_payment" value="<?php echo $row_content["sup_payment"];?>" step="1" size="10" maxlength="2"> Days</td>
					</tr>
					<tr>
						<td>Delivery</td>
						<td><input type="date" name="edit_delivery_date" id="edit_delivery_date" value="<?php echo $row_content["delivery_date"];?>"></td>
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
					$sql_item = "SELECT * FROM tbl_po_item WHERE po_con_id='".$po_con_id."' AND enable=1";
					$rs_item = $conn->query($sql_item);

					$n_row = 1;
					$n_total_qty = 0.0;
					$n_sub_total = 0.0;
					$a_item = array();
					while($row_item = $rs_item->fetch_assoc()){
						$a_item[$n_row] = $row_item;
						$n_total_qty += floatval($row_item["qty"]);
						$n_sub_total += floatval($row_item["amount"]);
						$n_row++;
					}

					$a_unit = array();
					$sql_unit = "SELECT * FROM tbl_unit WHERE enable=1 ORDER BY unit_name ASC";
					$rs_unit = $conn->query($sql_unit);
					while($row_unit = $rs_unit->fetch_assoc()){
						$a_unit[] = $row_unit["unit_name"];
					}


					//----------SET number of rows that want to generate for edit PO form-----------------------------//
					$gen_row = 8;
					//----------SET number of rows that want to generate for edit PO form-----------------------------//

					for($i=1;$i<=$gen_row;$i++){
						if(isset($a_item[$i])){
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<input name="edit_po_item_id[]" type="hidden" value="<?php echo $a_item[$i]["po_item_id"]; ?>">
							<input class="can-edit" name="edit_po_code[]" id="edit_po_code<?php echo $i; ?>" type="text" size="12" maxlength="80" value="<?php echo $a_item[$i]["code"]; ?>">
						</td>
						<td class="td-detail">
							<input class="can-edit" name="edit_po_item[]" id="edit_po_item<?php echo $i; ?>" type="text" size="30" maxlength="255" value="<?php echo $a_item[$i]["detail"]; ?>">
							<input name="h_item_id_edit_po[]" id="h_item_id_edit_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td>
							<input class="can-edit" name="edit_po_color[]" id="edit_po_color<?php echo $i; ?>" type="text" size="15" maxlength="100" value="<?php echo $a_item[$i]["color"]; ?>">
							<input name="h_color_id_edit_po[]" id="h_color_id_edit_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td><input class="can-edit" name="edit_po_qty[]" id="edit_po_qty<?php echo $i; ?>" type="number" min="0" max="10000" step="1.0" onkeyup="editCalculateQTY(<?php echo $gen_row; ?>); editCalculateRow(<?php echo $i; ?>);" onchange="editCalculateQTY(<?php echo $gen_row; ?>); editCalculateRow(<?php echo $i; ?>);" value="<?php echo $a_item[$i]["qty"]; ?>"></td>
						<td id="td_edit_po_unit<?php echo $i; ?>">
							<select class="can-edit" name="edit_po_unit[]" id="edit_po_unit<?php echo $i; ?>">
								<option value="<?php echo $a_item[$i]["unit"];?>"><?php echo $a_item[$i]["unit"];?></option>
								<?php
								for($k=0;$k<sizeof($a_unit);$k++){
									echo '<option value="'.$a_unit[$k].'">'.$a_unit[$k].'</option>';
								}
								?>
							</select>
						</td>
						<td><input class="can-edit show-currency currency-edit" name="edit_po_ppu[]" id="edit_po_ppu<?php echo $i; ?>" type="number" min="0" max="100000" step="1.0" onkeyup="editCalculateRow(<?php echo $i; ?>);" onchange="editCalculateRow(<?php echo $i; ?>);" size="5" value="<?php echo $a_item[$i]["price_per_unit"]; ?>"></td>
						<td><input class="show-currency currency-edit" name="edit_po_amount[]" id="edit_po_amount<?php echo $i; ?>" type="text" size="6" value="<?php echo $a_item[$i]["amount"]; ?>" readonly></td>
					</tr>
					<?php
						}else{
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<input name="edit_po_item_id[]" type="hidden" value="">
							<input name="edit_po_code[]" id="edit_po_code<?php echo $i; ?>" type="text" size="12" maxlength="80" value="">
						</td>
						<td class="td-detail">
							<input name="edit_po_item[]" id="edit_po_item<?php echo $i; ?>" type="text" size="30" maxlength="255" value="">
							<input name="h_item_id_edit_po[]" id="h_item_id_edit_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td>
							<input name="edit_po_color[]" id="edit_po_color<?php echo $i; ?>" type="text" size="15" maxlength="100" value="">
							<input name="h_color_id_edit_po[]" id="h_color_id_edit_po<?php echo $i; ?>" type="hidden" value="">
						</td>
						<td><input name="edit_po_qty[]" id="edit_po_qty<?php echo $i; ?>" type="number" min="0" max="10000" step="1.0" onkeyup="editCalculateQTY(<?php echo $gen_row; ?>); editCalculateRow(<?php echo $i; ?>);" onchange="editCalculateQTY(<?php echo $gen_row; ?>); editCalculateRow(<?php echo $i; ?>);" value=""></td>
						<td id="td_edit_po_unit<?php echo $i; ?>">
							<select name="edit_po_unit[]" id="edit_po_unit<?php echo $i; ?>">
								<?php
								for($k=0;$k<sizeof($a_unit);$k++){
									echo '<option value="'.$a_unit[$k].'">'.$a_unit[$k].'</option>';
								}
								?>
							</select>
						</td>
						<td><input name="edit_po_ppu[]" id="edit_po_ppu<?php echo $i; ?>" type="number" class="show-currency currency-edit" min="0" max="100000" step="1.0" onkeyup="editCalculateRow(<?php echo $i; ?>);" onchange="editCalculateRow(<?php echo $i; ?>);" size="5" value=""></td>
						<td><input name="edit_po_amount[]" id="edit_po_amount<?php echo $i; ?>" type="text" class="show-currency currency-edit" size="6" value="" readonly></td>
					</tr>
					<?php
						}
					}
					?>
					<tr>
						<td colspan="4"><div align="right">Total: </div></td>
						<td><div id="d_total_qty_edit"><?php echo number_format($n_total_qty,2); ?></div><input name="edit_po_total_qty" type="hidden" id="edit_po_total_qty" value="<?php echo $n_total_qty; ?>"></td>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" rowspan="3" align="center" >
							<div id="d_total_string_edit" class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> >=<span id="sp_total_string_edit"><?php echo $row_content["convert_to_word"]; ?></span>=
								<i class="fa fa-refresh" aria-hidden="true" style="cursor: pointer; color:#00F;" onclick="editShowNumberToString();"></i>
							</div>
							Note<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> > / หมายเหตุ</span>: <textarea name="edit_po_note" rows="2" cols="50"><?php echo $row_content["note"]; ?></textarea>
						</td>
						<td colspan="3"><div><span class="mark-vat edit-mark-vat" <?php if($row_content["inc_vat"]!="yes"){ ?>style="display:none;"<?php } ?> >Sub Total</span>&nbsp;</div></td>
						<td>
							<div id="d_edit_po_sub_total" <?php if($row_content["inc_vat"]!="yes"){ ?>style="display:none;"<?php } ?> >
							<input name="edit_po_sub_total" type="text" id="edit_po_sub_total" class="show-currency currency-edit" size="6" value="<?php if($row_content["inc_vat"]=="yes"){ echo number_format($n_sub_total,2); } ?>" readonly>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3" ><div class="mark-vat edit-mark-vat" <?php if($row_content["inc_vat"]!="yes"){ ?>style="display:none;"<?php } ?> >VAT 7%<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> ><br>ภาษีมูลค่าเพิ่ม</span></div></td>
						<td>
							<div id="d_edit_po_vat_value" <?php if($row_content["inc_vat"]!="yes"){ ?>style="display:none;"<?php } ?> >
							<input name="edit_po_vat_value" type="text" id="edit_po_vat_value" class="show-currency currency-edit" size="6" value="<?php if($row_content["inc_vat"]=="yes"){ echo number_format(($n_sub_total*0.07),2); } ?>" readonly>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3">Net Total<span class="mark-th edit-mark-th" <?php if($row_content["nationality"]!="TH"){ ?>style="display:none;"<?php } ?> ><br>รวมเงินสุทธิ</span></td>
						<td><input name="edit_po_net_total" type="text" id="edit_po_net_total" class="show-currency currency-edit" size="6" value="<?php echo $row_content["net_total"]; ?>" readonly></td>
					</tr>
				</table>
			</div>
			
		</div>
		<br>
		<div class="row" style="height:100px; padding: 10px;">
			<div class="col-6">
				Prepare by: <span id="sp_show_emp_prepare"><?php echo $row_content["prepare_emp_name"]; ?></span>
			</div>
			<div class="col-6">
				Authorized by: 
			</div>
		</div>
		<div class="row">
			<div class="col-3"></div>
			<input class="col-6 btn btn-primary" type="button" onclick="return editCheckBeforeSubmitNewPO();" value="Submit edit Purchase Order">
			<div class="col-3"></div>
		</div>
	</form>
</div>

<script type="text/javascript">

$(document).ready(function() {

  $('input.currency-edit').each(function() {
      var wrapper = $("<div class='currency-input' />");
      $(this).wrap(wrapper);
      $(this).before("<span class='currency-symbol-edit'><?php if($row_content["nationality"]!="TH"){ ?>$<?php }else{ echo "฿"; }?></span>");
      $(this).change(function() {
        var min = parseFloat($(this).attr("min"));
        var max = parseFloat($(this).attr("max"));
        var value = this.valueAsNumber;
        if(value < min)
          value = min;
        else if(value > max)
          value = max;
        $(this).val(value.toFixed(2)); 
      });
    });


});


</script>
<!-- End Edit PO Zone --------------------------------------------------------------------------->
<?php
}
?>