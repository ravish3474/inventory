<?php 
	if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}
	include('respond-button.php');

	if($op=='accessory_list'){
		if(isset($_GET['cat_id'])){$cat_id=base64_decode($_GET['cat_id']);}
?>
<style>
.progress {
    margin-top: 20px;
    display: none;
}

.progress-bar {
    background-color: #007bff; /* Change the progress bar color */
    color: #fff; /* Text color for the progress bar */
    font-weight: bold; /* Optionally, set font weight */
    padding: 8px; /* Padding around the text */
    text-align: center; /* Center-align text */
}

.progress-bar-edit {
    background-color: #007bff; /* Change the progress bar color */
    color: #fff; /* Text color for the progress bar */
    font-weight: bold; /* Optionally, set font weight */
    padding: 8px; /* Padding around the text */
    text-align: center; /* Center-align text */
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<h4 class="card-title">Accessory</h4>
							</div>
							<div class="col-sm-6 text-right">
							    <button class="btn btn-primary" data-toggle="modal" data-target="#accessory_modal">Add Accessory</button>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table id="order-listing" class="table dataTable table-hover">
										<thead>
											<tr class="bg-dark text-white">
												<th class="text-center">PRODUCT</th>
												<th>PICTURE</th>
												<th style="display:none;">PICTURE</th>
												<th class="text-center">SIZE /<br>COLOUR</th>
												<th class="text-center">LAST<br>USED</th>
												<th class="text-center">USED</th>
												<th class="text-center">BALANCE</th>
												<th class="text-center">UNIT<br> TYPE</th>
												<th class="text-center">LAST EX</th>
												<th class="text-center">PO</th>
												<th class="text-center">ACTION</th>
											</tr>
										</thead>
										<tbody>
										    <?php
										    $sql = "SELECT * FROM accessory_table ORDER BY stock ASC";
										    $query_cat_list = $conn->query($sql);
										while ($rs_cat_list = $query_cat_list->fetch_assoc()) {
										    ?>
										    <tr <?php
										    if($rs_cat_list['balance']<=20){
										        echo "style='background-color:tomato;'";
										    }
										    ?>>
										        <td><?=ucfirst($rs_cat_list['product_name'])?><input type="hidden" value="<?=$rs_cat_list['product_name']?>" id="product_name_<?=$rs_cat_list['access_id']?>"></td>
										        <td><img style="height:100px;width:100px;border-radius: 0px 6px 6px 0px;" src="files/images/<?=$rs_cat_list['picture_name']?>"></td>
										        <td style="10px;"><span><?=ucfirst($rs_cat_list['size_colour'])?></span><input type="hidden" value="<?=$rs_cat_list['size_colour']?>" id="size_colour_<?=$rs_cat_list['access_id']?>"></td>
										        <td style="display:none;"><input style="width:50px;" type="number" access_id="<?=$rs_cat_list['access_id']?>" class="stock_changer" id="stock_<?=$rs_cat_list['access_id']?>" value="<?=$rs_cat_list['stock']?>" min="0"></td>
										        <td>
										           <?=$rs_cat_list['stock_used']?></td>
										        <td><input style="width:50px;" type="number" access_id="<?=$rs_cat_list['access_id']?>" class="stock_changer" value="0" id="stock_used_<?=$rs_cat_list['access_id']?>" min="0"></td>
										        <td><input style="width:100px;" type="number" access_id="<?=$rs_cat_list['access_id']?>" class="stock_changer" id="balance_<?=$rs_cat_list['access_id']?>" readonly value="<?php echo $rs_cat_list['balance']?>"></td>
										        <td style="width:50px;"><?=ucfirst($rs_cat_list['unit_type'])?><input type="hidden" value="<?=$rs_cat_list['unit_type']?>" id="unit_type_<?=$rs_cat_list['access_id']?>"></td>
										        <td><input style="width:85px;" type="text" id="last_ex_<?=$rs_cat_list['access_id']?>" value="<?=ucfirst($rs_cat_list['last_ex'])?>"></td>
										        <td><input style="width:100px;" type="text" id="po_number_<?=$rs_cat_list['access_id']?>" value="<?=ucfirst($rs_cat_list['po_number'])?>"></td>
										        <td>
										            <button class="btn btn-primary save_stock" access_id = "<?=$rs_cat_list['access_id']?>">Save</button><br><br>
										            <button class="btn btn-warning deleter" access_id="<?=$rs_cat_list['access_id']?>">Edit</button><br><br>
										            <a href="export_log.php?access_id=<?=$rs_cat_list['access_id']?>"><button class="btn btn-success">Usage Log</button>
										        </td>
										    </tr>
										    <?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	}

?>

<div class="modal fade" id="accessory_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Accessory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form id="access_form">
  <div class="form-group">
    <label for="inputAddress">Category Name</label>
    <select class="form-control" name="cat_name">
        <option value="" selected disabled>--Select Category--</option>
        <?php
        $cat_sql = "SELECT * FROM cat WHERE type_id='2' ORDER BY cat_code ASC";
        $cat_list = $conn->query($cat_sql);
		while ($main_cat_list = $cat_list->fetch_assoc()) {
        ?>
        <option value="<?=$main_cat_list['cat_id']?>"><?=$main_cat_list['cat_code']?></option>
        <?php
		}
        ?>
    </select>
  </div>
  <div class="form-group">
    <label for="inputAddress">Product Name</label>
    <input type="text" class="form-control" required id="inputAddress" name="product_name" placeholder="Enter Product Name...">
  </div>
  <div class="form-group">
    <label for="inputAddress2">Size/Colour</label>
    <input type="text" name="size_colour" required class="form-control" id="inputAddress2" placeholder="Input Size/Color Here...">
  </div>
    <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Stock</label>
      <input type="number" min="0" name="stock" required class="form-control" id="inputEmail4" placeholder="Enter Stock Here...">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Enter Unit Type Here</label>
      <input type="text" name="unit_type" required class="form-control" id="inputPassword4" placeholder="Enter Unit Type Here...">
    </div>
  </div>
<div class="form-row">
    <div class="form-group col-md-6">
      <label for="sup_name">Supplier Name</label>
        <select class="form-control" name="sup_name" id="sup_name">
            <option value="" selected disabled>--Select Supplier--</option>
        <?php
        $sup_sql = "SELECT * FROM supplier ORDER BY supplier_name ASC";
        $sup_list = $conn->query($sup_sql);
		while ($main_sup_list = $sup_list->fetch_assoc()) {
        ?>
        <option value="<?=$main_sup_list['supplier_id']?>" sup_code="<?=$main_sup_list['supplier_code']?>"><?=$main_sup_list['supplier_name']?></option>
        <?php
		}
        ?>
    </select>
    </div>
    <div class="form-group col-md-6">
      <label for="sup_code">Supplier Code</label>
      <input type="text" name="sup_code"  id="sup_code" readonly class="form-control" id="sup_code">
    </div>
 </div>
 <div class="form-row">
    <div class="form-group col-md-6">
      <label for="recv_date">Received Date</label>
      <input type="date" class="form-control" name="recv_date" id="recv_date">
    </div>
    <div class="form-group col-md-6">
      <label for="location">Location</label>
      <input type="text" name="location" required class="form-control" id="location" placeholder="Enter Location Here...">
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress5">PO</label>
    <input type="text" name="po_number" required class="form-control" id="inputAddress5" placeholder="Enter PO Here...">
  </div>
  <div class="form-group">
    <label for="inputAddress22">Add Picture</label>
    <input type="file" accept="image/*" required name="add_picture" class="form-control" id="inputAddress22" style="background-color: #f3f4fa;">
  </div>
  <button type="submit" id="sub_btn" class="btn btn-primary">Submit</button>
</form>
  <div class="progress" style="display: none;">
    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="accessory_modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Accessory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form id="access_form_edit">
  <div class="form-group">
    <label for="inputAddress">Category Name</label>
    <select class="form-control" name="cat_name" id="cat_name_edit">
        <option value="" selected disabled>--Select Category--</option>
        <?php
        $cat_sql = "SELECT * FROM cat WHERE type_id='2' ORDER BY cat_code ASC";
        $cat_list = $conn->query($cat_sql);
		while ($main_cat_list = $cat_list->fetch_assoc()) {
        ?>
        <option value="<?=$main_cat_list['cat_id']?>"><?=$main_cat_list['cat_code']?></option>
        <?php
		}
        ?>
    </select>
  </div>
  <div class="form-group">
    <label for="product_name_edit">Product Name</label>
    <input type="text" class="form-control" required id="product_name_edit" name="product_name" placeholder="Enter Product Name...">
    <input type="hidden" value="" id="access_id_edit" name="access_id">
  </div>
  <div class="form-group">
    <label for="size_colour_edit">Size/Colour</label>
    <input type="text" name="size_colour" required class="form-control" id="size_colour_edit" placeholder="Input Size/Color Here...">
  </div>
    <div class="form-row">
    <div class="form-group col-md-6">
      <label for="stock_edit">Stock</label>
      <input type="number" min="0" name="stock" required class="form-control" id="stock_edit" placeholder="Enter Stock Here...">
    </div>
    <div class="form-group col-md-6">
      <label for="unit_type_edit">Enter Unit Type Here</label>
      <input type="text" name="unit_type" required class="form-control" id="unit_type_edit" placeholder="Enter Unit Type Here...">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="sup_name">Supplier Name</label>
        <select class="form-control" name="sup_name" id="sup_name_edit">
            <option value="" disabled>--Select Supplier--</option>
        <?php
        $sup_sql = "SELECT * FROM supplier ORDER BY supplier_name ASC";
        $sup_list = $conn->query($sup_sql);
		while ($main_sup_list = $sup_list->fetch_assoc()) {
        ?>
        <option value="<?=$main_sup_list['supplier_id']?>" sup_code="<?=$main_sup_list['supplier_code']?>"><?=$main_sup_list['supplier_name']?></option>
        <?php
		}
        ?>
    </select>
    </div>
    <div class="form-group col-md-6">
      <label for="sup_code_edit">Supplier Code</label>
      <input type="text" name="sup_code"  id="sup_code_edit" readonly class="form-control">
    </div>
 </div>
 <div class="form-row">
    <div class="form-group col-md-6">
      <label for="recv_date_edit">Received Date</label>
      <input type="date" class="form-control" name="recv_date" id="recv_date_edit">
    </div>
    <div class="form-group col-md-6">
      <label for="location_edit">Location</label>
      <input type="text" name="location" required class="form-control" id="location_edit" placeholder="Enter Location Here...">
    </div>
  </div>
  <div class="form-group">
    <label for="po_number_edit">PO</label>
    <input type="text" name="po_number" required class="form-control" id="po_number_edit" placeholder="Enter PO Here...">
  </div>
  <div class="form-group">
    <img src="" id="picture_view" style="height:100px;width:100px;">
  </div>
  <div class="form-group">
    <label for="picture_edit">Update Picture</label>
    <input type="file" accept="image/*" name="add_picture" class="form-control" id="picture_edit" style="background-color: #f3f4fa;">
  </div>
  <button type="submit" id="sub_btn_edit" class="btn btn-primary">Update</button>
</form>
<div class="progress-edit" style="display: none;">
  <div class="progress-bar-edit" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// $(document).on('submit','#access_form',function(e){
//     e.preventDefault();
//     var form = $(this)[0];
//     var formData = new FormData(form);
//     $('#sub_btn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Submitting...').attr('disabled', true);

//     $.ajax({
//         type:'POST',
//         data:formData,
//         contentType:false,
//         processData:false,
//         url:'ajax/accessory/add_accessory.php',
//         success:function(response){
//             var response = JSON.parse(response);
//             if(response.status==1){
//                 swal("Success", "Accessory added successfully", "success");
//                 location.reload();
//             }
//             else{
//                 swal("Error", "Something Went Wrong", "error");
//             }
//         }
//     })
// })
$(document).on('submit', '#access_form', function (e) {
    e.preventDefault();
    var form = $(this)[0];
    var formData = new FormData(form);
    var progressBar = $('.progress');
    var progressBarValue = $('.progress-bar');

    $('#sub_btn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Submitting...').attr('disabled', true);

    $.ajax({
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        url: 'ajax/accessory/add_accessory.php',
        xhr: function () {
            var xhr = new XMLHttpRequest();

            // Progress event listener
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    progressBarValue.css('width', percent + '%');
                    progressBarValue.text(percent + '%');
                }
            }, false);

            return xhr;
        },
        success: function (response) {
            var response = JSON.parse(response);
            if (response.status == 1) {
                swal("Success", "Accessory added successfully", "success");
                location.reload();
            } else {
                swal("Error", "Something Went Wrong", "error");
            }
        },
        beforeSend: function () {
            progressBar.show();
        },
        complete: function () {
            progressBar.hide();
            $('#sub_btn').html('Submit').removeAttr('disabled');
        }
    });
});


// $(document).on('submit','#access_form_edit',function(e){
//     e.preventDefault();
//     var form = $(this)[0];
//     var formData = new FormData(form);
//     $('#sub_btn_edit').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Updating...').attr('disabled', true);

//     $.ajax({
//         type:'POST',
//         data:formData,
//         contentType:false,
//         processData:false,
//         url:'ajax/accessory/update_main.php',
//         success:function(response){
//             //console.log(response);
//             var response = JSON.parse(response);
//             if(response.status==1){
//                 swal("Success", "Accessory updated successfully", "success");
//                 location.reload();
//             }
//             else{
//                 swal("Error", "Something Went Wrong", "error");
//             }
//         }
//     })
// })
$(document).on('submit', '#access_form_edit', function (e) {
    e.preventDefault();
    var form = $(this)[0];
    var formData = new FormData(form);
    var progressBar = $('.progress-edit');
    var progressBarValue = $('.progress-bar-edit');

    $('#sub_btn_edit').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Updating...').attr('disabled', true);

    $.ajax({
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        url: 'ajax/accessory/update_main.php',
        xhr: function () {
            var xhr = new XMLHttpRequest();

            // Progress event listener
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    progressBarValue.css('width', percent + '%');
                    progressBarValue.text(percent + '%');
                }
            }, false);

            return xhr;
        },
        success: function (response) {
            var response = JSON.parse(response);
            if (response.status == 1) {
                swal("Success", "Accessory updated successfully", "success");
                //location.reload();
            } else {
                swal("Error", "Something Went Wrong", "error");
            }
        },
        beforeSend: function () {
            progressBar.show();
        },
        complete: function () {
            progressBar.hide();
            $('#sub_btn_edit').html('Update').removeAttr('disabled');
        }
    });
});


$(document).on('change','.stock_changer',function(){
    var access_id = $(this).attr('access_id');
    var stock = parseInt($('#stock_'+access_id).val());
    var stock_used = parseInt($('#stock_used_'+access_id).val());
    var balance = parseInt($('#balance_'+access_id).val());
    if(stock_used>stock){
        swal('Alert','Used can not be greater then stock','warning');
        location.reload();
    }
    else if(stock<stock_used){
        swal('Alert','Stock can not be less then Used','warning');
        location.reload();
    }
    else{
        var new_balance = stock - stock_used;
        //$('#stock_'+access_id).val(new_balance);
        $('#balance_'+access_id).val(new_balance);
    }
})

$(document).on('click','.save_stock',function(){
    var access_id = $(this).attr('access_id');
    var stock = parseInt($('#stock_'+access_id).val());
    var stock_used = parseInt($('#stock_used_'+access_id).val());
    var balance = parseInt($('#balance_'+access_id).val());
    var product_name = $('#product_name_'+access_id).val();
    var size_colour = $('#size_colour_'+access_id).val();
    var unit_type = $('#unit_type_'+access_id).val();
    var last_ex = $('#last_ex_'+access_id).val();
    var po_number = $('#po_number_'+access_id).val();
    if(confirm('Are you sure?')){
        $.ajax({
            type:'POST',
            url:'ajax/accessory/update_accessory.php',
            data:{
                access_id:access_id,
                stock:stock,
                stock_used:stock_used,
                balance:balance,
                product_name:product_name,
                size_colour:size_colour,
                unit_type:unit_type,
                last_ex:last_ex,
                po_number:po_number
            },
            success:function(response){
                var response = JSON.parse(response);
                if(response.status==1){
                    swal("Success", "Accessory updated successfully", "success");
                    location.reload();
                }
                else{
                    swal("Error", "Something Went Wrong", "error");
                }
            }
        })
    }
})

$(document).on('click','.deleter',function(){
    var access_id = $(this).attr('access_id');
    $.ajax({
        type:'POST',
        data:{
            access_id:access_id
        },
        url:'ajax/accessory/fetch_accessory.php',
        success:function(response){
            var response = JSON.parse(response);
            if(response.status==1){
                $('#cat_name_edit').val(response.data.main_cat_id);
                $('#sup_name_edit').val(response.data.sup_id);
                $('#recv_date_edit').val(response.data.recv_date);
                $('#location_edit').val(response.data.location);
                $('#sup_code_edit').val(response.data.sup_code);
                $('#access_id_edit').val(access_id);
                $('#product_name_edit').val(response.data.product_name);
                $('#size_colour_edit').val(response.data.size_colour);
                $('#stock_edit').val(response.data.stock);
                $('#unit_type_edit').val(response.data.unit_type);
                $('#po_number_edit').val(response.data.po_number);
                $("#picture_view").attr("src", "files/images/"+response.data.picture_name);
                $('#accessory_modal_edit').modal('show');
            }
            else{
                alert('Something Went Wrong');
            }
        }
    })
    //$('#accessory_modal_edit').modal('show');
    // if(confirm('Are you sure?')){
    //     $.ajax({
    //         type:'POST',
    //         data:{
    //             access_id:access_id
    //         },
    //         url:'ajax/accessory/delete_accessory.php',
    //         success:function(response){
    //             var response = JSON.parse(response);
    //             if(response.status==1){
    //                 swal("Success", "Accessory Deleted successfully", "success");
    //                 location.reload();
    //             }
    //             else{
    //                 swal("Error", "Something Went Wrong", "error");
    //             }
    //         }
    //     })
    // }
})

$(document).ready(function() {
  $('#sup_name').change(function() {
    var selectedOption = $(this).find(':selected');
    var supCode = selectedOption.attr('sup_code');
    $('#sup_code').val(supCode);
  });
  
    $('#sup_name_edit').change(function() {
    var selectedOption = $(this).find(':selected');
    var supCode = selectedOption.attr('sup_code');
    $('#sup_code_edit').val(supCode);
  });
});

</script>

	