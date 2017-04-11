function btn_save_asset(p_hdn_tag_obj,p_hdn_value_obj,p_url){
	try{
		_save_xml_tag_and_value_list(document.forms[0], p_hdn_tag_obj, p_hdn_value_obj, true);
	}catch(e){;}
	if (verify(document.forms[0])){	
		//Hidden luu danh sach the va gia tri tuong ung trong xau XML			
		document.getElementById('hdn_XmlTagValueList').value = p_hdn_tag_obj.value + '|{*^*}|' + p_hdn_value_obj.value;	
		//document.getElementsByTagName('form')[0].disabled = true;
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit(); 
	}	
}

function selectrowradio(obj){
	$('td').parent().removeClass('selected');
	$(obj).parent().parent().addClass('selected');
}
//Ham btn_sent_onclick duoc goi khi NSD bam vao nut 'gui'
function btn_updatemovehandle_onclick(radio_obj,p_url){
	if(!test_date(document.getElementById('C_WORK_DATE').value)){
		alert('NGAY CHUYEN khong dung dinh dang ngay/thang/nam');
		return false;
	}
	isvalue = 0;
	for(i = 0; i < radio_obj.length; i++){
		if(radio_obj[i].checked){
			isvalue = 1;
			break;
		}
	}
	if(isvalue == 0){
		if(document.getElementsByName('objectHandle')[0].checked)
			alert('Chua chon PHONG BAN thu ly ho so');
		if(document.getElementsByName('objectHandle')[1].checked)
			alert('Chua chon CAN BO thu ly ho so');
		return false;
	}else{
		if(document.getElementsByName('objectHandle')[1].checked && HandleIdList == ''){
			alert('Chua chon CAN BO thu ly ho so');
			return false;
		}
		//Kiem tra neu chi co 1 phong ban thu ly,1 can bo thu ly va NSD click chuyen phong ban thi se cap nhat cho can bo thu ly
		if(HandleIdList.length ==1 && HandleIdList != '' && DepartmentIdList.length == 1 && DepartmentIdList != ''  && document.getElementsByName('objectHandle')[0].checked){
			document.getElementsByName('objectHandle')[1].checked = true;
			radio_obj[0].checked = true;
		}
		document.getElementsByTagName('form')[0].action = p_url;
		document.getElementsByTagName('form')[0].submit();
		window.opener.document.getElementsByTagName('form')[0].submit();
		//window.close();
	}
}

function set_hidden_movehandle(obj, radio_obj, value){
	for(i = 0; i< radio_obj.length; i++){
		if(radio_obj[i].value == value && radio_obj[i].disabled == false){
			radio_obj[i].checked = true;
			$('td').parent().removeClass('selected');
			$(obj).parent().addClass('selected');
		}else{
			radio_obj[i].checked = false;
		}		
	}
}
function show_modal_dialog_onclick_update_unit_all(p_url, p_obj, browerName){ 
	var url = _GET_HTTP_AND_HOST + p_url;	
	var sRtn;			
	sRtn = showModalDialog(url,"","dialogWidth=800px;dialogHeight=500px;status=no;scroll=no;dialogCenter=yes");		
    if (sRtn != ""){    	
    	p_obj.value = sRtn;
    }	
}
function getInforInvite(){
	try{
		var InforInvite = document.getElementById('infor_invite').value;
		var ObjInvite = InforInvite.split('!@~!');	
	}catch(e){;}
	if(ObjInvite[0] != ""){
		document.getElementById('C_USE_INFO').value = ObjInvite[0]; 
	}
}
function btn_sent_invitation_unit_list(p_obj){
	var IDList =  document.getElementsByName('chk_multiple_checkbox');
	var StaffList =  document.getElementsByName('chk_staff_id'); 
	var nameReturn = '';
	var StaffIdReturn = '';
	var UnitIdReturn = '';
	var InforReceived = '';
	
	for(i =0; i< StaffList.length; i++){
		if(StaffList[i].checked){
			var nameStaff = StaffList[i].getAttribute('staffName');
			var StaffId = StaffList[i].value;
			StaffIdReturn = StaffIdReturn + StaffId + ',';
			nameReturn = nameReturn + nameStaff + '; ';
		}
	}	
	
	for(i =0; i< IDList.length; i++){
		if(IDList[i].checked){
			var UnitName = IDList[i].getAttribute('nameUnit');
			var UnitId = IDList[i].value;
			UnitIdReturn = UnitIdReturn + UnitId + ',';
			nameReturn = nameReturn + UnitName + '; ';
		}
	}
	if (p_obj == "unit" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_received').value = InforReceived;
		document.getElementById('kinh_gui').value = nameReturn; 
		document.getElementById('can_bo').value = StaffIdReturn;
		document.getElementById('don_vi_nhan').value = UnitIdReturn;
	}
	if (p_obj == "staff" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_invite').value = InforReceived;
		document.getElementById('kinh_moi').value = nameReturn; 
	}
	if (p_obj == "sign" ){
		InforReceived = nameReturn +'!@~!'+ StaffIdReturn +'!@~!'+ UnitIdReturn;
		document.getElementById('infor_sign').value = InforReceived;
		document.getElementById('nguoi_ky').value = nameReturn; 
	}		
	window.close();
}
function getInforSign(){
	try{
		var InforSign = document.getElementById('infor_sign').value ;
		var ObjSign = InforSign.split('!@~!');
	}catch(e){;}
	if(ObjSign.length > 1){
		document.getElementById('C_REGISTER_USERID').value = ObjSign[0]; 
	}
}
function btn_report_delete_onclick(p_checkbox_obj, p_hidden_obj, table, pk, p_url, _redirect){
	if (!checkbox_value_to_list(p_checkbox_obj,",")){
		alert("Ban chua chon doi tuong!");
	}
	else{
		if(confirm('Ban thuc su muon xoa doi tuong da chon?')){
			p_hidden_obj.value = checkbox_value_to_list(p_checkbox_obj,","); //Xac dinh cac phan tu duoc checked va luu vao bien hidden p_hidden_obj
			try{
				document.getElementById('hdn_table').value = table;
			}catch(e){;}
			try{
				document.getElementById('hdn_pk').value = pk;
			}catch(e){;}
			try{
				document.getElementById('hdn_redirect').value = _redirect;
			}catch(e){;}
			actionUrl(p_url);
		}
	}
}
function btn_update_onclick(obj_check, hdn_hidden, url){
	k =0;
	for(i =0; i < obj_check.length; i ++){
		if(obj_check[i].checked){
			hdn_hidden.value = obj_check[i].value;
			k = k +1;
		}
	}
	if(k == 0){
		alert("Ban chua chon doi tuong!");		
		return;
	}
	if(k > 1){
		alert("Ban chi duoc chon mot doi tuong!");		
		return;
	}else{
		actionUrl(url);
	}
}
function _showFluctuation(value){	
	try{
		if(value.value == "BAO_DUONG_SUA_CHUA"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "KIEM_KE_BAO_CAO"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "THANH_LY"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "THU_HOI"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "DIEU_CHUYEN"){
			document.getElementById('DIV_C_USE_INFO').style.display = "block";
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			document.getElementById('DIV_C_BEGIN_DATE').style.display = "block";
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "block";
			
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}else{
			document.getElementById('DIV_C_BEGIN_DATE').style.display = "none";
		}
	}catch(e){;}
	try{
		if(value.value == "BAN_TAISAN"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "TIEU_HUY"){
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
			document.getElementById('DIV_ASSET_PARENT').style.display = "none";
				document.getElementById('ASSET_PARENT').value = "";
		}
	}catch(e){;}
	try{
		if(value.value == "TACH_NHAP"){
			document.getElementById('DIV_ASSET_PARENT').style.display = "block";
			document.getElementById('DIV_C_VALUE').style.display = "block";
			document.getElementById('DIV_C_STATUS').style.display = "block";
			
			document.getElementById('DIV_C_DEPRECIATION_DATE').style.display = "none";
				document.getElementById('C_DEPRECIATION_DATE').value = "";
			document.getElementById('DIV_C_USE_INFO').style.display = "none";
				document.getElementById('C_USE_INFO').value = "";
				document.getElementById('infor_invite').value = "";
				document.getElementById('C_REGISTER_USERID').value = "";
		}
	}catch(e){;}
}
function _search(e){
	if(e.keyCode == 13){
		actionUrl('');
	}
}