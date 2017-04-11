<?php
	// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('../../../../library/'
			. PATH_SEPARATOR . '../../../../application/models/'
			. PATH_SEPARATOR . '../../../../config/');
			
	// Goi class Zend_Load
	include "../../../../library/Zend/Loader.php";	
	Zend_Loader::loadClass('Efy_Function_RecordFunctions');
	
	session_start();
	$sOwnerName   	= $_REQUEST['sOwnerName'];
	//echo $sOwnerName;
	$sOwnerId = Efy_Function_RecordFunctions::convertUnitNameListToUnitIdList($sOwnerName);
	//echo $sOwnerId;
	$arrUnit = array();
	if(!is_null($sOwnerName) && $sOwnerName != "")
		foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
			if($objUnit['parent_id'] == $sOwnerId){
				$arr1Unit = array("id"=>$objUnit['id'],"name"=>$objUnit['name'],"code"=>$objUnit['code'],"address"=>$objUnit['address'],"email"=>$objUnit['email'],"order"=>$objUnit['order']);
				array_push($arrUnit,$arr1Unit);
			}
		}
	$html = "<select id='C_UNIT' name='C_UNIT' option = 'true' style='width:95%;' class='textbox normal_label' xml_data='false' column_name='C_UNIT' onchange=\"actionUrl('')\">";
	$html .= "<option id='' name = '' value=''>-- Chọn phòng ban--</option>";
	foreach ($arrUnit as $objUnit)
		$html .= "<option id='' name = '' value='".$objUnit['id']."'>".$objUnit['name']."</option>";
	$html .= "</select>";
	echo $html;
?>