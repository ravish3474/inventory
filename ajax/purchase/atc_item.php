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
    $table_db="tbl_item"; // ตารางที่ต้องการค้นหา
    $find_field="item_name"; // ฟิลที่ต้องการค้นหา
    $sql = "
    SELECT 
    item_id,
    item_name,
    item_type 
    FROM $table_db WHERE LOCATE('$q', $find_field) > 0 AND enable=1 
    ORDER BY LOCATE('$q', $find_field), $find_field LIMIT $pagesize
    ";
    $result = $conn->query($sql);
    if($result && $result->num_rows>0){
        while($row = $result->fetch_assoc()){
            // กำหนดฟิลด์ที่ต้องการส่ง่กลับ ปกติจะใช้ primary key ของ ตารางนั้น
            $id = $row["item_id"]; // 
             
            // จัดการกับค่า ที่ต้องการแสดง 
            $name = trim($row["item_name"]);// ตัดช่องวางหน้าหลัง
            $name = addslashes($name); // ป้องกันรายการที่ ' ไม่ให้แสดง error
            $name = htmlspecialchars($name); // ป้องกันอักขระพิเศษ

            $item_type = base64_encode($row["item_type"]);
 
            // กำหนดรูปแบบข้อความที่แใดงใน li ลิสรายการตัวเลือก
            $display_name = preg_replace("/(" .$q. ")/i", "<b>$1</b>", $name);
            echo "
                <li onselect=\"this.setText('$name').showEdit('$id','$name','$item_type')\">
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