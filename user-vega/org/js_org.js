function btn_add_staff_from_ldap_user(){
	document.all.hdn_list_item_id.value = checkbox_value_to_list(document.all.chk_item_id,_LIST_DELIMITOR);
	returnValue = document.all.hdn_list_item_id.value;
	window.close();
}
function btn_select_staff_from_LDAP(){
	var v_count = document.all.rad_item_id.length;
	var v_current_radio_id = 0;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (document.all.rad_item_id[i].checked){
				v_current_radio_id = document.all.rad_item_id[i].value;
				break;
			}
		}
	}else{
		if (document.all.rad_item_id.checked){
			v_current_radio_id = document.all.rad_item_id.value;
		}
	}
	if (v_current_radio_id>0){
		v_url = _DSP_MODAL_DIALOG_URL_PATH;
		v_url = v_url + "?goto_url=org/index.php" + "&fuseaction=DISPLAY_ALL_LDAP_USER" + "&modal_dialog_mode=1" 
		sRtn = showModalDialog(v_url,"","dialogWidth=400PT"+";dialogHeight=300PT"+";dialogTop=80pt;status=no;scroll=no;");
		if (!sRtn) return;
		document.all.hdn_ldap_user_list.value = sRtn;
		document.all.hdn_unit_id.value = v_current_radio_id;
		document.all.fuseaction.value = "ADD_STAFF_FROM_LDAP_USER";
		document.forms[0].submit();
	}else{
		alert("Phai xac dinh mot don vi truoc khi lay NSD tu LDAP");	
		return;
	}
}

function btn_save_staff_onclick(p_fuseaction){
	if (!verify(document.forms[0])){
		return;
	}
	v_birthday = document.forms[0].txt_birthday.value;
	v_date = new Date(ddmmyyyy_to_mmddyyyy(v_birthday));
	v_year = v_date.getFullYear()*1;
	if (v_year==0 || v_year<=1900){
		alert("Nam sinh khong hop le");
		return;
	}
	if (date_compare("1/1/1900",v_birthday)<0){
		alert("Ngay sinh phai sau ngay 01/01/1900");
		return;
	}
	if (document.forms[0].txt_unit_name.value==""){
		alert("Phai xac dinh phong, ban");
		return;
	}
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}

//**********************************************************************************************************************
// Ham select_parent_unit(): hien thi cua so modal dialog de chon don vi cha
//**********************************************************************************************************************
function select_parent_unit(){
	f = document.forms[0];
	v_parent_id = f.hdn_unit_id.value; // Khong hien thi don vi nay
	v_height = "280pt";
	v_width = "450pt";
	v_allow_editing_in_modal_dialog = 0; // Khong cho phep Them/sua/xoa dia ban tren cua so CHON dia ban
	v_allow_select = 1;	// Hien thi nut "Chon"
	show_modal_dialog_treeview_onclick('org/index.php','DISPLAY_ALL_UNIT',f.txt_parent_name, f.hdn_parent_code, f.hdn_parent_id,v_parent_id,v_height,v_width,v_allow_editing_in_modal_dialog,v_allow_select);
}
//**********************************************************************************************************************
// Ham set_root_node_to_open(): dat che do "block" cho doi tuong goc
//**********************************************************************************************************************
function set_root_node_to_open(p_img_url){
	v_count = document.all.str_obj.length;
	tr_obj = document.all.str_obj;
	i=0;
	while(i<v_count){
		if (tr_obj[i].parent_id=="" && tr_obj[i].type=="0"){
			document.all.img[i].src = p_img_url;
			document.all.div_obj[i].style.display="block";
		}
		i++;
	}
}

//**********************************************************************************************************************
// Ham set_parent_node_to_open()
//**********************************************************************************************************************
function set_node_to_open(p_current_parent_id, p_current_id,p_img_url){
	v_current_parent_id = p_current_parent_id;
	v_count = document.all.str_obj.length;
	tr_obj = document.all.str_obj;
	i=0;
	while(i<v_count){
		// Hien thi node hien tai
		if (tr_obj[i].item_id==p_current_id && tr_obj[i].type=="0"){
			document.all.img[i].src = p_img_url;
			document.all.div_obj[i].style.display="block";
			document.all.rad_item_id[i].checked=true;
		}
		// Hien thi cac node cha, ong, cu, ...
		if (tr_obj[i].item_id==v_current_parent_id && tr_obj[i].type=="0"){
			document.all.img[i].src = p_img_url;
			document.all.div_obj[i].style.display="block";
			v_current_parent_id = tr_obj[i].parent_id;
			i=0;
		}else{
			i++;
		}
	}
}
//**********************************************************************************************************************
// Ham node_image_onclick()
// Y nghia: 
// - Xy ly khi NSD nhan vao nut "dong/mo" trong CAY
//**********************************************************************************************************************
function node_image_onclick(node,show_control,img_open_container_str,img_close_container_str,hdn_parent_item_id_obj) {
	//alert(node.parent_id);
	if (_MODAL_DIALOG_MODE==1)
		document.forms[0].action = "index.php?modal_dialog_mode=1";
	else
		document.forms[0].action = "index.php";
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
					select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.id);
				}catch(e){;}
			}
		}
		//Kiem tra neu van nhan vao cung mot nut anh thi khong phai SUBMIT, nhan nut anh khac moi SUBMIT
		if (document.forms[0].hdn_item_id.value==node.id){
			//Mo nut hien tai dong thoi them id cua nut do vao chuoi id can lay
			nextDIV.style.display = 'block';
			return;
		}else{
			document.forms[0].hdn_item_id.value=node.id;
			document.forms[0].hdn_current_position.value=node.level + '_' + node.id;
			document.forms[0].hdn_parent_item_id.value=node.parent_id;	
			document.forms[0].fuseaction.value="DISPLAY_ALL_STAFF";
			document.forms[0].submit();
		//}
		}
	} else {
		if (node.childNodes.length > 0) {
			if (node.childNodes.item(0).nodeName == "IMG"){
				node.childNodes.item(0).src = img_close_container_str;
				try{
					select_parent_radio(document.forms[0].rad_item_id,document.forms[0].chk_item_id,node.id);
				}catch(e){;}
			}
		}
		//Neu dong nut do lai thi bo id khoi chuoi
		nextDIV.style.display = 'none';
	}
}
//**********************************************************************************************************************
// Ham node_name_onclick()
// Thuc hien chuc nang hieu chinh thong tin cua mot doi tuong tren mot node cua cay
//	Input
//		1. id: Khoa chinh cua doi tuong
//		2. value: Ma viet tat cua doi tuong
//		3. text: Ten doi tuong
//		4. type: Loai doi tuong: 0-Doi tuong la phong ban; 1-Doi tuong la can bo
//**********************************************************************************************************************
function node_name_onclick(node,select_parent){
	if(_MODAL_DIALOG_MODE==1){
		return_value = node.id + _LIST_DELIMITOR + node.value + _LIST_DELIMITOR + node.innerText;
		//alert(return_value);
		window.returnValue = return_value;
		window.close();
	}
	if (node.level=="0") {
		document.forms[0].fuseaction.value="DISPLAY_SINGLE_UNIT";
	}else{
		document.forms[0].fuseaction.value="DISPLAY_SINGLE_STAFF";
		document.forms[0].hdn_parent_item_id.value=node.parent_id;
	}
	document.forms[0].hdn_item_id.value=node.id;
	document.forms[0].hdn_current_position.value=node.level + '_' + node.id;
	document.forms[0].submit();
}

/* Ham btn_delete_of_tree_onclick lam nhiem vu xoa mot doi tuong trong danh sach trong form dsp_single*/
function delete_node_of_tree(p_radio_obj, p_checkbox_obj, p_hdn_list_item_id){
	//Xac dinh Radio dang chon
	var v_count;
	var v_current_radio_id = "";
	v_count = p_radio_obj.length;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_radio_obj[i].checked){
				v_current_radio_id = p_radio_obj[i].value;
				document.forms[0].hdn_parent_item_id.value = p_radio_obj[i].parent_id;
				document.forms[0].hdn_item_id.value = p_radio_obj[i].value;
				break;
			}
		}
	}else{
		if (p_radio_obj.checked){
			v_current_radio_id = p_radio_obj.value;
			document.forms[0].hdn_parent_item_id.value = p_radio_obj.parent_id;
			document.forms[0].hdn_item_id.value = p_radio_obj.value;
		}
	}
	v_empty_staff=true;
	try{
		//Kiem tra cac staff co trong unit
		var v_count;
		v_count = p_checkbox_obj.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (p_checkbox_obj[i].parent_id==v_current_radio_id){
					v_empty_staff=false;
					break;
				}
			}
		}else{
			if (p_checkbox_obj.parent_id==v_current_radio_id){
				v_empty_staff=false;
			}
		}
	}catch(e){;}

	if (v_empty_staff){
		//Xoa Unit hien thoi
		btn_delete_onclick(p_radio_obj,p_hdn_list_item_id,"DELETE_UNIT");
	}else{
		//Xoa cac staff
		btn_delete_onclick(p_checkbox_obj,p_hdn_list_item_id,"DELETE_STAFF");
	}	
}

//**********************************************************************************************************************
// Ham btn_add_node_of_treeview()
// Chuc nang: Them moi mot Node trong cay.
// Tham so truyen vao:
//	1. p_radio_obj : la mot doi tuong Radio button.
//	2. p_hdn_item_id_obj: la mot doi tuong chua id cua Cha doi node moi them 
//	3. p_fuseaction: bien fuseaction.
//**********************************************************************************************************************

function btn_add_node_of_treeview(p_show_control,p_fuseaction){

	var v_count;
	var v_current_radio_id = "0";
	var v_parent_radio_id = "0";
	
	if (p_show_control == "true") {
		v_count = document.all.rad_item_id.length;
		if (v_count){
			for(i=0;i<v_count;i++){
				if (document.all.rad_item_id[i].checked){
					v_current_radio_id = document.all.rad_item_id[i].value;
					v_parent_radio_id =  document.all.rad_item_id[i].parent_id;
					break;
				}
			}
		}else{
			if (document.all.rad_item_id.checked){
				v_current_radio_id = document.all.rad_item_id.value;
				v_parent_radio_id =  document.all.rad_item_id.parent_id;
			}
		}
	}
	document.forms[0].hdn_parent_item_id.value = v_current_radio_id;
	document.forms[0].hdn_item_id.value = '0';
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}
