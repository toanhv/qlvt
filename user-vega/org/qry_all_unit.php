<?
$v_status = -1;
if (_is_sqlserver()){	
	$v_sql_string = "Exec USER_UnitGetAll ";
	$v_sql_string.= "".$v_status."";
	$arr_all_unit = _adodb_query_data_in_number_mode($v_sql_string);
}
?>

 