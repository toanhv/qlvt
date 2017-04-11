<?
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}

if(_is_sqlserver()){
	$v_sql_string = "Exec USER_PositionGetSingle ";
	$v_sql_string.= "".$v_item_id."";
	$arr_single_position = _adodb_query_data_in_number_mode($v_sql_string);
}

?>
 