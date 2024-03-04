<style type="text/css">
input:focus{
	border:solid 1px #333;
}
input{
	font-size: 14px;
}
.currency {
  padding-left:12px;
}

.currency-symbol {
  position:absolute;
  padding: 0px 5px;
  margin-top: -1px;
}
.currency-symbol-edit {
  position:absolute;
  padding: 0px 5px;
  margin-top: -1px;
}
.show-po-list{
	margin-top: 30px;
}
.show-po-list th{
	padding: 5px;
	background-color: #19D895;
	color: #FFF;
	font-size: 14px;
	border-style: solid;
	border-color: #39F8B5;
	border-width: 1px;
	text-align: center;
}
.show-po-list td{
	padding: 5px;
	background-color: #FFF;
	color: #000;
	font-size: 14px;
	border-style: solid;
	border-color: #39F8B5;
	border-width: 1px;
	text-align: center;
}
.btn-icon{
	cursor: pointer;
}
.add-sup-form div{
	padding: 2px;
}

.new-po-header-logo{
	text-align: center;
}
.new-po-header table{
	height: 150px;
	vertical-align: middle;
}
.d-po-title{
	border: solid 2px #000;
	border-radius: 5px 5px;
	width:250px;
}

.new-po-detail-zone th{
	text-align: center;
	background-color: #3A3;
	color: #FFF;
}

.new-po-detail-zone td{
	text-align: center;
	padding: 0px;
	margin: 0px;
}
.new-po-detail-zone input{
	text-align: center;
	width: 100%;
	border: 0px;
}
.td-detail input.auto-complete-box{
	text-align: left;
}
.auto-complete-box{
	background-color: #DDF;
}

.tbl-show-latest th{
	font-size: 13px;
	text-align: center;
}
.tbl-show-latest td{
	font-size: 12px;
	color: #FFF;
	background-color: #55A;
	border: #99D 2px solid;
	text-align: center;
	letter-spacing: 1px;
}
.tbl-show-latest{
	vertical-align: bottom;
	line-height: 21px;
}
.search-box{
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	margin-bottom: 0px;
	vertical-align: bottom;
	line-height: 22px;
}
.btn-zone input{
	font-size: 13px;
	height: 26px;
	line-height: 13px;
	padding: 5px;
	text-align: center;
}
.btn-zone{
	text-align: center;
}
.detail-po{
	background-color: #FFF;
	padding:40px;
}
.detail-po pre{
	padding:0px;
	margin: 0px;
	font-size: 16px;
}
.modal-body.show-PO{
	padding: 0px 26px 35px 26px !important;
}
.can-edit{
	background-color: #FFA;
}
</style>
<script type="text/javascript" src="assets/autocomplete/autocomplete.js"></script>
<link rel="stylesheet" href="assets/autocomplete/autocomplete.css"  type="text/css"/>



<div class="row">
	<div class="col-12">
		<div class="card">
			
			<div class="card-body" id="card-po-list">
				<h4 class="card-title">Purchase Order</h4>
				<div class="row">
					<div class="col-5">
						<table width="100%" class="tbl-show-latest">
							<?php
							$inner_td = "";
							$sql_po_number = "SELECT * FROM tbl_po_number WHERE po_year='".date("Y")."' ORDER BY pre_code ASC,po_type ASC ";
							$rs_po_number = $conn->query($sql_po_number);
							$num_code_set = $rs_po_number->num_rows;
							while ($row_po_number = $rs_po_number->fetch_assoc()) {
								$inner_td .= "<td>".$row_po_number["lastest_no"]."</td>";
							}
							?>
							<tr><th colspan="<?php echo $num_code_set; ?>">Latest No.</th></tr>
							<tr>
							<?php echo $inner_td; ?>
							</tr>
						</table>
					</div>
					<div class="col-3 search-box btn-zone" >
						Search : <br>
						<select id="search_type">
							<option value="all">All</option>
							<option value="po_number">PO No.</option>
							<option value="po_date">PO Date</option>
							<option value="sup_name">Supplier</option>
						</select>
						<input type="text" id="search_value" size="15" > 
						<input class="btn btn-warning" type="button" value="GO" onclick="GOSearch();">
					</div>
					<div class="col-4 btn-zone">
						<br>
						<input type="button" class="btn btn-primary" value="Item" onclick="showItemSearch();">
						<!-- <input type="button" class="btn btn-primary" value="Color" onclick="showColorSearch();"> -->
						<input type="button" class="btn btn-primary" value="Unit" onclick="showUnitSearch();">
						<input type="button" class="btn btn-primary" value="Supplier" onclick="showSupplierSearch();">
						<input type="button" class="btn btn-success" value="New PO" onclick="showNewPOCard();">
					</div>
				</div>

				<!-- Manage item -->
				<div id="itemSearch" style="display: none;" class="manage-content">
					<div class="modal-dialog">

					  <!-- content-->
					  <div class="modal-content">
					    <div class="modal-header">
					      
					      <h4 class="modal-title">Manage item</h4>
					      <button type="button" class="close" onclick="closePanel('itemSearch');">&times;</button>
					    </div>
					    <div class="modal-body">
					    	<div id="itemSearchZone" >
					    		<div style="padding: 0px; margin: -20px 0px 0px 0px; color:#F00; ">* Type for search item</div>
						      	<input name="show_item" type="text" id="show_item" size="30" maxlength="120" />
					  			<input name="h_item_id" type="hidden" id="h_item_id" value="" />
					  			<input class="btn-icon" type="button" value="Add NEW ITEM" onclick="showItemAddZone();">
				  			</div>
				  			<div id="itemAddZone" style="display: none;" class="add-sup-form">

				  				<div class="row">
				  					<div class="col-4">Item add: </div>
				  					<div class="col-8"><input type="text" id="in_new_item_name" size="30" maxlength="120"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Type: </div>
				  					<div class="col-8">
				  						<select id="in_new_item_type">
				  							<option value="0">==Choose type==</option>
				  							<option value="Fabric">Fabric</option>
				  							<option value="Accessory">Accessory</option>
				  						</select>
				  					</div>
				  				</div>
				  				
				  				<div class="row">
				  					<div class="col-12" align="center">
						  				
						  				&nbsp;<font color="orange" onclick="cancelNewItem();">
						  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel"></i>
						  				</font>
						  				&nbsp;
						  				<button onclick="saveNewItem();" class="btn-icon">
						  					<font color=green><i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save as NEW ITEM"></i></font> Save new item
						  				</button>
						  			</div>
				  				</div>
				  			</div>
				  			<div id="itemEditZone" style="display: none;" class="add-sup-form">

				  				<div class="row">
				  					<div class="col-4">Item edit: </div>
				  					<div class="col-8"><input type="text" id="in_edit_item_name" size="30" maxlength="120"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Type: </div>
				  					<div class="col-8">
				  						<select id="in_edit_item_type">
				  							<option value="Fabric">Fabric</option>
				  							<option value="Accessory">Accessory</option>
				  						</select>
				  					</div>
				  				</div>
				  				
				  				<div class="row" align="center">
				  					<div class="col-12">
						  				<input type="hidden" id="in_edit_item_id">
						  				&nbsp;<font color="orange" onclick="cancelEditItem();">
						  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel editing"></i>
						  				</font>
						  				&nbsp;
						  				<button onclick="saveEditItem();" class="btn-icon">
						  					<font color=green><i class="fa fa-floppy-o" aria-hidden="true" title="Save editing"></i></font> Save editing
						  				</button>
						  				&nbsp;
						  				<button onclick="deleteEditItem();" class="btn-icon">
						  					<font color=red><i class="fa fa-times-circle" aria-hidden="true" title="Delete this item"></i></font> Delete this item
						  				</button>
						  			</div>
				  				</div>
				  			</div>
				  			
					    </div>
					    
					  </div>
					  
					</div>
				</div>

				<!-- Manage color -->
				<div id="colorSearch" style="display: none;" class="manage-content">
					<div class="modal-dialog">

					  <!-- content-->
					  <div class="modal-content">
					    <div class="modal-header">
					      
					      <h4 class="modal-title">Manage color</h4>
					      <button type="button" class="close" onclick="closePanel('colorSearch');">&times;</button>
					    </div>
					    <div class="modal-body">
					    	<div id="colorSearchZone" >
					    		<div style="padding: 0px; margin: -20px 0px 0px 0px; color:#F00; ">* Type for search color</div>
						      	<input name="show_color" type="text" id="show_color" size="30" maxlength="100" />
					  			<input name="h_color_id" type="hidden" id="h_color_id" value="" />
					  			<button class="btn-icon" onclick="saveNewColor();">
					  				<font color="green"><i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save as NEW COLOR"></i></font> Save as NEW COLOR
					  			</button>
				  			</div>
				  			<div id="colorEditZone" style="display: none;">
				  				Color edit: 
				  				<input type="text" id="in_edit_color_name" size="30" maxlength="100">
				  				<input type="hidden" id="in_edit_color_id">
				  				&nbsp;<font color="orange" onclick="cancelEditColor();">
				  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel editing"></i>
				  				</font>
				  				&nbsp;<font color="green" onclick="saveEditColor();">
				  					<i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save editing"></i>
				  				</font>
				  				&nbsp;<font color="red" onclick="deleteEditColor();">
				  					<i class="fa fa-times-circle btn-icon" aria-hidden="true" title="Delete this color"></i>
				  				</font>
				  			</div>
				  			
					    </div>
					    
					  </div>
					  
					</div>
				</div>

				<!-- Manage unit -->
				<div id="unitSearch" style="display: none;" class="manage-content">
					<div class="modal-dialog">

					  <!-- content-->
					  <div class="modal-content">
					    <div class="modal-header">
					      
					      <h4 class="modal-title">Manage unit</h4>
					      <button type="button" class="close" onclick="closePanel('unitSearch');">&times;</button>
					    </div>
					    <div class="modal-body">
					    	<div id="unitSearchZone" >
					    		<div style="padding: 0px; margin: -20px 0px 0px 0px; color:#F00; ">* Type for search unit</div>
						      	<input name="show_unit" type="text" id="show_unit" size="30" maxlength="20" />
					  			<input name="h_unit_id" type="hidden" id="h_unit_id" value="" />
					  			<button class="btn-icon" onclick="saveNewUnit();">
					  				<font color="green"><i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save as NEW UNIT"></i></font> Save as NEW UNIT
					  			</button>
				  			</div>
				  			<div id="unitEditZone" style="display: none;">
				  				Unit edit: 
				  				<input type="text" id="in_edit_unit_name" size="30" maxlength="20">
				  				<input type="hidden" id="in_edit_unit_id">
				  				&nbsp;<font color="orange" onclick="cancelEditUnit();">
				  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel editing"></i>
				  				</font>
				  				&nbsp;<font color="green" onclick="saveEditUnit();">
				  					<i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save editing"></i>
				  				</font>
				  				&nbsp;<font color="red" onclick="deleteEditUnit();">
				  					<i class="fa fa-times-circle btn-icon" aria-hidden="true" title="Delete this unit"></i>
				  				</font>
				  			</div>
				  			
					    </div>
					    
					  </div>
					  
					</div>
				</div>

				<!-- Manage supplier -->
				<div id="supplierSearch" style="display: none;" class="manage-content">
					<div class="modal-dialog">

					  <!-- content-->
					  <div class="modal-content">
					    <div class="modal-header">
					      
					      <h4 class="modal-title">Manage supplier</h4>
					      <button type="button" class="close" onclick="closePanel('supplierSearch');">&times;</button>
					    </div>
					    <div class="modal-body">
					    	<div id="supplierSearchZone" >
					    		<div style="padding: 0px; margin: -20px 0px 0px 0px; color:#F00; ">* Type for search supplier</div>
						      	<input name="show_supplier" type="text" id="show_supplier" size="30" />
					  			<input name="h_supplier_id" type="hidden" id="h_supplier_id" value="" />
					  			<input class="btn-icon" type="button" value="Add NEW SUPPLIER" onclick="addNewSupplier();">
				  			</div>
				  			
				  			<div id="supplierAddZone" style="display: none;" class="add-sup-form">
				  				<div class="row">
				  					<div class="col-4">Nationality: </div>
				  					<div class="col-8">
				  						<select id="add_nationality">
				  							<option value="TH">Thai</option>
				  							<option value="NOT_TH">Foreign</option>
				  						</select>
				  					</div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Supplier name: </div>
				  					<div class="col-8"><input type="text" id="add_sup_name"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Address: </div>
				  					<div class="col-8"><textarea id="add_sup_address" rows="4"></textarea></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Tel: </div>
				  					<div class="col-8"><input type="tel" id="add_sup_tel"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Fax: </div>
				  					<div class="col-8"><input type="text" id="add_sup_fax"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Email: </div>
				  					<div class="col-8"><input type="email" id="add_sup_email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Sale name: </div>
				  					<div class="col-8"><input type="text" id="add_sale_name"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">TAX ID: </div>
				  					<div class="col-8"><input type="text" id="add_sup_tax_id"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Payment period: </div>
				  					<div class="col-8"><input type="number" id="add_sup_payment"> Days</div>
				  				</div>
				  				
				  				<div class="row">
				  					<div class="col-12" align="center">
						  				&nbsp;<font color="orange" onclick="cancelAddSupplier();">
						  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel adding supplier"></i>
						  				</font>
						  				&nbsp;
						  				<button onclick="saveNewSupplier();" class="btn-icon">
						  				<font color="green">
						  					<i class="fa fa-floppy-o btn-icon" aria-hidden="true" title="Save new supplier"></i>
						  				</font>
						  				Save new supplier
						  				</button>
						  			</div>
						  		</div>
				  			</div>

				  			<div id="supplierEditZone" style="display: none;" class="add-sup-form">
				  				<div class="row">
				  					<div class="col-4">Nationality: </div>
				  					<div class="col-8">
				  						<select id="in_edit_nationality">
				  							<option value="TH">Thai</option>
				  							<option value="NOT_TH">Foreign</option>
				  						</select>
				  					</div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Supplier name: </div>
				  					<div class="col-8"><input type="text" id="in_edit_sup_name"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Address: </div>
				  					<div class="col-8"><textarea id="in_edit_sup_address" rows="4"></textarea></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Tel: </div>
				  					<div class="col-8"><input type="tel" id="in_edit_sup_tel"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Fax: </div>
				  					<div class="col-8"><input type="text" id="in_edit_sup_fax"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Email: </div>
				  					<div class="col-8"><input type="email" id="in_edit_sup_email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Sale name: </div>
				  					<div class="col-8"><input type="text" id="in_edit_sale_name"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">TAX ID: </div>
				  					<div class="col-8"><input type="text" id="in_edit_sup_tax_id"></div>
				  				</div>
				  				<div class="row">
				  					<div class="col-4">Payment period: </div>
				  					<div class="col-8"><input type="number" id="in_edit_sup_payment"> Days</div>
				  				</div>
				  				
				  				<div class="row">
				  					<div class="col-12" align="center">
				  						<input type="hidden" id="in_edit_po_sup_id">
						  				<font color="orange" onclick="cancelEditSupplier();">
						  					<i class="fa fa-backward btn-icon" aria-hidden="true" title="Cancel editing"></i>
						  				</font>
						  				&nbsp;
						  				<button onclick="saveEditSupplier();" class="btn-icon">
						  				<font color="green">
						  					<i class="fa fa-floppy-o" aria-hidden="true"></i>
						  				</font>
						  				Save editing
						  				</button>
						  				&nbsp;
						  				<button onclick="deleteEditSupplier();" class="btn-icon">
						  				<font color="red" >
						  					<i class="fa fa-times-circle" aria-hidden="true"></i>
						  				</font>
						  				Delete this supplier
						  				</button>
						  				
						  			</div>
						  		</div>
				  				
				  				
				  			</div>
				  			
					    </div>
					    
					  </div>
					  
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<table width="100%" class="show-po-list">
							<thead>
								<tr>
									<th>#</th><th>PO No.</th><th>PO Date</th><th>Supplier</th><th>Items</th><th>Cost</th><th>Status</th>
									<th>Action <div style="float:right;"> | Page: <select onchange="searchPOList();" id="select_page"><option value="1">1</option></select></div>
									</th>
								</tr>
							</thead>
							<tbody id="po_list_content">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>

<!-- -----------------------------------New PO ZONE----------------------------------- -->
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
								<td>
									<div id="d_new_po_sub_total">
										<input name="new_po_sub_total" type="text" id="new_po_sub_total" class="show-currency currency" size="6" readonly>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="3" ><div class="mark-vat" style="display:none;">VAT 7%<span class="mark-th" style="display:none;"><br>ภาษีมูลค่าเพิ่ม</span></div></td>
								<td>
									<div id="d_new_po_vat_value">
										<input name="new_po_vat_value" type="text" id="new_po_vat_value" class="show-currency currency" size="6" readonly>
									</div>
								</td>
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
			<!-- End New PO Zone --------------------------------------------------------------------------->

		</div>
	</div>
</div>

<iframe name="save_new_po_frame" style="display: none;" width="0" height="0"></iframe>



<!-- Show PO Modal -->
<div id="showPOModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body show-PO">
      	<div id="showPOPaper"></div>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

searchPOList();

function printPDF(f_name){
	var divContents = $("#print_this").html();
    var printWindow = window.open('', '', 'height=2000,width=1200');
    printWindow.document.write('<html><head><title>'+f_name+'</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
/*---Start Script for Edit PO zone---*/

function editShowTH(){

	if($("#edit_chk_lang:checked").val()=="TH"){
		$('.edit-mark-th').show();
		editShowTHCurrency();
	}else{
		$('.edit-mark-th').hide();
		editShowUSDCurrency();
	}
}

function editShowVAT(){

	if($("#edit_chk_include_vat:checked").val()=="yes"){
		$('.edit-mark-vat').show();
		$('#d_edit_po_sub_total').show();
		$('#d_edit_po_vat_value').show();
	}else{
		$('.edit-mark-vat').hide();
		$('#d_edit_po_sub_total').hide();
		$('#d_edit_po_vat_value').hide();
	}

	editCalculateAmount();
}

function editCalculateAmount(){

	var total_amount = 0.0;
	for(var k=1; k<=<?php echo $gen_row; ?>; k++){
		if($('#edit_po_amount'+k).val()!=""){
			total_amount += parseFloat($('#edit_po_amount'+k).val());
		}
		
	}

	total_amount = total_amount.toFixed(2);

	$('#edit_po_sub_total').val(total_amount);

	var vat_value = total_amount*0.07;
	vat_value = vat_value.toFixed(2);

	$('#edit_po_vat_value').val(vat_value);
	
	var net_total = 0.0;
	if($("#edit_chk_include_vat:checked").val()=="yes"){
		net_total = parseFloat(total_amount)+parseFloat(vat_value);
	}else{
		net_total = parseFloat(total_amount);
	}
	net_total = net_total.toFixed(2);

	$('#edit_po_net_total').val(net_total);

}

function editShowUSDCurrency(){
	$('.currency-symbol-edit').html('$');
}

function editShowTHCurrency(){
    $('.currency-symbol-edit').html('฿');
}

function editCalculateQTY(row_num=0){

	var total_QTY = 0.0;
	for(var k=1; k<=row_num; k++){
		if($('#edit_po_qty'+k).val()!=""){
			total_QTY += parseFloat($('#edit_po_qty'+k).val());
		}
		
	}

	$('#d_total_qty_edit').html(total_QTY.toFixed(2));
	$('#edit_po_total_qty').val(total_QTY.toFixed(2));

}

function editCalculateRow(num_row){

	var qty = 0.0;
	if( $('#edit_po_qty'+num_row).val()!="" ){
		qty = $('#edit_po_qty'+num_row).val();
	}

	var ppu = 0.0;
	if( $('#edit_po_ppu'+num_row).val()!="" ){
		ppu = $('#edit_po_ppu'+num_row).val();
	}

	var row_total = parseFloat(qty)*parseFloat(ppu);

	$('#edit_po_amount'+num_row).val(row_total.toFixed(2));

	editCalculateAmount();

}

function editShowNumberToString(){

	$.ajax({ 
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/gen_num2str.php" ,
		data:{
			"s_number":$('#edit_po_net_total').val()
		},
		success: function(resp){  
			
			$('#sp_total_string_edit').html(resp.result);
			
		}  
	});
}

function editCheckBeforeSubmitNewPO(){


	if($('#show_supplier_edit').val()==""){
		alert("Please input supplier");
		return false;
	}

	if($('#edit_po_date').val()==""){
		alert("Please input PO Date");
		return false;
	}

	if($('#edit_sale_name').val()==""){
		alert("Please input Sale name");
		return false;
	}

	var count_have_data = 0;

	for(var i=1;i<=<?php echo $gen_row; ?>;i++){

		if($('#edit_po_item'+i).val()!=""){
			if( ($('#edit_po_qty'+i).val()=="") || ( parseFloat($('#edit_po_qty'+i).val())==0.0) || ($('#edit_po_ppu'+i).val()=="") || ( parseFloat($('#edit_po_ppu'+i).val())==0.0)  ){
				alert("Please input QTY and Price/Unit");
				return false;
			}else{
				count_have_data++;
			}
		}
		if( ($('#edit_po_qty'+i).val()!="") && ( parseFloat($('#edit_po_qty'+i).val())!=0.0 ) ){
			if( ($('#edit_po_item'+i).val()=="") || ($('#edit_po_ppu'+i).val()=="") || ( parseFloat($('#edit_po_ppu'+i).val())==0.0)  ){
				alert("Please input Item and Price/Unit");
				return false;
			}else{
				count_have_data++;
			}
		}
		if( ($('#edit_po_ppu'+i).val()!="") && ( parseFloat($('#edit_po_ppu'+i).val())!=0.0) ){
			if( ($('#edit_po_item'+i).val()=="") || ($('#edit_po_qty'+i).val()=="") || ( parseFloat($('#edit_po_qty'+i).val())==0.0) ){
				alert("Please input Item and QTY");
				return false;
			}else{
				count_have_data++;
			}
		}

		
	}

	if(count_have_data<3){
		alert("Please input item");
		return false;
	}

	$('#edit_po_form').submit();
}

function saveEditPOSuccess(){

	alert("Save editing PO success.");
	editShowNumberToString();
	searchPOList();
}

function saveEditPOFail(){
	alert("Save editing PO fail..");
}

/*---End Script for Edit PO zone---*/

/*---Start Script for New PO zone---*/
<?php 
for($i=1;$i<=$gen_row;$i++){
?>
make_autocom('new_po_color<?php echo $i; ?>','h_color_id_new_po<?php echo $i; ?>','atc_color');
make_autocom('new_po_item<?php echo $i; ?>','h_item_id_new_po<?php echo $i; ?>','atc_item');
<?php
}
?>

function checkBeforeSubmitNewPO(){

	if($('#new_po_h_id').val()=="0"){
		alert("Please select Header");
		return false;
	}

	if($('#show_supplier_new').val()==""){
		alert("Please input supplier");
		return false;
	}

	if($('#new_po_date').val()==""){
		alert("Please input PO Date");
		return false;
	}

	if($('#new_sale_name').val()==""){
		alert("Please input Sale name");
		return false;
	}

	var count_have_data = 0;

	for(var i=1;i<=<?php echo $gen_row; ?>;i++){

		if($('#new_po_item'+i).val()!=""){
			if( ($('#new_po_qty'+i).val()=="") || ( parseFloat($('#new_po_qty'+i).val())==0.0) || ($('#new_po_ppu'+i).val()=="") || ( parseFloat($('#new_po_ppu'+i).val())==0.0)  ){
				alert("Please input QTY and Price/Unit");
				return false;
			}else{
				count_have_data++;
			}
		}
		if( ($('#new_po_qty'+i).val()!="") && ( parseFloat($('#new_po_qty'+i).val())!=0.0 ) ){
			if( ($('#new_po_item'+i).val()=="") || ($('#new_po_ppu'+i).val()=="") || ( parseFloat($('#new_po_ppu'+i).val())==0)  ){
				alert("Please input Item and Price/Unit");
				return false;
			}else{
				count_have_data++;
			}
		}
		if( ($('#new_po_ppu'+i).val()!="") && ( parseFloat($('#new_po_ppu'+i).val())!=0.0) ){
			if( ($('#new_po_item'+i).val()=="") || ($('#new_po_qty'+i).val()=="") || ( parseFloat($('#new_po_qty'+i).val())==0.0) ){
				alert("Please input Item and QTY");
				return false;
			}else{
				count_have_data++;
			}
		}

		
	}

	if(count_have_data<3){
		alert("Please input item");
		return false;
	}

	$('#new_po_form').submit();

}

function saveNewPOSuccess(){

	closeNewPOCard();
	searchPOList();
}

function saveNewPOFail(){
	alert("Save new PO fail..");
}

function showTH(){

	if($("#chk_lang:checked").val()=="TH"){
		$('.mark-th').show();
		showTHCurrency();
	}else{
		$('.mark-th').hide();
		showUSDCurrency();
	}
}

function showVAT(){

	if($("#chk_include_vat:checked").val()=="yes"){
		$('.mark-vat').show();
		$('#d_new_po_sub_total').show();
		$('#d_new_po_vat_value').show();
	}else{
		$('.mark-vat').hide();
		$('#d_new_po_sub_total').hide();
		$('#d_new_po_vat_value').hide();
	}

	calculateAmount();
}

function newPOChangeType(){

	if($('#new_po_h_id').val()=="0"){
		return false;
	}

	showPOnumber();

}

function newPOChangeHeader(){
	var po_h_id = $('#new_po_h_id').val();

	if(po_h_id=="0"){
		return false;
	}

	var comp_name = "";
	var comp_address = "";
	var comp_tel = "";
	var comp_fax = "";
	var website = "";
	var comp_tax_id = "";
	var comp_logo = "";

	var tmp_po_h_id = po_h_id.split(",");
	po_h_id = tmp_po_h_id[0];

	switch(po_h_id){
		<?php
		foreach($a_header_info as $s_po_h_id => $a_row_header){

			echo 'case "'.$s_po_h_id.'" : comp_name = "'.$a_row_header["comp_name"].'"; ';
			echo 'comp_address = "'.$a_row_header["comp_address"].'"; ';
			echo 'comp_tel = "'.$a_row_header["comp_tel"].'"; ';
			echo 'comp_fax = "'.$a_row_header["comp_fax"].'"; ';
			echo 'website = "'.$a_row_header["website"].'"; ';
			echo 'comp_tax_id = "'.$a_row_header["comp_tax_id"].'"; ';
			echo 'comp_logo = "'.$a_row_header["comp_logo"].'"; ';

			$comp_data = base64_encode(json_encode($a_row_header));

			echo '$("#hidden_comp_data").val("'.$comp_data.'"); ';

			echo 'break; ';
		}
		?>
	}

	var inner_logo = '<img src="assets/images/po-logo/'+comp_logo+'">';
	$('#new_head_comp_logo').html(inner_logo);

	var inner_info = '<table>';

	if(comp_name!=""){ inner_info += '<tr><td>'+comp_name+'</td></tr>'; }
	if(comp_address!=""){ inner_info += '<tr><td>'+comp_address+'</td></tr>'; }
	if(comp_tel!=""){ inner_info += '<tr><td>Tel: '+comp_tel; if(comp_fax!=""){ inner_info += ' Fax: '+comp_fax; } inner_info += '</td></tr>'; }
	if(website!=""){ inner_info += '<tr><td>Website: '+website+'</td></tr>'; }
	if(comp_tax_id!=""){ inner_info += '<tr><td>Company Tax ID: '+comp_tax_id+'</td></tr>'; }

	inner_info += '</table>';

	$('#new_head_comp_info').html(inner_info);

	showPOnumber();
	
}

function showPOnumber(){

	var po_h_id = $('#new_po_h_id').val();
	var pre_code = po_h_id.split(",");
	var po_type = $('#new_po_type').val();

	$('#sp_new_po_number').html('<font color=blue><b>'+pre_code[1]+'-'+po_type+'<?php echo date("ym");?>XXX</b></font>');

}

function showSupplierNewPOZone(po_sup_id,sup_name,nationality,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment){

	if(window.atob(nationality)=="TH"){
		$("#chk_lang").prop("checked",true);
		showTH();
	}else{
		$("#chk_lang").prop("checked",false);
		showTH();
	}
	
	$('#new_sup_address').val(window.atob(sup_address));
	$('#new_sup_tel').val(window.atob(sup_tel));
	$('#new_sup_fax').val(window.atob(sup_fax));
	$('#new_sup_email').val(window.atob(sup_email));
	$('#new_sale_name').val(window.atob(sale_name));
	$('#new_sup_tax_id').val(window.atob(sup_tax_id));
	$('#new_sup_payment').val(window.atob(sup_payment));

}



function genUnitTag(row_num=0){

	$.ajax({ 
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/get_unit.php" ,
		data:{
			"po_sup_id":$('#in_edit_po_sup_id').val()
		},
		success: function(resp){  
			
			var data_len = resp.result.length;

			for(var k=1; k<=row_num; k++){
				inner_select = '<select id="new_po_unit'+k+'" name="new_po_unit[]">';

				for(var i=0; i<data_len; i++){
					inner_select += '<option value="'+resp.result[i].unit_name+'">';
					inner_select += resp.result[i].unit_name;
					inner_select += '</option>';
				}

				inner_select += '</select>';

				$('#td_new_po_unit'+k).html(inner_select);
			}
			
		}  
	});

}

function calculateQTY(row_num=0){

	var total_QTY = 0.0;
	for(var k=1; k<=row_num; k++){
		if($('#new_po_qty'+k).val()!=""){
			total_QTY += parseFloat($('#new_po_qty'+k).val());
		}
		
	}

	$('#d_total_qty').html(total_QTY.toFixed(2));
	$('#new_po_total_qty').val(total_QTY.toFixed(2));

}

function calculateAmount(){

	var total_amount = 0.0;
	for(var k=1; k<=<?php echo $gen_row; ?>; k++){
		if($('#new_po_amount'+k).val()!=""){
			total_amount += parseFloat($('#new_po_amount'+k).val());
		}
		
	}

	total_amount = total_amount.toFixed(2);

	//$('#d_sub_total').html(total_amount);
	$('#new_po_sub_total').val(total_amount);

	var vat_value = total_amount*0.07;
	vat_value = vat_value.toFixed(2);

	//$('#d_vat_value').html(vat_value);
	$('#new_po_vat_value').val(vat_value);
	
	var net_total = 0.0;
	if($("#chk_include_vat:checked").val()=="yes"){
		net_total = parseFloat(total_amount)+parseFloat(vat_value);
	}else{
		net_total = parseFloat(total_amount);
	}
	net_total = net_total.toFixed(2);

	//$('#d_net_total').html(net_total);
	$('#new_po_net_total').val(net_total);

}

function calculateRow(num_row){

	var qty = 0.0;
	if( $('#new_po_qty'+num_row).val()!="" ){
		qty = $('#new_po_qty'+num_row).val();
	}

	var ppu = 0.0;
	if( $('#new_po_ppu'+num_row).val()!="" ){
		ppu = $('#new_po_ppu'+num_row).val();
	}

	var row_total = parseFloat(qty)*parseFloat(ppu);

	$('#new_po_amount'+num_row).val(row_total.toFixed(2));

	calculateAmount();

}

function showNumberToString(){

	$.ajax({ 
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/gen_num2str.php" ,
		data:{
			"s_number":$('#new_po_net_total').val()
		},
		success: function(resp){  
			
			$('#sp_total_string').html(resp.result);
			
		}  
	});
}

function showUSDCurrency(){
	$('.currency-symbol').html('$');
}

function showTHCurrency(){
    $('.currency-symbol').html('฿');
}

/*---End Script for New PO zone---*/

function make_autocom(autoObj,showObj,atc_portal){

    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
	var use_portal = "";
	
	if(atc_portal=="atc_item"){
		use_portal = "ajax/purchase/atc_item.php";
	}else if(atc_portal=="atc_item_new"){
		use_portal = "ajax/purchase/atc_item.php";
	}else if(atc_portal=="atc_color"){
		use_portal = "ajax/purchase/atc_color.php";
	}else if(atc_portal=="atc_unit"){
		use_portal = "ajax/purchase/atc_unit.php";
	}else if(atc_portal=="atc_supplier"){
		use_portal = "ajax/purchase/atc_supplier.php";
	}else if(atc_portal=="atc_supplier_new"){
		use_portal = "ajax/purchase/atc_supplier.php";
	}

    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {      
            document.getElementById(mkSerValObj).value = id;
        }
		
		this.showEdit = function(id,value,nationality=null,sup_address=null,sup_tel=null,sup_fax=null,sup_email=null,sale_name=null,sup_tax_id=null,sup_payment=null) {     
			if(atc_portal=="atc_item"){
				showItemEditZone(id,value,nationality);
			}else if(atc_portal=="atc_item_new"){
				showItemNewZone(id,value);
			}else if(atc_portal=="atc_color"){
				showColorEditZone(id,value);
			}else if(atc_portal=="atc_unit"){
				showUnitEditZone(id,value);
			}else if(atc_portal=="atc_supplier"){
				showSupplierEditZone(id,value,nationality,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment);
			}else if(atc_portal=="atc_supplier_new"){
				document.getElementById(mkSerValObj).value = id;
				showSupplierNewPOZone(id,value,nationality,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment);
			}
        }

        if ( this.isModified ){
            this.setValue("");
		}
        if ( this.value.length < 1 && this.isNotClick ){
			
            return ;    
		}

        return use_portal+"?q=" +encodeURIComponent(this.value);
    }); 
}   
   
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom('show_item','h_item_id','atc_item');
make_autocom('show_item_new','h_item_id_new','atc_item_new');
make_autocom('show_color','h_color_id','atc_color');
make_autocom('show_unit','h_unit_id','atc_unit');
make_autocom('show_supplier','h_supplier_id','atc_supplier');
make_autocom('show_supplier_new','h_supplier_id_new','atc_supplier_new');



function showItemSearch(){

	cancelEditItem();

	$('.manage-content').hide();
	$('.autocomplete_icon').fadeIn(500);

	$('#itemSearch').fadeIn(500);
}


function showItemEditZone(item_id,item_name,item_type){

	$('#itemSearchZone').hide();
	$('.autocomplete_icon').hide();
	$('#in_edit_item_id').val(item_id);
	$('#in_edit_item_name').val(item_name);
	$('#in_edit_item_type').val(window.atob(item_type));
	$('#itemEditZone').show();

}

function saveEditItem(){
	
	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_edit_item.php" ,
		data:{
			"item_id":$('#in_edit_item_id').val(),
			"item_name":$('#in_edit_item_name').val(),
			"item_type":$('#in_edit_item_type').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save data success..");
				cancelEditItem();

			}else{
				alert("Fail to save the data!!");
			}
			
		}  
	});
}

function deleteEditItem(){
	if(confirm("Do you want to delete this item?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/purchase/delete_edit_item.php" ,
			data:{
				"item_id":$('#in_edit_item_id').val()
			},
			success: function(resp){  
				
				if(resp.result=="success"){
					
					alert("Delete data success..");
					cancelEditItem();

				}else{
					alert("Fail to delete the data!!");
				}
				
			}  
		});
	}
}

function cancelEditItem(){
	
	$('#in_edit_item_id').val('');
	$('#in_edit_item_name').val('');
	//$('#in_edit_item_type').val(0);
	$('#itemEditZone').hide();

	$('#h_item_id').val('');
	$('#show_item').val('');

	$('#itemSearchZone').show();
	$('.autocomplete_icon').show();
}

function showItemAddZone(){

	if($('#show_item').val()==""){
		return false;
	}

	$('#itemSearchZone').hide();
	$('.autocomplete_icon').hide();
	$('#in_new_item_name').val($('#show_item').val());
	$('#itemAddZone').show();

}

function cancelNewItem(){

	$('#in_new_item_name').val('');
	$('#in_new_item_type').val(0);
	$('#itemAddZone').hide();

	$('#h_item_id').val('');
	$('#show_item').val('');

	$('#itemSearchZone').show();
	$('.autocomplete_icon').show();
}

function saveNewItem(){

	if($('#in_new_item_name').val()==""){
		alert("Please input item name");
		return false;
	}

	if($('#in_new_item_type').val()=="0"){
		alert("Please choose type");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_new_item.php" ,
		data:{
			"item_name":$('#in_new_item_name').val(),
			"item_type":$('#in_new_item_type').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save new data success..");
				cancelNewItem();

			}else{
				alert("Fail to save new data!!");
			}
			
		}  
	});
}

function showColorSearch(){

	cancelEditColor();

	$('.manage-content').hide();
	$('.autocomplete_icon').fadeIn(500);

	$('#colorSearch').fadeIn(500);
}


function showColorEditZone(color_id,color_name){

	$('#colorSearchZone').hide();
	$('.autocomplete_icon').hide();
	$('#in_edit_color_id').val(color_id);
	$('#in_edit_color_name').val(color_name);
	$('#colorEditZone').show();

}

function saveEditColor(){
	
	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_edit_color.php" ,
		data:{
			"color_id":$('#in_edit_color_id').val(),
			"color_name":$('#in_edit_color_name').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save data success..");
				cancelEditColor();

			}else{
				alert("Fail to save the data!!");
			}
			
		}  
	});
}

function deleteEditColor(){
	if(confirm("Do you want to delete this color?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/purchase/delete_edit_color.php" ,
			data:{
				"color_id":$('#in_edit_color_id').val()
			},
			success: function(resp){  
				
				if(resp.result=="success"){
					
					alert("Delete data success..");
					cancelEditColor();

				}else{
					alert("Fail to delete the data!!");
				}
				
			}  
		});
	}
}

function cancelEditColor(){
	
	$('#in_edit_color_id').val('');
	$('#in_edit_color_name').val('');
	$('#colorEditZone').hide();

	$('#h_color_id').val('');
	$('#show_color').val('');

	$('#colorSearchZone').show();
	$('.autocomplete_icon').show();
}

function saveNewColor(){

	if($('#show_color').val()==""){
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_new_color.php" ,
		data:{
			"color_name":$('#show_color').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save new data success..");
				cancelEditColor();

			}else{
				alert("Fail to save new data!!");
			}
			
		}  
	});
}

function showUnitSearch(){

	cancelEditUnit();

	$('.manage-content').hide();
	$('.autocomplete_icon').fadeIn(500);

	$('#unitSearch').fadeIn(500);

}

function showUnitEditZone(unit_id,unit_name){

	$('#unitSearchZone').hide();
	$('.autocomplete_icon').hide();
	$('#in_edit_unit_id').val(unit_id);
	$('#in_edit_unit_name').val(unit_name);
	$('#unitEditZone').show();

}

function saveEditUnit(){
	
	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_edit_unit.php" ,
		data:{
			"unit_id":$('#in_edit_unit_id').val(),
			"unit_name":$('#in_edit_unit_name').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save data success..");
				cancelEditUnit();

			}else{
				alert("Fail to save the data!!");
			}
			
		}  
	});
}

function deleteEditUnit(){
	if(confirm("Do you want to delete this unit?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/purchase/delete_edit_unit.php" ,
			data:{
				"unit_id":$('#in_edit_unit_id').val()
			},
			success: function(resp){  
				
				if(resp.result=="success"){
					
					alert("Delete data success..");
					cancelEditUnit();

				}else{
					alert("Fail to delete the data!!");
				}
				
			}  
		});
	}
}

function cancelEditUnit(){
	
	$('#in_edit_unit_id').val('');
	$('#in_edit_unit_name').val('');
	$('#unitEditZone').hide();

	$('#h_unit_id').val('');
	$('#show_unit').val('');

	$('#unitSearchZone').show();
	$('.autocomplete_icon').show();
}

function saveNewUnit(){

	if($('#show_unit').val()==""){
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_new_unit.php" ,
		data:{
			"unit_name":$('#show_unit').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save new data success..");
				cancelEditUnit();

			}else{
				alert("Fail to save new data!!");
			}
			
		}  
	});
}

function showSupplierSearch(){

	cancelEditSupplier();
	cancelAddSupplier();

	$('.manage-content').hide();
	$('.autocomplete_icon').fadeIn(500);

	$('#supplierSearch').fadeIn(500);
}

function showSupplierEditZone(po_sup_id,sup_name,nationality,sup_address,sup_tel,sup_fax,sup_email,sale_name,sup_tax_id,sup_payment){

	$('#supplierSearchZone').hide();
	$('.autocomplete_icon').hide();

	$('#in_edit_po_sup_id').val(po_sup_id);
	$('#in_edit_sup_name').val(sup_name);
	$('#in_edit_nationality').val(window.atob(nationality));
	$('#in_edit_sup_address').val(window.atob(sup_address));
	$('#in_edit_sup_tel').val(window.atob(sup_tel));
	$('#in_edit_sup_fax').val(window.atob(sup_fax));
	$('#in_edit_sup_email').val(window.atob(sup_email));
	$('#in_edit_sale_name').val(window.atob(sale_name));
	$('#in_edit_sup_tax_id').val(window.atob(sup_tax_id));
	$('#in_edit_sup_payment').val(window.atob(sup_payment));

	$('#supplierEditZone').show();

}

function saveEditSupplier(){

	if($('#in_edit_sup_name').val()==""){
		alert("Please fill Supplier name...");
		return false;
	}
	
	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_edit_sup.php" ,
		data:{
			"po_sup_id":$('#in_edit_po_sup_id').val(),
			"sup_name":$('#in_edit_sup_name').val(),
			"nationality":$('#in_edit_nationality').val(),
			"sup_address":$('#in_edit_sup_address').val(),
			"sup_tel":$('#in_edit_sup_tel').val(),
			"sup_fax":$('#in_edit_sup_fax').val(),
			"sup_email":$('#in_edit_sup_email').val(),
			"sale_name":$('#in_edit_sale_name').val(),
			"sup_tax_id":$('#in_edit_sup_tax_id').val(),
			"sup_payment":$('#in_edit_sup_payment').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				alert("Save data success..");
				cancelEditSupplier();

			}else{
				alert("Fail to save the data!!"+resp.result);

			}
			
		}  
	});
}

function deleteEditSupplier(){
	if(confirm("Do you want to delete this supplier?")){
		$.ajax({  
			type: "POST",  
			dataType: "json",
			url:"ajax/purchase/delete_edit_sup.php" ,
			data:{
				"po_sup_id":$('#in_edit_po_sup_id').val()
			},
			success: function(resp){  
				
				if(resp.result=="success"){
					
					alert("Delete data success..");
					cancelEditSupplier();

				}else{
					alert("Fail to delete the data!!");
				}
				
			}  
		});
	}
}

function cancelEditSupplier(){
	
	$('#in_edit_supplier_id').val('');
	$('#in_edit_supplier_name').val('');
	$('#supplierEditZone').hide();

	$('#h_supplier_id').val('');
	$('#show_supplier').val('');

	$('#supplierSearchZone').show();
	$('.autocomplete_icon').show();
}

function addNewSupplier(){

	if($('#show_supplier').val()==""){
		return false;
	}

	$('#add_sup_name').val($('#show_supplier').val());

	$('#supplierSearchZone').hide();
	$('.autocomplete_icon').hide();
	$('#supplierAddZone').show();

	

}

function cancelAddSupplier(){

	$('#supplierSearchZone').show();
	$('.autocomplete_icon').show();
	$('#supplierAddZone').hide();
}

function saveNewSupplier(){

	if($('#add_sup_name').val()==""){
		alert("Please fill Supplier name...");
		return false;
	}

	$.ajax({  
		type: "POST",  
		dataType: "json",
		url:"ajax/purchase/save_new_sup.php" ,
		data:{
			"nationality":$('#add_nationality').val(),
			"sup_name":$('#add_sup_name').val(),
			"sup_address":$('#add_sup_address').val(),
			"sup_tel":$('#add_sup_tel').val(),
			"sup_fax":$('#add_sup_fax').val(),
			"sup_email":$('#add_sup_email').val(),
			"sale_name":$('#add_sale_name').val(),
			"sup_tax_id":$('#add_sup_tax_id').val(),
			"sup_payment":$('#add_sup_payment').val()
		},
		success: function(resp){  
			
			if(resp.result=="success"){
				
				$('#add_nationality').val("");
				$('#add_sup_name').val("");
				$('#add_sup_address').val("");
				$('#add_sup_tel').val("");
				$('#add_sup_fax').val("");
				$('#add_sup_email').val("");
				$('#add_sale_name').val("");
				$('#add_sup_tax_id').val("");
				$('#add_sup_payment').val("");

				alert("Save new data success..");

				cancelAddSupplier();

			}else{
				alert("Fail to save new data!!");
			}
			
		}  
	});
}

function closePanel(modal_id){
	$('#'+modal_id).fadeOut(500);
	$('.autocomplete_icon').fadeOut(500);
}

function showNewPOCard(){

	$('.manage-content').fadeOut(500);
	$('.autocomplete_icon').hide();

	$('#card-po-list').fadeOut(1000);
	setTimeout(function(){
		$('#card-po-new').show(500);
		genUnitTag(<?php echo $gen_row; ?>);

	}, 1000);
	
}

function closeNewPOCard(){

	$('#new_po_form').trigger("reset");
	showTH();
	showVAT();
	$('#d_total_qty').html('');
	$('#sp_total_string').html('');

	$('#card-po-new').fadeOut(1000);
	setTimeout(function(){
		$('#card-po-list').show(500);
	}, 1000);

}

/*---Start Script for List PO zone---*/
function showPOdetail(po_con_id,action='view'){


	$('#showPOPaper').html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>Loading...');

	$.ajax({  
		type: "POST",  
		dataType: "html",
		url:"ajax/purchase/get_po_detail.php" ,
		data:{
			"po_con_id":po_con_id,
			"action":action
		},
		success: function(resp){  
			
			$('#showPOPaper').html(resp);
			
		}  
	});

}

function GOSearch(){

	$('#select_page').val("1");
	searchPOList();
}

function searchPOList(){

	var select_page = $('#select_page').val();
	var search_type = $('#search_type').val();
	var search_value = $('#search_value').val();

	$.ajax({ 
		type: "POST",  
		dataType: "html",
		url:"ajax/purchase/get_po_list.php" ,
		data:{
			"select_page":select_page,
			"search_type":search_type,
			"search_value":search_value
		},
		success: function(resp){  
			
			$('#po_list_content').html(resp);
			getPageNumber();
			
		}  
	});
	
}

function getPageNumber(){

	var select_page = $('#select_page').val();
	var search_type = $('#search_type').val();
	var search_value = $('#search_value').val();

	$.ajax({ 
		type: "POST",  
		dataType: "html",
		url:"ajax/purchase/get_po_list_page_no.php" ,
		data:{
			"select_page":select_page,
			"search_type":search_type,
			"search_value":search_value
		},
		success: function(resp){  
			
			$('#select_page').html(resp);
			
		}  
	});
	
}

/*---End Script for List PO zone---*/
</script>

<script type="text/javascript">

$(document).ready(function() {

  $('input.currency').each(function() {
      var wrapper = $("<div class='currency-input' />");
      $(this).wrap(wrapper);
      $(this).before("<span class='currency-symbol'>$</span>");
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