<?php 
/**
 * @author :phongtd
 * @since : 22/10/2010
 * @see : Lop chua cac phuong thuc dung chung cho toan bo cac modul
 */ 
 class Efy_Function_RecordFunctions{
 
	public function DocSentAttachFile($arrFileList, $piCountFile, $piMaxNumberAttachFile = 10, $psHaveUpLoadFields = true, $size){		
		
		$psGotoUrlForDeleteFile = "javascript:delete_row(document.getElementsByName(\"tr_line_new\"),document.getElementsByName(\"chk_file_attach_new_id\"),document.getElementById(\"hdn_deleted_new_file_id_list\"));";
		$psGotoUrlForAddFile = "javascript:add_row(document.getElementsByName(\"tr_line_new\")," . $piMaxNumberAttachFile .");";
		
		$strHTML = $strHTML . "<table width='75%' cellpadding='0' cellspacing='0'><col width = '6%'><col width = '94%'>";	
		
		//Tao doi tuong thong tin config
		$objConfig = new Efy_Init_Config();
		
		//ID File dinh kem		
		if (($piCountFile>0) && ($arrFileList != '')){		
			// Goi thu tuc xu ly khi xoa cac file da co
			$psGotoUrlForDeleteFile = $psGotoUrlForDeleteFile . "delete_row_exist(document.getElementsByName(\"tr_line_exist\"),document.getElementsByName(\"chk_file_attach_exist_id\"),\"" . $_SERVER['REQUEST_URI'] . "\");";
			for ($index = 0; $index<$piCountFile; $index++){
				$sFileId = $arrFileList[$index]['PK_FILE'];
				$sFileName = $arrFileList[$index]['C_FILE_NAME']; 
				// Tach ten file ra
				if(strpos($sFileName,"!~!") == 0){
					$file_name = $sFileName;
				}else{
					$arrFilename = explode('!~!',$sFileName);					
					$file_name = $arrFilename[1];
					$file_id   = explode("_", $arrFilename[0]);
				}							
				//Get URL
				$sActionUrl = $objConfig->_setAttachFileUrlPath() . $file_id[0] . "/" . $file_id[1] . "/" . $file_id[2] . "/" . $sFileName;		
				
				//
				$strHTML = $strHTML . "<tr id='tr_line_exist' name = 'tr_line_exist'><td colspan='2' class='normal_link'>";
				if ($psHaveUpLoadFields){
					$strHTML = $strHTML . "<input type='checkbox' name='chk_file_attach_exist_id' id = '' value='$sFileName'>";				
				}
				$strHTML = $strHTML . "<a href='$sActionUrl' $target > $file_name  </a></td></tr>";
			}
		}		
		//Them moi
		if ($psHaveUpLoadFields){
			//Vong lap hien thi cac file dinh kem se them vao van ban
			for($index = 0; $index<$piMaxNumberAttachFile; $index++){					
				if ($index < 1 ) {
					$v_str_show="block";
				}else{
					$v_str_show="none";
				}
				$strHTML = $strHTML . "<tr name = 'tr_line_new' id='tr_line_new' style='display:$v_str_show'><td><input type='checkbox' name='chk_file_attach_new_id' id = 'chk_file_attach_new_id' value=$index></td>";
				$strHTML = $strHTML . "<td><input class='textbox' type='file' name='FileName$index' id = 'FileName$index' optional='true' size = '" . $size . "'></td></tr>";
			}	
			$strHTML = $strHTML . "<tr align='center'><td colspan='2' align='center'><a onclick='$psGotoUrlForAddFile' class='small_link'>Th&#234;m file</a>&nbsp;|&nbsp;";
			$strHTML = $strHTML . "	<a onclick='$psGotoUrlForDeleteFile' class='small_link'>X&#243;a file</a></td></tr>";
		}
		$strHTML = $strHTML . "</table>";
		//echo htmlspecialchars($strHTML);//exit;
		return $strHTML;
	}	
	
	/**
	 * Creater : phongtd
	 * Date : 13/06/2009
	 * Idea : Tao phuong thuc kiem tra NSD hien thoi co ton tai trong he quan tri NSD khong?
	 *
	 */		
public function CheckLogin(){		
		//Tao bien Zend_Session_Namespace	
		if(!isset($_SESSION['varCheckLogin'])){						
			//Tao Zend_Session_Namespace
			Zend_Loader::loadClass('Zend_Session_Namespace');
			$SesCheckLogin = new Zend_Session_Namespace('varCheckLogin');	
		}
			
		//Neu NSD dang nhap vao phan mem thong qua Trang DHTN
		$_SESSION['sessionid'] = $_GET['sessionid'] ;
		//var_dump($_GET['sessionid']);
		//echo "<br>".session_id();
		//exit;
		if (isset($_GET['logonstaffid']) && $_GET['logonstaffid'] != 0 && $_GET['logonstaffid'] != ""){			
			session_destroy();	
			session_start();
			$SesCheckLogin->staffId = 	$_GET['logonstaffid'];	
			$_SESSION['staff_id'] = $_GET['logonstaffid'];
			$_SESSION['user_id'] = $_GET['logonstaffid'];
		}
		//var_dump($_GET['logonstaffid']);
		//exit;
		//Gan SESSION
		if (isset($_REQUEST['logon_staff_id']) && !isset($_SESSION['staff_id'])){					
			$SesCheckLogin->staffId = 	$_REQUEST['logon_staff_id'];	
			$_SESSION['staff_id'] = $_REQUEST['logon_staff_id'];
			$_SESSION['user_id'] = $_REQUEST['logon_staff_id'];
			$_SESSION['user_name'] = $_REQUEST['logon_user_name'];
			// Khoi tao them cac bien session de kiem tra xem
			// nguoi dang nhap co phai la NGUOI QUAN TRI EFY-USER va NGUOI QUAN TRI cua it nhat mot EFY-APP hay khong
			$_SESSION['is_EFY_user_admin'] = $_REQUEST['logon_is_EFY_user_admin'];
			$_SESSION['is_EFY_app_admin'] = $_REQUEST['logon_is_EFY_app_admin'];			
		}
		
		//Neu chua ton tai ID can bo dang nhap hien thoi thi moi thuc hien			
		if(!isset($SesCheckLogin->staffId) || is_null($SesCheckLogin->staffId) || $SesCheckLogin->staffId == 0){
			//Load class Efy_Init_Config
			Zend_Loader::loadClass('Efy_Init_Config');
			$objConfig = new Efy_Init_Config();
			//Kiem tra thong tin NSD?>
			<script type="text/javascript">
				 UrlRes = '<?php echo $objConfig->_setUserLoginUrl() ?>';					 
				 window.location = UrlRes; 					
			</script><?php	
		}
		//Lay thong tin NSD trong efy-user
		if(!isset($SesCheckLogin->arr_all_staff) || $SesCheckLogin->arr_all_staff == "" || is_null($SesCheckLogin->arr_all_staff)){
			Zend_Loader::loadClass('Efy_Init_Config');
			$SesCheckLogin->arr_all_staff = Efy_Init_Session::SesGetPersonalInfoOfAllStaff(Efy_Init_Config::_setWebServiceUrl());		
			//$_SESSION['arr_all_staff'] = $SesCheckLogin->arr_all_staff;	
		}	
		$_SESSION['arr_all_staff'] = $SesCheckLogin->arr_all_staff;	
		
		if(!isset($_SESSION['arr_all_position_group'])){	
			$_SESSION['arr_all_position_group'] = Efy_Init_Session::PositionGroupGetAll();	
		}
		//var_dump($_SESSION['arr_all_position_group']);
		//Lay thong tin phong ban trong efy-user
		if(!isset($SesCheckLogin->arr_all_unit) || $SesCheckLogin->arr_all_unit == "" || is_null($SesCheckLogin->arr_all_unit)){
			Zend_Loader::loadClass('Efy_Init_Config');
			$SesCheckLogin->arr_all_unit = Efy_Init_Session::SesGetDetailInfoOfAllUnit(Efy_Init_Config::_setWebServiceUrl());	
		}	
		$_SESSION['arr_all_unit'] = $SesCheckLogin->arr_all_unit;
		//Lay quyen NSD hien thoi				
			$_SESSION['arrStaffPermission'] = Efy_Init_Session::StaffPermisionGetAll($_SESSION['staff_id']);				
		//Lay thong tin ID don vi su dung, nhanh cac phong ban voi NSD hien thoi
		if(!isset($SesCheckLogin->arr_all_unit_keep) || $SesCheckLogin->arr_all_unit_keep == "" || is_null($SesCheckLogin->arr_all_unit_keep)){
			//Tao doi tuong session
			$objSession = new  Efy_Init_Session();
			
			//Luu toan bo thong tin cay NSD cua don vi vao bien session keep
			$_SESSION['arr_all_unit_keep'] = $_SESSION['arr_all_unit'];
			//
			$arr_value = explode("|!~~!|",$objSession->_getUnitLevelOneNameAndId($_SESSION['staff_id']));
			
			//Lay ID don vi su dung va luu vao bien session
			$_SESSION['OWNER_ID'] = $arr_value[0];		
			//echo $_SESSION['OWNER_ID'];exit;
			//Lay ma don vi su dung va luu vao bien session
			$_SESSION['OWNER_CODE'] = $arr_value[3];
			
			//Lay ten don vi su dung va luu vao bien session
			$_SESSION['OWNER_NAME'] = $arr_value[2];
			
			// Gan danh sach phong ban cua don vi trien khai(NSD hien thoi)
			$_SESSION['arr_all_unit'] = $objSession->_getUnitsByRoomIdLevelOne($arr_value);		
			
			//Luu toan bo thong tin NSD ban dau vao session_keep
			$_SESSION['arr_all_staff_keep'] = $_SESSION['arr_all_staff'];
			
			//Lay tat ca NSD cua don vi hien hoi
			$_SESSION['arr_all_staff'] = $objSession->_getAllUser($_SESSION['arr_all_unit']);
			
			//
			$SesCheckLogin->arr_all_unit_keep = $_SESSION['arr_all_unit'];	
		}
		//var_dump($_SESSION['arr_all_unit']);
		//Lay thong tin cua cac don vi su dung
		if(!isset($SesCheckLogin->arr_all_owner) || $SesCheckLogin->arr_all_owner == "" || is_null($SesCheckLogin->arr_all_owner)){			
			$SesCheckLogin->arr_all_owner =Efy_Init_Session::SesGetAllOwner();			
			$_SESSION['SesGetAllOwner'] = $SesCheckLogin->arr_all_owner;		
		}	
		//var_dump($_SESSION['arr_all_staff']);
		//Luu session thong tin nguoi dang nhap hien thoi
		if(!isset($SesCheckLogin->InformationStaffLogin) || $SesCheckLogin->InformationStaffLogin == "" || is_null($SesCheckLogin->InformationStaffLogin)){			
			$SesCheckLogin->InformationStaffLogin = Efy_Library::_getInformationStaffLogin();				
			$_SESSION['INFORMATION_STAFF_LOGIN'] 	 = $SesCheckLogin->InformationStaffLogin;				
		}	
		//Tao SESSION luu thong tin tat ca cac TTHC cua nguoi dang nhap hien thoi
		if (!isset($_SESSION['arr_all_record_type']))
			$_SESSION['arr_all_record_type'] = self::eCSRecordTypeGetAllByStaff($_SESSION['staff_id'], $_SESSION['OWNER_CODE']);						
		//khoi tao session danh sach phong ban va 
		//Efy_Function_DocFunctions::_getAllStaff($_SESSION['arr_all_unit'], $_SESSION['arr_all_staff'], $_SESSION['OWNER_ID']);
		
		return $SesCheckLogin;
	}
	
 	/**
 	 * Creater : phongtd
 	 * Date : 16/09/2009
 	 * Idea : Ham tao chuoi HTML lay ra danh sach cac checkbox LANH DAO va danh sach Y KIEN CHI DAO tuong ung
 	 *
 	 * @param unknown_type $arrLeader
 	 * @param unknown_type $leaderIdList
 	 * @param unknown_type $leaderIdeaList
 	 * @return Danh sach cac checkbox LANH DAO va danh sach Y KIEN CHI DAO tuong ung
 	 */
	public function generateUnitLeaderList($arrLeader, $leaderIdList = "" , $leaderIdeaList = ""){
		$strHTML ="";	
		$strHTML .= $this->formHidden("ds_lanh_dao","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_lanh_dao"));
		$strHTML .= $this->formHidden("ds_y_kien","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_y_kien"));
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="98%" align="center" class="list_table2" id="table1">
		<?php
		$arrConst = $this->arrConst; 
		$delimitor = $this->delimitor;//Lay ky tu phan cach giua cac phan tu
		//Hien thi cac cot cua bang hien thi du lieu
		$StrHeader = explode("!~~!",$this->GenerateHeaderTable("30%". $delimitor . "70%" 
										,$arrConst['_LANH_DAO_PHAN_CONG'] . $delimitor .$arrConst['_Y_KIEN_CHI_DAO']
										,$delimitor));
		echo $StrHeader[0];	
									
		echo $StrHeader[1]; //Hien thi <col width = 'xx'><..
		$v_current_style_name = "round_row";
		//Duyet cac phan tu mang danh sach LANH DAO DON VI 	
		for($i = 0; $i<sizeof($arrLeader); $i++){				
			//Checked gia tri
			$sChecked = "";
			$sIdea = "";
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($leaderIdList) != ""){			
				//Danh sach Id Lanh dao luu trong CSDL
				$arrLeaderInDb = explode(",",$leaderIdList);				
				//Danh sach Y kien Lanh dao luu trong CSDL
				$arrIdeaInDb = explode("!#~$|*",$leaderIdeaList);							
				for ($index = 0;$index < sizeof($arrLeaderInDb);$index++){
					if ($arrLeaderInDb[$index] == $arrLeader[$i]['id']){
						$sChecked = "checked";
						$sIdea = $arrIdeaInDb[$index];
					}
				}
			}	
			$leaderId = $arrLeader[$i]['id'];
			
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";					
			}
			
			$strHTML = $strHTML. "<tr class='<?=$v_current_style_name?>'>";	
			
			
			$strHTML = $strHTML. "<td style='margin-top:5px;'><input $sChecked type='checkbox' id='chk_multiple' name='chk_multiple'  xml_data='false' optional = 'true' value='$leaderId'  xml_tag_in_db_name =''>&nbsp;&nbsp;".$arrLeader[$i]['position_code'].' - '. $arrLeader[$i]['name']."</td>";			
			//Y kien			
			$strHTML = $strHTML. "<td><input style='width:99.4%;margin-top:5px;'type='textbox' id='txt_multiple' name='txt_multiple' xml_data='false'  xml_tag_in_db_name ='' value='$sIdea' optional = 'true' ></td>";

			$strHTML = $strHTML."</tr>";		
		
		}
		$strHTML = $strHTML."<tr><td height='5'></td></tr>";
		$strHTML = $strHTML."</table>";	   
		return $strHTML;					
	} 	
 	/**
 	 * Creater : phongtd
 	 * Date : 17/09/2009
 	 * Idea : Ham lay ra mang danh sach LANH DAO
 	 *
 	 * @param  $pGroupUser
 	 * @param  $psPositionLeader
 	 * @return Danh sach LANH DAO
 	 */
	public function docGetAllUnitLeader($pGroupUser = "",$sSessionName = "arr_all_staff"){
		$i = 0;
		$pPositionGroupCode = $pGroupUser;
		if($pPositionGroupCode == ""){
			$pPositionGroupCode = "LANH_DAO_UB";
		}
		
		foreach($_SESSION[$sSessionName] as $staff){	
			if(Efy_Library::_listHaveElement($pGroupUser,$staff['position_group_code'],",")){				
				$arrUnitLeader[$i] =  $staff;				
				$i++;				
			}
		}
		return $arrUnitLeader;	
	}	
	/**
	 * Creater : phongtd
 	 * Date : 18/09/2009
 	 * Idea : Ham tao chuoi HTML sinh ra danh sach cac multiple_checkbox cua cac DON VI
	 *
	 * @param unknown_type $arrUnit
	 * @param unknown_type $unitIdList
	 * @return Danh sach cac multiple_checkbox cua cac DON VI
	 */
	public function DocGenerateMultipleCheckbox($arrUnit, $unitIdList = "", $TagName = "ds_don_vi"){
		$strHTML ="";
		$strHTML = $strHTML . "<tr><td colspan='10' style='display:none;'><input type='text' id = '$TagName' name='$TagName' value='' hide='true'  xml_data='true' xml_tag_in_db='$TagName' optional='true' message=''></td></tr>";		
		//Dat style cho cac row
		$v_current_style_name == "round_row";
		
		//Duyet cac phan tu mang danh sach DON VI 			
		for($i = 0; $i<sizeof($arrUnit); $i++){				
			//Checked gia tri
			$sChecked = "";
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($unitIdList) != ""){
				//Danh sach Id DON VI luu trong CSDL
				$arrUnitInDb = explode(",",$unitIdList);
				for ($index = 0;$index < sizeof($arrUnitInDb);$index++){
					if ($arrUnitInDb[$index] == $arrUnit[$i]['id']){
						$sChecked = "checked";
					}
				}
			}			
			$unitId = $arrUnit[$i]['id'];
			if($i % 2 == 0 ){
				if ($v_current_style_name == "round_row"){
					$v_current_style_name = "odd_row";
				}else{
					$v_current_style_name = "round_row";
				}				
				$strHTML = $strHTML. "<tr class='" . $v_current_style_name . "'>";				
			}			
			$strHTML = $strHTML. "<td><input $sChecked  type='checkbox' id='chk_multiple_checkbox' name='chk_multiple_checkbox'  xml_data='true' optional = 'true' value='$unitId'  xml_tag_in_db_name ='$TagName'  nameUnit = '" . $arrUnit[$i]['name'] . "'>" . $arrUnit[$i]['name'] . "</td>";			
			if($i % 2 <> 0 ){				
				$strHTML = $strHTML. "</tr>";	
			}			
		}   
		return $strHTML;
	}
	
	/**
 	 * Creater : phongtd
 	 * Date : 02/10/2009
 	 * Idea : Ham tao chuoi HTML lay ra danh sach cac checkbox LANH DAO 
 	 * @param  $arrLeader
 	 * @param  $leaderIdList
 	 * @return Danh sach cac checkbox LANH DAO 
 	 */
	public function docGenerateLeaderList($arrLeader, $leaderIdList = "" ){
		$strHTML ="";	
		$strHTML .= $this->formHidden("ds_lanh_dao","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_lanh_dao"));
		//Duyet cac phan tu mang danh sach LANH DAO DON VI 	
		for($i = 0; $i<sizeof($arrLeader); $i++){				
			//Checked gia tri
			$sChecked = "";
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($leaderIdList) != ""){			
				//Danh sach Id Lanh dao luu trong CSDL
				$arrLeaderInDb = explode(",",$leaderIdList);											
				for ($index = 0;$index < sizeof($arrLeaderInDb);$index++){
					if ($arrLeaderInDb[$index] == $arrLeader[$i]['id']){
						$sChecked = "checked";
					}
				}
			}	
			$leaderId = $arrLeader[$i]['id'];
			$strHTML = $strHTML. "<tr>";			
			$strHTML = $strHTML. "<td><input $sChecked type='checkbox' id='chk_multiple' name='chk_multiple'  xml_data='false' optional = 'true' value='$leaderId'  xml_tag_in_db_name ='' >".$arrLeader[$i]['position_name'].' - '. $arrLeader[$i]['name']."</td></tr>";			
		}   
		return $strHTML;					
	}
	  /**
	 * Creater : phongtd
	 * Date : 20/05/2010
	 * Idea : Tim kiem gia tri trong mot mang   
	 *
	 * @param $arrRes : Mang gia tri
	 * @param $ColumnIdRes : Ma gia tri
	 * @param $ColumnTexRes : Ten gia tri
	 * @param $TextRes : Gia tri tim kiem
	 * @param $hndRes : Hidden luu gia tri
	 * @param $editable : 1 : duoc phep them moi doi tuong, 0: khong duoc phep them moi doi tuong
	 * @param $option : (Neu $option = 1 chi chon mot doi tuong ; $option = 0 thi duoc chon nhieu)
	 * @param $sColumName : Cot du lieu can bo sung them vao text hien thi tren doi tuong Auto Complete Text (vi du: truyen vao gia tri position_code hien thi Ma chuc vu - Ten can b. CT - Nguyen Van A)
	 * @return Xau html 
	 */
	 function doc_search_ajax($arrRes, $ColumnIdRes, $ColumnTexRes, $TextRes,$hndRes,$single = 1,$sColumName = "",$editable = 0){
		$sWebsitePart = Efy_Init_Config::_setWebSitePath();
		$sHtmlRes = '';
		$sHtmlRes = $sHtmlRes . ' <script type="text/javascript">  ';//
		$sHtmlNameId = '';
		$sHtmlNameId = $sHtmlNameId. '  var NameID'.$hndRes.' = new Array(' ;//
		$sHtmlNameText = '';
		$sHtmlNameText = $sHtmlNameText . ' var NameText'.$hndRes.' = new Array(';
		// Ghi Ma va ten ra mot mang
		foreach($arrRes as $arrTemp){
			$sTemp = "";
			if ($sColumName != ""){
				$sPositionCode = $arrTemp[$sColumName];
				if ($sPositionCode != ""){
					$sTemp = $sPositionCode . " - ";
				}
			}	
			 $sHtmlNameId = $sHtmlNameId .'"' . $arrTemp[$ColumnIdRes] . '",' ;
			 $sHtmlNameText = $sHtmlNameText .'"' . $sTemp . $arrTemp[$ColumnTexRes] . '",' ;
		}
		$sHtmlNameId = rtrim($sHtmlNameId,',') . '); ';
		$sHtmlNameText = rtrim($sHtmlNameText,',') . '); ';
		$sHtmlRes = $sHtmlRes . $sHtmlNameId . $sHtmlNameText .' ';
		$sHtmlRes = $sHtmlRes .' obj'.$hndRes.'= new actb(document.getElementById(\''.$TextRes.'\'),NameText'.$hndRes.',NameText'.$hndRes.',\'FillProduct'.$hndRes.'(\',\''.$single.'\',\''.$editable.'\',\''.$sWebsitePart.'\');';
		$sHtmlRes = $sHtmlRes .' function FillProduct'.$hndRes.'(v_id){}';
		$sHtmlRes = $sHtmlRes . '</script>';
		return  $sHtmlRes;
		
	}
	  /**
	 * Creater : phongtd
	 * Date : 07/06/2010
	 * Idea : Lay ngay dau tien trong tuan
	 *
	 * @return Ngay dau tuan 
	 */
	
	function getFirstDayOfWeek($format = ""){
		$firstDayOfWeek = "";
		$currentWeek = date("W"); // thu tu tuan hien tai cua nam
		$currentYear = date("Y"); // nam hien tai
		$orderDate = 0; // xac dinh ngay dau tuan (thu 2)
		$firstDayOfWeek = Efy_Library::_getAnyDateOnWeekOfYear($currentYear,$currentWeek,$orderDate);
		return $firstDayOfWeek;
	}
		/**
	 * Enter to mau tu khoa tim kiem..
	 *
	 * @param $nameStrColor :  Tu cantim kiem(trich yeu, do mat, noi nhan, noi gui)
	 * @param $nameStrInput : Chuoi tu tim thay tu Tu can tim kiem
	 * @return Xau ki tu duoc to mau o Tu can tim kiem
	 */
	public function searchStringColor($nameStrColor,$nameStrInput){ 
		$i =0;
		$j =0;
		$arrSubject ="";
		$arrSubject = explode(" ",$nameStrInput);
		$arrSearch = explode(" ",$nameStrColor);
		for($i =0; $i < sizeof($arrSearch); $i ++){
			$nameStrOutput = "";
			for($j =0; $j < sizeof($arrSubject); $j ++){
				if(sizeof($arrSearch) > 1){
					$str = $arrSearch[$i];
				}else{
					$str = $nameStrColor;
				}
				if(Efy_Library::Lower2Upper(trim($arrSubject[$j])) == Efy_Library::Lower2Upper(trim($str))){
					$strText = "<label style = 'background-color:#99FF99'>" . $arrSubject[$j] . "</label>";
					$arrSubject[$j] = $strText;
				}
				$nameStrOutput .= $arrSubject[$j] . " ";
			}
		}
		return 	$nameStrOutput;	
	}
	
	/**
	 * Enter to mau tu khoa tim kiem..
	 *
	 * @param $nameStrColor :  Tu cantim kiem(so, ki tu,ngay thang nam)
	 * @param $nameStrInput : Chuoi tu tim thay tu Tu can tim kiem
	 * @return Xau ki tu duoc to mau o Tu can tim kiem
	 */
	public function searchCharColor($nameStrColor,$nameStrInput){ 
		$strText = "<label style = 'background-color:#99FF99'>" . $nameStrColor . "</label>";
		$nameStrOutput .= str_replace(Efy_Library::Lower2Upper(trim($nameStrColor)),Efy_Library::Lower2Upper(trim($strText)),trim($nameStrInput));
		return 	$nameStrOutput;	
	}
	
	/**
	 * Nguoi tạo: Phongtd
	 * Ngay tao: 22/06/2010
	 * Lay ra danh sach Ten + Chuc vu tu danh sach Id staff
	 */
	public function getNamePositionStaffByIdList($sStaffIdList,$delimitor = '!#~$|*'){
		$arrStaffId = explode(',',$sStaffIdList);
		$sNamePositionStaffList= "";
		for($i=0;$i< sizeof($arrStaffId);$i++){  
			$sName = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'name');  
			$sPosition = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'position_code');
	    	$sNamePositionStaffList = $sNamePositionStaffList .$delimitor. $sPosition . ' - ' . $sName;
	    }       
	    $sNamePositionStaffList = substr($sNamePositionStaffList,6); 
		return $sNamePositionStaffList;		
	}
	
	/**
	 * Nguoi tạo: Phongtd
	 * Ngay tao: 22/06/2010
	 * Lay ra danh sach Ten phong ban tu danh sach Id phong ban
	 */
	public function getNameUnitByIdUnitList($sUnitIdList){
		$arrUnitId = explode(',',$sUnitIdList);
		$sNameUnitList= "";
		for($i=0;$i< sizeof($arrUnitId);$i++){  
			$sNameUnit = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$arrUnitId[$i],'name');  
	    	$sNameUnitList = $sNameUnitList .'!#~$|*'. $sNameUnit;
	    }       
	    $sNameUnitList = substr($sNameUnitList,6); 
		return $sNameUnitList;		
	}
	
	/**
	 * Creater : phongtd
	 * Date : 25/06/2010
	 * Idea : Tao phuong thuc chuyen doi danh sach ten can bo thanh ID tuong ung
	 *
	 * @param $sStaffNameList : Chuoi luu danh sach ten can bo (phan tach boi dau ';')
	 * @return Danh sach ID can bo tuong ung voi list name
	 */
	public function convertStaffNameToStaffId($sStaffNameList = "",$delimitor =","){
		$sStaffIdList = "";
		if (trim($sStaffNameList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arr_staff_name = explode(";",$sStaffNameList);
			for ($index = 0; $index<sizeof($arr_staff_name); $index++){
				foreach($_SESSION['arr_all_staff_keep'] as $staff){
					$sStaffPositionName = $staff['position_code'] . " - " . $staff['name'];
					if (trim($sStaffPositionName) == trim($arr_staff_name[$index])){
						$sStaffIdList .= $staff['id'] . $delimitor;
					}
				}
			}	
			$sStaffIdList = substr($sStaffIdList,0,strlen($sStaffIdList)-1); 
		}
		return $sStaffIdList;
	}
	
	/**
	 * Creater : Phongtd
	 * Date : 30/06/2010
	 * Idea : Tao phuong thuc chuyen doi danh sach ten phong ban thanh ID tuong ung
	 *
	 * @param $sUnitNameList : Chuoi luu danh sach ten phong ban (phan tach boi dau ';')
	 * @return Danh sach ID phong ban tuong ung voi list name
	 */
	public function convertUnitNameListToUnitIdList($sUnitNameList = ""){
		$sUnitIdList = "";
		if (trim($sUnitNameList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrUnitName = explode(";",$sUnitNameList);
			//var_dump($arrUnitName); exit;
			for ($index = 0; $index<sizeof($arrUnitName); $index++){
				foreach($_SESSION['arr_all_unit_keep'] as $unit){
					if (trim($unit['name']) == trim($arrUnitName[$index])){
						$sUnitIdList .= $unit['id'] . ";";
					}
				}
			}	
			$sUnitIdList = substr($sUnitIdList,0,-1); 
		}
		return $sUnitIdList;
	}
	/**
	 * Creater : Phongtd
	 * Date : 20/07/2010
	 * Idea : Lay danh sach can bo cua phong ban
	 * @param $iDepartmentId: ID cua phong can lay
	 * @return Mang chu danh sach can bo cua 1 phong ban
	 */
	function docGetAllDepartmentStaffId($iDepartmentId){
		$i = 0;
		foreach($_SESSION['arr_all_staff_keep'] as $staffId){	
			if ($staffId['unit_id'] == $iDepartmentId){
				$arrDepartmentStaffId[$i] =  $staffId;
				$i++;
			}
		}
		return $arrDepartmentStaffId;	
	}
	/**
	 * Creater : phongtd
	 * Date : 03/08/2010
	 * Idea : Lay danh sach can bo cua phong ban
	 * @param $positionGroupCode: Nhom lanh dao, $unitID ID phong ban hoac VP, UB
	 * @return Mang chu danh sach lanh dao cua mot phong, ban
	 */
	function docGetAllLeaderDepartment($positionGroupCode,$iDepartmentId){
		$k = 0;
		foreach($_SESSION['arr_all_staff_keep'] as $staffId){	
			if ($staffId['unit_id'] == $iDepartmentId and $staffId['position_group_code'] ==$positionGroupCode ){
				$arrDepartmentStaffId[$k] =  $staffId;
				$k++;
			}
		}
		return $arrDepartmentStaffId;	
	}
	/**
	 * Creater : phongtd
	 * Date : 04/08/2010
	 * Idea : Lay nguoi ky cua don vi dang nhap hien thoi
	 * @param $arrSigner: mang nguoi ky cua DANH_MUC_NGUOI_KY
	 * @return $arrResult
	 */
	function docGetSignByUnit($arrSigner){
		$j = 0; $m = 0;
		$arr_all_staff = $_SESSION['arr_all_staff'];
		for ($i=0;$i<sizeof($arrSigner);$i++){	
			for ($m=0;$m<sizeof($arr_all_staff);$m++)	{	
				if ($arrSigner[$i]['C_CODE'] == $arr_all_staff[$m]['id']){
					$arrResult[$j]['C_CODE'] = $arrSigner[$i]['C_CODE'];
					$arrResult[$j]['C_NAME'] = $arrSigner[$i]['C_NAME'];
					$j ++;
					$m = sizeof($arr_all_staff);
				}
			}	
		}
		return $arrResult;
	}
	function _get_item_attr_by_id($p_array, $p_id, $p_attr_name) {
		foreach($p_array as $staff){
			if (strcasecmp($staff['id'],$p_id)==0){
				return $staff[$p_attr_name];
			}
		}
		return "";
	}
	/**
	 * Phongtd
	 * Ham lay danh sach id phong ban tu danh sach id can bo
	 * @param unknown_type $v_staff_id_list
	 * @param unknown_type $v_option
	 * @return unknown
	 */
	function doc_get_all_unit_permission_form_staffIdList($v_staff_id_list,$v_option = 'unit'){
		$arr_staff_id = explode(',',$v_staff_id_list);
		$v_return_string = "";
		if($v_option == 'unit'){
			for($i=0;$i< sizeof($arr_staff_id);$i ++){
				$v_return_string = $v_return_string . ',' . Efy_Function_RecordFunctions::_get_item_attr_by_id($_SESSION['arr_all_staff_keep'], $arr_staff_id[$i],'unit_id');
			}
			$v_return_string = substr($v_return_string,1);
		}
		return $v_return_string;
		}
	/**
	 * Creater : phongtd
	 * Date : 13/09/2010
	 * Idea : Tao phuong thuc Lay danh sach dien thoai tu danh sach ID NSD
	 *
	 * @param $sUnitNameList : Chuoi luu danh sach ten phong ban (phan tach boi dau ';')
	 * @return 
	 */
	public function convertIdListToTelMobileList($sIdList = ""){
		$sTelMobileList = "";
		if (trim($sIdList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrId = explode(",",$sIdList);
			for ($index = 0; $index<sizeof($arrId); $index++){
				foreach($_SESSION['arr_all_staff_keep'] as $id){
					if (trim($id['id']) == trim($arrId[$index])){
						$sTelMobileList .= $id['tel_mobile'] . ",";
					}
				}
			}	
			$sTelMobileList = substr($sTelMobileList,0,-1); 
		}
		return $sTelMobileList;
	}
	/**
	 * Creater : phongtd
	 * Date : 13/09/2010
	 * Idea : Tao phuong thuc Lay Ten/chu vu/i tu So dien thoai NSD
	 */
	public function convertTelMobileToName($sTelMobile=""){
				foreach($_SESSION['arr_all_staff_keep'] as $name){
					if (trim($name['tel_mobile']) == trim($sTelMobile)){
						$sPositionName = $name['position_code'] . " - ".$name['name'];
						break;
					}	
				}
		return $sPositionName;

	}
	/**
	 * Creater : phongtd
	 * Date : 16/09/2010
	 * Idea : Chuoi HTML checkbox gui tin nhac thong bao nhac viec tuc thoi cho LD
	 */
	public function htmlCheckboxSms(){
		$sHtml	= "<input type='checkbox' name='SmsReminder'> Gửi thông báo nhắc việc>";
		return $sHtml;
	}
	/**
	 * Creater : phongtd
	 * Date : 16/09/2010
	 * Idea : Gui thong bao nhac viec moi qua SMS cho can bo duoc nhac
	 */
	public function sendSmsNewReminder($sPositionName,$sMsg){
		$iFkStaff = self::convertStaffNameToStaffId($sPositionName);
		$sTelMobile  = self::convertIdListToTelMobileList($iFkStaff);
		$iUnitId = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iFkStaff,'unit_id');
		$sUnitName = Efy_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		$psSql = "Exec Doc_DocSmsSendUpdate ";	
		$psSql .= "'"  . $sTelMobile . "'";
		$psSql .= ",'" . $sMsg . "'";
		$psSql .= ",'Send'";
		$psSql .= ",'" . $sPositionName . "'";
		$psSql .= ",'" . $sUnitName . "'";		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	* Nguoi tao: phongtd
	* Ngay tao: 25/10/2010
	* Y nghia:Lay Mang danh muc doi tuong cua mot danh muc
	* Input: Ma danh muc
	* Output: Mang cac doi tuong cua loai danh muc ung voi ma truyen vao
	* $optCache = 1: Luu cache 
	*/
	public function getAllObjectbyListCode($sOwnerCode,$sCode, $optCache = ""){
		// Tao doi tuong xu ly du lieu
		$objConn = new  Efy_DB_Connection(); 
		$sql = "EfyLib_ListGetAllbyListtypeCode ";
		$sql = $sql . " '" . $sOwnerCode . "'";
		$sql = $sql . " ,'" . $sCode . "'";
		//echo $sql . '<br>';exit;
		try {
			$arrObject = $objConn->adodbQueryDataInNameMode($sql,$optCache);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrObject;
	}
	/**
	 * Creater: phongtd
	 * Date:	29/10/2010
	 * Des: Lay thong tin TTHC
	 * @param unknown_type $sStaffId Id nguoi dang nhap hien thoi
	 * @param unknown_type $sOwnerCode Ma don vi su dung 
	 * @param unknown_type $sClauseString Menh de dieu kien SQL
	 */
	public function eCSRecordTypeGetAllByStaff($sStaffId, $sOwnerCode, $sClauseString = ''){
		$objConn = new  Efy_DB_Connection(); 
		$sql = "Exec eCS_RecordTypeGetAllByStaff ";
		$sql = $sql . "'" . $sStaffId . "'";
		$sql = $sql . ",'" . $sOwnerCode . "'";
		$sql = $sql . ",'" . $sClauseString . "'"; 
		try{
			$arrRecordType  = $objConn->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrRecordType;
	}
	/**
	 * Creater: phongtd
	 * Date: 29/10/2010
	 * Des: Kiem qua quyen tiep nhan cua nguoi su dung
	 * @param unknown_type $sStaffId Id nguoi dang nhap hien thoi
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 */
	public function eCSPermisstionReceiverForRecordType($sStaffId, $arrRecordType){
		$isyes = 0;
		$sStaffId = ',' . $sStaffId . ',';
		foreach ($arrRecordType as $recordType){
			$strtemp = ',' . $recordType['C_RECEIVER_ID_LIST'] . ',';
			if(stripos($strtemp, $sStaffId) !== false){
				$isyes = 1;
				break;
			}
		}
		if($isyes == 0)	return false;
		else return true;
	}
 	/**
	 * Creater: phongtd
	 * Date: 29/10/2010
	 * Des: Kiem qua quyen thu ly cua nguoi su dung
	 * @param unknown_type $sStaffId Id nguoi dang nhap hien thoi
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 */
	public function eCSPermisstionHandlerForRecordType($sStaffId, $arrRecordType){
		$isyes = 0;
		$sStaffId = ',' . $sStaffId . ',';
		foreach ($arrRecordType as $recordType){
			$strtemp = ',' . $recordType['C_HANDLER_ID_LIST'] . ',';
			if(stripos($strtemp, $sStaffId) !== false){
				$isyes = 1;
				break;
			}
		}
		if($isyes == 0)	return false;
		else return true;
	}
 	/**
	 * Creater: phongtd
	 * Date: 29/10/2010
	 * Des: Kiem qua quyen phe duyet cua nguoi su dung
	 * @param unknown_type $sStaffId Id nguoi dang nhap hien thoi
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 */
	public function eCSPermisstionApproveForRecordType($sStaffId, $sApproveLevel, $arrRecordType, $sRecordTypeId = ""){
		$isyes = 0;
		for($i = 0; $i < sizeof($arrRecordType); $i++){
			$arrLeaderId = explode(',', $arrRecordType[$i]['C_APPROVE_LEADER_ID_LIST']);
			$arrrolecode = explode(',', $arrRecordType[$i]['C_ROLES_CODE_LIST']);
			//
			if ($sRecordTypeId == ""){
				if(in_array($sStaffId, $arrLeaderId)){
					if($sApproveLevel == ''){
						return true; break;
					}
				}
			}else{
				if 	($arrRecordType[$i]['PK_RECORDTYPE'] == $sRecordTypeId){
					for($j=0;$j<sizeof($arrLeaderId);$j++){
						if( ($arrLeaderId[$j] == $sStaffId) && ($arrrolecode[$j] == $sApproveLevel)){
							return $sApproveLevel; break;
						}	
					} 
				}					
			}
		}
		return false;
	}
 	/**
	 * Ham lay danh sach ho so da tiep nhan
	 * @param $sRecordTypeId			Id loai ho so
	 * @param $iCurrentStaffId			Id nguoi dang nhap hien thoi
	 * @param $sReceiveDate				Ngay tiep nhan
	 * @param $sStatusList				Danh sach trang thai
	 * @param $sDetailStatusCompare		Menh de dieu kien sql
	 * @param $sRole					Nhom quyen
	 * @param $sOrderClause				Menh de sap xep
	 * @param $sOwnerCode				Ma don vi su dung
	 * @param $sfulltextsearch			Tu, cum tu tim kiem
	 * @param $iPage					Trang hien thoi
	 * @param $iNumberRecordPerPage		So ban ghi tren trang
	 */
	public function eCSRecordGetAll($sRecordTypeId,$sRecordType,$iCurrentStaffId, $sReceiveDate, $sStatusList, $sDetailStatusCompare, $sRole, $sOrderClause, $sOwnerCode, $sfulltextsearch, $iPage, $iNumberRecordPerPage){		
		$objConn = new  Efy_DB_Connection(); 
		$sql = "Exec eCS_RecordGetAll ";
		$sql = $sql . "'" . $sRecordTypeId . "'";
		$sql = $sql . ",'" . $sRecordType . "'";
		$sql = $sql . ",'" . $iCurrentStaffId . "'";
		$sql = $sql . ",'" . $sReceiveDate . "'";
		$sql = $sql . ",'" . $sStatusList . "'";	
		$sql = $sql . ",'" . $sDetailStatusCompare . "'";	
		$sql = $sql . ",'" . $sRole . "'";
		$sql = $sql . ",'" . $sOrderClause . "'";
		$sql = $sql . ",'" . $sOwnerCode . "'";
		$sql = $sql . ",'" . $sfulltextsearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";	
		echo $sql . '<br>';// exit;
		try{
			$arrResul = $objConn->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
 	/**
	 * Ham lay thong tin chi tiet mot ho so
	 * @param  $sRecordId		Ma ho so
	 * @param  $sRecordType		Loai ho so: LIEN_THONG, KHONG_LIEN_THONG
	 * @param  $sOwnerCode		Ma don vi su dung
	 * @param  $sRecordTransitionId		Ma ho so lien thong (neu co)
	 */
	public function eCSRecordGetSingle($sRecordId,$sOwnerCode,$sRecordTransitionId = null){
		$objConn = new  Efy_DB_Connection(); 
		$sql = "Exec [dbo].[eCS_RecordGetSingle] ";
		$sql .= "'" . $sRecordId . "'";
		$sql .= ",'" . $sOwnerCode . "'";
		$sql .= ",'" . $sRecordTransitionId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrSendReceived = $objConn->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrSendReceived;
	}
 	/**
	 * Ham lay thong tin chi tiet mot ho so phuc vu cho viec in 
	 * @param  $sRecordId		Ma ho so
	 * @param  $sRecordType		Loai ho so: LIEN_THONG, KHONG_LIEN_THONG
	 * @param  $sOwnerCode		Ma don vi su dung
	 * @param  $stagList		Danh sach the can lay gia tri phan cach bang dau ","
	 * @param  $sRecordTransitionId		Ma ho so lien thong (neu co)
	 */
	public function eCSRecordGetSingleForPrint($sRecordId,$sOwnerCode,$stagList,$sRecordTransitionId = null){
		$objConn = new  Efy_DB_Connection(); 
		$sql = "Exec [dbo].[eCS_RecordGetSingleforPrint] ";
		$sql .= "'" . $sRecordId . "'";
		$sql .= ",'" . $sOwnerCode . "'";
		$sql .= ",'" . $stagList . "'";
		$sql .= ",'" . $sRecordTransitionId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrSendReceived = $objConn->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrSendReceived;
	}
 	/**
	 * Creater: phongtd
	 * Date: 29/10/2010
	 * Des: Tao form loc tieu chi tim kiem tren man hinh danh sach
	 * @param unknown_type $sStaffId Id nguoi dang nhap hien thoi
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 * @param $sRecordTypId : Pk cua TTHC duoc chon
	 * @param unknown_type $roles Vai tro nguoi dang nhap hien thoi
	 */
	public function genEcsFilterFrom($staffId, $roles, $arrRecordType, $arrInput){
		$shtml = '<div id = "filter">';
		$sselect = '<select id = "recordType" name = "recordType" style="width:90%;" onChange="form.submit();" >';
		$staffIdTemp = ',' . $staffId . ',';
		if($roles == 'TIEP_NHAN'){
			foreach ($arrRecordType as $recordType){
				$strtemp = ',' . $recordType['C_RECEIVER_ID_LIST'] . ',';
				if(stripos($strtemp, $staffIdTemp) !== false)
				if ($recordType['PK_RECORDTYPE']  == $arrInput['RecordTypeId']) $sCheck = "selected='selected'";
				else $sCheck = "";
					$sselect .= '<option value = "'. $recordType['PK_RECORDTYPE'] . '" '.$sCheck.'  >' . $recordType['C_NAME'] . '</option>';
			}
		}
		else if($roles == 'THU_LY'){
			foreach ($arrRecordType as $recordType){
				$strtemp = ',' . $recordType['C_HANDLER_ID_LIST'] . ',';
				if(stripos($strtemp, $staffIdTemp) !== false)
					$sselect .= '<option value = "'. $recordType['PK_RECORDTYPE'] . '">' . $recordType['C_NAME'] . '</option>';
			}
		}
		else if($roles == 'DUYET_CAP_MOT' || $roles == 'DUYET_CAP_HAI' || $roles == 'DUYET_CAP_BA'){
			for($i = 0; $i < sizeof($arrRecordType); $i++){
				$arrLeaderId = explode(',', $arrRecordType[$i]['C_APPROVE_LEADER_ID_LIST']);
				$arrrolecode = explode(',', $arrRecordType[$i]['C_ROLES_CODE_LIST']);
				for($j = 0; $j < sizeof($arrLeaderId); $j++){
					if($staffId == $arrLeaderId[$j] && $arrrolecode[$j] == $roles){
						$sselect .= '<option value = "'. $arrRecordType[$i]['PK_RECORDTYPE'] . '">' . $arrRecordType[$i]['C_NAME'] . '</option>';
						break;
					}
				}		
			}
		}
		$sselect .= '</select>';
		$shtml .= '<div>' . $sselect . '</div>';
		$shtml .= '<div><input type = "textbox" value = "'.$arrInput['fullTextSearch'].'" name = "txtfullTextSearch" id = "txtfullTextSearch" style="width:80%;" /><input style="margin-left:2%;margin-bottom:0;" type="button" value="Tìm kiếm" class="add_large_button"  onclick="actionUrl(\''.$arrInput['pUrl'].'\');" /></div>';
		$shtml .= '</div>';
		return $shtml;
	}
	/**
	 * Creater: phongtd
	 * Date: 1/11/2010
	 * Des: Ham sinh ma cua mot ho so
	 * @param $srecordtype Ma Loai TTHC
	 */
	 function generateRecordCode($srecordtype){
	 	$objConn = new  Efy_DB_Connection(); 
		$v_inc_code_length = 5;//Do dai cua ma theo tung loai, tung nam cua ho so
		$v_fix_code = $_SESSION['OWNER_CODE'].".".$srecordtype.".".date("y");
		$v_str_count = strlen($v_fix_code);
		$v_str_sql = " Select Max(SUBSTRING(C_CODE, $v_str_count+2, $v_inc_code_length)) MAX_CODE ";
		$v_str_sql = $v_str_sql." From T_eCS_RECORD";
		$v_str_sql = $v_str_sql." Where SUBSTRING(C_CODE,1,$v_str_count) = '$v_fix_code' And LEN(C_CODE)=($v_inc_code_length+$v_str_count+1)";
		try{
			$arr_all_data = $objConn->adodbExecSqlString($v_str_sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		$v_next_code = $arr_all_data['MAX_CODE'];
		if (is_null($v_next_code) || $v_next_code==""){
			$v_next_code = 1;
			$v_next_code = str_repeat("0",$v_inc_code_length-strlen($v_next_code)).$v_next_code;
		}else{
			$v_next_code = intval($v_next_code)+1;
			$v_next_code = str_repeat("0",$v_inc_code_length-strlen($v_next_code)).$v_next_code;
		}
		$v_str_ret = $v_fix_code.".".$v_next_code;
		return $v_str_ret;
	}
	/**
	 * Creater: phongtd
	 * Date: 2/11/2010
	 * Des: Ham lay thong tin co ban cua mot loai thu tuc hanh chinh
	 * @param $RecordTypeId Ma Loai TTHC
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 */
 	function getinforRecordType($RecordTypeId, $arrRecordType){
		$arrResult = array();
		foreach ($arrRecordType as $recordType){
			if($recordType['PK_RECORDTYPE'] == $RecordTypeId){
				$arrResult = $recordType;
				break;
			}
		}
		return $arrResult;
	}
	/**
	 * Creater : phongtd
	 * Date : 04/11/2010
	 * Idea : Hien thi thong tin co ban cua mot HS( hien thi thong tin hs cho can bo trong phong ban giai quyet hs do)
	 *
	 * @param $sRecordPk : Id cua HS
	 * @param $iFkUnit : Id cua Phong ban
	 * @param $sOwnerCode : Ma don vi
	 * @return Thong tin co ban cua hs
	 */		
	public function eCSRecordBasicGetSingle($sRecordPk,$iFkUnit,$sOwnerCode){	
		// Tao doi tuong xu ly du lieu
		$objConn = new  Efy_DB_Connection(); 		
		//Tao duoi tuong trong lop dung chung
		$objLib = new Efy_Library();
		$objRecordFunction	     = new Efy_Function_RecordFunctions();	
		//Lay cac gia tri const
		$ojbEfyInitConfig = new Efy_Init_Config();
		$arrConst =	$ojbEfyInitConfig->_setProjectPublicConst();
		try {
			// Chuoi SQL
			$sql = "Exec eCS_RecordBasicGetSingle " ;
			$sql .= "'" . $sRecordPk . "'" ;
			$sql .= ",'" . $iFkUnit . "'";
			$sql .=  ",'" . $sOwnerCode . "'";
			// Truy van CSDL
			$arrTemp = $objConn->adodbExecSqlString($sql); 
			$sHandleStaff = $arrTemp['C_HANDLE_POSITION_NAME'];
			if(trim($sHandleStaff) !='') $sHandleStaff = $sHandleStaff .', ';
			// In ra ket qua
			$ResHtmlString = "<div class = 'large_title' style='padding-left: 1px; text-align: left; float: left;'>".$arrConst['_THONG_TIN_HS']."</div>";
			$ResHtmlString = $ResHtmlString . "<table class='table_detail_doc' border='1' width='98%'>";
			$ResHtmlString = $ResHtmlString . "<col width='30%'><col width='70%'>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_TEN_TTHC']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $arrTemp['C_NAME']."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_MA_HO_SO']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $arrTemp['C_CODE']."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_NGAY_TIEP_NHAN']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . Efy_Library::_yyyymmddToDDmmyyyyhhmm($arrTemp['C_RECEIVED_DATE'])."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_NGAY_HEN']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . Efy_Library::_yyyymmddToDDmmyyyyhhmm($arrTemp['C_APPOINTED_DATE'])."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_NOI_THU_LY']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $sHandleStaff. $objRecordFunction->getNameUnitByIdUnitList($iFkUnit)."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'>";
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$arrConst['_TRANG_THAI_XU_LY']. "</td>"; 
			$ResHtmlString = $ResHtmlString . "	<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $arrTemp['C_CURRENT_STATUS']."</td>";
			$ResHtmlString = $ResHtmlString . "</tr>";
			$ResHtmlString = $ResHtmlString . "</table>";
			// Tra lai gia tri
			return $ResHtmlString;							
		}catch (Exception $e){
			$e->getMessage();
		}
	}
 	/**
	 * Creater: phongtd
	 * Date: 8/11/2010
	 * Des: Ham lay lay thon cua mot ho so
	 * @param $srecordId Ma ho so
	 * @param $arrRecord Mang luu danh sach ho so
	 * @param $columnName Ten cot can lay gia tri
	 */
 	function getRecordTransitionId($srecordId, $arrRecord, $columnName){
 		$svalue = '';
		foreach ($arrRecord as $srecord){
			if($srecord['PK_RECORD'] == $srecordId){
				return $srecord[$columnName];
			}
		}
		return $svalue;
	}
	/**
	 * Creater: phongtd
	 * Date: 8/11/2010
	 * Des: Ham lay danh sach id phong ban tu danh sach id can bo
	 * @param $sStaffIdList danh sach id can bo
	 * @param $arrAllStaff Mang luu thong tin can bo
	 */
	function GetUnitIdListFromStaffIdList($sStaffIdList, $arrAllStaff){
		$arrStaffId = explode(',', $sStaffIdList);
		$sUnitIdList = '';
		foreach ($arrStaffId as $sStaffId){
			foreach ($arrAllStaff As $staff)
	 			if ($sStaffId == $staff['id']){
	 				$sUnitIdList .= $staff['unit_id'].',';
	 				break;
	 			}
		}
		return substr($sUnitIdList, 0, -1);
	}
 	/**
	 * Creater: phongtd
	 * Date: 8/11/2010
	 * Des: Ham tra ve quyen thu ly cua NSD tren mot loai TTHC: 'THULY_CAP_MOT,..'
	 * @param $RecordTypeId Ma Loai TTHC
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 * $sRecordTypeId id cua TTHC
	 */
 	function eCSPermisstionHandlerTypeForRecordType($sStaffId, $arrRecordType, $sRecordTypeId){
 		//Lay id phong ban cua $sStaffId
 		$iUnitId = '';
 		$sHandleType = '';
 		foreach ($_SESSION['arr_all_staff'] As $staff)
 			if ($sStaffId == $staff['id']){
 				$iUnitId = $staff['unit_id'];
 				break;
 			}
 		//Lay danh sach id phong ban tu danh sach can bo phe duyet	
		foreach ($arrRecordType as $sRecordType){
			if($sRecordTypeId == $sRecordType['PK_RECORDTYPE']){
				$sUnitIdList = self::GetUnitIdListFromStaffIdList($sRecordType['C_APPROVE_LEADER_ID_LIST'], $_SESSION['arr_all_staff']);
				$arrUnitIdList = explode(',', $sUnitIdList);
				$arrrolecode = explode(',', $sRecordType['C_ROLES_CODE_LIST']);
				for($i = 0; $i < sizeof($arrUnitIdList); $i++){
					if($iUnitId == $arrUnitIdList[$i]){
						$sHandleType = $arrrolecode[$i];
						break;
					}
				}
			}
			break;
		}
		$sHandleType = str_replace('DUYET', 'THULY', $sHandleType);
		return $sHandleType;
	}
	/*
	 	* Creater: phongtd
		* Date: 10/11/2010
		*Content : Lay File dinh kem cua mot ho hoac cong viec
	*/
	public function eCSGetAllDocumentFileAttach($sRecordId, $pFileTyle, $pTableObject){
		// Tao doi tuong xu ly du lieu
		$objConn = new  Efy_DB_Connection(); 	
		$sql = "Exec Doc_GetAllDocumentFileAttach '" . $sRecordId . "'";
		$sql .= ",'".$pFileTyle ."'";		
		$sql .= ",'".$pTableObject ."'"; 
		//echo $sql . '<br>';exit;
		try {						
			$arrResult = $objConn->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;			
	}
 	/**
	 * Creater: phongtd
	 * Date: 17/11/2010
	 * Des: Ham tra ve quyen phe duyet cua NSD tren mot loai TTHC: 'DUYET_CAP_MOT,..'
	 * @param $RecordTypeId Ma Loai TTHC
	 * @param unknown_type $arrRecordType Mang nay la ket qua cua ham eCSRecordTypeGetAllByStaff
	 * $sRecordTypeId id cua TTHC
	 */
 	function eCSGetPermisstionApproveForRecordType($sStaffId, $arrRecordType, $sRecordTypeId){	
		foreach ($arrRecordType as $sRecordType){
			if($sRecordTypeId == $sRecordType['PK_RECORDTYPE']){
				$arrApproveLeaderIdList = explode(',',$sRecordType['C_APPROVE_LEADER_ID_LIST']);
				$arrrolecode = explode(',', $sRecordType['C_ROLES_CODE_LIST']);
				for($i = 0; $i < sizeof($arrApproveLeaderIdList); $i++){
					if($sStaffId == $arrApproveLeaderIdList[$i]){
						return $arrrolecode[$i];
					}
				}
			}
		}
		return '';
	}
 	/** Nguoi tao: phongtd
		DS: Ham cap nhat thong tin chuyen thu ly
		* @param $sRecordIdList Dang sach Id ho so
		* $sOwnerCode Ma don vi su dung
	*/
	public function eCSGetInfoRecordFromListId($sRecordIdList, $sOwnerCode){
		$objConn = new  Efy_DB_Connection(); 	
		$arrResult = null;
		$sql = "Exec eCS_GetInfoRecordFromListId ";
		$sql .= "'" . $sRecordIdList . "'";
		$sql .= ",'" . $sOwnerCode . "'";
		//echo $sql; exit;
		try{
			$arrResul = $objConn->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
 	/** Nguoi tao: phongtd
		idea: Ham chuyen ma trang thai thanh ten 
		@param: $psStatus: Ma trang thai
	*/
	public function getNamStatus($psStatus){
		$StatusName = "";
		if($psStatus == "MT_CHO_DUYET") $StatusName = "Đăng ký mượn chờ duyệt";
		if($psStatus == "MT_DA_DUYET") $StatusName = "Đăng ký mượn đã duyệt";	
		if($psStatus == "MT_BI_TU_CHOI") $StatusName = "Đăng ký mượn bị từ chối";	
		if($psStatus == "DANG_SU_DUNG") $StatusName = "Đang sử dụng";	
		if($psStatus == "TRA_LAI") $StatusName = "Đã trả lại";	
		return $StatusName;
	}

 }	