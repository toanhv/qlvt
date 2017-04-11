<?php
$v_application_id = $_REQUEST['hdn_application_id'];
$v_modul_id = $_REQUEST['hdn_modul_id'];
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = "";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}
if(_is_sqlserver()){
	if($v_item_id >0){
		/*
		$cmd = @mssql_init("USER_FunctionGetSingle",$conn);
		@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);		
		$result = @mssql_execute($cmd);
		$arr_single_function  = _get_row_to_array($result);
		@mssql_free_result($result);
		*/
		$v_sql_string = "Exec USER_FunctionGetSingle ";
		$v_sql_string.= "".$v_item_id."";
		$arr_single_function = _adodb_query_data_in_number_mode($v_sql_string);
	}	
}?>
