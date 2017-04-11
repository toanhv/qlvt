<?php
// Tra lai danh sach gia tri cua mot column trong kieu du lieu bang
// $p_request_col: ten column (khai bao trong tag <column_name> hoac <xml_tag_in_db>
// p_request_row: ten dong (khai bao trong tag <row_id>)
function _get_request_varible_for_value_list($p_request_col,$p_request_row,$p_delimeter){
	global $_REQUEST;
	$list_value  = "";
	$deleted_row = $_REQUEST['hdn_deleted_row_' . $p_request_row];// Tuan Anh Them
	for ($i=0;$i<intval($_REQUEST['repeat_row_in_db_'.$p_request_row]);$i++){
		if (!_list_have_element($deleted_row, $i, ',')){ // Neu khong phai dong da bi xoa
			if ($i == 0){
				$list_value = $list_value ._replace_bad_char($_REQUEST[$p_request_col.$p_request_row.$i]) ;			
			}else{
				$list_value = $list_value . $p_delimeter . _replace_bad_char($_REQUEST[$p_request_col.$p_request_row.$i]) ;
			}
		}	
	}
	return $list_value;
}

function _get_request_varible_for_xml_string_list($p_request_col_list,$p_request_row,$p_delimeter){
	global $_REQUEST;
	/*$begin_xml_data = '<?xml version="1.0" encoding="UTF-8"?><root><data_list>';*/
	$begin_xml_data = '<root><data_list>';
	$end_xml_data.= "</data_list></root>";
	$list_value  = "";
	$deleted_row = $_REQUEST['hdn_deleted_row_' . $p_request_row];// Tuan Anh Them
	$arr_request_col = explode(",",$p_request_col_list);
	for ($i=0;$i<intval($_REQUEST['repeat_row_in_db_'.$p_request_row]);$i++){
		if (!_list_have_element($deleted_row, $i, ',')){ // Neu khong phai dong da bi xoa
			$sub_list_value = "";
			for ($j=0;$j<sizeof($arr_request_col);$j++){
				$sub_list_value = _list_add($sub_list_value,"<".$arr_request_col[$j].">"._replace_bad_char($_REQUEST[$arr_request_col[$j].$p_request_row.$i])."</".$arr_request_col[$j].">","");
			}
			$list_value = _list_add($list_value,$begin_xml_data.$sub_list_value.$end_xml_data,$p_delimeter);
		}
	}
	return $list_value;
}

function _convert_xml_string_to_array($p_xml_string_in_file,$p_item_tag){
	$arr_list_item = array();
	$i = 0;
	$v_struct_rax = new RAX();
	$v_struct_rec = new RAX();
	$v_struct_rax->open($p_xml_string_in_file);
	$v_struct_rax->record_delim = $p_item_tag;
	$v_struct_rax->parse();
	$v_struct_rec = $v_struct_rax->readRecord();
	while ($v_struct_rec) {
		$v_struct_row = $v_struct_rec->getRow();
		$arr_list_item[$i] = $v_struct_row;
		$i++;
		$v_struct_rec = $v_struct_rax->readRecord();
	}
	return $arr_list_item;
}
// Ham them gia tri va the vao xau chua gia tri cua form
//$p_xml_string: Chuoi van dua them the vao
//$p_xml_tag   : The muon dua vao
//$p_xml_value : Gia tri cua the muon dua vao
function _add_tag_and_value_in_xml_string($p_xml_string,$p_xml_tag,$p_xml_value){
	/*$strXML = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';*/
	$strXML = '<root><data_list></data_list></root>';
	if (!is_null($p_xml_string) && $p_xml_string!=""){
		//Xoa the va gia tri cua the $p_xml_tag trong xau truyen vao
		$p_xml_string = preg_replace ("'<".$p_xml_tag."[^>]*?>.*?</".$p_xml_tag.">'si", "", $p_xml_string);

		$strXML = substr($p_xml_string,0,strlen($p_xml_string)-strlen("</data_list></root>"));
		$strXML.= "<".$p_xml_tag.">".$p_xml_value."</".$p_xml_tag.">";
		$strXML.= "</data_list></root>";
	}
	return $strXML;
}
// Ham Thay the cac the cac gia tri trong cau lenh Query du lieu
//$p_sql_replace  : Chuoi can thay the
//$p_xml_string_in_file  : chuoi XML mo ta cac tieu thuc loc
//$p_xml_tag : The XML can lay trong chuoi XMl mo ta tieu thuc loc
//$p_filter_xml_string  : chuoi XML gom cac the va gia tri cua tung tieu thuc loc do.

function _replace_tag_xml_value_in_sql($p_sql_replace,$p_xml_string_in_file,$p_xml_tag,$p_filter_xml_string){
	global $_ISA_OWNER_CODE;
	$p_sql_replace = _restore_XML_bad_char($p_sql_replace);
	$p_sql_replace = str_replace('#OWNER_CODE#' ,$_ISA_OWNER_CODE,$p_sql_replace);
	$v_sql_replace_temp = $p_sql_replace;
	$v_xml_file_temp = $p_xml_string_in_file;
	$v_table_struct_rax = new RAX();
	$v_table_struct_rec = new RAX();
	$v_table_struct_rax->open($p_xml_string_in_file);
	$v_table_struct_rax->record_delim = $p_xml_tag;
	$v_table_struct_rax->parse();
	$v_table_struct_rec = $v_table_struct_rax->readRecord();
	while ($v_table_struct_rec) {
		$v_table_struct_row = $v_table_struct_rec->getRow();
		$v_tag_list = $v_table_struct_row["tag_list"];
		$arr_tag = explode(",", $v_tag_list);
		for($i=0;$i < sizeof($arr_tag); $i++){
			$v_formfield_rax = new RAX();
			$v_formfield_rec = new RAX();
			$v_formfield_rax->open($v_xml_file_temp);
			$v_formfield_rax->record_delim = $arr_tag[$i];
			$v_formfield_rax->parse();
			$v_formfield_rec = $v_formfield_rax->readRecord();
			$v_formfield_row = $v_formfield_rec->getRow();
			$v_data_format = $v_formfield_row["data_format"];
			$v_xml_tag_in_db = $v_formfield_row["xml_tag_in_db"];
			if ($p_filter_xml_string!=""){
				$v_column_rax = new RAX();
				$v_column_rec = new RAX();
				$v_column_rax->open($p_filter_xml_string);
				$v_column_rax->record_delim = 'data_list';
				$v_column_rax->parse();
				$v_column_rec = $v_column_rax->readRecord();
				$v_column_row = $v_column_rec->getRow();
				$v_value_input = _replace_XML_bad_char($v_column_row[$v_xml_tag_in_db]);
				if ($v_data_format=="isdate"){
					$v_value_input = _ddmmyyyy_to_yyyymmdd($v_value_input);
				}
				if ($v_data_format=="isnumeric"){
					$v_value_input = intval($v_value_input);
				}
				if ($v_data_format=="ismoney"){
					$v_value_input = floatval($v_value_input);
				}
			}
			$v_sql_replace_temp = str_replace("#".$v_xml_tag_in_db."#",$v_value_input,$v_sql_replace_temp);
		}
		$v_table_struct_rec = $v_table_struct_rax->readRecord();
	}
	return  $v_sql_replace_temp;
}

// Thay the cac bien trong cau lenh SQL bang cac gia tri duoc luu trong 1 chuoi XML
// p_sql: Chuoi lenh SQL
// p_variable: ten bien can thay the
// p_xml_str: Chuoi XML luu chua gia tri thay the duoi dang cac the XML
// p_xml_parent_tag: Ten the XML (laf the "cha") cua the XML chua gia tri thay the
// p_xml_tag: ten the XML chua gia tri se thay the
function _replace_variable_value_in_sql($p_sql,$p_variable, $p_xml_string, $p_xml_parent_tag, $p_xml_tag){
	$v_replace_value = _XML_get_xml_tag_value($p_xml_string,$p_xml_parent_tag,$p_xml_tag);
	$v_sql_string = str_replace($p_variable,$v_replace_value,$p_sql);
	return $v_sql_string;
}

// Ham tao chuoix XML de ghi vao CSDL
// $p_xml_tag_list: danh sach cac the XML (phan cach boi _CONST_SUB_LIST_DELIMITOR)
// $p_value_list: danh sach cac gia tri tuong ung voi moi the XML (phan cach boi _CONST_SUB_LIST_DELIMITOR)
function _XML_generate_xml_data_tring($p_xml_tag_list,$p_value_list){
	/*$strXML = '<?xml version="1.0" encoding="UTF-8"?><root><data_list>';*/
	$strXML = '<root><data_list>';
	for ($i=0;$i<_list_get_len($p_xml_tag_list,_CONST_SUB_LIST_DELIMITOR);$i++){
		$strXML = $strXML ."<"._list_get_at($p_xml_tag_list,$i,_CONST_SUB_LIST_DELIMITOR).">";
		$strXML = $strXML .trim(_replace_XML_bad_char(_list_get_at($p_value_list,$i,_CONST_SUB_LIST_DELIMITOR)));
		$strXML = $strXML ."</"._list_get_at($p_xml_tag_list,$i,_CONST_SUB_LIST_DELIMITOR).">";
	}
	$strXML = $strXML . "</data_list></root>";
	return $strXML;
}

// Ham _XML_read_record tra lai mot array chua ten cac the XML va gia tri tuong ung TRONG MOT NHANH cua file XML
// $p_xml_string: chuoi XML
// $p_xml_tag: ten the XML xac dinh nhanh can lay thong tin
// Gia tri tra lai: la mot array
// Vi du:
// $arr = _XML_read_record("../xml/quan_tri_doi_tuong_danh_muc.xml","listtype_type");
// var_dump($arr);

function _XML_read_record($p_xml_string,$p_xml_tag){
	if ($p_xml_string!=""){
		$rax = new RAX();
		$rec = new RAX();
		$rax->open($p_xml_string);
		$rax->record_delim = $p_xml_tag;
		$rax->parse();
		$rec = $rax->readRecord();
		return $rec;
	}else{
		return NULL;
	}
}
// Ham process_attach xu ly file dinh kem
function process_attach($p_arr,$p_max,$p_description_name){
	global $v_hide_update_file;
	//$v_record_id = $v_item_id;
	$v_goto_url_for_delete_file = "javascript:delete_row(document.all.tr_line_new,document.forms(0).chk_file_attach_new_id,document.forms(0).hdn_deleted_new_file_id_list);";
	$v_goto_url_for_add_file = "javascript:add_row(document.all.tr_line_new," . $p_max .");";
	
	$strHTML = $strHTML . "<table  width='98%' cellpadding='0' cellspacing='0'><col width='5%'><col width='95%'>";	
	if(isset($p_arr)){
		$v_count_file = sizeof($p_arr);
	}else{
		$v_count_file = 0;
	}	
	if ($v_count_file>0) {
		// Goi thu tuc xu ly khi xoa cac file da co
		$v_goto_url_for_delete_file = $v_goto_url_for_delete_file . "delete_row(document.all.tr_line_exist,document.forms(0).chk_file_attach_exist_id,document.forms(0).hdn_deleted_exist_file_id_list);";
		for ($j = 0; $j<$v_count_file; $j++) {
			$v_file_id = $p_arr[$j]['PK_FILE'];
			$v_file_name = $p_arr[$j]['C_FILE_NAME'];
			if ($p_arr[$j]["$p_description_name"] != '' && !is_null($p_arr[$j]["$p_description_name"])){
				$v_description_name = $p_arr[$j]["$p_description_name"];
			}else{
				$v_description_name = $v_file_name;
			}
			if(_is_sqlserver()){
				$v_file_url = trim(_CONST_ATTACH_FILE_PATH_FROM_ROOT). $v_file_name;
				$v_goto_url = "javascript:filename_onclick(&quot;T_QLBH_INSURANCE_FILE&quot;,&quot;PK_FILE&quot;,&quot;C_FILE_NAME&quot;,&quot;C_FILE_CONTENT&quot;," . strval($v_file_id) . ",&quot;".$v_file_url."&quot;);" ;
				$target = "";
			}
			$strHTML = $strHTML . "<tr id='tr_line_exist'><td colspan='2'><input type='checkbox' name='chk_file_attach_exist_id' value=$v_file_id><a href='$v_goto_url' class='normal_link' $target>$v_description_name</a></td>";
			$strHTML = $strHTML . "</tr>";	
		}
	}
//Vong lap hien thi cac file dinh kem se them vao van ban
//echo $v_hide_update_file.'aaaaaaaaaaaaaaaaa';
	if(!($v_hide_update_file =='true')){
		$v_add = _CONST_ADD_BUTTON;
		$v_del = _CONST_DELETE_BUTTON;
	
		$strHTML = $strHTML . "<tr><td>&nbsp;</td><td><span class='normal_label' style='width:40%'><b>T&#234;n File &#273;&#237;nh k&#232;m</b></span>";
		$strHTML = $strHTML . "<span class='normal_label' style='width:60%'><b>&#272;&#432;&#7901;ng d&#7851;n File &#273;&#237;nh k&#232;m</b></span></td></tr>";
		for($j=0; $j<$p_max; $j++){					
			if ($j<1 ) { //and $v_is_granted_update
				$v_str_show="block";
			}else{
				$v_str_show="none";
			}
			$strHTML = $strHTML . "<tr id='tr_line_new' style='display:$v_str_show'><td><input type='checkbox' name='chk_file_attach_new_id' value=$j></td>";
			$strHTML = $strHTML . "<td><input type='text' name='txt_description_file_attach_name$j' class='normal_textbox' optional='true' onKeyDown='change_focus(document.forms(0),this)' style='width:40%' >";
			$strHTML = $strHTML . "<input type='file' name='file_attach$j' style='width:60%'  class='small_textbox' optional='true'></td></tr>";
		}	
		$strHTML = $strHTML . "<tr align='center'><td colspan='2'><a href='$v_goto_url_for_add_file' class='normal_link'>$v_add</a>&nbsp;";
		$strHTML = $strHTML . "	<a href='$v_goto_url_for_delete_file' class='small_link'>$v_del</a></td></tr>";
	}	
	$strHTML = $strHTML . "</table>";
	//echo htmlspecialchars($strHTML);//exit;
	return $strHTML;
}

// Ham _XML_get_xml_tag_value tra lai GIA TI cua mot the XML
// $p_xml_file: tham so xac dÃ¡Â»â€¹inh duong dan toi file XML
// $p_xml_parent_tag: ten the XML CHA  - xac dinh nhanh can lay thong tin
// $p_xml_tag: ten the XML can lay gia tri
// Vi du:
// $ret = _XML_get_xml_tag_value("../xml/quan_tri_doi_tuong_danh_muc.xml","listtype_type","label");
// echo $ret;
function _XML_get_xml_tag_value($p_xml_string,$p_xml_parent_tag,$p_xml_tag){
	if ($p_xml_string!=""){
		$rec = new RAX();
		$rec = _XML_read_record($p_xml_string,$p_xml_parent_tag);
		$row = $rec->getRow();
		$v_ret = _restore_XML_bad_char($row[$p_xml_tag]);
	}else{
		$v_ret = "";
	}
	return $v_ret;
}

// $p_xml_file: duong dan toi file XML mo ta cac form field
// $p_xml_tag: ten THE XML xac dinh NHANH mo ta cac form field. Phai lay ten THE mo ta cau truc bang
// $p_xml_string_in_db: xau XML lay tu CSDL
// $p_arr_item_value: array chua gia tri cua cac column
//$p_input_file_name: De xac dinh truyen vao xau xml hay ten file xml
// Vi du: echo _XML_generate_formfield("../xml/quan_tri_doi_tuong_danh_muc.xml", "update_row", $v_xml_str, $arr_single_list);

function _XML_generate_formfield($p_xml_file, $p_xml_tag, $p_xml_string_in_db, $p_arr_item_value,$p_input_file_name=true,$p_view_mode=false){
	global $_ISA_IMAGE_URL_PATH,$_ISA_LIB_URL_PATH,$_ISA_WEB_SITE_PATH,$_ISA_LIST_WEB_SITE_PATH;
	global $v_label, $v_type, $v_dataformat,$v_message,$v_optional,$v_xml_data,$v_column_name,$v_xml_tag_in_db,$v_readonly_in_edit_mode,$v_disabled_in_edit_mode,$v_note,$v_relate_recordtype,$v_width,$v_height,$v_row, $v_row_id, $v_max, $v_min, $v_maxlength,$v_tooltip,$v_count;
	global $v_selectbox_option_sql,$v_selectbox_id_column, $v_selectbox_name_column,$v_function_value,$v_the_first_of_id_value;
	global $v_checkbox_multiple_sql,$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column ,$v_direct;
	global $v_textbox_multiple_sql,$v_textbox_multiple_id_column,$v_textbox_multiple_name_column,$v_first_width; //Cac bien multipletextbox
	global $v_file_attach_sql,$v_file_attach_max,$v_description_name,$v_hide_update_file;
	global $v_table_name, $v_order_column,$v_where_clause; //Cac bien textorder
	global $v_directory,$v_file_type;
	global $v_js_function_list,	$v_js_action_list;
	global $v_value,$i,$v_row,$v_row_id;
	global $v_xml_string_in_file;
	global $v_js_function_after_select,$v_path_root_to_modul;
	global $v_input_data,$v_session_name, $v_session_id_index,$v_session_name_index,$v_session_value_index;
	global $v_channel_sql, $v_url;
	global $v_path,$v_other_attribute;
	global $v_radio_value,$v_php_function,$v_content;
	global $v_media_file_onclick_url,$v_media_file_name,$v_media_file_name_column,$v_media_file_url_column;
	global $v_title,$v_class_name;
	global $v_have_title_value,$v_default_value;
	global $v_hrf,$v_label_list,$v_col_width_list,$v_tbl_sql_string,$v_col_name_in_db_list,$v_col_input_type_list;
	global $v_view_mode;
	global $v_public_list_code;
	global $v_store_in_child_table;	
	global $v_textbox_sql, $v_textbox_id_column,$v_textbox_name_column,	$v_textbox_fuseaction;
	global $v_display;
	//Tuan anh them ngay 27/6/2007
	global $v_is_set_innerHTML;	// Ngam dinh la dung innerHTML de dat gia tri cho vung DIV dang table , grid
	if (!isset($v_is_set_innerHTML)) $v_is_set_innerHTML=true;
	
	$v_view_mode = $p_view_mode;
	$v_html_str ="";
	if ($p_input_file_name){
		$v_xml_string_in_file = _read_file($p_xml_file);
	}
	$v_first_col_width = _XML_get_xml_tag_value($v_xml_string_in_file,"common_para","first_col_width");
	$v_second_col_width = (100-$v_first_col_width)."%";
	$v_js_file_name = _XML_get_xml_tag_value($v_xml_string_in_file,"common_para","js_file_name");
	$v_js_function = _XML_get_xml_tag_value($v_xml_string_in_file,"common_para","js_function");

	$arr_item_value = $p_arr_item_value;

	$rax = new RAX();
	$rec = new RAX();
	$rax->open($v_xml_string_in_file);
	$rax->record_delim = $p_xml_tag;
	$rax->parse();
	$rec = $rax->readRecord();
	while ($rec) {
		$table_struct_row = $rec->getRow();
		$v_have_line_before = $table_struct_row["have_line_before"];
		$v_tag_list = $table_struct_row["tag_list"];
		$v_row_id = $table_struct_row["row_id"];
		$v_store_in_child_table = $table_struct_row["store_in_child_table"];
		$v_sql_select_child_table = $table_struct_row["sql_select_child_table"];
		$v_xml_data_column = $table_struct_row["xml_data_column"];
		$v_hide_button = $table_struct_row["hide_button"];

		if (isset($table_struct_row["repeat"])){
			$v_repeat = intval($table_struct_row["repeat"]);
			//echo $v_repeat;
		}else{
			$v_repeat = 0;
		}
		
		if ($v_sql_select_child_table!=""){
			//echo $v_sql_select_child_table.'<br>';
			$arr_all_item = _adodb_query_data_in_name_mode($v_sql_select_child_table);
		}
		//var_dump($arr_all_item);
		$arr_tag = explode(",", $v_tag_list);
		
		//Bang chua mot dong cua form
		$v_html_str = $v_html_str . "<table width='100%'  border='0' cellspacing='0' cellpadding='0'>";
		$v_html_str = $v_html_str . "<col width='$v_first_col_width'>" . "<col width='$v_second_col_width'>";
		if ($v_have_line_before=="true"){
			$v_html_str = $v_html_str . "<tr id = '$v_row_id' style='display:block'>";
			$v_html_str = $v_html_str . "<td colspan='10' id = 'first_$v_row_id'><hr width='100%' color='#66CCFF' size='1'></td>";
			$v_html_str = $v_html_str . "</tr>";
		}
		$v_count = 0;
		$v_html_string_temp = '';
		$v_xml_tag_in_db_list = '';
		// Lay ra so lan lap trong csdl
		if($v_repeat>0){
			if ($v_store_in_child_table=="true"){
				if (sizeof($arr_all_item) > 0){
					$v_repeat = sizeof($arr_all_item);					
				}
			}else{
				$column_rax = new RAX();
				$column_rec = new RAX();
				$column_rax->open($p_xml_string_in_db);
				$column_rax->record_delim = 'data_list';
				$column_rax->parse();
				$column_rec = $column_rax->readRecord();
				$column_row = $column_rec->getRow();
				$v_value = $column_row[$v_xml_tag_in_db];
				$v_repeat_row_in_db = $column_row['repeat_row_in_db_'.$v_row_id];
				if($v_repeat_row_in_db > 0){
					$v_repeat = $v_repeat_row_in_db;
				}
			}
		}
		//
		do{
			$v_html_table = "";
			$v_html_tag = "";

			for($i=0;$i < sizeof($arr_tag);$i++){
				$formfield_rax = new RAX();
				$formfield_rec = new RAX();
				$formfield_rax->open($v_xml_string_in_file);
				$formfield_rax->record_delim = $arr_tag[$i];
				$formfield_rax->parse();
				$formfield_rec = $formfield_rax->readRecord();
				$formfield_row = $formfield_rec->getRow();
				$v_label = $formfield_row["label"];
				$v_type = $formfield_row["type"];
				$v_dataformat = $formfield_row["data_format"];
				$v_input_data = $formfield_row["input_data"];
				$v_url = $formfield_row["url"];
				$v_width = $formfield_row["width"];
				$v_php_function = $formfield_row["php_function"];
				$v_row = $formfield_row["row"];
				$v_max = $formfield_row["max"];
				$v_min = $formfield_row["min"];
				$v_note = $formfield_row["note"];
				$v_message = $formfield_row["message"];
				$v_optional = $formfield_row["optional"];
				$v_maxlength = $formfield_row["maxlength"];
				$v_xml_data = $formfield_row["xml_data"];
				$v_column_name = $formfield_row["column_name"];
				$v_xml_tag_in_db = $formfield_row["xml_tag_in_db"];
				$v_js_function_list = $formfield_row["js_function_list"];
				$v_js_action_list = $formfield_row["js_action_list"];
				$v_relate_recordtype = $formfield_row["relate_recordtype"];

				$v_default_value = $formfield_row["default_value"];
				$v_path_root_to_modul = $formfield_row["path_root_to_module"];
				$v_js_function_after_select = $formfield_row["js_function_after_select"];
				$v_readonly_in_edit_mode = $formfield_row["readonly_in_edit_mode"];
				$v_disabled_in_edit_mode = $formfield_row["disabled_in_edit_mode"];
				$v_session_name = $formfield_row["session_name"];
				//lay du lieu tu session
				$v_session_id_index = $formfield_row["session_id_index"];
				$v_session_name_index = $formfield_row["session_name_index"];
				$v_session_value_index = $formfield_row["session_value_index"];
				$v_channel_sql = $formfield_row["channel_sql"];
				$v_media_file_name_column = $formfield_row["media_name"];
				$v_media_file_url_column = $formfield_row["media_url"];

				$v_path = $formfield_row["path"];
				$v_title = $formfield_row["title"];
				$v_class_name = $formfield_row["class_name"];
				$v_have_title_value = $formfield_row["have_title_value"];
				$v_other_attribute = ($formfield_row["other_attribute"] != '')? $formfield_row["other_attribute"]:'';
				$v_radio_value = $formfield_row["value"];
				$v_align = $formfield_row["align"];
				$v_valign = $formfield_row["valign"];
//				echo '<br>$v_valign='.$v_valign.'$v_type='.$v_type;
				$v_hrf = $formfield_row["hrf"];
				$v_label_list = $formfield_row["label_list"];
				$v_col_width_list = $formfield_row["col_width_list"];
				$v_tbl_sql_string = $formfield_row["tbl_sql_string"];
				$v_col_name_in_db_list = $formfield_row["col_name_in_db_list"];
				$v_col_input_type_list = $formfield_row["col_input_type_list"];
				$v_public_list_code = $formfield_row["public_list_code"];
				$v_display = $formfield_row["display"];
				
				//Cac thuoc tinh cua textbox lay du lieu tu dialog
				$v_textbox_sql = $formfield_row["textbox_sql"];
				$v_textbox_id_column = $formfield_row["textbox_id_column"];
				$v_textbox_name_column = $formfield_row["textbox_name_column"];
				$v_textbox_fuseaction = $formfield_row["textbox_fuseaction"];

				if ($v_repeat>0){
					$p_arr_item_value = $arr_all_item[$v_count];
					$p_xml_string = $p_arr_item_value[$v_xml_data_column];
					//echo htmlspecialchars($p_arr_item_value[$v_xml_data_column])."<br>";
					if ($p_xml_string!="" && $v_xml_data=="true"){
						$column_rax = new RAX();
						$column_rec = new RAX();
						$column_rax->open($p_xml_string);
						$column_rax->record_delim = 'data_list';
						$column_rax->parse();
						$column_rec = $column_rax->readRecord();
						$column_row = $column_rec->getRow();
						//$v_value = $column_row[$v_xml_tag_in_db];
						$v_value = _restore_XML_bad_char($column_row[$v_xml_tag_in_db]);

					}else{
						$v_value = _replace_bad_char($p_arr_all_item[$row_index][$v_column_name]);
						if ($v_dataformat=="isdate"){
							$v_value = _yyyymmdd_to_ddmmyyyy(_replace_bad_char($p_arr_item_value[$v_column_name]));
						}else{
							$v_value = _replace_bad_char($p_arr_item_value[$v_column_name]);
						}
						$v_media_file_name = $p_arr_item_value[$v_media_file_name_column];
						$v_media_file_onclick_url = $p_arr_item_value[$v_media_file_url_column];
					}
					
					if ($v_xml_data=='true'){
						$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,$v_xml_tag_in_db.$v_row_id,",");
						if ($v_dataformat=="isdialog"){//Kiem tra xem co la dialog hay khong de giu gia tri cho cac cot an khi them 1 dong
							$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,"code_".$v_xml_tag_in_db.$v_row_id,",");
							$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,"name_".$v_xml_tag_in_db.$v_row_id,",");
						}
						$v_xml_tag_in_db_original = $v_xml_tag_in_db;
						$v_xml_tag_in_db = $v_xml_tag_in_db.$v_row_id.$v_count;
					}else{
						$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,$v_column_name.$v_row_id,",");
						if ($v_dataformat=="isdialog"){//Kiem tra xem co la dialog hay khong de giu gia tri cho cac cot an khi them 1 dong
							$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,"code_".$v_column_name.$v_row_id,",");
							$v_xml_tag_in_db_list = _list_add($v_xml_tag_in_db_list,"name_".$v_column_name.$v_row_id,",");
						}
						$v_column_name = $v_column_name.$v_row_id.$v_count;
					}
					//echo $v_xml_tag_in_db_list.$v_count."<br>";									
				}else{
					$p_arr_item_value = $arr_item_value;
					$p_xml_string = $p_xml_string_in_db;


					if ($p_xml_string!="" && $v_xml_data=="true"){
						$column_rax = new RAX();
						$column_rec = new RAX();
						$column_rax->open($p_xml_string);
						$column_rax->record_delim = 'data_list';
						$column_rax->parse();
						$column_rec = $column_rax->readRecord();
						$column_row = $column_rec->getRow();
						//$v_value = $column_row[$v_xml_tag_in_db];
						$v_value = _restore_XML_bad_char($column_row[$v_xml_tag_in_db]);

					}else{
						$v_value = _replace_bad_char($p_arr_all_item[$row_index][$v_column_name]);
						if ($v_dataformat=="isdate"){
							$v_value = _yyyymmdd_to_ddmmyyyy(_replace_bad_char($p_arr_item_value[$v_column_name]));
						}else{
							$v_value = _replace_bad_char($p_arr_item_value[$v_column_name]);
						}
						$v_media_file_name = $p_arr_item_value[$v_media_file_name_column];
						$v_media_file_onclick_url = $p_arr_item_value[$v_media_file_url_column];
					}
				}
				//Dat gia gi mac dinh cho doi tuong
				if (trim($v_default_value)!= "" && (is_null($p_arr_item_value) || sizeof($p_arr_item_value)==0 || $p_arr_item_value["chk_save_and_add_new"]=="true")){
					$v_arr_function_valiable = explode('(',$v_default_value);
					if(function_exists($v_arr_function_valiable[0])){
						$v_valiable = $v_arr_function_valiable[1];
						$v_valiable = str_replace(')','',$v_valiable);
						$v_valiable = str_replace('(','',$v_valiable);
						$v_arr_valiable = explode(',',$v_valiable);
						$v_call_user_function_str = "'".trim ($v_arr_function_valiable[0])."'";
						for($i=0;$i<sizeof($v_arr_valiable);$i++){
							$v_call_user_function_str = $v_call_user_function_str . "," . $v_arr_valiable[$i];
						}
						$v_call_user_function_str = "call_user_func(". $v_call_user_function_str . ")";
						eval("\$v_value = ".$v_call_user_function_str.";");
					}
					else{
						$v_value = $v_default_value;
					}
				}
				if ($v_type=="selectbox" || $v_type=="textselectbox"){
					$v_selectbox_option_sql = $formfield_row["selectbox_option_sql"];
					$v_selectbox_id_column = $formfield_row["selectbox_option_id_column"];
					$v_selectbox_name_column = $formfield_row["selectbox_option_name_column"];
					$v_the_first_of_id_value = $formfield_row["the_first_of_id_value"];
				}
				
				if ($v_type=="channel"){
					$v_channel_sql = $formfield_row["channel_sql"];
				}
				
				if ($v_type=="file_attach"){
					$v_file_attach_sql = $formfield_row["file_attach_sql"];
					$v_file_attach_max = $formfield_row["file_attach_max"];
					$v_description_name = $formfield_row["description_name"];
					$v_hide_update_file = $formfield_row["hide_update_file"];
				}
				if ($v_type=="multiplecheckbox" || $v_type=="multipleradio"){
					$v_checkbox_multiple_sql = $formfield_row["checkbox_multiple_sql"];
					$v_checkbox_multiple_id_column = $formfield_row["checkbox_multiple_id_column"];
					$v_checkbox_multiple_name_column = $formfield_row["checkbox_multiple_name_column"];
					$v_direct = $formfield_row["direct"];
					$v_first_width = $formfield_row["first_width"];
				}
				if ($v_type=="multipletextbox"){
					$v_first_width = $formfield_row["first_width"];
					$v_textbox_multiple_sql = $formfield_row["textbox_multiple_sql"];
					$v_textbox_multiple_id_column = $formfield_row["textbox_multiple_id_column"];
					$v_textbox_multiple_name_column = $formfield_row["textbox_multiple_name_column"];

				}
				if ($v_type=="textboxorder"){
					$v_table_name = $formfield_row["table_name"];
					$v_order_column = $formfield_row["order_column"];
					$v_where_clause = $formfield_row["where_clause"];
				}
				if ($v_type=="fileserver"){
					$v_directory = $formfield_row["directory"];
					$v_file_type = $formfield_row["file_type"];
				}
				if ($v_type=="media" || $v_type=="iframe"){
					$v_height = $formfield_row["height"];
				}
				if ($v_type=="labelcontent"){
					$v_content = $formfield_row["content"];
				}
				if(is_null($v_valign) or trim($v_valign) == ''){ 
					if ($v_type=="textarea"){
						$v_valign = "top";
					}else{
						$v_valign = "middle";
					}
				}				
				//$v_html_table = $v_html_table . "<col width='$v_first_col_width'>" . "<col width='$v_second_col_width'>";

				//Kiem tra neu ma form them moi thi cho phep nhap du lieu
				if ($p_view_mode==false && (is_null($v_value) || $v_value=='') && $v_type != "channel"){
					$v_readonly_in_edit_mode = "false";
					$v_disabled_in_edit_mode = "false";
				}
				$v_html_tag = $v_html_tag . _generate_html_input();
				
			}
			if($v_align != '' && !(is_null($v_align))){ 
				$v_align = "align='".$v_align."'";
			}else{
				$v_align = '';
			}
//			echo '<br>$v_valign=='.$v_valign;
			if($v_valign == 'top'){
//				echo htmlspecialchars($v_html_tag);
			}
			$v_html_string_temp = $v_html_string_temp . "<tr id = '$v_row_id' style='display:block'>" . "<td class='normal_label' id = 'first_$v_row_id'  style='display:block' ".$v_align." valign='".$v_valign."'>" . $v_html_tag."</td></tr>";
		$v_count ++;
		}
		while ($v_count<$v_repeat);
			if($v_repeat>0){
				$v_nhay_don = "'";
				$v_html_string_temp = str_replace($v_nhay_don,'"',$v_html_string_temp);
				$v_html_string_of_row = substr($v_html_string_temp,0,strpos($v_html_string_temp,'</tr>')+5);
				//echo htmlspecialchars($v_html_string_of_row);
				//echo $v_xml_tag_in_db_list;
				$v_arr_obj = explode(',',$v_xml_tag_in_db_list);
				for($i = 0; $i<sizeof($v_arr_obj);$i++){
					$v_html_string_of_row = str_replace($v_arr_obj[$i].'0',$v_arr_obj[$i].'#obj_position#',$v_html_string_of_row);
					//echo htmlspecialchars($v_html_string_of_row);exit;
				}
				// Tuan Anh moi them ngay 23/6/07: them bien hidden de chua cac dong bi xoa
				$v_html_str = $v_html_str .'<input type="text" style="display:none" name="repeat_row_in_db_'.$v_row_id.'" hide="true" value="'.$v_repeat.'" optional = true  xml_tag_in_db="repeat_row_in_db_'.$v_row_id.'" xml_data="'.$v_xml_data.'">';
				$v_html_str = $v_html_str .'<input type="hidden" name="hdn_deleted_row_'.$v_row_id.'" value="">';
				if ($v_is_set_innerHTML){
					$v_html_str = $v_html_str .'<DIV id="dynamic_'.$v_row_id.'" style="POSITION: relative;overflow: auto; width: 100%" html_per_row ="" number_row = "'.$v_repeat.'"></DIV>';
					$v_html_str .= '<script>dynamic_'.$v_row_id.'.innerHTML = '.$v_nhay_don.$v_html_string_temp.$v_nhay_don.';dynamic_'.$v_row_id.'.html_per_row = '.$v_nhay_don.$v_html_string_of_row.$v_nhay_don.';</script>';
				}else{
					$v_html_str = $v_html_str . $v_html_string_temp;
					$v_html_str .= '<script>dynamic_'.$v_row_id.'.html_per_row = '.$v_nhay_don.$v_html_string_of_row.$v_nhay_don.';</script>';
				}				
				if ($v_hide_button!="true"){
					$v_html_str .= '<tr id="button_'.$v_row_id.'"><td align = "center" class="normal_link">';
					$v_html_str .= '<input type="button" name="btn_add_row_'.$v_row_id.'" onClick="javascript:add_row_onclick(dynamic_'.$v_row_id.','.$v_nhay_don.$v_row_id.$v_nhay_don.','.$v_nhay_don.$v_xml_tag_in_db_list.$v_nhay_don.' ,dynamic_'.$v_row_id.'.html_per_row)" value="'._CONST_ADD_BUTTON.'" class="small_button">&nbsp;&nbsp;';
					$v_html_str .= '<input type="button" name="btn_delete_row_'.$v_row_id.'" onClick="javascript:del_row_onclick(dynamic_'.$v_row_id.','.$v_nhay_don.$v_row_id.$v_nhay_don.','.$v_nhay_don.$v_xml_tag_in_db_list.$v_nhay_don.' ,dynamic_'.$v_row_id.'.html_per_row)" value="'._CONST_DELETE_BUTTON.'" class="small_button"></td></tr>';
				}
			}else{
				$v_html_str = $v_html_str . $v_html_string_temp;
			}
			$v_html_str = $v_html_str . "</table>";
	
			$rec = $rax->readRecord();
		}	
	
	if($v_js_file_name != '' && !(is_null($v_js_file_name))){
		$v_html_str .= "<script src = '$v_js_file_name'></script>";
	}
	if($v_js_function != '' && !(is_null($v_js_function))){
		$v_html_str .= '<script>try{'.$v_js_function.'}catch(e){;}</script>';
	}

	return $v_html_str;

}
// $p_xml_file: duong dan toi file XML mo ta cac form field
// $p_xml_tag: ten THE XML xac dinh NHANH mo ta cac COT trong danh sach
// $p_arr_all_item: array chua du lieu cua tat ca cac doi tuong
// $p_colume_name_of_xml_string: Ten COT chua chuoi XML
// $p_have_move: Cho phep hien thi mui ten len xuong
// Vi du: _XML_generate_list($v_xml_file, 'col', $arr_all_list, "C_XML_DATA");

function _XML_generate_list($p_xml_file, $p_xml_tag, $p_arr_all_item, $p_colume_name_of_xml_string,$p_have_move=false,$p_number_of_row_per_list="",$p_add_row_blank=true,$p_header_string=""){
	global $v_value,$v_value_id,$v_url,$v_align,$v_inc,$v_selectbox_option_sql,$v_php_function,$row_index,$v_count,$v_current_style_name,$v_id_column;
	global $v_onclick_up,$v_onclick_down,$v_have_move;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode,$v_selectbox_option_sql,$v_selectbox_id_column,$v_selectbox_name_column;
	global $v_table, $v_pk_column,$v_filename_column,$v_content_column,$v_append_column;
	global $p_arr_item;
	global $v_public_list_code,$v_input_data,$v_maxlength;
	global $v_js_function_list,	$v_js_action_list,$v_dataformat;
	global $arr_all_item,$v_current_style_name;
	$v_current_style_name = "round_row";

	$v_have_move = $p_have_move;

	$v_xml_string_in_file = _read_file($p_xml_file);

	$v_function_in_list = _XML_get_xml_tag_value($v_xml_string_in_file,"common_para","function_in_list");

	$v_count = sizeof($p_arr_all_item);

	$arr_all_item = $p_arr_all_item;

	//Bang chua cac thanh phan cua form
	$v_html_string = '';

	$rax = new RAX();
	$rec = new RAX();
	$rax->open($v_xml_string_in_file);
	$rax->record_delim = $p_xml_tag;
	$rax->parse();
	$rec = $rax->readRecord();
	$v_html_string = $v_html_string . '<table class="" width="100%" cellpadding="0" cellspacing="0" border="0" align="center"><tr>';
	$v_html_temp_width = '';
	$v_html_temp_label = '';
	$v_column = 0;
	$v_inc = 0;
	while ($rec) {
		$table_struct_row = $rec->getRow();
		$v_label = $table_struct_row["label"];
		$v_width = $table_struct_row["width"];
		$v_type = $table_struct_row["type"];
		if ($v_type!="hidden"){
			$v_html_temp_width = $v_html_temp_width  . '<col width="'.$v_width.'">';
			//$v_html_temp_label = $v_html_temp_label . '<td><table class="list_table2" width="100%" cellpadding="0" cellspacing="0" border="0" align="center"><tr class="header">';
			//$v_html_temp_label = $v_html_temp_label . '<td class="swath_left"></td>';
			$v_html_temp_label = $v_html_temp_label . '<td >'.$v_label.'</td>';
			//$v_html_temp_label = $v_html_temp_label . '<td class="swath_right"></td>';
			//$v_html_temp_label = $v_html_temp_label . '</tr></table></td>';
			$v_column ++;
		}
		$rec = $rax->readRecord();
	}
	//$v_html_string = $v_html_string  . '<div style="overflow: auto; width: 100%; height:'.(_CONST_HEIGHT_OF_LIST+5).';padding-left:0px;margin:0px">';
	$v_html_string = $v_html_string  . '<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">';
	//$v_html_string = $v_html_string  . '</tr>';
	$v_html_string = $v_html_string  . $v_html_temp_width;
	
	
	if ($p_header_string == ""){
		$v_html_string = $v_html_string  . '<tr class="header">';
		$v_html_string = $v_html_string  . $v_html_temp_label;
		$v_html_string = $v_html_string  . '</tr>';
	}else{
		$v_html_string = $v_html_string  . $p_header_string;		
	}
	
	
	if ($v_count >0){
		for($row_index = 0; $row_index<$v_count; $row_index++){
			$v_url = "";
			$v_str_xml_data = $p_arr_all_item[$row_index][$p_colume_name_of_xml_string];

			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			if (trim($v_function_in_list)!=""){
				call_user_func($v_function_in_list);
			}
			$v_html_string = $v_html_string  .'<tr class="'.$v_current_style_name.'" >';
			$rax = new RAX();
			$rec = new RAX();
			$rax->open($v_xml_string_in_file);
			$rax->record_delim = $p_xml_tag;
			$rax->parse();
			$rec = $rax->readRecord();
			while ($rec) {
				$table_struct_row = $rec->getRow();
				$v_type = $table_struct_row["type"];
				$v_width = $table_struct_row["width"];
				$v_align = $table_struct_row["align"];
				$v_xml_data = $table_struct_row["xml_data"];
				$v_input_data = $table_struct_row["input_data"];
				$v_dataformat = $table_struct_row["data_format"];
				$v_column_name = $table_struct_row["column_name"];
				$v_xml_tag_in_db = $table_struct_row["xml_tag_in_db"];
				$v_php_function = $table_struct_row["php_function"];
				$v_id_column = $table_struct_row["id_column"];
				$v_repeat = $table_struct_row["repeat"];
				$v_selectbox_option_sql = $table_struct_row["selectbox_option_sql"];
				$v_readonly_in_edit_mode = $table_struct_row["readonly_in_edit_mode"];
				$v_disabled_in_edit_mode = $table_struct_row["disabled_in_edit_mode"];
				$v_public_list_code = $table_struct_row["public_list_code"];
				$v_maxlength = $table_struct_row["maxlength"];
				$v_js_function_list = $table_struct_row["js_function_list"];
				$v_js_action_list = $table_struct_row["js_action_list"];

				//Kiem tra neu ma form them moi thi cho phep nhap du lieu
				if ($p_view_mode==false && (is_null($p_arr_all_item) || sizeof($p_arr_all_item)==0)){
					$v_readonly_in_edit_mode = "false";
					$v_disabled_in_edit_mode = "false";
				}
				if ($v_type == "selectbox"){
					$v_selectbox_option_sql = $table_struct_row["selectbox_option_sql"];
					$v_selectbox_id_column = $table_struct_row["selectbox_option_id_column"];
					$v_selectbox_name_column = $table_struct_row["selectbox_option_name_column"];
				}
				if ($v_type =="attachment"){
					$v_table = $table_struct_row["table"];
					$v_pk_column = $table_struct_row["pk_column"];
					$v_filename_column = $table_struct_row["filename_column"];
					$v_content_column = $table_struct_row["content_column"];
					$v_append_column = $table_struct_row["append_column"];
				}


				$arr_xml_tag_in_db = explode(".",$v_xml_tag_in_db);
				if (sizeof($arr_xml_tag_in_db)>1){ //Kiem tra xem the xml can lay co chi ra lay tu cot nao hay khong?
					$v_str_xml_data = $p_arr_all_item[$row_index][$arr_xml_tag_in_db[0]];
					if (is_null($v_str_xml_data)||$v_str_xml_data==""){
						/*$v_str_xml_data = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';*/
						$v_str_xml_data = '<root><data_list></data_list></root>';
					}
					$v_xml_tag_in_db = $arr_xml_tag_in_db[1];
				}
				// Khoi tao bien $v_repeat
				if(!($v_repeat >0)){
					$v_repeat = 1;}
				if ($v_xml_data=="true"){
					$column_rax = new RAX();
					$column_rec = new RAX();
					$column_rax->open($v_str_xml_data);
					$column_rax->record_delim = 'data_list';
					$column_rax->parse();
					$column_rec = $column_rax->readRecord();
					$column_row = $column_rec->getRow();
					$v_value = _restore_XML_bad_char($column_row[$v_xml_tag_in_db]);
					if ($v_value =="") $v_value =" ";
					if ($v_dataformat=="money"){
						$v_value = _data_format($v_value);
					}
					$v_html_string = $v_html_string  . _generate_html_for_column($v_type);
				}else{
					$v_value = _replace_bad_char($p_arr_all_item[$row_index][$v_column_name]);
					$p_arr_item = $p_arr_all_item[$row_index];
					$v_value = $p_arr_all_item[$row_index][$v_column_name];
					if ($v_id_column=="true"){
						$v_value_id = $p_arr_all_item[$row_index][$v_column_name];
						$v_url ="item_onclick('" . $v_value_id . "')";
						$v_onclick_up = "btn_move_updown('".$v_value_id . "','UP')";
						$v_onclick_down = "btn_move_updown('".$v_value_id . "','DOWN')";
					}
					if ($v_value =="") {$v_value =" ";}
					if ($v_dataformat=="money"){
						$v_value = _data_format($v_value);
					}
					for($i=0;$i<$v_repeat;$i++){
					$v_html_string = $v_html_string ._generate_html_for_column($v_type);
					}
				}



				$rec = $rax->readRecord();
			}
			$v_inc = $v_inc + 1;
			$v_html_string = $v_html_string .'</tr>';
		}
	}
	$v_current_style_name = "odd_row";
	if ($v_current_style_name == "odd_row"){
		$v_next_style_name = "round_row";
	}else{
		$v_next_style_name = "odd_row";
	}
	if($p_number_of_row_per_list=="" || is_null($p_number_of_row_per_list)){
		$p_number_of_row_per_list = _CONST_NUMBER_OF_ROW_PER_LIST;
	}
	if ($p_add_row_blank){
		$v_html_string = $v_html_string  . _add_empty_row($v_count,$p_number_of_row_per_list,$v_current_style_name,$v_next_style_name,$v_column);
	}
	$v_html_string = $v_html_string  .'</table>';
	//$v_html_string = $v_html_string  .'</div>';
	return $v_html_string;
}

// Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
function _generate_html_for_multiple_checkbox($arr_list,$IdColumn,$NameColumn,$Valuelist) {
	global $v_xml_tag_in_db,$v_label,$v_tooltip, $v_formfiel_name,$v_current_style_name;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode;
	global $v_view_mode;
	$arr_value = explode(",", $Valuelist);
	$v_count_item = sizeof($arr_list);
	$v_count_value = sizeof($arr_value);
	$v_tr_name = '"tr_'.$v_formfiel_name.'"';
	$v_radio_name = '"rad_'.$v_formfiel_name.'"';
	$strHTML = '';
	//$strHTML = "<DIV title='$v_tooltip' STYLE='overflow: auto; height:105pt;padding-left:5px;margin:0px'>";
	$strHTML = $strHTML . "<table class='list_table2' width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
	if ($v_count_item > 0){
		$i=0;
		//$v_item_url_onclick = "_change_item_checked(this,$v_tr_name,$v_radio_name)";
		$v_item_url_onclick = "";
		while ($i<$v_count_item) {
			$v_item_id = $arr_list[$i][$IdColumn];
			$v_item_name = $arr_list[$i][$NameColumn];
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			$v_item_checked = "";
			$v_item_display = "block";
			if ($Valuelist!=""){ //Kiem tra xem Hieu chinh hay la them moi
				//$v_item_display = "none";
			}
			for ($j=0; $j<$v_count_value; $j++)
			if ($arr_value[$j]==$v_item_id){
				$v_item_checked = "checked";
				$v_item_display = "block";
				break;
			}
			$strHTML = $strHTML . "<tr id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name' style='display:$v_item_display'>";
			if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
				;
			}else{
				$strHTML = $strHTML . "<td><input id='chk_multiple_checkbox' type='checkbox' name='$v_formfiel_name$i' value='$v_item_id' xml_tag_in_db_name ='$v_formfiel_name' $v_item_checked  "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)."  onClick='$v_item_url_onclick' onKeyDown='change_focus(document.forms(0),this)'></td>";
			}
			$strHTML = $strHTML . "<td style='width:100%'>$v_item_name</td></tr>";
			$i++;
		}
	}
	if ($Valuelist!=""){   //Kiem tra xem Hieu chinh hay la them moi
		$v_checked_show_row_all = "";
		$v_checked_show_row_selected = "checked";
	}else{
		$v_checked_show_row_all = "checked";
		$v_checked_show_row_selected = "";
	}
	if ($v_label==""){
		$v_label = "&#273;&#7889;i t&#432;&#7907;ng";
	}else{
		$v_label = _first_stringtolower($v_label);
	}
	$strHTML = $strHTML ."</table>";
	//$strHTML = $strHTML . "</DIV>";
	if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
		;
	}else{
		$strHTML = $strHTML . "<table width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
		$strHTML = $strHTML . "<tr style='display:none'><td class='small_radiobutton' colspan='10' align='right'>";
		$strHTML = $strHTML . "<input type='radio' name='rad_$v_formfiel_name' value='1' hide='true' $v_checked_show_row_all "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." onClick='_show_row_all($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Hi&#7875;n th&#7883; t&#7845;t c&#7843; $v_label";
		$strHTML = $strHTML . "<input type='radio' name='rad_$v_formfiel_name' value='2' hide='true' $v_checked_show_row_selected "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." onClick='_show_row_selected($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Ch&#7881; hi&#7875;n th&#7883; c&#225;c $v_label &#273;&#432;&#7907;c ch&#7885;n";
		$strHTML = $strHTML . "</td></tr>";
		$strHTML = $strHTML ."</table>";
	}
	return $strHTML;
}
// Tao chuoi HTML sinh ra bang
function _generate_html_for_table(){
		global $v_label_list, $v_col_width_list, $v_tbl_sql_string,$v_col_name_in_db_list,$v_col_input_type_list;
		$strHTML = '<DIV STYLE="overflow: auto;width:'.$v_width.'; height:105pt;padding-left:5px;margin:0px">';
		$strHTML = $strHTML . '<table  class="list_table2" width="100%" cellpadding="0" cellspacing="0">';
		$arr_col_width = explode(',',$v_col_width_list);
		$arr_col_label = explode(',',$v_label_list);
		$arr_col_name_in_db_list = explode(',',$v_col_name_in_db_list);
		$arr_col_input_type_list = explode(',',$v_col_input_type_list);
		//var_dump($arr_col_input_type_list);
		for($i=0;$i<sizeof($arr_col_width);$i++){
			$strHTML = $strHTML . '<col width="'.$arr_col_width[$i].'">';
		}
		$v_current_style_name = 'odd_row';
		$strHTML = $strHTML . '<tr class="'.$v_current_style_name.'">';
		for($i=0;$i<sizeof($arr_col_label);$i++){
			$strHTML = $strHTML . '<td align = "center"><b>'.$arr_col_label[$i].'</b></td>';
		}
		$v_arr_all_tbl_content = _adodb_query_data_in_name_mode($v_tbl_sql_string);
		//echo $arr_col_name_in_db_list[2]."Nothing";
		$strHTML = $strHTML . '</tr>';
		for($i=0;$i<sizeof($v_arr_all_tbl_content);$i++){
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			$strHTML = $strHTML . '<tr class="'.$v_current_style_name.'">';
			$j=0;
			for($j=0;$j<sizeof($arr_col_name_in_db_list);$j++){
				$v_col_name = str_replace(' ','',$arr_col_name_in_db_list[$j]);
				$v_col_input_type = str_replace(' ','',$arr_col_input_type_list[$j]);
				switch($v_col_input_type){
					case 'textbox';
						$strHTML = $strHTML . '<td><input type = "text" size="'.$arr_col_width[$j].'" value = "'.$v_arr_all_tbl_content[$i][$v_col_name].'"></td>';
						break;
					case 'checkbox';
						$strHTML = $strHTML . '<td><input type = "checkbox" size="'.$arr_col_width[$j].'" value = "'.$v_arr_all_tbl_content[$i][$v_col_name].'"></td>';
						break;
					default:
						$strHTML = $strHTML . '<td>'.$v_arr_all_tbl_content[$i][$v_col_name].'&nbsp;</td>';
						break;
				}
			}
			$strHTML = $strHTML . '</tr>';
		}
		$strHTML = $strHTML .'</table>';
		$strHTML = $strHTML .'</DIV>';
		return $strHTML;
}
// Tao chuoi HTML de dinh nghia 1 danh sach cac radio
function _generate_html_for_multiple_radio($arr_list,$IdColumn,$NameColumn,$v_value, $v_direct = '') {
	global $v_xml_tag_in_db,$v_label,$v_tooltip, $v_formfiel_name,$v_width,$v_first_width;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode;
	global $v_js_function_list,	$v_js_action_list;
	$v_tr_name = '"tr_'.$v_formfiel_name.'"';
	$v_radio_name = '"rad_'.$v_formfiel_name.'"';
	//$v_item_url_onclick = "document.forms(0).$v_formfiel_name.value=this.value;";
	$v_count_item = sizeof($arr_list);
	$v_radio_checked = "false";
	if ($v_count_item > 0){
		$i=0;
		if($v_direct == 'true'){
			$strHTML = "<DIV title='$v_tooltip' STYLE='overflow: auto; height:105pt;padding-left:5px;margin:0px'>";
			$strHTML = $strHTML . "<table  class='list_table2' width='".$v_width."' cellpadding='0' cellspacing='0'><col width = '".$v_first_width."'><col width = ''>";
			while ($i<$v_count_item) {
				$v_item_id = $arr_list[$i][$IdColumn];
				$v_item_name = $arr_list[$i][$NameColumn];
				$v_item_checked = "";
				if ($v_item_id == $v_value){
					$v_item_checked = "checked";
					$v_radio_checked = "true";
				}
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				$strHTML = $strHTML . "<tr class='$v_current_style_name'>";
				$strHTML = $strHTML . "<td><input type='radio' name='rad_$v_formfiel_name' value='".$v_item_id."' item_name = '".$v_item_name."' xml_tag_in_db_name ='".$v_formfiel_name."' ". $v_item_checked  ." "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." onKeyDown='change_focus(document.forms(0),this)'></td>";
				$strHTML = $strHTML . "<td style='width:100%'>".$v_item_name."</td></tr>";
				$i++;
			}
			$strHTML = $strHTML ."</table>";
			$strHTML = $strHTML . "</DIV>";
		}
		else{
			$strHTML = $strHTML . "<table class='normal_label'  width='".$v_width."' cellpadding='0' cellspacing='0'><col width = '".$v_first_width."'><col width = ''>";
			$strHTML = $strHTML . "<tr class='normal_label'><td>";
			while ($i<$v_count_item) {
				$v_item_id = $arr_list[$i][$IdColumn];
				$v_item_name = $arr_list[$i][$NameColumn];
				$v_item_checked = "";
				if ($v_item_id == $v_value){
					$v_item_checked = "checked";
					$v_radio_checked = "true";
				}
				$strHTML = $strHTML . "<input type='radio' name='rad_$v_formfiel_name' value='".$v_item_id."' item_name = '".$v_item_name."' xml_tag_in_db_name ='".$v_formfiel_name."' ".$v_item_checked  ." "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." onKeyDown='change_focus(document.forms(0),this)'>&nbsp;".$v_item_name;
				$i++;
			}
			$strHTML = $strHTML . "</td></tr>";
			$strHTML = $strHTML ."</table>";
		}
		if($v_count_item > 1 && $v_radio_checked != "true"){
			$strHTML = $strHTML ."<script>";
			$strHTML = $strHTML ."document.forms(0).rad_".$v_formfiel_name."[0].checked = 'true';";
			$strHTML = $strHTML ."document.forms(0).$v_formfiel_name.value=document.forms(0).rad_".$v_formfiel_name."[0].value;";
			$strHTML = $strHTML ."</script>";
		}
		elseif($v_count_item == 1 && $v_radio_checked != "true"){
			$strHTML = $strHTML ."<script>";
			$strHTML = $strHTML ."document.forms(0).rad_".$v_formfiel_name.".checked = 'true';";
			$strHTML = $strHTML ."document.forms(0).$v_formfiel_name.value=document.forms(0).rad_".$v_formfiel_name.".value;";
			$strHTML = $strHTML ."</script>";
		}
	}
	return $strHTML;
}

// Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
function _generate_html_for_tree_user($p_valuelist) {
	global $v_xml_tag_in_db,$v_label,$v_tooltip,$v_formfiel_name;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode;
	global $v_view_mode;
	$arr_all_cooperator = explode(",", $p_valuelist);
	$v_cooperator_count = sizeof($arr_all_cooperator);
	if (trim($p_valuelist)!="" && trim($p_valuelist) != "0"){
		$strHTML = '<table class="list_table2" width="100%%" cellpadding="0" cellspacing="0">';
		$strHTML = $strHTML .'<col width="6%"><col width="27%"><col width="29%"><col width="38%">';
		$strHTML = $strHTML .'<tr  class="header">';
		$strHTML = $strHTML .'<td class="swath_left" width="1%"></td>';
		$strHTML = $strHTML .'<td align="center" class="title" width="4%">STT</td>';
		$strHTML = $strHTML .'<td class="swath_right" width="1%"></td>';
		$strHTML = $strHTML .'<td class="swath_left" width="1%"></td>';
		$strHTML = $strHTML .'<td align="center" class="title" width="25%">H&#7885; t&#234;n</td>';
		$strHTML = $strHTML .'<td class="swath_right" width="1%"></td>';
		$strHTML = $strHTML .'<td class="swath_left" width="1%"></td>';
		$strHTML = $strHTML .'<td align="center" class="title" width="27%">Ch&#7913;c v&#7909</td>';
		$strHTML = $strHTML .'<td class="swath_right" width="1%"></td>';
		$strHTML = $strHTML .'<td class="swath_left" width="1%"></td>';
		$strHTML = $strHTML .'<td align="center" class="title" width="36%">Ph&#242;ng ban</td>';
		$strHTML = $strHTML .'<td class="swath_right" width="1%"></td>';
		$strHTML = $strHTML .'</tr>';
		$strHTML = $strHTML .'</table>';
		//$strHTML = $strHTML ."<DIV title='$v_tooltip' STYLE='overflow: auto; height:100pt;padding-left:0px;margin:0px'>";
		$strHTML = $strHTML .'<table class="list_table2" width="100%" cellpadding="0" cellspacing="0" >';
		$strHTML = $strHTML .'<col width="6%"><col width="27%"><col width="29%"><col width="38%">';
			for($j = 0; $j < $v_cooperator_count; $j++){
				$v_cooperator_id = $arr_all_cooperator[$j];
				$v_cooperator_name = _get_item_attr_by_id($_SESSION['arr_all_staff'],$v_cooperator_id, 'name');
				$v_cooperator_position_name = _get_item_attr_by_id($_SESSION['arr_all_staff'],$v_cooperator_id, 'position_name');
				$v_cooperator_unit_id = _get_item_attr_by_id($_SESSION['arr_all_staff'],$v_cooperator_id, 'unit_id');
				$v_cooperator_unit_name = _get_item_attr_by_id($_SESSION['arr_all_unit'],$v_cooperator_unit_id, 'name');
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				$strHTML = $strHTML .'<tr class="'.$v_current_style_name.'">';
				$strHTML = $strHTML .'<td align="center">'.($j+1).'</td>';
				$strHTML = $strHTML .'<td align="left">'.$v_cooperator_name.'&nbsp;</td>';
				$strHTML = $strHTML .'<td align="left">'.$v_cooperator_position_name.'&nbsp;</td>';
				$strHTML = $strHTML .'<td align="left">'.$v_cooperator_unit_name.'&nbsp;</td>';
				$strHTML = $strHTML .'</tr>';
			}
		$strHTML = $strHTML .'</table>';
		//$strHTML = $strHTML .'</DIV>';
	}
	if (!($v_view_mode && $v_readonly_in_edit_mode=="true")){
		//$strHTML = $strHTML .'<DIV STYLE="overflow: auto; height:100pt; padding-left:0px;margin:0px">';
		$strHTML = $strHTML . "<table class='list_table2'  width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
		$strHTML = $strHTML . '<input type="hidden" name="hdn_item_id" value="">';
		$v_item_unit_id = _get_root_unit_id();
		$arr_unit = _get_arr_all_unit();
		$arr_staff = _get_arr_child_staff($arr_unit);
		$arr_list = _attach_two_array($arr_unit,$arr_staff, 5);
		//var_dump($arr_list);
		$v_current_id = 0;
		$xml_str = _built_XML_tree($arr_list,$v_current_id,'true','home.jpg','home.jpg','user.bmp','false',$p_valuelist,$v_formfiel_name);
		if (PHP_VERSION >= 5) {								
			$xml = new DOMDocument;
			$xml->loadXML($xml_str);
			
			$xsl = new DOMDocument;
			$xsl->load("treeview.xsl");
			
			// Configure the transformer
			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xsl); // attach the xsl rules				
			$ret = $proc->transformToXML($xml);
			$strHTML = $strHTML . "<tr><td>".$ret."</td></tr>";					
		} else {
			$xslt = new Xslt();
			$xslt->setXmlString($xml_str);
			$xslt->setXsl("treeview.xsl");
			if($xslt->transform()) {
				$ret=$xslt->getOutput();
				//echo $ret;
				$strHTML = $strHTML . "<tr><td>".$ret."</td></tr>";
			}else{
				//print("Error:".$xslt->getError());
				$strHTML = $strHTML . "<tr><td>".$xslt->getError()."</td></tr>";
			}			
		}	
		
		$strHTML = $strHTML ."</table>";
		//$strHTML = $strHTML . "</DIV>";
	}
	return $strHTML;
}

// Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
function _generate_html_for_multiple_checkbox_from_session($p_session_name, $p_session_id_index,$session_name_index,$p_valuelist) {
	global $v_xml_tag_in_db,$v_label,$v_tooltip, $v_formfiel_name;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode;
	global $v_view_mode;
	//var_dump($_SESSION[$p_session_name]);
	//$arr_list = $_SESSION[$p_session_name];
	$arr_value = explode(",", $p_valuelist);
	//$v_count_item = sizeof($arr_list);
	$v_count_value = sizeof($arr_value);
	$v_tr_name = '"tr_'.$v_formfiel_name.'"';
	$v_radio_name = '"rad_'.$v_formfiel_name.'"';
	$strHTML = "<DIV title='$v_tooltip' STYLE='overflow: auto; height:105pt;padding-left:5px;margin:0px'>";
	$strHTML = $strHTML . "<table class='list_table2'  width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
	$i = 0;
	foreach($_SESSION[$p_session_name] as $arr_list) {
		$v_item_url_onclick = "_change_item_checked(this,$v_tr_name,$v_radio_name)";
		$v_item_id = $arr_list[$p_session_id_index];
		$v_item_name = $arr_list[$session_name_index];
		if ($v_current_style_name == "odd_row"){
			$v_current_style_name = "round_row";
		}else{
			$v_current_style_name = "odd_row";
		}
		$v_item_checked = "";
		$v_item_display = "block";
		if ($p_valuelist!=""){ //Kiem tra xem Hieu chinh hay la them moi
			$v_item_display = "none";
		}
		for ($j=0; $j<$v_count_value; $j++)
		if ($arr_value[$j]==$v_item_id){
			$v_item_checked = "checked";
			$v_item_display = "block";
			break;
		}
		$strHTML = $strHTML . "<tr id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name' style='display:$v_item_display'>";
		if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
			;
		}else{
			$strHTML = $strHTML . "<td><input id='chk_multiple_checkbox' type='checkbox' name='$v_formfiel_name$i' value='$v_item_id' xml_tag_in_db_name ='$v_formfiel_name' $v_item_checked "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." onClick='$v_item_url_onclick' onKeyDown='change_focus(document.forms(0),this)'></td>";
		}
		$strHTML = $strHTML . "<td style='width:100%'>$v_item_name</td></tr>";
		$i++;
	}
	if ($p_valuelist!=""){   //Kiem tra xem Hieu chinh hay la them moi
		$v_checked_show_row_all = "";
		$v_checked_show_row_selected = "checked";
	}else{
		$v_checked_show_row_all = "checked";
		$v_checked_show_row_selected = "";
	}
	if ($v_label==""){
		$v_label = "&#273;&#7889;i t&#432;&#7907;ng";
	}else{
		$v_label = _first_stringtolower($v_label);
	}
	$strHTML = $strHTML ."</table>";
	$strHTML = $strHTML . "</DIV>";
	if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
		;
	}else{
		$strHTML = $strHTML . "<table width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
		$strHTML = $strHTML . "<tr><td class='small_radiobutton' colspan='10' align='right'>";
		$strHTML = $strHTML . "<input type='radio' name='rad_$v_formfiel_name' value='1' hide='true' $v_checked_show_row_all "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." onClick='_show_row_all($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Hi&#7875;n th&#7883; t&#7845;t c&#7843; $v_label";
		$strHTML = $strHTML . "<input type='radio' name='rad_$v_formfiel_name' value='2' hide='true' $v_checked_show_row_selected "._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." onClick='_show_row_selected($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Ch&#7881; hi&#7875;n th&#7883; c&#225;c $v_label &#273;&#432;&#7907;c ch&#7885;n";
		$strHTML = $strHTML . "</td></tr>";
		$strHTML = $strHTML ."</table>";
	}
	return $strHTML;
}

// Tao chuoi HTML de dinh nghia 1 danh sach cac textbox
function _generate_html_for_multiple_textbox($p_arr_list,$p_index_of_id_column,$p_index_of_name_column,$p_value_list) {
	global $v_xml_tag_in_db,$v_label,$v_tooltip,$v_optional, $v_formfiel_name,$v_dataformat_str,$v_optional_label,$v_message,$v_first_width,$v_width,$v_note;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode;
	//Tach phan ID va phan gia tri
	$arr_of_id_and_value = explode(_CONST_LIST_DELIMITOR._CONST_LIST_DELIMITOR, $p_value_list);
	// Array chua ID
	$arr_id = explode(_CONST_LIST_DELIMITOR, $arr_of_id_and_value[0]);
	// Array chua gia tri
	$arr_value = explode(_CONST_LIST_DELIMITOR, $arr_of_id_and_value[1]);
	//So phan tu cua danh sach
	$v_count_item = sizeof($p_arr_list);
		//Dem xem co bao nhieu gia tri
	$v_count_value = sizeof($arr_value);
	$v_tr_name = '"tr_'.$v_formfiel_name.'"';
	$v_radio_name = '"rad_'.$v_formfiel_name.'"';
	$v_second_with = (100-$v_first_width);
	$strHTML = "<DIV title='$v_tooltip' STYLE='overflow: auto; height:105pt;padding-left:5px;margin:0px'>";
	$strHTML = $strHTML . "<table class='list_table2'  width='100%' border='0' cellpadding='0' cellspacing='0'>";
	$strHTML = $strHTML . "<col width='$v_first_width%'><col width='$v_second_with%'>";
	if ($v_count_item > 0){
		$i=0;
		//$v_item_url_onclick = "_change_item_checked(this,$v_tr_name,$v_radio_name)";
		while ($i<$v_count_item) {
			$v_item_id = $p_arr_list[$i][$p_index_of_id_column];
			$v_item_name = $p_arr_list[$i][$p_index_of_name_column];
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			$v_item_checked = "";
			$v_item_display = "block";

			for ($j=0; $j<$v_count_value; $j++)
				if ($arr_id[$j]==$v_item_id){
					$v_value = trim($arr_value[$i]);
					break;
			}
			$strHTML = $strHTML . "<tr id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name' style='display:$v_item_display'>";
			$strHTML = $strHTML . "<td style='display:none'><input id='chk_multiple_textbox' type='checkbox' hide='true' name='chk_multiple_textbox' checked value='$v_item_id' xml_tag_in_db_name ='$v_formfiel_name' "._generate_property_type("optional",$v_optional)." $v_item_checked  onClick='$v_item_url_onclick' onKeyDown='change_focus(document.forms(0),this)'></td>";
			$strHTML = $strHTML . "<td class='normal_label'>$v_item_name"."$v_optional_label</td>";
			$strHTML = $strHTML . "<td class='normal_label'><input id='txt_multiple_textbox' type='textbox' name='$v_formfiel_name$i' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." style='width:$v_width%' value='$v_value' xml_tag_in_db_name ='$v_formfiel_name' $v_dataformat_str message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>$v_note</td>";
			$i++;
		}
	}
	$strHTML = $strHTML ."</table>";
	$strHTML = $strHTML . "</DIV>";
	//$strHTML = $strHTML . "<table width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
	//$strHTML = $strHTML . "<tr><td class='small_radiobutton' colspan='10' align='right'>";
	//$strHTML = $strHTML . "<input type='radio' id=$v_radio_name value='1' $v_checked_show_row_all onClick='_show_row_all($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Hi&#7875;n th&#7883; t&#7845;t c&#7843; $v_label";
	//$strHTML = $strHTML . "<input type='radio' id=$v_radio_name value='2' $v_checked_show_row_selected onClick='_show_row_selected($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms(0),this)'>Ch&#7881; hi&#7875;n th&#7883; c&#225;c $v_label &#273;&#432;&#7907;c ch&#7885;n";
	//$strHTML = $strHTML . "</td></tr>";
	//$strHTML = $strHTML ."</table>";
	return $strHTML;
}
// Tao chuoi HTML cho cac form field
function _generate_html_input(){
	global $_ISA_IMAGE_URL_PATH,$_ISA_LIB_URL_PATH,$_ISA_WEB_SITE_PATH,$_ISA_LIST_WEB_SITE_PATH;

	global $v_label, $v_type, $v_dataformat,$v_message,$v_optional,$v_xml_data,$v_column_name,$v_xml_tag_in_db,$v_readonly_in_edit_mode,$v_disabled_in_edit_mode,$v_note,$v_relate_recordtype,$v_width,$v_first_width,$v_row, $v_row_id, $v_max, $v_min, $v_maxlength,$v_tooltip,$v_listtype_filter,$v_list_id,$v_count;
	global $v_selectbox_option_sql,$v_selectbox_id_column, $v_selectbox_name_column,$v_function_value;
	global $v_checkbox_multiple_sql,$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column ,$v_the_first_of_id_value,$v_direct ;
	global $v_textbox_multiple_sql,$v_textbox_multiple_id_column,$v_textbox_multiple_name_column ;
	global $v_file_attach_sql,$v_file_attach_max,$v_description_name,$v_hide_update_file;
	global $v_table_name, $v_order_column,$v_where_clause; //Cac bien textorder
	global $v_js_function_list,	$v_js_action_list,$v_dataformat_str,$v_optional_label,$v_formfiel_name;
	global $v_directory,$v_file_type;
	global $v_value,$i,$v_row,$v_row_id, $v_counter_file_attack;
	global $v_js_function_after_select,$v_path_root_to_modul;
	global $v_input_data, $v_url,$v_session_name, $v_session_id_index,$v_session_name_index,$v_session_value_index;
	global $v_height, $v_width;
	global $v_channel_sql;
	global $v_path, $v_other_attribute;
	global $v_radio_value,$v_php_function,$v_content;
	global $v_media_file_onclick_url,$v_media_file_name;
	global $v_title,$v_class_name;
	global $v_have_title_value,$v_default_value;
	global $v_hrf,$v_label_list,$v_col_width_list,$v_tbl_sql_string,$v_col_name_in_db_list,$v_col_input_type_list;
	global $v_view_mode;
	global $v_public_list_code;
	global $v_store_in_child_table;
	global $v_textbox_sql, $v_textbox_id_column,$v_textbox_name_column,	$v_textbox_fuseaction;
	global $v_display;

	//Sinh ra cac thuoc tinh dung cho viec kiem hop du lieu tren form
	$v_dataformat_str = _generate_verify_property($v_dataformat);
	$v_url_path_calendar = '"'.$_ISA_LIB_URL_PATH.'isa-calendar/"';
	$v_optional_label = "";
	if ($v_optional=="false"){
		$v_optional_label = "<small class='normal_starmark'>*</small>";
	}
	if ($i==0){
		$v_str_label = $v_label.$v_optional_label."&nbsp;&nbsp;</td><td id = 'second_$v_row_id'  style='display:block' class='normal_label'>";
	}else{
		$v_str_label = "&nbsp;".$v_label.$v_optional_label."&nbsp;";
	}
	$v_checked = "";

	if ($v_xml_data=='true'){
		$v_formfiel_name = $v_xml_tag_in_db;
	}else{
		$v_formfiel_name = $v_column_name;
	}
	switch($v_type) {
		case "table";
			//style="overflow: auto; width: 100%; height:'.(_CONST_HEIGHT_OF_LIST+5);
			$v_ret_html = $v_label.$v_optional_label;
			$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'><td>";
			$v_ret_html = $v_ret_html . _generate_html_for_table();
			$v_ret_html = $v_ret_html . "</td></tr>";
			break;
		case "label";
			if($v_class_name !=""){
				$v_ret_html = "<span class='".$v_class_name."'>".$v_label.$v_optional_label."</span>&nbsp;";
			}else{
				$v_ret_html = $v_label.$v_optional_label."&nbsp;";
			}
			break;
		case "link";
			$v_hrf = str_replace('"','&quot;',$v_hrf);
			$v_ret_html = '&nbsp;&nbsp;</td><td id = "second_$v_row_id"  style="display:block" class="normal_link"><a href="'.$v_hrf.'">'.$v_label.'</a>';
//			$v_ret_html = '<a href="'.$v_hrf.'">'.$v_label.'</a>';
			break;
		case 'small_title':
			$v_ret_html .= $v_str_label . '<label class="small_title" valign="bottom">' . $v_value . '</label>';
			break;
		case "media_file";
			$v_ret_html .= '<td class=\"normal_label\">';
			$v_ret_html .= '<object id="MediaPlayer" classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">';
			$v_ret_html .= '<param id="MediaPlayer_FileName" name="filename" value="'.$v_media_file_onclick_url.'">';
			$v_ret_html .= '<param name="Showcontrols" value="True"><param name="autoStart" value="False">';
			$v_ret_html .= '</object></td>';
			break;
		case "relaterecord";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html . "<input type='textbox' name='$v_formfiel_name' class='normal_textbox' value='$v_value' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." $v_dataformat_str xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>&nbsp;";
			$v_ret_html = $v_ret_html . "<input type='hidden' name='hdn_relate_record_code' value=''>";
			if ($v_value == ""){
				$v_ret_html = $v_ret_html . "<input type='button' name='btn_submit' style='width:auto' title='$v_tooltip' value='L&#7845;y th&#244;ng tin t&#7915; h&#7891; s&#417; li&#234;n quan' class='small_button' onClick='show_modal_dialog_all_record_onclick(&quot;".$_ISA_WEB_SITE_PATH."record/archives/index.php&quot;,&quot;DISPLAY_ALL_RECORD_ARCHIVES&quot;,&quot;$v_relate_recordtype&quot;,document.forms(0).$v_formfiel_name,document.forms(0).hdn_relate_record_code,&quot;DISPLAY_SINGLE_PROJECT&quot;);'>";
			}else{
				$arr_single_record_by_code = _adodb_query_data_in_name_mode("Onegate_RecordGetSingleByCode '$v_value'");
				$v_record_id = $arr_single_record_by_code[0]['PK_RECORD'];
				$v_recordtype = $arr_single_record_by_code[0]['FK_RECORDTYPE'];
				if ($v_record_id>0){
					$v_ret_html = $v_ret_html . "<a href='".$_ISA_WEB_SITE_PATH."record/archives/index.php?fuseaction=DISPLAY_SINGLE_LICENSE&hdn_recordtype_filter=$v_recordtype&hdn_record_id=$v_record_id'>N&#7897;i dung c&#7911;a h&#7891; s&#417; li&#234;n quan</a>";
				}
			}
			break;
		case "file_upload";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html ."<input type='file' name='file_media_upload' value='$v_value' class='normal_textbox' title='$v_tooltip' style='width:$v_width' onKeyDown='change_focus(document.forms(0),this)'"._generate_event_and_function($v_js_function_list, $v_js_action_list).">";
			$v_ret_html = $v_ret_html . $v_note;
			break;
		case "file";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html ."<input type='file' name='file_attach' value='$v_value' class='normal_textbox' title='$v_tooltip' style='width:$v_width' onKeyDown='change_focus(document.forms(0),this)'"._generate_event_and_function($v_js_function_list, $v_js_action_list).">";
			$v_ret_html = $v_ret_html . $v_note;
			break;
		case "fileclient";
			$v_file_attack_name= "txt_xml_file_name". $v_counter_file_attack;
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html ."<input type='text' name='$v_formfiel_name' style='display:none' class='normal_textbox' title='$v_tooltip' value='$v_value'  style='width:$v_width' style='border=0' readonly  $v_dataformat_str xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode).">";
			$v_ret_html = $v_ret_html ."<input type='file' name='$v_file_attack_name' value='$v_value' class='normal_textbox' title='$v_tooltip' style='width:$v_width' onKeyDown='change_focus(document.forms(0),this)' OnChange='GetFileName(this,document.forms(0).".$v_formfiel_name.")'>";
			$v_ret_html = $v_ret_html . "";
			$v_ret_html = $v_ret_html . $v_note;
			$v_counter_file_attack= $v_counter_file_attack +1;
			break;
		case "fileserver";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html . "<input type='textbox' name='$v_formfiel_name' class='normal_textbox' value='$v_value' directory='$v_directory' title='$v_tooltip' style='width:$v_width' xml_data='$v_xml_data' "._generate_event_and_function($v_js_function_list, $v_js_action_list)._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)." $v_dataformat_str xml_tag_in_db='$v_xml_tag_in_db' message='$v_message' onKeyDown='change_focus(document.forms(0),this)' readonly>&nbsp;&nbsp;";
			$v_ret_html = $v_ret_html . "<input type='button' name='btn_choose' class='select_button' value='Ch&#7885;n' OnClick=\"_btn_show_all_file(document.forms(0).$v_formfiel_name.directory,'$v_file_type',document.forms(0).$v_formfiel_name);\" onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . $v_note;
			break;
		case "file_attach";
			$arr_attach =_adodb_query_data_in_name_mode($v_file_attach_sql);
			//var_dump($arr_attach);
			$v_ret_html = $v_str_label.'<br>';
			$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'><td colspan='2'>" . process_attach($arr_attach,$v_file_attach_max,$v_description_name);
			$v_ret_html = $v_ret_html . "</td></tr>";
			break;			
		case "textbox";
//			$v_value = "AA".$v_value;
//			echo $v_formfiel_name.$v_value."$v_readonly_in_edit_mode<br>";
			$v_ret_html = $v_str_label;
			if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
				$v_ret_html = $v_ret_html . $v_value;
			}else{
				if ($v_dataformat == "isdate"){
					$v_ret_html = $v_ret_html . '<input type="textbox" name="'.$v_formfiel_name.'" class="normal_textbox" value="'.$v_value.'" title="'.$v_tooltip.'" style="width:'.$v_width.'" '._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list).' '.$v_dataformat_str.' store_in_child_table="'.$v_store_in_child_table.'" xml_tag_in_db="'.$v_xml_tag_in_db.'" xml_data="'.$v_xml_data.'" column_name="'.$v_column_name.'" message="'.$v_message.'" onKeyDown="change_focus(document.forms(0),this)">';
					$v_ret_html = $v_ret_html . "<img src='". $_ISA_IMAGE_URL_PATH."calendar.gif' border='0' title='$v_tooltip' onclick='DoCal($v_url_path_calendar,document.forms(0).$v_formfiel_name);' style='cursor:hand'>";
				}elseif($v_dataformat == "isdialog"){
					$arr_list_item = _adodb_query_data_in_name_mode($v_textbox_sql);
					if ($v_path_root_to_modul!=""){
						$v_path_root_to_modul = $v_path_root_to_modul."/";
					}
					//echo $v_value;
					$v_ret_html = $v_ret_html . "<input type='textbox' name='name_$v_formfiel_name' class='normal_textbox'  value='"._get_value_from_array($arr_list_item,$v_textbox_id_column,$v_textbox_name_column,$v_value)."' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly","true")._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_data='$v_xml_data' xml_tag_in_db='name_$v_xml_tag_in_db' column_name='name_$v_column_name' message='$v_message'  onKeyDown='change_focus(document.forms(0),this)'>";
					if ($v_readonly_in_edit_mode=="false"){
						$v_ret_html = $v_ret_html . "<img border='0' src='".$_ISA_IMAGE_URL_PATH."find.gif' width='18' height='18' title='$v_tooltip' class='normal_image' onClick='show_modal_dialog_treeview_onclick(&quot;".$_ISA_WEB_SITE_PATH.$v_path_root_to_modul."index.php&quot;,&quot;$v_textbox_fuseaction&quot;,document.forms(0).name_$v_formfiel_name,document.forms(0).code_$v_formfiel_name,document.forms(0).$v_formfiel_name,-1);$v_js_function_after_select'>";
					}
					$v_ret_html = $v_ret_html . "<input type='hidden' name='code_$v_formfiel_name'><input type='textbox' style='width:0;visibility:hidden' name='$v_formfiel_name' value='$v_value' hide='true' xml_data='$v_xml_data' xml_tag_in_db='$v_xml_tag_in_db' column_name='$v_column_name' optional='true' store_in_child_table='".$v_store_in_child_table."' >";
				}elseif($v_dataformat == "isuser"){
					if ($v_path_root_to_modul!=""){
						$v_path_root_to_modul = $v_path_root_to_modul."/";
					}
					$v_ret_html = $v_ret_html . "<input type='hidden' name='code_$v_formfiel_name'><input type='hidden' name='$v_formfiel_name' value='$v_value' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' store_in_child_table='".$v_store_in_child_table."' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
					$v_ret_html = $v_ret_html . "<input type='textbox' name='name_$v_formfiel_name' class='normal_textbox' value='"._get_item_attr_by_id($_SESSION['arr_all_staff'],$v_value,'name')."' readonly title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." $v_dataformat_str store_in_child_table='".$v_store_in_child_table."' xml_tag_in_db='$v_xml_tag_in_db' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
					if ($v_readonly_in_edit_mode=="false"){
						$v_ret_html = $v_ret_html . "<img border='0' src='".$_ISA_IMAGE_URL_PATH."find.gif' width='18' height='18' title='$v_tooltip' class='normal_image' onClick='show_modal_dialog_treeview_onclick(&quot;".$_ISA_WEB_SITE_PATH.$v_path_root_to_modul."index.php&quot;,&quot;DISPLAY_ALL_STAFF_BY_UNIT&quot;,document.forms(0).name_$v_formfiel_name,document.forms(0).code_$v_formfiel_name,document.forms(0).$v_formfiel_name,-1);$v_js_function_after_select'>";
					}
				}else{
					$v_ret_html = $v_ret_html . '<input type="textbox" name="'.$v_formfiel_name.'" class="normal_textbox" value="'.$v_value.'" title="'.$v_tooltip.'" store_in_child_table="'.$v_store_in_child_table.'" style="width:'.$v_width.'" '._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list).' '.$v_dataformat_str.' store_in_child_table="'.$v_store_in_child_table.'" xml_tag_in_db="'.$v_xml_tag_in_db.'" xml_data="'.$v_xml_data.'" column_name="'.$v_column_name.'" message="'.$v_message.'" maxlength="'.$v_maxlength.'" ';
					if (rtrim($v_max) != '' && !is_null($v_max)){
						 $v_ret_html = $v_ret_html .' max="'.$v_max.'"';
					}
					if (rtrim($v_min) != '' && !is_null($v_min)){
						 $v_ret_html = $v_ret_html .' min="'.$v_min.'"';
					}
					$v_ret_html = $v_ret_html . ' onKeyDown="change_focus(document.forms(0),this)">';
				}
				$v_ret_html = $v_ret_html . $v_note;
			}
			break;
		case "text";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html.'<span class="data">'.$v_value.'&nbsp;</span>';
			break;
		case "identity";
			$v_value = $v_count+1;
			if ($v_value < 10){
				$v_ret_html = $v_ret_html.'<span class="data">0'.$v_value.'</span>';
			}else{
				$v_ret_html = $v_ret_html.'<span class="data">'.$v_value.'</span>';
			}
			break;
		case "checkbox";
			
			if ($v_value == "true" || $v_value==1){
				$v_checked = " checked ";
			}else{
				$v_checked = " ";
			}
			if($v_label != '' || $v_optional_label != ''){
				$v_ret_html = "&nbsp;&nbsp;</td><td class='normal_label'>";
			}else{
				$v_ret_html = "";
			}
			$v_ret_html = $v_ret_html ."<input type='checkbox' name='$v_formfiel_name' class='normal_checkbox' title='$v_tooltip' $v_checked value='1' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . "" . $v_label .$v_optional_label."";
			break;
		case "radio";
			if ($v_radio_value == $v_value || $v_value == "true"){
				$v_checked = " checked ";
			}else{
				$v_checked = " ";
			}
			$v_ret_html =  "<input type='radio' name='$v_row_id' class='normal_checkbox' $v_checked value='$v_radio_value' title='$v_tooltip' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . "" . $v_label .$v_optional_label."";
			break;
		case "textarea";
			$v_ret_html = $v_str_label;
			if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
				$v_ret_html = $v_ret_html . $v_value;
			}else{
				$v_ret_html = $v_ret_html . '<textarea class="normal_textarea" name="'.$v_formfiel_name.'" rows="'.$v_row.'" title="'.$v_tooltip.'" style="width:'.$v_width.'" '._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list).' xml_tag_in_db="'.$v_xml_tag_in_db.'" xml_data="'.$v_xml_data.'" column_name="'.$v_column_name.'" message="'.$v_message.'">'.$v_value.'</textarea>';
			}
			break;
		case "selectbox";
			$v_ret_html = $v_str_label;
			if ($v_view_mode && $v_readonly_in_edit_mode=="true"){
				if ($v_input_data == "session"){
					$j = 0;
					$arr_list_item = array();
					if (isset($_SESSION[$v_session_name])){
						foreach($_SESSION[$v_session_name] as $arr_item) {
							$arr_list_item[$j] = $arr_item;
							$j++;
						}
					}
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_session_id_index];
					}
					$v_ret_html = $v_ret_html ._get_value_from_array($arr_list_item,$v_session_id_index,$v_session_name_index,$v_value);
				}elseif ($v_input_data == "isalist"){
					$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
					$arr_list_item = _convert_xml_string_to_array($v_xml_data_in_url,"item");
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_selectbox_id_column];
					}
					$v_ret_html = $v_ret_html ._get_value_from_array($arr_list_item,$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
				}else{
					$arr_list_item = _adodb_query_data_in_number_mode($v_selectbox_option_sql);
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_selectbox_id_column];
					}
					$v_ret_html = $v_ret_html ._get_value_from_array($arr_list_item,$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
				}
			}else{
				if ($v_input_data == "session"){
					$j = 0;
					$arr_list_item = array();
					if (isset($_SESSION[$v_session_name])){
						foreach($_SESSION[$v_session_name] as $arr_item) {
							$arr_list_item[$j] = $arr_item;
							$j++;
						}
					}
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_session_id_index];
					}
					$v_ret_html = $v_ret_html . "<select id='$v_formfiel_name' class='normal_selectbox' name='$v_formfiel_name' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)' >";
					if ($v_the_first_of_id_value == "true"){
						$v_ret_html = $v_ret_html . _generate_select_option($arr_list_item,$v_session_id_index,$v_session_value_index,$v_session_name_index,$v_value);
					}else{
						$v_ret_html = $v_ret_html . "<option id='' value='' name=''>--- Ch&#7885;n $v_label ---</option>"._generate_select_option($arr_list_item,$v_session_id_index,$v_session_value_index,$v_session_name_index,$v_value);
					}	
					$v_ret_html = $v_ret_html . "</select>";

				}elseif ($v_input_data == "isalist"){
					$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
					$arr_list_item = _convert_xml_string_to_array($v_xml_data_in_url,"item");
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_selectbox_id_column];
					}
					$v_ret_html = $v_ret_html . "<select id='$v_formfiel_name' class='normal_selectbox' name='$v_formfiel_name' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)' >";
					if(is_null($v_have_title_value) || ($v_have_title_value=="") || ($v_have_title_value=="true")){
						if($v_the_first_of_id_value != "true"){
							$v_ret_html = $v_ret_html . "<option id='' value='' name=''>--- Ch&#7885;n $v_label ---</option>";
						}	
					}

					$v_ret_html = $v_ret_html ._generate_select_option($arr_list_item,$v_selectbox_id_column,$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
					$v_ret_html = $v_ret_html . "</select>";
				}else{
					$arr_list_item = _adodb_query_data_in_number_mode($v_selectbox_option_sql);
					if ( $v_the_first_of_id_value =="true" && $v_value == "" ){
						$v_value = $arr_list_item[0][$v_selectbox_id_column];
					}
					$v_ret_html = $v_ret_html . "<select id='$v_formfiel_name' class='normal_selectbox' name='$v_formfiel_name' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)' >";
					if(is_null($v_have_title_value) || ($v_have_title_value=="") || ($v_have_title_value=="true")){
						if($v_the_first_of_id_value != "true"){
							$v_ret_html = $v_ret_html . "<option id='' value='' name=''>--- Ch&#7885;n $v_label ---</option>";
						}	
					}
					$v_ret_html = $v_ret_html ._generate_select_option($arr_list_item,$v_selectbox_id_column,$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
					$v_ret_html = $v_ret_html . "</select>";
				}
			}
			break;
		case "multiplecheckbox";
			$v_ret_html = $v_label.$v_optional_label;
			if ($v_input_data == "session"){
				$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
				$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
				$v_ret_html = $v_ret_html . "</td><td colspan='10'>"._generate_html_for_multiple_checkbox_from_session($v_session_name, $v_session_id_index,$v_session_name_index,$v_value);
				$v_ret_html = $v_ret_html . "</td></tr>";
			}elseif ($v_input_data == "isalist"){
				$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
				$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
				$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
				$v_ret_html = $v_ret_html . "</td><td colspan='10'>"._generate_html_for_multiple_checkbox(_convert_xml_string_to_array($v_xml_data_in_url,"item"),$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column,$v_value);
				$v_ret_html = $v_ret_html . "</td></tr>";
			}else{
				$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
				$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
				$v_ret_html = $v_ret_html . "</td><td colspan='10'>"._generate_html_for_multiple_checkbox(_adodb_query_data_in_number_mode($v_checkbox_multiple_sql),$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column,$v_value);
				$v_ret_html = $v_ret_html . "</td></tr>";
			}
			break;
		case "multipleradio";
			if ($v_input_data == "isalist"){
				$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
				$arr_list_item = _convert_xml_string_to_array($v_xml_data_in_url,"item");
			}else{
				$arr_list_item = _adodb_query_data_in_number_mode($v_checkbox_multiple_sql);
			}
			if($v_direct == 'true'){
				$v_ret_html = $v_label.$v_optional_label;
				$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
				$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='$v_value' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'></td>";
				$v_ret_html = $v_ret_html . "<td>"._generate_html_for_multiple_radio($arr_list_item,$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column,$v_value,$v_direct);
				$v_ret_html = $v_ret_html . "</td></tr>";
			}else{
				if($v_label != "" && isset($v_label)){
					$v_ret_html = $v_label.$v_optional_label;
					$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='$v_value' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'></td>";
					$v_ret_html = $v_ret_html . "<td>"._generate_html_for_multiple_radio($arr_list_item,$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column,$v_value,$v_direct);
					$v_ret_html = $v_ret_html . "</td>";
				}
				else{
					$v_ret_html = $v_label.$v_optional_label;
					$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='$v_value' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'></td>";
					$v_ret_html = $v_ret_html . _generate_html_for_multiple_radio($arr_list_item,$v_checkbox_multiple_id_column,$v_checkbox_multiple_name_column,$v_value,$v_direct);
				}
			}
			break;
		case "multipletextbox";
			if ($v_input_data == "isalist"){
				$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
				$arr_list_item = _convert_xml_string_to_array($v_xml_data_in_url,"item");
			}elseif($v_input_data == "session"){
				$j = 0;
				$arr_list_item = array();
				if (isset($_SESSION[$v_session_name])){
					foreach($_SESSION[$v_session_name] as $arr_item) {
						$arr_list_item[$j] = $arr_item;
						$j++;
					}
				}
				$v_textbox_multiple_id_column = $v_session_id_index;
				$v_textbox_multiple_name_column = $v_session_name_index;
			}else{
				$arr_list_item = _adodb_query_data_in_number_mode($v_textbox_multiple_sql);
			}
			
			$v_ret_html = $v_label;
			$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
			$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
			$v_ret_html = $v_ret_html . "</td><td colspan='10'>"._generate_html_for_multiple_textbox($arr_list_item,$v_textbox_multiple_id_column,$v_textbox_multiple_name_column,$v_value);
			$v_ret_html = $v_ret_html . "</td></tr>";
			break;
		case "treeuser";
			$v_ret_html = $v_label;
			$v_ret_html = $v_ret_html . "<tr id = '$v_row_id' style='display:block'>";
			$v_ret_html = $v_ret_html . "<td style='display:none'><input type='textbox' name='$v_formfiel_name' value='' hide='true' readonly "._generate_property_type("optional",$v_optional)." xml_data='true' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
			$v_ret_html = $v_ret_html . "</td><td colspan='10'>"._generate_html_for_tree_user($v_value);
			$v_ret_html = $v_ret_html . "</td></tr>";
			break;
		case "textboxorder";
			$v_ret_html = $v_str_label;
			if(is_null($v_value) || $v_value==""){
				$v_value = _get_next_value("T_ISALIB_LIST","C_ORDER","FK_LISTTYPE = ".$v_listtype_filter);
				if(!is_null($v_table_name) && $v_table_name!=""){
					$v_value = _get_next_value($v_table_name,$v_order_column,$v_where_clause);
				}
			}
			$v_ret_html = $v_ret_html . "<input type='textbox' name='$v_formfiel_name' class='normal_textbox' value='$v_value' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." $v_dataformat_str xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' min='$v_min' max='$v_max' maxlength='$v_maxlength' onKeyDown='change_focus(document.forms(0),this)'>";
			break;
		case "checkboxstatus";
			if ($v_value == "true" || $v_value==1){
				$v_checked = " checked ";
			}
			$v_ret_html = "&nbsp;&nbsp;</td><td class='normal_label'>";
			$v_ret_html = $v_ret_html ."<input type='checkbox' name='$v_formfiel_name' class='normal_checkbox' title='$v_tooltip' $v_checked value='1' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name'  message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . "" . $v_label .$v_optional_label."";
			break;
		case "button";
			if(is_null($v_class_name) || ($v_class_name=="")){
				$v_class_name = "small_button";
			}
			$v_ret_html = $v_ret_html . "&nbsp;&nbsp;<input type='button' name='$v_formfiel_name' class='$v_class_name' value='$v_label' title='$v_tooltip' style='width:$v_width' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." $v_dataformat_str xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . $v_note;
			break;
		case "hidden";	
			$v_ret_html = $v_ret_html . "<input type='textbox' style='width:0;visibility:hidden' readonly='true' name='$v_formfiel_name' value='$v_value' hide='true' xml_data='$v_xml_data' "._generate_property_type("optional",$v_optional)." store_in_child_table='".$v_store_in_child_table."' xml_tag_in_db='$v_xml_tag_in_db' message='$v_message'>";
			break;
		case "image";
			$v_image_type = _list_get_last($v_value,'.');// Lay phan mo rong cua file anh
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html.'';
			if($v_width =='' || $v_width <=0 || is_null($v_width)) $v_width = 100;
			if (strtoupper($v_image_type) != 'SWF' ){
				$v_ret_html .= '<img src="'.$v_path.'" name="'.$v_formfiel_name.'" title="'.$v_tooltip.'"';
				$v_ret_html .= ' id="'.$v_formfiel_name.'"  width="'.$v_width.'"';
				$v_ret_html .= _generate_property_type("optional",$v_optional);
				$v_ret_html .= _generate_event_and_function($v_js_function_list, $v_js_action_list);
				$v_ret_html .= ($v_xml_tag_in_db == '')?'':' xml_tag_in_db="'.$v_xml_tag_in_db.'"';
				$v_ret_html .= ($v_xml_data =='')?'':' xml_data="'.$v_xml_data.'"';
				$v_ret_html .= ($v_column_name == '')?'':' column_name="'.$v_column_name.'"';
				$v_ret_html .= $v_other_attribute .' />';
			}
			else{
				$v_ret_html .= '<object id="'.$v_formfiel_name.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$v_width.'">
									<param name="movie" value="'.$v_path.'">
									<param name="quality" value="high">
									<embed name="swf" src="'.$v_path.'">"
										quality="high"
										pluginspage="http://www.macromedia.com/go/getflashplayer"
										type="application/x-shockwave-flash"
										width="'.$v_width.'" >
									</embed>
								</object>';
			}
			break;
		case "media";
			$v_ret_html = "&nbsp;&nbsp;<td class='normal_label'>";
			$v_ret_html = $v_ret_html . "<OBJECT id = 'Player'
					CLASSID = 'CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6' height = ".$v_height." width = ".$v_width.">
						<PARAM Name = 'AutoStart' value = '0'>
						<PARAM Name = 'uiMode' value = 'invisible'>
				</OBJECT>";
			break;
		case "iframe";
			if(is_null($v_height) || empty($v_height) || $v_height <=0 || $v_height == ''){$v_height = _CONST_HEIGHT_OF_LIST;};
			$v_ret_html = $v_ret_html . $v_label;
			$v_ret_html = $v_ret_html . '<tr><td>';
			$v_ret_html = $v_ret_html . '<IFRAME';
			$v_ret_html = $v_ret_html . ' ID="'.$v_formfiel_name.'"';
			$v_ret_html = $v_ret_html . ' NAME="'.$v_formfiel_name.'"';
			$v_ret_html = $v_ret_html . ' FRAMEBORDER=0';
			$v_ret_html = $v_ret_html . ' SCROLLING=YES';
			$v_ret_html = $v_ret_html . ' HEIGHT = '. $v_height;
			$v_ret_html = $v_ret_html . ' WIDTH = '. $v_width;
			$v_ret_html = $v_ret_html . ' ALIGN = BASELINE';
			$v_ret_html = $v_ret_html . ' MARGINHEIGHT = 0';
			$v_ret_html = $v_ret_html . ' MARGINWIDTH = 0';
			$v_ret_html = $v_ret_html . ' SRC="'.$v_url.'"> ';
			$v_ret_html = $v_ret_html . '</IFRAME>';
			$v_ret_html = $v_ret_html . '</td></tr>';
			break;
		case "editor";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html . ' <script language="javascript">';
			$v_ret_html = $v_ret_html . ' _editor_url = "'.$_ISA_LIB_URL_PATH.'js-editor/";';
			$v_ret_html = $v_ret_html . ' var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);';
			$v_ret_html = $v_ret_html . ' if (win_ie_ver >= 5.5) { ';
			$v_ret_html = $v_ret_html . "document.write('<scr' + 'ipt src=\"' +_editor_url+ 'editor.js\" language=\"JavaScript\"></scr' + 'ipt>')";
			$v_ret_html = $v_ret_html . "}else { document.write('<scr' + 'ipt>function editor_generate() { return false; }</scr' + 'ipt>'); }";
			$v_ret_html = $v_ret_html . ' </script>';
			$v_ret_html = $v_ret_html . '<textarea class="normal_textarea" name="'.$v_formfiel_name.'" rows="'.$v_row.'" title="'.$v_tooltip.'" style="width:'.$v_width.'"'._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list).'" xml_tag_in_db="'.$v_xml_tag_in_db.'" xml_data="'.$v_xml_data.'" column_name="'.$v_column_name.'" message="'.$v_message.'">'.$v_value.'</textarea>';
			$v_ret_html = $v_ret_html . ' <script language="javascript">editor_generate("'.$v_formfiel_name.'");</script>';
			break;
		case "channel";
			if ($v_input_data != "session"){
				$arr_all_channel = _adodb_query_data_in_number_mode($v_channel_sql);
			}else{
				$arr_all_channel = $_SESSION[$v_session_name];
			}
			$v_ret_html =  _genarate_tree_channel($arr_all_channel, $v_value, $v_label,$v_disabled_in_edit_mode);
			break;
		case "labelcontent";
			$v_ret_html = $v_str_label;
			if($v_php_function != ""){
				$v_ret_html = $v_ret_html . call_user_func($v_php_function,$v_value);//$v_value.$v_php_function;
			}
			else{
				if($v_content != "" && !is_null($v_content)){
					$v_ret_html = $v_ret_html . $v_content;
				}
				else{
					$v_ret_html = $v_ret_html . $v_value;
				}
			}
			break;
		case 'image_button':
			$v_ret_html = '<span class="normal_link" title="'.$v_title.'" align="left" id = "'.$v_xml_tag_in_db.'"';
			$v_ret_html .= _generate_event_and_function($v_js_function_list, $v_js_action_list);
			$v_ret_html .= '>';
			$v_ret_html .= '<img style="cursor:hand" src="'.$v_path.'" border="0"';
			$v_ret_html .= $v_other_attribute;
			$v_ret_html .= ' />&nbsp;'.$v_str_label;
			$v_ret_html .= '</span>';
			break;
		default:
			$v_ret_html = $v_str_label;
	}
	return $v_ret_html;
}

//Sinh ra XAU chua thuoc tinh cua doi tuong
function _generate_property_type($v_type, $v_value){
	switch($v_type) {
		case "optional";
			if ($v_value=="false"){
				$v_ret_html = "";
			}else{
				$v_ret_html = " optional = true ";
			}
			break;
		case "readonly";
			if ($v_value=="false"){
				$v_ret_html = "";
			}else{
				$v_ret_html = " readonly = true ";
			}
			break;
		case "disabled";
			if ($v_value=="false"){
				$v_ret_html = " ";
			}else{
				$v_ret_html = " disabled = true ";
			}
			break;
		default:
			$v_ret_html = "";
	}
	return $v_ret_html;

}

//Tao chuoi HTML chua ham va cac su kien tuong ung voi ham cua cac doi tuong
function _generate_event_and_function($v_js_function_list, $v_js_action_list){
	$arr_js_function_list = explode(",", $v_js_function_list);
	$arr_js_action_list =   explode(",", $v_js_action_list);
	$v_count_function =     sizeof($arr_js_function_list);
	$v_count_action =       sizeof($arr_js_action_list);
	$v_count = $v_count_function > $v_count_action ? $v_count_action : $v_count_function;
	$v_temp = "";
	for ($i=0;$i<$v_count;$i++){
		$v_temp = $v_temp . " $arr_js_action_list[$i]='$arr_js_function_list[$i]' ";
	}
	return $v_temp;
}

//Tao chuoi HTML chua thuoc tinh rang buoc du lieu cua cac doi tuong tren from
function _generate_verify_property($v_dataformat){
	switch($v_dataformat) {
		case "isemail";
			$v_ret_html = " isemail=true " ;
			break;
		case "isdate";
			$v_ret_html = " isdate=true " ;
			break;
		case "isnumeric";
			$v_ret_html = " isnumeric=true " ;
			break;
		case "isdouble";
			$v_ret_html = " isdouble=true " ;
			break;
		case "ismoney";
			$v_ret_html = " isnumeric=true onKeyUp='format_money(this)' ";
			break;
		case "ismoney_float";
			$v_ret_html = " isfloat=true onKeyUp='format_money(this)' ";
			break;
		default:
			$v_ret_html = "";
	}
	return $v_ret_html;
}
// Tao chuoi HTML cho cac cot cua danh sach
function _generate_html_for_column($p_type){
	global $_ISA_WEB_SITE_PATH,$_ISA_LIST_WEB_SITE_PATH;
	global $v_value,$v_value_id,$v_url,$v_align,$v_inc,$v_selectbox_option_sql,$v_php_function,$row_index,$v_count,$v_id_column,$v_onclick_up,$v_onclick_down;
	global $v_have_move;
	global $v_readonly_in_edit_mode,$v_disabled_in_edit_mode,$v_selectbox_option_sql,$v_selectbox_id_column,$v_selectbox_name_column;
	global $v_table, $v_pk_column,$v_filename_column,$v_content_column,$v_append_column;
	global $p_arr_item;
	global $v_public_list_code,$v_input_data,$v_maxlength,$v_js_function_list, $v_js_action_list,$v_dataformat;
	switch($p_type) {
		case "checkbox";
			$v_ret_html = '<td align="'.$v_align.'"><input type="checkbox" name="chk_item_id" '._generate_event_and_function($v_js_function_list, $v_js_action_list).' value="'.$v_value.'">&nbsp;<a name="'.$v_value.'">&nbsp;</a>';
			if ($v_id_column =="true" && $v_have_move){
				if ($row_index !=0){
					$v_ret_html = $v_ret_html. '<img src="'.$_ISA_WEB_SITE_PATH.'images/up.gif" border="0" style="cursor:hand;" onClick="'.$v_onclick_up.'">';
				}else{
					$v_ret_html = $v_ret_html. '&nbsp;&nbsp;&nbsp;';
				}
				if ($row_index != $v_count-1){
					$v_ret_html = $v_ret_html. '<img src="'.$_ISA_WEB_SITE_PATH.'images/down.gif" border="0" style="cursor:hand;" onClick="'.$v_onclick_down.'">';
				}else{
					$v_ret_html = $v_ret_html. '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}
			$v_ret_html  = $v_ret_html .'</td>';
			break;
		case "hidden";
			$v_ret_html = '';
			break;
		case "selectbox";
			if ($v_input_data == "isalist"){
				$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
				$arr_list_item = _convert_xml_string_to_array($v_xml_data_in_url,"item");
			}else{
				$arr_list_item = _adodb_query_data_in_number_mode($v_selectbox_option_sql);
			}
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html . "<td align='.$v_align.'><select class='normal_selectbox' name='sel_item' title='$v_tooltip' style='width:100%' "._generate_property_type("optional",$v_optional)._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode)._generate_event_and_function($v_js_function_list, $v_js_action_list)." xml_tag_in_db='$v_xml_tag_in_db' xml_data='$v_xml_data' column_name='$v_column_name' message='$v_message' onKeyDown='change_focus(document.forms(0),this)'>";
			$v_ret_html = $v_ret_html . "<option id='' value=''>--- Ch&#7885;n $v_label ---</option>"._generate_select_option($arr_list_item,$v_selectbox_id_column,$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
			$v_ret_html = $v_ret_html . "</select></td>";
			//$v_ret_html = '<td align="'.$v_align.'" onclick="'.$v_url.'">'._get_value_from_array(_adodb_query_data_in_number_mode($v_selectbox_option_sql),"0","1",$v_value).'&nbsp;</td>';
			break;
		case "textbox";
			if($v_php_function !="" && !is_null($v_php_function)){
				$v_value = call_user_func($v_php_function,$v_value);
			}
			$v_ret_html = '<td align="'.$v_align.'"><input type="textbox" name="txt_item_id" value="'.$v_value.'" style="width:100%" '._generate_property_type("readonly",$v_readonly_in_edit_mode)._generate_property_type("disabled",$v_disabled_in_edit_mode).' maxlength="'.$v_maxlength.'"'._generate_event_and_function($v_js_function_list, $v_js_action_list).'>';
			$v_ret_html  = $v_ret_html .'</td>';
			break;
		case "function";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'.call_user_func($v_php_function,$v_value).'&nbsp;</td>';
			break;
		case "date";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'._yyyymmdd_to_ddmmyyyy($v_value).'&nbsp;</td>';
			break;
		case "time";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'._yyyymmdd_to_hhmmss($v_value).'&nbsp;</td>';
			break;
		case "text";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'.$v_value.'&nbsp;</td>';
			break;
		case "identity";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'.$v_inc.'&nbsp;</td>';
			break;
		case "money";
			$v_ret_html = '<td class="data" align="'.$v_align.'" onclick="'.$v_url.'">'._data_format($v_value).'&nbsp;</td>';
			break;
		case "attachment";
			$v_ret_html = '<td class="data" align="'.$v_align.'" >';
			$v_ret_html .= $p_arr_item[$v_append_column]." ";

			$arr_file_attach = explode("|??|",$v_value);
			$arr_file_id = explode("|",$arr_file_attach[0]);
			$arr_file_name = explode("|",$arr_file_attach[1]);

			for ($j=0;$j<sizeof($arr_file_id);$j++){
				if ($j>0)
					$v_ret_html .="; ";
				$v_file_url = _CONST_VIEW_ATTACH_FILE_PATH_FROM_CURRENT.$arr_file_name[$j];
				$v_goto_url = "javascript:filename_onclick(&quot;$v_table&quot;,&quot;$v_pk_column&quot;,&quot;$v_filename_column&quot;,&quot;$v_content_column&quot;," . strval($arr_file_id[$j]) . ",&quot;".$v_file_url."&quot;);" ;
				$v_ret_html.='<a href="'.$v_goto_url.'">'.$arr_file_name[$j].'</a>';
			}
			$v_ret_html.='&nbsp;</td>';
			break;
		default:
			$v_ret_html = $v_value;
	}
	return $v_ret_html;
}
// Dinh dang tien te, so theo dung dinh dang
function _data_format($p_value){
	$v_ret_value = strval($p_value);
	if ($v_ret_value=="" || is_null($v_ret_value)){
		return "";
	}
	$arr_value=explode(".",$v_ret_value);
	if (isset($arr_value[1]) && $arr_value[1]*1==0){
		$v_ret_value = $arr_value[0];
	}
	if (strpos($v_ret_value,".")===false){
		$v_ret_value = number_format($v_ret_value, 0, '.', ',');
	}else{
		$v_ret_value = number_format($v_ret_value, 2, '.', ',');
	}
	if ($v_ret_value == "0.00") $v_ret_value = "0";
	return $v_ret_value;
}
//Chuyen chu cai dau tien cua xau thanh chu thuong
function _first_stringtolower($p_str){
	$v_temp = substr($p_str,1,strlen($p_str));
	$v_temp = strtolower(substr($p_str,0,1)).$v_temp ;
	return $v_temp;
}

//Lay gia tri cua phan tu co ID:$SelectedValue tu danh sach
//$arr_list : Mang chua danh sach
//$IdColumn : Ten cot chua ID can so sanh
//$NameColumn: Ten cot chua chua gi tri tra ve
//$SelectedValue: Gia tri so sanh voi ID cua danh sach
function _get_value_from_array($arr_list,$IdColumn,$NameColumn,$SelectedValue) {
	$v_value = "";
	$count=sizeof($arr_list);
	for($row_index = 0;$row_index< $count;$row_index++){
		$strID=trim($arr_list[$row_index][$IdColumn]);
		$DspColumn=trim($arr_list[$row_index][$NameColumn]);
		if($strID == $SelectedValue) {
			$v_value = $DspColumn;
		}
	}
	return $v_value;
}

//Ham so sanh gia tri cua xau  DUNG CHO SAP XEP DU LIEU
function _compare_two_value($a, $b){
	global $v_group_by,$v_xml_data_compare;
	if ($v_xml_data_compare == "true"){
		$v_xml_string_a = $a['C_RECEIVED_RECORD_XML_DATA'];
		$v_xml_string_b = $b['C_RECEIVED_RECORD_XML_DATA'];
		//Lay gia tri tu mang a
		$column_rax = new RAX();
		$column_rec = new RAX();
		$column_rax->open($v_xml_string_a);
		$column_rax->record_delim = 'data_list';
		$column_rax->parse();
		$column_rec = $column_rax->readRecord();
		$column_row = $column_rec->getRow();
		$v_value_a = _restore_XML_bad_char($column_row[$v_group_by]);
		//Lay gia tri tu mang b
		$column_rax = new RAX();
		$column_rec = new RAX();
		$column_rax->open($v_xml_string_b);
		$column_rax->record_delim = 'data_list';
		$column_rax->parse();
		$column_rec = $column_rax->readRecord();
		$column_row = $column_rec->getRow();
		$v_value_b = _restore_XML_bad_char($column_row[$v_group_by]);
		return strcmp($v_value_a, $v_value_b);
	}else{
		return strcmp($a[$v_group_by],$b[$v_group_by]);
	}
}
//Ham nay co chuc nang in ra phan than cua bao cao

function _XML_generate_report_body($p_xml_file,$p_xml_tag, $p_arr_all_item, $p_colume_name_of_xml_string){
	global $v_value,$v_value_id,$v_url,$v_align,$v_inc,$v_selectbox_option_sql,$v_php_function,$row_index,$v_count,$v_current_style_name,$v_id_column;
	global $v_group_by,$v_xml_data_compare;//Cac tham so de truyen vao ham _compare_two_value
	$v_xml_string_in_file = _read_file($p_xml_file);
	$v_count = sizeof($p_arr_all_item);

	//Bang chua cac phan than cua bao cao
	$v_column = 0;
	$v_html_temp_width = '';
	$v_html_temp_label = '';
	$v_current_style_name = "round_row";
	$v_HTML_string = '';
	//Cac tham so de nhom du lieu
	$v_group_by = _XML_get_xml_tag_value($v_xml_string_in_file,"report_sql","group_by");
	$v_group_name = _XML_get_xml_tag_value($v_xml_string_in_file,"report_sql","group_name");
	$v_xml_data_compare = _XML_get_xml_tag_value($v_xml_string_in_file,"report_sql","xml_data");
	$v_calculate_total = _XML_get_xml_tag_value($v_xml_string_in_file,"report_sql","calculate_total");
	$v_calculate_group = _XML_get_xml_tag_value($v_xml_string_in_file,"report_sql","calculate_group");

	//Lay ten file HTML dinh dang tieu de cot bao cao
	$v_report_label_file = trim(_XML_get_xml_tag_value($v_xml_string_in_file,"report_header","table_header_file"));
	if ($v_report_label_file != ""){
		//Tieu de cot doc tu file HTML vao
		$v_report_label_file = CONST_REPORT_LABEL_FILE_URL_PATH.$v_report_label_file;
		$v_html_label_content = _read_file($v_report_label_file);
		$v_HTML_string = $v_HTML_string.$v_html_label_content;
	}

	$table_struct_rax = new RAX();
	$table_struct_rec = new RAX();
	$table_struct_rax->open($v_xml_string_in_file);
	$table_struct_rax->record_delim = $p_xml_tag;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord();
	while ($table_struct_rec) {
		$table_struct_row = $table_struct_rec->getRow();
		$v_type =  $table_struct_row["type"];
		$v_label = $table_struct_row["label"];
		$v_width = $table_struct_row["width"];
		$v_align = $table_struct_row["align"];
		//Lay danh sach do rong cac cot cua bang
		$v_html_temp_width = $v_html_temp_width  . '<col width="'.$v_width .'">';
		//Lay danh sach cac tieu de cua cot
		$v_html_temp_label = $v_html_temp_label . '<td class="header" align="'.$v_align.'">'.$v_label.'</td>';
		$arr_type[$v_column] = $v_type;
		$arr_align[$v_column] = $v_align;
		$table_struct_rec = $table_struct_rax->readRecord();
		$v_column ++;
	}
	$v_width_col = 100/$v_column;
	$v_html_col_list = $v_html_col_list .str_repeat("<col width:'$v_width_col%'>",$v_column);

	if($v_report_label_file == ""){
		$v_HTML_string = $v_HTML_string  . '<table class="report_table" style="width:100%" border="0" cellpadding="0" cellspacing="0">';
		$v_HTML_string = $v_HTML_string  . $v_html_temp_width;
		//Lay tieu de cot tu file XML
		$v_HTML_string = $v_HTML_string  . '<tr>';
		$v_HTML_string = $v_HTML_string  . $v_html_temp_label;
		$v_HTML_string = $v_HTML_string  . '</tr>';
	}
	//Khoi tao thu tu cua danh sach va nhom

	$group_index=1;
	$v_inc = 1;
	if ($v_count >0){
		//Vong lap hien thi danh sach cac ho so
		$v_old_row = $p_arr_all_item[0];
		for ($i=0; $i< $v_column; $i++){
			$arr_calculate[$i] = 0;
		}
		for($row_index = 0;$row_index <$v_count ;$row_index++){
			$v_recordset = $p_arr_all_item[$row_index];
			$v_received_record_xml_data = $p_arr_all_item[$row_index][$p_colume_name_of_xml_string];
			$v_recordtype_code = $p_arr_all_item[$row_index]['FK_RECORDTYPE'];
			$v_group_name_label = $p_arr_all_item[$row_index][$v_group_name];
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			//Bat dau 1 dong
			$table_struct_rax = new RAX();
			$table_struct_rec = new RAX();
			$table_struct_rax->open($v_xml_string_in_file);
			$table_struct_rax->record_delim = $p_xml_tag;
			$table_struct_rax->parse();
			$table_struct_rec = $table_struct_rax->readRecord();
			$v_col_index = 0;
			$v_HTML_string_row = '';
			//In tieu de cua nhom
			if (trim($v_group_by)!="" && $row_index == 0){
				$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
				$v_HTML_string = $v_HTML_string  .'<td class="data"><B>'.$group_index.'</B></td>';
				$v_HTML_string = $v_HTML_string  .'<td class="data" colspan="'.($v_column-1).'"><B>'.$p_arr_all_item[$row_index][$v_group_name].'</B></td>';
				$v_HTML_string = $v_HTML_string  .'</tr>';
			}
			while ($table_struct_rec) {
				$table_struct_row = $table_struct_rec->getRow();
				$v_type = $table_struct_row["type"];
				$v_width = $table_struct_row["width"];
				$v_align = $table_struct_row["align"];
				$v_xml_data = $table_struct_row["xml_data"];
				$v_calculate = $table_struct_row["calculate"];
				$v_compare_value = $table_struct_row["compare_value"];
				$v_column_name = $table_struct_row["column_name"];
				$v_xml_tag_in_db_list = $table_struct_row["xml_tag_in_db_list"];
				//Lay the xml chua noi dung can hien thi tu danh sach tuong ung voi ma
				if (_list_get_len($v_xml_tag_in_db_list,',')>1){
					$v_xml_tag_in_db = get_value_from_two_list($v_recordtype_code,$table_struct_row["recordtype_code_list"],$table_struct_row["xml_tag_in_db_list"]);
				}else{
					$v_xml_tag_in_db = $table_struct_row["xml_tag_in_db_list"];
				}
				$v_selectbox_option_sql = $table_struct_row["selectbox_option_sql"];
				$v_php_function = $table_struct_row["php_function"];
				$arr_xml_tag_in_db = explode(".",$v_xml_tag_in_db);
				if (sizeof($arr_xml_tag_in_db)>1){
					$v_received_record_xml_data = $p_arr_all_item[$row_index][$arr_xml_tag_in_db[0]];
					$v_xml_tag_in_db = $arr_xml_tag_in_db[1];
				}else{
					$v_xml_tag_in_db = $table_struct_row["xml_tag_in_db_list"];
				}
				if ($v_xml_data=="true" && $v_received_record_xml_data!="" && !is_null($v_received_record_xml_data)){
					$column_rax = new RAX();
					$column_rec = new RAX();
					$column_rax->open($v_received_record_xml_data);
					$column_rax->record_delim = 'data_list';
					$column_rax->parse();
					$column_rec = $column_rax->readRecord();
					$column_row = $column_rec->getRow();
					$v_value = _restore_XML_bad_char($column_row[$v_xml_tag_in_db]);
				}else{
					$v_value = $p_arr_all_item[$row_index][$v_column_name];
				}
				if ($v_type=="money"){
					$v_value = str_replace(",","",$v_value);
				}
				//In tu dong cua bao cao
				$v_HTML_string_row = $v_HTML_string_row . _generate_html_for_column($v_type);
				//Neu ma tinh so luong
				if ($v_calculate=="count"){
					if ((trim($v_compare_value)!="")&&(_list_have_element(trim($v_compare_value), trim($v_value),","))){
						$arr_calculate[$v_col_index] = $arr_calculate[$v_col_index] + 1;
						$arr_total_calculate[$v_col_index] = $arr_total_calculate[$v_col_index] + 1;
					}
				}elseif ($v_calculate=="sum"){//Neu tinh tong cac gia tri
					$arr_calculate[$v_col_index] = $arr_calculate[$v_col_index] + floatval($v_value);
					$arr_total_calculate[$v_col_index] = $arr_total_calculate[$v_col_index] + floatval($v_value);
				}else{
					$arr_calculate[$v_col_index] = "";
					$arr_total_calculate[$v_col_index] = "";
				}
				$v_col_index ++;
				$table_struct_rec = $table_struct_rax->readRecord();
			}//End while
			$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
			$v_HTML_string = $v_HTML_string  .$v_HTML_string_row;
			$v_HTML_string = $v_HTML_string  .'</tr>';
			$v_inc ++;
			if (trim($v_group_by)!=""){
				$v_current_row = $p_arr_all_item[$row_index+1];
				if ((_compare_two_value($v_old_row,$v_current_row)!=0)){
					//Khoi tao lai thu tu cua danh sach
					$v_inc = 1;
					$group_index++;
					$v_html_temp = "";
					//Hien thi phan tinh toan theo nhom
					for ($i=0;$i < sizeof($arr_calculate);$i++){
						if ($arr_calculate[$i]>=0 && $v_calculate_group=="true"){
							$v_type = $arr_type[$i];
							$v_align = $arr_align[$i];
							$v_value = $arr_calculate[$i];
							$arr_calculate[$i] = 0;
							if ($v_type=="money"){
								$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">'.onegate_data_format($v_value).'&nbsp;</td>';
							}elseif($v_type=="identity"){
								$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">&nbsp;</td>';
							}elseif($i==1){
								$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'"><B>C&#7897;ng:&nbsp;</B></td>';
							}else{
								$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">'.$v_value.'&nbsp;</td>';
							}
						}
					}
					$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
					$v_HTML_string = $v_HTML_string  .$v_html_temp;
					$v_HTML_string = $v_HTML_string  .'</tr>';
					//In tieu de cua nhom
					if (trim($v_group_by)!="" && $row_index<$v_count-1){
						$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
						$v_HTML_string = $v_HTML_string  .'<td class="data"><B>'.$group_index.'</B></td>';
						$v_HTML_string = $v_HTML_string  .'<td class="data" colspan="'.($v_column-1).'"><B>'.$p_arr_all_item[$row_index+1][$v_group_name].'</B></td>';
						$v_HTML_string = $v_HTML_string  .'</tr>';
					}

				}//End if
				$v_old_row = $v_current_row;
			}
			//Ket thuc mot dong
		}//End for
	}//End if
	//Hien thi phan tinh toan tong
	if ($v_calculate_total=="true"){
		$v_html_temp = "";
		for ($i=0;$i < sizeof($arr_total_calculate);$i++){
			//if ($arr_total_calculate[$i]>=0){
				$v_type = $arr_type[$i];
				$v_align = $arr_align[$i];
				$v_value = $arr_total_calculate[$i];
				if ($v_type=="money"){
					$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">'._data_format($v_value).'&nbsp;</td>';
				}elseif($v_type=="identity"){
					$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">&nbsp;</td>';
				}elseif($i==1){
					$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'"><B>T&#7893;ng c&#7897;ng&nbsp;</B></td>';
				}else{
					$v_html_temp = $v_html_temp .'<td class="data" align="'.$v_align.'">'.$v_value.'&nbsp;</td>';
				}
			//}
		}
		$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
		$v_HTML_string = $v_HTML_string  .$v_html_temp;
		$v_HTML_string = $v_HTML_string  .'</tr>';
	}
	if ($v_current_style_name == "odd_row"){
		$v_next_style_name = "round_row";
	}else{
		$v_next_style_name = "odd_row";
	}
	//Ket thuc ban bang cua bao cao
	$v_HTML_string = $v_HTML_string  .'</table>';
	return $v_HTML_string;
}

//Sinh ra chuoi HTML chua du lieu ve dieu kien loc cua bao cao
function _XML_generate_report_header($p_xml_file,$p_xml_tag_row,$p_xml_tag_col, $p_filter_xml_string){
	global $_ISA_OWNER_NAME;
	global $v_label, $v_type, $v_dataformat,$v_width,$v_row, $v_row_id;
	global $v_selectbox_option_sql,$v_selectbox_id_column, $v_selectbox_name_column;
	global $v_value,$i,$v_row;
	global $v_input_data,$v_session_name, $v_session_id_index,$v_session_name_index,$v_session_value_index;
	global $v_public_list_code;
	global $v_textbox_sql, $v_textbox_id_column,$v_textbox_name_column,	$v_textbox_fuseaction;
	global $v_hide_object;

	$v_xml_string_in_file = _read_file($p_xml_file);
	$v_column = 0;
	$table_struct_rax = new RAX();
	$table_struct_rec = new RAX();
	$table_struct_rax->open($v_xml_string_in_file);
	$table_struct_rax->record_delim = $p_xml_tag_col;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord();
	while ($table_struct_rec) {
		$table_struct_row = $table_struct_rec->getRow();
		$table_struct_rec = $table_struct_rax->readRecord();
		$v_column ++;
	}
	$v_width_col = 100/$v_column;
	$v_html_col_list = '';
	$v_html_col_list = $v_html_col_list . str_repeat("<col width:'$v_width_col%'>",$v_column);
	$v_HTML_string = '';
	$v_report_unit = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_unit");
	$v_report_unit_father = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_unit_father");
	$v_report_unit_child = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_unit_child");
	$v_report_right_label = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_right_label");
	$v_report_date = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_date");
	$v_large_title = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","large_title");
	$v_small_title = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","small_title");
	$v_report_unit = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_unit");

	$v_HTML_string = $v_HTML_string  .'<table width="99%" border="0" cellpadding="0" cellspacing="0">';
	$v_HTML_string = $v_HTML_string  . $v_html_col_list;
	$v_HTML_string = $v_HTML_string  .'<tr valign="top"><td align="center" class="report_unit_name" colspan="'.floor($v_column/2).'">'.$v_report_unit_father."<br>".$v_report_unit_child.'</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="right" class="freedom_republic" colspan="'.($v_column-floor($v_column/2)).'">'.$v_report_right_label.'</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	//$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="right" class="date" colspan="'.$v_column.'"><i>'.$v_report_date.$v_current_date.'</i></td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="title" colspan="'.$v_column.'">'.$v_large_title.'</td>';
	if ($v_small_title!=""){
		$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="sub_title" colspan="'.$v_column.'">'.$v_small_title.'</td>';
	}
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	//$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr></table>';
	//Het phan tieu de cua bao cao
	//Phan chua cac tieu thuc loc bao cao
	$table_struct_rax = new RAX();
	$table_struct_rec = new RAX();
	$table_struct_rax->open($v_xml_string_in_file);
	$table_struct_rax->record_delim = $p_xml_tag_row;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord();
	while ($table_struct_rec) {
		$table_struct_row = $table_struct_rec->getRow();
		$v_tag_list = $table_struct_row["tag_list"];
		$v_row_id = $table_struct_row["row_id"];
		$arr_tag = explode(",", $v_tag_list);
		//Bang chua mot dong cua form
		$v_HTML_string = $v_HTML_string . "<table width='99%'  border='0' cellspacing='0' cellpadding='0'>";
		$v_html_table = "";
		$v_html_tag = "";
		for($i=0;$i < sizeof($arr_tag);$i++){
			$formfield_rax = new RAX();
			$formfield_rec = new RAX();
			$formfield_rax->open($v_xml_string_in_file);
			$formfield_rax->record_delim = $arr_tag[$i];
			$formfield_rax->parse();
			$formfield_rec = $formfield_rax->readRecord();
			$formfield_row = $formfield_rec->getRow();
			$v_label = $formfield_row["label"];
			$v_type = $formfield_row["type"];
			$v_dataformat = $formfield_row["data_format"];
			$v_width = $formfield_row["width"];
			$v_row = $formfield_row["row"];
			$v_max = $formfield_row["max"];
			$v_min = $formfield_row["min"];
			$v_maxlength = $formfield_row["maxlength"];
			$v_note = $formfield_row["note"];
			$v_message = $formfield_row["message"];
			$v_optional = $formfield_row["optional"];
			$v_xml_tag_in_db = $formfield_row["xml_tag_in_db"];
			$v_js_function_list = $formfield_row["js_function_list"];
			$v_js_action_list = $formfield_row["js_action_list"];
			$v_readonly_in_edit_mode = $formfield_row["readonly_in_edit_mode"];
			$v_disabled_in_edit_mode = $formfield_row["disabled_in_edit_mode"];
			//lay du lieu tu session
			$v_input_data = $formfield_row["input_data"];
			$v_session_name = $formfield_row["session_name"];
			$v_session_id_index = $formfield_row["session_id_index"];
			$v_session_name_index = $formfield_row["session_name_index"];
			$v_session_value_index = $formfield_row["session_value_index"];
			$v_public_list_code = $formfield_row["public_list_code"];
			
			//Cac thuoc tinh cua textbox lay du lieu tu dialog
			$v_textbox_sql = $formfield_row["textbox_sql"];
			$v_textbox_id_column = $formfield_row["textbox_id_column"];
			$v_textbox_name_column = $formfield_row["textbox_name_column"];
			$v_textbox_fuseaction = $formfield_row["textbox_fuseaction"];
			
			$v_hide_object = $formfield_row["hide_object"];
			
			if ($p_filter_xml_string!=""){
				$column_rax = new RAX();
				$column_rec = new RAX();
				$column_rax->open($p_filter_xml_string);
				$column_rax->record_delim = 'data_list';
				$column_rax->parse();
				$column_rec = $column_rax->readRecord();
				$column_row = $column_rec->getRow();
				$v_value = _restore_XML_bad_char($column_row[$v_xml_tag_in_db]);
			}
			if ($v_type=="selectbox"){
				$v_selectbox_option_sql = $formfield_row["selectbox_option_sql"];
				$v_selectbox_id_column = $formfield_row["selectbox_option_id_column"];
				$v_selectbox_name_column = $formfield_row["selectbox_option_name_column"];
			}
			if ($v_type=="checkboxmultiple"){
				$v_checkbox_multiple_sql = $formfield_row["checkbox_multiple_sql"];
				$v_checkbox_multiple_id_column = $formfield_row["checkbox_multiple_id_column"];
				$v_checkbox_multiple_name_column = $formfield_row["checkbox_multiple_name_column"];
			}
			$v_html_table = $v_html_table . "<col width='$v_first_col_width'>" . "<col width='$v_second_col_width'>";
			if ($v_hide_object != "true"){
				$v_html_tag = $v_html_tag . _generate_html_output();
			}
		}
		$v_HTML_string = $v_HTML_string .  $v_html_table . "<tr><td class='normal_label' align='center' colspan='$v_column'>" . $v_html_tag."</td></tr>";
		$v_HTML_string = $v_HTML_string . "</table>";
		$table_struct_rec = $table_struct_rax->readRecord();
	}
	$v_HTML_string = $v_HTML_string . "<table width='99%'  border='0' cellspacing='0' cellpadding='0'>";
	$v_HTML_string = $v_HTML_string . "<tr><td colspan='$v_column'>&nbsp;</td></tr>";
	$v_HTML_string = $v_HTML_string . "</table>";
	return $v_HTML_string;
}

function _XML_generate_report_footer($p_xml_file,$p_xml_tag){
	$v_xml_string_in_file = _read_file($p_xml_file);
	$v_column = 0;
	$v_current_date = "ng&#224;y ". date("d"). " th&#225;ng " . date("m")." n&#259;m " . date("Y");
	$table_struct_rax = new RAX();
	$table_struct_rec = new RAX();
	$table_struct_rax->open($v_xml_string_in_file);
	$table_struct_rax->record_delim = $p_xml_tag;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord();
	while ($table_struct_rec) {
		$table_struct_row = $table_struct_rec->getRow();
		$table_struct_rec = $table_struct_rax->readRecord();
		$v_column ++;
	}
	$v_width_col = 100/$v_column;
	$v_html_col_list = '';
	$v_html_col_list = $v_html_col_list .str_repeat("<col width:'$v_width_col%'>",$v_column);

	$v_HTML_string = '';
	$v_report_date = _XML_get_xml_tag_value($v_xml_string_in_file,"report_header","report_date");
	$v_report_creator = _XML_get_xml_tag_value($v_xml_string_in_file,"report_footer","report_creator");
	$v_report_approver = _XML_get_xml_tag_value($v_xml_string_in_file,"report_footer","report_approver");
	$v_report_signer = _XML_get_xml_tag_value($v_xml_string_in_file,"report_footer","report_signer");
	
	$v_HTML_string = $v_HTML_string  .'<table width="99%" border="0" cellspacing="0" cellpadding="0">';
	$v_HTML_string = $v_HTML_string  . $v_html_col_list;
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="right" class="date" colspan="'.$v_column.'"><i>'.$v_report_date.$v_current_date.'</i></td>';
	$v_HTML_string = $v_HTML_string  .'<tr><td class="normal_label" colspan="'.$v_column.'">&nbsp;</td></tr><tr>';
	$v_HTML_string = $v_HTML_string  .'<tr><td class="normal_label" colspan="'.$v_column.'">&nbsp;</td></tr><tr>';
	$v_HTML_string = $v_HTML_string  .'<td class="normal_label">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="creator">'.$v_report_creator.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="approver">'.$v_report_approver.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="signer">'.$v_report_signer.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr></table>';
	$v_HTML_string = str_replace('&lt;','<',$v_HTML_string);
	$v_HTML_string = str_replace('&gt;','>',$v_HTML_string);
	//echo htmlspecialchars($v_HTML_string);exit;
	return $v_HTML_string;
}
function _generate_html_output(){
	global $_ISA_OWNER_CODE,$_ISA_LIST_WEB_SITE_PATH;
	global $v_label, $v_type, $v_dataformat,$v_width,$v_row, $v_row_id;
	global $v_selectbox_option_sql,$v_selectbox_id_column, $v_selectbox_name_column;
	global $v_value,$i,$v_row ;
	global $v_input_data,$v_session_name, $v_session_id_index,$v_session_name_index,$v_session_value_index;
	global $v_public_list_code;
	global $v_textbox_sql, $v_textbox_id_column,$v_textbox_name_column,	$v_textbox_fuseaction;
	global $v_hide_object;
	$v_selectbox_option_sql = str_replace('#OWNER_CODE#' ,$_ISA_OWNER_CODE,$v_selectbox_option_sql);
	$v_optional_label = "";
	$v_str_label = "&nbsp;&nbsp;".$v_label."&nbsp;&nbsp;";
	switch($v_type) {
		case "label";
			$v_ret_html = $v_label.$v_optional_label."&nbsp;&nbsp;";
			break;
		case "textbox";
			$v_ret_html = $v_str_label;
			if ($v_dataformat == "isdialog"){
				$v_ret_html = $v_ret_html ._get_value_from_array(_adodb_query_data_in_name_mode($v_textbox_sql),$v_textbox_id_column,$v_textbox_name_column,$v_value);
			}else{
				$v_ret_html = $v_ret_html . $v_value;
			}
			break;
		case "selectbox";
			$v_ret_html = $v_str_label;
			if ($v_input_data == "session"){
				$j = 0;
				$arr_list_item = array();
				if (isset($_SESSION[$v_session_name])){
					foreach($_SESSION[$v_session_name] as $arr_item) {
						$arr_list_item[$j] = $arr_item;
						$j++;
					}
				}
				$v_ret_html = $v_ret_html ._get_value_from_array($arr_list_item,$v_session_id_index,$v_session_name_index,$v_value);
			}elseif ($v_input_data == "isalist"){
				$v_xml_data_in_url = _read_file($_ISA_LIST_WEB_SITE_PATH."listxml/output/".$v_public_list_code.".xml");
				$v_ret_html = $v_ret_html ._get_value_from_array(_convert_xml_string_to_array($v_xml_data_in_url,"item"),$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
			}else{
				$v_ret_html = $v_ret_html ._get_value_from_array(_adodb_query_data_in_number_mode($v_selectbox_option_sql),$v_selectbox_id_column,$v_selectbox_name_column,$v_value);
			}
			break;
		default:
			$v_ret_html = "";
	}
	return $v_ret_html;
}
?>