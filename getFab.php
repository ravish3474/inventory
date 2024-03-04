<?php
include('db.php');
include('function.php');

if(isset($_GET['op'])){$op=$_GET['op'];}

if($op=='add'){
  $q_fab = 'SELECT * FROM fabric WHERE cat_id="'.$_GET['cat_id'].'" AND fabric_color="'.$_GET['po_detail_color'].'" AND fabric_no="'.$_GET['po_detail_no'].'" AND fabric_balance>0 ';
  $query_fab = $conn->query($q_fab);
  $rs_fab = $query_fab->fetch_assoc();
  $code_count = $query_fab->num_rows;

  if($code_count==0){
    if($_GET['po_detail_no']==''){
      echo '<p class="text-danger">Please enter code</p>';
    }else{
      echo '<p class="text-success">Can use this code</p>';
    }
  }else if($code_count!=0){
    echo '<p class="text-danger">Already use this code</p>';
  }
}
if($op=='edit'){
  $q_fab = 'SELECT * FROM fabric WHERE cat_id="'.$_GET['cat_id'].'" AND fabric_color="'.$_GET['po_detail_color'].'" AND fabric_no="'.$_GET['po_detail_no'].'" AND fabric_balance>0 ';
  $query_fab = $conn->query($q_fab);
  $rs_fab = $query_fab->fetch_assoc();
  $code_count = $query_fab->num_rows;
  
  if($_GET['po_detail_no_old']==$_GET['po_detail_no']){
    echo '<p class="text-success">Same code</p>';
  }else{
    if($code_count==0){
      if($_GET['po_detail_no']==''){
        echo '<p class="text-danger">Please enter code</p>';
      }else{
        echo '<p class="text-success">Can use this code</p>';
      }
    }else if($code_count!=0){
      echo '<p class="text-danger">Already use this code</p>';
    }
  }
}
?>