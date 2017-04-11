<?php
$v_unit_parent_id = 0;
if(isset($_REQUEST['hdn_parent_item_id'])){
	$v_unit_parent_id = intval($_REQUEST['hdn_parent_item_id']);
}
$v_unit_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_unit_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
if(_is_sqlserver()){
	if($v_unit_id > 0){					
		$v_sql_string = "Exec USER_UnitGetSingle ";
		$v_sql_string.= "".$v_unit_id."";
		$arr_single_unit = _adodb_query_data_in_number_mode($v_sql_string);
	}else{
		$v_sql_string = "Exec USER_UnitGetSingle ";
		$v_sql_string.= "".$v_unit_parent_id."";
		$arr_single_unit = _adodb_query_data_in_number_mode($v_sql_string);
	}	
}	
?>
 