<?php
$v_status = -1;
// ID cua node duoc NSD nhan chuot vao
$v_current_item_id = 0;
if (isset($_REQUEST['hdn_item_id'])){
	$v_current_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
//echo $v_current_position;
if (_is_sqlserver()){
	$v_sql_string = "Exec USER_StaffGetAll ";
	$v_sql_string.= "".$v_status."";
	$v_sql_string.= ",".$v_current_item_id."";
	$arr_all_staff = _adodb_query_data_in_number_mode($v_sql_string);
}
?>
 