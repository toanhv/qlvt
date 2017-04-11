<?php

/**
 * Creater : phongtd
 * Date : 19/10/2010
 * Idea : Class Xu ly thong thong doi tuong danh muc
 */
class listxml_ListController extends  Zend_Controller_Action {
		
	//Phuong thuc init()
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
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		
		//Duong dan file JS xu ly modul
		//$this->view->baseJavaUrl = "efy-js/jsList.js";
		
		//Goi lop Listxml_modList
		Zend_Loader::loadClass('Listxml_modList');
		
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Efy_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','list/jsList.js,jquery-1.4.3.js,jQuery.equalHeights.js',',','js');										
		/* Ket thuc*/
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";
		$this->view->currentModulCodeForLeft = "LIST";		
		
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
			
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    	//Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    		//Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	//Hien thi footer
        // Goi ham kiem tra user login
		//Efy_Library::_isLogin($objConfig->_setUserLoginUrl(),$objConfig->_setAppCode(), $_SESSION['staff_id_temp'],$objConfig->_setTimeOut());
	}
		
	/**
	 * Creater: phongtd
	 * Date:
	 * Idea: Thuc hien phuong thuc Action hien thi danh sach doi tuong danh muc
	 */
	public function indexAction(){
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'DANH SÁCH ĐỐI TƯỢNG DANH MỤC';
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
	
		$objFilter = new Zend_Filter();	
		$objList = new Listxml_modList();
		$ojbXmlLib = new Efy_Publib_Xml();	
		
		//Lay danh sach cac THE mo ta tieu tri loc
		$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");		
		$this->view->filterXmlTagList = $psFilterXmlTagList;
		//Lay danh sach cac gia tri tuong ung mo ta tieu tri loc
		$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $psFilterXmlValueList;		
		
		// Lay cac tham param de truyen vao phuong thuc getAllListType : dung cho search
		$iStatus = '';
		$sListTypeName ='';
		$sOwnerCode = $_SESSION['OWNER_CODE'];		
		//Lay thong tin loai danh muc de hien thi selectbox "Loai danh muc"
		$arrListType = $objList->getAllListType($iStatus, $sListTypeName, $sOwnerCode);			
		
		//Tao mang mot chieu hien thi selecbox "Loai danh muc"		
		$this->view->arrAllListType = Efy_Library::_createOneDimensionArray($arrListType,'PK_LISTTYPE', 'C_NAME');
		
		//Dat gia tri ban dau cua dang hien thoi
		$piCurrentPage = -1;
		//Dat gia tri ban dau cho record/page
		$piNumRowOnPage = -1;
		$psFilterXmlString = $ojbXmlLib->_xmlGenerateXmlDataString($psFilterXmlTagList,$psFilterXmlValueList);	
		$iListType = intval($ojbXmlLib->_xmlGetXmlTagValue($psFilterXmlString,'data_list','listtype_type'));
		if (is_null($iListType) || $iListType == 0){
			//Neu NSD chua xac dinh loai danh muc nao (ID = null) thi lay ID cua loai danh muc dau tien 
			$iListType = $arrListType[0]['PK_LISTTYPE'];
			
			//Lay gia tri trong session
			if (isset($_SESSION['seArrParameter'])){
				$arrParaInSession = $_SESSION['seArrParameter'];
								
				//Lay ID loai danh muc
				$iListType = $arrParaInSession['hdn_id_listtype'];
				//Trang hien thoi
				$piCurrentPage = $arrParaInSession['sel_page'];
				//So record/page
				$piNumRowOnPage = $arrParaInSession['cbo_nuber_record_page'];	
				//Lay thanh sach cac the
				$psFilterXmlTagList = trim($arrParaInSession['hdn_filter_xml_tag_list']);	
				//Lay danh sach gia tri tuong ung
				$psFilterXmlValueList = trim($arrParaInSession['hdn_filter_xml_value_list']);	
				
				//Neu ton tai gia tri trong hidden luu danh sach THE va VALUE cua tieu thuc loc thi moi thuc hien
				if (isset($psFilterXmlTagList) && isset($psFilterXmlValueList) && $psFilterXmlTagList != "" && $psFilterXmlValueList != ""){
					//Tao xau XML mo ta cac tieu tri loc
					$psFilterXmlString = $ojbXmlLib->_xmlGenerateXmlDataString($psFilterXmlTagList,$psFilterXmlValueList);		
					//Lay ID loai danh muc trong tieu thuc loc
					$iListType = intval($ojbXmlLib->_xmlGetXmlTagValue($psFilterXmlString,'data_list','listtype_type'));
				}else{
					$psFilterXmlString = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
				}	
				//Xoa gia tri trong session
				unset($_SESSION['seArrParameter']);								
			}
			
		}		
		$this->view->iIdListType = $iListType;
		
		//Lay gia tri trang hien thoi
		if ($piCurrentPage == -1){
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			if ($piCurrentPage <= 1){
				$piCurrentPage = 1;
			}
		}	
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View
				
		//Lay thong tin quy dinh so row / page
		if ($piNumRowOnPage == -1){
			$piNumRowOnPage = $objFilter->filter($arrInput['cbo_nuber_record_page']);
			if ($piNumRowOnPage <= $this->view->NumberRowOnPage){
				$piNumRowOnPage = $this->view->NumberRowOnPage;
			}
		}	
		$this->view->numRowOnPage = $piNumRowOnPage; //Gan gia tri vao View
		
		//Lay thong tin danh muc doi tuong
		$arrResult = $objList->getAllList($arrListType, $iListType, $piCurrentPage, $piNumRowOnPage, $psFilterXmlString, $sOwnerCode);
		//Lay ten file XML
		$psXmlFileName = $arrResult['xmlFileName'];	
		$this->view->xmlFileName = $psXmlFileName;

		//Mang luu thong tin doi tuong danh muc
		$arrAllList = $arrResult['arrList'];		
		$psNumberRecord = $arrAllList[0]['TOTAL_RECORD'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Efy_Publib_Library::_selectDeselectAll(sizeof($arrAllList), $psNumberRecord);
		//var_dump($arrAllList);exit;	
		//Neu co du lieu tra ve thi moi hien thi SelectBox trang
		if (count($arrAllList) > 0){			
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page			
			$this->view->generateHtmlSelectBoxPage = Efy_Publib_Library::_generateNumberPageIntoSelectbox($psNumberRecord, $piCurrentPage, $piNumRowOnPage, 'index') . "&nbsp;" . Efy_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"index");
		}
		//echo $psXmlFileName; exit();
		//Tao chuoi HTML hien thi danh sach
		$this->view->generateHtmlList = $ojbXmlLib->_xmlGenerateList($psXmlFileName,'col',$arrAllList, "C_XML_DATA",false);			
		//Tao form hien thi tieu tri loc	
		$this->view->generateFilterForm = $ojbXmlLib->_xmlGenerateFormfield($psXmlFileName, 'list_of_object/table_struct_of_filter_form/filter_row_list/filter_row','list_of_object/filter_formfield_list', $psFilterXmlString, null, true, false);
		
		// Thuc hien tao mot mang de day vao view
		$this->view->arrInput = $arrInput;		
	}
	
	/**
	 * Creater: phongtd
	 * Date:
	 * Idea: Thuc hien phuong thuc Action them moi doi tuong mot loai danh muc
	 */
	public function addAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN ĐỐI TƯỢNG DANH MỤC';
		
		//Tao doi tuong XML
		$ojbXmlLib = new Efy_Publib_Xml();	
		
		//Tao doi tuong Efy_lib
		$ojbEfyLib = new Efy_Library();
		
		// Tao doi tuong cho lop tren		
		$objList = new listxml_modList();
		
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();	
		
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){				
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
			
			$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");	
			$this->view->filterXmlTagList = $psFilterXmlTagList;
			$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
			$this->view->filterXmlValueList = $psFilterXmlValueList;
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;
			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;
				
			//Lay Id loai danh muc
			$iListType = $this->_request->getParam('hdn_id_listtype',0);	
			
			$this->view->iIdListType = $iListType;			
			$_SESSION['listtypeId'] = $iListType;//Phuc vu menh de where lay so thu tu tiep theo cua doi tuong can them moi
			
			//Lay ten file XML
			$psFileName = $this->_request->getParam('hdn_xml_file','');
			//echo $psFileName;
			//Neu khong ton tai file XML thi doc file XML mac dinh
			if($psFileName == "" || !is_file($psFileName)){
				$psFileName = Efy_Init_Config::_setXmlFileUrlPath(1) . "list/quan_tri_doi_tuong_danh_muc.xml";
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
			
			//Trang thai cua doi tuong danh muc (HOAT_DONG : hoat dong; NGUNG_HOAT_DONG ; Ngung hoat dong)
			$sStatus = 'NGUNG_HOAT_DONG';
			if ($objFilter->filter($arrInput['C_STATUS'])){
				$sStatus = 'HOAT_DONG';
			}
			//Neu NSD khong chon thuoc don vi nao thi mac dinh lay ma don vi NSD hien thoi
			$sOwnerCodeList = trim($objFilter->filter($arrInput['C_OWNER_CODE_LIST']));
			if (is_null($sOwnerCodeList) or $sOwnerCodeList ==""){
				$sOwnerCodeList = $_SESSION['OWNER_CODE'];
			}
			//Mang luu tham so update in database
			$arrParameter = array(									
									'PK_LISTTYPE'					=>$iListType,
									'PK_LIST'						=>0,
									'C_CODE'						=>trim($objFilter->filter($arrInput['C_CODE'])),
									'C_NAME'						=>trim($objFilter->filter($arrInput['C_NAME'])),
									'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
									'C_OWNER_CODE_LIST'				=>$sOwnerCodeList,
									'C_STATUS'						=>$sStatus,
									'DELETED_EXIST_FILE_ID_LIST'	=>'',
									'NEW_FILE_ID_LIST'				=>'',
									'GET_XML_FILE_NAME'				=>$psFileName
							);
			$arrResult = "";
			if ($objFilter->filter($arrInput['C_CODE']) != ""){				
				$arrResult = $objList->updateList($iListType, $arrParameter, $psXmlStringInDb);							
				// Neu add khong thanh cong			
				if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');\n";				
					echo "</script>";
				}else {			
						//Luu gia tri												
						$arrParaSet = array("hdn_id_listtype"=>$iListType, "hdn_xml_file"=>$psFileName, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);
						//var_dump($arrParaSet); exit;
						$_SESSION['seArrParameter'] = $arrParaSet;
						$this->_request->setParams($arrParaSet);

						//Tro ve trang index						
						$this->_redirect('listxml/list/');						
					}
			}		
		}
	}		
	
	/**
	 * Creater: phongtd
	 * Date: 02/02/2009
	 * Idea: Thuc hien Action hieu chinh thong tin doi tuong danh muc
	 */
	public function editAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN ĐỐI TƯỢNG DANH MỤC';
		
		//Tao doi tuong XML
		$ojbXmlLib = new Efy_Publib_Xml();	
		
		//Tao doi tuong Efy_lib
		$ojbEfyLib = new Efy_Library();
				
		// Tao doi tuong cho lop tren		
		$objList = new Listxml_modList();
		
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();	
				
		$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");		
		$this->view->filterXmlTagList = $psFilterXmlTagList;
		$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $psFilterXmlValueList;
		
			// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){				
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
			
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;
			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;
						
			//Lay Id loai danh muc
			$iListType = $this->_request->getParam('hdn_id_listtype',0);
			$this->view->iIdListType = $iListType;
			//echo 'iListType:' .$iListType . '<br>';
			//Lay Id loai danh muc
			$iListId = $this->_request->getParam('hdn_list_id',0);
			$this->view->iIdList = $iListId;
			//Lay ten file XML
			$psFileName = $this->_request->getParam('hdn_xml_file','');
			//Neu khong ton tai file XML thi doc file XML mac dinh
			if($psFileName == "" || !is_file($psFileName)){
				$psFileName = Efy_Init_Config::_setXmlFileUrlPath(1) . "list/quan_tri_doi_tuong_danh_muc.xml";
			}	
			//Lay thong tin danh muc doi tuong
			if($iListId != 0){
				$arrGetSingleList 	= 	$objList->getSingleList($iListId);
				$psXmlStr 			= $arrGetSingleList['C_XML_DATA'];
			}else{
				$psXmlStr = '';
				$arrGetSingleList = array();	
			}
			if ($psXmlStr != ""){
				$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?>' . $psXmlStr;
			}else{
				$psXmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
			}
			//Tao xau html mo ta form field cap nhat thong tin va gui ra VIEW hien thi ket qua				
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
			
			//Trang thai cua doi tuong danh muc (HOAT_DONG : hoat dong; NGUNG_HOAT_DONG ; Ngung hoat dong)
			$sStatus = 'NGUNG_HOAT_DONG';
			if ($objFilter->filter($arrInput['C_STATUS'])){
				$sStatus = 'HOAT_DONG';
			}
			//Neu NSD khong chon thuoc don vi nao thi mac dinh lay ma don vi NSD hien thoi
			$sOwnerCodeList = trim($objFilter->filter($arrInput['C_OWNER_CODE_LIST']));
			if (is_null($sOwnerCodeList) or $sOwnerCodeList ==""){
				$sOwnerCodeList = $_SESSION['OWNER_CODE'];
			}
			
			//Mang luu tham so update in database
			$arrParameter = array(									
									'PK_LISTTYPE'					=>$iListType,
									'PK_LIST'						=>$iListId,
									'C_CODE'						=>trim($objFilter->filter($arrInput['C_CODE'])),
									'C_NAME'						=>trim($objFilter->filter($arrInput['C_NAME'])),
									'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
									'C_OWNER_CODE_LIST'				=>$sOwnerCodeList,
									'C_STATUS'						=>$sStatus,
									'DELETED_EXIST_FILE_ID_LIST'	=>'',
									'NEW_FILE_ID_LIST'				=>'',
									'GET_XML_FILE_NAME'				=>$arrGetSingleList['C_XML_FILE_NAME']
							);
							
			$arrResult = "";
			
			if (trim($objFilter->filter($arrInput['C_CODE']) != "") || trim($objFilter->filter($arrInput['C_NAME']) != "")){				
				$arrResult = $objList->updateList($iListType, $arrParameter, $psXmlStringInDb);							
				// Neu add khong thanh cong			
				if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');\n";				
					echo "</script>";
				}else {			
						//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)
						$arrParaSet = array("hdn_id_listtype"=>$iListType, "hdn_xml_file"=>$psFileName, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);
						//var_dump($arrParaSet); exit;
						$_SESSION['seArrParameter'] = $arrParaSet;
						//Luu bien ket qua
						$this->_request->setParams($arrParaSet);

						//Tro ve trang index						
						$this->_redirect('listxml/list/');						
					}
			}		
		}
	}
	
	/**
	 * Creater: phongtd
	 * Date: 02/02/2009
	 * Idea: Tao phuong thuc thuc hien xoa doi tuong danh muc
	 *
	 */
	public function deleteAction(){
		
		// Tao doi tuong cho lop tren		
		$objList = new Listxml_modList();
				
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){
			$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");		
			$this->view->filterXmlTagList = $psFilterXmlTagList;
			$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
			$this->view->filterXmlValueList = $psFilterXmlValueList;
			
			// Lay lai id cua loai danh muc
			$iListTypeId =(int)$this->_request->getParam('hdn_id_listtype',0);
			$this->view->iIdListType = $iListTypeId;
			
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;
			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;
						
			//Lay Id loai danh muc
			$iListIdList = $this->_request->getParam('hdn_object_id_list',"");
			if ($iListIdList != ""){
				$psRetError = $objList->deleteList($iListIdList);
				// Neu co loi			
				if($psRetError != null || $psRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$psRetError');\n";				
					echo "</script>";
				}else {		
						//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
						$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
						//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
						$_SESSION['seArrParameter'] = $arrParaSet;
						//Luu bien ket qua
						$this->_request->setParams($arrParaSet);
	
						//Tro ve trang index						
						$this->_redirect('listxml/list/index/');					
					}
			}
		}		
	}
	
	/**
	 * @see : xuat ra mot file xml 
	 * 			
	 * */
	public function xmlAction(){
		
		if($this->_request->isPost()){				
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();

			// Lay lai id cua loai danh muc
			$iListTypeId =(int)$this->_request->getParam('hdn_id_listtype',0);
			$this->view->iIdListType = $iListTypeId;
			
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;
			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;
						
			//Tinh trang
			$iStatus = 'HOAT_DONG';

			//Tao doi tuong  Listxml_modList 
			$objList = new Listxml_modList();
			
			// Thuc hien lay du lieu trong csdl 
			$arrTempList = $objList->createXMLDb($iStatus, $iListTypeId);
			
			// duong dan 
			$sFilePath = 'xml/list/output/';
			
			// Thuc hien Tao file xml
			$this->createXML($sFilePath,$arrTempList);		
			
			//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
			$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage);						
			
			//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
			$_SESSION['seArrParameter'] = $arrParaSet;
			
			//Luu bien ket qua
			$this->_request->setParams($arrParaSet);

			//Tro ve trang index						
			$this->_redirect('listxml/list/');				
		}	
	}
	
	/**
	 * @see : Ham thuc hien tao file xml 
	 * */
	private function createXML($pFilePath,$parrList){			
		for ($index = 0 ; $index < sizeof($parrList); $index++){
			echo $pFilePath.$parrList[$index]['C_CODE'].'.xml' . '<br>';
			Efy_Library::_writeFile($pFilePath.$parrList[$index]['C_CODE'].'.xml',$parrList[$index]['XML_DATA']);								
		}						
	}
}
?>