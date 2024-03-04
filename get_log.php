<?php
include('db.php');

if(isset($_GET['ac'])){$ac=$_GET['ac'];}

if($ac=='log'){
  $po_id = $_POST['po_id'];
?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <th>Action</th>
        <th>Type</th>
        <th>Product</th>
        <th>Color</th>
        <th>No/Size</th>
        <th>Box</th>
        <th>Amount</th>
        <th>Unit</th>
        <th>Price</th>
        <th>Total</th>
        <th>Date</th>
        <th>User</th>
      </thead>
      <tbody>
      <?php
      $q_po_log = 'SELECT * FROM po_log WHERE po_id="'.$po_id.'" ';
      $query_po_log = $conn->query($q_po_log);
      while ($rs_po_log = $query_po_log->fetch_assoc()) {
        $q_cat = 'SELECT * FROM cat WHERE cat_id="'.$rs_po_log['cat_id'].'" ';
        $query_cat = $conn->query($q_cat);
        $rs_cat = $query_cat->fetch_assoc();
      ?>
        <tr>
          <td><?php echo $rs_po_log['po_log_action'];?></td>
          <td><?php if($rs_po_log['po_type_id']==1){echo 'Fabrics';}else if($rs_po_log['po_type_id']==2){echo 'Accessory';}?></td>
          <td><?php echo $rs_cat['cat_code'];?></td>
          <td><?php echo $rs_po_log['po_log_color'];?></td>
          <td><?php if($rs_po_log['po_log_size']!=''){echo $rs_po_log['po_log_size'];}else{echo $rs_po_log['po_log_no'];}?></td>
          <td><?php echo $rs_po_log['po_log_box'];?></td>
          <td><?php echo $rs_po_log['po_log_piece'];?></td>
          <td>
            <?php
            if($rs_po_log['po_log_type_unit']==1){
              echo 'Piece';
            }else if($rs_po_log['po_log_type_unit']==2){
              echo 'Yard';
            }else if($rs_po_log['po_log_type_unit']==3){
              echo 'KG';
            }
            ?>
          </td>
          <td><?php echo $rs_po_log['po_log_price'];?></td>
          <td><?php echo $rs_po_log['po_log_total'];?></td>
          <td><?php echo $rs_po_log['po_log_date'];?></td>
          <td><?php echo $rs_po_log['po_log_user'];?></td>
        </tr>
      <?php
      }
      ?>
      </tbody>
    </table>
  </div>
<?php
}

if($ac=='fab'){
  $fab_id = $_POST['fab_id'];
?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <th>Action</th>
        <th>Type</th>
        <th>Product</th>
        <th>Color</th>
        <th>No/Size</th>
        <th>Box</th>
        <th>Amount</th>
        <th>Unit</th>
        <th>Price</th>
        <th>Total</th>
        <th>Date</th>
        <th>User</th>
      </thead>
      <tbody>
      <?php

      $q_po_log = 'SELECT * FROM po_log WHERE materials_id="'.$fab_id.'" AND po_type_id="1" ';
      $query_po_log = $conn->query($q_po_log);
      while ($rs_po_log = $query_po_log->fetch_assoc()) {
        $q_cat = 'SELECT * FROM cat WHERE cat_id="'.$rs_po_log['cat_id'].'" ';
        $query_cat = $conn->query($q_cat);
        $rs_cat = $query_cat->fetch_assoc();
      ?>
        <tr>
          <td><?php echo $rs_po_log['po_log_action'];?></td>
          <td><?php if($rs_po_log['po_type_id']==1){echo 'Fabrics';}else if($rs_po_log['po_type_id']==2){echo 'Accessory';}?></td>
          <td><?php echo $rs_cat['cat_code'];?></td>
          <td><?php echo $rs_po_log['po_log_color'];?></td>
          <td><?php if($rs_po_log['po_log_size']!=''){echo $rs_po_log['po_log_size'];}else{echo $rs_po_log['po_log_no'];}?></td>
          <td><?php echo $rs_po_log['po_log_box'];?></td>
          <td><?php echo $rs_po_log['po_log_piece'];?></td>
          <td>
            <?php
            if($rs_po_log['po_log_type_unit']==1){
              echo 'Piece';
            }else if($rs_po_log['po_log_type_unit']==2){
              echo 'Yard';
            }else if($rs_po_log['po_log_type_unit']==3){
              echo 'KG';
            }
            ?>
          </td>
          <td><?php echo $rs_po_log['po_log_price'];?></td>
          <td><?php echo $rs_po_log['po_log_total'];?></td>
          <td><?php echo $rs_po_log['po_log_date'];?></td>
          <td><?php echo $rs_po_log['po_log_user'];?></td>
        </tr>
      <?php
      }
      ?>
      </tbody>
    </table>
  </div>
<?php
}
?>