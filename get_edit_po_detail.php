<script src="assets/jquery-latest.js"></script>
<script type="text/javascript">
	function doCallAjax() {
		cat_id=document.formEdit.cat_id.value;
		po_detail_color=document.formEdit.po_detail_color.value;
		po_detail_no=document.formEdit.po_detail_no.value;
		po_detail_no_old=document.formEdit.po_detail_no_old.value;
		$("#show_code").html('<i class="fa fa-spin fa-circle-o-notch"></i>');  
		$.ajax({  
			type: "POST",  
			url:"getFab.php?op=edit&cat_id="+cat_id+"&po_detail_color="+po_detail_color+"&po_detail_no="+po_detail_no+"&po_detail_no_old="+po_detail_no_old ,
			success: function(data){  
				$("#show_code").html(data);  
			}  
		});
	}
	function number_format( number, decimals, dec_point, thousands_sep ) {

      var n = number, prec = decimals, dec = dec_point, sep = thousands_sep;
      n = !isFinite(+n) ? 0 : +n;
      prec = !isFinite(+prec) ? 0 : Math.abs(prec);
      sep = sep == undefined ? ',' : sep;

      var s = n.toFixed(prec),
          abs = Math.abs(n).toFixed(prec),
          _, i;

      if (abs > 1000) {
          _ = abs.split(/\D/);
          i = _[0].length % 3 || 3;

          _[0] = s.slice(0,i + (n < 0)) +
                _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
          s = _.join(dec || '.');
      } else {
          s = abs.replace('.', dec_point);
      }
      return s;
    }
	function fncSum(){
		var valSum = parseFloat(document.formEdit.po_detail_piece.value) * parseFloat(document.formEdit.po_detail_price.value);
		document.formEdit.po_detail_total.value = number_format(valSum, 2, '.', ',');
	}
</script>
<?php
include('db.php');
$po_detail_id = $_POST['po_detail_id'];

$q_po = 'SELECT * FROM po_detail INNER JOIN cat ON po_detail.cat_id = cat.cat_id WHERE po_detail.po_detail_id="'.$po_detail_id.'"';
$query_po = $conn->query($q_po);
$rs_po = $query_po->fetch_assoc();
?>
<form method="post" name="formEdit" id="formEdit" action="?vp=<?php echo base64_encode('po_sql');?>&ac=<?php echo base64_encode('po_detail_edit');?>">
	<div class="row">
		<div class="col-md-3 text-right">Product</div>
		<div class="col-md-9">
			<select class="form-control" name="cat_id" id="cat_id" required>
				<option value="<?php echo $rs_po['cat_id'];?>"><?php echo $rs_po['cat_code'];?></option>
				<option value="">Select Product</option>
				<?php
				$q_cat_acc = 'SELECT * FROM cat WHERE type_id="1" AND cat_id="'.$rs_po['cat_id'].'" ORDER BY cat_code ASC';
				$query_cat_acc = $conn->query($q_cat_acc);
				while($rs_cat_acc = $query_cat_acc->fetch_assoc()){
					echo '<option value="'.$rs_cat_acc['cat_id'].'">'.$rs_cat_acc['cat_name_en'].'</option>';
				}
				?>
			</select>
		</div>
		<div class="col-md-3 text-right">Color</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_color" id="po_detail_color" value="<?php echo $rs_po['po_detail_color'];?>">
		</div>
		<div class="col-md-3 text-right">No/Size</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_no" id="po_detail_no" value="<?php echo $rs_po['po_detail_no'];?>" OnKeyUp="doCallAjax()">
			<input type="hidden" name="po_detail_no_old" id="po_detail_no_old" value="<?php echo $rs_po['po_detail_no'];?>">
			<span id="show_code"></span>
		</div>
		<div class="col-md-3 text-right">Box</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_box" id="po_detail_box" value="<?php echo $rs_po['po_detail_box'];?>">
		</div>
		<div class="col-md-3 text-right">Amount</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_piece" id="po_detail_piece" value="<?php echo $rs_po['po_detail_piece'];?>" OnKeyUp="fncSum()">
		</div>
		<div class="col-md-3 text-right">Unit</div>
		<div class="col-md-9">
			<select class="form-control" name="po_detail_type_unit" style="width:100%" required>
				<?php
				switch ($rs_po['po_detail_type_unit']) {
					case '1':
						$option='<option value="1">Piece</option>';
						break;
					case '2':
						$option='<option value="2">Yard</option>';
						break;
					case '3':
						$option='<option value="3">KG</option>';
						break;
				}
				echo $option;
				?>
				<option value="">Select Type</option>
				<option value="1">Piece</option>
				<option value="2">Yard</option>
				<option value="3">KG</option>
			</select>
		</div>
		<div class="col-md-3 text-right">Price</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_price" id="po_detail_price" value="<?php echo number_format($rs_po['po_detail_price'],2);?>" OnKeyUp="fncSum()">
		</div>
		<div class="col-md-3 text-right">Total</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="po_detail_total" id="po_detail_total" value="<?php echo number_format($rs_po['po_detail_total'],2);?>">
		</div>
		<div class="col-md-3 text-right"></div>
		<div class="col-md-9">
			<input type="hidden" name="po_detail_id" value="<?php echo $rs_po['po_detail_id'];?>">
			<input type="hidden" name="po_type_id" value="<?php echo $rs_po['po_type_id'];?>">
			<button type="submit" class="btn btn-block btn-success">Update</button>
		</div>
	</div>
</form>

