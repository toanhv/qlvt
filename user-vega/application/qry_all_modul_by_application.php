<?php
//Neu la hieu chinh 1 chuc nang thi  lay tu qry_single_function
if (isset($arr_single_function[0][6])){
	$v_application_id = intval($arr_single_function[0][6]);
}else{ // Lay tu app duoc chon
	$v_application_id = intval($_REQUEST['sel_application']); 
}	
$v_item_status = -1;
if (_is_sqlserver()) {
	//Lay tat cac Modul cua ung dung hien thoi	
	/*
	$cmd = mssql_init("User_ModulGetAllByApp",$conn);
	@mssql_bind($cmd,"@p_status",$v_item_status,SQLINT4);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_modul = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec User_ModulGetAllByApp ";
	$v_sql_string.= "".$v_item_status."";
	$v_sql_string.= ",".$v_application_id."";
	$arr_all_modul = _adodb_query_data_in_number_mode($v_sql_string);
	//var_dump($arr_all_modul);
}?>


