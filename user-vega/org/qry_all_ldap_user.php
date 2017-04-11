<?
$v_filter="";
if(isset($_REQUEST['hdn_filter_enduser'])){
	$v_filter = _replace_bad_char($_REQUEST['hdn_filter_enduser']);
}
$arr_all_ldap_user = LDAP_GetAllUser($v_filter);
//var_dump($arr_all_ldap_user);

if (_is_sqlserver()){
	$v_sql_string = "Select C_DN From T_USER_STAFF Where C_DN<>'' ";
	$arr_all_current_staff = _adodb_query_data_in_number_mode($v_sql_string);
}
?>
 