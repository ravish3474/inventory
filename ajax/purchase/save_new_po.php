<?php
session_start();
/*
Array
(
	[new_po_h_id] => 1,G2W
    [new_po_type] => A

    [chk_lang] => TH
    [chk_include_vat] => yes

    [show_supplier_new] => wehss
    [h_supplier_id_new] => 32
    [new_sup_address] => 1922 Charoen Krung Road,Wat Prayakrai 
ssss

98666666
    [new_sup_tax_id] => 333333333ss
    [new_sup_email] => aaaass
    [new_po_date] => 2019-09-04
    [new_po_number] => 
    [new_sale_name] => sssss999
    [new_sup_tel] => 01111111111ss
    [new_sup_payment] => 47
    [new_delivery_date] => 
    [new_po_code] => Array
        (
            [0] => QqqQQQ
            [1] => 
            [2] => TTTrrt
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_item] => Array
        (
            [0] => Uno Switch
            [1] => 
            [2] => aaa
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [h_item_id_new_po] => Array
        (
            [0] => 
            [1] => 
            [2] => 
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_color] => Array
        (
            [0] => POWDER BLUE
            [1] => 
            [2] => KELLY GREEN
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [h_color_id_new_po] => Array
        (
            [0] => 
            [1] => 
            [2] => 
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_qty] => Array
        (
            [0] => 50
            [1] => 
            [2] => 41
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_ppu] => Array
        (
            [0] => 77.00
            [1] => 
            [2] => 6.00
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_amount] => Array
        (
            [0] => 3850.00
            [1] => 
            [2] => 246.00
            [3] => 
            [4] => 
            [5] => 
            [6] => 
            [7] => 
        )

    [new_po_total_qty] => 91.00
    [new_po_note] => Teesttt 
ทดสอบ
    [new_po_sub_total] => 4096.00
    [new_po_vat_value] => 286.72
    [new_po_net_total] => 4382.72
)

po_con_id	po_h_id	comp_name	comp_address	comp_tel	comp_fax	website	comp_tax_id	comp_logo	po_sup_id	nationality	sup_name	sup_address	sup_tel	sup_fax	sup_email	sale_name	sup_tax_id	sup_payment	delivery_date	po_number	po_date	prepare_emp_id	prepare_emp_name	convert_to_word	inc_vat	vat_percent	note	enable	date_add
*/
require_once('../../db.php');
require_once('../../function.php');

$a_comp_data = json_decode(base64_decode($_POST["hidden_comp_data"]));


$pre_code = $a_comp_data->pre_code;
$po_type = $_POST['new_po_type'];//----A = Accessory, F = Fabric

$sql_po_number = "SELECT po_num_id,po_number FROM tbl_po_number WHERE pre_code='".$pre_code."' AND po_type='".$po_type."' AND po_year='".date("Y")."';" ;
$rs_po_number = $conn->query($sql_po_number);
$no_need_update = false;

if($rs_po_number->num_rows>0){
    $row_po_number = $rs_po_number->fetch_assoc();

    $tmp_po_number = "00".$row_po_number["po_number"];
    $tmp_po_number = substr($tmp_po_number,(strlen($tmp_po_number)-3),3);

    $new_po_number = $pre_code."-".$po_type.date("ym").$tmp_po_number;
}else{

    $new_po_number = $pre_code."-".$po_type.date("ym")."001";
    $sql_insert_new_year = "INSERT INTO tbl_po_number (pre_code,po_type,po_year,po_number,lastest_no) VALUES ('".$pre_code."','".$po_type."','".date("Y")."','2','".$new_po_number."');" ;
    $conn->query($sql_insert_new_year);
    $no_need_update = true;
}

$po_sup_id = $_POST['h_supplier_id_new'];

$convert_to_word = "";
$nationality = "";
if( isset($_POST["chk_lang"]) && ($_POST["chk_lang"]=="TH") ){
    $convert_to_word = number_to_string($_POST["new_po_net_total"]);
    $nationality = "TH";
}else{
    $nationality = "NOT_TH";
}

$inc_vat = "";
$vat_percent = 0.0;
if(isset($_POST["chk_include_vat"])){
    $inc_vat = $_POST["chk_include_vat"];
    $vat_percent = 7.0;
}


$a_data = array(
	"po_h_id" => $conn->real_escape_string($a_comp_data->po_h_id),
	"comp_name" => $conn->real_escape_string($a_comp_data->comp_name),
    "comp_address" => $conn->real_escape_string($a_comp_data->comp_address),
    "comp_tel" => $conn->real_escape_string($a_comp_data->comp_tel),
    "comp_fax" => $conn->real_escape_string($a_comp_data->comp_fax),
    "website" => $conn->real_escape_string($a_comp_data->website),
    "comp_tax_id" => $conn->real_escape_string($a_comp_data->comp_tax_id),
    "comp_logo" => $conn->real_escape_string($a_comp_data->comp_logo),
    "po_sup_id" => $conn->real_escape_string($po_sup_id),
    "nationality" => $nationality,
    "sup_name" => $conn->real_escape_string($_POST["show_supplier_new"]),
    "sup_address" => $conn->real_escape_string($_POST["new_sup_address"]),
    "sup_tel" => $conn->real_escape_string($_POST["new_sup_tel"]),
    "sup_fax" => $conn->real_escape_string($_POST["new_sup_fax"]),
    "sup_email" => $conn->real_escape_string($_POST["new_sup_email"]),
    "sale_name" => $conn->real_escape_string($_POST["new_sale_name"]),
    "sup_tax_id" => $conn->real_escape_string($_POST["new_sup_tax_id"]),
    "sup_payment" => $conn->real_escape_string($_POST["new_sup_payment"]),
    "delivery_date" => $conn->real_escape_string($_POST["new_delivery_date"]),
    "po_number" => $new_po_number,
    "po_date" => $conn->real_escape_string($_POST["new_po_date"]),
    "prepare_emp_id" => $conn->real_escape_string(intval($_SESSION["employee_id"])),
    "prepare_emp_name" => $conn->real_escape_string($_SESSION["employee_name"]),
    "net_total" => $conn->real_escape_string($_POST["new_po_net_total"]),
    "convert_to_word" => $conn->real_escape_string($convert_to_word),
    "inc_vat" => $conn->real_escape_string($inc_vat),
    "vat_percent" => $vat_percent,
    "note" => $conn->real_escape_string($_POST["new_po_note"]),
    "date_add" => $conn->real_escape_string(date("Y-m-d H:i:s"))
);

$insert_sql = "INSERT INTO tbl_po_content (";

$is_first = 0;
foreach($a_data as $key => $value){
	if($is_first!=0){
		$insert_sql .= ",";
	}
	$insert_sql .= $key;
	$is_first=1;
}

$insert_sql .= ") VALUES (";

$is_first = 0;
foreach($a_data as $key => $value){
	if($is_first!=0){
		$insert_sql .= ",";
	}
	$insert_sql .= "'".$value."'";
	$is_first=1;
}

$insert_sql .= ");";
?>
<script type="text/javascript">    
<?php
if( $conn->query($insert_sql) ){

    $po_con_id = $conn->insert_id;

    if(!$no_need_update){
        $next_po_number = intval($row_po_number["po_number"])+1;
        $update_po_number = "UPDATE tbl_po_number SET po_number='".$next_po_number."',lastest_no='".$new_po_number."' WHERE po_num_id='".$row_po_number["po_num_id"]."'; " ;
        $conn->query($update_po_number);
    }

    $a_new_po_item = $_POST["new_po_item"];
    for($i=0;$i<sizeof($a_new_po_item);$i++){
        if($a_new_po_item[$i]!=""){

            $sql_insert_item = "INSERT INTO tbl_po_item (po_con_id,code,detail,color,qty,unit,price_per_unit,amount,date_add) VALUES ('".$po_con_id."','".$conn->real_escape_string($_POST["new_po_code"][$i])."'";
            $sql_insert_item .= ",'".$conn->real_escape_string($_POST["new_po_item"][$i])."','".$conn->real_escape_string($_POST["new_po_color"][$i])."','".$_POST["new_po_qty"][$i]."','".$_POST["new_po_unit"][$i]."'";
            $sql_insert_item .= ",'".$_POST["new_po_ppu"][$i]."','".$_POST["new_po_amount"][$i]."','".date("Y-m-d H:i:s")."'); ";
            $conn->query($sql_insert_item);

        }
    }
    ?>
    window.parent.saveNewPOSuccess();
    <?php
}else{
    ?>
    window.parent.saveNewPOFail();
    <?php
}
?>
</script>