<?php
/*
--+TRa ve danh muc cac ung dung ma NSD co quyen su dung cung nhu quan tri
*/
if($staff_id >0){
	if(_is_sqlserver()){
		$v_sql_string = "Exec USER_ApplicationGetAll_For_Enduser ";
		$v_sql_string.= "".$staff_id."";
		$arr_all_application_for_enduser = _adodb_query_data_in_number_mode($v_sql_string);
	}
}
?>
 