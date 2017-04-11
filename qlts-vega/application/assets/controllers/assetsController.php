<?php
/**
 * Enter description here...
 *
 */
class assets_assetsController extends  Zend_Controller_Action {
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
			//Efy_Library::_createCookie("showHideMenu",1);
			//Efy_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			//Mac dinh hien thi menu trai
			$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			//Hien thi anh dong menu trai
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
		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Efy_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "ASSETS";
		$this->view->currentModulCodeForLeft = 'INFOASSETS';
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
			
		$arrAsset = $objAsset->_getAll('', '', $_SESSION['OWNER_ID'], $textsearch, '', $piCurrentPage, $piNumRowOnPage);
		$this->view->arrAsset = $arrAsset;
		$psCurrentPage = $arrAsset[0]['TOTAL_RECORD'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		//$this->view->SelectDeselectAll = Efy_Library::_selectDeselectAll(sizeof($arrAsset), $psCurrentPage);
		if (sizeof($arrAsset) > 0){			
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Efy_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Efy_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,'index');
		}	
		$arrInput = $this->_request->getParams();
		$this->view->action = $arrInput['action'];	
	}
	public function fluctuationsAction(){
		$objAsset = new assets_modAssets();
		$objDocFun = new Efy_Function_RecordFunctions();
		$ojbEfyLib = new Efy_Library();
		$objFilter = new Zend_Filter();
		$arrInput = $this->_request->getParams();
		$arrWorks = $objAsset->getPropertiesDocument('DM_CV');
		$this->view->arrWorks = $arrWorks;
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;
		
		$this->view->arrAllAsset = $objAsset->_getAll_Asset();
		
		$this->view->C_WORK = $objFilter->filter($arrInput['C_WORK']);	
		
		$action = $this->_request->getParam('hdn_action','');	
		$this->view->action = $action;
		if ($action == "viewrecord"){
			$table = "T_SUB_ASSETS";
			$pk = "PK_SUB_ASSETS";			
		}else{
			$table = "T_FIXED_ASSETS";
			$pk = "PK_FIXED_ASSETS";	
		}	
		
		$hdn_object_id = $this->_request->getParam('hdn_object_id','');
		if($hdn_object_id == "" && isset($_SESSION['hdn_object_id'])){
			$hdn_object_id = $_SESSION['hdn_object_id'];
		}else{
			$_SESSION['hdn_object_id'] = $hdn_object_id;
		}
		$this->view->hdn_object_id = $hdn_object_id;			
		if($hdn_object_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, $table, $pk);
			if(sizeof($arrAsset) < 1){
				$table = "T_SUB_ASSETS";
				$pk = "PK_SUB_ASSETS";	
				$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, $table, $pk);
			}
		}	
		//$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$arrFluctuations = $objAsset->_assetFluctuationsGetall($hdn_object_id);		
			
		$strUse = $objFilter->filter($arrInput['infor_invite']);
		$arrUse = explode("!@~!", $strUse);
		
		$userIdList = "";
		if($arrUse[1] != ""){
			$userIdList = $arrUse[1];
		}
		else if($arrUse[2] != "" && $arrUse[1] != ""){
			$userIdList = $arrUse[1] . ',' . $arrUse[2];
		}
		
		$arrFileNameUpload = $ojbEfyLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','@!~!@');
		if($hdn_object_id != ""){
			$arFileAttach = $objAsset->DOC_GetAllDocumentFileAttach($hdn_object_id,'','T_VARIABLE_ASSETS');
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,90);
		}
		
		if($this->_request->getParam('C_CONTENT','') != ""){
			$arrParameter = array(	
							  'TABLE'						=>$table			    
							  ,'TABLE_PK'					=>$pk
							  ,'ASSETPK'					=>$hdn_object_id
							  ,'ASSETFK'					=>$objFilter->filter($arrInput['ASSET_PARENT'])
							  ,'C_VALUE'					=>$objFilter->filter($arrInput['C_VALUE'])	
							  ,'C_STATUS'					=>$objFilter->filter($arrInput['C_STATUS'])						      
						      ,'C_INFO'						=>trim($objFilter->filter($arrInput['C_INFO']))						    
						      ,'C_BEGIN_DATE'				=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_BEGIN_DATE']))
						      ,'C_DEPRECIATION_DATE'		=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_DEPRECIATION_DATE']))						      
						      ,'C_USE_INFO'					=>$userIdList
						      ,'C_USE_INFO_NAME_LIST'		=>$objFilter->filter($arrInput['C_USE_INFO'])
						      ,'STAFF_ID'					=>$objFilter->filter($arrInput['C_REGISTER_USERID'])
						      ,'STAFF_NAME'					=>Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['C_REGISTER_USERID']),'name')
						      ,'C_WORK'						=>$objFilter->filter($arrInput['C_WORK'])	
						      ,'C_CONTENT'					=>$objFilter->filter($arrInput['C_CONTENT'])
						      ,'FILENAME'					=>$arrFileNameUpload			      
							);								
			$arrResult = "";
			if($this->_request->getParam('C_CONTENT','') != ""){	
				$arrResult = $objAsset->_assetFluctuationsUpdate($arrParameter);
			}
			//$this->_redirect('assets/assets/fluctuations');	
			$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, $table, $pk);
			$arrFluctuations = $objAsset->_assetFluctuationsGetall($hdn_object_id);		
			$this->_request->setParam('C_CONTENT','');
		}	
		$this->view->arrFluctuations = $arrFluctuations;
		$this->view->arrAsset = $arrAsset;	
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
		if($hdn_object_id != '' && $userIdList == ''){
			$userIdList = $arrAsset[0]['USERS'];
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
		
		if ($objFilter->filter($arrInput['C_CODE']) != ""){
			$number = $objFilter->filter($arrInput['C_NUMBER']);
			if(intval($number) < 1)
				$number = 1;
			$arrParameter = array(	
								'PK_FIXED_ASSETS'			=>$hdn_object_id
							   ,'C_CODE'					=>$objFilter->filter($arrInput['C_CODE'])
							   ,'C_NAME'					=>$objFilter->filter($arrInput['C_NAME'])	
							   ,'C_TYPE'					=>$objFilter->filter($arrInput['C_TYPE'])
							   ,'C_GROUP'					=>$objFilter->filter($arrInput['C_GROUP'])
							   ,'C_INFO'					=>trim($objFilter->filter($arrInput['C_INFO']))
							   ,'C_VALUE'					=>$objFilter->filter($arrInput['C_VALUE'])
							   ,'C_BEGIN_DATE'				=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_BEGIN_DATE']))
							   ,'C_DEPRECIATION_DATE'		=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_DEPRECIATION_DATE']))
							   ,'C_STATUS'					=>$objFilter->filter($arrInput['C_STATUS'])
							   ,'C_REGISTER_USERID'			=>$objFilter->filter($arrInput['C_REGISTER_USERID'])
							   ,'C_REGISTER_USERNAME'		=>Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['C_REGISTER_USERID']),'name')
							   ,'C_USE_INFO'				=>$userIdList
							   ,'C_USE_INFO_NAME_LIST'		=>$objFilter->filter($arrInput['C_USE_INFO'])
							   ,'C_IMAGE'					=>$arrFileNameUpload
							   ,'C_XML_DATA'				=>$psXmlStringInDb
							   ,'C_STATUS_ASSETS'			=>''
							   ,'C_DETAIL_ASSETS'			=>0
							   ,'WORKTYPE'					=>''
							   ,'APPROVED_ID'				=>0
							   ,'APPROVED_NAME'				=>''
							   ,'REGISTER_DATE'				=>''
							   ,'REGISTER_ID'				=>0
							   ,'REGISTER_NAME'				=>''
							   ,'C_NUMBER'					=>$number
							);								
			$arrResult = "";	
			$arrResult = $objAsset->_assetManageUpdate($arrParameter);
			if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');";	
					echo "</script>";
			}else {
			$this->_redirect('assets/assets/index');	
			}
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
			$number = $objFilter->filter($arrInput['C_NUMBER']);
			if(intval($number) < 1)
				$number = 1;
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
							   ,'C_NUMBER'					=>$number
							);								
			$arrResult = "";	
			$arrResult = $objAsset->_assetComponetUpdate($arrParameter);
			$this->_redirect('assets/assets/viewrecord');	
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
		
		//var_dump($_SESSION['arr_all_staff']);
		$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_object/table_struct_of_update_form/update_row_list/update_row','update_object/update_formfield_list', $psXmlStr, $arrGetSingleList,true,true);
		//Lay danh sash THE va GIA TRI tuong ung mo ta chuoi XML, cau truc bien hdn_XmlTagValueList luu TagList|{*^*}|ValueList		
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
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}
		
		// Thuc hien tao mot mang de day vao view
		$this->view->arrInput = $arrInput;
	
		//Lay thong tin history back
		$this->view->historyBack = 'add';
	}
	public function signAction(){
		$this->view->bodyTitle = 'Thông tin người sử dụng';
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
			$psFileName = Efy_Init_Config::_setXmlFileUrlPath(1) . "record/use/thong_tin_nguoi_ky.xml";
		}
		
		$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_object/table_struct_of_update_form/update_row_list/update_row','update_object/update_formfield_list', $psXmlStr, $arrGetSingleList,true,true);
		
		//Lay danh sash THE va GIA TRI tuong ung mo ta chuoi XML, cau truc bien hdn_XmlTagValueList luu TagList|{*^*}|ValueList		
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
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}
		
		// Thuc hien tao mot mang de day vao view
		$this->view->arrInput = $arrInput;
	
		//Lay thong tin history back
		$this->view->historyBack = 'add';
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
	 * Date: 30/09/2010
	 * Idea: Tao phuong thuc thuc hien xoa doi tuong danh muc
	 *
	 */
	public function deleteAction(){		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objModul = new assets_modAssets();	
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){			
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
			//Gan modul chuc nang cho view	
			$psModulName = $this->_request->getParam('hdn_function_modul',"");			
			$this->view->functionModul = $psModulName;						
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;	

			$hdn_asset_id = $this->_request->getParam('hdn_asset_id','');	
			$this->view->hdn_asset_id = $hdn_asset_id;	
					
			//Lay Id doi tuong can xoa
			$iObjectIdList = $this->_request->getParam('hdn_object_id_list',"");			
			if ($iObjectIdList != ""){
				$hdn_table = $this->_request->getParam('hdn_table','');	
				$hdn_pk = $this->_request->getParam('hdn_pk','');	
				$psRetError = $objModul->_deleteAsset($iObjectIdList,$hdn_table,$hdn_pk);					
				//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
				$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
				//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
				$_SESSION['seArrParameter'] = $arrParaSet;
				//Luu bien ket qua
				$this->_request->setParams($arrParaSet);	
				$_redirect = $this->_request->getParam('hdn_redirect','');	
				//Tro ve trang index		
				$this->_redirect('assets/assets/' . $_redirect);	
			}
		}		
	}
}?>