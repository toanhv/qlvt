<?php
function get_root_unit_id($arr_all_unit){
	$v_root_id = "";
	for($i = 0; $i < sizeof($arr_all_unit); $i++){
		if (is_null($arr_all_unit[$i][1])){
			$v_root_id = $arr_all_unit[$i][0]; //Don vi goc 
			break;
		}
	}
	return $v_root_id;
}
//Lay don vi cap 1 chu staff
function get_unit_level1_name($p_staff_id,$p_unit_id,$arr_all_unit){
	$v_count = sizeof($arr_all_unit);
	$v_root_id = get_root_unit_id($arr_all_unit);
	$v_parent_id = $p_unit_id;
	while($v_parent_id <> $v_root_id){
		for($i=0; $i < $v_count; $i++){
			if ($arr_all_unit[$i][0] == $v_parent_id){
				$v_unit_level1_id = $arr_all_unit[$i][0];
				$v_parent_id =  $arr_all_unit[$i][1];
				$v_unit_leve1_name =  $arr_all_unit[$i][3];
				break;
			}
		}
	}
	return $v_unit_leve1_name;
}
?>