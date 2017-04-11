<?php
/**
 * Y nghia: Class Xu ly tim kiem 
 */	
class search_searchController extends  Zend_Controller_Action {
	
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
		Zend_Loader::loadClass('search_modSearch');
		Zend_Loader::loadClass('assets_modAssets');
		
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
		$this->view->currentModulCode = "SEARCH";
		$this->view->modul = $this->_request->getParam('modul','');	
	
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
		$objSearch = new search_modSearch();
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
			
		$departmentId = $this->_request->getParam('arr_all_unit');
		$this->view->arr_all_unit = $departmentId;
		$staffId = $this->_request->getParam('arr_all_staff');
		$this->view->arr_all_staff = $staffId;
		$C_GROUP = $this->_request->getParam('C_GROUP');
		$this->view->C_GROUP = $C_GROUP;
		$C_TYPE = $this->_request->getParam('C_TYPE');
		$this->view->C_TYPE = $C_TYPE;
		
		$userId = $departmentId;
		if($staffId != ""){
			$userId = $staffId;
		}
		
		$textsearch = $this->_request->getParam('textsearch');
		$this->view->textsearch = $textsearch;
		
		$this->view->arrGroup = $objAsset->getPropertiesDocument('DM_NHOMTAISAN');
		$this->view->arrType = $objAsset->getPropertiesDocument('DM_LOAITAISAN');
		//$this->view->arrAllAsset = $objAsset->_getAll_Asset();
		//$this->view->arrComponent = $objSearch->_assetComponetGetAll($arr_all_asset);
		$arrPermission = $_SESSION['arrStaffPermission'];
		$permission = $arrPermission['QUYEN_SERVER'];
		$permission = ($permission == 1) ? 1 : 0;
		$arrAsset = $objSearch->_searchGetAll($userId, $C_GROUP, $C_TYPE, $textsearch, $piCurrentPage, $piNumRowOnPage, $permission);
		$this->view->arrAsset = $arrAsset;
		$psCurrentPage = $arrAsset[0]['TOTAL_RECORD'];				
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
}?>