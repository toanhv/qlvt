<?php 
//Luu noi dung cua bien cookie
function _create_cookie($p_name,$p_value = ""){ 
	$expires = time() + 60*60*24; 
	setcookie($p_name, $p_value, $expires,"/",""); 
} 
// Lay noi dung cua bien cookie
function _get_cookie($p_name){ 
	if (isset($_COOKIE[$p_name]) && strlen($_COOKIE[$p_name])>0 ) { 
		return urldecode($_COOKIE[$p_name]); 
	}else{ 
		return FALSE; 
	} 

} 
//Sinh ra mot so ngau nhien duy nhat
function _generate_guid(){

	$rawid = strtoupper(md5(uniqid(rand(), true)));
	$workid = $rawid;
		
	$byte = hexdec( substr($workid,12,2) );
	$byte = $byte & hexdec('0f');
	$byte = $byte | hexdec('40');
	$workid = substr_replace($workid, strtoupper(dechex($byte)), 12, 2);
	
	$byte = hexdec( substr($workid,16,2) );
	$byte = $byte & hexdec('3f');
	$byte = $byte | hexdec('80');
	$workid = substr_replace($workid, strtoupper(dechex($byte)), 16, 2);

	$wid = substr($workid, 0, 8).'-'.substr($workid, 8, 4).'-' .substr($workid,12, 4).'-'.substr($workid,16, 4).'-'.substr($workid,20,12);
	return  $wid;
}
    
// Su dung ADODB de thuc thi cau lenh Query du lieu tu CSDL 
// va tra lai array voi cac phan tu duoc truy nhap qua SO THU TU
function _adodb_exec_update_delete_sql($p_sql){
	global $ado_conn;
	if(_is_sqlserver()){
		$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$arr_single_data = $ado_conn->GetRow($p_sql); 
	}
	return $arr_single_data;
}

// Su dung ADODB de thuc thi cau lenh Query du lieu tu CSDL 
// va tra lai array voi cac phan tu duoc truy nhap qua SO THU TU
function _adodb_query_data_in_number_mode($p_sql){
	global $ado_conn;
	$arr_all_data = array();
	if(_is_sqlserver()){
		$ado_conn->SetFetchMode(ADODB_FETCH_NUM);
		$rs = $ado_conn->Execute($p_sql);
		$i = 0;
		if ($rs) 	
			while ($arr = $rs->FetchRow()) {  
			$arr_all_data[$i] = $arr;
			$i++;
		}			
	}
	return $arr_all_data;
}
// Su dung ADODB de thuc thi cau lenh Query du lieu tu CSDL 
// va tra lai array voi cac phan tu duoc truy nhap qua TEN
function _adodb_query_data_in_name_mode($p_sql){
	global $ado_conn;
	$arr_all_data = array();
	if(_is_sqlserver()){
		$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $ado_conn->Execute($p_sql);
		$i = 0;
		if ($rs) 	
			while ($arr = $rs->FetchRow()) {  
			$arr_all_data[$i] = $arr;
			$i++;
		}
	}
	return $arr_all_data;
}

// Gui mail
function _send_email_from_PHP($p_from_email, $p_list_email_to, $p_subject, $p_message, $p_from_name = false, $p_list_cc=false, $p_list_bcc=false, $p_attach_file_path=false){
	require("../phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->Host     = "localhost";//SMTP servers
	$mail->From     = $p_from_email;
	if (!is_null($p_from_name) And $p_from_name!=""){
		$mail->FromName = $p_from_name;
	}	
	//$p_to la mot danh sach cac email can gui toi
	//neu co tu 3 dia chi tro len thi khong gui duoc ????
	$arr_to_email = split(',',$p_list_email_to);
	$v_to_count = sizeof($arr_to_email);
	if($v_to_count > 0) {
		for($i = 0; $i < $v_to_count-1; $i++){
			$mail->AddAddress($arr_to_email[$i]); 
			echo $arr_to_email[$i] . '<br>';
		}
	}
	//$mail->AddAddress($p_list_email_to);
	$mail->IsHTML(true);// cho phep hien thi message duoi dinh dang HTML
	$mail->IsSMTP();//dung SMTP thi moi gui duoc theo CC va BCC        
	//gui CC                              
	if (!is_null($p_list_cc) and $p_list_cc!=""){
		$arr_cc_email = split(',',$p_list_cc);
		$v_cc_count = sizeof($arr_cc_email);
		if($v_cc_count > 0) {
			for($j = 0; $j < $v_cc_count-1; $j++){
				$mail->AddCC($arr_cc_email[$j]);// gui CC
			}
		}
	}	
	//Gui BCC
	if (!is_null($p_list_bcc) and $p_list_bcc!=""){
		$arr_bcc_email = split(',',$p_list_bcc);
		$v_bcc_count = sizeof($arr_bcc_email);
		if($v_bcc_count > 0) {
			for($k = 0; $k < $v_bcc_count-1; $k++){
				$mail->AddBCC($arr_bcc_email[$k]);// gui BCC
			}
		}
	}	
	//reply den dia chi $p_from_email
	$mail->AddReplyTo($p_from_email);
	$mail->WordWrap = 50;// set word wrap
	//attach file
	if (!is_null($p_attach_file_path) and $p_attach_file_path!=""){
		$mail->AddAttachment($p_attach_file_path); //attach file
	}
	//chu de va noi dung
	$mail->Subject  = $p_subject;
	$mail->Body     = $p_message;
	if( !($mail->Send()) ){
	   echo "Fail";
	}
	echo "Message sent sucessfully !";
}
// Kiem tra dang nhap
function _LDAP_GetCN($p_dn){
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	$v_ret = "";
	if (strpos($p_dn,$_ISA_LDAP_USERNAME_ATTRIBUTE."=")>=0){
		$arr_dn_item = explode(",",$p_dn);
		$cn = $arr_dn_item[0];
		$arr_cn_item = explode("=",$cn);
		$v_ret = $arr_cn_item[1];
	}	
	return $v_ret;
}

//********************************************************************************************************************
//Ham _is_integrated_ldap Hien thi thong tin ve nguoi dang nhap vao cac ung dung co tich hop ISA-USER
//	Gia tri tra lai:
//		Chuoi HTML tao bang chua thong tin ve Nguoi dang nhap
//********************************************************************************************************************
function _is_integrated_ldap() {
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	if (strpos($_SESSION['staff_id'],$_ISA_LDAP_USERNAME_ATTRIBUTE."=")===false){ // Khong tich hop voi LDAP
		return false;
	}else{
		return true;
	}
}

/********************************************************************************
//Ten ham		:_get_all_application_on_isa_user()
//Chuc nang	: Lay danh sach tat ca cac ung dung tich hop boi ISA-USER
//Vi du:
//$p_arr_items = array();
//$p_level1_tag_name = "application";
//$p_level2_tag_name_list="id,code,name,status";
//$p_delimitor = ",";
//_get_all_application_on_isa_user();
********************************************************************************/
function _get_all_application_on_isa_user(){
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_all_application_on_isa_user');
	}else{
		$str_return = get_all_application_on_isa_user();
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//_write_log_file('xml_cut.log',$str_return);
	_xmlstring_to_array($str_return);
	return;
}	

/********************************************************************************
//Ten ham		:_get_all_room_of_week()
//Chuc nang	: Lay danh sach tat ca cac phong hop trong tuan tu lich lam viec cua lanh dao
//Vi du:
//$p_arr_items = array();
//$p_level1_tag_name = "room_list";
//$p_level2_tag_name_list="id,code,name";
//$p_delimitor = ",";
//_get_detail_info_of_all_room();
//$_SESSION['arr_all_room'] = $p_arr_items;
********************************************************************************/
function _get_all_room_of_week($p_year,$p_week){
	//echo $p_year.'=='.$p_week;exit;
	$parameters = array('p_year' => $p_year, 'p_week'=>$p_week);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_SCHEDULE_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_all_room_of_week', $parameters);
	}else{
		$str_return = call_user_func_array('get_all_room_of_week',$parameters);
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//_write_log_file('xml_cut.log',$str_return);
	_xmlstring_to_array($str_return);
	return;
}	
/********************************************************************************
//Ten ham		:_get_detail_info_of_all_room()
//Chuc nang	: Lay danh sach tat ca cac phong hop
//Vi du:
//$p_arr_items = array();
//$p_level1_tag_name = "room_list";
//$p_level2_tag_name_list="id,code,name";
//$p_delimitor = ",";
//_get_detail_info_of_all_room();
//$_SESSION['arr_all_room'] = $p_arr_items;
********************************************************************************/
function _get_detail_info_of_all_room(){
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_RESOURCE_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_detail_info_of_all_room');
	}else{
		$str_return = get_detail_info_of_all_room();
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//_write_log_file('xml.log',$str_return);
	_xmlstring_to_array($str_return);
	return;
}	
//********************************************************************************
//Ten ham		:_get_personal_info_of_all_enduser()
//Chuc nang	: Lay danh muc cac nhom chuc danh can bo
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "position_group";
//$p_level2_tag_name_list="id,code,name,order";
//$p_delimitor = ",";
//_get_all_position_group();
//$_SESSION['arr_all_position_group'] = $p_arr_items;
//********************************************************************************/
function _get_all_position_group(){
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_all_position_group');
	}else{
		$str_return = get_all_position_group();
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//_write_log_file('xml.log',$str_return);
	_xmlstring_to_array($str_return);
	return;
}	

//********************************************************************************
//Ten ham		:_delete_enduser_logged
//Chuc nang	: Xoa NSD khoi danh sach NSD da logon vao ung dung
//********************************************************************************/
function _delete_enduser_logged($p_ip_address,$p_app_code,$p_staff_id){
	$parameters = array('p_ip_address' => $p_ip_address, 'p_app_code' => $p_app_code, 'p_staff_id' => $p_staff_id);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('delete_enduser_logged', $parameters);
	}else{
		$str_return = call_user_func_array('delete_enduser_logged',$parameters);
	}
	if ($str_return=='true'){
		return true;
	}else{
		return false;
	}
}	

//********************************************************************************
//Ten ham		:_set_permission_on_function
//Chuc nang	: Dat quyen tren mot modul, function
//********************************************************************************/
function _set_permission_on_function($p_staff_id, $p_app_code, $p_modul_code, $p_function_code){
	$parameters = array('p_staff_id' => $p_staff_id, 'p_app_code'=>$p_app_code, 'p_modul_code'=>$p_modul_code, 'p_function_code'=>$p_function_code);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('set_permision_on_function', $parameters);
	}else{
		$str_return = call_user_func_array('set_permision_on_function',$parameters);
	}
	if ($str_return=='true'){
		return true;
	}else{
		return false;
	}	
}	
//********************************************************************************
//Ten ham		:_get_personal_info_of_all_enduser()
//Chuc nang	: Lay thong tin chi tiet cua tat ca cac end-user cua mot ung dung
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "group";
//$p_level2_tag_name_list="id,name,code,order";
//$p_delimitor = ",";
//_get_all_group_of_application();
//$_SESSION['arr_all_gruop'] = $p_arr_items;
//var_dump($_SESSION['doclib_arr_all_group']);
//********************************************************************************/
function _get_personal_info_of_all_enduser($p_app_code){
	$parameters = array('p_app_code'=>$p_app_code);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_personal_info_of_all_enduser', $parameters);
	}else{
		$str_return = call_user_func_array('get_personal_info_of_all_enduser',$parameters);
	}

	if (is_null($str_return) or $str_return==""){
		return;
	}
	//echo $str_return;
	_xmlstring_to_array($str_return);
	return;
}	

//********************************************************************************
//Ten ham		:_get_all_group_of_application()
//Chuc nang	: Lay danh sach cac group cua 1 application
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "group";
//$p_level2_tag_name_list="id,name,code,order";
//$p_delimitor = ",";
//_get_all_group_of_application();
//$_SESSION['arr_all_gruop'] = $p_arr_items;
//var_dump($_SESSION['doclib_arr_all_group']);
//********************************************************************************/
function _get_all_group_of_application($p_app_code){
	$parameters = array('p_app_code'=>$p_app_code);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_all_group_of_application', $parameters);
	}else{
		$str_return = call_user_func_array('get_all_group_of_application', $parameters);
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//echo $str_return;
	_xmlstring_to_array($str_return);
	return;
}	

//********************************************************************************
//Ten ham		:_get_all_group_by_member()
//Chuc nang	: Lay danh sach cac group ma p_staff_id la thanh vien
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "group";
//$p_level2_tag_name_list="id,name,code,order";
//$p_delimitor = ",";
//_get_all_group_of_application();
//$_SESSION['arr_all_gruop'] = $p_arr_items;
//var_dump($_SESSION['doclib_arr_all_group']);
//********************************************************************************/
function _get_all_group_by_member($p_app_code,$p_staff_id){
	$parameters = array('p_app_code'=>$p_app_code,'p_staff_id'=>$p_staff_id);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_all_group_by_member', $parameters);
	}else{
		$str_return = call_user_func_array('get_all_group_by_member', $parameters);
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	return $str_return;
}	

//********************************************************************************
//Ten ham		:_get_detail_info_of_all_unit()
//Chuc nang	: Lay thong tin chi tiet cua tat ca phong ban (unit)
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "staff";
//$p_level2_tag_name_list="id,name,code,unit_id,position_id,address,email,tel,order";
//$p_delimitor = ",";
//_get_personal_info_of_all_staff();
//$_SESSION['arr_all_staff'] = $p_arr_items;
//var_dump($_SESSION['arr_all_staff']);
//********************************************************************************/
function _get_detail_info_of_all_unit(){
	global $p_arr_items,$p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_detail_info_of_all_unit');
	}else{
		$str_return = get_detail_info_of_all_unit();
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	//_write_log_file('../xml.log',$str_return);
	_xmlstring_to_array($str_return);
	return;
}	
//********************************************************************************
//Ten ham		:_get_personal_info_of_all_staff()
//Chuc nang	: Lay thong tin ca nhan cua tat ca can bo (staff)
//Vi du:		
//$p_arr_items = array();
//$p_level1_tag_name = "staff";
//$p_level2_tag_name_list="id,name,code,unit_id,position_id,address,email,tel,order";
//$p_delimitor = ",";
//_get_personal_info_of_all_staff();
//$_SESSION['arr_all_staff'] = $p_arr_items;
//var_dump($_SESSION['arr_all_staff']);
//********************************************************************************/
function _get_personal_info_of_all_staff(){
	global $p_arr_items,$p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('get_personal_info_of_all_staff');
	}else{
		$str_return = get_personal_info_of_all_staff();
	}
	if (is_null($str_return) or $str_return==""){
		return;
	}
	_xmlstring_to_array($str_return);
	return;
}	
//********************************************************************************
//Ten ham		:_xmlstring_to_array
//Chuc nang	: Chuyen doi mot xau XML thanh mot array
//Tham so: $p_xmlstring - la xau XML can chuyen doi. Xau XML nay chi co 2 cap, vi du
//Cach su dung:
//- Truoc khi goi ham nay, phai khai bao cac bien sau:
//+ $p_arr_items: la array ket qua
//+ $p_level1_tag_name: ten tag dau tien cua xau XML (trong vi du tren thi $p_level1_tag_name=staff_list)
//+ $p_level2_tag_name_list: danh sach tag can chuyen gia tri vao array: (trong vi du tren thi $p_level1_tag_name="id,code")
//+ $p_delimitor: ki tu phan cach cac phan tu cua $p_level2_tag_name_list
//********************************************************************************/
function _xmlstring_to_array($p_xmlstring){
	global $p_arr_items,$p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
	$currentTag = "";
	$flag = 0;
	$count = 0;
	//$items = array();
	// opening tag handler
	// create parser
	$xp = xml_parser_create();
	// set element handler
	xml_set_element_handler($xp, "elementBegin", "elementEnd");
	xml_set_character_data_handler($xp, "characterData");
	xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, TRUE);
	xml_parser_set_option($xp, XML_OPTION_SKIP_WHITE, TRUE);
	// parse data
	$xml = $p_xmlstring; 
	if (!xml_parse($xp, $xml)){
		die("XML parser error: " .xml_error_string(xml_get_error_code($xp)));
	}
	// destroy parser
	xml_parser_free($xp);
	return;
}
//Cac ham su dung trong _xmlstring_to_array
	function elementBegin($parser, $name, $attributes){
		global $currentTag, $flag, $p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		$currentTag = $name;
		if (strtolower($name) == $p_level1_tag_name){
			$flag = 1;
		}
	}
	// closing tag handler       
	function elementEnd($parser, $name){
		global $currentTag, $flag, $count, $p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		$currentTag = "";
		// set flag if exiting <channel> or <item> block
		if (strtolower($name) == $p_level1_tag_name){
			$count++;
			$flag = 0;
		}
	}
	// character data handler
	function characterData($parser, $data){
		global $currentTag, $flag, $p_arr_items, $count,$p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		$data = trim(htmlspecialchars($data));
		if (_list_have_element($p_level2_tag_name_list,strtolower($currentTag),$p_delimitor)){
			if ($flag == 1){
				$p_arr_items[$count][strtolower($currentTag)] .=$data;
			}
		}	
	}
/********************************************************************************
Ten ham		:_check_permission_on_function
Chuc nang	: Kiem tra quyen thuc hien doi voi mot function
Tham so		
*/
function _check_permission_on_function($p_staff_id, $p_app_code, $p_function_code){
	$parameters = array('p_staff_id' => $p_staff_id, 'p_app_code'=>$p_app_code, 'p_function_code'=>$p_function_code);
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('check_permision_on_function', $parameters);
	}else{
		$str_return =call_user_func_array('check_permision_on_function', $parameters);
	}
	if ($str_return=='true'){
		return true;
	}else{
		return false;
	}	
}	
/********************************************************************************
Ten ham		:_read_file
Chuc nang	:Doc file
Tham so		
********************************************************************************/
function _read_file($p_file_path){
	$v_ret = "";
	$handle = fopen($p_file_path,"r");
	if($handle){
		while(!feof($handle)){
			$v_ret .= fread($handle,1000000);
		}
	}
	return $v_ret;
}	
/********************************************************************************
Ten ham		:_write_file
Chuc nang	:Ghi file
Tham so		
********************************************************************************/
function _write_file($p_file_path, $p_content){
	if (file_exists($p_file_path)) {
	   chmod($p_file_path,0777);	
	}
	$handle = fopen ($p_file_path, "w+");
	if ($handle){
		fwrite($handle, $p_content);
		fclose($handle);
	}	
}	

/********************************************************************************
Ten ham		:_write_log_file
Chuc nang	:Ghi nhat ky ra mot file
Tham so		
********************************************************************************/
function _write_log_file($p_file_path, $p_content){
	$handle = fopen ($p_file_path, "a+");
	if ($handle){
		$content = date("d-m-Y H:i:s") . ":" . $p_content .chr(13).chr(10);
		fwrite($handle, $content);
		fclose($handle);
	}	
}	
/********************************************************************************
Ten ham		:_is_on_menu
Chuc nang	:Kiem tra xem mot modul, function co duoc hien thi tren menu hay khong
Tham so		
-p_item_code: ma cua modul, function can kiem tra
-p_public_item_list_var: ten bien chua danh sach cac modul, ham cong cong
-p_granted_item_list_var: ten bien chua danh sach cac modul, ham duoc ban cho nguoi dang nhap hien tai
*/
function _is_on_menu($p_item_code, $p_public_item_list_var, $p_granted_item_list_var){
	if (_list_have_element($p_public_item_list_var,$p_item_code,',')==true Or _list_have_element($p_granted_item_list_var,$p_item_code,',')==true){
		return true;
	}else{
		return false;	
	}
}	
/********************************************************************************
Ten ham		:view_file_attach
Chuc nang	:Ham hien thi cac ten file dinh kem cua mot van ban tren man hinh danh sach tra cuu van ban
Tham so		:p_item_id 			La id cua PK_DOCUMENT cua mot van ban
			:p_array_file_attach	La array chua cac file dinh kem cua danh sach van ban tren man hinh tra cuu
GT tra ve	:Chuoi HTML chua cac ten va link de xem noi dung van ban hien thi trong cot trich yeu van ban
*/
function _view_file_attach($p_item_id,$p_array_file_attach){
	global $_ISA_IMAGE_URL_PATH;
	global $_ISA_DB_VERSION;
	$strHTML = "";
	$v_count = sizeof($p_array_file_attach);
	if ($v_count > 0){
		for ($ii=0; $ii < $v_count; $ii++) {
			if ($p_item_id == $p_array_file_attach[$ii]['1']){
				$v_doc_content_id = $p_array_file_attach[$ii]['0'];
				$v_file_name = trim($p_array_file_attach[$ii]['2']);
				if(_is_sqlserver()){
					$v_file_url = trim(CONST_DOCUMENT_URL_PATH_FROM_ROOT) . $v_doc_content_id . $v_file_name;
					$v_goto_url = "javascript:filename_onclick('T_DOCLIB_DOCUMENT_CONTENT', 'PK_DOCUMENT_CONTENT', 'C_NAME', 'C_CONTENT'," . strval($v_doc_content_id) . ",'" . $v_file_url ."');";
					$target = '';					
				}
				if(_is_postgresql()){
	 				  if(isset($_ISA_DB_VERSION) && $_ISA_DB_VERSION == "7.1.3"){
								if(isset($v_file_name) && trim($v_file_name)<>""){
									$v_goto_url = CONST_DOCUMENT_URL_PATH_FROM_CURRENT.$p_item_id.$v_file_name;
								}else{
								   $v_goto_url ="";
								}	
					  }else{	
							$v_file_content = trim($p_array_file_attach[$ii]['3']);
							//Lay anh 
							if(isset($v_file_name) && trim($v_file_name)<>""){
								$v_file_url = CONST_DOCUMENT_URL_PATH_FROM_CURRENT.$v_doc_content_id.$v_file_name;
								$v_goto_url = trim($v_file_url);
								if(!file_exists($v_goto_url)){
									$fp=fopen($v_file_url,"w");
									$image_content=pg_unescape_bytea($v_file_content);
									fwrite($fp,$image_content);
									fclose($fp);
								}	
							}else{
							   $v_goto_url ="";
							}
					  }	
  				$target = 'target="_blank"';
				}
				$strHTML = $strHTML . '<br><a href="' . $v_goto_url . '" class="small_link"'.$target.'><img src="'.$_ISA_IMAGE_URL_PATH.'file_attach.jpg" class="normal_image">-' . $v_file_name . '</a>';
			}
		}
	}
	return $strHTML;
}
//********************************************************************************************************************
//Ham _get_row_to_array: chuyen gia tri cua mot RecordSet ra mot mang hai chieu
//Tham so:	p_RecordSet - Ten cua bien RecordSet
//********************************************************************************************************************
function _get_row_to_array($p_RecordSet){
	global $_ISA_DB_TYPE;
	if (_is_sqlserver()) {
		$v_count = @mssql_num_rows($p_RecordSet);
		if ($v_count>0) {
			for ($v_index = 0; $v_index < $v_count; $v_index++) {
				$tmp_Array[$v_index] = @mssql_fetch_array($p_RecordSet);
			}
		}else{
			$tmp_Array = NULL;
		}
	}
	if (_is_postgresql()) {
		$v_count = @pg_num_rows($p_RecordSet);
		if ($v_count>0) {
			for ($v_index = 0; $v_index < $v_count; $v_index++) {
				$tmp_Array[$v_index] = @pg_fetch_array($p_RecordSet);
			}
		}else{
			$tmp_Array = NULL;
		}
	}
	return $tmp_Array;
}
/********************************************************************************
' Ham: get_value_hidden_input()
' Muc dich : Lay gia tri cua mot doi tuong hidden tu mot danh sach luu gia tri cua cac hidden
' $p_hidden_name: la ten cua doi tuong hidden can lay gia tri
' $p_str_save_hidden_input: la danh sach (dang chuoi) luu gia tri cua cac hidden
********************************************************************************/
function get_value_hidden_input($p_hidden_name,$p_str_save_hidden_input) {
	$strHTML="";
	//echo $p_str_save_hidden_input;
	if ($p_str_save_hidden_input <> "") {
		$arr_input=explode(_CONST_LIST_DELIMITOR,$p_str_save_hidden_input);
		for ($i=0; $i<sizeof($arr_input); $i++) {
			//echo $arr_input[$i].'<br>';
			$tmp=explode(_CONST_SUB_LIST_DELIMITOR,$arr_input[$i]);
			if (strtoupper($tmp[0])==strtoupper($p_hidden_name)) {
				$strHTML = $tmp[1];
				break;
			}
		}
	}
	return $strHTML;
}

/********************************************************************************
'Muc dich : Tim va Lay gia tri cua mot phan tu mang
' $pArray: la ten mang
' $pSearchColumnIndex: so thu tu cua cot can tim kiem
' $pRetColumnIndex: so thu tu cua cot chua gia tri tra lai
' $pSearchValue: gia tri can tim kiem
********************************************************************************/
function _get_array_value($pArray, $pSearchColumnIndex, $pRetColumnIndex, $pSearchValue){
	$GetArrayValue = "";
	for($myrow_index = 0;$myrow_index<sizeof($pArray);$myrow_index++){
		if($pSearchValue == $pArray[$myrow_index][$pSearchColumnIndex]){
			$GetArrayValue = $pArray[$myrow_index][$pRetColumnIndex];
		}	
	}
	return $GetArrayValue;
}
//*************************************************************************
//Muc dich:Lay tong so phan tu cua mot danh sach
// $p_string :danh sachs
// $p_delimitor :chuoi ky tu phan cach
//*************************************************************************
function _list_get_len($p_string, $p_delimitor){
	$ret_value =0;
	if(strlen($p_string)<>0){
		$array = explode($p_delimitor, $p_string);
		$ret_value = sizeof($array);
	}
	return  $ret_value;
}
//*************************************************************************
//Muc dich:Them mot phan tu vao danh sach  
//*************************************************************************
function _list_add($p_list, $p_element, $p_delimitor){
	if(strlen($p_list)>0){
		$p_list = $p_list . $p_delimitor . $p_element;
	}else{
		$p_list = $p_element;
	}
	return $p_list;
}
//*************************************************************************
//Muc dich:Kiem tra xem trong danh sach $p_list co chua mot gia tri $p_element hay khong
//*************************************************************************
function _list_have_element($p_list, $p_element, $p_delimitor){
	$p_element = strval($p_element);
	if ($p_list=="" Or $p_element==""){
		return false;
	}
	$ret_value = false;
	if(strlen($p_list)>0){
		$array = explode($p_delimitor, $p_list);
		$ret_value = in_array($p_element,$array);
	}
	return $ret_value;
}
//*************************************************************************
// Muc dich:Lay phan tu dau tien cua mot danh sach
// $p_string :danh sach
// $p_delimitor :chuoi ky tu phan cach
//*************************************************************************
function _list_get_first($p_string, $p_delimitor){
	$ret_value ="";
	if(strlen($p_string)<>0){
		$array = explode($p_delimitor, $p_string);
		$i = sizeof($array);
		if($i>0){
			$ret_value =$array[0];
		}else{
			$ret_value =$p_string;
		}
	}
	return  $ret_value;
}
//*************************************************************************
// Muc dich:Lay phan tu cuoi cung cua mot danh sach
// $p_string :danh sachs
// $p_delimitor :chuoi ky tu phan cach
//*************************************************************************
function _list_get_last($p_string, $p_delimitor){
	$ret_value ="";
	if(strlen($p_string)<>0){
		$array = explode($p_delimitor, $p_string);
		$i = sizeof($array);
		if($i>0){
			$ret_value = $array[$i-1];
		}else{
			$ret_value = $p_string;
		}
	}
	return  $ret_value;
}
//*************************************************************************
// Lay mot phan tu cua danh sach tai mot vi tri cho truoc
//*************************************************************************
function _list_get_at($p_list,$p_index,$p_delimitor) {
	$ret_value = "";
	if (strlen($p_list) == 0){
		return $ret_value;
	}
	$arr_element = explode($p_delimitor,$p_list);
	$ret_value = $arr_element[$p_index];
	return $ret_value;
}
//**********************************************************************************************************
function _write_counter($p_hit_counter) {
	$HTMLstr = "<table width=100% cellpadding=0 cellspacing=0>";
	$HTMLstr = $HTMLstr . "<tr><td height=5></td></tr>";
	$HTMLstr = $HTMLstr . "<tr><td  align=center class=hit_counter_title>" . LABEL_ACCESS_NUMBER . "</td><tr>";
	$v_hit_counter = strval(p_hit_counter);
	$HTMLimg = "";
	$char_index = 1;
	for($char_index;$char_index<strlen($p_hit_counter);$char_index++) {
		$number_char = substr($v_hit_counter,0,1);
		$v_hit_counter = substr($v_hit_counter,strlen($v_hit_counter)-1);
		$HTMLimg = $HTMLimg . "<img src=images/img_" . number_char . ".gif" . ">";
	}	
	$HTMLstr = $HTMLstr . "<tr><td align=center height=25>" . $HTMLimg . "</td><tr>";
	$HTMLstr = $HTMLstr . "</table>";
	$write_counter = $HTMLstr;
	return $write_counter;
}
//'Muc dich : Thay the ki tu xuong dong (chr(13) bang tag <br>
//'********************************************************************************
function _replace_line_feed($p_string) {
	$ret_value = str_replace(chr(13).chr(10) ,"<br>",$p_string);
	return $ret_value;
}

//********************************************************************************************************************
//Ham _generate_time_input : Sinh ra ma HTML de tao mot selectbox nhap GIO va 1 selectbox nhap PHUT
//Tham so: 
// p_hour_field: ten selectbox chua GIO
// p_minute_field: ten selectbox chua PHUT
// p_time_value: chuoi thoi gian dang HH:MM (vi du '09:10') de xac dinh lua chon hien thoi
//Ta se co strFieldName + "Minute" la ten list box the hien phut
//********************************************************************************************************************
function _generate_time_input($p_hour_field,$p_minute_field,$p_time_value){
	$strHTML = "";
	$strHTML = $strHTML . "<select name='" . $p_hour_field . "' class='very_small_selectbox'" . "onKeyDown='change_focus(document.forms(0),this)' optional = 'false' message = 'Ban phai chon thoi gian'>";
	if ($p_time_value <> ""){
		$strAMPM = substr($p_time_value,-2);
		$array = explode(":", $p_time_value);
		$intHour	= $array[0];
		$intMinute = $array[1];
	}else{
		$strAMPM = "";
		$intHour	=  ""; 
		$intMinute = "";
	}	
	
	$strHTML = $strHTML . "<option value='' selected>&nbsp;</option>";
	for ($i=0; $i < 24; $i++){
		if ($p_time_value <> ""){
			if ($strAMPM=="PM"  and $intHour <> 12){
				if ($i == $intHour+12){
					$strSelected="selected"	;
				}else{	
					$strSelected="";
				}		
			}else{
				if ($i == $intHour){ 
					$strSelected="selected";
				}else{	
					$strSelected="";
				}	
			}	
		}	
		$strHour = $i . " gi&#7901;" ;
		$strHTML = $strHTML . "<option value='" . $i . "' " . $strSelected . ">" . $strHour . "</option>";
	}	
	$strHTML = $strHTML . "</select>";
	$strSelected="";
	$strHTML = $strHTML . "<select name='" . $p_minute_field . "' " . "class='very_small_selectbox' onKeyDown='change_focus(document.forms(0),this)' optional = 'false' message = 'Ban phai chon thoi gian'>";
	$strHTML = $strHTML . "<option value='' selected>&nbsp;</option>";
	for ($i=0; $i < 60; $i++) {
		if ($p_time_value <> ""){
			if ($i == ($intMinute/5)*5){
				$strSelected = "selected";
			}else{
				$strSelected = "";
			}
		}		
		$strHTML = $strHTML . "<option value='" . $i . "' " . $strSelected . ">:" . $i . " ph&#250;t</option>";
	}
	$strHTML = $strHTML . "</select>&nbsp;";
	$strHTML = $strHTML . "";
	return $strHTML; 
}
//***************************************************************************************
//'Muc dich : Sinh ra doan ma HTML the hien cac option cuar mot SelectBox 
//'			dua tren mot arr
//'Tham so  :	
//			arr_list	: mang du lieu
//			ValueColumn		: Ten cot lay gia tri gan cho moi option
//			DisplayColumn	: Ten cot lay de hien thi cho moi option
//			SelectedValue	: Gia tri duoc lua chon )
//****************************************************************************************
function _generate_select_option($arr_list,$IdColumn,$ValueColumn,$NameColumn,$SelectedValue) {
	$strHTML = "";
	$i=0;
	$count=sizeof($arr_list);
	for($row_index = 0;$row_index< $count;$row_index++){
		$strID=trim($arr_list[$row_index][$IdColumn]);
		$strValue=trim($arr_list[$row_index][$ValueColumn]);
		$gt=$SelectedValue;
		if($strID != $SelectedValue) {
			$optSelected="";
		} else {
			$optSelected="selected";
		}
		$DspColumn=trim($arr_list[$row_index][$NameColumn]);
		$strHTML.='<option id='.'"'.$strID.'"'.' '.'name='.'"'.$DspColumn.'"'.' ';
		$strHTML.='value='.'"'.$strValue.'"'.' '.$optSelected.'>'.$DspColumn.'</option>';
		$i++;
	}
	return $strHTML;
}
//***************************************************************************************
//'Muc dich : Sinh ra doan ma HTML the hien cac option cua mot SelectBox theo dang cay
//'Tham so  :	
//			arr_list	: mang du lieu
//			ValueColumn		: Ten cot lay gia tri gan cho moi option
//			DisplayColumn	: Ten cot lay de hien thi cho moi option
//			SelectedValue	: Gia tri duoc lua chon )
//****************************************************************************************
function _generate_select_option_by_tree($arr_list,$IdColumn,$ParentIdColumn,$ValueColumn,$NameColumn,$SelectedValue,$HaveNoChildrenColumn,$IsSelectParent,$OtherColumn) {
	global $parent_id,$strPre;
	$strHTML="";
	//Lay ra mang chua cac Object muc ngoai cung 
	$v_count = sizeof($arr_list);
	$v_current_index = 0;
	if (!isset($parent_id)){
		$parent_id = "";
		$strPre="";
	}	
	for($i=0; $i<$v_count; $i++){
		if($arr_list[$i][$ParentIdColumn]==$parent_id){
			$arr_current_list[$v_current_index]=$arr_list[$i];
			$v_current_index++;
		}
	}
	//Tao cac Node muc 2 cua treeview
	for ($i=0; $i<$v_current_index; $i++) {
		// Cac doi tuong child se duoc hien thi trong selectbox voi 4 ky tu trang o dau
		if ($arr_current_list[$i][$ParentIdColumn]!=""){
			$strPre="&nbsp;&nbsp;&nbsp;&nbsp;";
			$strSuf="";
		}else{
			$strPre="";
			$strSuf="";
		}
		// Xac dinh gia tri khi NSD chon1 doi tuong trong selectbox
		if ($arr_current_list[$i][$HaveNoChildrenColumn]=="0"){ // Neu co child
			if (isset($IsSelectParent) && $IsSelectParent==true){ 
				$strValue=trim($arr_current_list[$i][$ValueColumn]);
			}else{
				$strValue=_CONST_LIST_DELIMITOR;
			}	
		}else{
			$strValue=trim($arr_current_list[$i][$ValueColumn]);
		}	
		//  Neu 
		if (isset($OtherColumn) && isset($arr_current_list[$i][$OtherColumn])){
			$strOther = " other='" . $arr_current_list[$i][$OtherColumn] ."'";
		}else{
			$strOther = " other=''";
		}
		
		$parent_id = $arr_current_list[$i][$IdColumn];
		$strID=trim($arr_current_list[$i][$IdColumn]);
		$gt=$SelectedValue;
		if($strID != $SelectedValue) {
			$optSelected="";
		} else {
			$optSelected="selected";
		}
		$DspColumn=$strPre.trim($arr_current_list[$i][$NameColumn]).$strSuf;

		$strHTML.='<option id='.'"'.$strID.'"'.' '.'name='.'"'.$DspColumn.'"'.' ';
		$strHTML.='value='.'"'.$strValue.'"'.' '.$optSelected .' '.$strOther.'>'.$DspColumn.'</option>';
		$strHTML.=_generate_select_option_by_tree($arr_list,$IdColumn,$ParentIdColumn,$ValueColumn,$NameColumn,$SelectedValue,$HaveNoChildrenColumn,$IsSelectParent,$OtherColumn);
	}
	$parent_id = "";
	return  $strHTML;
	
}

//*************************************************************************
//'Muc dich : Lay n tu dau tien cua mot xau ki tu 
//'Tham so  :	
//'	p_string	: xau ban dau
//'	p_word_number	: so ki tu can lay
//'****************************************************************************************
function _get_left_words($p_string, $p_word_number) {
	$ret_value = " ";
	if($p_string=="") {
		return $ret_value;
	}
	$arr_string = explode(" ",$p_string);
	$word_count = 0;
	$word_index=0;
	for($word_index;$word_index < sizeof($arr_string);$word_index++) {
		if(trim($arr_string[$word_index]) !="") {
			$ret_value = $ret_value . " " . $arr_string[$word_index];
			$word_count = $word_count +1;
		}
		if($word_count == $p_word_number) {
			break;
		}
	}
	return $ltrim(ret_value);
}
//'****************************************************************************************
	//'Muc dich : Thay the cac ki tu dac biet trong mot xau boi ki tu khac 
	//'Tham so  :	
	//'	String	: xau ban dau
//'****************************************************************************************
function _replace_bad_char($p_string) {
	$ret_value = stripslashes($p_string);
	$ret_value = str_replace('<','&lt;', $ret_value);
	$ret_value = str_replace('>','&gt;',$ret_value);
	$ret_value = str_replace('"','&#34;', $ret_value);
	$ret_value = str_replace("'",'&#39;', $ret_value);
	//$ret_value = htmlspecialchars($ret_value);
	return $ret_value;
}

function _restore_XML_bad_char($v_html){
	$v_html = str_replace('&amp;','&',$v_html);
	$v_html = str_replace('&quot;','"',$v_html);
	$v_html = str_replace('&#39;',"'",$v_html);
	$v_html = str_replace('&lt;','<',$v_html);
	$v_html = str_replace('&gt;','>',$v_html);
	$v_html = str_replace('&#34;','"',$v_html);
	$v_html = htmlspecialchars($v_html);
	return $v_html;
}
function _replace_XML_bad_char($v_html){
	$v_html = stripslashes($v_html);
	$v_html = str_replace('&','&amp;',$v_html);
	$v_html = str_replace('"','&quot;',$v_html);
	$v_html = str_replace('<','&lt;',$v_html);
	$v_html = str_replace('>','&gt;',$v_html);
	$v_html = str_replace("'",'&#39;', $v_html);
	return $v_html;
}

//'********************************************************************************************************************
//'Ham AddEmptyRow : tra lai xau chua lenh HTML dien them cac dong trang vao mot table
//'********************************************************************************************************************
function _add_empty_row($pCurrentRow,$pTotalRow,$pCurrentStyleName,$pNextStyleName,$pTotalColumn) {
	if($pCurrentRow>=$pTotalRow) {
		return "";
	}
	$strHTML = "";
	$style_name = $pCurrentStyleName;
	for($ii=$pCurrentRow+1;$ii<=$pTotalRow;$ii++) {
		if($style_name == $pCurrentStyleName) { 
			$style_name = $pNextStyleName;
		} else {
			$style_name = $pCurrentStyleName;
		}
		$strHTML.='<tr class='.'"'."$style_name".'"'.'>';
		for($jj=1;$jj<=$pTotalColumn;$jj++) {		
			$strHTML.="<td>&nbsp;</td>";
		}
		$strHTML.="</tr>";
	}
	return $strHTML;
}
/*
Chuc nang :	Sinh ra cac nam trong mot Selectbox
Tham so :	
		+	$p_begin_year Nam bat dau 
		+	$p_end_year nam ket thuc
		+	$p_current_year Nam hien hoi
*/
function _generate_year_input($p_begin_year,$p_end_year,$p_current_year){
	$strHTML = "";
	for($i = $p_begin_year; $i<= $p_end_year; $i++){
		if($i == $p_current_year){
			$str_selected = "selected";
		}else{
			$str_selected = "";
		}
		$strHTML.='<option id='.'"'.$i.'"'.' '.'name='.'"'.$i.'"'.' ';
		$strHTML.='value='.'"'.$i.'"'.' '.$str_selected.'>'.'&nbsp;'.$i.'&nbsp;'.'</option>';
	}
	return $strHTML;
}
/*
Chuc nang:	Sinh ra cac tuan trong mot nam
Tham so:
		+	$p_year (int): Xac dinh nam. Vidu: $p_year=2004
		+	$p_number_of_week (int) So tuan se duoc hien thi trong combo_box:
				= -1 : hien thi tat ca cac tuan trong nam
				=  0 : Hien thi tu tuan dau toi tuan hien thoi
				n >0 : Hien thi n tuan tinh tu tuan hien thoi (neu muon hien thi tu tuan hien thoi den het nam thi n>=53)
		+	$p_current_week (int): Tuan hien thoi hien dang duoc chon trong combo_box. Vi du: $p_current_week=33
*/
function _generate_weeks_of_year($p_year, $p_number_of_week, $p_current_week){
	$p_week_name = "Tu&#7847;n ";
	//Xac dinh ngay 1/1 la thu may
	$v_timestamp = mktime(0,0,0,1,1,$p_year);
	$v_fisrt_day_of_year = date('w',$v_timestamp);
	if ($v_fisrt_day_of_year == 0) {$v_fisrt_day_of_year = 7;}
	//Xac dinh ngay dau cua tuan thu nhat la ngay nao, ngay nay co the thuoc nam truoc.
	if ($v_fisrt_day_of_year <= 4){
		$v_day = 2 - $v_fisrt_day_of_year;
	}else{
		$v_day = 9 - $v_fisrt_day_of_year;
	}
	$strHTML = "";
	for ($i=1; $i <= 53; $i++){
		$v_fisrt_day_of_week = date("d/m/Y",mktime(0,0,0,1,$v_day,$p_year));
		$v_last_day_of_week = date("d/m/Y",mktime(0,0,0,1,$v_day+6,$p_year));
		$v_text = $p_week_name.$i.' ('.$v_fisrt_day_of_week.' --> '.$v_last_day_of_week.')';
		if($i == $p_current_week){
			$str_selected = "selected";
		}else{
			$str_selected = "";
		} 
		// Hien thi toan bo (ngam dinh)
		$add_HTML=true;
		if ($p_number_of_week == 0){ // Hien thi tu tuan 1 toi tuan hien thoi
			if ($i > date("W")){break;}
		}
		if ($p_number_of_week > 0){ // Hien thi n tuan tu tuan hien thoi tro di
			if ($i < date("W")){
				$add_HTML=false;
			}else{
				$add_HTML=true;
				if ($i - date("W") >= $p_number_of_week){break;}
			}
		}
		if ($add_HTML == true){
			$strHTML.='<option id='.'"'.$i.'"'.' name='.'"'.$i.'"';
			$strHTML.=' value='.'"'.$i.'"';
			$strHTML.=' fisrt_day='.'"'.$v_fisrt_day_of_week.'"';
			$strHTML.=' last_day='.'"'.$v_last_day_of_week.'"';
			$strHTML.=' '.$str_selected.'>' .$v_text.'</option>';
		}
		$v_day = $v_day + 7;
		//Kiem tra tuan sau da sang tuan thu nhat cua nam sau chua.
		if (date("W",mktime(0,0,0,1,$v_day,$p_year)) == 1){break;}
	}
	return $strHTML;
}
/*
Ham _generate_days_on_week_of_year
Chuc nang:	Sinh ra cac ngay trong tuan (tu thu 2 den chu nhat) cua nam nao do.
			Ham nay co the sinh ra select box voi noi dung cua cac option la:
				-Chi co thu (khong kem theo ngay cu the): Vi du Thu 2
				-Co ca thu va ngay cu the: Vi du: Thu 2 - Ngay 16/18/2004
			Ngoai ra, trong select_box tao ra con dua them thuoc tinh "day" va "date" de phuc vu viec lay thong tin cap nhat
				-Thuoc tinh "day" : La chuoi chua ngay trong tuan: "Thu 2" ....... "Chu nhat"
				-Thuoc tinh "date": La chuoi chua ngay cu the trong nam dang "dd/mm/yyyy"
			Luu y: 	Ham nay chi tao ra cac option ben trong, day la dang chuan tao ra cac select box
Tham so:
		+	$p_year (int): Xac dinh nam. Vi du: $p_year=2004
		+	$p_week (int): Xac dinh tuan: Vi du: $p_week=35
		+	$p_full_day (logical):
			Gia tri "true" : Neu muon tao noi dung cac option cua select box co day du Thu - Ngay (Thu 2 - Ngay 16/08/2004)
			Gia tri "false": Neu muon tao noi dung cac option cua select box chi co Thu (Thu 2)
		+	$p_current_day: Ngay dang duoc chon hien thi trong select box. Vi du $p_current_day=1 (ngay "Thu Ba" se hien thi trong select box)
Tra ve: Chuoi HTML tao ra cac option cua select box tao cac ngay trong tuan
Vi du:  Doan ma goi ham _generate_days_on_week_of_year() nhu sau:
		<?php
		$v_year=2004;
		$v_week=34;
		$p_full_day=true;
		$p_current_day=1?>
		<select name="sel_day" class="normal_selectbox" onChange="select_week_for_register(this)">
			<option id=""></option>
			<?php echo _generate_days_on_week_of_year($v_year, $v_week, true, $p_current_day);?>
		</select>
*/
function _generate_days_on_week_of_year($p_year, $p_week, $p_full_day,$p_current_day){
	//Xac dinh ngay 1/1 cua nam la thu may
	$v_timestamp = mktime(0,0,0,1,1,$p_year);
	$v_fisrt_date_of_year = date('w',$v_timestamp);
	if ($v_fisrt_date_of_year == 0) {$v_fisrt_date_of_year = 7;}
	//Xac dinh ngay dau cua tuan thu nhat la ngay nao, ngay nay co the thuoc nam truoc.
	if ($v_fisrt_date_of_year <= 4){
		$v_date = 2 - $v_fisrt_date_of_year;
	}else{
		$v_date = 9 - $v_fisrt_date_of_year;
	}
	$strHTML = "";
	for ($i=1; $i <= 53; $i++){
		if($i == $p_week){
			for ($j=0; $j<7; $j++){
				$v_day_on_week =  _get_day_of_week_in_vn(date("w",mktime(0,0,0,1,$v_date+$j,$p_year)));
				$v_date_on_week = date("d/m/Y",mktime(0,0,0,1,$v_date+$j,$p_year));
				//Tao chuoi HTML sinh ra cac option trong tuan
				if ($j == $p_current_day){
					$str_selected = "selected";
				}else{
					$str_selected = "";
				}
				if ($p_full_day){
					$v_text = $v_day_on_week.' ('.$v_date_on_week.')';
				}else{
					$v_text = $v_day_on_week;
				}
				$strHTML.='<option id='.'"'.$j.'"'.' name='.'"'.$j.'"';
				$strHTML.=' value='.'"'.$j.'"';
				$strHTML.=' day='.'"'.$v_day_on_week.'"';
				$strHTML.=' date='.'"'.$v_date_on_week.'"';
				$strHTML.=' '.$str_selected.'>' .$v_text.'</option>';
			}
			break;
		}
		$v_date = $v_date + 7;
		//Kiem tra tuan sau da sang tuan thu nhat cua nam sau chua.
		if (date("W",mktime(0,0,0,1,$v_date,$p_year)) == 1){break;}
	}
	return $strHTML;
}
/*
Chuc nang: Lay mot ngay bat ky trong tuan cua nam
Tham so:
		+	$p_year (int): Xac dinh nam. Vidu: $p_year=2004
		+	$p_number_of_week (int) Tuan thu may trong nam, Vi du: $p_number_of_week=33
		+	$p_order_date (int): Lay ngay thu may trong tuan
			Thu 2: = 0 .......... Chu nhap = 6
*/
function _get_any_date_on_week_of_year($p_year, $p_number_of_week, $p_order_date){
	//Xac dinh ngay 1/1 la thu may
	$v_timestamp = mktime(0,0,0,1,1,$p_year);
	$v_fisrt_day_of_year = date('w',$v_timestamp);
	if ($v_fisrt_day_of_year == 0) {$v_fisrt_day_of_year = 7;}
	//Xac dinh ngay dau cua tuan thu nhat la ngay nao, ngay nay co the thuoc nam truoc.
	if ($v_fisrt_day_of_year <= 4){
		$v_day = 2 - $v_fisrt_day_of_year;
	}else{
		$v_day = 9 - $v_fisrt_day_of_year;
	}
	for ($i=1; $i <= 53; $i++){
		if($i == $p_number_of_week){
			$v_date_of_week = date("d/m/Y",mktime(0,0,0,1,$v_day+$p_order_date,$p_year));
			break;
		} 
		$v_day = $v_day + 7;
		//Kiem tra tuan sau da sang tuan thu nhat cua nam sau chua.
		if (date("W",mktime(0,0,0,1,$v_day,$p_year)) == 1){break;}
	}
	return $v_date_of_week;
}
/********************************************************************************
Muc dich : Tra lai ngay trong tuan (Tieng Viet)
********************************************************************************/
function _get_day_of_week_in_vn($pDate){
	$RetStr = "";
	switch($pDate){
		case 0;
			$RetStr = "Ch&#7911; nh&#7853;t";
			break;
		case 1;
			$RetStr = "Th&#7913; hai";
			break;
		case 2;
			$RetStr = "Th&#7913; ba";
			break;
		case 3;
			$RetStr = "Th&#7913; t&#432;";
			break;
		case 4;
			$RetStr = "Th&#7913; n&#462;m";
			break;
		case 5;
			$RetStr = "Th&#7913; s&#225;u";
			break;
		case 6;
			$RetStr = "Th&#7913; b&#7843;y";
	}
	return $RetStr;
}
//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang dd/mm/yyyy
//'Tham so $p_yyyymmyy : la thoi gian dang chuoi
//'Ket qua tra ve : chuoi dang dd/mm/yyyy(De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_ddmmyyyy($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("d/m/Y", strtotime($p_yyyymmdd));
}
//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang dd/mm/yyyy hh:mm
//'Tham so $p_yyyymmyy : la thoi gian dang chuoi
//'Ket qua tra ve : chuoi dang dd/mm/yyyy hh:mm (De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_ddmmyyyyhhmm($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("d/m/Y H:i", strtotime($p_yyyymmdd));
}
//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang yyyymmdd thanh chuoi dang dd/mm/yyyy hh:mm:ss
//'Tham so $p_yyyymmyy : la thoi gian dang chuoi
//'Ket qua tra ve : chuoi dang dd/mm/yyyy hh:mm:ss (De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_ddmmyyyyhhmmss($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("d/m/Y H:i:s", strtotime($p_yyyymmdd));
}

//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang yyyymmdd thanh chuoi dang yyyy-mm-dd hh:mm:ss
//'Tham so $p_yyyymmyy : la thoi gian dang chuoi
//'Ket qua tra ve : chuoi dang dd/mm/yyyy hh:mm:ss (De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_yyyymmddhhmmss($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("Y/m/d H:i:s", strtotime($p_yyyymmdd));
}

//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang yyyymmdd thanh chuoi dang hh:mm
//'Tham so $p_yyyymmyy : la thoi gian dang chuoi
//'Ket qua tra ve : chuoi hh:mm(De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_hhmm($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("H:i", strtotime($p_yyyymmdd));
}
//'********************************************************************************
//'Muc dich : chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang hh:mm:ss
//'Tham so $p_yyyymmyy : la thoi gian dang yyyymmdd
//'Ket qua tra ve : chuoi hh:mm:ss (De hien thi tren man hinh)
//'********************************************************************************
function _yyyymmdd_to_hhmmss($p_yyyymmdd) {
	if (is_null($p_yyyymmdd) Or trim($p_yyyymmdd)=="")
		return "";
	return date("H:i:s", strtotime($p_yyyymmdd));
}
//'********************************************************************************
//'Muc dich : chuyen doi ngay dang mm/dd/yyyy thanh ngay dang yyyy/mm/dd
//'Tham so $p_ddmmyyyy : la chuoi dang dd/mm/yyyy( chuoi chu khong phai date object )
//'Ket qua tra ve : chuoi dang yyyy/mm/dd(De dua vao csdl)
//'********************************************************************************
function _ddmmyyyy_to_yyyymmdd($p_ddmmyyyy) {
	$date=NULL;
	$date_arr="";
	$del="";
	if(strlen($p_ddmmyyyy)==0 or is_null($p_ddmmyyyy)) {
		$date="";
	return $date;
	}
	if(strpos($p_ddmmyyyy,"-")<=0 and strpos($p_ddmmyyyy,"/")<=0) {
		$date="";
	return $date;	
	}
	if(strpos($p_ddmmyyyy,"-")>0) { 
			$del="-";
	}
	if(strpos($p_ddmmyyyy,"/")>0) {
			$del="/";
	}
	$arr=explode(" ",$p_ddmmyyyy);
	if($arr[0] <> "") {
		$date_arr=explode($del,$arr[0]);
		if(sizeof($date_arr)<>3) {
			$date=NULL;
			return $date;	
		} else {
			$date=$date_arr[2]."-".$date_arr[1]."-".$date_arr[0];
			return $date;	
		}
	}
	return $date;
}
//'********************************************************************************
//'Muc dich : Lay so ngau nhien
//'********************************************************************************
function _get_randon_number() {
	$ret_value = mt_rand(1,1000000);
	return $ret_value;
}

//'********************************************************************************
//'Muc dich : chuyen doi mot gia tri decimal thanh gia tri binary
//'********************************************************************************
function _dec_bin($pValue){
	return pack("H2", str_pad(dechex($pValue),2, "0", STR_PAD_LEFT));
}
//'********************************************************************************
//'Muc dich : doc va hien thi noi dung cua mot file duoi dang so hexa
//'********************************************************************************
function _get_file_content_in_hexa($pFile){
	$v_datastring = file_get_contents($pFile);
	$v_data = unpack("H*hex", $v_datastring);
	$v_datastring  = "0x" . $v_data['hex'];
	return $v_datastring;
}

//'********************************************************************************
//'Muc dich : copy file
//'********************************************************************************
function _copy_file($pFromFile, $pToFile){
	$v_datastring = file_get_contents($pFromFile);
	$handle = fopen($pToFile, "wb");
	fwrite($handle, $v_datastring);
	fclose($handle);
}

//'********************************************************************************
//'Muc dich : Doc van ban tu mot column dang ntext va tra lai gia tri la toan bo doan van ban do
// Tham so:
//   - pTable: ten table chua du lieu
//   - pIDColumn: ten column chua ID cua row can lay du lieu (pFileIDColumn thuong la Primary key column)
//   - pTextColumn: ten column chua noi dung cua file
//   - pID: xac dinh file can lay du lieu
// Tra lai: doan van ban doc tu CSDL
//'********************************************************************************
function _get_text_from_database($pTable, $pIDColumn, $pTextColumn, $pID) {
	global $ado_conn;
	global $_ISA_DB_TYPE;
	if (_is_sqlserver()){
		/*
		$cmd = mssql_init("Sp_GetText", $conn);
		mssql_bind($cmd, "@p_table", $pTable, SQLVARCHAR);
		mssql_bind($cmd, "@p_id_column", $pIDColumn, SQLVARCHAR);
		mssql_bind($cmd, "@p_text_column", $pTextColumn, SQLVARCHAR);
		mssql_bind($cmd, "@p_id", $pID, SQLINT4);
		$result = mssql_execute($cmd);
		*/
		$v_sql_string = " SET NOCOUNT ON; Select ".$pTextColumn." As TEXT From ".$pTable." Where ".$pIDColumn."=".$pID;
		$rs = _adodb_exec_update_delete_sql($v_sql_string);
		$v_count = sizeof($rs);
		$text = $rs['TEXT'];
		/*
		if (@mssql_num_rows($result)>0){
			do {
				while ($file_rec = @mssql_fetch_array($result)){
					if (mssql_num_rows($result)>0){
						$text = $text . $file_rec[$pTextColumn];
					}	
				}
			} while (mssql_next_result($result));
		}
		*/	
	}	
	if (_is_postgresql()){
		$strsql = "Select " . trim($pTextColumn)  . " From " . trim($pTable) . " Where " . trim($pIDColumn) . "=" . strval($pID);
		$result = pg_query($conn, $strsql);
		$file_rec = @pg_fetch_array($result, 0);
		$text = $file_rec[$pTextColumn];
	}	
	return $text;
}	

//'********************************************************************************
//'Muc dich : 
// - Ghi du lieu cua mot doan van ban (kieu ntext) vao CSDL
// Tham so:
//   - pTable: ten table chua du lieu
//   - pTextColumn: ten column chua du lieu text
//   - pText: doan van ban can luu vao CSDL
//'********************************************************************************
function _save_text_to_database($pTable, $pTextColumn, $pText) {
	global $ado_conn;
	global $_ISA_DB_TYPE;
	$v_new_id = 0;
	if (_is_sqlserver()){
		$text = str_replace('"','&#34;', $pText);
		$text = str_replace("'",'&#39;', $text);
		$v_sql_string = " SET NOCOUNT ON; Insert into " . trim($pTable). "(" . trim($pTextColumn) . ")";
		$v_sql_string.= " values('" . $text ."')";
		$v_sql_string.= " Select @@IDENTITY NEW_ID";
		$rs = _adodb_exec_update_delete_sql($v_sql_string);
		//mssql_query($v_strsql);
		//$v_strsql = " Select @@IDENTITY NEW_ID";
		//$result = mssql_query($v_strsql);
		//$rs_new_id = @mssql_fetch_array($result);
		//$v_new_id = intval($rs_new_id['NEW_ID']);
		//$ado_conn->Execute($v_sql_string)
		$v_new_id = $rs['NEW_ID'];
		//$ado_conn->UpdateClob($pTable,$pTextColumn,$pText,$pIDColumn . "=" . $v_new_id); 
		return $v_new_id;
	}	
	if (_is_postgresql()){
		$strsql = "Select " . trim($pFileTypeColumn) . "," . trim($pFileNameColumn) . "," . trim($pFileNameColumn) . " From " . trim($pTable) . " Where " . trim($pFileIDColumn) . "=" . strval($pFileID);
		$result = pg_query($conn, $strsql);
		$file_rec = @pg_fetch_array($result, 0);
	}	
}

function _save_XML_to_database($pTable, $pIDColumn, $pTextColumn, $pText) {
	global $ado_conn;
	global $_ISA_DB_TYPE;
	$v_new_id = 0;
	if (_is_sqlserver()){
		$v_sql_string = " SET NOCOUNT ON; Insert into " . trim($pTable). "(" . trim($pTextColumn) . ")";
		$v_sql_string.= " values('')";
		$v_sql_string.= " Select @@IDENTITY NEW_ID";
		$rs = _adodb_exec_update_delete_sql($v_sql_string);
		//$v_strsql = "insert into " . trim($pTable); 
		//$v_strsql = $v_strsql . "(" . trim($pTextColumn) . ")";
		//$v_strsql = $v_strsql . " values(null)";
		//mssql_query($v_strsql);
		//$v_strsql = " Select @@IDENTITY NEW_ID";
		//$result = mssql_query($v_strsql);
		//$rs_new_id = @mssql_fetch_array($result);
		//$v_new_id = intval($rs_new_id['NEW_ID']);
		$v_new_id = $rs['NEW_ID'];
    	$ado_conn->UpdateClob($pTable,$pTextColumn,$pText,$pIDColumn . "=" . $v_new_id); 
		return $v_new_id;
	}	
}

//'********************************************************************************
//'Muc dich : 
// - Ghi du lieu cua mot file vao CSDL 
// - Copy $pFromFile -> $pToFile
// Tham so:
//   - pTable: ten table chua du lieu
//   - pFileNameColumn: ten column chua ten file
//   - pFileContentColumn: ten column chua noi dung cua file
//   - pFileUrl: duong dan URL cua file 
//'********************************************************************************
function _save_file_to_database($pTable, $pFileNameColumn, $pFileContentColumn, $pFileUrl,$pColumnNameList='',$pColumnValueList='',$pdelimitor='') {
	global $conn;
	global $_ISA_DB_TYPE;
	global $_ISA_SERVER_NAME,$_ISA_DB_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD;
	$v_new_id = 0;
	if (_is_sqlserver()){
		$conn=@mssql_connect($_ISA_SERVER_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD) or die(_CONST_DB_CONNECT_ERROR);
		@mssql_select_db($_ISA_DB_NAME);
		$path_parts = pathinfo($pFileUrl);
		$v_file_name = _replace_bad_char($path_parts["basename"]);
		$v_datastring = _get_file_content_in_hexa($pFileUrl);
		$v_sql_string = "insert into " . trim($pTable); 
		$v_sql_string = $v_sql_string . "(" . trim($pFileNameColumn) . "," . trim($pFileContentColumn) ;
		if($pColumnNameList != '' && $pColumnValueList != '' && $pdelimitor !='' && !is_null($pColumnNameList) && !is_null($pColumnValueList) && !is_null($pdelimitor)){
			$arr_column_name = split($pdelimitor,$pColumnNameList);
			for($i=0; $i<sizeof($arr_column_name); $i++){
				$v_sql_string = $v_sql_string . "," . trim($arr_column_name[$i]);
			}
		}
		$v_sql_string = $v_sql_string . ")";
		$v_sql_string = $v_sql_string . " values('" . $v_file_name . "'," . $v_datastring ;
		if($pColumnNameList != '' && $pColumnValueList != '' && $pdelimitor !='' && !is_null($pColumnNameList) && !is_null($pColumnValueList) && !is_null($pdelimitor)){
			$arr_column_value = split($pdelimitor,$pColumnValueList);
			for($i=0; $i<sizeof($arr_column_value); $i++){
				$v_sql_string = $v_sql_string .",'". trim($arr_column_value[$i])."'";
			}
		}
		$v_sql_string = $v_sql_string . ")";
		@mssql_query($v_sql_string);
		$v_strsql = " Select @@IDENTITY NEW_ID";
		$result = @mssql_query($v_strsql);
		$rs_new_id = @mssql_fetch_array($result);
		$v_new_id = intval($rs_new_id['NEW_ID']);		
	}	
	if (_is_postgresql()){
		$strsql = "Select " . trim($pFileTypeColumn) . "," . trim($pFileNameColumn) . "," . trim($pFileNameColumn) . " From " . trim($pTable) . " Where " . trim($pFileIDColumn) . "=" . strval($pFileID);
		$result = pg_query($conn, $strsql);
		$file_rec = @pg_fetch_array($result, 0);
	}	
	return $v_new_id;
}
//'********************************************************************************
//'Muc dich : Doc du lieu cua mot file dang Binary tu CSDL va tao file va tra lai ten file
// Tham so:
//   - pTable: ten table chua du lieu
//   - pFileIDColumn: ten column chua ID cua row can lay du lieu (pFileIDColumn thuong la Primary key column)
//   - pFileNameColumn: ten column chua ten file
//   - pFileContentColumn: ten column chua noi dung cua file
//   - pFileID: xac dinh file can lay du lieu
//   - pFileURL: la duong dan URL cua file se duoc tao lap
// Tra lai: ten file neu co du lieu, "" neu khong co du lieu
//'********************************************************************************
function _create_file_from_database($pTable, $pFileIDColumn, $pFileNameColumn, $pFileContentColumn, $pFileID, $pFileUrl) {
	global $conn;
	global $_ISA_DB_TYPE;
	global $_ISA_SERVER_NAME,$_ISA_DB_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD;
	$return_value = 0;
	if (_is_sqlserver()){
		$conn=mssql_connect($_ISA_SERVER_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD) or die(_CONST_DB_CONNECT_ERROR);
		mssql_select_db($_ISA_DB_NAME);
		$cmd = mssql_init("Sp_GetFileContent", $conn);
		mssql_bind($cmd, "@p_table", $pTable, SQLVARCHAR);
		mssql_bind($cmd, "@p_file_id_column", $pFileIDColumn, SQLVARCHAR);
		mssql_bind($cmd, "@p_file_name_column", $pFileNameColumn, SQLVARCHAR);
		mssql_bind($cmd, "@p_file_content_column", $pFileContentColumn, SQLVARCHAR);
		mssql_bind($cmd, "@p_file_id", $pFileID, SQLINT4);
		$result = mssql_execute($cmd);
		//$file_rec = @mssql_fetch_array($result);
		if (@mssql_num_rows($result)>0){
			$handle = fopen ($pFileUrl, "wb");
			do {
				while ($file_rec = @mssql_fetch_array($result)){
					if (mssql_num_rows($result)>0){
						$return_value = 1;
						$file_content = $file_rec[$pFileContentColumn];
						fwrite($handle, $file_content);
					}	
				}
			} while (mssql_next_result($result));
			fclose($handle);
		}	
	}	
	if (_is_postgresql()){
		$strsql = "Select " . trim($pFileTypeColumn) . "," . trim($pFileNameColumn) . "," . trim($pFileNameColumn) . " From " . trim($pTable) . " Where " . trim($pFileIDColumn) . "=" . strval($pFileID);
		$result = pg_query($conn, $strsql);
		$file_rec = @pg_fetch_array($result, 0);
	}	
	return $return_value;
}
//'********************************************************************************
//Ham _built_XML_tree
//Muc dich: Xay dung cau truc cay du lieu (TreeView) dong khong gioi han cap do. Gom 2 loai doi tuong la:
//	-ContainerOBJ: la dang container object chua cac object khac (Vi du: cac Phong ban, cac to chuc khac...)
//	-LeafOBJ: la dang leaf object cuoi cung chua cac thong tin, khong chua cac object khac (Vi du: cac nhan vien...)
//Phuong phap xay dung:
//	-Su dung dang du lieu XML de xay dung cau truc dong tu mot mang du lieu 2 chieu.
//	-Su dung phuong phap de quy de xay dung cau truc dong
//Input:
//	1. $arr_all_list: Mang chua cac ban ghi cua bang can xay dung cay du lieu. Mang co cau truc sau
//		-Phan tu thu 1 (index 0): ID cua Object
//		-Phan tu thu 2 (index 1): ID cua Object cha chua Object ID
//		-Phan tu thu 3 (index 2): Ma viet tat cua Object
//		-Phan tu thu 4 (index 3): Ten cua Object
//		-Phan tu thu 5 (index 4): Loai Object (0 - ContainerOBJ, 1 - LeafOBJ) 
//	2. $root_text: Dong text o goc cay thu muc
//	3. $exception_brand_id: ID cua doi tuong can hieu chinh
//		Duoc dung trong truong hop muon hieu chinh mot doi tuong dang ConrainerOBJ
//		Khi hieu chinh can chon lai ParentOBJ tu ModalDialog, khi do nhanh cua Object dang hieu chinh (ke ca cac ChildOBJ)
//		se khong xuat hien tren cay thu muc (tranh loi mot Object nay vua la cha dong thoi vua la con cua Object khac)
//		Gia tri cua ID nay: -1 neu tao tat ca cay, >0 neu tao thieu nhanh do.
//Output:
//	Tra ve mot chuoi dang XML cua cau truc cay du lieu
//'********************************************************************************
function _built_XML_tree($arr_all_list,$exception_brand_id,$show_control,$opening_node_img_name,$closing_node_img_name,$leaf_node_img_name,$select_parent,$list_id_checked="",$object_name="") {
	global $_ISA_IMAGE_URL_PATH;
	global $_MODAL_DIALOG_MODE;
	$strTop='<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$strTop.='<treeview title="Treeview" >'."\n";
	$strTop.="<custom-parameters>"."\n";
	$strTop.='<param name="shift-width" value="10"/>' . "\n";
	$strTop.='<param name="opening_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$opening_node_img_name.'"/>'."\n";
	$strTop.='<param name="closing_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$closing_node_img_name.'"/>'."\n";	
	$strTop.='<param name="leaf_node_img_name" value="'.$_ISA_IMAGE_URL_PATH . $leaf_node_img_name.'"/>' . "\n";
	$strTop.='<param name="modal_dialog_mode" value="'.$_MODAL_DIALOG_MODE.'"/>' . "\n";
	$strTop.='<param name="show_control" value="'.$show_control.'"/>'."\n";
	$strTop.='<param name="select_parent" value="'.$select_parent.'"/>'."\n";
	$strTop.="</custom-parameters>"."\n";	
	$strBottom= "</treeview>";
	$strXML="";
	$parent_id=NULL;
	//Lay ra mang chua cac Object muc ngoai cung 
	$v_count = sizeof($arr_all_list);
	$v_current_index = 0;
	for($i=0; $i<$v_count; $i++){
		if (strcasecmp(trim($arr_all_list[$i][1]),$parent_id)==0){
		//if($arr_all_list[$i][1]==$parent_id){
			$arr_current_list[$v_current_index][0]=$arr_all_list[$i][0];//PK
			$arr_current_list[$v_current_index][1]=$arr_all_list[$i][1];//FK
			$arr_current_list[$v_current_index][2]=htmlspecialchars($arr_all_list[$i][2]);//C_CODE
			$arr_current_list[$v_current_index][3]=htmlspecialchars($arr_all_list[$i][3]);//C_NAME
			$arr_current_list[$v_current_index][4]=$arr_all_list[$i][4];//C_TYPE
			$arr_current_list[$v_current_index][5]=$arr_all_list[$i][5];//C_LEVEL
			$v_current_index++;
		}
	}
	//Tao cac Node muc 2 cua treeview
	for ($i=0; $i<$v_current_index; $i++) {
		$v_current_id = $arr_current_list[$i][0];//PK
		$v_parent_id = 0;// id cua cha (FK =0)
		$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
		$v_current_name = htmlspecialchars($arr_current_list[$i][3]);	//C_NAME
		$v_current_type = $arr_current_list[$i][4];//C_TYPE
		$v_current_level = $arr_current_list[$i][5];//C_LEVEL
		//Kiem tra ID neu no khong la $exception_brand_id thi moi tao (tranh truong hop "vua la chau vua la cha" giua hai phan tu)
		if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
		//if($v_current_id!=$exception_brand_id){
			$arr_id_list = explode(",",$list_id_checked);
			$value_checked = 0;
			for ($j=0;$j<sizeof($arr_id_list);$j++){
				if ($arr_id_list[$j]==$v_current_id){
					$value_checked = $v_current_id;
				}				
			}
			$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" value_check="'.$value_checked.'" type="'.$v_current_type.'" parent_id="'.$v_parent_id.'" xml_tag_in_db_name="'.$object_name.'" level="'.$v_current_level.'" >'."\n";
			//Tao ra cac Node  con cua tree view
			$strXML.=_built_child_node($arr_all_list,$v_current_level,$v_current_id,$exception_brand_id,$list_id_checked,$object_name);
			$strXML.="</folder>"."\n";
		}
	}
	return  $strTop. $strXML . $strBottom;
	
}
//Xay dung cac node con cua mot doi tuong
function _built_child_node($arr_all_list,$current_level,$parent_id,$exception_brand_id,$list_id_checked="",$object_name=""){
	$strXML="";
	$v_current_index = 0;
	$v_count = sizeof($arr_all_list);
	for($j=0;$j<$v_count;$j++){
	//Tim nhung thang con
		if ((strcasecmp(trim($arr_all_list[$j][1]),$parent_id)==0) && ($arr_all_list[$j][5]>=$current_level)){
		//if (($arr_all_list[$j][1]==$parent_id) and ($arr_all_list[$j][5]>=$current_level)){
			$arr_current_list[$v_current_index][0]=$arr_all_list[$j][0];//PK
			$arr_current_list[$v_current_index][1]=$arr_all_list[$j][1];//FK
			$arr_current_list[$v_current_index][2]=htmlspecialchars($arr_all_list[$j][2]);//C_CODE
			$arr_current_list[$v_current_index][3]=htmlspecialchars($arr_all_list[$j][3]);//C_NAME			
			$arr_current_list[$v_current_index][4]=$arr_all_list[$j][4];//C_TYPE
			$arr_current_list[$v_current_index][5]=$arr_all_list[$j][5];//C_LEVEL
			$v_current_index++;
		}
	}
	//Truong hop mang $arr_current_list rong thi ket thuc de quy
	if($v_current_index <= 0){return;}
	for ($i=0; $i<$v_current_index; $i++){
		$v_current_id = $arr_current_list[$i][0];//PK
		$v_parent_id = $arr_current_list[$i][1];//FK	
		$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
		$v_current_name = htmlspecialchars($arr_current_list[$i][3]);//C_NAME
		$v_current_type = $arr_current_list[$i][4];//C_TYPE
		$v_current_level = $arr_current_list[$i][5];//C_LEVEL
		//Kiem tra ID neu no khong la $exception_brand_id thi moi tao
		if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
		//if($v_current_id!=$exception_brand_id){
			$arr_id_list = explode(",",$list_id_checked);
			$value_checked = 0;
			for ($j=0;$j<sizeof($arr_id_list);$j++){
				if ($arr_id_list[$j]==$v_current_id){
					$value_checked = $v_current_id;
				}				
			}
			$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" value_check="'.$value_checked.'" type="'.$v_current_type.'" parent_id="'.$parent_id.'" xml_tag_in_db_name="'.$object_name.'" level="'.$v_current_level.'" >'."\n";
			if ($v_current_type=='0'){
				$strXML.=_built_child_node($arr_all_list,$v_current_level,$v_current_id,$exception_brand_id,$list_id_checked,$object_name);
			}
			$strXML.="</folder>"."\n";
		}
	}
	return  $strXML;
}
//********************************************************************************************************************
//Ham _built_XML_tree_by_order : Ham nay co chuc nang tuong tu ham _built_XML_tree. Diem khac biet la cac phan tu cua mang
// arr_all_list da duoc sap xep san theo cau truc hinh cay. Do do se khong phai su dung DE QUY de tao xau XML
//********************************************************************************************************************
function _built_XML_tree_by_order($arr_all_list,$exception_brand_id,$show_control,$opening_node_img_name,$closing_node_img_name,$leaf_node_img_name,$select_parent) {
	global $_ISA_IMAGE_URL_PATH;
	global $_MODAL_DIALOG_MODE;
	$strTop='<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$strTop.='<treeview title="Treeview" >'."\n";
	$strTop.="<custom-parameters>"."\n";
	$strTop.='<param name="shift-width" value="10"/>' . "\n";
	$strTop.='<param name="opening_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$opening_node_img_name.'"/>'."\n";
	$strTop.='<param name="closing_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$closing_node_img_name.'"/>'."\n";	
	$strTop.='<param name="leaf_node_img_name" value="'.$_ISA_IMAGE_URL_PATH . $leaf_node_img_name.'"/>' . "\n";
	$strTop.='<param name="modal_dialog_mode" value="'.$_MODAL_DIALOG_MODE.'"/>' . "\n";
	$strTop.='<param name="show_control" value="'.$show_control.'"/>'."\n";
	$strTop.='<param name="select_parent" value="'.$select_parent.'"/>'."\n";
	$strTop.="</custom-parameters>"."\n";	
	$strBottom= "</treeview>";
	$strXML="";
	$arr_opening_internal_order = array();
	$v_count = sizeof($arr_all_list);
	$v_opening_count = 0;
	$v_exception_internal_order = "XXX";
	for($i=0;$i<$v_count;$i++){
		$v_current_id = $arr_all_list[$i][0];//PK
		$v_parent_id = $arr_all_list[$i][1];//FK	
		$v_current_code = htmlspecialchars($arr_all_list[$i][2]);//C_CODE
		$v_current_name = htmlspecialchars($arr_all_list[$i][3]);//C_NAME
		$v_current_type = $arr_all_list[$i][4];//C_TYPE
		$v_current_level = $arr_all_list[$i][5];//C_LEVEL
		$v_current_have_children = $arr_all_list[$i][6];//C_HAVE_CHILDREN
		$v_current_internal_order = $arr_all_list[$i][7];//C_INTERNAL_ORDER
		if ($i+1<$v_count){
			$v_next_internal_order = $arr_all_list[$i+1][7];//C_INTERNAL_ORDER
		}	
		if ($v_current_id==$exception_brand_id){
			$v_exception_internal_order = $v_current_internal_order;
		}
		//Kiem tra ID neu no khong la $exception_brand_id thi moi tao
		if (substr($v_current_internal_order,0,strlen($v_exception_internal_order))!=$v_exception_internal_order){
			$v_opening_count = sizeof($arr_opening_internal_order);
			while (sizeof($arr_opening_internal_order)>0){
				$v_opening_count = sizeof($arr_opening_internal_order);
				if (substr($v_current_internal_order,0,strlen($arr_opening_internal_order[$v_opening_count-1]))!=$arr_opening_internal_order[$v_opening_count-1]){
					$strXML.="</folder>"."\n";
					array_pop($arr_opening_internal_order);
				}else{
					break;
				}
			}	
			$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" type="'.$v_current_type.'" parent_id="'.$v_parent_id.'" level="'.$v_current_level.'" have_children="'.$v_current_have_children.'">'.'\n';
			// Neu phan tu tiep theo thuoc NHANH khac thi dong tag 
			if (substr($v_next_internal_order,0,strlen($v_current_internal_order))!=$v_current_internal_order Or $v_current_internal_order==$v_next_internal_order){
				$strXML.="</folder>"."\n";
			}else{
				array_push($arr_opening_internal_order, $v_current_internal_order);
			}
		}
	}
	$v_opening_count = sizeof($arr_opening_internal_order);
	for ($i=0; $i<sizeof($arr_opening_internal_order); $i++){
		$strXML.= "</folder>"."\n";
	}	
	return  $strTop. $strXML . $strBottom;
}

//********************************************************************************************************************
//Ham _show_error : Loai bo cac ki tu dac biet trong mot thong bao loi va hien thi cua so thong bao loi
//********************************************************************************************************************
function _show_error($p_app_err_msg, $p_db_err_msg){
	$StrError = _replace_bad_char($p_app_err_msg) . " (" . _replace_bad_char($p_db_err_msg) . ")";
	if ($StrError > "") {
		echo "<script language='JavaScript'>";
		echo "alert ('".$StrError."');";
		echo "</script>";
	}
}	
//********************************************************************************************************************
//Ham _get_next_value : Lay gia tri lon nhat cua mot cot trong mot table va cong them 1
//********************************************************************************************************************
function _get_next_value($p_table, $p_column, $p_where_clause){
	global $ado_conn;
	$cmd = "Select max(" . $p_column . ")" . " MAX_VALUE " . " From " . $p_table ;
	if (!is_null(trim($p_where_clause)) and trim($p_where_clause)<>""){
		$cmd = $cmd . " Where " . $p_where_clause ;
	}	
	if (_is_sqlserver()){
		//$result = @mssql_query($cmd,$conn);
		//$row_max_value = @mssql_fetch_array($result);
		$row_max_value = _adodb_exec_update_delete_sql($cmd);
	}	
	if (_is_postgresql()){
		$rs_max_value = @pg_query($conn, $cmd);
		$row_max_value = @pg_fetch_array($rs_max_value);
	}	
	$next_value = $row_max_value['MAX_VALUE'];
	if (!is_null($next_value)){
		$next_value=intval($next_value)+1;
	}else{
		$next_value=1;
	}
	return $next_value;
}	
//********************************************************************************************************************
//Ham _is_sqlserver : tra lai gia tri true neu DB hien thoi la SQL-SERVER
//********************************************************************************************************************
function _is_sqlserver(){
	global $_ISA_DB_TYPE;
	return ($_ISA_DB_TYPE == "SQL SEVER");
}
//********************************************************************************************************************
//Ham _is_postgresql : tra lai gia tri true neu DB hien thoi la PostgreSQL
//********************************************************************************************************************
function _is_postgresql(){
	global $_ISA_DB_TYPE;
	return ($_ISA_DB_TYPE == "POSTGRESQL");
}
//********************************************************************************************************************
//Ham _is_oracle : tra lai gia tri true neu DB hien thoi la ORACLE
//********************************************************************************************************************
function _is_oracle(){
	global $_ISA_DB_TYPE;
	return ($_ISA_DB_TYPE == "ORACLE");
}
function _get_remote_ip_address(){
	if (isSet($_SERVER)) {
		if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		}else{
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	}else{
		if (getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
		}elseif (getenv( 'HTTP_CLIENT_IP' ) ) {
			$realip = getenv( 'HTTP_CLIENT_IP' );
		}else{
			$realip = getenv( 'REMOTE_ADDR' );
		}
	}
	return $realip; 
}
//********************************************************************************************************************
//Thuc hien noi array2 vao array1 voi so phan tu cua 2 mang la $number_element
//********************************************************************************************************************
function _attach_two_array($p_array1, $p_array2, $number_element){
	$v_count_arr1 = sizeof($p_array1);
	$v_count_arr2 = sizeof($p_array2);
	$j = $v_count_arr1;
	for($i = 0; $i<$v_count_arr2; $i++){
		for($h=0; $h<=$number_element; $h++){
			$p_array1[$j][$h] = $p_array2[$i][$h];
		}
		$j++;
	}
	return $p_array1;
}
//********************************************************************************************************************
//Y nghia :	Thuc hien gui mot Email 
//Tham so:	$p_web_master: Email cua nguoi quan tri;
//			$p_mail_address : Email duoc gui toi;
//			$p_subject:	Tieu de cua Email;
//			$p_content: Noi dung cua Email;	
//********************************************************************************************************************
function _senmail_from_php($p_web_master,$p_mail_address,$p_subject,$p_content){
 mail($p_mail_address,$p_subject,$p_content, 
	"From: ".$p_web_master."\r\n"
    ."Reply-To: ".$p_web_master."\r\n"
    ."X-Mailer: PHP/" . phpversion());
}
//Ham tra lai mang arr_all_unit moi theo cau truc dinh san de sinh cay
//Truoc khi thuc hien, chuyen mang cau truc phong ban tu _SESSION ra mang
function _unitarr_to_treearr($arr_all_unit_from_session){
	$i=0;
	foreach($arr_all_unit_from_session as $unit_item) {
		$arr_return[$i][0]=$unit_item['id'];
		$arr_return[$i][1]=$unit_item['parent_id'];
		$arr_return[$i][2]=$unit_item['code'];
		$arr_return[$i][3]=$unit_item['name'];
		$arr_return[$i][4]=0;
		$arr_return[$i][5]=0;
		$i++;
	}
	return $arr_return;
}
//********************************************************************************************************************
//Ham _get_item_attr_by_id($p_array, $p_id, $p_attr_name) tra ve mot gia tri cua mot thuoc tinh cua mot phan tu mang.
//	Tham so:
//		-$p_array: Mang cac phan tu can lay(lay tu bien SESSION arr_all_starff hoac arr_all_unit)
//		-$p_id: id cua phan tu can lay gia tri cua thuoc tinh
//		-$p_attr_name: ten cua thuoc tinh can lay gia tri
//	Gia tri tra lai:
//		Gia tri cua thuoc tinh cua phan tu mang can lay
//********************************************************************************************************************
function _get_item_attr_by_id($p_array, $p_id, $p_attr_name) {
	foreach($p_array as $staff){	
		if (strcasecmp($staff['id'],$p_id)==0){
			return $staff[$p_attr_name];
		}
	}
	return "";
}
//********************************************************************************************************************
//Ham _show_info_by_enduser() Hien thi thong tin ve nguoi dang nhap vao cac ung dung co tich hop ISA-USER
//	Gia tri tra lai:
//		Chuoi HTML tao bang chua thong tin ve Nguoi dang nhap
//********************************************************************************************************************
function _show_info_by_enduser($p_horizon=1) {
	if (!isset($_SESSION['staff_id'])){
		return "";
	}
	global $_ISA_USER_WEB_SITE_PATH;
	$enduser_label = "Ng&#432;&#7901;i &#273;&#259;ng nh&#7853;p:";
	$unit_label = "&#272;&#417;n v&#7883;:";
	$enduser_name = $_SESSION['user_name'];
	$infor_label = "&#272;&#7893;i m&#7853;t kh&#7849;u NSD";

	$unit_name = _get_unit_level_one_name($_SESSION['staff_id']);

	$html_str = '<table align="right"><tr><td>';
	$html_str = $html_str . '<small class=small_label>' . $enduser_label.'</small>';
	$html_str = $html_str . '<small class="logged_user">' . $enduser_name . '</small><small>&nbsp;|&nbsp;</small></td>';

	if ($unit_name!=""){
		$html_str = $html_str . '<td class="small_label">'. $unit_label;
		$html_str = $html_str . '<small class="logged_user">' . $unit_name . '</small></td>';
	}	
	$v_isa_user_change_personal_info_url = $_ISA_USER_WEB_SITE_PATH . "org/index.php";

	$html_str = $html_str . '<td colspan="10" align="center" class="normal_link">';
	$html_str = $html_str . '<a href="javascript:show_modal_dialog_change_personal_info('."'".$v_isa_user_change_personal_info_url."','".$_SESSION['staff_id']."'".');">' . $infor_label . '</a></td></tr>';
	$html_str = $html_str . '</table>';
	return $html_str;
}

//********************************************************************************************************************
// Ham _get_parent_object_id()
// Y nghia: Lay ID cua doi tuong cha o cap bat ky 
// $p_object_arr: mang (array) chua tat ca cac doi tuong
// $p_object_id_index: Chi so (la so) cua doi tuong chua ID
// $p_object_parent_id_index: Chi so (la so) cua doi tuong chua ID cha
// $p_seached_object_id: ID CHA cua doi tuong hien thoi
// $p_level: Cap cua doi tuong CHA can tim (Neu $p_level=0 thi tim doi tuong CHA cap 1 - ONG TO
// Nguoi tao: Nguyen Tuan Anh
// Ngay: 24/9/2004
//********************************************************************************************************************
function _get_parent_object_id($p_object_arr, $p_object_id_index, $p_parent_object_id_index, $p_seached_object_id, $p_level){
	// Neu mang (array) chua tat ca cac doi tuong khong co phan  tu nao thi ket thuc luon
	if (sizeof($p_object_arr)==0){
		return "";
	}
	// Neu gia tri tim kiem la rong hoac NULL thi ket thuc luon
	if ($p_seached_object_id=="" Or is_null($p_seached_object_id)){
		return "";
	}
	// Neu cap tim kiem la 1 thi tra lai luon gia tri cua tham so p_seached_object_id
	if ($p_level==1){
		return $p_seached_object_id;
	}
	$v_ret_value = "";
	$v_level = 0;
	$v_seached_object_id = $p_seached_object_id;
	while(1==1){
		$v_found_in_FOR_loop = false; // Bien nay de xac dinh xem trong mot vong lap FOR co doi tuong nao chua gia tri can tim hay khong. Neu Khong co thi ket thuc vong lap While
		for($i=0;$i<sizeof($p_object_arr); $i++){
			//echo "id=" . $p_object_arr[$i][$p_object_id_index] . "| p_id=" . $p_object_arr[$i][$p_parent_object_id_index]." | search=". $v_seached_object_id ."br>";
			if ($p_object_arr[$i][$p_object_id_index] == $v_seached_object_id){
				$v_found_in_FOR_loop = true;
				$v_level = $v_level+1;
				// Neu da dat den cap can tim thi ket thuc
				if ($p_level>0){
					if ($v_level>=$p_level){
						$v_ret_value = $p_object_arr[$i][$p_object_id_index];
						break;
					}else{
						if ($p_object_arr[$i][$p_parent_object_id_index]==""){
							$v_ret_value = $p_object_arr[$i][$p_object_id_index];
							break;
						}else{
							$v_seached_object_id = $p_object_arr[$i][$p_parent_object_id_index];
						}	
					}
				}else{ // Neu cap can tim la 0 (cap "ONG TO") thi neu doi tuong hien tai khong co CHA thi chinh la doi tuong can tim
					if ($p_object_arr[$i][$p_parent_object_id_index]==""){
						$v_ret_value = $p_object_arr[$i][$p_object_id_index];
						break;
					}else{
						$v_seached_object_id = $p_object_arr[$i][$p_parent_object_id_index];
					}
				}	
			}		
		}
		// Neu ket thuc 1 vong FOR ma khong tim thay doi tuong nao thoa man dieu kien tim kiem thi ket thuc vong lap While
		if (!$v_found_in_FOR_loop) break;
	}	
	return $v_ret_value;
}

//********************************************************************************************************************
// Ham _get_unit_level_one_id()
// Lay ID cua don vi cap 1 cua mot nguoi bat ky trong don vi
//********************************************************************************************************************
function _get_unit_level_one_id($user_id){
	$v_unit_level1_id="";
	$v_root_id = _get_root_unit_id();
	$v_parent_id = _get_item_attr_by_id($_SESSION['arr_all_staff'],$user_id,'unit_id');
	if ($v_parent_id!="" && !is_null($v_parent_id) && $v_parent_id!="NULL"){
		while(strcasecmp($v_parent_id,$v_root_id)!=0){
			foreach($_SESSION['arr_all_unit'] as $v_unit){
				if (strcasecmp($v_unit['id'], $v_parent_id)==0){
					$v_unit_level1_id = $v_unit['id'];
					$v_parent_id =  $v_unit['parent_id'];
					break;
				}
			}
		}
	}
	return $v_unit_level1_id;
}
//********************************************************************************************************************
//Lay TEN cua don vi cap 1 cua mot nguoi bat ky trong don vi
//********************************************************************************************************************
function _get_unit_level_one_name($user_id){
	if (!isset($_SESSION['arr_all_staff']) || sizeof($_SESSION['arr_all_staff'])<=0){
		return "";
	}
	$v_unit_level1_name="";
	$v_root_id = _get_root_unit_id();
	$v_parent_id = _get_item_attr_by_id($_SESSION['arr_all_staff'],$user_id,'unit_id');
	if ($v_parent_id!="" && !is_null($v_parent_id) && $v_parent_id!="NULL"){
		while(strcasecmp($v_parent_id,$v_root_id)!=0){
			foreach($_SESSION['arr_all_unit'] as $v_unit){
				if (strcasecmp($v_unit['id'],$v_parent_id) == 0){
					$v_unit_level1_name = $v_unit['name'];
					$v_parent_id =  $v_unit['parent_id'];
					break;
				}
			}
		}
	}
	return $v_unit_level1_name;
}
//********************************************************************************************************************
//Lay id goc cua cau truc phong ban
//********************************************************************************************************************
function _get_root_unit_id(){
	$v_root_id = "";
	foreach ($_SESSION['arr_all_unit'] as $v_unit){
		if (is_null($v_unit['parent_id']) || $v_unit['parent_id']=="" || $v_unit['parent_id']=="NULL"){
			$v_root_id = $v_unit['id']; //Don vi goc 
			break;
		}
		unset($v_unit);	
	}
	return $v_root_id;
}
//*********************************************************************************************************************
//Lay danh sach cac don vi con cua 1 don vi
//*********************************************************************************************************************
function _get_arr_child_unit($unit_id){
	$arr_child_unit[0][0] = $unit_id;
	$arr_child_unit[0][1] = NULL;
	$arr_child_unit[0][2] = _get_item_attr_by_id($_SESSION['arr_all_unit'],$unit_id,'code');
	$arr_child_unit[0][3] = _get_item_attr_by_id($_SESSION['arr_all_unit'],$unit_id,'name');
	$arr_child_unit[0][4] = 0;
	$arr_child_unit[0][5] = 0;
	$i = 1;
	foreach($_SESSION['arr_all_unit'] as $v_unit){
		if ($v_unit['parent_id'] == $unit_id){
			$arr_child_unit[$i][0] = $v_unit['id'];
			$arr_child_unit[$i][1] = $v_unit['parent_id'];
			$arr_child_unit[$i][2] = $v_unit['code'];
			$arr_child_unit[$i][3] = $v_unit['name'];
			$arr_child_unit[$i][4] = 0;
			$arr_child_unit[$i][5] = 0;
			$i++;
		}
	}
	return $arr_child_unit;	
}
//*********************************************************************************************************************
//Lay danh sach nguoi su dung cua mot don vi
//*********************************************************************************************************************
function _get_arr_child_staff($arr_unit){
	$i = 0;
	$v_count = sizeof($arr_unit);
	for($j=0; $j < $v_count; $j++){
		foreach($_SESSION['arr_all_staff'] as $v_staff){
			if($v_staff['unit_id'] == $arr_unit[$j]['0']){
				$arr_child_staff[$i][0] = $v_staff['id'];
				$arr_child_staff[$i][1] = $v_staff['unit_id'];
				$arr_child_staff[$i][2] = $v_staff['code'];
				$arr_child_staff[$i][3] = $v_staff['name'];
				$arr_child_staff[$i][4] = 1;
				$arr_child_staff[$i][5] = 1;
				$i++;
			}
		}
	}
	return $arr_child_staff;
}
//Lay danh sach tat ca cac phogn ban
function _get_arr_all_unit(){
	$i = 0;
	foreach($_SESSION['arr_all_unit'] as $v_unit){
		$arr_child_unit[$i][0] = $v_unit['id'];
		$arr_child_unit[$i][1] = $v_unit['parent_id'];
		$arr_child_unit[$i][2] = $v_unit['code'];
		$arr_child_unit[$i][3] = $v_unit['name'];
		$arr_child_unit[$i][4] = 0;
		$arr_child_unit[$i][5] = 0;
		$i++;
	}
	return $arr_child_unit;	
}
function _get_arr_child_unit_root(){
	$i = 0;
	foreach($_SESSION['arr_all_unit'] as $v_unit){
		if (is_null($v_unit['parent_id']) || $v_unit['parent_id']=="" || $v_unit['parent_id']=="NULL"){
			$arr_child_unit_root[$i][0] = $v_unit['id'];
			$arr_child_unit_root[$i][1] = NULL;
			$arr_child_unit_root[$i][2] = $v_unit['code'];
			$arr_child_unit_root[$i][3] = $v_unit['name'];
			$arr_child_unit_root[$i][4] = 0;
			$arr_child_unit_root[$i][5] = 0;
			$i++;
		}else{
			$arr_child_unit_root[$i][0] = $v_unit['id'];
			$arr_child_unit_root[$i][1] = $v_unit['parent_id'];
			$arr_child_unit_root[$i][2] = $v_unit['code'];
			$arr_child_unit_root[$i][3] = $v_unit['name'];
			$arr_child_unit_root[$i][4] = 0;
			$arr_child_unit_root[$i][5] = 0;
			$i++;
		}
	}
	return $arr_child_unit_root;	
}
//Lay danh sach cac don vi cap 1
//*********************************************************************************************************************
function _get_arr_unit_level_one(){
	$i = 0;
	$unit_id = _get_root_unit_id();
	foreach($_SESSION['arr_all_unit'] as $v_unit){
		if ($v_unit['parent_id'] == $unit_id){
			$arr_child_unit[$i][0] = $v_unit['id'];
			$arr_child_unit[$i][1] = $v_unit['parent_id'];
			$arr_child_unit[$i][2] = $v_unit['code'];
			$arr_child_unit[$i][3] = $v_unit['name'];
			$arr_child_unit[$i][4] = 0;
			$arr_child_unit[$i][5] = 0;
			$i++;
		}
	}
	return $arr_child_unit;	
}
?>