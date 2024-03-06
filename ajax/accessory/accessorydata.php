<?php

require_once('../../db.php');

$start = isset($_POST['start']) ? $_POST['start'] : 0; 
$length = isset($_POST['length']) ? $_POST['length'] : 10; 
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : ''; 
$order_column_index = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; 
$order_direction = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; 

$sql = "SELECT * FROM accessory_table";

// Get total number of records (for pagination)
$total_records_query = $conn->query("SELECT COUNT(*) FROM accessory_table");
$total_records = $total_records_query->fetch_row()[0];

if (!empty($search)) {
    $sql .= " WHERE product_name LIKE '%$search%'";
}

// Construct the SQL query for counting total records
$total_records_query = $conn->query("SELECT COUNT(*) FROM accessory_table" . ($search ? " WHERE product_name LIKE '%$search%'" : ''));

if ($total_records_query) {
    // Check if there are rows in the result set
    if ($total_records_query->num_rows > 0) {
        $total_records_row = $total_records_query->fetch_assoc();
        $total_records = $total_records_row['COUNT(*)'];
    } else {
        $total_records = 0; // Set total records to 0 if no matching rows found
    }
} else {
    die('Error executing query: ' . $conn->error); // Handle query execution error
}



// Apply ordering and limit 
$sql .= " ORDER BY product_name $order_direction LIMIT $start, $length";

$result = $conn->query($sql);

// Prepare data array
$data = array();
while ($row = $result->fetch_assoc()) {
    // Create HTML for input fields and images
    $inputField = "<input type='text' value='" . $row['last_ex'] . "' id='last_ex_" . $row['access_id'] . "'>";
    $image = "<img style='height:100px;width:100px;border-radius:0px 6px 6px 0px;' src='files/images/" . $row['picture_name'] . "'>";

    // Create a new array with the HTML and other column values
    $rowData = array(
        ucfirst($row['product_name']),
        $image,
        "<span>" . ucfirst($row['size_colour']) . "</span>",
        "<input type='hidden' style='display:none;' value='" . $row['stock'] . "' id='stock_" . $row['access_id'] . "'>",
        $row['stock_used'],
        "<input style='width:50px;' type='number' access_id='" . $row['access_id'] . "' class='stock_changer' value='0' id='stock_used_" . $row['access_id'] . "' min='0'>",
        "<input style='width:100px;' type='number' access_id='" . $row['access_id'] . "' class='stock_changer' id='balance_" . $row['access_id'] . "' readonly value='" . $row['balance'] . "'>",
        ucfirst($row['unit_type']),
        $inputField,
        "<input style='width:100px;' type='text' id='po_number_" . $row['access_id'] . "' value='" . ucfirst($row['po_number']) . "'>",
        "<button class='btn btn-primary save_stock' access_id='" . $row['access_id'] . "'>Save</button><br><br><button class='btn btn-warning deleter' access_id='" . $row['access_id'] . "'>Edit</button><br><br><a href='export_log.php?access_id=" . $row['access_id'] . "'><button class='btn btn-success'>Usage Log</button>"
    );
    $data[] = $rowData;
}

// Construct JSON response

    # code...
    $response = array(
        "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
        "recordsTotal" => $total_records,
        "recordsFiltered" => $total_records,
        "data" => $data
    );


echo json_encode($response);

$conn->close();
?>
