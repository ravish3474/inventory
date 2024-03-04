<?php

header("Content-type:text/html; charset=UTF-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);     

require_once('../../db.php');
//echo "<li>".$_GET['q']."</li>";
if(isset($_GET['q']) && $_GET['q']!=""){
    $q = urldecode($_GET["q"]);
    $q = $conn->real_escape_string($q);
     
    $pagesize = 15; // จำนวนรายการที่ต้องการแสดง
    $table_db="tbl_po_supplier"; // ตารางที่ต้องการค้นหา
    $find_field="sup_name"; // ฟิลที่ต้องการค้นหา
    $sql = "
    SELECT 
    * 
    FROM $table_db WHERE LOCATE('$q', $find_field) > 0 AND enable=1 
    ORDER BY LOCATE('$q', $find_field), $find_field LIMIT $pagesize
    ";
    $result = $conn->query($sql);
    if($result && $result->num_rows>0){
        while($row = $result->fetch_assoc()){
            // กำหนดฟิลด์ที่ต้องการส่ง่กลับ ปกติจะใช้ primary key ของ ตารางนั้น
            $id = $row["po_sup_id"]; // 
             
            // จัดการกับค่า ที่ต้องการแสดง 
            $name = trim($row["sup_name"]);// ตัดช่องวางหน้าหลัง
            $name = addslashes($name); // ป้องกันรายการที่ ' ไม่ให้แสดง error
            $name = htmlspecialchars($name); // ป้องกันอักขระพิเศษ

            $nationality = base64_encode($row["nationality"]);
            $sup_address = base64_encode($row["sup_address"]);
            $sup_tel = base64_encode($row["sup_tel"]);
            $sup_fax = base64_encode($row["sup_fax"]);
            $sup_email = base64_encode($row["sup_email"]);
            $sale_name = base64_encode($row["sale_name"]);
            $sup_tax_id = base64_encode($row["sup_tax_id"]);
            $sup_payment = base64_encode($row["sup_payment"]);
 
            // กำหนดรูปแบบข้อความที่แใดงใน li ลิสรายการตัวเลือก
            $display_name = preg_replace("/(" .$q. ")/i", "<b>$1</b>", $name);
            echo "
                <li onselect=\"this.setText('$name').showEdit('$id','$name','$nationality','$sup_address','$sup_tel','$sup_fax','$sup_email','$sale_name','$sup_tax_id','$sup_payment')\">
                    $display_name
                </li>
            ";  
        }
    }else{
    	//echo "<li onclick=\"saveNewColor();\">Save this as NEW COLOR</li>"; 
    }
    $conn->close();
}else{
    	//echo "<li onclick=\"saveNewColor();\">Save this as NEW COLOR</li>"; 
    }
?>