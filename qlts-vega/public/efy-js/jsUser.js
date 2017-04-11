//Xu ly khi NSD nhan vao nut anh cua don vi chua cac enduser.
//Trong man hinh danh sach nguoi su dung cua mot ung dung (goi tu file dsp_all_enduser.php va dsp_all_staff_for_application)
function show_enduser_on_unit(img_obj){
	var v_count;
	//Thay doi anh hien thi cua modul
	var v_img_path = img_obj.src.substring(0, img_obj.src.lastIndexOf('/') + 1);
	if (img_obj.getAttribute('status') == "on"){
		img_obj.setAttribute('status','off');
		img_obj.setAttribute('src', v_img_path + 'close.gif');
	}else{		
		img_obj.setAttribute('status','on');
		img_obj.setAttribute('src', v_img_path + 'open.gif');
	}	
	var objTr = document.getElementsByName("tr_enduser");	
	if (objTr.length > 1){
		for(i=0; i<objTr.length; i++){						
			if (img_obj.getAttribute('status') == "on"){
				var sUnit = objTr[i].getAttribute('unit');
	 			if (sUnit == img_obj.getAttribute('unit')){	 				
					objTr[i].style.display = "block";			
				}	 						 		
	 		}else{
	 			var sUnit = objTr[i].getAttribute('unit');				
	 			if (sUnit == img_obj.getAttribute('unit')){	 				
					objTr[i].style.display = 'none';				
				}	
	 		}	
	 	}
	}
}

function save_hidden_list_item_id(p_hdn_list,p_chk_obj){
	p_hdn_list.value = checkbox_value_to_list(p_chk_obj,",");
}

/// Ham item_onclick duoc goi khi NSD click vao 1 dong trong danh sach
//  p_item_value: chua ID cua doi tuong can hieu chinh
function iTemOnclick(pObjCheckBox, pObjHidden, pAction){	
	if (!checkbox_value_to_list(pObjCheckBox,"!~~!")){
		alert("Chua co doi tuong nao duoc chon!");
	}else{
		pObjHidden.value = checkbox_value_to_list(pObjCheckBox,"!~~!"); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
		actionUrl(pAction);
	}
}


//Ham xu ly  check all cac phan tu cua nhom
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

//Update quyen cho NSD
function saveUserPermission(ObjChkItem, pHiddenValue, pAction){
	pHiddenValue.value = checkbox_value_to_list(ObjChkItem,"!~~!");	
	actionUrl(pAction);	
}
/*
	Creater: Toanhv
	Date: 09/06/2010
*/
function select_all_enduser_on_unit(unitId, chk_item_id){
	try{
		var chk_item_unit = document.getElementsByName('chk_unit_id');
		for(j =0; j < chk_item_unit.length; j ++){
			if(unitId.checked){
				if(chk_item_unit[j].value != unitId.value){
					chk_item_unit[j].checked = false;
				}
			}
		}
	}catch(e){;}
	for(i =0; i < chk_item_id.length; i ++){
		if(unitId.checked){
			if(chk_item_id[i].getAttribute('unit_id') == unitId.value){
				chk_item_id[i].checked = true;
			}else{
				chk_item_id[i].checked = false;
			}
		}else{
			chk_item_id[i].checked = false;
		}
	}	
}