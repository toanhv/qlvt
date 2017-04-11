<?php
if (!defined('IS_AJAX_RESPONSE_PAGE')){?>
	<script language="JavaScript">
		<!--Luu lai url cua trang hien thoi de quay lai-->
		v_url = window.location.href;
		//Dua them fuseaction vao url neu url chua co fuseaction
		if (v_url.indexOf("fuseaction",0) < 0) {
			if (v_url.indexOf('?') < 0){
				v_url = v_url+'?fuseaction=$$$$$';
			}else{
				v_url = v_url+'&fuseaction=$$$$$';
			}
		}
		v_url = v_url + '&random=' + <? echo mt_getrandmax();?>;
	</script><?php
}
if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
	require_once('isa-lib/nusoap/nusoap.php');
}	

//$v_ip_address = _get_remote_ip_address();

$v_ip_address = _get_cookie('guid_cookie');

if ($v_ip_address==""){
	$v_value_guid = _generate_guid();
	//_create_cookie('guid_cookie',$v_value_guid);
	$v_ip_address = $v_value_guid;
}

$v_app_code = $_ISA_APP_CODE;
$v_timeout = _CONST_ISA_USER_TIME_OUT;

@session_start();
/*
if (isset($_REQUEST['logon_staff_id'])){
	$_SESSION['staff_id'] = $_REQUEST['logon_staff_id'];
	$_SESSION['user_id'] = $_REQUEST['logon_staff_id'];
	$_SESSION['user_name'] = $_REQUEST['logon_user_name'];
	// Khoi tao them cac bien session de kiem tra xem
	// nguoi dang nhap co phai la NGUOI QUAN TRI ISA-USER va NGUOI QUAN TRI cua it nhat mot ISA-APP hay khong
	$_SESSION['is_isa_user_admin'] = $_REQUEST['logon_is_isa_user_admin'];
	$_SESSION['is_isa_app_admin'] = $_REQUEST['logon_is_isa_app_admin'];
}
*/
if (!isset($_SESSION['staff_id']) Or (isset($_SESSION['staff_id']) And $_SESSION['staff_id']==0)){
	//Chua co bien session staff_id
	//Goi ham is_logged() (la mot function cua ISA-USER) de kiem tra xem co phai dang nhap lai hay khong ???
	$is_logged_parameters = array('p_ip_address' => $v_ip_address, 'p_app_code'=>$v_app_code, 'p_timeout'=>$v_timeout);
	//var_dump($is_logged_parameters );
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('is_logged', $is_logged_parameters);
	}else{
		$str_return = call_user_func_array('is_logged',$is_logged_parameters);
	}
	//echo $str_return;
	//exit;
	if (_CONST_WRITE_LOG==1){
		_write_log_file($_ISA_LOGFILE_URL_PATH,"Goi ham is_logged(). Ket qua:".$str_return);
	}
	//echo $str_return;
	//exit;
	// Gia tri tra ve la -1 co nghia NSD phai dang nhap lai
	if (trim($str_return) == '-1'){
		if (!defined('IS_AJAX_RESPONSE_PAGE')){?>
		<script>
			//alert('is_logged' + '<?php echo $v_ip_address;?>');
			v_url = escape(v_url);
			window.location = '<?php echo _CONST_ISA_USER_LOGIN_URL;?>?app_code=<?php echo $v_app_code;?>&url_back='+v_url +'&ip_address=<?php echo $v_ip_address;?>';
		</script><?php
		}
	}else{
		// Neu gia tri tra ve chi chua ID cua mot STAFF thi su dung luon STAFF nay de truy nhap vao ISA-APP 
		if (strstr($str_return, ',') ==''){
			$arr_return = explode('||',$str_return);
			$staff_id = $arr_return[0];
			//Kiem tra STAFF nay co phai la enduser cua Application nay khong (kiem tra thong qua WEBSERVICE cua ISA-USER)?
			$is_app_enduser_parameters = array('p_staff_id' => $staff_id, 'p_app_code'=>$v_app_code);
			if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
				$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
				$str_return = $obj_client->call('is_app_enduser', $is_app_enduser_parameters);
			}else{
				$str_return = call_user_func_array('is_app_enduser',$is_app_enduser_parameters);
			}
			if (_CONST_WRITE_LOG==1){
				_write_log_file($_ISA_LOGFILE_URL_PATH,"Goi ham is_app_enduser(). Ket qua:".$str_return);
			}
			// Neu STAFF nay la enduser cua Application thi khoi tao cac bien session de luu PK_STAFF, PK_ENDUSER, C_NAME, C_ISA_USER_ADMIN, C_ISA_APP_ADMIN
			if ($str_return != 'false'){
				$arr_staff_login = explode('|&|',$str_return);
				$_SESSION['staff_id'] = $arr_staff_login[0];
				$_SESSION['user_id'] = $arr_staff_login[0];
				$_SESSION['user_name'] = $arr_staff_login[2];
				$_SESSION['is_isa_user_admin'] = $arr_staff_login[3];
				$_SESSION['is_isa_app_admin'] = $arr_staff_login[4];
			}else{
				if (!defined('IS_AJAX_RESPONSE_PAGE')){
					//STAFF_ID nay khong co quyen thuc hien ung dung, yeu cau login lai?>
					<script>
						//alert('is_app_enduser' + '<?php echo $v_ip_address;?>');
						v_url = v_url;
						v_url = escape(v_url);
						window.location = '<?php echo _CONST_ISA_USER_LOGIN_URL;?>?app_code=<?php echo $v_app_code;?>&url_back='+v_url +'&ip_address=<?php echo $v_ip_address;?>';
					</script><?php
				}
			}
		}else{
			//Co nhieu STAFF_ID duoc tra ve
			if (!defined('IS_AJAX_RESPONSE_PAGE')){?>
				<script>
					//Truyen vao danh sach cac staff_id tra ve de hien thi trang chon
					//alert('Co nhieu STAFF_ID' + '<?php echo $v_ip_address;?>');
					v_url = v_url + '&list_id=<?php echo $str_return;?>';
					v_url = escape(v_url);
					window.location = '<?php echo _CONST_ISA_USER_LOGIN_URL;?>?fuseaction=LIST_USER_FOR_SELECT&app_code=<?php echo $v_app_code;?>&url_back='+v_url+'&ip_address=<?php echo $v_ip_address;?>';									
				</script><?php				
			} 
		}
	}
}
if (_CONST_WRITE_LOG==1){
	_write_log_file($_ISA_LOGFILE_URL_PATH,"STAFF ID/ISA-USER-ADMIN/ISA-APP-ADMIN:".$_SESSION['staff_id']."/".$_SESSION['is_isa_user_admin'],"/".$_SESSION['is_isa_app_admin']);			
}	
//Cap nhat thoi diem cuoi cung STAFF_ID truy nhap vao mot trang cua ung dung
if (isset($_SESSION['staff_id']) And $_SESSION['staff_id']!=0 And $_SESSION['staff_id']!=""){
	$update_last_time_parameters = array('p_ip_address' => $v_ip_address, 'p_app_code'=>$v_app_code, 'p_staff_id'=>$_SESSION['staff_id']);		
	if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
		$obj_client = new soapclient(_CONST_ISA_USER_WEBSERVICE_URL);
		$str_return = $obj_client->call('update_last_time', $update_last_time_parameters);	
	}else{
		$str_return =call_user_func_array('update_last_time',$update_last_time_parameters);
	}
	if (_CONST_WRITE_LOG==1){
		_write_log_file($_ISA_LOGFILE_URL_PATH,"Goi ham update_last_time(). Ket qua:".$str_return);			
	}
}	
?>
 