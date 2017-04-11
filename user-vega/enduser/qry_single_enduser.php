<?
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){	
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}	
if($v_item_id >0){
	if (_is_sqlserver()){
		/*
		$cmd = @mssql_init("USER_EndUserGetsingle",$conn);
		@mssql_bind($cmd,"@p_enduser_id",$v_item_id,SQLINT4);
		$result = @mssql_execute($cmd);
		$arr_single_enduser = _get_row_to_array($result);
		@mssql_free_result($result);
		*/
		$v_sql_string = "Exec USER_EndUserGetsingle ";
		$v_sql_string.= "".$v_item_id."";
		$arr_single_enduser = _adodb_query_data_in_number_mode($v_sql_string);
	}
}?>