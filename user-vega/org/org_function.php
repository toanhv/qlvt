<?php
function get_root_unit_id($arr_unit){
	$v_root_id = "";
	for($i=0;$i<sizeof($arr_unit); $i++){
		if (is_null($arr_unit[$i][1])){
			$v_root_id = $arr_unit[$i][0];
			break;
		}
	}
	return $v_root_id;
}

/*Ham thuc hien lay ra "Chuoi" cac don vi muc tren cua can bo hien thoi (duong dan toi staff)
CHI CAN TIM TU THANG UNIT CUOI CUNG*/
function get_string_unit_level_higher($unit_id,$arr_unit){
	$v_root_id = get_root_unit_id($arr_unit);
	$v_list_unit_higher = $v_root_id; 
	while($unit_id <> $v_root_id){
		for($i = 0; $i < sizeof($arr_unit); $i++){
			if ($arr_unit[$i][0] == $unit_id){
				$v_list_unit_higher = $v_list_unit_higher.",".$arr_unit[$i][0].",";
				$unit_id =  $arr_unit[$i][1];
				break;
			}
		}
	}
	return $v_list_unit_higher;
}
?>
 