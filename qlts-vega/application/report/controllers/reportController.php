<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class report_reportController extends  Zend_Controller_Action {
	
	public function init(){
			
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		
		//Cau hinh cho Zend_layoutasdfsdfsd
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
		$this->view->NumberRowOnPage    = $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		//Goi lop Listxml_modProject
		Zend_Loader::loadClass('report_modReport');
		Zend_Loader::loadClass('assets_modAssets');
		//echo 'hoang van toan';
		//Tao doi tuong XML
		Zend_Loader::loadClass('Efy_Publib_Xml');
		
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();	
		
		
		$efyLibUrlPath = $objConfig->_setLibUrlPath();
		$url_path_calendar = $efyLibUrlPath . 'efy-calendar/';
		$this->view->urlCalendar = $url_path_calendar;	
		
		/* Dung de load file Js va css		/*/
		// Goi lop public
		Zend_Loader::loadClass(Efy_Publib_Library);
		$objPublicLibrary = new Efy_Library();
		
		// Load tat ca cac file Js va Css		
		//$this->view->LoadAllFileJsCss = $objPublicLibrary->_getAllFileJavaScriptCss('','efy-js','reports.js',',','js');
		/* Ket thuc*/
		
		//Lay tra tri trong Cookie
		$sGetValueInCookie = Efy_Library::_getCookie("showHideMenu");
		
		//Neu chua ton tai thi khoi tao
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Efy_Library::_createCookie("showHideMenu",1);
			Efy_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
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
		$this->view->currentModulCode = "REPORT";
		$this->view->modul = $this->_request->getParam('modul','');	
	
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
  	}
	/**
	 * Danh sach bao cao
	 *
	 */
	public function indexAction(){
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';

		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		//var_dump($arrInput);
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();

		// Tao doi tuong cho lop tren
		$objReport = new report_modReport();		
		$objAsset = new assets_modAssets();
		
		$arrStatus = $objAsset->getPropertiesDocument('DM_TINHTRANG');
		$this->view->arrStatus = $arrStatus;
		
		$arrType = $objAsset->getPropertiesDocument('DM_LOAITAISAN');
		$this->view->arrType = $arrType;
		
		$arrGroup = $objAsset->getPropertiesDocument('DM_NHOMTAISAN');
		$this->view->arrGroup = $arrGroup;

		//Tao doi tuong XML
		$objXmlLib = new Efy_Publib_Xml();
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList; 
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		$this->view->sExportType = $sExportType; 
		
		$begin_from_date = $this->_request->getParam('begin_from_date',"");
		$this->view->begin_from_date = $begin_from_date; 
		
		$begin_to_date = $this->_request->getParam('begin_to_date',"");
		$this->view->begin_to_date = $begin_to_date; 
		
		$status = $this->_request->getParam('C_STATUS',"");
		$this->view->status = $status; 
		
		//Lay ra cac bao cao
		$arrReporList = $objReport->getAllReportByReportType('DM_BAO_CAO');
		$this->view->arrReportList = $arrReporList;
	}
	/**
	 * Enter description here...
	 *
	 */
	public function viewreportAction(){	
		$ojbEfyLib = new Efy_Library();
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		$this->view->sExportType = $sExportType; 
		if($sExportType ==''){
			$sExportType = 14;
		}	

		$begin_from_date = $this->_request->getParam('begin_from_date',"");
		$this->view->begin_from_date = $begin_from_date;	
		
		$begin_to_date = $this->_request->getParam('begin_to_date',"");
		$this->view->begin_to_date = $begin_to_date;
		
		$status = "";	
		if($sCodeList == "BC.001"){
			$status = $this->_request->getParam('C_STATUS',"");
			$this->view->status = $status;		
		}
		if($sCodeList == "BC.003"){
			$status = $this->_request->getParam('C_UNIT',"");
			$this->view->unitid = $status;		
		}
		$type = "";
		$type = $this->_request->getParam('C_TYPE',"");
		$this->view->type = $type;	
		//echo 'Type: ' . $type; exit;
		$group = "";
		$group = $this->_request->getParam('C_GROUP',"");
		$this->view->group = $group;	
		
		// Duong dan file rpt
		$path = $_SERVER['DOCUMENT_ROOT'] . Efy_Init_Config::_setWebSitePath() . "rpt/";
		$my_report = str_replace("/", "\\", $path) . $sCodeList . ".rpt";
		
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp = new COM($COM_Object) or die("Unable to Create Object");
		
		$creport = $crapp->OpenReport($my_report,1);	
		//exit;
		//- Set database logon info - must have
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		
		//- DiscardSavedData - to refresh then read records
		//$creport->DiscardSavedData;		
		$creport->ReadRecords();
		// Truyen tham so vao
		$z = $creport->ParameterFields(1)->SetCurrentValue($status);
		$z = $creport->ParameterFields(2)->SetCurrentValue($ojbEfyLib->_ddmmyyyyToYYyymmdd($begin_from_date));
		$z = $creport->ParameterFields(3)->SetCurrentValue($ojbEfyLib->_ddmmyyyyToYYyymmdd($begin_to_date));
		$z = $creport->ParameterFields(4)->SetCurrentValue($type);
		$z = $creport->ParameterFields(5)->SetCurrentValue($group);
		
		
		$fileNameReport = $sCodeList;
		// Dinh dang file report ket xuat
		$report_file = $fileNameReport . mt_rand(1,1000000) . '.doc';
		if ($sExportType == 31){
			$report_file = $fileNameReport . mt_rand(1,1000000) . '.pdf';
		}
		elseif ($sExportType == 14){
			$report_file = $fileNameReport . mt_rand(1,1000000) . '.doc';
		}
		elseif ($sExportType == 29){
			$report_file = $fileNameReport . mt_rand(1,1000000) . '.xls';
		}		 
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT'] . Efy_Init_Config::_setWebSitePath()) . "public\\" . $report_file;
		//echo $my_report_file; exit;
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= $sExportType; // Type file
		$creport->Export(FALSE);		
		// doc file pdf len trinh duyet		
		//$this->_redirect($my_report_file);  
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'] . Efy_Init_Config::_setWebSitePath() . 'public/' . $report_file;
		$this->view->my_report_file = $my_report_file; 	
		$this->view->fileName = $report_file;	 
	}
}?>