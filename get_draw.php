<?php
include('db.php');
include('function.php');
$used_id = $_POST['used_id'];

$q_used = 'SELECT * FROM used_head WHERE used_id="'.$used_id.'" ';
$query_used = $conn->query($q_used);
$rs_used = $query_used->fetch_assoc();
?>
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-2">
        <b>Order NO.</b><br>
        <span class="text-danger"><?php echo $rs_used['used_order_code'];?></span><br>
        <b>Date</b><br>
        <span class="text-danger">
          <?php 
          echo '<div class="badge badge-outline-primary">'.Ndate($rs_used['used_date']).'</div>';
          $exd = explode(' ', $rs_used['used_date']);
          echo '<br><div class="badge badge-outline-danger mt-1">'.$exd[1].'</div>';
          ?>
        </span><br>
        <b>TOTAL</b><br>
        <span class="text-danger"><?php echo number_format($rs_used['used_total'],2);?></span><br>
      </div>
      <div class="col-md-10">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr class="bg-dark text-white text-center">
                <th width="120">Type</th>
                <th width="150">Product</th>
                <th width="100">Color</th>
                <th>No/Size</th>
                <th>Used</th>
                <th>Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql_used_de = 'SELECT * FROM used_detail where used_id="'.$used_id.'"';
                $query_used_de = $conn->query($sql_used_de);
                while ($rs_used_de = $query_used_de->fetch_assoc()) {
              ?>
              <tr class="text-center">
                <td>
                  <?php
                  switch ($rs_used_de['type_id']) {
                    case 1:
                      $t_name='Fabrics';
                      break;
                    case 2:
                      $t_name='Accessory';
                      break;
                  }
                  echo $t_name;
                  ?>
                </td>
                <td>
                  <?php 
                  $sql_cat = 'SELECT * FROM cat where cat_id="'.$rs_used_de['cat_id'].'"';
                  $query_cat = $conn->query($sql_cat);
                  $rs_cat = $query_cat->fetch_assoc();
                  echo $rs_cat['cat_name_en'];
                  ?>
                </td>
                <td><?php echo $rs_used_de['used_detail_color'];?></td>
                <td>
                  <?php 
                    if($rs_used_de['type_id']==1){
                      echo $rs_used_de['used_detail_no'];
                    }else{
                      echo $rs_used_de['used_detail_size'];
                    }
                  ?>
                </td>
                <td><?php echo $rs_used_de['used_detail_used'];?></td>
                <td><?php echo $rs_used_de['used_detail_price'];?></td>
                <td><?php echo $rs_used_de['used_detail_total'];?></td>
              </tr>
            <?php 
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>