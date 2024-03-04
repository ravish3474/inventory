<?php

include('db.php');
include('function.php');
$re_id = $_POST['re_id'];

$q_re = 'SELECT * FROM receipt INNER JOIN supplier ON receipt.receipt_supplier = supplier.supplier_id WHERE receipt.receipt_id="'.$re_id.'"';
$query_re = $conn->query($q_re);
$rs_re = $query_re->fetch_assoc();
?>
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 text-center">
            <div class="form-group">
              Received No : <b><?php echo $rs_re['receipt_number'];?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              Received Date : <b><?php echo Ndate($rs_re['receipt_date']);?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              Supplier : 
              <b>
                <?php 
                $sql_sup = 'SELECT supplier_name FROM supplier where supplier_id="'.$rs_re['receipt_supplier'].'"';
                $query_sup = $conn->query($sql_sup);
                $rs_sup = $query_sup->fetch_assoc();
                echo $rs_sup['supplier_name'];
                ;?>
              </b>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <?php
    $i=1;
    $sql_po = 'SELECT * FROM po_head where receipt_id="'.$re_id.'"';
    $query_po = $conn->query($sql_po);
    while ($rs_po = $query_po->fetch_assoc()) {
    ?>  
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
                <label class="col-form-label">PO NO. : <b><?php echo $rs_po['po_no'];?></b></label>
              </div>
              <div class="form-group">
                <label class="col-form-label">PO date : <b><?php echo Ndate($rs_po['po_date']);?></b></label>
              </div>
              <div class="form-group">
                <label class="col-form-label">Total : 
                <b>
                  <?php 
                  $sql_sum_po = 'SELECT sum(po_detail_total) AS sum_po FROM po_detail where po_id="'.$rs_po['po_id'].'"';
                  $query_sum_po = $conn->query($sql_sum_po);
                  $rs_sum_po = $query_sum_po->fetch_assoc();

                  if($rs_sum_po['sum_po']!=$rs_po['po_total']){
                    $sql_up = 'UPDATE po_head SET po_total = "'.$rs_sum_po['sum_po'].'" WHERE po_id = "'.$rs_po['po_id'].'" ';
                    $query = mysqli_query($conn,$sql_up);
                  }
                  echo number_format($rs_sum_po['sum_po'],2);
                  ?>
                </b></label>
              </div>
          </div>
          <div class="col-md-10">
            <div class="table-responsive">
              <table width="100%" class="table-bordered">
                <thead>
                  <tr class="bg-dark text-white text-center">
                    <th width="120">Type</th>
                    <th width="150">Product</th>
                    <th width="100">Color</th>
                    <th>No/Size</th>
                    <th>Box</th>
                    <th>Amount</th>
                    <th>Type<br><small>Unit</small></th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody id="po_detail<?php echo $i;?>">
                  <?php
                  $sql_po_de = 'SELECT * FROM po_detail where po_id="'.$rs_po['po_id'].'" ';
                  $query_po_de = $conn->query($sql_po_de);
                  while ($rs_po_de = $query_po_de->fetch_assoc()) {
                  ?>
                  <tr class="text-right">
                    <td class="text-center">
                      <?php
                      switch ($rs_po_de['po_type_id']) {
                        case 1:
                          $t_name='Fabrics';
                          break;
                        case 2:
                          $t_name='Accessory';
                          break;
                        case 0:
                          $t_name='No type';
                          break;
                      }
                      echo $t_name;
                      ?>
                    </td>
                    <td class="text-center">
                      <?php 
                      $sql_cat = 'SELECT * FROM cat where cat_id="'.$rs_po_de['cat_id'].'"';
                      $query_cat = $conn->query($sql_cat);
                      $rs_cat = $query_cat->fetch_assoc();
                      echo $rs_cat['cat_name_en'];
                      ?>
                    </td>
                    <td class="text-center">
                      <?php echo $rs_po_de['po_detail_color'];?>
                    </td>
                    <td class="text-center">
                      <?php 
                      
                      switch ($rs_po_de['po_type_id']) {
                        case 1:
                          $numb=$rs_po_de['po_detail_no'];
                          break;
                        case 2:
                          $numb=$rs_po_de['po_detail_size'];
                          break;
                        case 0:
                          $numb='-';
                          break;
                      }
                      echo $numb;
                      ?>
                    </td>
                    <td class="text-center">
                      <?php echo $rs_po_de['po_detail_box'];?>
                    </td>
                    <td class="text-center">
                      <?php echo $rs_po_de['po_detail_piece'];?>
                    </td>
                    <td class="text-center">
                      <?php
                      switch ($rs_po_de['po_detail_type_unit']) {
                        case 1:
                          $tu_name='Piece';
                          break;
                        case 2:
                          $tu_name='Yard';
                          break;
                        case 3:
                          $tu_name='KG';
                          break;
                      }
                      echo $tu_name;
                      ?>
                    </td>
                    <td>
                      <?php echo number_format($rs_po_de['po_detail_price'],2).'&nbsp;&nbsp;';?>
                    </td>
                    <td>
                      <?php echo number_format($rs_po_de['po_detail_total'],2).'&nbsp;&nbsp;';?>
                    </td>
                  </tr>
                  <?php 
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <hr>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <?php $i++;}?>
  </div>
</div>
<?php /*
<a href="?vp=<?php echo base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($re_id);?>" class="btn btn-danger btn-block">Edit PO</a>
  */
?>
