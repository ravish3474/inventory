<?php
require_once('../../db.php');

$order_title = $_POST["order_title"];

$sql_select = "SELECT * FROM tbl_order_lkr WHERE order_title='".$order_title."' AND enable=1 ORDER BY file_name ASC";
$rs_select = $conn->query($sql_select);

$show_remove = false;
if( isset($_POST["show_remove"]) && ($_POST["show_remove"]=="yes") ){
	$show_remove = true;
}

$sql_title = "SELECT order_name,order_detail,folder_name FROM tbl_order_lkr_title WHERE order_title='".$order_title."' ";
$rs_title = $conn->query($sql_title);
$row_title = $rs_title->fetch_assoc();

if($row_title["order_name"]!=""){
?>
<h5>Order name: <?php echo $row_title["order_name"]; ?></h5>
<?php
}
if($row_title["order_detail"]!=""){
?>
<h5>Detail: <pre><?php echo $row_title["order_detail"]; ?></pre></h5>
<?php
}
?>
<input type="hidden" id="folder_name" value="<?php echo $_POST["folder_name"]; ?>">
<div class="row">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr class="bg-dark text-white">
					<th>Type</th>
					<th>Title</th>
					<th>Upload by</th>
					<th width="10%" class="text-center">File</th>
					<?php if($show_remove){ ?>
					<th width="10%" class="text-center">&nbsp;</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$tmp_file_name = "";
				$use_class = "primary";
				while($row_order = $rs_select->fetch_assoc()){
					if($tmp_file_name==$row_order['file_name']){
						$use_class = "warning";
					}else{
						$use_class = "primary";
					}

					
					$a_tmp_ext = explode(".", $row_order['file_name']);
					$last_index = sizeof($a_tmp_ext)-1;
					$tmp_ext = strtolower($a_tmp_ext[$last_index]);
					
				?>
				<tr class="table-<?php echo $use_class; ?>" id="tr_show_file<?php echo $row_order["order_lkr_id"]; ?>">
					<td class="text-center" id="td_file_type<?php echo $row_order["order_lkr_id"]; ?>"><?php echo $row_order["file_type"]; ?></td>
					<td id="td_id_<?php echo $row_order["order_lkr_id"]; ?>"><?php echo $row_order['file_name']; ?></td>
					<td><?php echo $row_order['user_add']; ?></td>
					<td>
						<?php if( $tmp_ext=="xls" || $tmp_ext=="xlsx" || $tmp_ext=="pdf" ){ ?>
						<a class="btn btn-primary text-white btn-block mb-2" onclick="showFile(<?php echo $row_order["order_lkr_id"]; ?>);"><i class="mdi mdi-eye text-light"></i> View</a>
						<?php }else{ 

								$other_path = "";
								if($row_order["file_type"]=="Other"){
									$other_path = "no_oform/";
								}

								if($_SERVER["SERVER_NAME"]=="localhost"){
									$root_folder = 'internal';
									$protocal = 'http';
								}else{
									$root_folder = 'lockerroom';
									$protocal = 'https';
								}

								$use_link = $protocal.'://'.$_SERVER["SERVER_NAME"].'/'.$root_folder.'/files/'.$other_path.$row_title["folder_name"].'/'.$row_order['file_name'];
						?>
						<a class="btn btn-primary text-white btn-block mb-2" href="<?php echo $use_link; ?>" target="_blank">Download</a>
						<?php } ?>
					</td>
					<?php if($show_remove){ ?>
					<td>
						<a class="btn btn-danger text-white btn-block mb-2" onclick="removeFile(<?php echo $row_order["order_lkr_id"]; ?>);"><i class="fa fa-times text-light"></i> Remove</a>
					</td>
					<?php } ?>
				</tr>
				<?php
					$tmp_file_name = $row_order['file_name'];
				}
				?>
			</tbody>
		</table>
	</div>
</div>