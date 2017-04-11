function select_user_onchange(sel_obj){
	document.getElementById('C_CODE').value = sel_obj[sel_obj.selectedIndex].value;
	document.getElementById('C_NAME').value = sel_obj[sel_obj.selectedIndex].getAttribute("name");
	//alert(sel_obj.selectedIndex);
}

// Ham btn_save_list duoc goi khi NSD nhan vao nut "Cap Nhat" tren form cap nhat 1 doi tuong
function btn_save_recordtype(p_hdn_tag_obj,p_hdn_value_obj,p_url){
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
function btn_delete_recordtype(p_checkbox_obj, p_hidden_obj, p_url){	
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
//Kiem tra so nguyen
function IsNumeric(input){
	if ( (input.value - 0) == input.value && (input.value.length > 0 ) && input.value > 0){
		return true;
	}else{
		alert("Gia tri nhap khong dung dinh dang kieu so nguyen (1, 2, 3, …)!");
		input.value = "";
		return false;
	}
}

//Kiem tra kieu so va ki tu duoc phep su dung
function isEdit(keycode,str)
{
	if ((keycode>=48 && keycode <=57) || (keycode>=96 && keycode <=105) || (keycode == 46) || (keycode == 8) || (keycode == 17 ) || (keycode == 37 ) || (keycode == 39 ) || (keycode == 9 ) || (keycode == 16 ) || (keycode == 13 ) || (keycode == 35 ) || (keycode == 36 )){
		return true;
	}else{
		alert("Chi su dung so tu 0 den 9!")
		str.value = '';
		return false;
	}	
}

// Tu dong them dau phay
function AddComma(str,e){
	var keycode;
	var delimitor;
	//keycode=window.event.keyCode
	keycode = (window.event)?event.keyCode:e.which; 
	//alert(keycode);
	isEdit(keycode,str);
	//alert(keycode);
	var lengthValue = str.value.length;
	if( lengthValue >= 4 ){
		delimitor = str.value.split(",");
		str.value = '';
		for(i=0;i<delimitor.length;i++){
			str.value = str.value + delimitor[i];
		}
		delimitor = str.value;
		str.value = '';
		for(i=0;i< delimitor.length;i++){
			str.value = str.value + delimitor[i];
			if(delimitor.length > 3 && (delimitor.length - 1 > i) ){
				if( (delimitor.length % 3 == 0) && (i % 3 == 2)){
					str.value = str.value + ',';
				}else if((delimitor.length % 3 == 1) && (i % 3 == 0)){
					str.value = str.value + ',';
				}else if((delimitor.length % 3 == 2) && (i % 3 == 1)) {
					str.value = str.value + ',';
				}
			}	
		}
		//str.value = str.value.substring(0,str.value.length -3) + ',' + str.value.substring(str.value.length -3,str.value.length);
	}	
}