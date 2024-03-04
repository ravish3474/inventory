<?php
session_start();

if(!isset($_SESSION["employee_position_id"])){
  echo "<b><font color=red>Session expired please REFRESH page and try again.</font></b>";
  exit();
}

include('../../db.php');
$pac_id = $_POST['pac_id'];

$sql_pac = "SELECT tbl_packing.*,CONCAT('PAC-',RIGHT(CONCAT('00000',tbl_packing.pac_id),6)) AS pack_no,supplier.supplier_name FROM tbl_packing LEFT JOIN supplier ON tbl_packing.supplier_id = supplier.supplier_id WHERE tbl_packing.pac_id='".$pac_id."' ";
$rs_pac = $conn->query($sql_pac);
$row_pac = $rs_pac->fetch_assoc();
?>
<div class="row">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 text-center">
            <div class="form-group">
              Pack No. <b><?php echo $row_pac['pack_no'];?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              Create Date : <b><?php echo $row_pac['add_date'];?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              Supplier : 
              <b>
                <?php 
                echo $row_pac['supplier_name'];
                ;?>
              </b>
            </div>
          </div>

          <div class="col-md-4 text-center">
            <div class="form-group">
              PO No. <b><?php echo $row_pac['po_no'];?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              PO Date : <b><?php echo $row_pac['po_date'];?></b>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="form-group">
              INV No. <b><?php echo $row_pac['inv_no'];?></b>
              <?php
              $a_pos_allow = array(99,2);
              
              if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){
              ?>
              <span style="cursor:pointer; color: #00F; " onclick="return editINVNo(<?php echo $pac_id; ?>,'<?php echo $row_pac['inv_no']; ?>');">
                <i class="fa fa-pencil-square-o"></i>
              </span>
              <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <?php

    
    ?>  
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <table width="100%" class="tbl-input-board">
              <thead>
                <tr>
                  <th>Material</th><th>Color</th><th>Box</th><th>No.</th><th>Unit price<br>(THB)</th>
                  <?php
                  
                  if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){
                    echo '<th style="width:25px;"><div style="cursor:pointer; color: #00F;" onclick="return editUnitPrice('.$pac_id.');" data-toggle="modal" data-target="#editUnitPriceModal"><i class="fa fa-pencil-square-o"></i></div><hr style="margin:5px 2px;"><input type="checkbox" onclick="checkAllRows(this);"></th>';
                  }
                  ?>
                  <th>Amount<br>(Kg.)</th><th>Total<br>(THB)</th>
                  <?php
                  
                  if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){
                    echo '<th>Action</th>';
                  }
                  ?>
                </tr>
              </thead>
              <tbody id="in_board_body">
                <?php
                $sum_piece = 0.0;
                $sum_total = 0.0;

                $sql_list = "SELECT tbl_packing_list.*,cat.cat_name_en,fabric.fabric_color,fabric.fabric_box,fabric.fabric_no,fabric.fabric_in_piece,fabric.fabric_in_price,fabric.fabric_in_total,fabric.fabric_balance,fabric.on_producing FROM tbl_packing_list ";
                $sql_list .= " LEFT JOIN fabric ON tbl_packing_list.fabric_id=fabric.fabric_id ";
                $sql_list .= " LEFT JOIN cat ON cat.cat_id=fabric.cat_id ";
                $sql_list .= " WHERE pac_id='".$pac_id."'";
                $rs_list = $conn->query($sql_list);
                while ($row_list = $rs_list->fetch_assoc()) {

                  $sum_piece += $row_list["fabric_in_piece"];
                  $sum_total += $row_list["fabric_in_total"];
                ?>
                <tr>
                  <td class="text-center"><?php echo $row_list["cat_name_en"]; ?></td>
                  <td class="text-center"><?php echo $row_list["fabric_color"]; ?></td>
                  <td class="text-center"><?php echo $row_list["fabric_box"]; ?></td>
                  <td class="text-center"><?php echo $row_list["fabric_no"]; ?></td>
                  <td class="text-right"><?php echo number_format($row_list["fabric_in_price"],2); ?></td>
                  <?php
                  
                  if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){
                    echo '<td class="text-center"><input type="checkbox" class="e_fabric_id" value="'.$row_list["fabric_id"].'"></td>';
                  }
                  ?>
                  <td class="text-right"><?php echo number_format($row_list["fabric_in_piece"],2); ?></td>
                  <td class="text-right"><?php echo number_format($row_list["fabric_in_total"],2); ?></td>
                  <?php
                  $col_span = 5;
                  if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){

                    $col_span = 6;

                    echo '<th>';
                    if( ($row_list["fabric_in_piece"]==$row_list["fabric_balance"]) && ($row_list["on_producing"]=="0") ){
                      echo '<button class="btn btn-danger" type="button" onclick="return removeFabricRoll('.$row_list["pac_id"].','.$row_list["fabric_id"].');">Remove</button>';
                    }else{
                      echo '&nbsp;';
                    }
                    echo '</th>';
                  }
                  ?>
                </tr>
                <?php
                }
                ?>
                <tr class="total-row">
                  <td class="text-right" colspan="<?php echo $col_span; ?>">Total :</td>
                  <td class="text-right"><?php echo number_format($sum_piece,2); ?></td>
                  <td class="text-right"><?php echo number_format($sum_total,2); ?></td>
                  <?php
                  
                  if( in_array($_SESSION["employee_position_id"],$a_pos_allow) ){
                    echo '<th></th>';
                  }
                  ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php /*
<a href="?vp=<?php echo base64_encode('po_form').'&op='.base64_encode('po_edit').'&re_id='.base64_encode($re_id);?>" class="btn btn-danger btn-block">Edit PO</a>
  */
?>
