<?php
//inser data to mysql thai date
$now=date("Y-m-d H:i:s");
$no_y=date("d-m-Y");

function Ndate($val){
	$exd = explode("-",$val);
	$exDate = explode(" ",$exd[2]);
	$month = $exd[1];
	$year = $exd[0];
	$date = $exDate[0].'/'.$month.'/'.$year;
	return $date;
}

function Endate($val){
	$exd = explode("-",$val);
	$month = $exd[1];
	switch ($month){
		case "01"	: $month = "Jan";
		break;
		case "02"	: $month = "Feb";
		break;
		case "03"	: $month = "Mar";
		break;
		case "04"	: $month = "Apr";
		break;
		case "05"	: $month = "May";
		break;
		case "06"	: $month = "Jun";
		break;
		case "07"	: $month = "Jul";
		break;
		case "08"	: $month = "Aug";
		break;
		case "09"	: $month = "Sep";
		break;
		case "10"	: $month = "Oct";
		break;
		case "11"	: $month = "Nov";
		break;
		case "12"	: $month = "Dec";
		break;
	}
	$year = $exd[0];
	$date = $exd[2]." ".$month." ".$year;
	return $date;
}

function Endate2($val){
	$exd = explode("-",$val);
	$exDate = explode(" ",$exd[2]);
	$month = $exd[1];
	switch ($month){
		case "01"	: $month = "Jan";
		break;
		case "02"	: $month = "Feb";
		break;
		case "03"	: $month = "Mar";
		break;
		case "04"	: $month = "Apr";
		break;
		case "05"	: $month = "May";
		break;
		case "06"	: $month = "Jun";
		break;
		case "07"	: $month = "Jul";
		break;
		case "08"	: $month = "Aug";
		break;
		case "09"	: $month = "Sep";
		break;
		case "10"	: $month = "Oct";
		break;
		case "11"	: $month = "Nov";
		break;
		case "12"	: $month = "Dec";
		break;
	}
	$year = $exd[0];
	$date = $exDate[0].' '.$month.' '.$year.' '.$exDate[1];
	return $date;
}

function Endate3($val){
	$exd = explode("-",$val);
	$exDate = explode(" ",$exd[2]);
	$month = $exd[1];
	switch ($month){
		case "01"	: $month = "Jan";
		break;
		case "02"	: $month = "Feb";
		break;
		case "03"	: $month = "Mar";
		break;
		case "04"	: $month = "Apr";
		break;
		case "05"	: $month = "May";
		break;
		case "06"	: $month = "Jun";
		break;
		case "07"	: $month = "Jul";
		break;
		case "08"	: $month = "Aug";
		break;
		case "09"	: $month = "Sep";
		break;
		case "10"	: $month = "Oct";
		break;
		case "11"	: $month = "Nov";
		break;
		case "12"	: $month = "Dec";
		break;
	}
	$year = $exd[0];
	$date = $exDate[0].' '.$month.' '.$year;
	return $date;
}


function getBE($val){
	$exd = explode("-",$val);
	$year = $exd[2]+543;
	return $year;
}


function full_copy( $source, $target ) {
    if ( is_dir( $source ) ) {
        @mkdir( $target , 0777, true);
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' ) {
                continue;
            }
            $Entry = $source . '/' . $entry; 
            if ( is_dir( $Entry ) ) {
                full_copy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }

        $d->close();
    }else {
        copy( $source, $target );
    }
}

function unitType($val){
	switch ($val) {
		case 1:
			$tu_name='Piece';
			break;
		case 2:
			$tu_name='Yard';
			break;
		case 3:
			$tu_name='KG';
			break;
		default : 
			$tu_name='';
			break;
	}
	return $tu_name;
}


function number_to_string($s_tmp_num){

	$s_decimal = "";
	$tmp_explode = explode(".",$s_tmp_num);
	if(sizeof($tmp_explode)==2){
		$s_tmp_num = $tmp_explode[0];
		$s_decimal = $tmp_explode[1];
	}else if(sizeof($tmp_explode)==1){
		//ignore
	}else{
		return false;
	}

	$n_size = strlen($s_tmp_num);
	$n_loop = intval($n_size/6);
	if(($n_size%6)>0){
		$n_loop++;
		$n_first_loop = $n_size%6;
	}else{
		$n_first_loop = 6;
	}


	$s_output = "";

	for($i=0;$i<$n_loop;$i++){

		if( $i==0 ){
			$use_string = substr($s_tmp_num,0,$n_first_loop);
			for($j=0;$j<$n_first_loop;$j++){


				$tmp_string = substr($use_string,$j,1);
				if( ($j+1)==$n_first_loop ){
					if( ( ($n_first_loop==1) && ( strlen($use_string)==1 ) ) || ( ($n_first_loop>1) && ( substr($use_string,($n_first_loop-2),1)=="0" ) ) ){
						$s_output .= con_num2str($tmp_string);
					}else{
						$s_output .= con_num2str($tmp_string,0);
					}
					
				}else if( ($j+2)==$n_first_loop ){

					if( ($tmp_string=="2") || ($tmp_string=="1") ){
						$s_output .= con_num2str($tmp_string,2);

					}else{
						$s_output .= con_num2str($tmp_string);
					}

					if($tmp_string!="0"){
						$s_output .= "สิบ";
					}
					
				}else{

					$s_output .= con_num2str($tmp_string);

					if($tmp_string!="0"){
						switch( ($n_first_loop-$j) ){
							case 6 : $s_output .= "แสน"; break;
							case 5 : $s_output .= "หมื่น"; break;
							case 4 : $s_output .= "พัน"; break;
							case 3 : $s_output .= "ร้อย"; break;
							
						}
					}
				}		
			}
		}else{

			$s_output .= "ล้าน";

			$use_string = substr($s_tmp_num,( $n_first_loop+(6*($i-1)) ),6);
			for($j=0;$j<6;$j++){

				$tmp_string = substr($use_string,$j,1);
				if( ($j+1)==6 ){
					if(  substr($use_string,4,1)=="0"  ){
						$s_output .= con_num2str($tmp_string);
					}else{
						$s_output .= con_num2str($tmp_string,0);
					}
					
				}else if( ($j+2)==6 ){

					if( ($tmp_string=="2") || ($tmp_string=="1") ){
						$s_output .= con_num2str($tmp_string,2);
					}else{
						$s_output .= con_num2str($tmp_string);
					}

					if($tmp_string!="0"){
						$s_output .= "สิบ";
					}
					
				}else{

					$s_output .= con_num2str($tmp_string);

					if($tmp_string!="0"){
						switch( (6-$j) ){
							case 6 : $s_output .= "แสน"; break;
							case 5 : $s_output .= "หมื่น"; break;
							case 4 : $s_output .= "พัน"; break;
							case 3 : $s_output .= "ร้อย"; break;
							
						}
					}
				}		
			}
		}

	}

	if( ($s_decimal=="") || (intval($s_decimal)==0) ){
		$s_output .= "บาทถ้วน";
	}else{

		$s_output .= "บาท";
		if(strlen($s_decimal)==1){
			$s_output .= con_num2str($s_decimal,2)."สิบ";
		}else if(strlen($s_decimal)==2){

			$tmp_stang = substr($s_decimal,0,1);
			if($tmp_stang!="0"){
				$s_output .= con_num2str($tmp_stang,2)."สิบ";
			}

			$tmp_stang = substr($s_decimal,1,1);
			$s_output .= con_num2str($tmp_stang,0);

		}
		$s_output .= "สตางค์";
	}

	return $s_output;
}



function con_num2str($in_str,$unit_no=1){

	$out_str = "";
	switch($in_str){
		case "0" : $out_str = ""; break;
		case "1" : 
			if($unit_no==1){
				$out_str = "หนึ่ง";
			}else if($unit_no==0){
				$out_str = "เอ็ด";
			}
		break;
		case "2" :
			if($unit_no==2){
				$out_str = "ยี่";
			}else if( ($unit_no==1) || ($unit_no==0) ){
				$out_str = "สอง";
			}
		break;
		case "3" : $out_str = "สาม"; break;
		case "4" : $out_str = "สี่"; break;
		case "5" : $out_str = "ห้า"; break;
		case "6" : $out_str = "หก"; break;
		case "7" : $out_str = "เจ็ด"; break;
		case "8" : $out_str = "แปด"; break;
		case "9" : $out_str = "เก้า"; break;
	}

	return $out_str;

}

?>