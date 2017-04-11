<?
/*if(isset($_REQUEST['hdn_filter'])){
	$v_filter = _replace_bad_char(trim($_REQUEST['hdn_filter']));
}else { 
	$v_filter = "";
}*/
$v_filter = "";
if (_is_sqlserver()){
	$v_filter = "%".$v_filter."%";
	$v_status = -1;	
	$v_sql_string = "Exec USER_PositionGroupGetAll ";
	$v_sql_string.= "'".$v_filter."'";
	$v_sql_string.= ",".$v_status."";
	$arr_all_position_group = _adodb_query_data_in_number_mode($v_sql_string);
}
?>

 