//************************************************************************
// CHU Y: Khong duoc thay doi vi tri cua bien nay (LUON LUON dat o dong dau tien)

if (typeof(_ALLOW_EDITING_IN_MODAL_DIALOG)=="undefined") 
	_ALLOW_EDITING_IN_MODAL_DIALOG = 0; // Ngam dinh khong cho phep THEM/SUA/XOA cac thong tin danh muc khi chay o mot cua so MODAL DIALOG

if (typeof(_DECIMAL_DELIMITOR)=="undefined") 	
	_DECIMAL_DELIMITOR = ","; // Ngam dinh su dung day PHAY (,) de phan cach hang nghin

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
	_save_checkbox_value_to_textbox_obj(document.all.chk_multiple_textbox,the_separator);
	try{
		if (!p_textbox_obj.length){
			list_value=p_textbox_obj.value;
			eval('document.forms[0].'+p_textbox_obj.xml_tag_in_db_name+'.value=document.forms[0].'+p_textbox_obj.xml_tag_in_db_name+'.value+"'+list_value+'"');
		}else{	
			current_chk_obj = p_textbox_obj[0].xml_tag_in_db_name;
			for(i=0;i<p_textbox_obj.length;i++){
				next_chk_obj = p_textbox_obj[i].xml_tag_in_db_name;	
				if (current_chk_obj != next_chk_obj){    //Neu het danh sach thi gan vao gia tri cua danh sach
					eval('document.forms[0].'+current_chk_obj+'.value=document.forms[0].'+current_chk_obj+'.value+"'+list_value+'"');
					list_value = the_separator;
				}
				v_value = replace(p_textbox_obj[i].value,the_separator,"");
				if (v_value=="" || v_value==null){
					v_value=" ";
				}
				list_value=list_append(list_value,v_value,the_separator);					
				if (i==p_textbox_obj.length-1){          //Cuoi gan gia tri vao danh sach
					eval('document.forms[0].'+current_chk_obj+'.value=document.forms[0].'+current_chk_obj+'.value+"'+list_value+'"');
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
	_save_checkbox_value_to_textbox_obj(document.all.chk_multiple_checkbox,',');
	_save_checkbox_value_to_textbox_obj(document.forms[0].chk_unit_id,',');
	_save_checkbox_value_to_textbox_obj(document.forms[0].chk_staff_id,',');
	_save_textbox_value_to_textbox_obj(document.all.txt_multiple_textbox,_LIST_DELIMITOR);
	f = p_form_obj
	for (i=0;i<f.length;i++){
		var e=f.elements[i];
		if ((p_only_for_xml_data && e.xml_data=='true') || (!p_only_for_xml_data)){
			if (e.value==""||e.value==null){
				v_value=" ";
			}else{
				v_value=e.value;
			}
			//alert(v_value);
			if (e.xml_tag_in_db &&(e.type!='radio' && e.type!='checkbox')){
				list_tag = list_append(list_tag,e.xml_tag_in_db,_SUB_LIST_DELIMITOR);
				list_value = list_append(list_value,v_value,_SUB_LIST_DELIMITOR);
			}
			if (e.xml_tag_in_db &&(e.type=='radio' || e.type=='checkbox')){
				list_tag = list_append(list_tag,e.xml_tag_in_db,_SUB_LIST_DELIMITOR);
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
	//alert(document.forms[0].hdn_list_code.value);
	//alert(document.forms[0].hdn_list_name.value);
}

// Ham _save_checkbox_value_to_textbox_obj duyet tat ca cac doi tuong multiple-checkbox va luu gia tri cua cac phan tu duoc chon
// vao 1 doi tuong textbox co ten xac dinh boi thuoc tinh "xml_tag_in_db_name"
// p_chk_obj: ten doi tuong multuple-checkbox

function _save_checkbox_value_to_textbox_obj(p_chk_obj,the_separator){
	var ret = "";
	try{
		if (!p_chk_obj.length){
			if (p_chk_obj.checked){
				ret=p_chk_obj.value;
				eval('document.forms[0].'+p_chk_obj.xml_tag_in_db_name+'.value="'+ret+'"');
			}
		}else{	
			current_chk_obj = p_chk_obj[0].xml_tag_in_db_name;
			for(i=0;i<p_chk_obj.length;i++){
				next_chk_obj = p_chk_obj[i].xml_tag_in_db_name;	
				if (current_chk_obj != next_chk_obj){  //Neu het danh sach thi gan vao gia tri cua danh sach
					eval('document.forms[0].'+current_chk_obj+'.value="'+ret+'"');
					ret = "";
				}
				if (p_chk_obj[i].checked){
					ret=list_append(ret,p_chk_obj[i].value,the_separator);					
				}
				if (i==p_chk_obj.length-1){ //Cuoi gan gia tri vao danh sach
					eval('document.forms[0].'+current_chk_obj+'.value="'+ret+'"');
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
function _show_row_selected(rad_id,tr_name){
	//Thay doi selected cua radio button
	eval('document.all.' + rad_id + '(0).checked=false');
	eval('document.all.' + rad_id + '(1).checked=true');
	v_count=eval('document.all.' + tr_name + '.length');
	var v_odd_class="";
	if(v_count){
		for(var i=0;i<v_count;i++){
			if (eval('document.all.' + tr_name + '[i].checked == ""')){
				eval('document.all.' + tr_name + '[i].style.display="none"');
			}else{
				if (v_odd_class == "odd_row"){
					eval('document.all.'+ tr_name + '[i].className="round_row"');
					v_odd_class = "round_row"
				}else{
					eval('document.all.'+ tr_name + '[i].className="odd_row"');
					v_odd_class = "odd_row"
				}
			}
		}
	}else{
		if (eval('document.all.' + tr_name + '.checked == ""')){
			eval('document.all.' + tr_name + '.style.display="none"');
		}else{
			if (v_odd_class == "odd_row"){
				eval('document.all.'+ tr_name + '.className="round_row"');
				v_odd_class = "round_row"
			}else{
				eval('document.all.'+ tr_name + '.className="odd_row"');
				v_odd_class = "odd_row"
			}
		}
	}
}

//**********************************************************************************************************************
// Ham show_row_all de hien thi TAT CA cac dong (khong phan biet da DUOC CHON hay khong)
// rad_id: la ten cua bien radio xac dinh che do HIEN THI TAT CA hay Chi HIEN THI NHUNG DOI TUONG DUOC CHON 
// (vi du neu ta co <input name="rad_indicator_filter" thi rad_id="rad_indicator_filter")
// tr_name: ten cua id tuong uong voi dong chua doi tuong (vi d? neu ta co <tr id="xxxx"> thi tr_name="xxxx")
//**********************************************************************************************************************
function _show_row_all(rad_id,tr_name){
	//Thay doi selected cua radio button
	eval('document.all.' + rad_id + '(0).checked=true');
	eval('document.all.' + rad_id + '(1).checked=false');
	v_count=eval('document.all.' + tr_name + '.length');
	var v_odd_class="";
	if(v_count){
		for(var i=0;i<v_count;i++){
			eval('document.all.' + tr_name + '[i].style.display="block"');
			if (v_odd_class == "odd_row"){
				eval('document.all.'+ tr_name + '[i].className="round_row"');
				v_odd_class = "round_row"
			}else{
				eval('document.all.'+ tr_name + '[i].className="odd_row"');
				v_odd_class = "odd_row"
			}
		}
	}else{
		eval('document.all.' + tr_name + '.style.display="block"');
		if (v_odd_class == "odd_row"){
			eval('document.all.'+ tr_name + '.className="round_row"');
			v_odd_class = "round_row"
		}else{
			eval('document.all.'+ tr_name + '.className="odd_row"');
			v_odd_class = "odd_row"
		}
	}
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

function _change_item_checked(chk_obj,tr_name,rad_id){
	var v_count;
	var i;
	var v_modul_checked=false;
	//Tim row chua checkbox va thay doi gia tri cua tr_name.checked
	v_count = eval('document.all.' + tr_name +'.length');
	i=0;
	if (v_count){
		while (i<v_count){
			if (eval('document.all.' + tr_name + '[i].value == chk_obj.value')){
				if (eval('document.all.' + tr_name + '[i].checked == "checked"')){
					eval('document.all.' + tr_name + '[i].checked = ""');
				}else{
					eval('document.all.' + tr_name + '[i].checked = "checked"');
				}
				//break;
			}
			i++;
		}
	}else{
		if (eval('document.all.' + tr_name + '.value == chk_obj.value')){
			if (eval('document.all.' + tr_name + '.checked == "checked"')){
				eval('document.all.' + tr_name + '.checked = ""');
			}else{
				eval('document.all.' + tr_name + '.checked = "checked"');
			}
		}
	}
	//thay doi gia tri cua checked trong tr_modul va chk_modul_id theo v_modul_checked=True or False
	//Kiem tra che do hien thi de refresh man hinh
	if (eval('document.all.' + rad_id + '(1).checked')){
		_show_row_selected(rad_id,tr_name);
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
// Tham so:
// node: ten object cua doi doi tuong phong ban
function select_unit_checkbox_treeuser(node){
	var checked = 0;
	var v_count = document.all.chk_staff_id.length;
	if(node.checked){
		for(i=0; i < v_count; i++){
			if(checked > 0 && document.all.chk_staff_id[i].parent_id != node.value && document.all.chk_staff_id[i].xml_tag_in_db_name==node.xml_tag_in_db_name){
				return;
			}			
			if(document.all.chk_staff_id[i].parent_id == node.value && document.all.chk_staff_id[i].xml_tag_in_db_name==node.xml_tag_in_db_name){
				document.all.chk_staff_id[i].checked = "checked";
				checked ++;
			}
		}
	}else{
		for(i=0; i < v_count; i++){
			if(checked >0 && document.all.chk_staff_id[i].parent_id != node.value && document.all.chk_staff_id[i].xml_tag_in_db_name==node.xml_tag_in_db_name){
				return;
			}						
			if(document.all.chk_staff_id[i].parent_id == node.value && document.all.chk_staff_id[i].xml_tag_in_db_name==node.xml_tag_in_db_name){
				document.all.chk_staff_id[i].checked = "";
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
	var v_count = document.all.chk_staff_id.length;
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
	// Luu ID cua cac doi tuong bi xoa vao p_hdn_obj
	if (checkbox_value_to_list(p_checkbox_obj,",")!=""){
		if (p_hdn_obj.value!=""){
			p_hdn_obj.value = p_hdn_obj.value + "," + checkbox_value_to_list(p_checkbox_obj,",");
		}else{
			p_hdn_obj.value = checkbox_value_to_list(p_checkbox_obj,",");
		}
	}
	try{
		if (p_row_obj.length){
			for (i=0; i<p_row_obj.length; i++){
				if (p_checkbox_obj[i].checked){
					p_row_obj[i].style.display="none";
					p_checkbox_obj[i].checked=false;
				}
			}
		}
		else{
			if (p_checkbox_obj.checked){
				p_row_obj.style.display="none";
				p_checkbox_obj.checked=false;
			}
		}
	}
	catch(e){;}
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
function row_onclick(p_obj, p_value, p_fuseaction, p_goto_url){
	p_obj.value = p_value;
	if (_MODAL_DIALOG_MODE==1){
		v_url = _DSP_MODAL_DIALOG_URL_PATH;
		v_url = v_url + "?goto_url=" + p_goto_url + "&hdn_item_id=" + p_value + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1" + "&" + randomizeNumber();
		sRtn = showModalDialog(v_url,"","dialogWidth=470pt;dialogHeight=210pt;status=no;scroll=no;");
		document.forms[0].fuseaction.value = "";
		document.forms[0].submit();
	}else{
		//v_current_url  = window.location;
		//arr_url = v_current_url.split(".");
		//v_current_url = arr_url[0];
		//v_current_url = "index.php";
		//v_current_url = v_current_url + "?fuseaction=" + p_fuseaction + "&" + p_obj.name + "=" + p_obj.value;
		//window.location = v_current_url;
		document.forms[0].fuseaction.value = p_fuseaction;
		document.forms[0].submit();
	}	
}
// ham btn_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_fuseaction: ten fuseaction tiep theo
function btn_delete_onclick(p_checkbox_obj, p_hidden_obj, p_fuseaction){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}	
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?'))
		{
			document.forms[0].fuseaction.value = p_fuseaction;
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,",");
			document.forms[0].submit(); 	
		}
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
		document.getElementById('fuseaction').value = p_fuseaction;
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
//  - p_fuseaction: ten fuseaction tiep theo
function btn_back_onclick(p_fuseaction){
	if (_MODAL_DIALOG_MODE==1){
		if (_ALLOW_EDITING_IN_MODAL_DIALOG!=1) 
			window.close(); 
		else{
			document.forms[0].fuseaction.value = p_fuseaction;
			document.forms[0].submit();			
		}
	}else{
		document.forms[0].fuseaction.value = p_fuseaction;
		document.forms[0].submit();
	}	
}

// ham goto_after_update_data() duoc goi khi NSD nhan chuot vao nut "Quay lai"
//  - p_fuseaction: ten fuseaction tiep theo
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
		//if (_MODAL_DIALOG_MODE==1){
		//	if (p_return_value!="")
		//		window.returnValue = p_return_value;				
		//	window.close();
		//}else{	
			document.forms[0].action = "index.php";
			//document.forms[0].fuseaction.value = "";
			document.forms[0].submit();
		//}	
	}
}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////
// Ham nay duoc goi khi NSD click vao ten mot tap tin (de xem hoac download)
function show_file_content(p_filename) {
	window.location = p_filename;
}

// Hien thi cua so ModalDialog de chon ngay
// p_url: duong dan URL toi thu muc chua calendar.html
// p_obj: ten doi tuong form nhan gia tri ngay
function DoCal(p_url, p_obj) {
	var url = p_url + "Calendar.htm";
	var sRtn;
	sRtn = showModalDialog(url,"","dialogWidth=210pt;dialogHeight=180pt;status=no;scroll=no");
    if (sRtn!="")
		p_obj.value = sRtn;
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
function checkbox_value_to_list(the_field,the_separator)
{	
	var ret = "";
	try{
		if (!the_field.length)
		{
			if (the_field.checked)
			{
				ret=the_field.value;
			}
		}
		else
		{	
			for(i=0;i<the_field.length;i++)
			{
				if (the_field[i].checked)
				{
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
function verify(f)
{
	var errors = "";
	var i;
	for (i=0;i<f.length;i++)
	{
		var e=f.elements[i];

		if (e.type =="radio" &&  !e.optional)
			{
				if (ischecked(f,e.name)==false)
				{
					if (e.message!=null) alert(e.message);
					else alert("At least one "+e.name+" must be checked ");
					e.focus();
					return false;
				}
			}	
		// If it is hour object
		if ((e.ishour) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{ 
			if (IsHour(e,':')==false)
			{
				if (e.message!=null) alert(e.message);
				else alert("Hour is invalid");
				e.focus();
				return false;
			}	
		}
		// If it is email object
		if ((e.isemail) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (isemail(e.value)==false)
			{
				if (e.message!=null) alert(e.message);
				else alert("Email is invalid");
				e.focus();
				return false;
			}	
		}

		// if it is Date object
		if ((e.isdate) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (isdate(e.value)==false)
			{
				if (e.message!=null) alert(e.message);
				else alert("Date is invalid");
				e.focus();
				return false;
			}	
		}

		// if it is number object
		if ((e.isnumeric || e.isdouble || (e.min!=null) || (e.max!=null)) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (!_DECIMAL_DELIMITOR) decimal_delimitor = ",";else decimal_delimitor = _DECIMAL_DELIMITOR;
			test_value = replace(e.value,decimal_delimitor,"");
			if (e.isdouble)
				is_number = isdouble(test_value);
			else
				is_number = isnum(test_value);
					
			var v = parseFloat(test_value);
			/**
			if (!is_number 
				|| ((e.min!=null) && (v<e.min))
				|| ((e.max!=null) && (v>e.max)))
			{
				errors += "- The field "+ e.name + " must be a number";
				if (e.min!=null)
					errors += " that is greater than "+e.min;
				if (e.min!=null && e.max!=null)
					errors += " and less than "+e.max;
				else if (e.max !=null)	
					errors += " That is less than "+e.max;
				errors += ".\n";
				if (e.message!=null) alert(e.message);
				else alert(errors);
				e.focus();
				return false;
			}
			/**/	
		}

		// check maxlength
		if ((e.maxlength!=null) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (e.value.length>e.maxlength)
			{
				if (e.message!=null) alert(e.message);
				else alert("The length of "+e.name+" must be less than "+e.maxlength);
				e.focus();
				return false;
			}
		}

		// check multiple selectbox must be not empty
		if (e.checkempty && e.type=="select-multiple" && e.length==0)
		{
			if (e.message!=null) alert(e.message);
			else alert(e.name+" must be not empty");
			e.focus();
			return false;
		}
		
		// Check for text, textarea
		if (((e.type == "password") || (e.type =="text") || (e.type=="textarea") || (e.type =="select-one")) && !e.optional)
		{
			if ((e.value==null) || (e.value=="") || isblank(e.value))
			{
				if (e.message!=null) alert(e.message);
				else alert(e.name+" must be not empty");
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
	function select_all_checkbox(f,objname)
	{
		for (i=0;i<f.length;i++)
		{
		var e=f.elements[i];
		 	if (e.name == objname)
		  	{
				f.elements[i].checked = true;
		  	}	
		}
		return true;	
	}
	
// deselect all elements named objname in the form	
	function deselect_all(f,objname)
	{
		for (i=0;i<f.length;i++)
		{
		var e=f.elements[i];
			if (e.name == objname)
			{
				f.elements[i].checked = false;
			}	
		}
		return true;	
	}

//	Function set_selected set the i option to be checked if its value equals p_value
	function set_selected(p_obj,p_value)
	{
		for (i=0;i<p_obj.options.length;i++)
		{
			if (p_obj.options[i].value==p_value)
			{
				p_obj.options[i].selected = true;
				break;
			}	
		}
	}
// function isselected(p_obj) returns true if p_obj has been selected
	function isselected(p_obj)
	{
		for (i=0;i<p_obj.options.length;i++)
		{
			if (p_obj.options[i].selected = true)
			{
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
	function saveControl(src, dest) {
		var i;
		var s="";
		if (src.options.length>0) {
			for (i=0; i<src.options.length; i++) {
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
function change_focus(f,o){
	var ret1 = "";
	var j=0;
	var i=0;
	var b=0;
	first_object_id = -1;
	//try{
		keyCode = window.event.keyCode;
		// Neu la phim Enter, Down, Up
		if (keyCode=='13' || keyCode=='40' || keyCode=='38') {
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
/**********************************************************************************************************************
//	Ham FormatMoney tu dong them dau "," vao text box khi nhap gia tri co kien la "Tien"
//	Khi do TextBox co dang : "123,456,789"
//	Khi goi : onkeyup="JavaScript:FormatMoney(this)"
***********************************************************************************************************************/
 function format_money(Obj)
 {
	var theKey = event.keyCode;	
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
function GetFileName(Obj,DesObj)
{
	strFilePath = Obj.value;
	FilePathLen = strFilePath.length;
	var strFileName = '';
	var SepChar = '';
	for(var i = FilePathLen;i>=0;i--)
	{	
		SepChar = Obj.value.substring(strFilePath.length-1,strFilePath.length);
		if(SepChar!="\\")
		{			
			strFilePath = strFilePath.substring(0,strFilePath.length-1)	;
			strFileName = SepChar+strFileName ;
		}
		else
		{
			DesObj.value = strFileName;
			return 1;
		}
	
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
		p_select_obj(0).selected = true;
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
			document.forms[0].hdn_item_id.value=node.id;
			document.forms[0].submit();
		}
	}else{
		document.forms[0].hdn_item_id.value=node.id;
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
// Y nghia: 
// - Xy ly khi NSD nhan vao nut "dong/mo" trong CAY
//**********************************************************************************************************************
function node_image_onclick(node,show_control,img_open_container_str,img_close_container_str,hdn_list_parent_obj) {
	//alert(hdn_list_parent_obj.value);
	//Neu nut (anh) la mot nut dang leaf_object thi khong co tuong tac
	if (node.type=='1') {return;}
	var nextDIV = node.nextSibling;
	while(nextDIV.nodeName != "DIV"){
		nextDIV = nextDIV.nextSibling;
	}
	if (nextDIV.style.display == 'none') {
		if (node.childNodes.length > 0) {
			if(node.childNodes.item(0).nodeName == "IMG"){
				node.childNodes.item(0).src = img_open_container_str;
				try{
					select_parent_radio(document.all.rad_item_id,document.all.chk_item_id,node.id);
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
			if (node.childNodes.item(0).nodeName == "IMG"){
				node.childNodes.item(0).src = img_close_container_str;
				try{
					select_parent_radio(document.all.rad_item_id,document.all.chk_item_id,node.id);
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
