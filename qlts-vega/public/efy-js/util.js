//************************************************************************
// CHU Y: Khong duoc thay doi vi tri cua bien nay (LUON LUON dat o dong dau tien)

if (typeof(_ALLOW_EDITING_IN_MODAL_DIALOG)=="undefined")
	_ALLOW_EDITING_IN_MODAL_DIALOG = 0; // Ngam dinh khong cho phep THEM/SUA/XOA cac thong tin danh muc khi chay o mot cua so MODAL DIALOG

if (typeof(_DECIMAL_DELIMITOR)=="undefined")
	_DECIMAL_DELIMITOR = ","; // Ngam dinh su dung day PHAY (,) de phan cach hang nghin


//************************************************************************
function add_textselectbox_value_to_tag_list_and_value_list(p_object_id_list,p_hdn_tag_obj,p_hdn_value_obj) {
	var list_value = "";
	var list_tag = "";
	arr_object = new Array();
	arr_object = p_object_id_list.split(',');
	for (var i=0; i < arr_object.length; i++) {
		list_tag = list_append(list_tag,arr_object[i],_SUB_LIST_DELIMITOR);
		list_value = list_append(list_value,eval(arr_object[i]+".getActualValue()"),_SUB_LIST_DELIMITOR);
	}
	p_hdn_tag_obj.value = list_append(p_hdn_tag_obj.value,list_tag,_SUB_LIST_DELIMITOR);
	p_hdn_value_obj.value = list_append(p_hdn_value_obj.value,list_value,_SUB_LIST_DELIMITOR);
	
}
// Ham create_dynamic_form: Tao form dong
function create_dynamic_form(number_obj, obj, html_tr_string) {
	var data = '';
	for (i=0; i < number_obj; i++) {
		var html_string_temp = html_tr_string;
		while (html_string_temp.indexOf('#obj_position#') > -1){
			html_string_temp = html_string_temp.replace('#obj_position#',i);
		}
		data += html_string_temp;
	}
	if (document.all) {
		obj.innerHTML = data;
		obj.number_row = number_obj;
	}
}
// Ham them mot dong tren danh sach
function del_row_onclick(obj,row_id,obj_list,html_tr_string){
	if(obj.innerHTML != ''){
		if(obj.number_row > 2){
			arr_obj = new Array();
			arr_obj = obj_list.split(',');
			for(i=0;i<arr_obj.length;i++){
				var str_obj = arr_obj[i];
				var length_obj = eval('document.all.'+ row_id + '.length');
				eval('var ' + str_obj + '_arr = new Array()');
				var k = 0;
				for(j=0;j<=length_obj;j++){
					chk_check_obj = arr_obj[0] + j;
					if(!eval('document.all.' + chk_check_obj + '.checked')){
						eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.value');
						k += 1;
					}
				}
			}
			create_dynamic_form(k,obj, html_tr_string);
			eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = '+ k);
			for(i=0;i<arr_obj.length;i++){
				var str_obj = arr_obj[i];
				for(j=0;j<eval(str_obj + '_arr.length');j++){
					eval('document.all.' + str_obj + j + '.value = ' + str_obj + '_arr[' + j + ']');
				}
			}
		}else{
			if(obj.number_row == 2){
				arr_obj = new Array();
				arr_obj = obj_list.split(',');
				for(i=0;i<arr_obj.length;i++){
					var str_obj = arr_obj[i];
					eval('var ' + str_obj + '_arr = new Array()');
					var k = 0;
					for(j=0;j<2;j++){
						chk_check_obj = arr_obj[0] + j;
						if(!eval('document.all.' + chk_check_obj + '.checked')){
							eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.value');
							k += 1;
						}
					}
				}
				create_dynamic_form(k,obj, html_tr_string);
				eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = ' + k);
				for(i=0;i<arr_obj.length;i++){
					var str_obj = arr_obj[i];
					for(j=0;j<k;j++){
						eval('document.all.' + str_obj + j + '.value = ' + str_obj + '_arr[' + j + ']');
					}
				}
			}else{
				arr_obj = new Array();
				arr_obj = obj_list.split(',');
				var str_obj = arr_obj[i];
				chk_check_obj = arr_obj[0] + 0;
				if(eval('document.all.' + chk_check_obj + '.checked')){
					create_dynamic_form(0,obj, html_tr_string);
					eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = 0');
				}
			}
		}
	}
}
// Ham xoa mot dong khoi danh sach
function add_row_onclick(obj,row_id,obj_list,html_tr_string){
	if(obj.innerHTML == ''){
		create_dynamic_form(1,obj, html_tr_string);
		eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = 1');
		arr_obj = new Array();
		arr_obj = obj_list.split(',');
		for(i=0;i<arr_obj.length;i++){
			var str_obj = arr_obj[i];
			eval('var ' + str_obj + '_arr = new Array()');
			eval('document.all.' + str_obj + 0 + '.value = ""');
		}
	}else{
		try{
			if(obj.number_row > 2){
				length_obj = eval('document.all.'+ row_id + '.length');
				arr_obj = new Array();
				arr_obj = obj_list.split(',');
				for(i=0;i<arr_obj.length;i++){
					var str_obj = arr_obj[i];
					eval('var ' + str_obj + '_arr = new Array()');
					var k = 0;
					for(j=0;j<=length_obj;j++){
						if (eval('document.all.' + str_obj + j + '.type')=="checkbox")
							eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.checked');
						else
							eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.value');
						k += 1;
					}
				}
				create_dynamic_form(k+1,obj, html_tr_string);
				eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = '+ (k+1));
				for(i=0;i<arr_obj.length;i++){
					var str_obj = arr_obj[i];
					for(j=0;j<=eval(str_obj + '_arr.length');j++){
						if(j == eval(str_obj + '_arr.length')){
							if (eval('document.all.' + str_obj + j + '.type')=="checkbox")
								eval('document.all.' + str_obj + j + '.checked = false');
							else
								eval('document.all.' + str_obj + j + '.value = ""');
						}
						else{
							if (eval('document.all.' + str_obj + j + '.type')=="checkbox")
								eval('document.all.' + str_obj + j + '.checked = ' + str_obj + '_arr[' + j + ']');
							else
								eval('document.all.' + str_obj + j + '.value = ' + str_obj + '_arr[' + j + ']');
						}
					}
				}
			}else{
				if(obj.number_row == 2){
					arr_obj = new Array();
					arr_obj = obj_list.split(',');
					for(i=0;i<arr_obj.length;i++){
						var str_obj = arr_obj[i];
						eval('var ' + str_obj + '_arr = new Array()');
						var k = 0;
						for(j=0;j<2;j++){
							if (eval('document.all.' + str_obj + j + '.type')=="checkbox")
								eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.checked');
							else
								eval(str_obj + '_arr[' + k + '] = document.all.' + str_obj + j + '.value');
							k += 1;
						}
					}
					create_dynamic_form(3,obj, html_tr_string);
					eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = 3');
					for(i=0;i<arr_obj.length;i++){
						var str_obj = arr_obj[i];
						eval('document.all.' + str_obj + 2 + '.value = ""');
						for(j=0;j<2;j++){
							if (eval('document.all.' + str_obj + j + '.type')=="checkbox")
								eval('document.all.' + str_obj + j + '.checked = ' + str_obj + '_arr[' + j + ']');
							else
								eval('document.all.' + str_obj + j + '.value = ' + str_obj + '_arr[' + j + ']');
						}
					}
				}else{
					arr_obj = new Array();
					arr_obj = obj_list.split(',');
					for(i=0;i<arr_obj.length;i++){
						var str_obj = arr_obj[i];
						eval('var ' + str_obj + '_arr = new Array()');
						if (eval('document.all.' + str_obj + 0 + '.type')=="checkbox")
							eval(str_obj + '_arr[0] = document.all.' + str_obj + 0 + '.checked');
						else
							eval(str_obj + '_arr[0] = document.all.' + str_obj + 0 + '.value');
					}
					create_dynamic_form(2,obj, html_tr_string);
					eval('document.forms(0).repeat_row_in_db_'+ row_id + '.value = 2');
					for(i=0;i<arr_obj.length;i++){
						var str_obj = arr_obj[i];
						if (eval('document.all.' + str_obj + 0 + '.type')=="checkbox"){
							eval('document.all.' + str_obj + 0 + '.checked = ' + str_obj + '_arr[0]');
							eval('document.all.' + str_obj + 1 + '.checked = false');
						}else{
							eval('document.all.' + str_obj + 0 + '.value = ' + str_obj + '_arr[0]');
							eval('document.all.' + str_obj + 1 + '.value = ""');
						}
					}
				}
			}
			length_obj = eval('document.all.'+ row_id + '.length');
			arr_obj = new Array();
			arr_obj = obj_list.split(',');
			eval('document.all.' + arr_obj[0]+length_obj+'.focus();');
		}catch(e){;}
	}
}
//************************************************************************
// Ham _btn_show_all_file: goi modal dialog de hien thi danh sach cac file trong mot thu muc
// Cach dung:   p_directory      -- thu muc can lay file
//				p_typefile_list  -- danh sach cac phan mo rong cua file
//				p_obj_text 		 -- Doi tuong ma ten file tra lai
function _btn_show_all_file(p_directory,p_typefile_list,p_obj_text){
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + escape("dsp_file_in_directory.php?hdn_directory=" + p_directory + "&hdn_typefile_list=" + p_typefile_list);
	v_url = v_url + "&modal_dialog_mode=1" + "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=400pt;dialogHeight=280pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	p_obj_text.value = sRtn;
}
//************************************************************************
// Ham _save_textbox_value_to_textbox_obj duyet tat ca cac doi tuong multiple-text va luu gia tri cua cac phan tu duoc chon
// vao 1 doi tuong textbox co ten xac dinh boi thuoc tinh "xml_tag_in_db_name"
// p_textbox_obj: ten doi tuong multuple-text
function _save_textbox_value_to_textbox_obj(p_textbox_obj,the_separator){
	var v_value;
	var list_value = the_separator;
	_save_checkbox_value_to_textbox_obj(document.getElementsByName('chk_multiple_textbox'),the_separator);
	try{
		if (!p_textbox_obj.length){
			list_value=p_textbox_obj.value;
			//eval('document.forms[0].'+p_textbox_obj.xml_tag_in_db_name+'.value=document.forms[0].'+p_textbox_obj.xml_tag_in_db_name+'.value+"'+list_value+'"');
			document.getElementById(p_textbox_obj.getAttribute("xml_tag_in_db_name")).setAttribute("value", list_value);
		}else{
			current_chk_obj = p_textbox_obj[0].getAttribute("xml_tag_in_db_name");
			for(i=0;i<p_textbox_obj.length;i++){
				next_chk_obj = p_textbox_obj[i].getAttribute("xml_tag_in_db_name");
				if (current_chk_obj != next_chk_obj){    //Neu het danh sach thi gan vao gia tri cua danh sach
					//eval('document.forms[0].'+current_chk_obj+'.value = document.forms[0].'+current_chk_obj+'.value+"'+list_value+'"');
					document.getElementById(current_chk_obj).setAttribute("value", list_value);
					list_value = the_separator;
				}
				v_value = replace(p_textbox_obj[i].value,the_separator,"");
				if (v_value=="" || v_value==null){
					v_value=" ";
				}
				list_value=list_append(list_value,v_value,the_separator);
				if (i==p_textbox_obj.length-1){          //Cuoi gan gia tri vao danh sach
					//eval('document.forms[0].'+current_chk_obj+'.value=document.forms[0].'+current_chk_obj+'.value+"'+list_value+'"');
					document.getElementById(current_chk_obj).setAttribute("value", list_value);
					list_value = the_separator;
				}
				current_chk_obj = next_chk_obj;
			}
		}
	}catch(e){;}
}
// Tao danh sach (list) chua cac the XML va danh sach gia tri tuong ung. Cac the nay duoc luu vao CSDL duoi danh chuoi XML
//Sau do luu vao 2 bien hidden.
// f: form object
// hdn_obj_tag: ten bien hidden chua danh sach cac the XML
// hdn_obj_value: ten bien hidden chua danh sach cac gia tri tuong ung voi moi the XML
// p_only_for_xml_data: neu la true thi chi luu cac the XML va gia tri cua cac form field co thuoc tinh xml_data='true'

function _save_xml_tag_and_value_list(p_form_obj,p_hdn_tag_obj,p_hdn_value_obj,p_only_for_xml_data){
	var list_tag = "";
	var list_value = "";
	var v_temp = "";
	var v_value = "";
	_save_checkbox_value_to_textbox_obj(document.getElementsByName('chk_multiple_checkbox'),',');	
	_save_checkbox_value_to_textbox_obj(document.getElementsByName('chk_unit_id'),',');
	_save_checkbox_value_to_textbox_obj(document.getElementsByName('chk_staff_id'),',');
	_save_textbox_value_to_textbox_obj(document.getElementsByName('txt_multiple_textbox'),_LIST_DELIMITOR);
	f = p_form_obj
	for (i=0;i<f.length;i++){
		var e=f.elements[i];
		if ((p_only_for_xml_data && e.getAttribute("xml_data")=='true' && e.getAttribute("store_in_child_table")!='true') || (!p_only_for_xml_data)){
			if (e.value == ""||e.value == null){
				v_value = " ";
			}else{
				v_value = e.value;
			}			
			if (e.getAttribute("xml_tag_in_db") && e.getAttribute("store_in_child_table")!='true' &&(e.type!='radio' && e.type!='checkbox')){
				list_tag = list_append(list_tag,e.getAttribute("xml_tag_in_db"),_SUB_LIST_DELIMITOR);
				list_value = list_append(list_value,v_value,_SUB_LIST_DELIMITOR);
			}
			if (e.getAttribute("xml_tag_in_db") && e.getAttribute("store_in_child_table")!='true' &&(e.type=='radio' || e.type=='checkbox')){
				list_tag = list_append(list_tag,e.getAttribute("xml_tag_in_db"),_SUB_LIST_DELIMITOR);
				if (e.checked==true){
					v_temp="true";
				}else{
					v_temp="false";
				}
				list_value = list_append(list_value,v_temp,_SUB_LIST_DELIMITOR);
			}
		}
	}
	p_hdn_tag_obj.value = list_tag;
	p_hdn_value_obj.value = list_value;
//alert(p_hdn_tag_obj.value + '\n' + p_hdn_value_obj.value);
}
// Ham _save_checkbox_value_to_textbox_obj duyet tat ca cac doi tuong multiple-checkbox va luu gia tri cua cac phan tu duoc chon
// vao 1 doi tuong textbox co ten xac dinh boi thuoc tinh "xml_tag_in_db_name"
// p_chk_obj: ten doi tuong multuple-checkbox

function _save_checkbox_value_to_textbox_obj(p_chk_obj,the_separator){
	var ret = "";
	try{
		if (!p_chk_obj.length){
			if (document.getElementById(p_chk_obj.getAttribute("xml_tag_in_db_name")).getAttribute("checked")){
				ret = document.getElementById(p_chk_obj.getAttribute("xml_tag_in_db_name")).getAttribute("value");
				document.getElementById(p_chk_obj.getAttribute("xml_tag_in_db_name")).setAttribute("value", ret);				
			}
		}else{
			current_chk_obj = p_chk_obj[0].getAttribute("xml_tag_in_db_name");
			for(i=0;i<p_chk_obj.length;i++){
				next_chk_obj = p_chk_obj[i].getAttribute("xml_tag_in_db_name");
				if (current_chk_obj != next_chk_obj){  //Neu het danh sach thi gan vao gia tri cua danh sach					
					document.getElementById(current_chk_obj).setAttribute("value", ret);
					ret = "";
				}
				if (p_chk_obj[i].checked){
					ret=list_append(ret,p_chk_obj[i].value,the_separator);
				}
				if (i==p_chk_obj.length-1){ //Cuoi gan gia tri vao danh sach			
					document.getElementById(current_chk_obj).setAttribute("value", ret);
					ret = "";
				}				
				current_chk_obj = next_chk_obj;	
			}
		}
	}catch(e){;}
}

//**********************************************************************************************************************
// CAC HAM LIEN QUAN DEN VIEC XU LY KHI NSD NHAN CHUOT VAO CAC O CHECKBOX TRONG 1 DANH SACH DE CHON HOAC BO CHON 1 DOI TUONG
//**********************************************************************************************************************

//**********************************************************************************************************************
// Ham show_row_selected de hien thi TAT CA cac dong DA DUOC CHON (checked)
// rad_id: la ten cua bien radio xac dinh che do HIEN THI TAT CA hay Chi HIEN THI NHUNG DOI TUONG DUOC CHON
// (vi du neu ta co <input name="rad_indicator_filter" thi rad_id="rad_indicator_filter")
// tr_name: ten cua id tuong uong voi dong chua doi tuong (vi d? neu ta co <tr id="xxxx"> thi tr_name="xxxx")
//**********************************************************************************************************************
function _show_row_selected(tablename){
	var v_count, objtable, i, v_modul_checked=false;
	objtable = document.getElementById(tablename);
	v_count = objtable.getElementsByTagName('tr').length;
	for(i = 0; i < v_count; i++){
		if(objtable.rows[i].getAttribute('checked') == "")
			objtable.rows[i].style.display = "none";
	}
}

//**********************************************************************************************************************
// Ham show_row_all de hien thi TAT CA cac dong (khong phan biet da DUOC CHON hay khong)
// rad_id: la ten cua bien radio xac dinh che do HIEN THI TAT CA hay Chi HIEN THI NHUNG DOI TUONG DUOC CHON
// (vi du neu ta co <input name="rad_indicator_filter" thi rad_id="rad_indicator_filter")
// tr_name: ten cua id tuong uong voi dong chua doi tuong (vi d? neu ta co <tr id="xxxx"> thi tr_name="xxxx")
//**********************************************************************************************************************
function _show_row_all(tablename){
	var v_count, objtable, i, v_modul_checked=false;
	objtable = document.getElementById(tablename);
	v_count = objtable.getElementsByTagName('tr').length;
	for(i = 0; i < v_count; i++)
		objtable.rows[i].style.display = "";
}

// Ham change_item_checked
// Chuc nang: Xu ly khi NSD click mouse vao checkbox cua mot doi tuong trong danh sach
//	-Tim tr_name chua checkbox duoc click va thay doi trang thai cua attribute checked ("" hoac "checked")
//	-Kiem tra cac trang thai checked cua cac function trong modul de xac dinh trang thai checked cua modul
//	(Neu khong co function nao duoc chon thi checked=""; neu co thi checked="checked")
//	-Kiem tra che do hien thi (qua radio button) de refresh lai man hinh
// Tham so:
//	-chk_obj: doi tuong checkbox duoc click
//	-tr_name: ten id cua row chua checkbox (tr_function hoac tr_enduser)
//	-rad_id:  ten id cua radio button xac dinh che do hien thi cua moi loai (rad_group_enduser hoac rad_group_function)

function _change_item_checked(chk_obj,tablename){
	var v_count, objtable, i, v_modul_checked=false;
	objtable = document.getElementById(tablename);
	v_count = objtable.getElementsByTagName('tr').length;
	for(i = 0; i < v_count; i++){
		if(objtable.rows[i].getAttribute('value') == chk_obj.value){
			if(chk_obj.checked)
				objtable.rows[i].setAttribute('checked','checked');
			else
				objtable.rows[i].setAttribute('checked','');
			break;
		}		
	}
}
//
// Ham select_unit_checkbox() cho phep danh dau cac staff cua mot phong ban
// Tham so:
// node: ten object cua doi doi tuong phong ban
function select_unit_checkbox(node){
	var checked = 0;
	var v_count = document.all.chk_staff_id.length;
	if(node.checked){
		for(i=0; i < v_count; i++){
			if(checked > 0 && document.all.chk_staff_id[i].parent_id != node.value){
				return;
			}
			if(document.all.chk_staff_id[i].parent_id == node.value){
				document.all.chk_staff_id[i].checked = "checked";
				checked ++;
			}
		}
	}else{
		for(i=0; i < v_count; i++){
			if(checked >0 && document.all.chk_staff_id[i].parent_id != node.value){
				return;
			}
			if(document.all.chk_staff_id[i].parent_id == node.value){
				document.all.chk_staff_id[i].checked = "";
				checked ++;
			}
		}
	}
}
// Ham select_unit_checkbox_treeuser() cho phep danh dau cac staff cua mot phong ban dung cho khai bao xml
// Editer: Hoang Van Toan
// date: 11/10/2009
// Hieu chinh de duyet tren moi trinh duyet
// Tham so:
// node: ten object cua doi doi tuong phong ban
function select_unit_checkbox_treeuser(node){
	var checked = 0;
	var v_staff = document.getElementsByName('chk_staff_id');
	if(node.checked){
		for(i=0; i < v_staff.length; i++){
			if(checked > 0 && v_staff[i].getAttribute('parent_id') != node.value && v_staff[i].getAttribute('xml_tag_in_db_name') == node.getAttribute('xml_tag_in_db_name')){
				return;
			}
			if(v_staff[i].getAttribute('parent_id') == node.value && v_staff[i].getAttribute('xml_tag_in_db_name') == node.getAttribute('xml_tag_in_db_name')){
				v_staff[i].checked = "checked";
				checked ++;
			}
		}
	}else{
		for(i=0; i < v_staff.length; i++){
			if(checked >0 && v_staff[i].getAttribute('parent_id') != node.value && v_staff[i].getAttribute('xml_tag_in_db_name') == node.getAttribute('xml_tag_in_db_name')){
				return;
			}
			if(v_staff[i].getAttribute('parent_id') == node.value && v_staff[i].getAttribute('xml_tag_in_db_name') == node.getAttribute('xml_tag_in_db_name')){
				v_staff[i].checked = "";
				checked ++;
			}
		}
	}
}
// Ham select_unit_checkbox_treeuser() cho phep danh dau cac staff cua mot phong ban dung cho khai bao xml
// Tham so:
// node: ten object cua doi doi tuong phong ban
function select_staff_checkbox_treeuser(node){
	var checked = 0;
	var v_count = document.getElementsByName('chk_staff_id').length;
	//alert(node.parent_id);
	//for(i=0; i < v_count; i++){
	//	if(document.all.chk_staff_id[i].value!=node.value && document.all.chk_staff_id[i].xml_tag_in_db_name==node.xml_tag_in_db_name){
	//		document.all.chk_staff_id[i].checked = false;
	//	}
	//}
}
////////////////////////////////////////////////////////////////////////
// Ham add_new_row() duoc goi khi NSD muon them mot dong
// Tham so:
// p_row_obj: ten object cua cac dong
// p_limit: so row toi da duoc them
////////////////////////////////////////////////////////////////////////////
function add_row(p_row_obj, p_limit){
	for (var i=0; i<p_limit; i++) {
		if (p_row_obj[i].style.display=="none"){
			p_row_obj[i].style.display="block";
			return;
		}
	}
}
////////////////////////////////////////////////////////////////////////////
// Ham delete_row() xu ly khi NSD xoa cac file dinh kem hien co
//Chuc nang:
//	- Khong hien thi cac file bi xoa
//	- Luu ID cua cac file bi xoa vao mot doi tuong kieu hidden
//Tham so:
//	- p_row_obj: ten doi tuong (duoc dinh nghia bang thuoc tinh "id" trong tag <tr>)
//	- p_checkbox_obj: ten doi tuong checkbox tuong ung
//	- p_hdn_obj ten doi tuong hidden luu gia tri cua cac file bi xoa
////////////////////////////////////////////////////////////////////////////
function delete_row(p_row_obj, p_checkbox_obj, p_hdn_obj){	
	if (checkbox_value_to_list(p_checkbox_obj,_LIST_DELIMITOR)!=""){
		if (p_hdn_obj.value!=""){
			p_hdn_obj.value = p_hdn_obj.value + _LIST_DELIMITOR + checkbox_value_to_list(p_checkbox_obj,_LIST_DELIMITOR);
		}else{
			p_hdn_obj.value = checkbox_value_to_list(p_checkbox_obj,_LIST_DELIMITOR);
		}
	}
	try{
		for(i=0; i<p_row_obj.length; i++){
			if(p_checkbox_obj[i].checked){
				p_row_obj[i].style.display = "none";
				p_checkbox_obj[i].checked = false;
			}
		}
	}
	catch(e){;}
}
function delete_row_exist(p_row_obj, p_checkbox_obj, fileUrl){
	if(confirm("Ban co that su muon xoa file da dinh kem trong he thong?")){
	try{
	var fileNameList = "";
	for(i=0; i<p_row_obj.length; i++){
	if(p_checkbox_obj[i].checked){
	fileNameList = fileNameList + p_checkbox_obj[i].value + '!#~$|*';
	p_row_obj[i].style.display = "none";
	p_checkbox_obj[i].checked = false;
	}
	}
	}
	catch(e){;}
	try{
	arrUrl = fileUrl.split('/');
	var key = 'fileNameList=' + fileNameList;
	//alert('/' + arrUrl[1] + '/public/ajax/deleteFileUpload.php');
	if(key != ""){
	postAJAXHTTPText('/' + arrUrl[1] + '/public/ajax/deleteFileUpload.php', key, null, null);
	}
	}catch(e){;}
	}
}

////////////////////////////////////////////////////////////////////////
// Ham reshow_row() DAU cac dong da bi xoa truoc do
// Tham so:
// p_row_obj: ten object cua cac dong
// p_checkbox_obj: ten doi tuong checkbox tuong ung
// p_hdn_obj: ten doi tuond hidden luu thu tu cua cac row da bi xoa truoc do
////////////////////////////////////////////////////////////////////////////
function reshow_row(p_row_obj, p_checkbox_obj, p_hdn_obj){
	if (p_hdn_obj.value=="" || null(p_hdn_obj.value))
		return;
	deleted_str = p_hdn_obj.value;
	deleted_array = deleted_str.split(",");
	for (i=0; i<deleted_array.length; i++){
		p_row_obj[i].style.display="none";
		p_checkbox_obj[i].checked=false;
	}
}

// Luu cac gia tri cua cac doi tuong hidden co thuoc tinh save=1
// Tham so:
// f: ten form
// p_save_hidden_input_obj: ten doi tuong hidden dung de luu gia tri cua cac hidden khac co thuoc tinh save=1
function save_hidden_input(f,p_save_hidden_input_obj)
{
	var errors = "";
	var strReturn="";
	var stt=1;
	for (var i=0;i<f.length;i++)
	{
		var e=f.elements[i];
		if (e.type =="hidden" &&  e.save=="1") {
			strReturn = strReturn + e.name + _SUB_LIST_DELIMITOR + e.value;
			strReturn = strReturn + _LIST_DELIMITOR;
		}
	}
	p_save_hidden_input_obj.value=strReturn;
}
/////////////////////////// Cac ham JS duoc su dung trong modul quan tri danh muc //////////////////////
// Ham nay duoc goi khi NSD nhan chuot vao ten file dinh kem
// Ham nay mo mot cua so moi va thuc thi dsp_file_content.php
function filename_onclick(p_table, p_file_id_column, p_file_name_column, p_file_content_column, p_file_id, p_file_url){
	url = _DSP_FILE_CONTENT_URL_PATH;
	url = url + "?file_id=" + p_file_id;
	url = url + "&table=" + p_table;
	url = url + "&file_id_column=" + p_file_id_column;
	url = url + "&file_name_column=" + p_file_name_column;
	url = url + "&file_content_column=" + p_file_content_column;
	url = url + "&file_url=" + p_file_url;
	open(url);
}
// Xu ly khi NSD nhan phim ENTER trong o textbox "Tim Kiem"
function txt_filter_keydown(){
	var keyCode = (document.layers) ? keyStroke.which : event.keyCode;
	// Phim Enter
	if(keyCode == 13){
		document.forms[0].btn_filter.onclick();
		return;
	}
}
// ham process_hot_key() xu ly cac phim nong tren form
function process_hot_key(p_f12, p_insert, p_delete, p_esc, p_enter){
	var keyCode = (document.layers) ? keyStroke.which : event.keyCode;
	// Phim INSERT
	if(keyCode == 45 && p_insert){
		document.forms[0].btn_add.onclick();
		return;
	}
	// Phim Delete
	if(keyCode == 46 && p_delete){
		document.forms[0].btn_delete.onclick();
		return;
	}
	// Phim ESC
	if(keyCode == 27 && p_esc){
		document.forms[0].btn_back.onclick();
		return;
	}
	// Phim F12
	if(keyCode == 123 && p_esc){
		document.forms[0].btn_save.onclick();
		return;
	}
}

// ham btn_filter_onclick() duoc goi khi NSD nhan chuot vao nut "Loc" tren man hinh hien thi mot danh sach
// p_hidden_filter_obj: doi tuong hidden dung de luu dieu kien tim kiem
// p_filter_obj: doi tuong textbox dung de nhap dieu kien tim kiem
// p_fuseaction: ten fuseaction tiep theo
function btn_filter_onclick(p_hidden_filter_obj, p_filter_obj, p_fuseaction){
	p_hidden_filter_obj.value = p_filter_obj.value;
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}

// ham row_onclick() duoc goi khi NSD nhan chuot vao 1 dong trong danh sach. Ham nay co 3 tham so:
//  - tham so thu nhat la ten form field chua ID cua doi tuong,
//  - tham so thu hai la gia tri cua ID
//  - tham so thu ba la ten fuseaction tiep theo
function row_onclick(p_obj, p_value, p_goto_url){
	p_obj.value = p_value;
	if (_MODAL_DIALOG_MODE==1){
		v_url = _DSP_MODAL_DIALOG_URL_PATH;
		v_url = v_url + "?goto_url=" + p_goto_url + "&hdn_item_id=" + p_value + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1" + "&" + randomizeNumber();
		sRtn = showModalDialog(v_url,"","dialogWidth=470pt;dialogHeight=210pt;status=no;scroll=no;");
		//document.forms[0].fuseaction.value = "";
		//document.forms[0].submit();
		document.getElementsByTagName('form')[0].action = p_goto_url;
		document.getElementsByTagName('form')[0].submit();
	}else{
		document.getElementsByTagName('form')[0].action = p_goto_url;
		document.getElementsByTagName('form')[0].submit();
	}
}

// ham btn_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_delete_onclick(p_checkbox_obj, p_hidden_obj, p_url, UrlAjax, DocType, TableName){
	var Delimitor   = '!#~$|*';
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){
			value_list = checkbox_value_to_list(p_checkbox_obj,",");
			var key = 'ListIdDoc=' + value_list + '&DocType='+ DocType +'&TableName='+ TableName +'&delimitor=' + Delimitor;
			arrUrl = UrlAjax.split('/');
			postAJAXHTTPText('/' + arrUrl[3] + '/public/ajax/deleteAllFile.php',key,'', null);	
			p_hidden_obj.value = value_list; //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			actionUrl(p_url);
		}
	}
}
//Ham btn_update_onclick duoc goi khi NSD bam nut "Sua"
//- p_checkbox_obj		Ten cua checkbox
//- p_url				Dia chi URL de thuc thi
function btn_update_onclick(p_checkbox_obj,p_url){
	v_value_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!v_value_list){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		arr_value = v_value_list.split(",");
		if (arr_value.length > 1){
			alert("Chi duoc chon mot doi tuong de sua")
			return;
		}
		else
			item_onclick(v_value_list,p_url);
	}
}
// ham btn_add_onclick() duoc goi khi NSD nhan chuot vao nut "Them"
//  - p_obj: ten cua object chua id duoc chon (dat bang 0)
//  - p_fuseaction: ten fuseaction tiep theo
function btn_add_onclick(p_obj, p_fuseaction, p_goto_url){
	p_obj.value = 0;
	if (_MODAL_DIALOG_MODE==1){
		v_url = _DSP_MODAL_DIALOG_URL_PATH;
		v_url = v_url + "?goto_url=" + p_goto_url + "&hdn_item_id=0" + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1" + "&" + randomizeNumber();
		sRtn = showModalDialog(v_url,"","dialogWidth=470pt;dialogHeight=210pt;status=no;scroll=no;");
		//alert(sRtn);
		document.forms[0].fuseaction.value = "";
		document.forms[0].submit();
	}else{
		document.forms[0].fuseaction.value = p_fuseaction;
		document.forms[0].submit();
	}
}

// ham btn_select_onclick() duoc goi khi NSD nhan chuot vao nut "Chon". Ham nay tra lai gia tri cua dong duoc chon va dong cua so lai
function btn_select_onclick(p_checkbox_obj){
	list_of_id = checkbox_value_to_list(p_checkbox_obj,",");
	if (!list_of_id){
		alert("Chua co doi tuong nao duoc chon");
		return;
	}
	window.returnValue = list_get_first(list_of_id,',');
	window.close();
}

// ham btn_save_onclick() duoc goi khi NSD nhan chuot vao nut "Chap nhan"
//  - p_fuseaction: ten fuseaction tiep theo
function btn_save_onclick(p_fuseaction){
	if (_MODAL_DIALOG_MODE==1)
		document.forms[0].action = "index.php?modal_dialog_mode=1";
	else
		document.forms[0].action = "index.php";

	if (verify(document.forms[0])){
		document.forms[0].fuseaction.value = p_fuseaction;
		document.forms[0].submit();
	}
}

// ham btn_single_checkbox_onclick() duoc goi khi NSD nhan chuot vao mot o Check-box
//  - p_checkbox_obj: ten doi tuong checkbox chi nhan 1 trong 2 gia tri 1 va 0
//  - p_checkbox_obj: ten doi tuong (kieu hidden) luu gia tri cua checkbox
function btn_single_checkbox_onclick(p_checkbox_obj, p_hidden_obj){
	if (p_checkbox_obj.checked)
		p_hidden_obj.value = 1;
	else
		p_hidden_obj.value = 0;
}

// ham btn_back_onclick() duoc goi khi NSD nhan chuot vao nut "Quay lai"
//  - p_url: URL quay lai
function btn_back_onclick(p_url){
	if (_MODAL_DIALOG_MODE==1){
		if (_ALLOW_EDITING_IN_MODAL_DIALOG!=1)
			window.close();
		else{
			document.getElementsByTagName('form')[0].action = p_url;
			document.getElementsByTagName('form')[0].submit(); 
		}
	}else{
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}
}

//
function goto_after_update_data(p_save_and_add_new, p_fuseaction, p_return_value){
	if (p_save_and_add_new==1){
		if (_MODAL_DIALOG_MODE==1){
			document.forms[0].action = "index.php?modal_dialog_mode=1";
		}else{
			document.forms[0].action = "index.php";
		}
		document.forms[0].fuseaction.value = p_fuseaction;
		document.forms[0].submit();
	}else{
			document.forms[0].action = "index.php";
			document.forms[0].submit();
		//}
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
// Ham nay duoc goi khi NSD click vao ten mot tap tin (de xem hoac download)
function show_file_content(p_filename){
	window.location = p_filename;
}
// Hien thi cua so ModalDialog de chon ngay
// p_url: duong dan URL toi thu muc chua calendar.html
// p_obj: ten doi tuong form nhan gia tri ngay
function DoCal(p_url, p_obj, browerName){
	var url = p_url + "Calendar.htm";
	var sRtn;
	if(browerName == 'Safari'){
		dataitem = window.open(url,'','height=230px,width=285px,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,modal=yes');
		targetitem = p_obj;
		dataitem.targetitem = targetitem;
	}else{		
		sRtn = showModalDialog(url,"","dialogWidth=285px;dialogHeight=230px;status=no;scroll=no;dialogCenter=yes");			
	    if (sRtn!=""){
			p_obj.value = sRtn;
	    }
	}
}
function openPopUpWindow(url)
{
    open(url,'','height=230px,width=285px,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,modal=yes');
}
// Quay ve trang truoc do
function goback(){
	if (_MODAL_DIALOG_MODE==1){
		if (_ALLOW_EDITING_IN_MODAL_DIALOG!=1)
			window.close();
		else{
			document.forms[0].fuseaction="";
			document.forms[0].submit();
		}
	}else
		window.history.back();
}
//Chuyen toi url
function goto_url(p_url,p_open_new_win)
{
	if (p_open_new_win==3)
		open_me(p_url, 1, 1, 1, 1, 1, 0, 0, 0, 0, 450, 650, 0, 0);
	else{
		//document.parentWindow.location = p_url;	
		//alert (document.location);
		if (p_open_new_win==2)
			window.top.location = p_url;
		else{
			window.location = p_url;
		}
	}
}
// open new window with some value
function open_me(url, vStatus, vResizeable, vScrollbars, vToolbar, vLocation, vFullscreen, vTitlebar, vCentered, vHeight, vWidth, vTop, vLeft)	 {
	winDef = '';
	winDef = winDef.concat('status=').concat((vStatus) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('resizable=').concat((vResizeable) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('scrollbars=').concat((vScrollbars) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('toolbar=').concat((vToolbar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('location=').concat((vLocation) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('fullscreen=').concat((vFullscreen) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('titlebar=').concat((vTitlebar) ? 'yes' : 'no').concat(',');
	winDef = winDef.concat('height=').concat(vHeight).concat(',');
	winDef = winDef.concat('width=').concat(vWidth).concat(',');

	if (vCentered)	{
		winDef = winDef.concat('top=').concat((screen.height - vHeight)/2).concat(',');
		winDef = winDef.concat('left=').concat((screen.width - vWidth)/2);
	}
	else	{
		winDef = winDef.concat('top=').concat(vTop).concat(',');
		winDef = winDef.concat('left=').concat(vLeft);
	}
	open(url, '_blank', winDef);
}
// formfield_value_to_list(the_field) converts all values of a form field to a list
// the_field is a form field that have some elements, the_field must not be a checkbox object
function formfield_value_to_list(the_field, the_separator)
{
	var ret = "";
	if (!the_field.length)
	{
		ret=the_field.value;
	}
	else
	{
		for(i=0;i<the_field.length;i++)
		{
			ret=list_append(ret,the_field[i].value,the_separator);
		}
	}
	return ret;
}
// checkbox_value_to_list(the_field) converts all values of a checkbox object to a list
// the_field is a checkbox object
function checkbox_value_to_list(the_field,the_separator){
	var ret = "";
	try{
		if (!the_field.length){
			if (the_field.checked){
				ret=the_field.value;
			}
		}else{
			for(i=0;i<the_field.length;i++){
				if (the_field[i].checked){
					ret=list_append(ret,the_field[i].value,the_separator);
				}
			}
		}
	}catch(e){;}
	return ret;
}
// Lay phan tu dau tien cua mot danh sach
function list_get_first(the_list,the_separator)
{
	if (the_list=="") return "";
	arr_value = the_list.split(the_separator);
	return arr_value[0];
}

// Kiem tra phan tu the_element co trong danh sach the_list hay khong
function list_have_element(the_list,the_element, the_separator)
{
	if (the_list=="") return -1;
	arr_value = the_list.split(the_separator);
	for(var i=0;i<arr_value.length;i++){
		if (arr_value[i]==the_element){
			return i;
		}
	}
	return -1;
}
function list_count_element(the_list,the_separator)
{	
	if (the_list=="") return -1;
	arr_value = the_list.split(the_separator);
	if (arr_value.length > 0){
		return arr_value.length;
	}
	return -1;
}	
// add a value to a list
function list_append(the_list,the_value,the_separator)
{
	var list=the_list;
	if (list=="") list = the_value;
	else if (the_value !="") list = list+the_separator+the_value;
	return list;
}
// this function does nothing
function nothing()
{
	return;
}
//Add p_numberofday days to  p_date
// p_date and return value are in US format
function set_date(p_date, p_numberofday)
{
	dt_date = new Date(p_date);
	dt_date.setDate(p_numberofday);
	return dt_date.toLocaleString();
}
// Return number of dates from the begining of the month
function get_date(p_date)
{
	dt_date = new Date(p_date);
	dt_date.getDate();
	return dt_date.getDate();
}
//Convert date from mmddyyyy format to ddmmyyyy format
function mmddyyyy_to_ddmmyyyy(theDate)
{
	strSeparator = ""
	if (theDate.indexOf("/")!=-1) strSeparator = "/";
	if (theDate.indexOf("-")!=-1) strSeparator = "-";
	if (theDate.indexOf(".")!=-1) strSeparator = ".";
	if (strSeparator == "")
		return "";
	parts=theDate.split(strSeparator);
	day=parts[1];
	month=parts[0];
	year=parts[2];
	return day + strSeparator + month + strSeparator + year.substr(0,4);

}
//Convert date from ddmmyyyy format to mmddyyyy fromat
function ddmmyyyy_to_mmddyyyy(theDate)
{
	strSeparator = ""
	if (theDate.indexOf("/")!=-1) strSeparator = "/";
	if (theDate.indexOf("-")!=-1) strSeparator = "-";
	if (theDate.indexOf(".")!=-1) strSeparator = ".";
	if (strSeparator == "")
		return "";
	parts=theDate.split(strSeparator);
	day=parts[0];
	month=parts[1];
	year=parts[2];
	return month + strSeparator + day + strSeparator + year.substr(0,4);

}
// ***********************************************************
// Compare date
// Format of p_str_date1 and p_str_date2 is: dd/mm/yyyy
// Return value:
//		>0 - p_str_date1>p_str_date2
//		 0  - p_str_date1=p_str_date2
//		-1 - p_str_date1<p_str_date2
// ***********************************************************
function date_compare(p_str_date1,p_str_date2)
{
	date1 = new Date(ddmmyyyy_to_mmddyyyy(p_str_date1));
	date2 = new Date(ddmmyyyy_to_mmddyyyy(p_str_date2));
	year1 = date1.getFullYear()*1;
	month1 = date1.getMonth()*1;
	day1 = date1.getDate()*1;

	year2 = date2.getFullYear()*1;
	month2 = date2.getMonth()*1;
	day2 = date2.getDate()*1;

	if (year1 > year2)
		return -1;
	else if (year1 < year2)	return 1;
	else
	{
		if (month1 > month2) return -1
		else if (month1 < month2) return 1
		else
		{
			if (day1 > day2)return -1;
			else if (day1<day2)return 1;
			else return 0;
		}
	}
}
// Valid number
function isnum(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}
// Valid double
function isdouble(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	// if there are more character ".", it is invalid double
	if (count_char(passedVal,'.')>1)
		return false;
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)!="." && passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)!="." && passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}
// Valid float
function isfloat(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	// if there are more character ".", it is invalid float
	if (count_char(passedVal,'.')>1)
		return false;
	// if there are more character "-", it is invalid float
	if (count_char(passedVal,'-')>1)
		return false;
	if (passedVal.indexOf('-')>0)
		return false;
		passedVal=passedVal.substring(1);
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)!="." && passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)!="." && passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}
// Get the number of times the "what_char" is in "what_str"
function count_char(what_str,what_char)
{
	if (what_str=="") return 0;
	var str;
	var count;
	var pos;
	count=0;
	str=what_str;
	while (str.indexOf(what_char,0)>=0)
	{
		count++;
		pos=str.indexOf(what_char,0)+1;
		str=str.substring(pos);
	}
	return count;
}
//Checking email;
function isemail(email)
{
 	var invalidChars ="/ :,;";

	if (email=="")
	{
		return false;
	}

	for (i=0; i<invalidChars.length;i++)
	{
		badChar = invalidChars.charAt(i);
		if(email.indexOf(badChar,0)>-1)
		{
			return false;
		}
	}
	atPos =email.indexOf("@",1)
	if(atPos==-1){
		return false;
	}
	if (email.indexOf("@",atPos+1)>-1){
		return false;
	}
	periodPos = email.indexOf(".",atPos);
	if (periodPos==-1){
		return false;
	}
	if(periodPos+3 > email.length){
		return false;
	}
	return true;
}
// Check date
function isdate(the_date) {
	var strDatestyle = "EU";  //European date style
	var strDate;
	var strDateArray;
	var strDay;
	var strMonth;
	var strYear;
	var intday;
	var intMonth;
	var intYear;
	var booFound = false;
	var strSeparatorArray = new Array("-"," ","/",".");
	var intElementNr;
	var err = 0;
	var strMonthArray = new Array(12);

	strMonthArray[0] = "Jan";
	strMonthArray[1] = "Feb";
	strMonthArray[2] = "Mar";
	strMonthArray[3] = "Apr";
	strMonthArray[4] = "May";
	strMonthArray[5] = "Jun";
	strMonthArray[6] = "Jul";
	strMonthArray[7] = "Aug";
	strMonthArray[8] = "Sep";
	strMonthArray[9] = "Oct";
	strMonthArray[10] = "Nov";
	strMonthArray[11] = "Dec";
	strDate = the_date;
	if (strDate == "") {
		return false;
	}
	for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
		if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
			strDateArray = strDate.split(strSeparatorArray[intElementNr]);
			if (strDateArray.length != 3) {
				err = 1;
				return false;
			} else {
				strDay = strDateArray[0];
				strMonth = strDateArray[1];
				strYear = strDateArray[2];
			}
			booFound = true;
	   }
	}
	if (booFound == false) {
		if (strDate.length>5) {
			strDay = strDate.substr(0, 2);
			strMonth = strDate.substr(2, 2);
			strYear = strDate.substr(4);
		} else {
			return false;
		}
	}
	if (strYear.length == 2) {
		strYear = '20' + strYear;
	}
	// US style
	if (strDatestyle == "US") {
		strTemp = strDay;
		strDay = strMonth;
		strMonth = strTemp;
	}
	if (!isnum(strDay)) {
		err = 2;
		return false;
	}
	intday = parseInt(strDay, 10);
	if (isNaN(intday)) {
		err = 2;
		return false;
	}
	if (!isnum(strMonth)) {
		err = 3;
		return false;
	}
	intMonth = parseInt(strMonth, 10);
	if (isNaN(intMonth)) {
		for (i = 0;i<12;i++) {
			if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
				intMonth = i+1;
				strMonth = strMonthArray[i];
				i = 12;
		   }
		}
		if (isNaN(intMonth)) {
			err = 3;
			return false;
	   }
	}
	if (!isnum(strYear)) {
		err = 4;
		return false;
	}
	intYear = parseInt(strYear, 10);
	if (isNaN(intYear)) {
		err = 4;
		return false;
	}
	if (intMonth>12 || intMonth<1) {
		err = 5;
		return false;
	}
	if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
		err = 6;
		return false;
	}
	if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
		err = 7;
		return false;
	}
	if (intMonth == 2) {
		if (intday < 1) {
			err = 8;
			return false;
		}
		if (LeapYear(intYear) == true) {
			if (intday > 29) {
				err = 9;
				return false;
			}
		} else {
			if (intday > 28) {
				err = 10;
				return false;
			}
		}
	}
	return true;
}
//Lay ngay tiep theo cua ngay trong elTerget.value
function getNext_Date(elTarget) {
	if(isdate(elTarget.value)){
			var theDate,strSeparator,arr,day,month,year;
			strSeparator = "";
			theDate = elTarget.value;
			if (theDate.indexOf("/")!=-1) strSeparator = "/";
			if (theDate.indexOf("-")!=-1) strSeparator = "-";
			if (theDate.indexOf(".")!=-1) strSeparator = ".";
			if (strSeparator != "") {
			arr=theDate.split(strSeparator);
			day=new Number(arr[0])+1;
			month=new Number(arr[1]);
			year=new Number(arr[2]);
			if(day > 28) {
				if (((month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12) && (day > 31))
				|| ((month == 4 || month == 6 || month == 9 || month == 11) && (day > 30))||(month == 2 && year % 4 !=0)||(month == 2 && year % 4 ==0 && day > 29))
				{
					day = 1;
					month = month+1;
				}
				if (month > 12 ){
					year = year +1;
					month = 1;
				}

			}
			elTarget.value = day + strSeparator + month + strSeparator + year;
		}
   }

}
//Lay ngay truoc cua ngay trong elTerget.value
function getPrior_Date(elTarget) {
	if(isdate(elTarget.value)){
			var theDate,strSeparator,arr,day,month,year,arr1;
			strSeparator = "";
			theDate = elTarget.value;
			if (theDate.indexOf("/")!=-1) strSeparator = "/";
			if (theDate.indexOf("-")!=-1) strSeparator = "-";
			if (theDate.indexOf(".")!=-1) strSeparator = ".";
			if (strSeparator != "") {
			arr=theDate.split(strSeparator);
			day=new Number(arr[0])-1;
			arr1=new Number(arr[1]);
			year=new Number(arr[2]);
			if(day == 0) {
				if ((arr1 -1 == 1) || (arr1 -1 == 3) || (arr1 -1 == 5) || (arr1 -1 == 7) || (arr1 -1 == 8) || (arr1 -1 == 10) || (arr1 -1 == 12)){
					day = 31;
					month = arr1 -1;
				}
				if((arr1 -1 == 4) || (arr1 -1 == 6) || (arr1 -1 == 9) || (arr1 -1 == 11)){
					day = 30;
					month = arr1 - 1;
				}
				if ((arr1 -1 == 2) && (year % 4 !=0)){
					day = 28;
					month = arr1 -1;
				}
				if((arr1 -1 == 2) && (year % 4 ==0)){
					day = 29;
					month = arr1-1;
				}
				if (arr1 - 1 == 0 ){
					day = 31;
					month = 12;
					year = year -1;
				}
			}else{
				month = arr1;
			}
			elTarget.value = day + strSeparator + month + strSeparator + year;
	    }
    }

}
function LeapYear(intYear) {
if (intYear % 100 == 0) {
if (intYear % 400 == 0) { return true; }
}
else {
if ((intYear % 4) == 0) { return true; }
}
return false;
}
// return true if at least one item is checked
function ischecked(f,objname)
{
	var tmpchecked = false;
	var i=0
	for (i=0;i<f.length;i++)
	{
		var e1=f.elements[i];
		if (e1.name==objname && e1.checked)
		{
			tmpchecked = true;
			break;
		}
	}
	return tmpchecked;
}
// return true if a string contains only white characters
function isblank(s)
{
	var i;
	for (i=0;i<s.length;i++)
	{
		var c=s.charAt(i);
		if ((c!=" ") && (c!="\n") && (c!="\t")) return false;
	}
	return true;
}
function verify(f){
	var errors = "";
	var i;
	var strSeparatorArray = new Array("-"," ","/",".");
	for (i=0;i<f.length;i++)
	{
		var e=f.elements[i];
		
		//get cac thuoc tinh cua cac doi tuong tren form
		var optional 	= e.getAttribute("optional");
		var message 	= e.getAttribute("message");
		var is_hour 	= e.getAttribute("ishour");
		var is_email 	= e.getAttribute("isemail");
		var is_date 	= e.getAttribute("isdate");
		var is_numeric 	= e.getAttribute("isnumeric");
		var is_double 	= e.getAttribute("isdouble");
		var is_float 	= e.getAttribute("isdouble");
		var min 		= e.getAttribute("min")*1;
		var max 		= e.getAttribute("max")*1;
		var maxlength 	= e.getAttribute("maxlength")*1;
		var checkempty  = e.getAttribute("checkempty");
		if (e.type =="radio" &&  !optional)
		{
			if (ischecked(f,e.name)==false)
			{
				if (message!=null) alert(message);
				else alert("At least one "+e.name+" must be checked");
				e.focus();
				return false;
			}
		}
		// If it is hour object
		if ((is_hour) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (IsHour(e,':')==false)
			{
				if (message!=null) alert(message);
				else alert("Hour is invalid");
				e.focus();
				return false;
			}
		}
		// If it is email object
		if ((is_email) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (isemail(e.value)==false)
			{
				if (message!=null) alert(message);
				else alert("Email is invalid");
				e.focus();
				return false;
			}
		}
		// if it is Date object
		if (is_date && !optional){
			if ((e.value==null) || (e.value=="")){
				if (message!=null) alert(message);
				else alert("Date is invalid");
				e.focus();
				return false;
			}
		}
		
		if (((is_date) && !((e.value==null) || (e.value=="") || isblank(e.value))))	{
			if (isdate(e.value)==false)	{
				if (message!=null) alert(message);
				else alert("Date is invalid");
				e.focus();
				return false;
			}else{
				var strSeparatorArray = new Array("-"," ","/",".");
				for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
					if (e.value.indexOf(strSeparatorArray[intElementNr]) != -1) {
						strDateArray = e.value.split(strSeparatorArray[intElementNr]);
						if (strDateArray[2].length != 4) {
							if (message!=null) alert(message);
							else alert("Date is invalid");
							e.focus();
							return false;
						}
				   }
				}
			}
		}
		// if it is number object
		//if ((e.isnumeric || e.isdouble || (e.min!=null) || (e.max!=null)) && !((e.value==null) || (e.value=="") || isblank(e.value)) && !e.optional)
		if ((is_numeric || is_double || is_float || (min!=0) || (max!=0)) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (!_DECIMAL_DELIMITOR) 
				decimal_delimitor = ",";
			else 
				decimal_delimitor = _DECIMAL_DELIMITOR;
			test_value = replace(e.value,decimal_delimitor,"");
			if (is_double)
				is_number = isdouble(test_value);
			else{
				if (isfloat)
					is_number = isfloat(test_value);
				else	
					is_number = isnum(test_value);
			}

			var v = parseFloat(test_value);
			var v_min = parseFloat(min);
			var v_max = parseFloat(max);
			if (!is_number
				|| ((min != 0) && (v < v_min))
				|| ((max != 0) && (v > v_max)))
			{
				errors += "- The field "+ e.name + " must be a number";
				if (min!=null)
					errors += " that is greater than "+min;
				if (min!=0 && max!=0)
					errors += " and less than "+max;
				else if (max !=0)
					errors += " That is less than "+max;
				errors += ".\n";
				if (message!=null) alert(message);
				else alert(errors);
				e.focus();
				return false;
			}
		}
		// check maxlength
		if ((maxlength!=0) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (e.value.length > maxlength)
			{
				alert("The length of " + e.name + " must be less than " + maxlength);
				e.focus();
				return false;
			}
		}
		// check multiple selectbox must be not empty
		if (checkempty && e.type=="select-multiple" && e.length==0)
		{
			if (message!=null) alert(message);
			else alert(e.name+" must be not empty");
			e.focus();
			return false;
		}
		// Check for text, textarea
		if (((e.type == "password") || (e.type == "text") || (e.type == "textarea") || (e.type == "select-one") || (e.type == "checkbox")) && !optional)
		{
			if((e.value==null) || (e.value == "") || isblank(e.value))
			{				
				if (message != null) alert(message);
				else alert(e.name + " must be not empty");
				e.focus();
				return false;
			}
		}
	}
	return true;
}
/*********************************************************************************************************************
// Functions for moving items from a selectbox object to other one													//
//   Before call this function, you have to set value to properties "id","text" of all options of the "from_obj"	//
//   Parameters:																									//
//	   from_obj: is the object name of a select-multiple you want to move some it's options to ...					//
//	   to_obj: is the object name of a select-multiple you want to add some options									//
**********************************************************************************************************************/

function move_selectbox_item(from_obj,to_obj)
{
	var len=0;
	for (i=0; i<from_obj.length; i++)
	{
		if (from_obj.options[i].selected)
		{
			if (to_obj.length==null) len = 0;
			else len=to_obj.length;
			to_obj.options[len] = new Option(from_obj.options[i].name,from_obj.options[i].id);
			to_obj.options[len].value = from_obj.options[i].value;
			to_obj.options[len].id = from_obj.options[i].id;
			to_obj.options[len].name = from_obj.options[i].name;
			from_obj.options[i].id = -1;
		}
	}
	defrag_object(from_obj);
	refresh(from_obj);
}
// Reindex all options of a select box after moving some options to another one
function defrag_object(obj) {
	var len = obj.length;
	var i =0;
	while (i<len)
	{
		if (obj.options[i].id == -1)
		{
			if (i != (len - 1))
			{
				for (j=i; j<len-1 ; j++)
				{
					obj.options[j].id = obj.options[j + 1].id;
					obj.options[j].name = obj.options[j + 1].name;
	//				obj.options[j].value = obj.options[j + 1].value;

				}
			}
			else
			{
				i++;
			}
			len--;
		}
		else
		{
			i++;
		}
	}
	obj.length = len;
}
// Refresh a select box after moving some options to another one
function refresh(obj)
{
	for (i=0; i<obj.length; i++)
	{
		oid=obj.options[i].id;
		oname=obj.options[i].name;
		obj.options[i] = new Option(obj.options[i].name, obj.options[i].id);
		obj.options[i].id=oid;
		obj.options[i].name=oname;
	}
}
/* Display options of the selectbox object by a foreign key value
   Parameters:
	   parent_id: the key value to fillter
	   child_obj: is a name of selectbox object that will be displayed it's options by parent_id
*/
function display_childselectbox_by_fk(parent_id,child_obj){
	var k = 0;
	var len = child_obj.arr_parent_id.length;
	child_obj.length=0;
	for (i=0; i<len; i++)
	{
		if (child_obj.arr_parent_id[i] == parent_id || parent_id == "")
		{
			child_obj.options[k] = new Option(child_obj.arr_text[i]);
			child_obj.options[k].value = child_obj.arr_id[i];
			k=k+1;
		}
		//if (k>0) child_obj.options[0].selected;
	}
}
/*********************************************************************************************************************
// Display options of the selectbox object by a value. Text and value of those options will be set from 1 to "maxvalue"
//   Parameters:
//   		child_obj: is a name of selectbox object that will be displayed it's options with text and value from 1 to "maxvalue"
//		maxvalue:
**********************************************************************************************************************/
function display_childselectbox_by_value(child_obj,maxvalue)
{
	var k = 0;
	child_obj.length=0;
	for (i=1; i<maxvalue+1; i++)
	{
		child_obj.options[k] = new Option(i);
		child_obj.options[k].value = i;
		k=k+1;
	}
	//if (k>0) child_obj.options[0].selected;
}
// select all checkboxelements named objname in the form
function select_all_checkbox(f,objname){
	for (i=0;i<f.length;i++){
		var e=f.elements[i];
	 	if (e.name == objname && !e.disabled){
			f.elements[i].checked = true;
	  	}
	}
	return true;
}

// deselect all elements named objname in the form
function deselect_all(f,objname){
	for (i=0;i<f.length;i++){
	var e=f.elements[i];
		if (e.name == objname){
			f.elements[i].checked = false;
		}
	}
	return true;
}
//	Function set_selected set the i option to be checked if its value equals p_value
function set_selected(p_obj,p_value){
	for (i=0;i<p_obj.options.length;i++){
		if (p_obj.options[i].value==p_value){
			p_obj.options[i].selected = true;
			break;
		}
	}
}
// function isselected(p_obj) returns true if p_obj has been selected
function isselected(p_obj){
	for (i=0;i<p_obj.options.length;i++){
		if (p_obj.options[i].selected = true){
			return true;
		}
	}
	return false;
}

/*================================================================
function saveControl(src, dest);
Purpose:
	- Save data from a list control into a textbox.
Input:
	- src:	list control.
	- dest: text control.
================================================================*/
function saveControl(src, dest){
	var i;
	var s="";
	if (src.options.length>0){
		for (i=0; i<src.options.length; i++){
			s = s + src.options[i].value + "|" + src.options[i].name + ",";
		}
		dest.value = s.substring(0, s.length-1);
	} else {
		dest.value = "";
	}
}
/*================================================================
function restoreControl(src, dest);
Purpose:
	- Restore data from a list control back to a textbox.
Input:
	- src:	text control.
	- dest: list control.
================================================================*/
function restoreControl(src, dest) {
	var i;
	var s;
	for (i=dest.options.length-1; i>=0; i--) {
		dest.options.remove(dest.options[i]);
	}

	i=0;
	s = src.value+",";
	while (s.indexOf(",")>0) {
		var sValue = "" + s.substring(0, s.indexOf("|"));
		var sName = "" + s.substring(s.indexOf("|")+1, s.indexOf(","));

		dest.options[i] = new Option(sName, sValue);
		dest.options[i].id = sValue;
		dest.options[i].value = sValue;
		dest.options[i].name = sName;
		s = s.substring(s.indexOf(",")+1, s.length);
		i++;
	}
}
//--------Chuyen focus toi doi tuong tiep theo-----------------------
// f = form name; 0 = cuurent object name
function change_focus(f,o,ev){
	var ret1 = "";
	var j=0;
	var i=0;
	var b=0;
	first_object_id = -1;
	//try{
		keyCode = ev.keyCode;
		//keyCode = (window.event)?event.keyCode:e.which; 
		if (ev.keyCode == 13 && o.type!="button")
		   {
		      ev.keyCode = 9;
		   }
		// Neu la phim Enter, Down, Up
		if ((keyCode=='9' && o.type=='select-one') || (o.type!='select-one' && (keyCode=='40' || keyCode=='38'))) {
			b=0;
			while (i>=0&&(i<f.length)&&(j<2)){
				var e=f.elements[i];
				// Xac dinh ID cua field dau tien co kieu khong phai la hidden
				if (e.type!='hidden' && first_object_id==-1) first_object_id = i;
				// Tim de vi tri cua doi tuong hien tai
				if ((b==0)&&(e.name==o.name)&&(e.type!='hidden')){
					o.blur();
					b=1;
					if (keyCode!='38'){
						i=i+1;
						if (i==f.length) i = first_object_id;
					}else{
						if (i==first_object_id) i=f.length-1;else i=i-1;
					}
					var e=f.elements[i];
				}
				if (b==1){
					if ((e.type!='hidden')&&(!e.readOnly)&&(!e.disabled)&&(e.hide!='true')){
						e.focus();
						return true;
					}
				}
				if (keyCode!='38'){
					i=i+1;
					if (i==f.length) {i=0;j=j+1;}
				}else{
					i=i-1;
					if (i==first_object_id){i=f.length-1;j=j+1;}
				}
			}
		}
		return true;
	//}catch(e){}
}
// Disable tat ca cac phan tu cua mot form
// f: ten form
function disable_all_elements(f)
{
	var i;
	for (i=0;i<f.length;i++)
	{
		if (f.elements[i].type != "hidden")
			f.elements[i].readOnly = true;
		if (f.elements[i].type == "button")
			f.elements[i].disabled = true;
		if (f.elements[i].type == "submit")
			f.elements[i].disabled = true;
	}
	return;
}
//--------Set focus at the first field of the form "f" -----------------------
function set_focus(f){
	var i=0;
	while (i<f.length){
		var e=f.elements[i];
		if (((e.type=='text')||(e.type=='textarea'))&&(!e.disabled)&&(!e.readOnly)){
			e.focus();
			return true;
			}
		i=i+1;
	}
	return false;
}
/*********************************************************************************************************************
//	Ham to_upper_case bien chu thuong thanh chu hoa
//	Khi goi : onchange="JavaScript:ToUpperKey(this)"
/*********************************************************************************************************************/
 function to_upper_case(p_obj)
 {
	p_obj.value = p_obj.value.toUpperCase();
 }
//	Ham to_lower_case bien chu hoa thanh chu thuong
//	Khi goi : onchange="JavaScript:ToLowerKey(this)"
 function to_lower_case(p_obj)
 {
	p_obj.value = p_obj.value.toLowerCase();
 }
//**********************************************************************************************************************
//Doi so xxxxxx.xxxx thanh dinh dang xxx,xxx.xxxx
function FormatCurrency(num){
var sign, tail; 
	num = num.toString().replace(/\$|\,/g,''); 
	if(isNaN(num)) 
		num = "0"; 
	sign = (num == (num = Math.abs(num))); 
	var str=num.toString();
	var arr_str = str.split(".");
	if(arr_str.length > 1){
		var tail = new String(arr_str[1])
		if(tail.length<2){
			tail =tail + '0';
		}
	}else{
		tail = '';
	}	
	num = arr_str[0];
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) 
		num = num.substring(0,num.length-(4*i+3))+','+ 

	num.substring(num.length-(4*i+3)); 
	
	if (tail=='')
		ret_value = (((sign)?'':'-') + num);
	else
		ret_value = (((sign)?'':'-') + num + '.' + tail);
	return ret_value; 
} 
/**********************************************************************************************************************
//	Ham FormatMoney tu dong them dau "," vao text box khi nhap gia tri co kien la "Tien"
//	Khi do TextBox co dang : "123,456,789"
//	Khi goi : onkeyup="JavaScript:FormatMoney(this)"
***********************************************************************************************************************/
 function format_money(Obj,e)
 {
	var theKey = e.keyCode;
	var theLen = Obj.value.length;
	var theStringNum = Obj.value;
	theSecondStringNum = "";
	// Neu ki tu dau tien la "." thi bo qua
	if (theStringNum=="."){
		Obj.value = "";
		return;
	}
	pos = theStringNum.indexOf(".",0)
	if (pos>0){
		arr_numstr = theStringNum.split(".");
		theFirstStringNum = theStringNum.substr(0,pos);
		theSecondStringNum = theStringNum.substr(pos+1,theStringNum.length-pos);
		if (theSecondStringNum.substr(theSecondStringNum.length-1,1)=="."){
			Obj.value = theStringNum.substr(0,theStringNum.length-1);
			return;
		}
		theStringNum = theFirstStringNum;
	}
	//Chi nhan cac ky tu la so
	if ((theKey >= 48 && theKey <= 57)||(theKey >= 96 && theKey <= 105)||(theKey==8)||(theKey==46))
	{
		var theNewString;
		var theSubString;
		var LastIndex;
		LastIndex = 0;
		theSubString=""
		// Thay the ky tu ","
		for(var i=0;i<theStringNum.length;i++)
		{
			if (theStringNum.substring(i,i+1)==_DECIMAL_DELIMITOR)		// Tim ky tu ","
			{
				theSubString = theSubString + theStringNum.substring(LastIndex,i)
				LastIndex = i+1;
			}
		}
		theSubString = theSubString + theStringNum.substring(LastIndex,theStringNum.length) // Lay mot doan cuoi cung (vi doan cuoi cung khong co ky tu ",")
		theStringNum = theSubString;

		theNewString = ""
		if (theStringNum.length > 3)
		while(theStringNum.length > 3)
		{
			theSubString = theStringNum.substring(theStringNum.length-3,theStringNum.length);
			theStringNum = theStringNum.substring(0,theStringNum.length-3);
			theNewString = _DECIMAL_DELIMITOR + theSubString+theNewString;
		}
		if (pos>0)
			theNewString=theStringNum+theNewString+"."+theSecondStringNum;
		else
			theNewString=theStringNum+theNewString;

		if (theLen > 3)
			Obj.value = theNewString;
	}
 }
/***********************************************************************************************************************
Ham FormatDate dinh dang hien thi kieu thoi gian "Ngay/Thang/Nam" trong khi nhap du lieu
Ham nay chi cho phep nhap cac ki tu dang so, neu nhap bat ky ki tu nao khac se khong nhan
Ham nay duoc goi trong su kien onKeyUp cua cac text_box. onKeyUp="FormatDate(this,'/')"
Tham so:
	-txt_obj (Object): doi tuong text box nhap du lieu kieu thoi gian
	-separator_char (character): dau ngan cach giua ngay, thang va nam (Vi du: dau ":", dau "/", dau "|", dau "-", ...)

***********************************************************************************************************************/
function FormatDate(txt_obj, separator_char)
{
	//Lay gia tri ma ASCII cua phim an
	var theKey = event.keyCode;
	var theLen = txt_obj.value.length;
	//Neu an phim BackSpace, Up, Down, Left, Right, Home, End thi khong xu ly
	if (theKey == 8 || theKey == 37 || theKey == 39 || theKey == 40) { return 1;}
	//Loai bo cac ki tu khong phai ky tu so (ke ca dau phan cach ngay thang nam)
	theStr = "";
	for (var i=0; i<theLen; i++){
		theChar = txt_obj.value.charCodeAt(i);
		if (theChar >= 48 & theChar <= 57){
			theStr = theStr + txt_obj.value.substring(i,i+1);
		}
	}
	theLen = theStr.length;
	// Xu ly tao format theo dang thoi gian dd/mm/yyyy
	if (theLen>=4){
		theDate = theStr.substring(0,2);
		theMonth = theStr.substring(2,4);
		theYear = theStr.substring(4);
		txt_obj.value = theDate + separator_char + theMonth + separator_char + theYear;
	}else{
		if (theLen >=2){
			theDate = theStr.substring(0,2);
			theMonth = theStr.substring(2);
			txt_obj.value = theDate + separator_char + theMonth;
		}else{
			txt_obj.value = theStr;
		}
	}
	return 1;
}
/***********************************************************************************************************************
Ham dinh dang hien thi kieu thoi gian hh:mm trong khi nhap du lieu
Ham nay chi cho phep nhap cac ki tu dang so, neu nhap bat ky ki tu nao khac se khong nhan
Ham nay duoc goi trong su kien onKeyUp cua cac text_box. onKeyUp = "format_hour(this,':')"
Tham so:
	-txt_obj (Object): doi tuong text box nhap du lieu kieu thoi gian
	-separator_char (character): dau ngan cach giua gio va phut (Vi du: dau ":", dau "/", dau "|",...)
***********************************************************************************************************************/
function FormatHour(txt_obj, separator_char){
	//Lay gia tri ma ASCII cua phim an
	var theKey = event.keyCode;
	//alert(event.shiftKey);
	var theLen = txt_obj.value.length;

	//Neu an phim BackSpace, Up, Down, Left, Right, Home, End thi khong xu ly
	if (theKey == 8 || theKey == 37 || theKey == 39 || theKey == 40) { return 1;}
	//Xu ly truong hop nguoi su dung nhap dau phan cach

	//Loai bo cac ki tu khong phai ky tu so (ke ca dau phan cach thoi gian gio va phut)
	theStr = "";
	for (var i=0; i<theLen; i++){
		theChar = txt_obj.value.charCodeAt(i);
		if (theChar >= 48 & theChar <= 57){
			theStr = theStr + txt_obj.value.substring(i,i+1);
		}
	}
	//alert(theStr);
	theLen = theStr.length
	// Xu ly tao format theo dang thoi gian hh:mm
	if (theLen>=2) {
		theHour = theStr.substring(0,2);
		if(theHour >= 24){alert("Ban nhap gio khong dung (Gio phai nho hon 24)");txt_obj.value = '';return;}

		theMinute = theStr.substring(2);
		if(theMinute > 59){alert("Ban nhap phut khong dung (Phut phai nho hon 60)"); txt_obj.value = '';return;}

		txt_obj.value = theHour + separator_char + theMinute;
	}else{
		txt_obj.value = theStr;
	}
	//alert(theHour);
	//if(theMinute > 59){alert("Nhap lai phut (Phut phai nho hon 60)"); txt_obj.value = '';return;}
	return 1;
}
/***********************************************************************************************************************
Ham bo sung cac so '0' vao chuoi ky tu kieu thoi gian neu NSD nhap thieu
Ham nay duoc goi trong su kien onBlur cua cac text_box kieu thoi gian. onBlur="AdjustHour(this, ':')"
Tham so:
	-txt_obj (Object): doi tuong text box nhap du lieu kieu thoi gian
	-separator_char (character): dau ngan cach giua gio va phut (Vi du: dau ":", dau "/", dau "|",...)
***********************************************************************************************************************/
function AdjustHour(txt_obj, separator_char){
	// Xu ly tao format theo dang thoi gian hh:mm
	var theLen = txt_obj.value.length;
	theStr = txt_obj.value;
	if (theLen == 1) {
		theHour = '0' + theStr.substring(0,1);
		theMinute = '00';
		txt_obj.value = theHour + separator_char + theMinute;
	}
	if (theLen == 2 || theLen == 3) {
		theHour = theStr.substring(0,2);
		theMinute = '00';
		txt_obj.value = theHour + separator_char + theMinute;
	}
	if (theLen == 4) {
		theHour = theStr.substring(0,2);
		theMinute = '0' + theStr.substring(3,4);
		txt_obj.value = theHour + separator_char + theMinute;
	}
}
/* Ham kiem tra gio cap nhat co hop le hay khong
Tham so:
	-txt_obj (Object): doi tuong text box nhap du lieu kieu thoi gian
	-separator_char (character): dau ngan cach giua gio va phut (Vi du: dau ":", dau "/", dau "|",...)
Tra ve: True neu gio la hop le
		False neu gio khong hop le
Luu y: Ham nay duoc goi trong ham verify(f) de kiem tra cac control cap nhat kieu thoi gian la gio, phut
*/
function IsHour(txt_obj, separator_char){
	var strLen = txt_obj.length;
	var theStr = txt_obj.value;
	var tbeHour = "";
	var theMinute = "";
	//Neu chuoi thoi gian la trang
	if (strLen == 0) {return false;}
	separator_pos = theStr.indexOf(separator_char,1);
	if (separator_pos == 0) {return false;}
	else{
		theHour = theStr.substr(0,separator_pos);
		theMinute = theStr.substr(separator_pos + 1,separator_pos + 3);
		//alert(tbeHour + '---' + theMinute);
	}
	if (parseInt(theHour) > 24 || parseInt(theMinute) > 60){
		return false;
	}
	return true;
}

/********************************************************************************************************************
//	Ham AdjustYearForDate()
//	Xu ly nam khi NSD nhap vao 1 hoac 2 hoac 3 ky tu cho nam :
//	VD: 11/12/2 -> 11/12/2002 ; 11/12/02 -> 11/12/2002 ; 11/12/002 -> 11/12/2002
//		11/12/97 -> 11/12/1997 ; 11/12/997 -> 11/12/1997
// Su dung : AdjustYearForDate(fMyForm.theDate)
*****************************************************************************************************************/
function AdjustYearForDate(Obj)
{
	if(Obj.value !='')
	{
		DateArr = Obj.value.split("/")
		iYear = parseInt(DateArr[2]);
		if(DateArr[2].length == 1)
			if(iYear>0&&iYear<=50)
				Obj.value = DateArr[0]+'/'+DateArr[1]+'/'+'200'+DateArr[2];
		if(DateArr[2].length == 2)
			if(iYear>0&&iYear<=50)
				Obj.value = DateArr[0]+'/'+DateArr[1]+'/'+'20'+DateArr[2];
			else
				Obj.value = DateArr[0]+'/'+DateArr[1]+'/'+'19'+DateArr[2];
		if(DateArr[2].length == 3)
			if(iYear>0&&iYear<=50)
				Obj.value = DateArr[0]+'/'+DateArr[1]+'/'+'20'+DateArr[2].substring(1,3);
			else
				Obj.value = DateArr[0]+'/'+DateArr[1]+'/'+'19'+DateArr[2].substring(1,3);
	}
}
/********************************************************************************************************************
//	Ham GetFileName lay ten file trong mot duong dan file day du VD : "C:\project\filename.txt" lay ra "filename.txt"
//	Khi goi : onchange="GetFileName(Obj,DesObj)"
//	Trong do : Obj : doi tuong chua duong dan file dau du
//			   DesObj : Doi tuong nhan ket qua
*****************************************************************************************************************/
function GetFileName(Obj,DesObj){
	strFilePath = Obj.value;
	FilePathLen = strFilePath.length;
	var strFileName = '';
	var SepChar = '';
	for(var i = FilePathLen;i>=0;i--){
		SepChar = Obj.value.substring(strFilePath.length-1,strFilePath.length);
		if(SepChar!="\\"){
			strFilePath = strFilePath.substring(0,strFilePath.length-1)	;
			strFileName = SepChar+strFileName ;
		}
		else{
			DesObj.value = strFileName;
			return 1;
		}

	}
	if(DesObj.value == ""){
		DesObj.value = Obj.value;
	}
}
//**********************************************************************************************************************
//Ham randomizeNumber() tra lai mot so ngau nhien
//**********************************************************************************************************************
function randomizeNumber(){
	today=new Date();
	jran=today.getTime();
	number = 1000000;
	ia=9301;
	ic=49297;
	im=233280;
	jran = (jran*ia+ic) % im;
	return Math.ceil( (jran/(im*1.0)) *number);
}
//**********************************************************************************************************************
// Ham get_id_from_selected
// Ham lay ID tu danh sach Select duoc sinh ra tu ham GenerateSelectOption
//**********************************************************************************************************************
function get_id_from_selected(p_select_obj,p_hdn_obj)
{
	p_hdn_obj.value = p_select_obj(p_select_obj.selectedIndex).id;
}
//**********************************************************************************************************************
// Ham chuyen gia tri tuong ung tu select sang cac doi tuong Text va Hidden*/
// Cac tham so :Select_obj: Select object Co san du lieu la cac option duoc sinh tu ham GennarateSelectOption trong Publicfunction
//				Text_obj: Text object lay gia tri tuong ung
//				hdn_obj: Dung de lay ID tuong ung tu Text
//**********************************************************************************************************************
function change_text_from_selected(p_select_obj,p_text_obj,p_hdn_obj)
{
	p_text_obj.value = p_select_obj.value.toUpperCase();
	p_hdn_obj.value = p_select_obj(p_select_obj.selectedIndex).id;
}
//**********************************************************************************************************************
// Ham change_selected_from_text():
// Chuc nang:
//	- Thay doi gia tri (value) hien thoi cua mot SelectBox theo gia tri cua mot textbox
//	- Luu ID hien thoi cua mot SelectBox vao mot bien hidden
//Cac tham so:
//	p_select_obj: doi tuong SelectBox duoc thay doi gia tri hien thoi
//	p_text_obj: doi tuong textbox tuong ung
//	p_hdn_obj: doi tuong hidden de luu ID hien thoi cua SelectBox
//**********************************************************************************************************************
function change_selected_from_text(p_select_obj,p_text_obj,p_hdn_obj)
{
	var found_flag = false;
	for(var i=0; i<p_select_obj.length; i++) {
		if (p_select_obj(i).value.toUpperCase()==p_text_obj.value.toUpperCase()) {
			found_flag = true;
			break;
		}
	}
	if (found_flag) {
		p_select_obj(i).selected = true;
		p_hdn_obj.value = p_select_obj(p_select_obj.selectedIndex).id;
		//alert(p_select_obj(p_select_obj.selectedIndex).id);
		p_text_obj.value = p_text_obj.value.toUpperCase();
	}else{
		p_select_obj[0].selected = true;
		p_text_obj.value = "";
	}
}
//**********************************************************************************************************************
// Cac ham JavaScrip thao tac tren TreeView
//**********************************************************************************************************************

//**********************************************************************************************************************
// Ham node_name_onclick()
// Y nghia:
// - Neu o che do cho phep THEM/SUA/XOA thi hien thi man hinh cap nhat 1 doi tuong
// - Neu o che do KHONG cho phep THEM/SUA/XOA thi tra lai 1 chuoi gom ID,CODE,NAME cua doi tuong
// Tham so:
// - node: doi tuong ma NSD nhan CHUOT
//**********************************************************************************************************************
function node_name_onclick(node,select_parent){
	if ((select_parent=='false') && (_MODAL_DIALOG_MODE==1)){
		if(node.level==0){return;}
	}
	if (_MODAL_DIALOG_MODE==1){
		// Neu la che do khong cho phep Them, Xoa, Hieu chinh thi Tra lai mot chuoi chua cac thuoc tinh cua DOI TUONG
		if(_ALLOW_EDITING_IN_MODAL_DIALOG!=1){
			return_value = node.id + _LIST_DELIMITOR + node.value + _LIST_DELIMITOR + node.innerText;
			window.returnValue = return_value;
			//alert(return_value);
			window.close();
			return;
		}else{
			//document.forms[0].fuseaction.value="DISPLAY_SINGLE_AREA";
			document.getElementById('hdn_item_id').value=node.id;
			document.forms[0].submit();
		}
	}else{
		document.getElementById('hdn_item_id').value=node.id;
		document.forms[0].submit();
	}
}
//**********************************************************************************************************************
// Ham select_node_of_tree()
// Y nghia:
// - Tra lai 1 chuoi gom ID,CODE,NAME cua doi tuong duoc chon bang nut RDIO hoac CHECK-BOX
//**********************************************************************************************************************
function select_nodes_of_tree(p_radio_obj, p_checkbox_obj){
	//Xac dinh Radio dang chon
	var v_count;
	return_value = "";
	f = document.all;
	try {
		v_count = p_radio_obj.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_radio_obj[i].checked){
					return_value = f.str_obj[i].item_id + _LIST_DELIMITOR + f.str_obj[i].item_code + _LIST_DELIMITOR + f.str_obj[i].item_title;
					break;
				}
			}
		}else{
			if (p_radio_obj.checked){
				return_value = f.str_obj.item_id + _LIST_DELIMITOR + f.str_obj.item_code + _LIST_DELIMITOR + f.str_obj.item_title;
			}
		}
	} catch(e){;}
	if (return_value != ""){
		window.returnValue = return_value;
		window.close();
		return;
	}
	//Xac dinh check-box dang chon
	var v_count;
	return_value = "";
	f = document.all;
	try {
		v_count = p_checkbox_obj.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_checkbox_obj[i].checked){
					return_value = f.str_obj[i].item_id + _LIST_DELIMITOR + f.str_obj[i].item_code + _LIST_DELIMITOR + f.str_obj[i].item_title;
					break;
				}
			}
		}else{
			if (p_checkbox_obj.checked){
				return_value = f.str_obj.item_id + _LIST_DELIMITOR + f.str_obj.item_code + _LIST_DELIMITOR + f.str_obj.item_title;
			}
		}
	} catch(e){;}
	if (return_value != ""){
		window.returnValue = return_value;
		window.close();
		return;
	}
}
//**********************************************************************************************************************
// Ham delete_nodes_of_tree()
// Y nghia:
// - Xoa 1 hoac nhieu doi tuong duoc chon
//**********************************************************************************************************************
function delete_nodes_of_tree(p_radio_obj, p_checkbox_obj, p_hdn_list_item_id, p_delete_fuseaction){
	//Xac dinh Radio dang chon
	var v_count;
	var v_current_radio_id = "";
	try {
	v_count = p_radio_obj.length;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_radio_obj[i].checked){
				v_current_radio_id = p_radio_obj[i].value;
				break;
			}
		}
	}else{
		if (p_radio_obj.checked){
			v_current_radio_id = p_radio_obj.value;
		}
	}
	} catch(e){;}
	//Kiem tra cac staff co trong unit
	var v_count;
	v_empty_staff=true;
	try{
	v_count = p_checkbox_obj.length;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_checkbox_obj[i].parent_id==v_current_radio_id){
				v_empty_staff=false;
				break;
			}
		}
	}else{
		if (p_radio_obj.parent_id==v_current_radio_id){
			v_empty_staff=false;
		}
	}
	}catch(e){;}
	if (v_empty_staff){
		// Xoa doi tuong hien thoi
		btn_delete_onclick(p_radio_obj,p_hdn_list_item_id,p_delete_fuseaction);
	}else{
		//Xoa cac doi tuong duco chon
		btn_delete_onclick(p_checkbox_obj,p_hdn_list_item_id,p_delete_fuseaction);
	}
}
//**********************************************************************************************************************
// Ham add_node_of_treeview()
// Y nghia:
// - Hien thi man hinh them doi tuong
//**********************************************************************************************************************
function add_node_of_treeview(p_radio_obj,p_hdn_item_id_obj,p_fuseaction){
	//Xac dinh Radio dang chon
	var v_count;
	var v_current_radio_id = ""
	try {
		v_count = p_radio_obj.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_radio_obj[i].checked){
					v_current_radio_id = p_radio_obj[i].value;
					break;
				}
			}
		}else{
			if (p_radio_obj.checked){
				v_current_radio_id = p_radio_obj.value;
			}
		}
	}catch(e){;}
	p_hdn_item_id_obj.value = v_current_radio_id;
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}
//**********************************************************************************************************************
// Ham node_image_onclick()
// Editer: Hoang Van Toan
// date edit: 11/10/2009
// Muc dich: duyet duoc tren moi trinh duyet
// Y nghia:
// - Xy ly khi NSD nhan vao nut "dong/mo" trong CAY
//**********************************************************************************************************************
function node_image_onclick(node,show_control,img_open_container_str,img_close_container_str,hdn_list_parent_obj) {
	//alert(hdn_list_parent_obj.value);
	//Neu nut (anh) la mot nut dang leaf_object thi khong co tuong tac
	if (node.type == '1' || node.type == 1) {return;}
	var nextDIV = node.nextSibling;
	while(nextDIV.nodeName != "DIV"){
		nextDIV = nextDIV.nextSibling;
	}
	if(document.all){
		var nodeItem = node.childNodes.item(0).nodeName;
		var nodeScr  = node.childNodes.item(0).src;
	}else{
		var nodeItem = node.childNodes.item(1).nodeName;
		var nodeScr  = node.childNodes.item(1).src;
	}
	if (nextDIV.style.display == 'none'){
		if (node.childNodes.length > 0) {
			if(nodeItem == "IMG"){
				nodeScr = img_open_container_str;
				try{
					select_parent_radio(document.getElementsByName('rad_item_id'),document.getElementsByName('chk_item_id'),node.id);
				}catch(e){;}
			}
		}
		//Mo nut hie tai dong thoi them id cua nut do vao  chuoi id can lay
		nextDIV.style.display = 'block';
		//Kiem tra xem id cua nut vua click co trong chuoi chua, Neu co roi thi thoi khong add nua
		try{
			if(hdn_list_parent_obj.value.search(node.id) < 0){
				hdn_list_parent_obj.value = hdn_list_parent_obj.value + node.id + _LIST_DELIMITOR ;
			}
		}catch(e){;}
	} else {
		if (node.childNodes.length > 0) {
			if (nodeItem == "IMG"){
				nodeScr = img_close_container_str;
				try{
					select_parent_radio(document.getElementsByName('rad_item_id'),document.getElementsByName('chk_item_id'),node.id);
				}catch(e){;}
			}
		}
		//Neu dong nut do lai thi bo id khoi chuoi
		nextDIV.style.display = 'none';
		try{
			hdn_list_parent_obj.value = hdn_list_parent_obj.value.replace(node.id,'');
			hdn_list_parent_obj.value = hdn_list_parent_obj.value.replace(_LIST_DELIMITOR+_LIST_DELIMITOR,_LIST_DELIMITOR);
		}catch(e){;}
	}
}
//**********************************************************************************************************************
// Ham unselect_checbox()
// Y nghia:
//	1. Khi bam vao Radio button cua mot Unit se bo danh dau checkbox cua cac Staff khong thuoc Unit
//	2. Giu nguyen danh danh cac Staff thuoc Unit.
//**********************************************************************************************************************
function unselect_checbox(p_radio_obj,p_checkbox_obj){
	try{
		var v_count;
		var v_parent_id = p_radio_obj.value;
		v_count = p_checkbox_obj.length;
		if(v_count){
			for(i=0;i<v_count;i++){
				if (p_checkbox_obj[i].parent_id != v_parent_id){
					p_checkbox_obj[i].checked="";
				}
			}
		}else{
			if (p_checkbox_obj.parent_id!=v_parent_id){
				p_checkbox_obj.checked="";
			}
		}
	}catch(e){;}
}
//**********************************************************************************************************************
// Ham select_parent_radio()
// Y nghia:
// su dung trong truong hop danh dau Check box cua mot can bo se dan toi
//	1. Danh dau Radio button cua don vi chua can bo do.
//	2. Bo danh dau cua cac Check box cua cac can bo khong cung don vi voi can bo do
//**********************************************************************************************************************
function select_parent_radio(p_radio_obj,p_checkbox_obj,v_parent_id){
	try{
		var v_count;
		v_count = p_radio_obj.length;
		if(v_count){
			for(i=0;i<v_count;i++){
				if (p_radio_obj[i].value == v_parent_id){
					p_radio_obj[i].checked=true;
					break;
				}else{
					p_radio_obj[i].checked=false;
				}
			}
		}else{
			if (p_radio_obj.value ==v_parent_id){
				p_radio_obj.checked=true;
			}else{
				p_radio_obj.checked=false;
			}
		}
		//bo selected cua cac nhanh khac
		v_count = p_checkbox_obj.length;
		if(v_count){
			for(i=0;i<v_count;i++){
				if (p_checkbox_obj[i].parent_id != v_parent_id){
					p_checkbox_obj[i].checked = "";
				}
			}
		}else{
			if (p_checkbox_obj.parent_id != v_parent_id){
				p_checkbox_obj.checked = "";
			}
		}
	}catch(e){;}
}
//**********************************************************************************************************************
// Ham set_checkbox_checked()
//**********************************************************************************************************************
function set_checkbox_checked(p_radio_obj,p_checkbox_obj,p_list_item_id){
	try{
		var v_count;
		var v_parent_id;
		v_count = p_checkbox_obj.length;
		v_parent_id = 0;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_list_item_id.search(p_checkbox_obj[i].value)>=0){
					p_checkbox_obj[i].checked="checked";
					v_parent_id = p_checkbox_obj[i].parent_id;
				}
			}
		}else{
			if (p_list_item_id.search(p_checkbox_obj.value)>=0){
				p_checkbox_obj.checked="checked";
				v_parent_id = p_checkbox_obj.parent_id;
			}
		}
		select_parent_radio(p_radio_obj,p_checkbox_obj,v_parent_id);
	}catch(e){;}
}
//**********************************************************************************************************************
// Ham is_node_empty()
// Ham cho phep xac dinh node duoc click (chon) co phai la node con cuoi cung hay khong
// Ham nay chi dung cho cay duoc tao ra tu ham _built_XML_tree()
// Input:
// Output: tra lai gia tri kieu logic
// 	1. True: neu no la node con cuoi cung (Khong chua cac node con khac)
// 	2. False: neus nos khong phai la node con cuoi cung (Chua cac node con khacs
//**********************************************************************************************************************
function is_node_empty(node) {
	if (node.type=='1') {return true;}
	var v_count;
	v_count = document.all.str_obj.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (document.all.str_obj[i].parent_id==node.id){
				return false;
			}
		}
	}else{
		return true;
	}
}
//**********************************************************************************************************************
// Ham show_modal_dialog_treeview_onclick()
// Ham thuc hien chuc nang
// 	hien thi modal dialog danh sach cac doi tuong hinh cay
// 		input:
// 			p_text_name_obj:Doi tuong chua text name cua doi tuong
// 			p_text_code_obj :Doi tuong chua text code cua doi tuong
// 			p_hdn_obj: Doi tuong chua id tra ve cua doi tuong
// 			p_hdn_owner_id: "Gia tri id" cua doi tuong dua vao de loc khong sinh ra chinh no va cac con cua no trong cay thu muc
// 							Neu gia tri nay <0 thi co nghia la cho hien thi tat ca
// 			p_height: chieu cao cua modal dialog (gia tri truyenf vao co ca chu "pt")
// 			p_width: chieu rong cua modal dialog (gia tri truyenf vao co ca chu "pt")
// 			p_allow_editing_in_modal_dialog: Neu co truyen tham so nay thi trong cua so modaldialog hien thi danh sach DOI TUONG se khong co cac nut "Them", "Xoa"
// 			p_allow_select: Neu co truyen tham so nay thi trong cua so hien thi danh sach DOI TUONG se co nut "Chon"
// 		Output: Tra lai cac gia tri tuong ung cua doi tuong duoc click
// 				1. ID cua doi tuong
// 				2. Ma viet tat cua doi tuong
// 				3. Ten doi tuong
//**********************************************************************************************************************
function show_modal_dialog_treeview_onclick(p_goto_url,p_fuseaction, p_text_name_obj, p_text_code_obj, p_hdn_obj, p_hdn_owner_id,p_height,p_width,p_allow_editing_in_modal_dialog,p_allow_select){
	if (!p_height) p_height = "280pt";
	if (!p_width) p_width = "450pt";
	if (!p_allow_editing_in_modal_dialog) v_allow_editing_in_modal_dialog = 0; else v_allow_editing_in_modal_dialog = p_allow_editing_in_modal_dialog;
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + p_goto_url + "&hdn_item_id="+p_hdn_owner_id + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1"
	if (v_allow_editing_in_modal_dialog==1)
		v_url = v_url + "&allow_editing_in_modal_dialog=1";

	v_url = v_url+ "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth="+p_width+";dialogHeight="+p_height+";dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	arr_value = sRtn.split(_LIST_DELIMITOR);
	p_hdn_obj.value = arr_value[0];
	p_text_code_obj.value = arr_value[1];
	p_text_name_obj.value = arr_value[2];
}
//**********************************************************************************************************************
// Ham show_modal_dialog_onclick()
// Ham thuc hien chuc nang
// 	Ham nay duoc goi khi NSD muon hien thi cua so modaldialog de thuc hien chuc nang quan tri mot thong tin danh muc dang BANG (khong phai dang CAY)
// 	-p_goto_url: dia chi cua file index.php tuong ung voi dach muc can quan tri
//  -p_fuseaction: ten fuseaction ngam dinh
//  -p_select_obj: ten bien selectbox chua TEN danh muc
//  -p_text_obj: ten bien textbox chua CODE cua doi tuong
//  -p_hdn_obj: ten bien hidden chua ID cua doi tuong
//**********************************************************************************************************************
function show_modal_dialog_onclick(p_goto_url,p_fuseaction, p_select_obj, p_text_obj, p_hdn_obj)
{
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + p_goto_url + "&hdn_item_id=0" + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1" + "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=420pt;dialogHeight=370pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	arr_value = sRtn.split(_LIST_DELIMITOR);
	select_obj_length = p_select_obj.length;
	p_select_obj.options[select_obj_length] = new Option(arr_value[2]);
	p_select_obj.options[select_obj_length].id = arr_value[0];
	p_select_obj.options[select_obj_length].value = arr_value[1];
	p_select_obj.options[select_obj_length].name = arr_value[2];
	p_select_obj.options[select_obj_length].selected = true;
	p_text_obj.value = arr_value[1];
	p_hdn_obj.value = arr_value[0];
}

function show_modal_dialog_change_personal_info(p_goto_url,p_staff_id)
{
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + p_goto_url + "&fuseaction=DISPLAY_DETAIL_USER&hdn_item_id=" + p_staff_id + "&modal_dialog_mode=1" + "&" + randomizeNumber();
	//alert(v_url);
	sRtn = showModalDialog(v_url,"","dialogWidth=420pt;dialogHeight=300pt;dialogTop=80pt;status=no;scroll=no;");
}
//**********************************************************************************************************************
// function replace(string,text,by)
// Thay the ky tu trong mot chuoi
//**********************************************************************************************************************
function replace(string,text,by) {
    var strLength = string.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)) return string;

    var i = string.indexOf(text);
    if ((!i) && (text != string.substring(0,txtLength))) return string;
    if (i == -1) return string;

    var newstr = string.substring(0,i) + by;

    if (i+txtLength < strLength)
        newstr += replace(string.substring(i+txtLength,strLength),text,by);

    return newstr;
}

//Ajax
function $(id) { return document.getElementById(id); }
function createXMLHttpRequestObject(){
	var xmlHttp;
	if(window.ActiveXObject){
		try {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {
			xmlHttp = false;
		}
	} else {
		try {
			xmlHttp = new XMLHttpRequest();
		} catch (e){
			xmlHttp = false;
		}
	}
	if (!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}
function trim(TRIM_VALUE){
	if(TRIM_VALUE.length < 1){
		return"";
	}
	TRIM_VALUE = rtrim(TRIM_VALUE);
	TRIM_VALUE = ltrim(TRIM_VALUE);
	if(TRIM_VALUE==""){
		return "";
	}
	else{
		return TRIM_VALUE;
	}
} //End Function

function ltrim(VALUE){
	var w_space = String.fromCharCode(32);
	var v_length = VALUE.length;
	var strTemp = "";
	if(v_length < 0){
		return"";
	}
	var iTemp = v_length -1;

	while(iTemp > -1){
		if(VALUE.charAt(iTemp) == w_space){
		}
		else{
			strTemp = VALUE.substring(0,iTemp +1);
			break;
		}
		iTemp = iTemp-1;

	} //End While
	return strTemp;

} //End Function

function rtrim(VALUE){
	var w_space = String.fromCharCode(32);
	if(v_length < 1){
		return"";
	}
	var v_length = VALUE.length;
	var strTemp = "";
	var iTemp = 0;
	while(iTemp < v_length){
		if(VALUE.charAt(iTemp) == w_space){
		}
		else{
			strTemp = VALUE.substring(iTemp,v_length);
			break;
		}
		iTemp = iTemp + 1;
	} //End While
	return strTemp;
} //End Function
///////////////////////////////////////////////////////////////
function _select_all_multiple_checkbox(p_chk_obj,obj,rdo_id,option){	
	try{
		//current_chk_obj = p_chk_obj[0].getAttribute("xml_tag_in_db_name");
		for(i=0;i<p_chk_obj.length;i++){
			if(rdo_id.checked && option == 0){
				current_chk_obj = p_chk_obj[i].getAttribute("xml_tag_in_db_name");					
				if (current_chk_obj == obj){					
					p_chk_obj[i].checked = true;				
				}
			}else{
				current_chk_obj = p_chk_obj[i].getAttribute("xml_tag_in_db_name");					
				if (current_chk_obj == obj){					
					p_chk_obj[i].checked = false;				
				}
			}
		}
	}catch(e){;}
}
///////////////////////////////////////////////////////////////
/*
Nguoi tao: Thainv	
Ngay tao: 06/10/2009
Y nghia: Ham thuc hien action mot url: p_url
		 chay chuan tren IE va Firefox	
Tham so:
	+ p_url : Gia tri URL can thuc hien
*/
function actionUrl(p_url){	
	document.getElementsByTagName('form')[0].action = p_url;
	document.getElementsByTagName('form')[0].submit(); 
}
/*
	Creater: HUNGVM
	Date: 03/02/2009
	Idea: Ham nay thuc thi khi NSD chon vao mot trang tren SelectBox (Danh sach trang tren mh danh sach)
	Parameters:		
		+ sel_obj : Gia tri trang hien thoi
		+ pAction : Action can thuc hien (Index)
*/
function page_onchange(sel_obj, pAction){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'), document.getElementById('hdn_filter_xml_value_list'), true);		
	document.getElementById('hdn_current_page').value = sel_obj.value;		
	actionUrl(pAction);
}
//Ham thay thuc thi khi NSD muon thay doi so dong quy dinh tren mot trang
function page_record_number_onchange(sel_obj, pAction){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'), document.getElementById('hdn_filter_xml_value_list'), true);
	document.getElementById('hdn_current_page').value = 1;
	document.getElementById('hdn_record_number_page').value = sel_obj.value;
	actionUrl(pAction);
}
// Menu Control
function at_display(x){
  var win = window.open();
  for (var i in x) win.document.write(i+' = '+x[i]+'<br>');
}
// ----- Show Aux -----
function at_show_aux(parent, child){
  var p = document.getElementById(parent);
  var c = document.getElementById(child );

  var top  = (c["at_position"] == "y") ? p.offsetHeight : 0;
  var left = (c["at_position"] == "x") ? p.offsetWidth +2 : 0;

  for (; p; p = p.offsetParent){
    top  += p.offsetTop;
    left += p.offsetLeft;
  }
  c.style.position   = "absolute";
  c.style.top        = top +'px';
  c.style.left       = left+'px';
  c.style.visibility = "visible";
}
// ----- Show -----
function at_show(){
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);
  at_show_aux(p.id, c.id);
  clearTimeout(c["at_timeout"]);
}
// ----- Hide -----
function at_hide(){
  var c = document.getElementById(this["at_child"]);
  c["at_timeout"] = setTimeout("document.getElementById('"+c.id+"').style.visibility = 'hidden'", 10);
}
// ----- Click -----
function at_click(){
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);

  if (c.style.visibility != "visible")
       at_show_aux(p.id, c.id);
  else c.style.visibility = "hidden";

  return false;
}
// ----- Attach -----

// PARAMETERS:
// parent   - id of visible html element
// child    - id of invisible html element that will be dropdowned
// showtype - "click" = you should click the parent to show/hide the child
//            "hover" = you should place the mouse over the parent to show
//                      the child
// position - "x" = the child is displayed to the right of the parent
//            "y" = the child is displayed below the parent
// cursor   - Omit to use default cursor or check any CSS manual for possible
//            values of this field
function at_attach(parent, child, showtype, position, cursor){
  var p = document.getElementById(parent);
  var c = document.getElementById(child);

  p["at_parent"]     = p.id;
  c["at_parent"]     = p.id;
  p["at_child"]      = c.id;
  c["at_child"]      = c.id;
  p["at_position"]   = position;
  c["at_position"]   = position;

  c.style.position   = "absolute";
  c.style.visibility = "hidden";

  if (cursor != undefined) p.style.cursor = cursor;

  switch (showtype){
    case "click":
      p.onclick     = at_click;
      p.onmouseout  = at_hide;
      c.onmouseover = at_show;
      c.onmouseout  = at_hide;
      break;
    case "hover":
      p.onmouseover = at_show;
      p.onmouseout  = at_hide;
      c.onmouseover = at_show;
      c.onmouseout  = at_hide;
      break;
  }
}
function list_have_date(the_list,the_element, the_separator){	
	if (the_list=="") return 0;
	arr_value = the_list.split(the_separator);
	for(i=0;i<arr_value.length;i++){
		if (date_compare(arr_value[i],the_element)==0){
			return 1;
		}
	}
	return 0;
}
/*
	Creater: Toanhv
	date: 24/10/2009
*/
function DatePrompt(v) {
	var v1 = v.value;
	var o1 = '';
	var monthdays=0;
	var r1=true;
	
	var vietDateExpr = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]/[0-9][0-9][0-9][0-9]$');
	var dateexpr1 = new RegExp('^[0-3][0-9][0-1][0-9]$');
	var dateexpr2 = new RegExp('^[0-3][0-9][0-1][0-9][0-9][0-9]$');
	var dateexpr3 = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]/[0-9][0-9]$');
	var dateexpr4 = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]$');
	
	if (v1.match(vietDateExpr))
		o1=v1;
	else {
		if (v1.match(dateexpr1)) {
			var d = new Date();
			o1= v1.substring(0,2) + '/' + v1.substring(2, 4) + '/' + d.getFullYear();
		}
		else {
			if (v1.match(dateexpr2))
				o1= v1.substring(0,2) + '/' + v1.substring(2, 4) + '/20' + v1.substring(4,6);
			else {
				if (v1.match(dateexpr3)) {
					var i1= v1.lastIndexOf('/');
					o1 = v1.substring(0, i1) + '/20' + v1.substring(i1+1, v1.length);
				}
				else {
					if (v1.match(dateexpr4)) {
						var d = new Date();
						o1= v1 + '/' + d.getFullYear();
					}
				}
			}
		}
	}
	if (o1=='' && v1!='') {
		alert('Ngay thang nam khong cung dinh dang!');
		v.focus();
		r1 = false;
	}	
	// Now check date validity
	strDate1 = o1.split("/");
	if(strDate1[0].substring(0,1)=='0'){
		strDate1[0] = strDate1[0].substring(1,1);
	}	
	if(strDate1[1].substring(0,1)=='0'){
		strDate1[1] = strDate1[1].substring(1,1);
	}	
	if (parseInt(strDate1[1])<1 || parseInt(strDate1[1])>12) {
		alert('Thang: 1-12');
		r1 = false;
	}	
	switch(parseInt(strDate1[1])) {
		case 2: 
			if (parseInt(strDate1[2]) % 4 == 0) 
				monthdays=29; 
			else 
				monthdays=28;
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			monthdays=30;
			break;
		default:
			monthdays=31;
	}	
	if (parseInt(strDate1[0])<1 || parseInt(strDate1[0])>monthdays) {
		alert('Ngay: 1-' + monthdays);
		r1 = false;
	}	
	if (r1)
		return o1;
	else
		return v1;
}

function searchFull(objName1, objName2, objView){
	objView.style.display = "block";
	objName1.style.display = "none";
	objName2.style.display = "block";
}
function turnOff(objName1, objName2, objView){
	objView.style.display = "none";
	objName1.style.display = "block";
	objName2.style.display = "none";
}
function searchForText(event, ObjSearch){
	if(event.keyCode == 13){
		document.getElementById('hdn_current_page').value=1;
		ObjSearch.setAttribute("onclick", actionUrl(''));
	}
}
/*
	Creater: phongtd
	Date: 13/05/2010
	Idea: Ham nay thuc thi khi NSD chon vao mot trang tren danh sach cac trang dang hien thi 
	Parameters:	pCurrentPage : Gia tri trang hien thoi
*/
function break_page(pCurrentPage,pUrl){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'), document.getElementById('hdn_filter_xml_value_list'), true);		
	document.getElementById('hdn_current_page').value = pCurrentPage;	
	//document.getElementsByTagName('form')[0].submit(); 
	actionUrl(pUrl);
}
/*
	Creater: phongtd
	Date: 14/05/2010
	Idea: Ham nay thuc thi khi NSD muon "Previous" hoac "Next" trang
	Parameters:	psPreviousNextPage : Gia tri la "Previous" hoac "Next" 
*/

function previous_next_page (psPreviousNextPage,pUrl){
	if(psPreviousNextPage == "Previous"){
		_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'), document.getElementById('hdn_filter_xml_value_list'), true);		
		document.getElementById('hdn_current_page').value = parseInt(document.getElementById('hdn_current_page').value) - 1;	
		//document.getElementsByTagName('form')[0].submit(); 
		actionUrl(pUrl);	
	}
	if(psPreviousNextPage == "Next"){
		_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'), document.getElementById('hdn_filter_xml_value_list'), true);
		document.getElementById('hdn_current_page').value = parseInt(document.getElementById('hdn_current_page').value)  + 1;	
		//document.getElementsByTagName('form')[0].submit(); 
		actionUrl(pUrl);	
	}
}
//----------------
//set hidden man hinh danh sach van ban den
function set_hidden(obj,chk_obj,hdn_obj,value){
	hdn_obj.value ="";
	for(i = 0; i< chk_obj.length; i++){
		if(chk_obj[i].value == value && chk_obj[i].disabled == false){
			chk_obj[i].checked = true;
			hdn_obj.value = value;
			rowid = "#" + obj.id;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
		}else{
			chk_obj[i].checked = false;
		}		
	}
}
//set hidden man hinh danh sach tim kiem van ban den
function set_hidden_search(obj,hdn_obj,value){
	//hdn_obj.value ="";
	hdn_obj.value = value;
	$('td').parent().removeClass('selected');
	$(obj).parent().addClass('selected');	
}

/**
 * http://www.openjs.com/scripts/events/keyboard_shortcuts/
 * Version : 2.01.B
 * By Binny V A
 * License : BSD
 */
shortcut = {
	'all_shortcuts':{},//All the shortcuts are stored in this array
	'add': function(shortcut_combination,callback,opt) {
		//Provide a set of default options
		var default_options = {
			'type':'keydown',
			'propagate':false,
			'disable_in_input':false,
			'target':document,
			'keycode':false
		}
		if(!opt) opt = default_options;
		else {
			for(var dfo in default_options) {
				if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
			}
		}

		var ele = opt.target;
		if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
		var ths = this;
		shortcut_combination = shortcut_combination.toLowerCase();

		//The function to be called at keypress
		var func = function(e) {
			e = e || window.event;
			
			if(opt['disable_in_input']) { //Don't enable shortcut keys in Input, Textarea fields
				var element;
				if(e.target) element=e.target;
				else if(e.srcElement) element=e.srcElement;
				if(element.nodeType==3) element=element.parentNode;

				if(element.tagName == 'INPUT' || element.tagName == 'TEXTAREA') return;
			}
	
			//Find Which key is pressed
			if (e.keyCode) code = e.keyCode;
			else if (e.which) code = e.which;
			var character = String.fromCharCode(code).toLowerCase();
			
			if(code == 188) character=","; //If the user presses , when the type is onkeydown
			if(code == 190) character="."; //If the user presses , when the type is onkeydown

			var keys = shortcut_combination.split("+");
			//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
			var kp = 0;
			
			//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
			var shift_nums = {
				"`":"~",
				"1":"!",
				"2":"@",
				"3":"#",
				"4":"$",
				"5":"%",
				"6":"^",
				"7":"&",
				"8":"*",
				"9":"(",
				"0":")",
				"-":"_",
				"=":"+",
				";":":",
				"'":"\"",
				",":"<",
				".":">",
				"/":"?",
				"\\":"|"
			}
			//Special Keys - and their codes
			var special_keys = {
				'esc':27,
				'escape':27,
				'tab':9,
				'space':32,
				'return':13,
				'enter':13,
				'backspace':8,
	
				'scrolllock':145,
				'scroll_lock':145,
				'scroll':145,
				'capslock':20,
				'caps_lock':20,
				'caps':20,
				'numlock':144,
				'num_lock':144,
				'num':144,
				
				'pause':19,
				'break':19,
				
				'insert':45,
				'home':36,
				'delete':46,
				'end':35,
				
				'pageup':33,
				'page_up':33,
				'pu':33,
	
				'pagedown':34,
				'page_down':34,
				'pd':34,
	
				'left':37,
				'up':38,
				'right':39,
				'down':40,
	
				'f1':112,
				'f2':113,
				'f3':114,
				'f4':115,
				'f5':116,
				'f6':117,
				'f7':118,
				'f8':119,
				'f9':120,
				'f10':121,
				'f11':122,
				'f12':123
			}
	
			var modifiers = { 
				shift: { wanted:false, pressed:false},
				ctrl : { wanted:false, pressed:false},
				alt  : { wanted:false, pressed:false},
				meta : { wanted:false, pressed:false}	//Meta is Mac specific
			};
                        
			if(e.ctrlKey)	modifiers.ctrl.pressed = true;
			if(e.shiftKey)	modifiers.shift.pressed = true;
			if(e.altKey)	modifiers.alt.pressed = true;
			if(e.metaKey)   modifiers.meta.pressed = true;
                        
			for(var i=0; k=keys[i],i<keys.length; i++) {
				//Modifiers
				if(k == 'ctrl' || k == 'control') {
					kp++;
					modifiers.ctrl.wanted = true;

				} else if(k == 'shift') {
					kp++;
					modifiers.shift.wanted = true;

				} else if(k == 'alt') {
					kp++;
					modifiers.alt.wanted = true;
				} else if(k == 'meta') {
					kp++;
					modifiers.meta.wanted = true;
				} else if(k.length > 1) { //If it is a special key
					if(special_keys[k] == code) kp++;
					
				} else if(opt['keycode']) {
					if(opt['keycode'] == code) kp++;

				} else { //The special keys did not match
					if(character == k) kp++;
					else {
						if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
							character = shift_nums[character]; 
							if(character == k) kp++;
						}
					}
				}
			}
			
			if(kp == keys.length && 
						modifiers.ctrl.pressed == modifiers.ctrl.wanted &&
						modifiers.shift.pressed == modifiers.shift.wanted &&
						modifiers.alt.pressed == modifiers.alt.wanted &&
						modifiers.meta.pressed == modifiers.meta.wanted) {
				callback(e);
	
				if(!opt['propagate']) { //Stop the event
					//e.cancelBubble is supported by IE - this will kill the bubbling process.
					e.cancelBubble = true;
					e.returnValue = false;
	
					//e.stopPropagation works in Firefox.
					if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
					}
					return false;
				}
			}
		}
		this.all_shortcuts[shortcut_combination] = {
			'callback':func, 
			'target':ele, 
			'event': opt['type']
		};
		//Attach the function with the event
		if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
		else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
		else ele['on'+opt['type']] = func;
	},

	//Remove the shortcut - just specify the shortcut and I will remove the binding
	'remove':function(shortcut_combination) {
		shortcut_combination = shortcut_combination.toLowerCase();
		var binding = this.all_shortcuts[shortcut_combination];
		delete(this.all_shortcuts[shortcut_combination])
		if(!binding) return;
		var type = binding['event'];
		var ele = binding['target'];
		var callback = binding['callback'];

		if(ele.detachEvent) ele.detachEvent('on'+type, callback);
		else if(ele.removeEventListener) ele.removeEventListener(type, callback, false);
		else ele['on'+type] = false;
	}
}
//Ham checkbox all 
function checkbox_all_item_id(p_chk_obj){
	//remove class cua tat ca cac tr trong table
	$('tr').removeClass('selected');
	try{
		v_count = p_chk_obj.length;
		if(v_count){
			if(document.forms[0].chk_all_item_id.checked == true){
				for(i=0;i<=v_count;i++){
					if(p_chk_obj[i].disabled == false && (p_chk_obj[i].value != 0) ){
						p_chk_obj[i].checked = 'checked';
						$(p_chk_obj[i]).parent().parent().addClass('selected');
					}
				}
			}else{
				for(i=0;i<p_chk_obj.length;i++){
					p_chk_obj[i].checked = '';
				}		
			}
		}else{
			if(document.forms[0].chk_all_item_id.checked == true){
				if(p_chk_obj.disabled == false){
					p_chk_obj.checked = 'checked';
					$(p_chk_obj).parent().parent().addClass('selected');
				}
			}else{
				p_chk_obj.checked = '';
			}
		}
		
	}catch(e){;}
}
//Ham thuc hien to mau row khi nguoi su dung bam vao nut checkbox tren row
//obj	Checkbox duoc chon
function selectrow(obj){
	if(obj.checked){
		$(obj).parent().parent().addClass('selected');
		$('td > p:.red').addClass('blue');
	}
	else
		$(obj).parent().parent().removeClass('selected');
}
//	Ham item_onclick duoc goi khi NSD click vao 1 dong trong danh sach
//  p_item_value: chua ID cua doi tuong can hieu chinh
function item_onclick(p_item_value,p_url){		
	row_onclick(document.getElementById('hdn_object_id'), p_item_value, p_url);
}
/*
Creater ; HUNGVM
Date : 16/06/2010
Idea : Tao ham khoi tao bien luu trong Cookie
Paras :
	+ name  : Ten Cookie
	+ value : Gia tri luu trong Cookie
	+ expires : Thoi gian expires
	+ path : Duong dan luu Cookie
	+ domain : Ten mien
	+ secure : Chinh sach
*/
function Set_Cookie( name, value, expires, path, domain, secure ){
	// set time, it's in milliseconds
	var today = new Date();
	today.setTime( today.getTime() );
	
	/*
	if the expires variable is set, make the correct
	expires time, the current script below will set
	it for x number of days, to make it for hours,
	delete * 24, for minutes, delete * 60 * 24
	*/
	if ( expires ){
		expires = expires * 1000 * 60 * 60 * 24;
	}
	
	var expires_date = new Date( today.getTime() + (expires) );
	//
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}

/*
Creater ; HUNGVM
Date : 16/06/2010
Idea : Tao ham khoi tao bien luu trong Cookie
Paras :
	+ check_name  : Ten Cookie can lay gia tri
*/

// this fixes an issue with the old method, ambiguous values
// with this test document.cookie.indexOf( name + "=" );
function Get_Cookie( check_name ) {
	
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f

	for ( i = 0; i < a_all_cookies.length; i++ ){
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );

		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

		// if the extracted name matches passed check_name
		if ( cookie_name == check_name ){
			b_cookie_found = true;
			// we need to handle case where cookie has no value but exists (no = sign, that is):
			if ( a_temp_cookie.length > 1 )	{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	
	//
	if ( !b_cookie_found ){
		return null;
	}
}

/*
Creater ; HUNGVM
Date : 16/06/2010
Idea : Tao ham xu ly An/Hien menu trai cua he thong
Paras :
	+ sName  : Ten Cookie
	+ iValue : Gia tri gan Cookie
*/
function showHideMenuLeft(sName){
	//Lay gia tri trong Cookie
	sValueInCookie = Get_Cookie(sName);
	//Dia chi anh gan trong Cookie
	sImagePathInCookie = Get_Cookie("ImageUrlPath");
	//alert(sValueInCookie);
	//Chua ton tai thi khoi tao bien trong Cookie
	if (sValueInCookie == ""){
		//An menu
		Set_Cookie( sName, 1, '', '/', '', '' );
		Set_Cookie( ImageUrlPath, _IMAGE_URL_PATH + "close_left_menu.gif", '', '/', '', '' );
	}else{//Set value in Cookie
		if (sValueInCookie == 1){
			iSetValue = 0;
			sImagePath = _IMAGE_URL_PATH + "open_left_menu.gif";
		}else{
			iSetValue = 1;
			sImagePath = _IMAGE_URL_PATH + "close_left_menu.gif";
		}
		//Dat gia tri vao Cookie
		Set_Cookie( sName, iSetValue, '', '/', '', '' );
		Set_Cookie( "ImageUrlPath", sImagePath, '', '/', '', '' );
		//
		document.getElementById("pictureShowHileMenu").src = sImagePath;
		//
		if (document.getElementById("Iddiv_leftmenu").style.display == "none"){
			document.getElementById("Iddiv_leftmenu").style.display = "block";
		}else{
			document.getElementById("Iddiv_leftmenu").style.display = "none";
		}
	}
}

//--------------------------------------------------- 
// Check date 
// xu ly ho tro nhap ngay thang
function DateOnkeyup(objName,str,e){
			var keycode;
			var dateMonth;
			//keycode=window.event.keyCode;
			keycode = (window.event)?event.keyCode:e.which; 
			if (isEdit(keycode,str)){
				//document.getElementById(objName).value=editdate(trimSupport(str));
				if(str.value > 3 && str.value.length == 1){
					document.getElementById(objName).value = '0' + document.getElementById(objName).value ;
				}
				if(str.value.length == 2 && str.value.split("/").length >1){
					document.getElementById(objName).value = '0' +document.getElementById(objName).value ;
				}
				if(str.value.length == 2 && str.value.split("-").length >1){
					alert("ok");
					document.getElementById(objName).value = '0' +str.value.substring(0,1) +'/';
				}
				if(str.value <= 31 && str.value.length == 2){
					document.getElementById(objName).value = document.getElementById(objName).value + '/';
				}
				if(str.value > 31 ){
					alert("Nhap sai ngay");
					document.getElementById(objName).value ='';
				}
				if(str.value.length == 4){
					dateMonth = str.value.split("/");
					if(dateMonth[1] >1 ){
						if((dateMonth[0] == 31 &&(dateMonth[1] == 2 || dateMonth[1] == 4 || dateMonth[1] == 6 || dateMonth[1] == 9)) ||(dateMonth[0] == 30 && dateMonth[1] == 2)){
							alert("Sai thang");
							document.getElementById(objName).value = str.value.substring(0,str.value.length -1);
						}else{
						document.getElementById(objName).value = dateMonth[0]	+ '/' + '0'+ dateMonth[1] + '/';
						}
					}
				}
				if(str.value.length == 5){
					dateMonth = str.value.split("/");
					if(dateMonth[1] <= 12 && !((dateMonth[1] == 2 || dateMonth[1] == 4 || dateMonth[1] == 6 || dateMonth[1] == 9 || dateMonth[1] == 11) &&  dateMonth[0] == 31 ) && !(dateMonth[0] == 30 && dateMonth[1] == 2)){
						document.getElementById(objName).value = document.getElementById(objName).value + '/';
					
					}
					else{
						alert("Sai thang");
						document.getElementById(objName).value = str.value.substring(0,str.value.length -2);
					}
				}
				if(str.value.length >= 10){
					dateMonth = str.value.split("/");
					if(dateMonth[0] == 28 && dateMonth[1] == 2 && dateMonth[2] % 4 == 0){
						alert("Sai nam");
						document.getElementById(objName).value = str.value.substring(0,str.value.length -4);
					}
					if(dateMonth[0] == 29 && dateMonth[1] == 2 && dateMonth[2] % 4 != 0){
						alert("Sai nam");
						document.getElementById(objName).value = str.value.substring(0,str.value.length -4);
					}
				}
				if(str.value.length > 10){
					alert("Sai nam");
					dateMonth = str.value.split("/");
					document.getElementById(objName).value = dateMonth[0]	+ '/' +  dateMonth[1] + '/';
				}
			}
		}
function isEdit(keycode,str)
{
	if ((keycode>=48 && keycode <=57 || keycode>=96) && keycode <=105 || keycode==111 || keycode==191){
		return true;
	}else{
		//alert("Chi su dung so va dau '/ ' de phan cach")
		str.value = '';
		return true;
	}	
}
function btn_register_onclick(p_checkbox_obj, p_hidden_obj, p_url){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon dang ky phat hanh nhung doi tuong da chon?'))
		{
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			actionUrl(p_url);
		}
	}
}
//Lay ngay tiep theo cua ngay trong elTerget.value
function Next_Date(p_date) {
	if(isdate(p_date)){
		var theDate,strSeparator,arr,day,month,year;
		strSeparator = "";
		theDate = p_date;
		if (theDate.indexOf("/")!=-1) strSeparator = "/";
		if (theDate.indexOf("-")!=-1) strSeparator = "-";
		if (theDate.indexOf(".")!=-1) strSeparator = ".";
		if (strSeparator != "") {
			arr=theDate.split(strSeparator);
			day=new Number(arr[0])+1;
			month=new Number(arr[1]);
			year=new Number(arr[2]);
			if(day > 28){
				if (((month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12) && (day > 31))
				|| ((month == 4 || month == 6 || month == 9 || month == 11) && (day > 30))||(month == 2 && year % 4 !=0)||(month == 2 && year % 4 ==0 && day > 29)) 
				{
					day = 1;
					month = month+1;
				}
				if (month > 12 ){
					year = year +1;
					month = 1;
				}			
			}
			return day + strSeparator + month + strSeparator + year;
		}
    }
}
//Tinh han xu ly VB
function appointed_date(v_implementation_date,p_limit_date,p_appointed_date){
	if(!isnum(p_limit_date.value)){
		alert('SO NGAY XU LY phai la so nguyen duong!');
		p_limit_date.value ='';
		//p_limit_date.focus();
		return;
	}
	var count = p_limit_date.value;
	//Lay thong tin Nam hien thoi
	var d = new Date();		
	var p_year = d.getFullYear();
	var v_list_day_off_of_year = _LIST_DAY_OFF_OF_YEAR.split(",");
	var v_list_day = _LIST_WORK_DAY_OF_WEEK;
	var v_list_luner_date="";
	var v_increase_and_decrease_day = parseInt(_INCREASE_AND_DECREASE_DAY);
	var v_date,v_temp_date;
	var v_next_date = "";
	//Ngay thuc hien
	var v_input_date = v_implementation_date;	
	if (!isdate(v_input_date)){
		return;
	}else{	
		var arr_input_date = v_input_date.split("/");
		v_input_date = arr_input_date[0]*1 + "/" + arr_input_date[1]*1 + "/" + arr_input_date[2]*1; 
	}	
	for (var i=0;i<v_list_day_off_of_year.length;i++){
		v_date = v_list_day_off_of_year[i].split("/");
		if (v_date[0]=="-"){
			v_list_luner_date = list_append(v_list_luner_date,v_date[1]+"/" + v_date[2] + "/" + p_year,",");
		}else{
			v_temp_date = Solar2Lunar(v_date[1]+ "/" + v_date[2] + "/" + p_year);
			v_list_luner_date = list_append(v_list_luner_date,v_temp_date,",");
		}
	}
	var i=0;
	v_next_date = v_input_date;
	//Tinh tong so ngay tru ngay duoc nghi ra
	while ((i<count-v_increase_and_decrease_day)){ 
		if ((list_have_date(v_list_luner_date,Solar2Lunar(v_next_date),",")!=1)&&(Solar2DayofWeek(v_next_date)!=7)&&(Solar2DayofWeek(v_next_date)!=8)){
			i++;			
			v_next_date = Next_Date(v_next_date);	
		}else{
			v_next_date = Next_Date(v_next_date);				
		}
	}
	//Neu gap ngay thu 7 hoac CN thi bo qua
	while ((list_have_element(v_list_day,Solar2DayofWeek(v_next_date),",")<0)){
		v_next_date = Next_Date(v_next_date);	
	}
	p_appointed_date.value = v_next_date;
}
//Ham kiem tra kieu ngay/thang/nam
function test_date(v_input_date){
	var pattern = "^(?:(?:31(\\/|-|\\.)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$";
	var rx = new RegExp(pattern,"g");
	return rx.test(v_input_date);
}
//Ham mo cua so o giua man hinh
function DialogCenter(pageURL, title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
function checkvalue(){
	try{
		document.getElementById('timkiem').focus();
	}catch(e){;}
	actionUrl('');
}
function _checkedAll(obj, chk_item_id){
	if(obj.checked){
		for(i =0; i < chk_item_id.length; i ++){
			chk_item_id[i].checked = true;
		}
	}else{
		for(i =0; i < chk_item_id.length; i ++){
			chk_item_id[i].checked = false;
		}
	}
}
function row_ondblclick(obj_hdn, obj_id, url){
	obj_hdn.value = obj_id;
	actionUrl(url);
}
function btn_rad_onclick(p_rad_obj,p_hdn_obj){
	p_hdn_obj.value = p_rad_obj;
	try{
		opt_reporttype_id = document.getElementsByName('opt_reporttype_id');
		for(i =0; i < opt_reporttype_id.length; i ++){
			if(opt_reporttype_id[i].value == p_rad_obj){
				opt_reporttype_id[i].checked = true;
			}else{
				opt_reporttype_id[i].checked = false;
			}
		}
	}catch(e){;}
	try{
		if(p_rad_obj == "BC.002"){
			document.getElementById('asset_status').style.display = "none";
			document.getElementById('C_STATUS').value = "";			
			document.getElementById('asset_unitid').style.display = "none";
			document.getElementById('C_UNIT').value = "";
		}
		if(p_rad_obj == "BC.001"){
			document.getElementById('asset_status').style.display = "";
			document.getElementById('asset_unitid').style.display = "none";
			document.getElementById('C_UNIT').value = "";
		}
		if(p_rad_obj == "BC.003"){
			document.getElementById('asset_status').style.display = "none";
			document.getElementById('asset_unitid').style.display = "";
			document.getElementById('C_UNIT').value = "";
		}
	}catch(e){;}
}
/*
	Ham thuc hien click vao nut bao cao
*/
function btn_show_report(p_hdn_obj,p_hdn_tag_obj,p_hdn_value_obj, p_fuseaction){	
	var v_url = '';
	var v_exporttype = document.getElementById('hdn_exporttype').value;		
	var v_report_xml_file = document.getElementById('hdn_xml_file').value;	 
	try{
		_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);	
	}catch(e){;}
	if(verify(document.forms[0])){	
		var tagValue = p_hdn_value_obj.value.replace("/","-");	
		v_url =  p_fuseaction + '/';
		v_url = v_url + 'hdn_exporttype/' + v_exporttype;	
		v_url = v_url + '/hdn_Report_id/' + p_hdn_obj.value;
		v_url = v_url + '/begin_to_date/' + document.getElementById('begin_to_date').value.replace("/","-").replace("/","-");
		v_url = v_url + '/begin_from_date/' + document.getElementById('begin_from_date').value.replace("/","-").replace("/","-");
		try{	
			v_url = v_url + '/C_STATUS/' + document.getElementById('C_STATUS').value;	
		}catch(e){;}
		try{
			v_url = v_url + '/C_UNIT/' + document.getElementById('C_UNIT').value;			
		}catch(e){;}
		try{
			v_url = v_url + '/C_TYPE/' + document.getElementById('C_TYPE').value;			
		}catch(e){;}
		try{
			v_url = v_url + '/C_GROUP/' + document.getElementById('C_GROUP').value;			
		}catch(e){;}
		browerName = const_browerName;
		if(browerName == 'Safari'){
			window.open(v_url,'','height=1px,width=1px,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,modal=yes');
			document.getElementById('downloadFileReport').style.display = "block";	
			document.getElementById('linkDownload').focus();		
		}else{
			sRtn = showModalDialog(v_url,"","dialogWidth=1px;dialogHeight=1px;status=no;scroll=no;dialogCenter=yes");			
		    if (sRtn!=""){
				window.open(sRtn);
		    }
		}
	}
}
function iTemOnclick(pObjCheckBox, pObjHidden, pAction){	
	if (!checkbox_value_to_list(pObjCheckBox,"!~~!")){
		alert("Chua co doi tuong nao duoc chon!");
	}
	else{
		pObjHidden.value = checkbox_value_to_list(pObjCheckBox,"!~~!"); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
		actionUrl(pAction);
	}
}

function checkElementsGroup(ObjChkItem, pGroupCode){
	var v_count;		
	var iCount = ObjChkItem.length;	
	if (iCount){
		for(i=0; i<iCount; i++){	
			if (ObjChkItem[i].getAttribute('parent') == pGroupCode.value){				
				if (pGroupCode.checked){
					ObjChkItem[i].checked = true;									
				}else{					
					ObjChkItem[i].checked = false;					
				}		
			}
	 	}
	}
}
function _action(hdn_id, item, url){
	k =0;
	for(i =0; i < item.length; i ++){
		if(item[i].checked){
			k ++;
			hdn_id.value = item[i].value;
		}
	}
	if(k > 0){
		alert('Ban chi duoc chon mot doi tuong de duyet!');
		return;
	}else{
		alert(hdn_id.value);
		actionUrl(url);
	}
}




