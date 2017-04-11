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
		for (var i=0;i<v_count;i++){
			//alert(' gia tri cu checked tr_function: ' + eval('document.all.' + tr_name + '[i].checked'));
			if (eval('document.all.' + tr_name + '[i].checked == ""')){
				eval('document.all.' + tr_name + '[i].style.display="none"');
			}else{
				eval('document.all.' + tr_name + '[i].style.display="block"');
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
/*Ham change_item_checked
Chuc nang: Xu ly khi NSD click mouse vao checkbox cua EndUser hoac cua Function
	-Tim tr_name chua checkbox duoc click va thay doi trang thai cua attribute checked ("" hoac "checked")
	-Kiem tra cac trang thai checked cua cac function trong modul de xac dinh trang thai checked cua modul
	(Neu khong co function nao duoc chon thi checked=""; neu co thi checked="checked")
	-Kiem tra che do hien thi (qua radio button) de refresh lai man hinh
Tham so:
	-chk_obj: doi tuong checkbox duoc click
	-tr_name: ten id cua row chua checkbox (tr_function hoac tr_enduser)
	-rad_id:  ten id cua radio button xac dinh che do hien thi cua moi loai (rad_group_enduser hoac rad_group_function)
*/
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
			//Kiem tra xem trong modul co chuc nang nao duoc chon khong
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
/*

*/
function show_function_on_modul(img_obj){
	var v_count;
	//Thay doi anh hien thi cua modul
	var v_img_path = img_obj.src.substring(0, img_obj.src.lastIndexOf('/') + 1);
	if (img_obj.status == "on"){
		img_obj.status = "off";
		eval('img_obj.src = v_img_path + "close.gif"');
	}else{
		img_obj.status = "on";
		eval('img_obj.src = v_img_path + "open.gif"');
	}
	//dat che do hien thi cho cac tr_function thuoc modul
	v_count = document.all.tr_function.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (img_obj.status == "on"){
				if (eval('document.all.tr_function[i].modul == img_obj.modul')){
					//Kiem tra neu che do hien thi la tat ca thi show, neu khong thi chi show nhung tr_function.checked="checked"
					if (document.all.rad_group_function(0).checked == true){ //hien thi tat ca
						eval('document.all.tr_function[i].style.display = "block"');
					}else{ //Chi hien thi nhung chuc nang da chon
						if (eval('document.all.tr_function[i].checked == "checked"')){
							eval('document.all.tr_function[i].style.display = "block"');
						}
					}
				}
			}else{
				if (eval('document.all.tr_function[i].modul == img_obj.modul')){
					eval('document.all.tr_function[i].style.display = "none"');
				}
			}
		}
	}else{
		if (img_obj.status == "on"){
			if (eval('document.all.tr_function.modul == img_obj.modul')){
				//Kiem tra neu che do hien thi la tat ca thi show, neu khong thi chi show nhung tr_function.checked="checked"
				if (document.all.rad_group_function(0).checked == true){ //hien thi tat ca
					eval('document.all.tr_function.style.display = "block"');
				}else{ //Chi hien thi nhung chuc nang da chon
					if (eval('document.all.tr_function.checked == "checked"')){
						eval('document.all.tr_function.style.display = "block"');
					}
				}
			}
		}else{
			if (eval('document.all.tr_function.modul == img_obj.modul')){
				eval('document.all.tr_function.style.display = "none"');
			}
		}
	}
}
//Xu ly khi NSD nhan vao nut anh cua don vi chua cac enduser.
//Trong man hinh cap nhat mot nhom NSD (goi tu file dsp_single_group.php)
function show_enduser_on_unit_for_uppdating_group(img_obj,rad_option_obj){
	var v_count;
	var v_show_all = false;
	if (rad_option_obj(0).checked){
		v_show_all = true;
	}
	//Thay doi anh hien thi cua modul
	var v_img_path = img_obj.src.substring(0, img_obj.src.lastIndexOf('/') + 1);
	if (img_obj.status == "on"){
		img_obj.status = "off";
		img_obj.src = v_img_path + "close.gif"
	}else{
		img_obj.status = "on";
		img_obj.src = v_img_path + "open.gif"
	}
	//dat che do hien thi cho cac tr_enduser thuoc unit
	v_count = document.all.tr_enduser.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (img_obj.status == "on"){
				if (document.all.tr_enduser[i].unit == img_obj.unit){
					if (v_show_all == true){
						document.all.tr_enduser[i].style.display = "block";
					}else{
						if (document.all.tr_enduser[i].checked == "checked"){
							document.all.tr_enduser[i].style.display = "block";
						}else{
							document.all.tr_enduser[i].style.display = "none";
						}
					}
				}
			}else{
				if (document.all.tr_enduser[i].unit == img_obj.unit){
					document.all.tr_enduser[i].style.display = "none";
				}
			}
		}
	}else{
		if (img_obj.status == "on"){
			if (document.all.tr_enduser.unit == img_obj.unit){
					if (v_show_all == true){
						document.all.tr_enduser.style.display = "block";
					}else{
						if (document.all.tr_enduser.checked == "checked"){
							document.all.tr_enduser.style.display = "block";
						}else{
							document.all.tr_enduser.style.display = "none";
						}
					}
			}
		}else{
			if (document.all.tr_enduser.unit == img_obj.unit){
				document.all.tr_enduser.style.display = "none";
			}
		}
	}
}
//Xu ly khi NSD nhan vao nut anh cua don vi chua cac enduser.
//Trong man hinh danh sach nguoi su dung cua mot ung dung (goi tu file dsp_all_enduser.php va dsp_all_staff_for_application)
function show_enduser_on_unit(img_obj){
	var v_count;
	//Thay doi anh hien thi cua modul
	var v_img_path = img_obj.src.substring(0, img_obj.src.lastIndexOf('/') + 1);
	if (img_obj.status == "on"){
		img_obj.status = "off";
		eval('img_obj.src = v_img_path + "close.gif"');
	}else{
		img_obj.status = "on";
		eval('img_obj.src = v_img_path + "open.gif"');
	}
	//dat che do hien thi cho cac tr_enduser thuoc unit
	v_count = document.all.tr_enduser.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (img_obj.status == "on"){
				if (eval('document.all.tr_enduser[i].unit == img_obj.unit')){
					eval('document.all.tr_enduser[i].style.display = "block"');
				}
			}else{
				if (eval('document.all.tr_enduser[i].unit == img_obj.unit')){
					eval('document.all.tr_enduser[i].style.display = "none"');
				}
			}
		}
	}else{
		if (img_obj.status == "on"){
			if (eval('document.all.tr_enduser.unit == img_obj.unit')){
				eval('document.all.tr_enduser.style.display = "block"');
			}
		}else{
			if (eval('document.all.tr_enduser.unit == img_obj.unit')){
				eval('document.all.tr_enduser.style.display = "none"');
			}
		}
	}
}

function save_hidden_list_item_id(p_hdn_list,p_chk_obj){
	p_hdn_list.value = checkbox_value_to_list(p_chk_obj,",");
}

function getImgDirectory(source) {
	return source.substring(0, source.lastIndexOf('/') + 1);
}

function show_or_hide_all_enduser(p_type,p_tr_enduser_obj,img_obj,rad_select_obj){
	//Thay doi selected cua radio button
	if (p_type == "SHOW_ALL"){
		rad_select_obj(1).checked=false;
		rad_select_obj(0).checked=true;
	}else{
		rad_select_obj(1).checked=true;
		rad_select_obj(0).checked=false;
	}
	//Xu ly cac tr cua enduser
	v_count = p_tr_enduser_obj.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (p_type == "SHOW_ALL"){
				p_tr_enduser_obj[i].style.display = "block";
			}else{
				p_tr_enduser_obj[i].style.display = "none";
			}
		}
	}else{
		if (p_type == "SHOW_ALL"){
			p_tr_enduser_obj.style.display = "block";
		}else{
			p_tr_enduser_obj.style.display = "none";
		}
	}
	//Xu ly cac anh cua tr unit
	v_count = img_obj.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			var v_img_path = img_obj[i].src.substring(0, img_obj[i].src.lastIndexOf('/') + 1);
			if (p_type == "SHOW_ALL"){
				img_obj[i].status = "on";
				eval('img_obj[i].src = v_img_path + "open.gif"');
			}else{
				img_obj[i].status = "off";
				eval('img_obj[i].src = v_img_path + "close.gif"');
			}
		}
	}else{
		var v_img_path = img_obj.src.substring(0, img_obj.src.lastIndexOf('/') + 1);
		if (p_type == "SHOW_ALL"){
			img_obj.status = "on";
			eval('img_obj.src = v_img_path + "open.gif"');
		}else{
			img_obj.status = "off";
			eval('img_obj.src = v_img_path + "close.gif"');
		}
	}
}
//Ham nay thuc hien chuc nang thiet lap thuoc tinh checked cho cac chk_modul_id
//Kiem tra xem neu trong modul co it nhat 1 chk_function_id dang checked thi dat thuoc tinh cho chk_modul_id la checked
function set_modul_checked_by_function_checked(p_chk_modul_obj,p_chk_function_obj,p_tr_modul_obj){
	var v_count_modul = p_chk_modul_obj.length;
	var v_count_function = p_chk_function_obj.length;
	
	if (v_count_modul){
		for (var i=0; i<v_count_modul; i++){
			v_modul_checked = "";
			//Tim trong cac p_chk_function_obj thuoc vao modul neu co 1 p_chk_function_obj da checked thi dat modul la checked
			if (v_count_function){
				for (j=0; j<v_count_function; j++){
					if ((p_chk_function_obj[j].checked == true) && (p_chk_function_obj[j].modul==p_chk_modul_obj[i].value)){
						v_modul_checked = "checked";
					}
				}//End For of Function
			}else{
				if ((p_chk_function_obj.checked == true) && (p_chk_function_obj.modul==p_chk_modul_obj[i].value)){
					v_modul_checked = "checked";
				}
			}
			//Dat cac thuoc tinh checked cho chk_modul_id
			p_chk_modul_obj[i].checked = v_modul_checked;
			//Dat thuoc tinh checked cho tr_modul
			p_tr_modul_obj[i].checked = v_modul_checked;
		}//End For of modul
	}else{
		v_modul_checked = "";
		if (v_count_function){
			for (j=0; j<v_count_function; j++){
				if ((p_chk_function_obj[j].checked == true) && (p_chk_function_obj[j].modul==p_chk_modul_obj.value)){
					v_modul_checked = "checked";
				}
			}//End For of Function
		}else{
			if ((p_chk_function_obj.checked == true) && (p_chk_function_obj.modul==p_chk_modul_obj.value)){
				v_modul_checked = "checked";
			}
		}
		//Dat cac thuoc tinh checked cho chk_modul_id
		p_chk_modul_obj.checked = v_modul_checked;
		//Dat thuoc tinh checked cho tr_modul
		p_tr_modul_obj.checked = v_modul_checked;
	}
}
//Ham nay thuc hien chuc nang thiet lap thuoc tinh checked,disabled cho cac chk_function_id; thuoc tinh checked cho tr_function;
//Khi NSD thay doi viec ban quyen theo nhom cho enduser
function set_function_checked_by_group_checked(p_chk_group_obj,p_chk_function_obj,p_tr_function_obj){
	var v_count_group = p_chk_group_obj.length;
	var v_count_function = p_chk_function_obj.length;
	
	if (v_count_function){
		for (var i=0; i<v_count_function; i++){
			//Luu lai trang thai checked cua cac chk_function_obj[i] neu no khong phai la disabled de khoi phuc lai
			v_function_checked = "";
			if (p_chk_function_obj[i].disabled == false){
				v_function_checked = p_chk_function_obj[i].checked;
			}
			v_function_disabled = ""; //p_chk_function_obj[i].disabled;
			//Tim xem chk_function_obj[i] co thuoc cac p_chk_group_obj dang chon hay khong, neu co thi thiet lap cac thuoc tinh cho no
			//Neu khong thi thiet lap nguoc lai
			if (v_count_group){
				for (j=0; j<v_count_group; j++){
					if (p_chk_function_obj[i].group_list.indexOf(p_chk_group_obj[j].value) >= 0){
						if (p_chk_group_obj[j].checked == true){
							v_function_checked = "checked";
							v_function_disabled = "disabled";
							break;
						}
					}
				}//End For
			}else{
				if ((p_chk_function_obj[i].group_list.indexOf(p_chk_group_obj.value) >= 0) && (p_chk_group_obj.checked == true)){
					v_function_checked = "checked";
					v_function_disabled = "disabled";
				}
			}
			//Dat cac thuoc tinh checked,disabled cho chk_function_id
			p_chk_function_obj[i].checked = v_function_checked;
			p_chk_function_obj[i].disabled = v_function_disabled;
			//Dat thuoc tinh checked cho tr_function
			if (p_chk_function_obj[i].checked == true){
				p_tr_function_obj[i].checked = "checked";
			}else{
				p_tr_function_obj[i].checked = "";
			}
		}//End For
	}else{
		//Luu lai trang thai checked cua cac chk_function_obj[i] neu no khong phai la disabled de khoi phuc lai
		v_function_checked = "";
		if (p_chk_function_obj.disabled == false){
			v_function_checked = p_chk_function_obj.checked;
		}
		v_function_disabled = ""; //p_chk_function_obj[i].disabled;
		//Tim xem chk_function_obj[i] co thuoc cac p_chk_group_obj dang chon hay khong, neu co thi thiet lap cac thuoc tinh cho no
		//Neu khong thi thiet lap nguoc lai
		if (v_count_group){
			for (j=0; j<v_count_group; j++){
				if (p_chk_function_obj.group_list.indexOf(p_chk_group_obj[j].value) >= 0){
					if (p_chk_group_obj[j].checked == true){
						v_function_checked = "checked";
						v_function_disabled = "disabled";
						break;
					}
				}
			}//End For
		}else{
			if ((p_chk_function_obj.group_list.indexOf(p_chk_group_obj.value) >= 0) && (p_chk_group_obj.checked == true)){
				v_function_checked = "checked";
				v_function_disabled = "disabled";
			}
		}
		//Dat cac thuoc tinh checked,disabled cho chk_function_id
		p_chk_function_obj.checked = v_function_checked;
		p_chk_function_obj.disabled = v_function_disabled;
		//Dat thuoc tinh checked cho tr_function
		if (p_chk_function_obj.checked == true){
			p_tr_function_obj.checked = "checked";
		}else{
			p_tr_function_obj.checked = "";
		}
	}
}

/*Ham checkbox_group_onclick
Chuc nang: Xu ly khi NSD click mouse vao checkbox cua chk_group_id
	-Tim tr_name chua checkbox duoc click va thay doi trang thai cua attribute checked ("" hoac "checked")
	-Tim cac function cua group duoc chon de thay doi gia tri cua 2 thuoc tinh: checked va disabled
	-Thay doi thuoc tinh checked cua tr_function chua chk_function tuong ung
	-Kiem tra cac trang thai checked cua cac function trong modul de xac dinh trang thai checked cua modul
	(Neu khong co function nao duoc chon thi checked=""; neu co thi checked="checked")
	-Kiem tra che do hien thi (qua radio button) de refresh lai man hinh
Tham so:
	-chk_group_obj: doi tuong checkbox duoc click
	-tr_group_obj: ten id cua row chua checkbox (tr_function hoac tr_enduser)
	-rad_id:  ten id cua radio button xac dinh che do hien thi cua moi loai (rad_group_enduser hoac rad_group_function)
*/
function checkbox_group_onclick(p_item_obj,chk_group_obj,tr_group_obj,chk_modul_obj,tr_modul_obj,chk_function_obj,tr_function_obj,rad_group_id,rad_function_id){
	var v_count;
	//Dat lai gia tri cua chk_group_obj.checked va tr_group_obj.checked
	v_count = tr_group_obj.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (tr_group_obj[i].value == p_item_obj.value){
				tr_group_obj[i].checked = p_item_obj.checked;
				break;
			}
		}
	}else{
		if (tr_group_obj.value == p_item_obj.value){
			tr_group_obj.checked = p_item_obj.checked;
		}
	}
	//Dat lai thuoc tinh checked va disabled cho cac chk_function_obj thuoc chk_group_obj duoc click
	set_function_checked_by_group_checked(chk_group_obj,chk_function_obj,tr_function_obj);
	//Goi ham de dat thuoc tinh cho cac chk_modul_obj theo cac chk_function_obj
	set_modul_checked_by_function_checked(chk_modul_obj,chk_function_obj,tr_modul_obj);
	//Kiem tra che do hien thi de refresh man hinh
	if (eval('document.all.' + rad_group_id + '(1).checked')){
		show_row_selected(rad_group_id,'tr_group');
	}
	//Kiem tra che do hien thi de refresh man hinh
	if (eval('document.all.' + rad_function_id + '(1).checked')){
		show_row_selected(rad_function_id,'tr_function');
	}
}
//Ham xu ly khi NSD nhan vao ten cua enduser de cap nhat thong tin ve quyen cua enduser.
function enduser_onclick(p_item_obj,p_item_id,p_unit_id,p_fuseaction,p_url){
	document.all.hdn_current_position.value = p_unit_id;
	row_onclick(p_item_obj,p_item_id,p_fuseaction,p_url);
}
//Ham xu ly khi NSD nhan vao checkbox cua Unit chua cac enduser.
//Neu Unit duoc chon thi danh dau tat ca cac enduser cua Unit do
//Neu Unit khong duoc chon thi bo danh dau tat caca enduser cua Unit do.
function select_all_enduser_on_unit(p_item_obj,p_chk_enduser_obj){
	//alert(p_item_obj.checked);
	var v_modul_checked = p_item_obj.checked;
	var v_count = p_chk_enduser_obj.length;
	if (v_count){
		for (var i=0; i<v_count; i++){
			if (p_chk_enduser_obj[i].unit_id == p_item_obj.value){
				p_chk_enduser_obj[i].checked = v_modul_checked;
			}
		}
	}else{
		if (p_chk_enduser_obj.unit_id == p_item_obj.value){
			p_chk_enduser_obj.checked = v_modul_checked;
		}
	}
}
//Ham xu ly khi NSD nhan vao checkbox cua Enduser
//Kiem tra neu tat car enduser cua Unit duoc chon thi danh dau vao unit tuong ung, 
//Neu khong co enduser nao duoc chon thi bo danh dau cua Unit
function set_value_checked_checkbox_unit(p_item_obj,p_chk_enduser_obj,p_chk_unit_obj){
	var v_enduser_checked = p_item_obj.checked;
	var v_count_enduser = p_chk_enduser_obj.length;
	var v_count_unit = p_chk_unit_obj.length;
	//Neu danh dau chon 1 enduser trong Unit thi danh dau Unit tuong ung
	if (v_enduser_checked == true){
		if (v_count_unit){
			for (var i=0; i<v_count_unit; i++){
				if (p_chk_unit_obj[i].value == p_item_obj.unit_id){
					p_chk_unit_obj[i].checked = v_enduser_checked;
					break;
				}
			}
		}else{
			if (p_chk_unit_obj.value == p_item_obj.unit_id){
				p_chk_unit_obj.checked = v_enduser_checked;
			}
		}
	}else{
		//Kiem tra xem cac enduser cua Unit co cai nao duoc duoc danh dau khong
		var v_unit_checked = false;
		if (v_count_enduser){
			for (var i=0; i<v_count_enduser; i++){
				if (p_chk_enduser_obj[i].unit_id == p_item_obj.unit_id){
					if (p_chk_enduser_obj[i].checked == true){
						v_unit_checked = true;
						break;
					}
				}
			}
		}else{
			if (p_chk_enduser_obj.unit_id == p_item_obj.unit_id){
				if (p_chk_enduser_obj.checked == true){
					v_unit_checked = true;
				}
			}
		}
		//alert(v_unit_checked);
		//Dat thuoc tinh checked cho Unit tuong ung
		if (v_count_unit){
			for (var i=0; i<v_count_unit; i++){
				if (p_chk_unit_obj[i].value == p_item_obj.unit_id){
					p_chk_unit_obj[i].checked = v_unit_checked;
					break;
				}
			}
		}else{
			if (p_chk_unit_obj.value == p_item_obj.unit_id){
				p_chk_unit_obj.checked = v_unit_checked;
			}
		}
	}
}

