<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class Listxml_ListTypeController extends  Zend_Controller_Action {
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
		
		//Goi lop Listxml_modListType
		Zend_Loader::loadClass('Listxml_modListType');
		
		//Lay so dong treng man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
				
		// Goi lop public		
		$objPublicLibrary = new Efy_Library();
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = $objPublicLibrary->_getAllFileJavaScriptCss('public/efy-js/ListType','','','','js') . $objPublicLibrary->_getAllFileJavaScriptCss('public/efy-js/ListType','','','','css').Efy_Publib_Library::_getAllFileJavaScriptCss('','efy-js','jquery-1.4.3.min.js',',','js');
				
		/* Ket thuc 		*/
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";		
		$this->view->currentModulCodeForLeft = "LISTTYPE";
		
		//Lay cac hang so su dung trong JS public		
		$objConfig = new Efy_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();			

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
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
        
        // Goi ham kiem tra user login
		//Efy_Library::_isLogin($objConfig->_setUserLoginUrl(),$objConfig->_setAppCode(), $_SESSION['staff_id_temp'],$objConfig->_setTimeOut());
	}	
	
	/**
	 * Creater : phongtd
	 * Date :
	 * Idea : Tao phuong thuc hien thi danh sach loai danh muc
	 *
	 */
	// Xu ly thong tin de day ra file index.phtml
	public function indexAction(){
		
		$this->view->bodyTitle = 'DANH SÁCH CÁC LOẠI DANH MỤC';			
		
		// Tao doi tuong cho lop tren		
		$objListType = new Listxml_modListType();
		
		// Lay cac tham param de truyen vao phuong thuc getAllListType : dung cho search
		$iStatus = '';
		$sListTypeName =$this->_request->getParam('txtSearch','');
		$this->view->listTypeName = $sListTypeName;	
		$sOwnerCode = $_SESSION['OWNER_CODE'];
				
		// Thuc hien phuong thuc getAllListType 
		$this->view->arrResult = $objListType->getAllListType($iStatus,$sListTypeName,$sOwnerCode);
				
		// Tao bien chua so phan tu cua mang ket qua 
		$this->view->iCountElement = count($this->view->arrResult);	
		
	}
	
	/**
	 * @see : Thuc hien Them moi LOAI DANH Muc
	 *
	 */
	public function addAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT MỘT LOẠI DANH MỤC';
		//Gan gia tri mac dinh cho cac Input
		$this->view->parError = '';
		
		// goi load div 
		$this->view->divDialog = $this->showDialog();
		
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();				
						
			// Tao doi tuong Zend_Filter
			$filter = new Zend_Filter();
			
			// Thuc hien tao mot mang de day vao view
			$this->view->arrInput = $arrInput;
			
			// Lay so thu tu lon nhat gan vao 
			$this->view->arrInput['C_ORDER'] = $filter->filter($arrInput['hdn_order']);	 
							
			// Lay lai bien kiem tra xem form co submit khong
			$isUpdate = $filter->filter($arrInput['hdn_update']);		

			if($isUpdate == 'OK' & $isFlag=true ){
				// Tao doi tuong trong thu vien EFY
				$objEfyLibrary = new Efy_Library();
				
				// lay cac tham so cua form				
				$iListTypeId =(int)$filter->filter($arrInput['hdn_listtype_id']);					
				
				$sListTypeCode	=trim($objEfyLibrary->_restoreXmlBadChar($filter->filter($arrInput['C_CODE']))) ;				
				$sListTypeName = trim($objEfyLibrary->_restoreXmlBadChar($filter->filter($arrInput['C_NAME']))) ;			
				$iListTypeOrder = $filter->filter($arrInput['C_ORDER']) ;								
				
				// Ten file up load
				$sListTypeXml = $_FILES['C_XML_FILE_NAME']['name'];								
				// Lay ten file khi chon tu server
				if($sListTypeXml == null || $sListTypeXml == ''){
					$sListTypeXml = $this->_request->getParam('txt_xml_file_name');	
				}				
				
				//Id loai danh muc
				$iListTypeId =(int)$filter->filter($arrInput['hdn_listtype_id']);
				
				//Trang thai cua doi tuong danh muc (HOAT_DONG : hoat dong; NGUNG_HOAT_DONG ; Ngung hoat dong)
				$sStatus = 'NGUNG_HOAT_DONG';
				if ($filter->filter($arrInput['C_STATUS'])){
					$sStatus = 'HOAT_DONG';
				}
				
				// Lay don vi su dung
				$arrListTypeOwnerCodeList = $this->_request->getParam('chk_onwer_code_list');
				$sListTypeOwnerCodeList = '';
				for ($i = 0; $i < sizeof($arrListTypeOwnerCodeList) - 1; $i++)
					$sListTypeOwnerCodeList .= $arrListTypeOwnerCodeList[$i] . ',';
				$sListTypeOwnerCodeList .= $arrListTypeOwnerCodeList[sizeof($arrListTypeOwnerCodeList) - 1];
				$isSaveAndAddNew = 	$filter->filter($arrInput['C_SAVE_AND_ADD_NEW']);						
				// xu ly viec copy file len o cung
				if($_FILES['C_XML_FILE_NAME']['name'] != ""){
					move_uploaded_file($_FILES['C_XML_FILE_NAME']['tmp_name'], "../xml/list/" . $_FILES['C_XML_FILE_NAME']['name']);
					$sListTypeXml = $_FILES['C_XML_FILE_NAME']['name'];					
				}	
				
				// Tao doi tuong modeListType
				$objListType= new Listxml_modListType();
				
				// Thuc hien cap nhat vao csdl				
				$arrResult = $objListType->updateListType($iListTypeId,$sListTypeCode,$sListTypeName,$iListTypeOrder,$sListTypeXml,$sStatus,$sListTypeOwnerCodeList); 																				
				
				// Neu add khong thanh cong
				if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');";	
					echo "</script>";
				}else {
					// kt xem co save and add new
					if($isSaveAndAddNew!=1){
						//Tro ve trang index
						$this->_redirect('listxml/listtype/');
					}else {
						$iListTypeOrder = $iListTypeOrder +  1;
						echo "<script type='text/javascript'>";														
						echo "document.getElementById('C_CODE').value='';";
						echo "document.getElementById('C_NAME').value='';";						
						echo "document.getElementById('C_ORDER').value='" . $iListTypeOrder ."';";						
						echo "document.getElementById('C_XML_FILE_NAME').value='';";						
						echo "</script>";
					}		
				}
			}
		}
				
	}		

	/**
	 * @see : Thuc hien viec sua Mot hoac Nhieu Loai Danh muc
	 */
	public function editAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT MỘT LOẠI DANH MỤC';
		
		//Gan gia tri mac dinh cho cac Input Error
		$this->view->parError = '';	
		
		// Tao doi tuong cho lopr Filter
		$filter = new Zend_Filter();
		
		// goi load div 
		$this->view->divDialog = $this->showDialog();
		
		//  Lay id cua ban ghi
		$iListTypeId = (int)$this->_request->getParam('hdn_listtype_id');
		$this->view->iListTypeId = $iListTypeId;
		// Tao doi tuong 
		$objListType = new  Listxml_modListType();
		
		// Tao doi tuong Efy	
	   	$objEfyLibrary = new Efy_Library();
	   		
		// Lay thong tin trong csdl
		$arrResult = $objListType->getSingleListType($iListTypeId);
				
		//	xu ly du lieu de dua vao mang	
		$arrTempResult = array(
			'PK_LISTTYPE'=> $arrResult[0]['PK_LISTTYPE'],
			'C_CODE'=> trim($objEfyLibrary->_replaceBadChar($arrResult[0]['C_CODE'])),
			'C_NAME' => trim($objEfyLibrary->_replaceBadChar($arrResult[0]['C_NAME'])),
			'C_ORDER' => $arrResult[0]['C_ORDER'],
			'C_XML_FILE_NAME'=>$arrResult[0]['C_XML_FILE_NAME'],
			'C_STATUS' => $arrResult[0]['C_STATUS'],
			'C_OWNER_CODE_LIST' => $arrResult[0]['C_OWNER_CODE_LIST']
		);				
		// Thuc hien bind du lieu vao view
	   $this->view->arrInput = $arrTempResult;
	   
	   // Xu ly nut Tinh trang
	   if($arrTempResult['C_STATUS']==1){
	   		$this->view->bStatus = true;	   	
	   }else {
			$this->view->bStatus = false;	   	
	   }
	   
	   // Lay lai bien kiem tra xem form co submit khong
		$isUpdate = $this->_request->getParam('hdn_update');		

			
	   //  Thuc hien hieu chinh danh muc
	   if($isUpdate == 'OK'){	
	   		// lay cac tham so cua form					
			$sListTypeCode	= $objEfyLibrary->_restoreXmlBadChar($this->_request->getParam('C_CODE')) ;				
			$sListTypeName = $objEfyLibrary->_restoreXmlBadChar($this->_request->getParam('C_NAME'));
			$iListTypeOrder = $this->_request->getParam('C_ORDER') ;				
			
			// Ten file up load
			$sListTypeXml = $_FILES['C_XML_FILE_NAME']['name'];	
			// Lay ten file khi chon tu server
			if($sListTypeXml == null || $sListTypeXml == ''){
				$sListTypeXml = $this->_request->getParam('txt_xml_file_name');	
			}								
			
			//Trang thai cua doi tuong danh muc (HOAT_DONG : hoat dong; NGUNG_HOAT_DONG ; Ngung hoat dong)
			$sStatus = 'NGUNG_HOAT_DONG';
			if ($this->_request->getParam('C_STATUS')){
				$sStatus = 'HOAT_DONG';
			}
			
			// Lay don vi su dung
			$arrListTypeOwnerCodeList = $this->_request->getParam('chk_onwer_code_list');
			$sListTypeOwnerCodeList = '';
			for ($i = 0; $i < sizeof($arrListTypeOwnerCodeList) - 1; $i++)
				$sListTypeOwnerCodeList .= $arrListTypeOwnerCodeList[$i] . ',';
			$sListTypeOwnerCodeList .= $arrListTypeOwnerCodeList[sizeof($arrListTypeOwnerCodeList) - 1];
				
			// Nut Save va Them moi
			$isSaveAndAddNew = 	$this->_request->getParam('C_SAVE_AND_ADD_NEW');
			$this->view->saveAndAdd = true;
					
			// xu ly viec copy file len o cung
			if($_FILES['C_XML_FILE_NAME']['name'] != ""){
				move_uploaded_file($_FILES['C_XML_FILE_NAME']['tmp_name'], "../xml/list/" . $_FILES['C_XML_FILE_NAME']['name']);
				$sListTypeXml = $_FILES['C_XML_FILE_NAME']['name'];					
			}else{
				$sListTypeXml = $this->_request->getParam('txt_xml_file_name');
			}						
			
			// Tao doi tuong modeListType
			$objListType= new Listxml_modListType();

			// Thuc hien cap nhat vao csdl				
			$arrResult = $objListType->updateListType($iListTypeId,$sListTypeCode,$sListTypeName,$iListTypeOrder,$sListTypeXml,$sStatus,$sListTypeOwnerCodeList); 															
							
			// Neu edit khong thanh cong
			if($arrResult != null || $arrResult != '' ){											
				echo "<script type='text/javascript'>";
				echo "alert('$arrResult');";	
				echo "</script>";
			}else {
				// kt xem co save and add new
				if($isSaveAndAddNew != 1){
					//Tro ve trang index
					$this->_redirect('listxml/listtype/');
				}else {					
					echo "<script type='text/javascript'>";														
					echo "document.getElementById('C_CODE').value='';";
					echo "document.getElementById('hdn_listtype_id').value='';";
					echo "document.getElementById('C_NAME').value='';";
					echo "document.getElementById('C_ORDER').value='" .(int)($iListTypeOrder + 1)."';";					
					echo "document.getElementById('C_XML_FILE_NAME').value='';";	
					echo "document.getElementById('txt_xml_file_name').value='';";
					echo "</script>";
				}
				
			}
		}	  
				
	}
	
		
	/**
	 * @see : Thuc hien viec xoa Mot hoac Nhieu Loai Danh muc
	 */
	public function deleteAction(){
		//Request hidden luu id da duoc chon
		$sListTypeIdList = $this->_request->getParam('hdn_object_id_list');

		// Tao doi tuong ListType
		$objListType = new  Listxml_modListType();
		
		// thuc hien cau lenh xoa		
		$arrResult = $objListType->deleteListType($sListTypeIdList);
		// Kiem tra 
		if($arrResult != null || $arrResult != '' ){											
			echo "<script type='text/javascript'>";
			echo "alert('$arrResult');";	
			echo "</script>";
		}else {
			//Tra ve trang index
			$this->_redirect('listxml/listtype/');
		}	
	}	
/**
 * @see : Thuc hien lay file doc tat ca cac file xml tu o cung cua server
 * @creator: phongtd
 * @createdate: 
 * 
 * 
 * 	*/
	
	private function showDialog(){
		
		$dir = "./xml/list/";				
		$objConfig = new Efy_Init_Config();
		
		$sResHtml = $sResHtml. "<div style='overflow:auto;height:95%; width:98%; padding: 6px 2px 2px 2px;'>";	
		
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		        	// kt la file xml thi hien thi		        	
		        	$filetype = substr($file,strlen($file)-4,4);			        	
		        	$filetype = strtolower($filetype);
		        	if($filetype == ".xml"){		            	
		            	$sResHtml = $sResHtml . "<p  class='normal_label' style='width:95%'  align='left' >";		            	
		            	$sResHtml = $sResHtml . " 	<img src ='".$objConfig->_setImageUrlPath() ."file_icon.gif' width='12' />" ;	
						$sResHtml = $sResHtml . "		<a href='#' onClick =\"getFileNameFromDiv('".$file. "')\">" . $file . "</a>";
		            	$sResHtml = $sResHtml . "</p>";
		        	}	
		        }
		        closedir($dh);
		    }
		}
		$sResHtml = $sResHtml.'</div>';	
		
		return  $sResHtml;
	}
		
}
?>