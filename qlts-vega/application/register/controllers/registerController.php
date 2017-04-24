<?php
/**
 * Nguoi tao: phongtd
 * Y nghia: Class Xu module MUON TRA TAI SAN
 */	
class register_registerController extends  Zend_Controller_Action {
	
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
		//Lay cac gia tri const
		$ojbEfyInitConfig = new Efy_Init_Config();
		$this->view->arrConst =	$ojbEfyInitConfig->_setProjectPublicConst();
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		
		//Goi lop modReceived
		Zend_Loader::loadClass('register_modRegister');
		Zend_Loader::loadClass('assets_modAssets');
		Zend_Loader::loadClass('search_modSearch');
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','record.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','ajax.js',',','js') . Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','jquery-1.4.3.min.js',',','js'). Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js/LibSearch','actb_search.js,common_search.js',',','js');
		
		//Lay tra tri trong Cookie
		//$sGetValueInCookie = Efy_Library::_getCookie("showHideMenu");
		
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
			//$this->view->ShowHideimageUrlPath = Efy_Library::_getCookie("ImageUrlPath");
		}
		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Efy_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code		
		$this->view->currentModulCode = "register";			
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;
		
		if($sStatus == 'register'){
			$this->view->currentModulCodeForLeft = 'left_register_assets';
		}elseif ($sStatus == 'approved'){
			$this->view->currentModulCodeForLeft = 'left_register_approved';
		}elseif ($sStatus == 'wait'){
			$this->view->currentModulCodeForLeft = 'left_register_wait';
		}elseif ($sStatus == 'refused'){
			$this->view->currentModulCodeForLeft = 'left_register_refused';
		}
		
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		$objAsset = new assets_modAssets();
		$objRegister = new register_modRegister();
		$ojbEfyInitConfig = new Efy_Init_Config();
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
					
		//Lay trang thai
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;
		if($sStatus == "register"){
			//$sStatus = "DANG_KY_MUON";
			$sStatus = "";
		}
		if($sStatus == "wait"){
			$sStatus = "MT_CHO_DUYET";
		}
		if($sStatus == "approved"){
			$sStatus = "MT_DA_DUYET";
		}
		if($sStatus == "refused"){
			$sStatus = "MT_BI_TU_CHOI";
		}
		if(empty($sStatus)){
			$sStaffId = "";
		}else{
			$sStaffId = $_SESSION['staff_id'];
		}
		//echo '$sStatus='.$sStatus;
		
		$arrAsset = $objRegister->_registerGetAll($sStaffId,$sStatus,$textsearch, $piCurrentPage, $piNumRowOnPage);
		
		$this->view->arrAsset = $arrAsset;
		$psCurrentPage = $arrResul[0]['TOTAL_RECORD'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (sizeof($arrAsset) > 0){			
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Efy_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Efy_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,'index');
		}	
		$arrInput = $this->_request->getParams();
		$this->view->action = $arrInput['action'];
	}
	public function viewAction(){
		$objAsset = new assets_modAssets();
		$objSearch = new search_modSearch();
		$objRegister = new register_modRegister();
		$ojbEfyInitConfig = new Efy_Init_Config();
		$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;	
		
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
		
		$this->view->arrUser = $objSearch->_assetUserGetAll($hdn_asset_id);	
		$this->view->arrUserApproved = $objRegister->_approvedUserGetSingle($hdn_asset_id);		
		
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
		
				
		$hdn_object_id = $this->_request->getParam('hdn_object_id','');				
		$this->view->hdn_object_id = $hdn_object_id;			
		
		if($hdn_object_id != ""){
			$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, "T_FIXED_ASSETS", "PK_FIXED_ASSETS");
			if(sizeof($arrAsset) < 1){
				$arrAsset = $objAsset->_assetGetSingle($hdn_object_id, "T_SUB_ASSETS", "PK_SUB_ASSETS");
			}
			$this->view->arrAsset = $arrAsset;
		}	
		//$unitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$arrFluctuations = $objAsset->_assetFluctuationsGetall($hdn_object_id);
		$this->view->arrFluctuations = $arrFluctuations;
	}
	public function registerAction(){
		$objAsset = new assets_modAssets();
		$objRegister = new register_modRegister();
		$objDocFun = new Efy_Function_RecordFunctions();
		$ojbEfyLib = new Efy_Library();
		$objFilter = new Zend_Filter();
		$arrInput = $this->_request->getParams();
		
		//Ten can bo
		$sStaffName = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Chuc vu
		$sPositionName = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
		$sStaffPositionName = $sPositionName." - " . $sStaffName;
		$this->view->sStaffPositionName = $sStaffPositionName;
		
		//Phong ban
		$iUnitId =  $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sUnitName =  $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		$this->view->sUnitName = $sUnitName;
		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;
		
		$arrWorks = $objAsset->getPropertiesDocument('DM_QT_MUONTRA_TAISAN');
		$this->view->arrWorks = $arrWorks;
		
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
		$sWorkType = $this->_request->getParam('work_type','');
		$sApproveId = $this->_request->getParam('approve_id','');
		$sApproveName = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$sApproveId,'name');
		$sApprovePosition = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$sApproveId,'position_code');
		$sApprovePositionName = $sApprovePosition." - " . $sApproveName;
		
		if(!empty($sApproveId)){
			$arrParameter = array(	
							  'FK_ASSETS'					=>$hdn_object_id			    
							  ,'FK_STAFF'					=>$_SESSION['staff_id']
							  ,'C_STAFF_NAME'				=>$sStaffPositionName 
							  ,'STAFF_APPROVE_ID'			=>$sApproveId
							  ,'STAFF_APPROVE_NAME'			=>$sApprovePositionName
							  ,'C_STATUS'					=>'MT_CHO_DUYET'
							  ,'C_DETAIL_STATUS'			=>1	
							  ,'C_WORK_TYPE'				=>$sWorkType						      
						      ,'C_CONTENT'					=>trim($objFilter->filter($arrInput['C_CONTENT']))						    
						      ,'C_DATE'						=>$ojbEfyLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_DATE']))	
							);								
			$arrResult = "";
			if(!empty($sApproveId)){	
				$arrResult = $objRegister->_assetRegisterUpdate($arrParameter);
				$this->_redirect('register/register/index/status/register');
			}
		}	
		$this->view->arrAsset = $arrAsset;	
	}
	public function approvedAction(){		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objModul = new register_modRegister();	
		$ojbEfyLib = new Efy_Library();
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
			echo '$hdn_asset_id='.$hdn_asset_id.'<br>';					
			//Lay Id doi tuong 
			$hdn_object_id = $this->_request->getParam('hdn_object_id',"");
			echo '$hdn_object_id='.$hdn_object_id.'<br>';					
			$approved_status = $this->_request->getParam('hdn_approved_status',"");
			
			//Ten can bo
			$sStaffName = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
			//Chuc vu
			$sPositionName = $ojbEfyLib->_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
			$sStaffPositionName = $sPositionName." - " . $sStaffName;
		
			if ($hdn_object_id != ""){	
				$psRetError = $objModul->_approvedAsset($hdn_object_id,$hdn_asset_id,$_SESSION['staff_id'],$sStaffPositionName,$approved_status);					
				//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
				$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
				//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
				$_SESSION['seArrParameter'] = $arrParaSet;
				//Luu bien ket qua
				$this->_request->setParams($arrParaSet);		
				//Tro ve trang index		
				$this->_redirect('register/register/view?' . $hdn_asset_id);	
			}
		}		
	}
}?>