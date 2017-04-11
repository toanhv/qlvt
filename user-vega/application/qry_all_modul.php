<?php
$v_item_status = -1;
if (_is_sqlserver()) {
	//Lay tat cac Modul cua ung dung hien thoi	
	/*
	$cmd = mssql_init("User_ModulGetAll",$conn);
	@mssql_bind($cmd,"@p_status",$v_item_status,SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_modul = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec User_ModulGetAll ";
	$v_sql_string.= "".$v_item_status."";
	$arr_all_modul = _adodb_query_data_in_number_mode($v_sql_string);
}?>


