<?php
session_start();

require_once('../../db.php');
require_once('../../function.php');

$po_con_id = $_POST['edit_po_con_id'];

$po_sup_id = $_POST['h_supplier_id_edit'];

$convert_to_word = "";
$nationality = "";
if( isset($_POST["edit_chk_lang"]) && ($_POST["edit_chk_lang"]=="TH") ){
    $convert_to_word = number_to_string($_POST["edit_po_net_total"]);
    $nationality = "TH";
}else{
    $nationality = "NOT_TH";
}

$inc_vat = "";
$vat_percent = 0.0;
if(isset($_POST["edit_chk_include_vat"])){
    $inc_vat = $_POST["edit_chk_include_vat"];
    $vat_percent = 7.0;
}


$a_data = array(
    "po_sup_id" => $conn->real_escape_string($po_sup_id),
    "nationality" => $nationality,
    "sup_name" => $conn->real_escape_string($_POST["show_supplier_edit"]),
    "sup_address" => $conn->real_escape_string($_POST["edit_sup_address"]),
    "sup_tel" => $conn->real_escape_string($_POST["edit_sup_tel"]),
    "sup_fax" => $conn->real_escape_string($_POST["edit_sup_fax"]),
    "sup_email" => $conn->real_escape_string($_POST["edit_sup_email"]),
    "sale_name" => $conn->real_escape_string($_POST["edit_sale_name"]),
    "sup_tax_id" => $conn->real_escape_string($_POST["edit_sup_tax_id"]),
    "sup_payment" => $conn->real_escape_string($_POST["edit_sup_payment"]),
    "delivery_date" => $conn->real_escape_string($_POST["edit_delivery_date"]),
    "po_date" => $conn->real_escape_string($_POST["edit_po_date"]),
    "prepare_emp_id" => $conn->real_escape_string(intval($_SESSION["employee_id"])),
    "prepare_emp_name" => $conn->real_escape_string($_SESSION["employee_name"]),
    "net_total" => $conn->real_escape_string($_POST["edit_po_net_total"]),
    "convert_to_word" => $conn->real_escape_string($convert_to_word),
    "inc_vat" => $conn->real_escape_string($inc_vat),
    "vat_percent" => $vat_percent,
    "note" => $conn->real_escape_string($_POST["edit_po_note"])
);

$update_sql = "UPDATE tbl_po_content SET ";

$is_first = 0;
foreach($a_data as $key => $value){
	if($is_first!=0){
		$update_sql .= ",";
	}
	$update_sql .= $key."='".$value."'";
	$is_first=1;
}

$update_sql .= " WHERE po_con_id=".$po_con_id.";";

?>
<script type="text/javascript">    
<?php
if( $conn->query($update_sql) ){

    $a_edit_po_item = $_POST["edit_po_item"];
    for($i=0;$i<sizeof($a_edit_po_item);$i++){

        if( ($_POST["edit_po_item_id"][$i]!="") && ($_POST["edit_po_item"][$i]=="") && ($_POST["edit_po_qty"][$i]=="") && ($_POST["edit_po_ppu"][$i]=="") ){

            $disable_item = "UPDATE tbl_po_item SET enable=0 WHERE po_item_id=".$_POST["edit_po_item_id"][$i].";";
            $conn->query($disable_item);

        }else if( ($_POST["edit_po_item_id"][$i]!="") ){

            $update_item = "UPDATE tbl_po_item SET code='".$conn->real_escape_string($_POST["edit_po_code"][$i])."',detail='".$conn->real_escape_string($_POST["edit_po_item"][$i])."'";
            $update_item .= ",color='".$conn->real_escape_string($_POST["edit_po_color"][$i])."',qty='".$_POST["edit_po_qty"][$i]."'";
            $update_item .= ",unit='".$conn->real_escape_string($_POST["edit_po_unit"][$i])."',price_per_unit='".$_POST["edit_po_ppu"][$i]."',amount='".$_POST["edit_po_amount"][$i]."'";
            $update_item .= " WHERE po_item_id=".$_POST["edit_po_item_id"][$i].";";
            $conn->query($update_item);

        }else if($a_edit_po_item[$i]!=""){

            $sql_insert_item = "INSERT INTO tbl_po_item (po_con_id,code,detail,color,qty,unit,price_per_unit,amount,date_add) VALUES ('".$po_con_id."','".$conn->real_escape_string($_POST["edit_po_code"][$i])."'";
            $sql_insert_item .= ",'".$conn->real_escape_string($_POST["edit_po_item"][$i])."','".$conn->real_escape_string($_POST["edit_po_color"][$i])."','".$_POST["edit_po_qty"][$i]."','".$_POST["edit_po_unit"][$i]."'";
            $sql_insert_item .= ",'".$_POST["edit_po_ppu"][$i]."','".$_POST["edit_po_amount"][$i]."','".date("Y-m-d H:i:s")."'); ";
            $conn->query($sql_insert_item);
        }
    }
    ?>
    window.parent.saveEditPOSuccess();
    <?php
}else{
    ?>
    window.parent.saveEditPOFail();
    <?php
}
?>
</script>