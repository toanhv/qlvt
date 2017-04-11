<?php

/**
 * Creater : HUNGVM
 * Date : 15/09/2009
 * Idea : Tao lop xu ly thong tin NSD
 */
class permission_userController extends  Zend_Controller_Action {
	public function init(){
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		//Cau hinh cho Zend_layout
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();
		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		
		//Goi lop modHandle
		//Zend_Loader::loadClass('Handle_modHandle');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','jsUser.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','ajax.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js/Autocomplete','actb_search.js,common_search.js',',','js');
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		//$this->view->InforStaff = Efy_Publib_Library::_InforStaff();
		
		//Dinh nghia Package "QUYEN"
		$this->view->currentModulCode = "LIST";				
		//Modul chuc nang				
		$this->view->currentModulCodeForLeft = "LIST-USER";								
		//echo Efy_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']) . '<br>';
		//var_dump($_SESSION['arrStaffPermission']);
		
		//echo $_SESSION['INFORMATION_STAFF_LOGIN']  . '<br>';
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer       		
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 16/09/2009
	 * Idea : Hien thin danh sach NSD cho ban quyen
	 *
	 */
	public function indexAction(){	

		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH NGƯỜI SỬ DỤNG";
		//
		$arrInput = $this->_request->getParams();
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		//Thu vien dung chung
		$ojbEfyLib = new Efy_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
				
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View
		// Goi ham search de hien thi ra Complete Textbox
		//var_dump($_SESSION['SesGetAllOwner']);
		
		//$this->view->search_textselectbox_unit_name = Efy_Function_ReportFunctions::doc_search_ajax($_SESSION['SesGetAllOwner'],"id","name","C_OWNER","hdn_owner",1);
		  $this->view->search_textselectbox_unit_name = Efy_Function_RecordFunctions::doc_search_ajax($_SESSION['SesGetAllOwner'],"id","name","C_OWNER","hdn_owner",1);
		//var_dump($this->view->search_textselectbox_unit_name);		  		  		 		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $objFilter->filter($arrInput['cbo_nuber_record_page']);		
		if ($piNumRowOnPage <= $this->view->NumberRowOnPage){
			$piNumRowOnPage = $this->view->NumberRowOnPage;
		}		
		$sOwnerName 	= $this->_request->getParam('C_OWNER','');
		//echo $sOwnerName;
		$sUnitId		= $this->_request->getParam('C_UNIT','');
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sUnitId			= $Parameter['idphongban'];
			$sOwnerName			= $Parameter['tendonvi'];
			unset($_SESSION['seArrParameter']);
		}
		$sOwnerId 		= Efy_Function_RecordFunctions::convertUnitNameListToUnitIdList($sOwnerName);
		//echo $sOwnerId; exit;
		//Mang luu danh sach can bo tim duoc
		$arrAllStaff = array();
		//Kiem tra neu khong nhap phong ban thi lay tat ca cac can bo cua don vi duoc chon
		//-> Lay danh sach phong ban cua don vi duoc chon
		if ($sUnitId == '' || is_null($sUnitId)) { 
			$arrUnit = array();
			foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
				if($objUnit['parent_id'] == $sOwnerId){
					array_push($arrUnit,$objUnit['id']);
				}
			}
			//Truong hop don vi co phong ban
			if (sizeof($arrUnit)) {
				//->Lay danh sach can bo nam trong cac don vi tim duoc
				foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
					if(in_array($objStaff['unit_id'],$arrUnit)){
						$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
						array_push($arrAllStaff,$arrAllStaff1);
					}
				}
			//Truong hop don vi khong co phong ban
			}else {
				if(!is_null($sOwnerId) && $sOwnerId != "")
					//->Lay danh sach can bo nam trong cac don vi tim duoc
					foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
						if($objStaff['unit_id'] == $sOwnerId){
							$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
							array_push($arrAllStaff,$arrAllStaff1);
						}
					}
			}
			
		}else{
			foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
				if($objStaff['unit_id'] == $sUnitId){
					$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
					array_push($arrAllStaff,$arrAllStaff1);
				}
			}
		}
		//Day tieu chi tim kiem ra view
		$this->view->sOwnerName = $sOwnerName;
		$this->view->sUnitId 	= $sUnitId;
		$this->view->arrAllStaff = $arrAllStaff;
		$arrUnit = array();
		if(!is_null($sOwnerId) && $sOwnerId != "")
			foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
				if($objUnit['parent_id'] == $sOwnerId){
					$arr1Unit = array("id"=>$objUnit['id'],"name"=>$objUnit['name'],"code"=>$objUnit['code'],"address"=>$objUnit['address'],"email"=>$objUnit['email'],"order"=>$objUnit['order']);
					array_push($arrUnit,$arr1Unit);
				}
			}
		$this->view->arrUnit = $arrUnit;
	}
	/**
	 * Creater: HUNGVM
	 * Date: 16/09/2009
	 * Idea: Thuc hien phuong thuc Action cap nhat quyen NSD
	 */
	public function addAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT QUYỀN NGƯỜI SỬ DỤNG';
		
		//Tao doi tuong Efy_lib
		$ojbEfyLib = new Efy_Library();
		
		//Goi lop modHandle
		Zend_Loader::loadClass('permission_modUserPermission');
				
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){				
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
			
			$objPermission = new permission_modUserPermission();
			$arrUserPermission = $objPermission->PermissionGroupGetAll('DM_NHOM_QUYEN','quyen_thuocnhom');
			//var_dump($arrUserPermission);
			
			$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");	
			$this->view->filterXmlTagList = $psFilterXmlTagList;
			$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
			$this->view->filterXmlValueList = $psFilterXmlValueList;

			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;

			//Lay danh sach ID can bo duoc chon
			$iStaffIdList = $this->_request->getParam('hdn_object_id_list',"");
			$this->view->iStaffIdList = $iStaffIdList;				
			$this->view->staffInformation = $this->getStaffInformation($iStaffIdList);
			//Quyen luu trong DB (Da ban quyen)
			$arrPermissionInDB = $objPermission->StaffPermissionGetAll($iStaffIdList);
			//Lay danh muc quyen chuc nang			
			$arrPermissionObject = $objPermission->objectOfListtypeGetAll('DM_QUYEN','');
			//Hien thi nhom quyen NSD
			$this->view->displayUserPermission = $this->generatePermissionGroup($arrUserPermission,$arrPermissionInDB,$arrPermissionObject);
			
			//Lay danh sach ID quyen
			$userPermissionIdList = $this->_request->getParam('hdn_permission_id_list',"");
			echo $userPermissionIdList;
			$this->view->userPermissionIdList = $userPermissionIdList;		
			//Mang luu tham so update in database
			$arrParameter = array(	'PK_PERMISSION'						=>'',								
									'FK_STAFF_ID_LIST'					=>$iStaffIdList,
									'FK_PERMISSION_ID_LIST'				=>$userPermissionIdList,
									'CONST_LIST_DELIMITOR'				=>"!~~!"
							);
							
			$arrResult = "";		
			if (isset($_REQUEST['btn_update']) || $this->_request->getParam('hdn_is_update',"")){		
				$arrResult = $objPermission->StaffPermissionUpdate($arrParameter);							
				// Neu add khong thanh cong			
				if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');\n";				
					echo "</script>";
				}else{
						//Tro ve trang index						
					$this->_redirect('/permission/user/index/');						
				}
			}else {
				//Lay gia tri tim kiem tren form
				$sOwnerName 	= $this->_request->getParam('C_OWNER','');
				$sUnitId		= $this->_request->getParam('C_UNIT','');
				$arrParaSet = array("idphongban"=>$sUnitId, "tendonvi"=>$sOwnerName);
				$_SESSION['seArrParameter'] = $arrParaSet;
			}
		}
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 17/09/2009
	 * Idea : Tao phuong thuc hien thi thong tin NSD da chon de ban quyen
	 *
	 * @param unknown_type $sStaffIdList
	 */
	private function getStaffInformation($sStaffIdList){
		//Sinh Header		
		$sStrHtml = "<table cellpadding='0' cellspacing='0' border='0' width='100%' class='list_table2' align='center'>";
			
		$delimitor = "!~~!";
		//Hien thi cac cot cua bang hien thi du lieu
		$strHeaer = Efy_Library::_generateHeaderTable("5%" . $delimitor . "40%" . $delimitor . "20%" . $delimitor . "35%"
											,"TT" . $delimitor . "Tên người sử dụng" . $delimitor . "Chức vụ" . $delimitor . "Phòng ban"
											,$delimitor);
		$StrHeader = explode("!~~!",$strHeaer);
		$sStrHtml .= $StrHeader[0];
		$sStrHtml .= $StrHeader[1];//Hien thi <col width = 'xx'><...
				
		//Kieu style
		$v_current_style_name = "round_row";	

		if ($sStaffIdList != ""){
			$arrStaff = explode($delimitor,$sStaffIdList);
			for ($index = 0;$index < sizeof($arrStaff);$index++){
				// en can bo
				$sStaffName = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'name') . "&nbsp;";				
				//Chuc vu
				$sPositionName = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'position_name') . "&nbsp;";							
				//----------------Lay thong tin Phong ban----------------------------
				//Id
				$sUnitId = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'unit_id');
				//Name
				$sUnitName = Efy_Library::_getItemAttrById($_SESSION['arr_all_unit_keep'],$sUnitId,'name') . "&nbsp;";				
				//-------------------------------------------------------------------
				// su dung style
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";					
				}				
				$sStrHtml .= "<tr class='$v_current_style_name'>";					
				$sStrHtml .= "<td align='center' class='normal_label'>" . ($index+1) . "</td>";					
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sStaffName . "</td>";
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sPositionName . "</td>";
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sUnitName . "</td>";
				$sStrHtml .= "</tr>";
			}
		}		
		$sStrHtml .= "</table>";
		return $sStrHtml;
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 20/09/2009
	 * Idea : Tao phuong thuc hien thi nhom quyen
	 *
	 * @param $arrPermission : Mang luu toan bo nhom quyen cua NSD
	 * @param $arrPermissionInDB : Mang luu thong tin quyen cua NSD da duoc ban
	 * @return unknown
	 */
	private function generatePermissionGroup($arrPermission = array(), $arrPermissionInDB = array(), $arrPermissionObject = array()){
		
		$sPermissionList = $arrPermissionInDB[0]['C_PERMISSION_LIST'];
		$v_chk_enduser_id_onclick = "onchildclick(this)";
		//echo $sPermissionList;
		//Sinh Header		
		$sStrHtml = "<table cellpadding='0' cellspacing='0' border='0' width='100%' class='list_table2' align='center'>";
		$delimitor = "!~~!";
		//Hien thi cac cot cua bang hien thi du lieu
		$strHeaer = Efy_Library::_generateHeaderTable("5%" . $delimitor . "10%" . $delimitor . "85%"
											,"#" . $delimitor . "" . $delimitor . "Nhóm quyền"
											,$delimitor);
		$StrHeader = explode("!~~!",$strHeaer);
		$sStrHtml .= $StrHeader[0];
		//$sStrHtml .= $StrHeader[1];//Hien thi <col width = 'xx'><...

		$icount = sizeof($arrPermission);
		if ($icount >0){
			$sOldGroupName = "";
			for ($index = 0;$index<$icount; $index++){
				//Ma nhom
				$sGroupCode = $arrPermission[$index]['C_CODE'];
				//Ten nhom
				$sGroupName = $arrPermission[$index]['C_NAME'];
				//Nhom cu
				
				//Ma Quyen chuc nang
				$sFunctionPermission = $arrPermission[$index]['XML_TAG_IN_DB'];
				//Chuyen doi xau => Mang mot chieu
				if (trim($sFunctionPermission) != ""){
					$arrFunctionPermission = explode(",",$sFunctionPermission);
				}
				//Dia chi URL khi thuc hien onclick tren mot dong
				$sOnclick = "item_onclick('"  . $v_item_id . "')";
				//Onclick tren anh cua doi tuong
				$v_img_url_onclick = "show_enduser_on_unit(this)";
				//
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				if ($sGroupName != $sOldGroupName){					
					$sStrHtml .= "<tr style='background:#DBDEE9'>";					
					$sStrHtml .= "<td align='center'><input type='checkbox' name='chk_item_id' value='" . $sGroupCode . "' onClick=\"" . "checkElementsGroup(document.getElementsByName('chk_item_id'),this);" . "\"></td>";					
					$sStrHtml .= "<td align='center'><img unit='$sGroupCode' id='img_permission' src='" . $this->_request->getBaseUrl() . "/public/" . "images/open.gif" . "' class='normal_image' status='on' onClick=\"" . $v_img_url_onclick ."\">&nbsp;</td>";
					$sStrHtml .= "<td align='left' class='normal_label'><b>" . $sGroupName . "&nbsp;</b></td>";
					$sStrHtml .= "</tr>";
					$sOldGroupName = $sGroupName;
				}
				//Hien thi quyen chuc nang cua nhom
			
				for ($j = 0; $j<sizeof($arrFunctionPermission);$j++){
					// su dung style
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";					
					}		
					//Ma quyen chuc nang
					$sCode = $arrFunctionPermission[$j];
					$sStrHtml .= "<tr unit='$sGroupCode' id='tr_permission' name='tr_permission' value='" . $sCode . "' class='" . $v_current_style_name. "'>";
					$sStrHtml .= "<td>&nbsp;&nbsp;</td>";
					$sStrHtml .= "<td align='center'>";
					$schecked = "";					
					if (Efy_Library::_listHaveElement($sPermissionList,$sCode. "!*~*!" . $sGroupCode,'!~~!')){
						$schecked = "checked";
					}
					$sStrHtml .= "<input type='checkbox' $schecked name='chk_item_id' parent='" . $sGroupCode .  "' value='" . $sCode . "!*~*!" . $sGroupCode . "' onClick=\"" . $v_chk_enduser_id_onclick . "\"></td>";
					$sStrHtml .= "<td align='left' onclick=\"" . $sOnclick . "\">";
					$sStrHtml .= "&nbsp;" . $arrPermissionObject[$sCode] . "&nbsp;" . "</td></tr>";					
				}	
			}	
		}
		if ($v_current_style_name == "odd_row"){
			$v_next_style_name = "round_row";
		}else{
			$v_next_style_name = "odd_row";
		}
		$sStrHtml .="</table>";
		return $sStrHtml;
	}
}
?>