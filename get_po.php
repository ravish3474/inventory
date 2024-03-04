<?php
include('db.php');
include('function.php');
$po_id = $_POST['po_id'];

$q_po = 'SELECT * FROM po_head INNER JOIN supplier ON po_head.supplier_id = supplier.supplier_id WHERE po_head.po_id="'.$po_id.'"';
$query_po = $conn->query($q_po);
$rs_po = $query_po->fetch_assoc();
?>
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-2">
        <b>PO NO.</b><br>
        <span class="text-danger"><?php echo $rs_po['po_no'];?></span><br>
        <b>Supplier</b><br>
        <span class="text-danger"><?php echo $rs_po['supplier_name'];?></span><br>
        <b>PO Date</b><br>
        <span class="text-danger"><?php echo Endate($rs_po['po_date']);?></span><br>
        <b>TOTAL</b><br>
        <span class="text-danger"><?php echo number_format($rs_po['po_total'],2);?></span><br>
      </div>
      <div class="col-md-10">
        <table class="table table-hover">
          <thead>
            <tr class="bg-dark text-white text-center">
              <th width="120">Type</th>
              <th width="150">Product</th>
              <th width="100">Color</th>
              <th>No/Size</th>
              <th>Piece</th>
              <th>Type</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql_po_de = 'SELECT * FROM po_detail where po_id="'.$po_id.'"';
              $query_po_de = $conn->query($sql_po_de);
              while ($rs_po_de = $query_po_de->fetch_assoc()) {
            ?>
            <tr class="text-center">
              <td>
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
              <td>
                <?php 
                $sql_cat = 'SELECT * FROM cat where cat_id="'.$rs_po_de['cat_id'].'"';
                $query_cat = $conn->query($sql_cat);
                $rs_cat = $query_cat->fetch_assoc();
                echo $rs_cat['cat_name_en'];
                ?>
              </td>
              <td><?php echo $rs_po_de['po_detail_color'];?></td>
              <td>
                  <?php 
                  if($rs_po_de['po_type_id']==1){
                    echo $rs_po_de['po_detail_no'];
                  }else if($rs_po_de['po_type_id']==2){
                    echo $rs_po_de['po_detail_size'];
                  }else{
                    echo '-';
                  }
                  ?>
              </td>
              <td><?php echo $rs_po_de['po_detail_piece'];?></td>
              <td><?php echo unitType($rs_po_de['po_detail_type_unit']);?></td>
              <td><?php echo number_format($rs_po_de['po_detail_price'],2);?></td>
              <td><?php echo number_format($rs_po_de['po_detail_total'],2);?></td>
            </tr>
          <?php 
          }
          ?>
          </tbody>
        </table>
        <a href="?vp=<?php echo base64_encode('po_form').'&op='.base64_encode('po_edit').'&po_id='.base64_encode($po_id);?>" class="btn btn-danger btn-block">Edit PO</a>
      </div>
    </div>
  </div>
</div>