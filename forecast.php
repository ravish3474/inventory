<?php
if(isset($_GET['op'])){$op=base64_decode($_GET['op']);}else{$op='';}
?>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{
	width: 50px;
}
#pagination {
  text-align: center;
  margin-top: 20px;
}

.pagination {
  list-style: none;
  display: inline-block;
  margin: 0;
  padding: 0;
}

.page-item {
  display: inline-block;
  margin: 0 4px;
}

.pagination-link {
  display: block;
  padding: 8px 12px;
  text-decoration: none;
  color: #333;
  background-color: #eee;
  border: 1px solid #ccc;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.pagination-link-search {
  display: block;
  padding: 8px 12px;
  text-decoration: none;
  color: #333;
  background-color: #eee;
  border: 1px solid #ccc;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.pagination .active a {
    background-color: #007bff;
    color: #fff;
}


.pagination-link:hover {
  background-color: #ddd;
}

.pagination-link-search:hover {
  background-color: #ddd;
}
</style>
<h4>Forecast list</h4>
<div class="row">
	<div class="col-10">
		<div class="card">
			<div class="card-body">
			    <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="input-group">
                            <input type="text" class="form-control border-dark" placeholder="Search..." id="search_jog">
                            <div class="input-group-append">
                                <span class="input-group-text bg-dark border-dark">
                                    <i class="fa fa-search text-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-12">
						<div class="table-responsive">
							<table id="order-listing-main" class="table">

							</table>
							<div id="pagination">
    <?php
    $records_per_page = 10;
    $current_page = isset($_POST['page']) ? $_POST['page'] : 1;

    $sql_select_all = "SELECT COUNT(*) AS row_count
FROM forecast_detail
LEFT JOIN forecast_head ON forecast_head.forecast_id = forecast_detail.forecast_id";
    $rs_select_all = $conn->query($sql_select_all);
    $row_select_all = $rs_select_all->fetch_assoc();
    $total_records = $row_select_all["row_count"];
    $total_pages = ceil($total_records / $records_per_page);

    $num_links = 5; // Number of pagination links to display around the current page

    echo '<ul class="pagination">';

    // Display link to first page
    if ($current_page > 1) {
        echo '<li class="page-item"><a href="#" class="pagination-link" data-page="1">First</a></li>';
    }

    // Display links around the current page
    for ($i = max(1, $current_page - $num_links); $i <= min($total_pages, $current_page + $num_links); $i++) {
        $active_class = ($i == $current_page) ? 'active' : '';
        echo '<li class="page-item ' . $active_class . '"><a href="#" class="pagination-link" data-page="' . $i . '">' . $i . '</a></li>';
    }

    // Display link to last page
    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a href="#" class="pagination-link" data-page="' . $total_pages . '">Last</a></li>';
    }

    echo '</ul>';
    ?>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-2">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<h4 class="card-title">Forecast latest add</h4>
				</div>
				<div class="row">
					<table class="table table-hover">
						<tbody>
							<?php
							$sql_head = 'SELECT * FROM forecast_head ORDER BY forecast_update DESC LIMIT 0,10; ';
							$query_head = $conn->query($sql_head);
							while ($rs_head = $query_head->fetch_assoc()) {
							?>
							<tr>
								<td>
									<a href="?vp=<?php echo base64_encode('forecast_view_edit').'&forecast_id='.base64_encode($rs_head['forecast_id']);?>">
										<?php 
										if($rs_head['forecast_order']!=''){
											echo $rs_head['forecast_order'];
										}else{
											echo $rs_head['forecast_code'];
										}
										?>
									</a>
								</td>
							</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function loadData(page) {
    $.ajax({
        url: 'ajax/forecast/forecast_ajax.php',
        type: 'POST',
        data: {page: page},
        success: function(data) {
            $('#order-listing-main').html(data);
            // Update pagination links based on the response
            updatePaginationLinks(page);
        }
    });
}

// Initial load
loadData(1);

// Function to handle pagination click
$(document).on('click', '.pagination-link', function(){
    var page = $(this).data('page');
    loadData(page);
    // Optionally, you can prevent the default link behavior
    return false;
});

// Function to update pagination links
function updatePaginationLinks(currentPage) {
    $.ajax({
        url: 'ajax/forecast/pagination.php', // Replace with the URL to your pagination PHP file
        type: 'POST',
        data: {page: currentPage},
        success: function(data) {
            $('#pagination').html(data);
        }
    });
}

// Function to handle pagination click
$(document).on('click', '.pagination-link-search', function(){
    var page = $(this).data('page');
    var code = $('#search_jog').val();
    $.ajax({
            type:'POST',
            data:{
                code:code,
                page: page
            },
            url:'ajax/forecast/search.php',
            success:function(response){
                $('#order-listing-main').html(response);
                updatePaginationLinksSearch(page,code);
            }
    })
    // Optionally, you can prevent the default link behavior
    return false;
});

function updatePaginationLinksSearch(currentPage,code){
    $.ajax({
        url: 'ajax/forecast/paginationSearch.php', // Replace with the URL to your pagination PHP file
        type: 'POST',
        data: {
            page: currentPage,
            code: code
        },
        success: function(data) {
            $('#pagination').html(data);
        }
    });
}

$(document).on('keyup','#search_jog',function(){
    var code = $(this).val();
    if(code=="" || code.length==0){
        loadData(1);
    }
    else{
        $.ajax({
            type:'POST',
            data:{
                code:code
            },
            url:'ajax/forecast/search.php',
            success:function(response){
                $('#order-listing-main').html(response);
                updatePaginationLinksSearch(1,code);
            }
        })
    }
})

</script>