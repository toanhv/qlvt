<?php
//'********************************************************************************
//'Muc dich : Kiem tra xem nguoi dang nhap hien thoi co phai la QUAN TRI cua mot ung dung hay khong
// Tham so:
//   - pApplicationID: ID cua ung dung 
//   - pStaffID: ID cua nguoi dang nhap
// Tra lai: 1-Neu la nguoi QUAN TRI, 0-neu khong phai quan tri
//'********************************************************************************
function user_is_app_admin($pApplicationID, $pStaffID){
	global $ado_conn;
	global $_ISA_DB_TYPE;
	$return_value = false;
	if (_is_sqlserver()){
		/*
		$cmd = mssql_init("User_IsAppAdmin", $conn);
		mssql_bind($cmd, "@p_application_id", $pApplicationID, SQLINT4);
		mssql_bind($cmd, "@p_staff_id", $pStaffID, SQLINT4);
		$result = mssql_execute($cmd);
		$rs = @mssql_fetch_array($result);
		@mssql_free_result($result);
		*/
		$v_sql_string = "Exec User_IsAppAdmin ";
		$v_sql_string.= "".$pApplicationID."";
		$v_sql_string.= ",".$pStaffID."";
		$rs = _adodb_exec_update_delete_sql($v_sql_string);
		if($rs['IS_APP_ADMIN']==1){
			$return_value = true;
		}	
	}
	return $return_value;
}	
/*Ham thuc hien lay ra "Chuoi" cac don vi muc tren cua can bo hien thoi (duong dan toi staff)
cHI CAN TIM TU THANG UNIT CUOI CUNG*/
function get_string_unit_level_higher($unit_id){
	$v_root_id = 44;//get_root_unit_id();
	//echo sizeof($arr_all_unit); exit;
	$v_list_unit_higher = ""; 
	while($unit_id <> $v_root_id){
		for($i = 0; $i < sizeof($arr_all_unit); $i++){
			if ($arr_all_unit[$i][0] == $unit_id){
				$v_list_unit_higher = $v_list_unit_higher.$arr_all_unit[$i][1].",";
				$unit_id =  $arr_all_unit[$i][1];
				break;
			}
		}
	}
	return $v_list_unit_higher;
}
/*
Ham thuc hien lay danh sach cac nhom ma chuc nang thuoc vao cac nhom do.
Luu y: Mang $arr_function_belong_group phai duoc sap xep truoc theo id cua chuc nang
*/
function get_group_list_of_function($p_function_id,$p_arr_function_belong_group){
	$v_found = false;
	$v_count = sizeof($p_arr_function_belong_group);
	$v_group_list = "";
	if ($v_count>0){
		for ($i=0; $i<$v_count; $i++){
			if ($p_arr_function_belong_group[$i][0] == $p_function_id){
				$v_found = true;
				if ($v_group_list == ""){
					$v_group_list = $p_arr_function_belong_group[$i][2];
				}else{
					$v_group_list = $v_group_list . "," . $p_arr_function_belong_group[$i][2];
				}
			}else{
				if ($v_found == true){
					break;
				}
			}
		}
	}
	return $v_group_list;
}
//Ham lay thuoc tinh disabled cua chk_function_id neu chuc nang do thuoc vao cac nhom da chon:
//$v_group_list: Danh sach cac nhom ma chuc nang thuoc vao
//$arr_all_group_for_enduser: Mang chuc cac nhom cua ung dung
function get_attr_disabled_for_function($p_group_list,$p_arr_all_group_for_enduser){
	$str_return = "";
	$arr_group_list = explode(",",$p_group_list);
	for ($i=0; $i<sizeof($arr_group_list); $i++){
		for ($j=0; $j<sizeof($p_arr_all_group_for_enduser); $j++){
			// Kiem tra neu no thuoc nhom va nhom dang duoc chon cho enduser
			if (($arr_group_list[$i] == $p_arr_all_group_for_enduser[$j][0]) and ($p_arr_all_group_for_enduser[$j][2] > 0)){
				$str_return = "disabled";
				break;
			}
		}
	}
	return $str_return;
}
//'********************************************************************************
//Ham _built_XML_tree
//Muc dich: Xay dung cau truc cay du lieu (TreeView) dong khong gioi han cap do. Gom 2 loai doi tuong la:
//	-ContainerOBJ: la dang container object chua cac object khac (Vi du: cac Phong ban, cac to chuc khac...)
//	-LeafOBJ: la dang leaf object cuoi cung chua cac thong tin, khong chua cac object khac (Vi du: cac nhan vien...)
//Phuong phap xay dung:
//	-Su dung dang du lieu XML de xay dung cau truc dong tu mot mang du lieu 2 chieu.
//	-Su dung phuong phap de quy de xay dung cau truc dong
//Input:
//	1. $arr_all_list: Mang chua cac ban ghi cua bang can xay dung cay du lieu. Mang co cau truc sau
//		-Phan tu thu 1 (index 0): ID cua Object
//		-Phan tu thu 2 (index 1): ID cua Object cha chua Object ID
//		-Phan tu thu 3 (index 2): Ma viet tat cua Object
//		-Phan tu thu 4 (index 3): Ten cua Object
//		-Phan tu thu 5 (index 4): Loai Object (0 - ContainerOBJ, 1 - LeafOBJ) 
//	2. $root_text: Dong text o goc cay thu muc
//	3. $exception_brand_id: ID cua doi tuong can hieu chinh
//		Duoc dung trong truong hop muon hieu chinh mot doi tuong dang ConrainerOBJ
//		Khi hieu chinh can chon lai ParentOBJ tu ModalDialog, khi do nhanh cua Object dang hieu chinh (ke ca cac ChildOBJ)
//		se khong xuat hien tren cay thu muc (tranh loi mot Object nay vua la cha dong thoi vua la con cua Object khac)
//		Gia tri cua ID nay: -1 neu tao tat ca cay, >0 neu tao thieu nhanh do.
//Output:
//	Tra ve mot chuoi dang XML cua cau truc cay du lieu
//'********************************************************************************
function built_XML_tree($arr_all_list,$exception_brand_id,$show_control,$opening_node_img_name,$closing_node_img_name,$leaf_node_img_name,$select_parent) {
	global $_ISA_IMAGE_URL_PATH;
	global $_MODAL_DIALOG_MODE;
	$strTop='<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$strTop.='<treeview title="Treeview" >'."\n";
	$strTop.="<custom-parameters>"."\n";
	$strTop.='<param name="shift-width" value="10"/>' . "\n";
	$strTop.='<param name="opening_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$opening_node_img_name.'"/>'."\n";
	$strTop.='<param name="closing_node_img_name" value="'.$_ISA_IMAGE_URL_PATH.$closing_node_img_name.'"/>'."\n";	
	$strTop.='<param name="leaf_node_img_name" value="'.$_ISA_IMAGE_URL_PATH . $leaf_node_img_name.'"/>' . "\n";
	$strTop.='<param name="modal_dialog_mode" value="'.$_MODAL_DIALOG_MODE.'"/>' . "\n";
	$strTop.='<param name="show_control" value="'.$show_control.'"/>'."\n";
	$strTop.='<param name="select_parent" value="'.$select_parent.'"/>'."\n";
	$strTop.="</custom-parameters>"."\n";	
	$strBottom= "</treeview>";
	$strXML="";
	$parent_id=NULL;
	//Lay ra mang chua cac Object muc ngoai cung 
	$v_count = sizeof($arr_all_list);
	$v_current_index = 0;
	for($i=0; $i<$v_count; $i++){
		if (strcasecmp(trim($arr_all_list[$i][1]),$parent_id)==0){
		//if($arr_all_list[$i][1]==$parent_id){
			$arr_current_list[$v_current_index][0]=$arr_all_list[$i][0];//PK
			$arr_current_list[$v_current_index][1]=$arr_all_list[$i][1];//FK
			$arr_current_list[$v_current_index][2]=htmlspecialchars($arr_all_list[$i][2]);//C_CODE
			$arr_current_list[$v_current_index][3]=htmlspecialchars($arr_all_list[$i][3]);//C_NAME
			$arr_current_list[$v_current_index][4]=$arr_all_list[$i][4];//C_TYPE
			$arr_current_list[$v_current_index][5]=$arr_all_list[$i][5];//C_LEVEL
			$v_current_index++;
		}
	}
	//Tao cac Node muc 2 cua treeview
	for ($i=0; $i<$v_current_index; $i++) {
		$v_current_id = $arr_current_list[$i][0];//PK
		$v_parent_id = 0;// id cua cha (FK =0)
		$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
		$v_current_name = htmlspecialchars($arr_current_list[$i][3]);	//C_NAME
		$v_current_type = $arr_current_list[$i][4];//C_TYPE
		$v_current_level = $arr_current_list[$i][5];//C_LEVEL
		//Kiem tra ID neu no khong la $exception_brand_id thi moi tao (tranh truong hop "vua la chau vua la cha" giua hai phan tu)
		if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
		//if($v_current_id!=$exception_brand_id){
			$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" type="'.$v_current_type.'" parent_id="'.$v_parent_id.'" level="'.$v_current_level.'" >'."\n";
			//Tao ra cac Node  con cua tree view
			$strXML.= built_child_node($arr_all_list,$v_current_level,$v_current_id,$exception_brand_id);
			$strXML.="</folder>"."\n";
		}
	}
	return  $strTop. $strXML . $strBottom;
	
}
//Xay dung cac node con cua mot doi tuong
function built_child_node($arr_all_list,$current_level,$parent_id,$exception_brand_id){
	$strXML="";
	$v_current_index = 0;
	$v_count = sizeof($arr_all_list);
	for($j=0;$j<$v_count;$j++){
	//Tim nhung thang con
		if ((strcasecmp(trim($arr_all_list[$j][1]),$parent_id)==0) && ($arr_all_list[$j][5]==$current_level+1)){
		//if (($arr_all_list[$j][1]==$parent_id) and ($arr_all_list[$j][5]>=$current_level)){
			$arr_current_list[$v_current_index][0]=$arr_all_list[$j][0];//PK
			$arr_current_list[$v_current_index][1]=$arr_all_list[$j][1];//FK
			$arr_current_list[$v_current_index][2]=htmlspecialchars($arr_all_list[$j][2]);//C_CODE
			$arr_current_list[$v_current_index][3]=htmlspecialchars($arr_all_list[$j][3]);//C_NAME			
			$arr_current_list[$v_current_index][4]=$arr_all_list[$j][4];//C_TYPE
			$arr_current_list[$v_current_index][5]=$arr_all_list[$j][5];//C_LEVEL
			$v_current_index++;
		}
	}
	//Truong hop mang $arr_current_list rong thi ket thuc de quy
	if($v_current_index <= 0){return;}
	for ($i=0; $i<$v_current_index; $i++){
		$v_current_id = $arr_current_list[$i][0];//PK
		$v_parent_id = $arr_current_list[$i][1];//FK	
		$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
		$v_current_name = htmlspecialchars($arr_current_list[$i][3]);//C_NAME
		$v_current_type = $arr_current_list[$i][4];//C_TYPE
		$v_current_level = $arr_current_list[$i][5];//C_LEVEL
		//Kiem tra ID neu no khong la $exception_brand_id thi moi tao
		if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
		//if($v_current_id!=$exception_brand_id){
			$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" type="'.$v_current_type.'" parent_id="'.$parent_id.'" level="'.$v_current_level.'" >'."\n";
			if ($v_current_type=='0'){
				$strXML.= built_child_node($arr_all_list,$v_current_level,$v_current_id,$exception_brand_id);
			}
			$strXML.="</folder>"."\n";
		}
	}
	return  $strXML;
}
?>
 