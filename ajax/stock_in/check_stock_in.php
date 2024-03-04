<?php

require_once('../../db.php');

for($i=0;$i<sizeof($_POST["cat_id"]);$i++){

	$chk_fabric = "SELECT fabric_id FROM fabric WHERE cat_id='".$_POST["cat_id"][$i]."' AND fabric_color='".$_POST["color_name"][$i]."' AND fabric_no='".$_POST["roll_no"][$i]."' AND fabric_balance<>0; ";
	$rs_chk_fabric = $conn->query($chk_fabric);

	if( $rs_chk_fabric->num_rows > 0){
		?>
		<script type="text/javascript">
		window.parent.checkNotPass();
		</script>
		<?php
		exit();
	}

}

?>
<script type="text/javascript">
window.parent.checkPass();
</script>