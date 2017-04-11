<?php
//Xac dinh id cua cha node can them
$v_parent_id = 0;
if(isset($_REQUEST['hdn_parent_id'])){
	$v_parent_id = $_REQUEST['hdn_parent_id'];
}
//echo $v_parent_id;
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}
if(_is_sqlserver()){
	if($v_item_id >0){	//Truong hop hieu chinh
		/*
		$cmd = @mssql_init("USER_ModulGetSingle",$conn);
		@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
		$result = @mssql_execute($cmd);	
		$arr_single_modul = _get_row_to_array($result);
		@mssql_free_result($result);
		*/
		$v_sql_string = "Exec USER_ModulGetSingle ";
		$v_sql_string.= "".$v_item_id."";
		$arr_single_modul = _adodb_query_data_in_number_mode($v_sql_string);
		
	}else{ //Truong hop them moi 
		if($v_parent_id > 0){
			/*
			$cmd = @mssql_init("USER_ApplicationGetSingle",$conn);
			@mssql_bind($cmd,"@p_application_id",$v_parent_id,SQLINT4);
			$result = @mssql_execute($cmd);	
			$arr_single_application = _get_row_to_array($result);
			@mssql_free_result($result);	
			*/			
			$v_sql_string = "Exec USER_ApplicationGetSingle ";
			$v_sql_string.= "".$v_parent_id."";
			$arr_single_application = _adodb_query_data_in_number_mode($v_sql_string);			
		}
	}
}
?>