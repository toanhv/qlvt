function btn_update_handle(p_checkbox_obj,p_url){
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
			row_onclick(document.getElementById('hdn_record_id'), v_value_list, p_url);
	}
}
function btn_save_handle(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML				
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 		
	}	
}
function btn_delete_handle(p_checkbox_obj, p_hidden_obj, p_url){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon ?')){			
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			actionUrl(p_url);
		}
	}
}
function btn_submitorder_handle(p_checkbox_obj, p_hidden_obj, p_url){	
	_save_xml_tag_and_value_list(document.forms[0], document.getElementById('hdn_filter_xml_tag_list'),document.getElementById('hdn_filter_xml_value_list'), true);
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Chua co doi tuong nao duoc chon");
	}else{
		p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
		actionUrl(p_url);
	}
}
function 	getLeader(code){
	try{
		if(code.value == 'department' ){
			document.getElementById('unit').style.display = "none"; 
			document.getElementById('department').style.display = "block";
			document.getElementById('unit_leader').value ='';
			document.getElementById('department_leader').setAttribute("option","false");
			document.getElementById('department_leader').setAttribute("optional","");
			document.getElementById('unit_leader').setAttribute("option","");
			document.getElementById('unit_leader').setAttribute("optional","true");
		}
	}catch(e){;}	
	try{
		if(code.value == 'unit' ){
			document.getElementById('department').style.display = "none"; 
			document.getElementById('unit').style.display = "block";
			document.getElementById('department_leader').value ='';
			document.getElementById('unit_leader').setAttribute("option","false");
			document.getElementById('unit_leader').setAttribute("optional","");
			document.getElementById('department_leader').setAttribute("option","");
			document.getElementById('department_leader').setAttribute("optional","true");
		}
	}catch(e){;}	
}	