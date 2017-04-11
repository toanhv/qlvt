// JavaScript Document
function save_list_to_hidden(p_hdn_name,p_hdn_value,p_name_obj,p_value_obj){
	var name_list = "";
	var value_list = "";
	document.forms[0].hdn_admin_id_list.value=checkbox_value_to_list(document.forms[0].chk_staff_id,',');
	for(i=0;i<p_name_obj.length;i++){
		if (p_name_obj[i].value != ""){
			name_list = list_append(name_list,p_name_obj[i].value,_SUB_LIST_DELIMITOR);
			value_list = list_append(value_list,p_value_obj[i].value,_SUB_LIST_DELIMITOR);
		}
	}
	p_hdn_name.value = name_list;
	p_hdn_value.value = value_list;
}
function authentication_type_onchange(p_sel_obj){
	if (p_sel_obj.value==0){
		for (i=0; i<document.all.tr_application.length;i++){
			document.all.tr_application[i].style.display = "none";
		}
	}else{
		for (i=0; i<document.all.tr_application.length;i++){
			document.all.tr_application[i].style.display = "block";
		}
	}
}
function sel_onchange(p_select_obj,p_hdn_obj,p_fuseaction){
	p_hdn_obj.value = p_select_obj(p_select_obj.selectedIndex).id;
	document.forms[0].fuseaction.value = p_fuseaction;
	//document.forms[0].submit();
}
function save_hidden_list_item_id(p_hdn_list,p_chk_obj){
	p_hdn_list.value = checkbox_value_to_list(p_chk_obj,",");
}
function node_name_onclick(node,select_parent){
	//alert(is_node_empty(node));
	if ((select_parent=='false') & (_MODAL_DIALOG_MODE==1)){
		if (is_node_empty(node)){return;}
	}
	if (_MODAL_DIALOG_MODE==1){
		return_value = node.id + _LIST_DELIMITOR + node.value + _LIST_DELIMITOR + node.innerText;
		window.returnValue = return_value;
		window.close();
	}
	if (node.level=="0") {
		document.forms[0].fuseaction.value="DISPLAY_SINGLE_APPLICATION";
		document.forms[0].hdn_current_position.value=node.level + '_' + node.id;
	}
	if(node.level=="1"){
		document.forms[0].fuseaction.value="DISPLAY_SINGLE_MODUL";
		document.forms[0].hdn_current_position.value=node.level + '_' + node.id;
	}
	if(node.level=="2"){
		document.forms[0].fuseaction.value="DISPLAY_SINGLE_FUNCTION";
		document.forms[0].hdn_current_position.value='1_' + node.parent_id;
	}
	document.forms[0].hdn_item_id.value=node.id;
	document.forms[0].submit();
}
/* Mot so ham thao tac voi cay : them mot nude, xoa mot hoac nhieu node*/
function delete_nodes_of_treeview(p_radio_obj, p_checkbox_obj, p_hdn_list_item_id){
	//Xac dinh Radio dang chon
	var v_count; //Xac dinh so Radio trong form
	var v_current_radio_id = "";
	var v_parent_radio_id = "";
	var v_level = "";
	v_count = p_radio_obj.length;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_radio_obj[i].checked){
				v_current_radio_id = p_radio_obj[i].value;
				v_parent_radio_id = p_radio_obj[i].parent_id;
				v_level = p_radio_obj[i].level;
				break;
			}
		}
	}else{
		if (p_radio_obj.checked){
			v_current_radio_id = p_radio_obj.value;
			v_parent_radio_id = p_radio_obj.parent_id;
			v_level = p_radio_obj.level;	
		}
	}
	//Kiem tra cac leaf_obj co trong contener_obj co duoc danh dau chon hay khong
	//Neu co (v_empty_leaf=false) thi co nghia la xoa cac function, 
	//Neu khong (v_empty_leaf=true) thi la xoa application hoac modul dang chon
	var v_count;
	v_count = p_checkbox_obj.length;
	v_empty_leaf=true;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_checkbox_obj[i].parent_id==v_current_radio_id){
				v_empty_leaf=false;
				break;
			}
		}
	}else{
		if (p_checkbox_obj.parent_id==v_current_radio_id){
			v_empty_leaf=false;
		}
	}
	// Kiem tra cac containner_obj co trong containner_obj
	var v_count;
	v_count = p_radio_obj.length;
	v_empty_contener=true;
	if (v_count){
		for(i=0;i<v_count;i++){
			if (p_radio_obj[i].parent_id==v_current_radio_id){
				v_empty_contener=false;
				break;
			}
		}
	}else{
		if (p_radio_obj.parent_id==v_current_radio_id){
			v_empty_contener=false;
		}
	}
	
	if (v_level==0 & v_empty_contener == true){//Xoa ung dung
		btn_delete_onclick(p_radio_obj,p_hdn_list_item_id,"DELETE_APPLICATION");
		return;
	}
	if(v_level==1 & v_empty_leaf == true){//Xoa Modul
		//Luu vi tri cua ung dung hien thoi de quay lai vi tri hien thoi tren man hinh
		document.all.hdn_current_position.value = '0_' + v_parent_radio_id;
		//alert(document.all.hdn_current_position.value);
		btn_delete_onclick(p_radio_obj,p_hdn_list_item_id,"DELETE_MODUL");
		return;
	}else{// Xoa Function
		//Luu vi tri cua modul hien thoi de quay lai vi tri hien thoi tren man hinh
		document.all.hdn_current_position.value = '1_' + v_current_radio_id;
		//alert(document.all.hdn_current_position.value);
		btn_delete_onclick(p_checkbox_obj,p_hdn_list_item_id,"DELETE_FUNCTION");
	}
}
/*Them  mot nut trong treeview
Chuc nang: Them moi mot Node trong cay.
Tham so truyen vao:
	1. p_radio_obj : la mot doi tuong Radio button.
	2. p_hdn_item_id_obj: la mot doi tuong chua id cua Cha doi node moi them 
	3. p_fuseaction: bien fuseaction.
*/

function btn_add_node_of_treeview (p_show_control,p_fuseaction){
	//Xac dinh Radio dang chon
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
		if (v_parent_radio_id == "0" | v_parent_radio_id == "") { //Chon APPLICATION
			document.forms[0].hdn_application_id.value = v_current_radio_id;
		}else{ //Chon MODUL
			document.forms[0].hdn_application_id.value = v_parent_radio_id;
			document.forms[0].hdn_modul_id.value = v_current_radio_id;
		}
		//alert('item id '+v_current_radio_id);
		//alert('parent id '+v_parent_radio_id);
	}
	document.forms[0].hdn_item_id.value = '0';
	document.forms[0].fuseaction.value = p_fuseaction;
	document.forms[0].submit();
}
/* Ham show_all_row_selected co chuc nang hien thi nhung doi tuong thuoc nhom
*/
function show_row_selected(rad_id,tr_name){
	//Thay doi selected cua radio button
	eval('document.all.' + rad_id + '(0).checked=false');
	eval('document.all.' + rad_id + '(1).checked=true');
	//hide cac dong chua modul neu tr_name='tr_function' va gia tri cua checked=""
	if (tr_name == 'tr_function'){
		v_count=eval('document.all.tr_modul.length');
		if (v_count){
			for(var i=0;i<v_count;i++){
				if (eval('document.all.tr_modul[i].checked == ""')){
					eval('document.all.tr_modul[i].style.display="none"');
				}else{
					eval('document.all.tr_modul[i].style.display="block"');
				}
			}
		}else{
			if (eval('document.all.tr_modul.checked == ""')){
				eval('document.all.tr_modul.style.display="none"');
			}else{
				eval('document.all.tr_modul.style.display="block"');
			}
		}
	}
	//hide cac dong tr_name neu gia tri checked=""
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
/* Ham show_all_row co chuc nang hien thi tat ca doi tuong (ke ca khong thuoc nhom)
*/
function show_row_all(rad_id,tr_name){
	//Thay doi selected cua radio button
	eval('document.all.' + rad_id + '(0).checked=true');
	eval('document.all.' + rad_id + '(1).checked=false');
	//show tat ca cac tr_modul neu tr_name='tr_function'
	if (tr_name == 'tr_function'){
		v_count=eval('document.all.tr_modul.length');
		if(v_count){
			for(var i=0;i<v_count;i++){
				var v_img_path = getImgDirectory(eval('document.all.img_modul[i].src'));
				eval('document.all.img_modul[i].src = v_img_path + "open.gif"');
				eval('document.all.tr_modul[i].style.display="block"');
			}
		}else{
			eval('document.all.tr_modul.style.display="block"');
		}
	}
	//show tat ca cac tr_name
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
function change_item_checked(chk_obj,tr_name,rad_id){
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
			//Kiem tra xem trong mo dul co chuc nang nao duoc chon khong
			if (tr_name == 'tr_function'){
				if (eval('document.all.' + tr_name + '[i].modul == chk_obj.modul')){
					if (eval('document.all.' + tr_name + '[i].checked == "checked"')){
						v_modul_checked=true;
					}
				}
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
		if (tr_name == 'tr_function'){
			if (eval('document.all.' + tr_name + '.modul == chk_obj.modul')){
				if (eval('document.all.' + tr_name + '.checked == "checked"')){
					v_modul_checked=true;
				}
			}
		}
	}
	//thay doi gia tri cua checked trong tr_modul va chk_modul_id theo v_modul_checked=True or False
	if (tr_name == 'tr_function'){
		v_count = eval('document.all.tr_modul.length');
		if (v_count){
			i=0;
			while (i<v_count){
				if (eval('document.all.tr_modul[i].value == chk_obj.modul')){
					if (v_modul_checked == true){
						eval('document.all.tr_modul[i].checked = "checked"');
						eval('document.all.chk_modul_id[i].checked = "checked"');
					}else{
						eval('document.all.tr_modul[i].checked = ""');
						eval('document.all.chk_modul_id[i].checked = ""');
					}
					break;
				}
				i++;
			}
		}else{
			if (eval('document.all.tr_modul.value == chk_obj.modul')){
				if (v_modul_checked == true){
					eval('document.all.tr_modul.checked = "checked"');
				}else{
					eval('document.all.tr_modul.checked = ""');
				}
			}
		}
	}
	//Kiem tra che do hien thi de refresh man hinh
	if (eval('document.all.' + rad_id + '(1).checked')){
		show_row_selected(rad_id,tr_name);
	}
}

//Ham khoi phuc lai trang thai cua cay danh muc cac ung dung
//Ham nay dat che do mo (block) cho cac node dua vao danh sach cac node dang mo da duoc luu lai truoc khi SUBMIT

function keep_status_of_node(list_parent_id, open_image_name){	
	//Neu khong co node nao can khoi phuc thi thoat luon
	if(list_parent_id == ''){return;}
	// Dem cac node cua cay
	v_count = document.all.div_obj.length;
	//Dem cac node se khoi phuc lai che do mo (block)
	var arr = list_parent_id.split(_LIST_DELIMITOR);
	var number_parent_id = arr.length;
	var number_for = 0;
	for(i=0;i, i< v_count; i++){
		//Kiem tra xem neu da khoi phuc du cac node thi dung luon
		if(number_for == number_parent_id) 
			break;
		if(list_parent_id.search(document.all.div_obj[i].item_id) >= 0){
			document.all.div_obj[i].style.display = 'block';
			document.all.img[i].src = open_image_name;
			number_for ++;
		}
	}
}