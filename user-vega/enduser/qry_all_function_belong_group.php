<?php
$v_application_id = intval($_SESSION['user_application_id']);
if (_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_FunctionGetAllBelongGroup",$conn);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_function_belong_group = _get_row_to_array($result);
	@mssql_free_result($result);
	*/	
	$v_sql_string = "Exec USER_FunctionGetAllBelongGroup ";
	$v_sql_string.= "".$v_application_id."";
	$arr_all_function_belong_group = _adodb_query_data_in_number_mode($v_sql_string);
}?>
