function phone_node_name_onclick(node,select_parent){
	v_count = document.all.div_obj.length;
	v_url = "index.php?hdn_item_id=" + node.id;
	if (_EDITING_MODE==1){
		v_url = v_url + "&editing_mode=1";
	}
	//Neu chon don vi Trong man hinh tim kiem (dsp_single_staff) thi khong cho chon cha	
	//Neu don vi ma co cac phong ban khac thi khong hien thi truc tiep cac can bo ra ma chi hien thi cac phong ban
	for(i=0;i, i < v_count; i++){
		if(document.all.div_obj[i].parent_id == node.id){
			//Hien thi danh sach nhung so may chu y
			if(node.parent_id == 0){
				//v_url = v_url + "&fuseaction=DISPLAY_PHONE_TO_MIND";
				//var master_frame =  parent.master;
				//master_frame.location = v_url;				
				//window.parent.frames(2).location = v_url;
				//window.open(v_url,'master');
				node_image_onclick(node,'false','home.jpg','home.jpg');
			}
			return;
		}
	}	
	// Neu khong thi tim theo don vi
	v_url = v_url + "&fuseaction=DISPLAY_ALL_STAFF_BY_UNIT";
	var master_frame =  parent.master;
	master_frame.location = v_url;
	return;
}
function show_modal_onclick(p_goto_url,p_fuseaction,hdn_staff_name_obj,hdn_unit_name_obj,hdn_tel_local_obj,hdn_tel_office_obj,hdn_tel_mobile_obj,hdn_tel_home_obj,fuseaction_obj){
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + p_goto_url + "&fuseaction=" + p_fuseaction + "&modal_dialog_mode=1" + "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=350pt;dialogHeight=250pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	arr_value = sRtn.split(_LIST_DELIMITOR);
	hdn_staff_name_obj.value = arr_value[0];
	hdn_unit_name_obj.value = arr_value[1];
	hdn_tel_local_obj.value = arr_value[2];
	hdn_tel_office_obj.value = arr_value[3];
	hdn_tel_mobile_obj.value = arr_value[4];
	hdn_tel_home_obj.value = arr_value[5];
	fuseaction_obj.value = "DISPLAY_RESULT";
	document.forms(0).submit();
}
//Nut tim kiem
function btn_find_onclick(){
	if( document.all.txt_name.value == '' && document.all.txt_unit_name.value == ''){
		return;
	}
	return_value = document.all.txt_name.value + _LIST_DELIMITOR + document.all.txt_unit_name.value + _LIST_DELIMITOR + document.all.txt_tel_local.value + _LIST_DELIMITOR + document.all.txt_tel_office.value + _LIST_DELIMITOR + document.all.txt_tel_mobile.value + _LIST_DELIMITOR + document.all.txt_tel_home.value;
	window.returnValue = return_value;
	window.close();
}
function staff_onclick(p_staff_id){
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + "org/index.php" + "&fuseaction=DISPLAY_SINGLE_STAFF&modal_dialog_mode=1&hdn_item_id=" + p_staff_id + "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=400pt;dialogHeight=150pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	//Lay lai tu file act_update_staff
	arr_value = sRtn.split(_LIST_DELIMITOR);
	document.forms(0).fuseaction.value = "DISPLAY_ALL_STAFF_BY_UNIT";
	//Luu lai id cua don vi (hdn_item_id dung de cho fu hop voi file qry_single_staff)
	document.forms(0).hdn_item_id.value = arr_value[3];
	document.forms(0).submit();
}
function unit_onclick(p_unit_id){
	v_url = _DSP_MODAL_DIALOG_URL_PATH;
	v_url = v_url + "?goto_url=" + "org/index.php" + "&fuseaction=DISPLAY_SINGLE_UNIT&modal_dialog_mode=1&hdn_item_id=" + p_unit_id + "&" + randomizeNumber();
	sRtn = showModalDialog(v_url,"","dialogWidth=400pt;dialogHeight=150pt;dialogTop=80pt;status=no;scroll=no;");
	if (!sRtn) return;
	arr_value = sRtn.split(_LIST_DELIMITOR);
	document.forms(0).fuseaction.value = "DISPLAY_ALL_STAFF_BY_UNIT";
	document.forms(0).hdn_item_id.value = arr_value[0];
	document.forms(0).submit();
}
