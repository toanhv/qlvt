<?php
$v_unit_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_unit_id	= intval($_REQUEST['hdn_item_id']);
}
if (_is_sqlserver()){
	/*
	$cmd = mssql_init("USER_GetPersonalInfoOfAllStaffForEveryStatus", $conn);
	@mssql_bind($cmd,"@p_unit_id",$v_unit_id,SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_staff_by_unit = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_GetPersonalInfoOfAllStaffForEveryStatus ";
	$v_sql_string.= "".$v_unit_id."";
	$arr_all_staff_by_unit = _adodb_query_data_in_number_mode($v_sql_string);
}
?>