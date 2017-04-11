
function select_staff_onchange(sel_obj){
	try{
		if(sel_obj.value!= ""){	
			document.getElementById('id_position_group').style.display = "none";
			document.getElementById('position_group').value = "";
		}else{	
			document.getElementById('id_position_group').style.display = "block";
		}
	}catch(e){;}
}
function select_group_onchange(sel_obj){
	try{
		if(sel_obj.value!= ""){	
			document.getElementById('id_staff_process').style.display = "none";
			document.getElementById('staff_process').value = "";
		}else{
			document.getElementById('id_staff_process').style.display = "block";
		}
	}catch(e){;}
}

// Ham btn_save_list duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat 1 doi tuong
function btn_save_list(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML				
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 		
	}	
}

///Luu lai danh sach cac the va cac gia tri co trong form
function btn_reset_onclick(p_hdn_tag_obj,p_hdn_value_obj,p_hdn_page_obj,p_fuseaction){
	p_hdn_tag_obj.value = "";
	p_hdn_value_obj.value = "";
	p_hdn_page_obj.value = 1;
	document.getElementById('hdn_filter_xml_tag_list').value = 'listtype_type';
	document.getElementById('hdn_filter_xml_value_list').value = document.getElementById('hdn_listtype').value;
	document.getElementById('fuseaction').value = p_fuseaction;
	document.forms[0].submit(); 
}

/// Ham item_onclick duoc goi khi NSD click vao 1 dong trong danh sach
//  p_item_value: chua ID cua doi tuong can hieu chinh
function item_onclick(p_item_value){
	//_save_xml_tag_and_value_list(document.forms(0), document.forms(0).hdn_filter_xml_tag_list,document.forms(0).hdn_filter_xml_value_list, false);	
	row_onclick(document.getElementById('hdn_list_id'), p_item_value,"edit");
}

function btn_move_updown(p_list_id, p_direction) {
	document.forms(0).fuseaction.value = 'MOVE_UPDOWN_LIST';
	document.forms(0).hdn_list_id.value = p_list_id;
	document.forms(0).hdn_direction.value = p_direction;	
	document.forms(0).submit();
}	

function btn_del_onclick(p_hdn_tag_obj, p_hdn_value_obj, p_chk_obj, p_hdn_id_list_obj, p_hdn_page_obj, p_fuseaction){
	_save_xml_tag_and_value_list(document.forms[0],p_hdn_tag_obj,p_hdn_value_obj,false);
	p_hdn_page_obj.value =1;
	//alert(checkbox_value_to_list(p_chk_obj,","));
	btn_delete_onclick(p_chk_obj,p_hdn_id_list_obj,p_fuseaction);
}

// Ham nay duoc goi khi NSD nhan vao 1 trang trong danh sach cac trang o cuoi danh sach
function page_onclick(p_page_number){
	document.forms(0).hdn_page.value = p_page_number;
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	btn_save_onclick('DISPLAY_ALL_LIST');
}

// Ham nay duoc goi khi NSD nhan vao nut "Truy van du lieu"
function btn_query_data_onclick(p_hdn_tag_obj,p_hdn_value_obj, p_hdn_page_obj, pAction){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, false);		
	p_hdn_page_obj.value = 1;	
	actionUrl(pAction);
}

///////////////////////////////////////////////////////////////////////////////////////////
function onchange_submit(pAction){
	//document.forms(0).hdn_page.value =1;	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	actionUrl('');
}
function set_input(){
	
}
// Ham nay duoc goi khi NSD nhan vao radio "du an da giao dat"
function radio_checked(obj_sel){

	document.getElementById('tu_dong_tang_dan').checked=true;
	document.getElementById('nhap_bang_tay').checked=false;	
	alert(document.getElementById('tu_dong_tang_dan').value);
	//alert("OK");
	if(document.getElementById('tu_dong_tang_dan').checked=true){
		document.getElementById('nhap_bang_tay').checked=false;		
	}else{
		document.getElementById('nhap_bang_tay').checked=true;		
	}
	//document.forms(0).submit();
}
// ham btn_delete_onclick() duoc goi khi NSD nhan chuot vao nut "Xoa"
//  - p_checkbox_name: ten cua checckbox, vi du "chk_building_form_id"
//  - p_url: Dia chi URL de thuc thi
function btn_delete_onclick(p_checkbox_obj, p_hidden_obj, p_url){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){			
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			actionUrl(p_url);
		}
	}
}