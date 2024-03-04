<thead>
	<tr class="bg-dark text-white">
		<th >#</th>
		<th style="text-align: left;">Order Code</th>
		<th >RQ Date</th>
		<th >Finish</th>
		<th >Items</th>
		<th style="text-align: right;">Used(Kg)</th>
		<th style="text-align: right;">Total</th>
		<th >Status</th>
		<th >User</th>
		<th>Add-on</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php
require_once('../../db.php');
$code = $_POST['code'];
$records_per_page = 10;

if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}
$op ="finish";
// Calculate the starting point for the query
$start_from = ($page - 1) * $records_per_page;

$n_count_row = $start_from + 1;
// $sql_select = "SELECT tbl_rq_form.*,COUNT(*) AS item_num,employee.employee_name ";
// 	$sql_select .= " ,SUM((tbl_rq_form_item.balance_before-tbl_rq_form_item.balance_after)) AS g_used ";
// 	$sql_select .= " ,SUM((tbl_rq_form_item.balance_before-tbl_rq_form_item.balance_after)*fabric.fabric_in_price) AS g_total ";
// $sql_select .= " FROM tbl_rq_form LEFT JOIN tbl_rq_form_item ON tbl_rq_form.rq_id=tbl_rq_form_item.rq_id ";
// $sql_select .= " LEFT JOIN employee ON employee.employee_id=tbl_rq_form.employee_id ";
// 	$sql_select .= " LEFT JOIN fabric ON fabric.fabric_id=tbl_rq_form_item.fabric_id ";

// $sql_select .= " WHERE tbl_rq_form.enable=1 ";
// $sql_select .= " AND tbl_rq_form.rq_status='".$op."' AND tbl_rq_form.is_addon=0 ";
// $sql_select .= " GROUP BY tbl_rq_form.rq_id ORDER BY tbl_rq_form.finish_date DESC,tbl_rq_form.rq_date DESC";

// $sql_select .= " LIMIT $start_from, $records_per_page;";

$sql_select = "
    SELECT 
        tbl_rq_form.rq_id,
        tbl_rq_form.order_code,
        tbl_rq_form.rq_date,
        tbl_rq_form.finish_date,
        COUNT(tbl_rq_form_item.rq_id) AS item_num,
        employee.employee_name,
        SUM(tbl_rq_form_item.balance_before - tbl_rq_form_item.balance_after) AS g_used,
        SUM((tbl_rq_form_item.balance_before - tbl_rq_form_item.balance_after) * fabric.fabric_in_price) AS g_total,
        tbl_rq_form.is_addon,
        tbl_rq_form.rq_status
    FROM 
        tbl_rq_form
    LEFT JOIN 
        tbl_rq_form_item ON tbl_rq_form.rq_id = tbl_rq_form_item.rq_id
    LEFT JOIN 
        employee ON employee.employee_id = tbl_rq_form.employee_id
    LEFT JOIN 
        fabric ON fabric.fabric_id = tbl_rq_form_item.fabric_id
    WHERE 
        tbl_rq_form.enable = 1
        AND tbl_rq_form.rq_status = '$op'
        AND tbl_rq_form.is_addon = 0
        AND tbl_rq_form.order_code LIKE '%$code%'
    GROUP BY 
        tbl_rq_form.rq_id, tbl_rq_form.order_code, tbl_rq_form.rq_date, tbl_rq_form.finish_date, employee.employee_name, tbl_rq_form.is_addon, tbl_rq_form.rq_status
    ORDER BY 
        tbl_rq_form.finish_date DESC, tbl_rq_form.rq_date DESC
    LIMIT 
        $start_from, $records_per_page;
";


$a_rq = array();
// Execute the query and fetch data
$rs_select = $conn->query($sql_select);

	while ($row_select = $rs_select->fetch_assoc()) {

?>
<tr>
	<td ><?php echo $n_count_row; ?></td>
	<td style="text-align: left;" id="show_order_code<?php echo $row_select["rq_id"]; ?>">
		<?php 
		echo $row_select["order_code"]; 
		$a_rq[($row_select["rq_id"])]["order_code"] = $row_select["order_code"];
		if($row_select["is_addon"]=="1"){
			echo " (Add-on)";
			$a_rq[($row_select["rq_id"])]["order_code"] .= " (Add-on)";
		}

		$a_rq[($row_select["rq_id"])]["rq_date"] = $row_select["rq_date"];
		$a_rq[($row_select["rq_id"])]["rq_status"] = strtoupper($row_select["rq_status"]);
		?>
	</td>
	<td ><div id="show_rq_date<?php echo $row_select["rq_id"]; ?>" class="badge badge-primary"><?php echo $row_select["rq_date"]; ?></div></td>
	<td ><div id="show_finish_date<?php echo $row_select["rq_id"]; ?>" class="badge badge-primary"><?php echo $row_select["finish_date"]; ?></div></td>
	<td ><?php echo $row_select["item_num"]; ?></td>
	<td style="text-align: right;"><?php echo number_format($row_select["g_used"],2); ?></td>
	<td style="text-align: right;"><?php echo number_format($row_select["g_total"],2); ?></td>
	<td id="show_rq_status<?php echo $row_select["rq_id"]; ?>"><?php echo strtoupper($row_select["rq_status"]); ?></td>
	<td ><?php echo $row_select["employee_name"]; ?></td>
	<?php
		$sql_select2 = "SELECT COUNT(*) AS addon_num FROM tbl_rq_form WHERE order_code='".$row_select["order_code"]."' AND is_addon=1 AND rq_status='finish'; ";
		$rs_select2 = $conn->query($sql_select2);
		$row_select2 = $rs_select2->fetch_assoc();

	?>
	<td ><?php echo $row_select2["addon_num"]; ?></td>
	<td >
		<a class="btn btn-primary act-btn" data-toggle="modal" data-target="#editRQModal" onclick="editRQ(<?php echo $row_select["rq_id"]; ?>);">
		View
		<?php if( $row_select["rq_status"]=="new" || $row_select["rq_status"]=="update" ){ echo " | Edit"; }?>
		</a>
	<?php
	if( $row_select["rq_status"]=="new" || $row_select["rq_status"]=="update" ){
	?>
		<a class="btn btn-success act-btn" data-toggle="modal" data-target="#finishRQModal" onclick="finishRQ(<?php echo $row_select["rq_id"]; ?>);">Finish</a>
	<?php
		
	}
	?>
	</td>
</tr>
<?php
$n_count_row++;
}
?>

</tbody>
