<?
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}else{
	$v_item_id = 0;
}

$v_application_id = intval($_SESSION['user_application_id']);

if (_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_FunctionGetAllForEnduser",$conn);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	@mssql_bind($cmd,"@p_enduser_id",$v_item_id,SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_function_for_enduser = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_FunctionGetAllForEnduser ";
	$v_sql_string.= "".$v_application_id."";
	$v_sql_string.= ",".$v_item_id."";
	$arr_all_function_for_enduser = _adodb_query_data_in_number_mode($v_sql_string);	
}?>
