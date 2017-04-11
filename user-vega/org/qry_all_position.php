<?
$v_current_item_id = 0;
if (isset($_REQUEST['hdn_item_id'])){
	$v_current_item_id = intval($_REQUEST['hdn_item_id']);
}
//echo $v_current_item_id;
if(isset($_REQUEST['hdn_filter'])){
	$v_filter = _replace_bad_char(trim($_REQUEST['hdn_filter']));
}else { 
	$v_filter = "";
}
if (_is_sqlserver()){
	$v_filter = "%".$v_filter."%";
	$v_status = -1;
	$v_sql_string = "Exec USER_PositionGetAll";
	$v_sql_string.= "'".$v_filter."'";
	$v_sql_string.= ",".$v_status."";
	$arr_all_position = _adodb_query_data_in_number_mode($v_sql_string);
}
?>

 