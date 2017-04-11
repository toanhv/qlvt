<?php
/**
 * Creater: Toanhv
 * Date: 1/5/2011
 * Dis: Quy trinh dang ky mua tai san 
 */
class shopping_shoppingController extends  Zend_Controller_Action {
	//Bien public luu quyen
	public $_publicPermission;
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
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		$efyLibUrlPath = $objConfig->_setLibUrlPath();
		$url_path_calendar = $efyLibUrlPath . 'efy-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
				
		//Goi lop modRecord
		Zend_Loader::loadClass('assets_modAssets');
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','record.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','ajax.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','jquery-1.4.3.min.js,jQuery.equalHeights.js,js_calendar.js',',','js'). Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js/LibSearch','actb_search.js,common_search.js',',','js');

		//Lay tra tri trong Cookie
		$sGetValueInCookie = Efy_Library::_getCookie("showHideMenu");
		
		//Neu chua ton tai thi khoi tao
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			$this->view->hideDisplayMeneLeft = 1;
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{//Da ton tai Cookie
			/*
			Lay gia tri trong Cookie, neu gia tri trong Cookie = 1 thi hien thi menu, truong hop = 0 thi an menu di
			*/
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}
			//Lay dia chi anh trong Cookie
			$this->view->ShowHideimageUrlPath = Efy_Library::_getCookie("ImageUrlPath");
		}
		$this->view->InforStaff = Efy_Publib_Library::_InforStaff();
		$this->view->currentModulCode = "shopping";	
		$action = $this->_request->getParam('modul','');
		$this->view->modul = $action;		
		if($action == 'register'){
			$this->view->currentModulCodeForLeft = 'left_shopping_register';
		}elseif ($action == 'approved'){
			$this->view->currentModulCodeForLeft = 'left_shopping_approved';
		}elseif ($action == 'wait'){
			$this->view->currentModulCodeForLeft = 'left_shopping_wait';
		}elseif ($action == 'refused'){
			$this->view->currentModulCodeForLeft = 'left_shopping_refused';
		}elseif ($action == 'buy'){
			$this->view->currentModulCodeForLeft = 'left_shopping_buy';
		}
		$psshowModalDialog = $this->_request->getParam('showModelDialog',0);
		$this->view->showModelDialog = $psshowModalDialog;
		if ($psshowModalDialog != 1){
			//Hien thi file template
			$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
			$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
	        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
		}		
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		$objAsset = new assets_modAssets();
		$ojbEfyInitConfig = new Efy_Init_Config();
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;	
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		$this->view->currentPage = $piCurrentPage; 	
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}	
		$this->view->numRowOnPage = $piNumRowOnPage; 
		$textsearch = $this->_request->getParam('textsearch','');	
		$this->view->textsearch = $textsearch;
		$leftMenu = '';
		$status = $this->_request->getParam('modul','');
		if($status == 'register'){
			$status = '';
			$leftMenu = 'register';
		}
		if($status == 'wait' || $status == 'buy'){
			$status = 'MS_CHO_DUYET';
		}
		if($status == 'approved'){
			$status = 'MS_DA_DUYET';
		}
		if($status == 'refused'){
			$status = 'MS_TU_CHOI';
		}		
		$arrAsset = $objAsset->_getAll($_SESSION['staff_id'], '', $_SESSION['OWNER_ID'], $textsearch, '', $piCurrentPage, $piNumRowOnPage, $status, $leftMenu);
		$this->view->arrAsset = $arrAsset;
		$psCurrentPage = $arrAsset[0]['TOTAL_RECORD'];
		if (sizeof($arrAsset) > 0){	
			$this->view->generateStringNumberPage = Efy_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;	
			$this->view->generateHtmlSelectBoxPage = Efy_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,'index');
		}		
	}
	public function addcomponentAction(){		
		$objAsset = new assets_modAssets();
		$objDocFun = new Efy_Function_RecordFunctions();
		$ojbEfyLib = new Efy_Library();
		$objFilter = new Zend_Filter();
		$ojbXmlLib = new Efy_Xml();
		$arrInput = $this->_request->getParams();
		$arrType = $objAsset->getPropertiesDocument('DM_LOAITAISAN');
		$this->view->arrType = $arrType;
		$arrGroup = $objAsset->getPropertiesDocument('DM_NHOMTAISAN');
		$this->view->arrGroup = $arrGroup;
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,90);
		$strUse = $objFilter->filter($arrInput['infor_invite']);
		$arrUse = explode("!@~!", $strUse);
		
		$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');	
		$this->view->hdn_asset_id = $hdn_asset_id;	
		$_SESSION['hdn_asset_id'] = $hdn_asset_id;
		if($hdn_asset_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			$this->view->arrAsset = $arrAsset;
		}
		
		$hdn_object_id = $objFilter->filter($arrInput['hdn_object_id']);	
		$this->view->hdn_object_id = $hdn_object_id;	
		if($hdn_object_id != ""){
			$arrResul = $objAsset->_assetGetSingle($hdn_object_id, "T_SUB_ASSETS", "PK_SUB_ASSETS");
			$this->view->arrResul = $arrResul;
		}
		
		$arrFileNameUpload = $ojbEfyLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','@!~!@');
		if($hdn_object_id != ""){
			$arFileAttach = $objAsset->DOC_GetAllDocumentFileAttach($hdn_object_id,'','T_SUB_ASSETS');
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,90);
		}
		
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
		//Tao xau XML luu CSDL
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				//Danh sach THE
				$psXmlTagList = $arrXmlTagValue[0];
				//Danh sach GIA TRI
				$psXmlValueList = $arrXmlTagValue[1];
				//Tao xau XML luu CSDL					
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString_forXML($psXmlTagList, $psXmlValueList);					
			}
		}
		
		if ($objFilter->filter($arrInput['C_CODE']) != ""){
			$arrParameter = array(	
							  'PK_SUB_ASSETS'				=>$hdn_object_id					    
							  ,'C_CODE'						=>$objFilter->filter($arrInput['C_CODE'])
							  ,'C_NAME'						=>$objFilter->filter($arrInput['C_NAME'])	
							  ,'C_TYPE'						=>$objFilter->filter($arrInput['C_TYPE'])
							  ,'C_GROUP'					=>$objFilter->filter($arrInput['C_GROUP'])							      
						      ,'C_INFO'						=>$objFilter->filter($arrInput['C_INFO'])
						      ,'C_VALUE'					=>$objFilter->filter($arrInput['C_VALUE'])
						      ,'C_BEGIN_DATE'				=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_BEGIN_DATE']))
						      ,'C_DEPRECIATION_DATE'		=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_DEPRECIATION_DATE']))
						      ,'C_STATUS'					=>$objFilter->filter($arrInput['C_STATUS'])
						      ,'STAFF_ID' 					=>$arrAsset[0]['C_REGISTER_USERID']
						      ,'STAFF_NAME'					=>Efy_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrAsset[0]['C_REGISTER_USERID'],'name')
						      ,'FK_FIXED_ASSETS'			=>$hdn_asset_id
						      ,'C_IMAGE'					=>$arrFileNameUpload
						      ,'C_XML_DATA'					=>$psXmlStringInDb
							);								
			$arrResult = "";	
			$arrResult = $objAsset->_assetComponetUpdate($arrParameter);
			$this->_redirect('assets/assets/viewrecord');	
		}			
	}		
	public function addAction(){		
		$objAsset = new assets_modAssets();
		$objDocFun = new Efy_Function_RecordFunctions();
		$ojbEfyLib = new Efy_Library();
		$objFilter = new Zend_Filter();
		$ojbXmlLib = new Efy_Xml();
		$arrInput = $this->_request->getParams();
		$arrType = $objAsset->getPropertiesDocument('DM_LOAITAISAN');
		$this->view->arrType = $arrType;
		$arrGroup = $objAsset->getPropertiesDocument('DM_NHOMTAISAN');
		$this->view->arrGroup = $arrGroup;
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;
		$arrWorks = $objAsset->getPropertiesDocument('DM_QT_MUASAM_TAISAN');
		$this->view->arrWorks = $arrWorks;		
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,90);
		$strUse = $objFilter->filter($arrInput['infor_invite']);
		$arrUse = explode("!@~!", $strUse);
		$userIdList = "";
		if($arrUse[1] != ""){
			$userIdList = $arrUse[1];
		}
		else if($arrUse[2] != "" && $arrUse[1] != ""){
			$userIdList = $arrUse[1] . ',' . $arrUse[2];
		}
		
		$hdn_object_id = $objFilter->filter($arrInput['hdn_object_id']);
		$this->view->hdn_object_id = $hdn_object_id;		
		if($hdn_object_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			$this->view->arrAsset = $arrAsset;
		}
		
		$arrFileNameUpload = $ojbEfyLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','@!~!@');
		if($hdn_object_id != ""){
			$arFileAttach = $objAsset->DOC_GetAllDocumentFileAttach($hdn_object_id,'','T_FIXED_ASSETS');
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,90);
		}
		
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
		//Tao xau XML luu CSDL
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				//Danh sach THE
				$psXmlTagList = $arrXmlTagValue[0];
				//Danh sach GIA TRI
				$psXmlValueList = $arrXmlTagValue[1];
				//Tao xau XML luu CSDL					
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString_forXML($psXmlTagList, $psXmlValueList);					
			}
		}		
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$this->view->unitId = $unitId;
		$work_type = $this->_request->getParam('work_type','');
		$approve_id = $this->_request->getParam('approve_id','');
		if($objFilter->filter($arrInput['C_NAME']) != ""){
			$arrParameter = array(	
								'PK_FIXED_ASSETS'			=>$hdn_object_id
							   ,'C_CODE'					=>''
							   ,'C_NAME'					=>$objFilter->filter($arrInput['C_NAME'])	
							   ,'C_TYPE'					=>$objFilter->filter($arrInput['C_TYPE'])
							   ,'C_GROUP'					=>$objFilter->filter($arrInput['C_GROUP'])
							   ,'C_INFO'					=>trim($objFilter->filter($arrInput['C_INFO']))
							   ,'C_VALUE'					=>0
							   ,'C_BEGIN_DATE'				=>''
							   ,'C_DEPRECIATION_DATE'		=>''
							   ,'C_STATUS'					=>''
							   ,'C_REGISTER_USERID'			=>''
							   ,'C_REGISTER_USERNAME'		=>''
							   ,'C_USE_INFO'				=>$_SESSION['staff_id']
							   ,'C_USE_INFO_NAME_LIST'		=>Efy_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name')
							   ,'C_IMAGE'					=>$arrFileNameUpload
							   ,'C_XML_DATA'				=>$psXmlStringInDb
							   ,'C_STATUS_ASSETS'			=>'MS_CHO_DUYET'
							   ,'C_DETAIL_ASSETS'			=>'0'
							   ,'WORKTYPE'					=>$work_type
							   ,'APPROVED_ID'				=>$approve_id
							   ,'APPROVED_NAME'				=>Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$approve_id,'name')
							   ,'REGISTER_DATE'				=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_REGISTER_DATE']))
							   ,'REGISTER_ID'				=>$_SESSION['staff_id']
							   ,'REGISTER_NAME'				=>Efy_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name')
							);								
			$arrResult = "";	
			$arrResult = $objAsset->_assetManageUpdate($arrParameter);
			if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');";	
					echo "</script>";
			}else {
			$this->_redirect('shopping/shopping/index/modul/register');	
			}
		}			
	}	
	public function editAction(){
		$this->view->bodyTitle = 'Thông tin người sử dụng tài sản';
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = "none"; 
		$this->view->hideDisplayMenuHeader = "none";
		$this->view->hideDisplayMenuFooter = "none";
		
		//Lay  thong tin trinh duyet
		$objBrower = new Efy_Publib_Browser();
		$brwName = $objBrower->Name;
		$this->view->brwName = $brwName ;
		
		//Tao doi tuong XML
		$ojbXmlLib = new Efy_Publib_Xml();	
		
		//Tao doi tuong Efy_lib
		$ojbEfyLib = new Efy_Library();
		
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();	
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		
		//Lay ten file XML
		$psFileName = $this->_request->getParam('hdn_xml_file','');
		//Neu khong ton tai file XML thi doc file XML mac dinh
		if($psFileName == "" || !is_file($psFileName)){
			$psFileName = Efy_Init_Config::_setXmlFileUrlPath(1) . "record/use/thong_tin_noi_nhan.xml";
		}
		
		$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_object/table_struct_of_update_form/update_row_list/update_row','update_object/update_formfield_list', $psXmlStr, $arrGetSingleList,true,true);
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');	
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				$psXmlTagList = $arrXmlTagValue[0];
				$psXmlValueList = $arrXmlTagValue[1];
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}
		$this->view->arrInput = $arrInput;
		$this->view->historyBack = 'add';
	}
	public function signAction(){
		$this->view->bodyTitle = 'Thông tin người sử dụng';
		$this->view->hideDisplayMeneLeft = "none"; 
		$this->view->hideDisplayMenuHeader = "none";
		$this->view->hideDisplayMenuFooter = "none";
		$objBrower = new Efy_Publib_Browser();
		$brwName = $objBrower->Name;
		$this->view->brwName = $brwName ;
		$ojbXmlLib = new Efy_Publib_Xml();	
		$ojbEfyLib = new Efy_Library();
		$filter = new Zend_Filter();	
		$arrInput = $this->_request->getParams();
		$psFileName = $this->_request->getParam('hdn_xml_file','');
		if($psFileName == "" || !is_file($psFileName)){
			$psFileName = Efy_Init_Config::_setXmlFileUrlPath(1) . "record/use/thong_tin_nguoi_ky.xml";
		}		
		$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_object/table_struct_of_update_form/update_row_list/update_row','update_object/update_formfield_list', $psXmlStr, $arrGetSingleList,true,true);
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');	
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				$psXmlTagList = $arrXmlTagValue[0];
				$psXmlValueList = $arrXmlTagValue[1];
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}
	}
	public function viewrecordAction(){
		$objAsset = new assets_modAssets();
		$ojbEfyInitConfig = new Efy_Init_Config();
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;	
		$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');	
		if($hdn_asset_id == "" || is_null($hdn_asset_id)){
			$hdn_asset_id = $_SESSION['hdn_asset_id'];
		}		
		$this->view->hdn_asset_id = $hdn_asset_id;	
		if($hdn_asset_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			$this->view->arrAsset = $arrAsset;
		}		
		$arrResult = $objAsset->_assetComponetGetAll($hdn_asset_id);
		$this->view->arrResult = $arrResult;	
		
		$arrInput = $this->_request->getParams();
		$this->view->action = $arrInput['action'];		
	}
	/**
	 * Creater: TOANHV
	 * Date: 01/05/2011
	 * Idea: Tao phuong thuc thuc hien xoa doi tuong danh muc
	 *
	 */
	public function deleteAction(){	
		$objModul = new assets_modAssets();		
		if($this->_request->isPost()){		
			$arrInput = $this->_request->getParams();
			$psModulName = $this->_request->getParam('hdn_function_modul',"");			
			$this->view->functionModul = $psModulName;	
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;	
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;	
			$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');	
			$this->view->hdn_asset_id = $hdn_asset_id;
			$iObjectIdList = $this->_request->getParam('hdn_object_id_list',"");			
			if ($iObjectIdList != ""){
				$hdn_table = $this->_request->getParam('hdn_table','');	
				$hdn_pk = $this->_request->getParam('hdn_pk','');	
				$psRetError = $objModul->_deleteAsset($iObjectIdList,$hdn_table,$hdn_pk);
				$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);	
				$_redirect = $this->_request->getParam('hdn_redirect','');	
				$this->_redirect('assets/assets/' . $_redirect);	
			}
		}		
	}
	public function approveAction(){
		$objAsset = new assets_modAssets();
		$ojbEfyLib = new Efy_Library();
		$ojbEfyInitConfig = new Efy_Init_Config();
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;	
		$arrWorks = $objAsset->getPropertiesDocument('DM_QT_MUASAM_TAISAN');
		$this->view->arrWorks = $arrWorks;	
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$this->view->unitId = $unitId;
		
		$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');	
		if($hdn_asset_id == "" || is_null($hdn_asset_id)){
			$hdn_asset_id = $_SESSION['hdn_asset_id'];
		}
		$_SESSION['hdn_asset_id'] = $hdn_asset_id;
		$this->view->hdn_asset_id = $hdn_asset_id;	
		if($hdn_asset_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			if(sizeof($arrAsset) < 1){
				$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_SUB_ASSETS", "PK_SUB_ASSETS");
			}
			$this->view->arrAsset = $arrAsset;
		}		
		$arrResult = $objAsset->_assetComponetGetAll($hdn_asset_id);
		$this->view->arrResult = $arrResult;
		$arrStaffRegister = $objAsset->_assetGetStaffRegister($hdn_asset_id);
		$this->view->arrStaffRegister = $arrStaffRegister;
		$appreve_id = $this->_request->getParam('approve_id','');
		$dilimiter = "!#!";
		$staffId = $_SESSION['staff_id'];
		$staffName = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$staffId,'name');
		$staff_id_list = $appreve_id . $dilimiter . $staffId;
		$staff_name_list = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$appreve_id,'name') . $dilimiter . $staffName;
		$status_list = "MS_CHO_DUYET" . $dilimiter . "MS_DA_DUYET";
		$detail_list = "0" . $dilimiter . "0";		
		$workType = $this->_request->getParam('work_type','');
		if($workType == 'TCD'){
			$status_list = "MS_TU_CHOI" . $dilimiter . "MS_DA_DUYET";
		}
		$content = $this->_request->getParam('content','');
		$date = $ojbEfyLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('C_DATE',''));
		if($content != "" && $appreve_id*1 > 0){				
			$arrResult = "";	
			$arrResult = $objAsset->_assetShoppingApprove($staff_id_list, $staff_name_list, $hdn_asset_id, $status_list, $detail_list, $workType, $content, $staffId, $staffName, $date, $dilimiter);
			if($arrResult){		
				$this->_redirect('shopping/shopping/index/modul/register');	
			}
		}			
	}	
	public function progressAction(){
		$objAsset = new assets_modAssets();
		$ojbEfyLib = new Efy_Library();
		$ojbEfyInitConfig = new Efy_Init_Config();
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;	
		$arrWorks = $objAsset->getPropertiesDocument('DM_QT_MUASAM_TAISAN');
		$this->view->arrWorks = $arrWorks;
		$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');
		if($hdn_asset_id == "" || is_null($hdn_asset_id)){
			$hdn_asset_id = $_SESSION['hdn_asset_id'];
		}
		$_SESSION['hdn_asset_id'] = $hdn_asset_id;
		$this->view->hdn_asset_id = $hdn_asset_id;	
		echo $hdn_asset_id;
		if($hdn_asset_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			if(sizeof($arrAsset) < 1){
				$arrAsset = $objAsset->_assetGetSingle($hdn_asset_id, "T_SUB_ASSETS", "PK_SUB_ASSETS");
			}
			$this->view->arrAsset = $arrAsset;
		}	
		if($hdn_asset_id != ""){
			$arrProgress = $objAsset->_progressGetAll($hdn_asset_id);
			$this->view->arrProgress = $arrProgress;
		}			
	}	
}?>