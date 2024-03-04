<?php
require_once('../../db.php');
$code = $_POST['code'];
$op="finish";
$records_per_page = 10;
$current_page = isset($_POST['page']) ? $_POST['page'] : 1;

$sql_select_all = "SELECT COUNT(*) AS total_records FROM tbl_rq_form WHERE enable=1 AND rq_status='$op' AND order_code LIKE '%$code%' AND is_addon=0;";
$rs_select_all = $conn->query($sql_select_all);
$row_select_all = $rs_select_all->fetch_assoc();
$total_records = $row_select_all["total_records"];
$total_pages = ceil($total_records / $records_per_page);

$num_links = 5; // Number of pagination links to display around the current page

echo '<ul class="pagination">';

// Display link to first page
if ($current_page > 1) {
    echo '<li class="page-item"><a href="#" class="pagination-link-search" data-page="1">First</a></li>';
}

// Display links around the current page
for ($i = max(1, $current_page - $num_links); $i <= min($total_pages, $current_page + $num_links); $i++) {
    $active_class = ($i == $current_page) ? 'active' : '';
    echo '<li class="page-item ' . $active_class . '"><a href="#" class="pagination-link-search" data-page="' . $i . '">' . $i . '</a></li>';
}

// Display link to last page
if ($current_page < $total_pages) {
    echo '<li class="page-item"><a href="#" class="pagination-link-search" data-page="' . $total_pages . '">Last</a></li>';
}

echo '</ul>';
?>
