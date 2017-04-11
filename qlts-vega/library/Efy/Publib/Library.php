<?php 

/**
 * Nguoi tao: HUNGVM 
 * Ngay tao: 18/11/2008
 * Noi dung: Tao lop Efy_Publib_Library luu cac ham dung chung
 */
abstract class Efy_Publib_Library {		
	/**
	 * Creater : HUNGVM
	 * Date : 16/06/2010
	 * Idea : Tao phuong thuc khoi tao gia tri luu trong Cookie
	 *
	 * @param $sName : Ten Cookie
	 * @param $sValue : Gia tri luu trong Cookie
	 */
	public function _createCookie($sName,$sValue = ""){ 
		//Dat gia tri expires
		$dExpires = time() + 60*60*24; 
		//Dat gia tri vao Cookie
		setcookie($sName, $sValue, $dExpires,"/",""); 
	} 
	
	/**
	 * Creater : HUNGVM
	 * Date : 16/06/2010
	 * Idea : Tao phuong thuc lay gia tri luu trong Cookie voi $sNname tuong ung
	 *
	 * @param $sNname : Ten Cookie
	 * @return Gia tri luu trong Cookie, neu chua ton tai return false
	 */
	public function _getCookie($sNname){ 
		if (isset($_COOKIE[$sNname]) && strlen($_COOKIE[$sNname])>0 ) { 
			return urldecode($_COOKIE[$sNname]); 
		}else{ 
			return ""; 
		} 
	
	}
	/**
	 * 
	 */		
	//********************************************************************************
	//Ten ham		:_xmlStringToArray
	//Chuc nang	: Chuyen doi mot xau XML thanh mot array
	//Tham so: $p_xmlstring - la xau XML can chuyen doi. Xau XML nay chi co 2 cap, vi du
	//Cach su dung:
	//- Truoc khi goi ham nay, phai khai bao cac bien sau:
	//+ $p_arr_items: la array ket qua
	//+ $p_level1_tag_name: ten tag dau tien cua xau XML (trong vi du tren thi $p_level1_tag_name=staff_list)
	//+ $p_level2_tag_name_list: danh sach tag can chuyen gia tri vao array: (trong vi du tren thi $p_level1_tag_name="id,code")
	//+ $p_delimitor: ki tu phan cach cac phan tu cua $p_level2_tag_name_list
	//********************************************************************************
	public function _xmlStringToArray($xmlData){		
		global $p_arr_items,$p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		
		//Tao doi tuong de doc du lieu dang XML
		$objXML = new SimpleXMLElement($xmlData); 
		
		//Lay du lieu tu phan tu $p_level1_tag_name
		$result = $objXML->xpath($p_level1_tag_name);

		
		//Chuyen cac phan tu cua xau -> mang mot chieu
	   	$arr_node = explode($p_delimitor,$p_level2_tag_name_list);
	   	
	   	//So phan tu cua mang
	   	$count = 0;
	   	
	   	//Chuyen doi du lieu tu XML -> array theo chuan array[index][node] trong do
	   	//index: La chi so cua array; node : La cac then trong xau XML
		
		foreach($result as $xmlValue){
			for($index = 0;$index < sizeof($arr_node);$index++){
				$p_arr_items[$count][$arr_node[$index]] = (string)$xmlValue->$arr_node[$index];				
			}
	    	$count++; //Tang so phan tu cua mang len
	    }
	    	    
		return $p_arr_items;
	}
	
	
	/**
	 * Creater: HUNGVM
	 * Date: 25/11/2008
	 * Idea : Lay toan bo thong tin(ID, NAME) cua don vi su dung
	 *
	 * @param $p_arr : Mang chua danh sach phong ban (don vi)
	 * @return Mang luu thong tin don vi
	 */	
	public function _getAllOwner ($p_arr = array()){
		$v_unit_level1_id="";
		//var_dump($p_arr); exit;
		//Goi phuong thuc lay ID cua don vi (phong ban) cha
		$v_root_id = $this->_getRootUnitId($p_arr);
		
		$arr_list_owner = array();
		$v_index = 0;
		
		//Lay thong tin cac phong ban	
		$arr_all_unit = $p_arr;
				
		foreach($arr_all_unit as $v_unit){
			if (strcasecmp($v_unit['parent_id'], $v_root_id) == 0){			
				$v_parent_name 		=  $v_unit['name'];
				$v_parent_code 		=  $v_unit['code'];
				$arr_list_owner[$v_index]['OWNER_CODE'] = $v_parent_code;
				$arr_list_owner[$v_index]['OWNER_NAME'] = $v_parent_name;
				$v_index++;
			}
		}
		return $arr_list_owner;
	}
	/**
	 * ******************************************************************************************************************
	 * @Idea   :Lay id goc cua don vi (phong ban) cha
	 * @return : Id cua thu muc goc
	 ********************************************************************************************************************* 
	 */
	public function _getRootUnitId($p_arr=array()){
		$v_root_id = "";
		
		//Lay thong tin cac phong ban	
		$arr_all_unit = $p_arr;
		$arr_all_unit = str_replace('\n','',$arr_all_unit);
		//var_dump($arr_all_unit);
		foreach ($arr_all_unit as $v_unit){			
			if (is_null($v_unit['parent_id']) || trim($v_unit['parent_id']) == '' || trim($v_unit['parent_id']) == 'NULL'){
				$v_root_id = $v_unit['id']; //Don vi goc 
				break;
			}
			unset($v_unit);	
		}
		return $v_root_id;
	}		
	
	/**
	 * Creater : HUNGVM
	 * Date :
	 * Idea : copy file $pToFile vao thu muc chi dinh$pFromFile
	 *
	 * @param $pFromFile : Duong dan luu file
	 * @param $pToFile : Ten file chua noi dung
	 */
	public function _copyFile($pFromFile, $pToFile){		
		//Goi ham copy file cua he thong PHP
		move_uploaded_file ($pFromFile,$pToFile);
	}	
		
	/**
	 * Creater : HUNGVM
	 * Date : 09/01/2009
	 * Idea: Doc file
	 * @param $spFilePath : Duong dan file can doc
	 * @return Noi dung file
	 */
	public function _readFile($spFilePath){
		$spRet = "";
		$handle = fopen($spFilePath,"r");
		if($handle){
			while(!feof($handle)){
				$spRet .= fread($handle,10000);
			}
		}
		return $spRet;
	}	

	/**
	 * Creater : HUNGVM
	 * Date : 09/01/2009
	 * Idea: Ghi file
	 * @param $spFilePath : Duong dan file can ghi
	 * @param $spContent : Noi dung can ghi vao file
	 */
	public function _writeFile($spFilePath, $spContent){		
		if (file_exists($spFilePath)) {
		   chmod($spFilePath,0777);	
		}
		$handle = fopen ($spFilePath, "w+");
		if ($handle){
			fwrite($handle, $spContent);
			fclose($handle);
		}	
		//echo $handle;exit;
	}	

	/**
	 * Creater : HUNGVM
	 * Date : 21/05/2009
	 * Idea : Tao phuong thuc xoa file trong thu muc $sPathDir
	 *
	 * @param $sFileNameList : Danh sach file can xoa
	 * @param $sDelimitor    : Ky tu phan tach giua cac phan tu
	 * @param $sPathDir		 : Duong dan luu file can xoa
	 */
	public function _deleteFile($sFileNameList, $sDelimitor, $sPathDir){
 		$arrFileName = explode($sDelimitor,$sFileNameList); //Mang luu ten file
    	$iCount = sizeof($arrFileName);		
		for($index = 0; $index < $iCount; $index++){
			$sFullPathFileName =  $sPathDir . $arrFileName[$index];
			if(file_exists($sFullPathFileName)){ 
				if(is_writable($sFullPathFileName)){
					unlink($sFullPathFileName);					
				}else{		
					echo "<font color='red'> <b>File $arrFileName[$index]: can xoa khong cho phep truy cap!</b></font> <br /> ";				
				}
			}else{
				echo "<font color='red'> <b>File $arrFileName[$index]: can xoa khong ton tai trong :" . $sPathDir . "</b></font> <br /> ";				
			}	
		}			
	 }
	/**
	 * Idea: Sinh ra doan ma HTML the hien cac option cuar mot SelectBox 
	 *
	 * @param + $arr_list : mang du lieu
	 * @param + $IdColumn : Ten cot lay gia tri gan cho moi option
	 * @param + $ValueColumn
	 * @param $NameColumn  : Ten cot lay de hien thi cho moi option
	 * @param $SelectedValue : Gia tri duoc lua chon
	 * @return Chuoi HTML hien thi selectbox
	 */
	
	public function _generateSelectOption($arr_list,$IdColumn,$ValueColumn,$NameColumn,$SelectedValue){
		$strHTML = "";
		$i=0;
		$count=sizeof($arr_list);
		for($row_index = 0;$row_index< $count;$row_index++){
			$strID=trim($arr_list[$row_index][$IdColumn]);
			$strValue=trim($arr_list[$row_index][$ValueColumn]);
			$gt=$SelectedValue;
			if($strID != $SelectedValue) {
				$optSelected="";
			} else {
				$optSelected="selected";
			}
			$DspColumn=trim($arr_list[$row_index][$NameColumn]);
			$strHTML.='<option id='.'"'.$strID.'"'.' '.'name='.'"'.$DspColumn.'"'.' ';
			$strHTML.='value='.'"'.$strValue.'"'.' '.$optSelected.'>'.$DspColumn.'</option>';
			$i++;
		}
		return $strHTML;
	}
	
	/**
	 * Thay the cac ki tu dac biet trong mot xau boi ki tu khac
	 *
	 * @param $p_string : Chuoi can thay the
	 * @return : Chuoi da thay the ky tu
	 */	
	public function _replaceBadChar($spString) {
		$psRetValue = stripslashes($spString);
		$psRetValue = str_replace('<','&lt;', $psRetValue);
		$psRetValue = str_replace('>','&gt;',$psRetValue);
		$psRetValue = str_replace('"','&#34;', $psRetValue);
		$psRetValue = str_replace("'",'&#39;', $psRetValue);		
		return $psRetValue;
	}
	
	/**
	 * Lay gia tri lon nhat cua mot cot trong mot table va cong them 1
	 *
	 * @param unknown_type $p_table
	 * @param unknown_type $p_column
	 * @param unknown_type $p_where_clause
	 * @return unknown
	 */	
	public function _getNextValue($psTable, $psColumn, $psWhereClause){
		global $adoConn;
		Zend_Loader::loadClass('Efy_Db_Connection');
		$objConn = new Efy_DB_Connection();
		
		$cmd = "Select max(" . $psColumn . ")" . " MAX_VALUE " . " From " . $psTable ;
		if (!is_null(trim($psWhereClause)) and trim($psWhereClause)<>""){
			$cmd = $cmd . " Where " . $psWhereClause ;
		}
		
		//Thuc hien cau lenh SQL
		$arrResult = $objConn->adodbQueryDataInNameMode($cmd);
						
		//Lay gia tri thu tu hien tiep theo
		$piNextValue = $arrResult[0]['MAX_VALUE'];
		if (!is_null($piNextValue)){
			$piNextValue = intval($piNextValue)+1;
		}else{
			$piNextValue = 1;
		}		
		return $piNextValue;
	}	
	
	/**
	 * tra lai gia tri true neu DB hien thoi la SQL-SERVER
	 *
	 * @return unknown
	 */	
	public function _isSqlserver(){
		global $_ISA_DB_TYPE;
		return ($_ISA_DB_TYPE == "SQL SEVER");
	}
	
	/**
	 * Creater : HUNGVM
	 * Date: 11/01/2009
	 * Idea: Tao mang mot chieu, mang co cau truc cac phan tu theo kieu
	 * array(pt1=>pt2, pt3=>pt4)
	 *
	 * @param $pArraySource : Mang chua du lieu can lay thong tin de tao mang mot chieu
	 * @param $psColumName01 : Ten cot (VD: PK_)
	 * @param $psColumName02 : : Ten cot (VD: C_NAME)
	 * @return Mang mot chieu
	 */
	public function _createOneDimensionArray($pArraySource, $psColumName01, $psColumName02){
		$arrResultArray = array();			
		if (is_array($pArraySource) && sizeof($pArraySource) > 0){
			//Duyet cac phan tu cua mang
			for($index = 0;$index < sizeof($pArraySource);$index++){				
				// Lay gia tri cua $psColumName01
				$psColumName01Value 		= $pArraySource[$index][$psColumName01];					
				// Lay gia tri cua $psColumName02
				$psColumName02Value 		= $pArraySource[$index][$psColumName02];
				//Chuyen cac phan tu vao mang
				$arrResultArray[$psColumName01Value] = $psColumName02Value;
			}
		}
		return $arrResultArray;
	}
	
	/**
	 * Thay the ky tu thong thuong bang ky tu dac biet
	 *
	 * @param $pshtml : Xau can thay the
	 * @return Xau da duoc thay the
	 */
	public function _restoreXmlBadChar($pshtml){
		$pshtml = str_replace('&amp;','&',$pshtml);
		$pshtml = str_replace('&quot;','"',$pshtml);
		$pshtml = str_replace('&#39;',"'",$pshtml);
		$pshtml = str_replace('&lt;','<',$pshtml);
		$pshtml = str_replace('&gt;','>',$pshtml);
		$pshtml = str_replace('&#34;','"',$pshtml);
		$pshtml = htmlspecialchars($pshtml);
		return $pshtml;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 12/01/2009
	 * 
	 * @Idea: Thay the ky tu dac biet trong xau XML
	 *
	 * @param $psHtml : Xau can thay the
	 * @return CHuoi duoc thay the cac ky tu dac biet
	 */
	public function _replaceXmlBadChar($psHtml){
		$psHtml = stripslashes($psHtml);
		$psHtml = str_replace('&','&amp;',$psHtml);
		$psHtml = str_replace('"','&quot;',$psHtml);
		$psHtml = str_replace('<','&lt;',$psHtml);
		$psHtml = str_replace('>','&gt;',$psHtml);
		$psHtml = str_replace("'",'&#39;', $psHtml);
		return $psHtml;
	}
	
	
	/**
	 * Idea: chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang dd/mm/yyyy
	 *
	 * @param $psYyymmdd: la thoi gian dang chuoi
	 * @return Chuoi dang dd/mm/yyyy(De hien thi tren man hinh)
	 */	
	public function _yyyymmddToDDmmyyyy($psYyymmdd) {
		//echo '<br>xx:'.$psYyymmdd;
		if (is_null($psYyymmdd) || trim($psYyymmdd) == "" || $psYyymmdd ==1900)		
		return "";
		return date("d/m/Y", strtotime($psYyymmdd));
	}
	
	/**
	 * Idea: chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang dd/mm/yyyy hh:mm
	 *
	 * @param $psYyyymmdd : la thoi gian dang chuoi
	 * @return chuoi dang dd/mm/yyyy hh:mm (De hien thi tren man hinh)
	 */	
	public function _yyyymmddToDDmmyyyyhhmm($psYyyymmdd) {
		if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
			return "";
		return date("d/m/Y H:i", strtotime($psYyyymmdd));
	}
	
	/**
	 * Idea: chuyen doi thoi gian dang yyyymmdd thanh chuoi dang dd/mm/yyyy hh:mm:ss
	 *
	 * @param $psYyyymmdd :la thoi gian dang chuoi
	 * @return chuoi dang dd/mm/yyyy hh:mm:ss (De hien thi tren man hinh)
	 */	
	public function _yyyymmddToDDmmyyyyhhmmss($psYyyymmdd) {
		if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
			return "";
		return date("d/m/Y H:i:s", strtotime($psYyyymmdd));
	}
	
	/**
	 * Idea: chuyen doi thoi gian dang yyyymmdd thanh chuoi dang yyyy-mm-dd hh:mm:ss
	 *
	 * @param $psYyyymmdd : la thoi gian dang chuoi
	 * @return chuoi dang dd/mm/yyyy hh:mm:ss (De hien thi tren man hinh)
	 */	
	public function _yyyymmddToYYyymmddhhmmss($psYyyymmdd) {
		if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
			return "";
		return date("Y/m/d H:i:s", strtotime($psYyyymmdd));
	}
	
	/**
	 * Idea: chuyen doi thoi gian dang yyyymmdd thanh chuoi dang hh:mm
	 *
	 * @param $psYyyymmdd : la thoi gian dang chuoi
	 * @return chuoi hh:mm(De hien thi tren man hinh)
	 */	
	public function _yyyymmddToHHmm($psYyyymmdd) {
		if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
			return "";
		return date("H:i", strtotime($psYyyymmdd));
	}
	
	/**
	 * Idea: chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang hh:mm:ss
	 *
	 * @param $psYyyymmdd : la thoi gian dang yyyymmdd
	 * @return chuoi hh:mm:ss (De hien thi tren man hinh)
	 */	
	public function _yyyymmddToHHmmss($psYyyymmdd) {
		if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
			return "";
		return date("H:i:s", strtotime($psYyyymmdd));
	}
	
	/**
	 * @Idea : chuyen doi ngay dang mm/dd/yyyy thanh ngay dang yyyy/mm/dd
	 *
	 * @param $psDdmmyyyy : la chuoi dang dd/mm/yyyy( chuoi chu khong phai date object )
	 * @return chuoi dang yyyy/mm/dd(De dua vao csdl)
	 */	
	public function _ddmmyyyyToYYyymmdd($psDdmmyyyy) {
		$psdate = NULL;
		$psdateArr = "";
		$psdel = "";
		if(strlen($psDdmmyyyy)==0 or is_null($psDdmmyyyy)) {
			$psdate = "";
		return $psdate;
		}
		if(strpos($psDdmmyyyy,"-")<=0 and strpos($psDdmmyyyy,"/")<=0) {
			$psdate = "";
		return $psdate;	
		}
		if(strpos($psDdmmyyyy,"-")>0) { 
				$psdel = "-";
		}
		if(strpos($psDdmmyyyy,"/")>0) {
				$psdel = "/";
		}
		$arr=explode(" ",$psDdmmyyyy);
		if($arr[0] <> "") {
			$psdateArr = explode($psdel,$arr[0]);
			if(sizeof($psdateArr)<>3) {
				$psdate = NULL;
				return $psdate;	
			} else {
				$psdate = $psdateArr[2]."/".$psdateArr[1]."/".$psdateArr[0].' '.gmdate("H:i:s", time() + 3600*(7+date("0")));
				return $psdate;	
			}
		}
		return $psdate;
	}
	
	/**
	 * @Idea: Lay mot phan tu cua danh sach tai mot vi tri cho truoc
	 *
	 * @param $pList : Mang luu danh sach phan tu
	 * @param $pIndex : Chi so phan tu can lay
	 * @param $pDelimitor : Ky tu phan cach giua cac phan tu
	 * @return Gia tri phan tu lay duoc
	 */	
	public function _listGetAt($pList,$pIndex,$pDelimitor) {
		$retValue = "";
		if (strlen($pList) == 0){
			return $retValue;
		}
		$arrElement = explode($pDelimitor,$pList);
		$retValue = $arrElement[$pIndex];
		return $retValue;
	}
	
	/**
	 * @Idea : Lay tong so phan tu cua mot danh sach
	 *
	 * @param $pString : Xau ky tu
	 * @param $pDelimitor : Ky tu phan tach cac phan tu
	 * @return So phan tu trong xau phan tach nhau boi $pDelimitor
	 */	
	//*************************************************************************
	public function _listGetLen($pString, $pDelimitor){
		$retValue =0;
		if(strlen($pString) <> 0){
			$array = explode($pDelimitor, $pString);
			$retValue = sizeof($array);
		}
		return  $retValue;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 02/02/2009
	 * IDea: Tao mot chuoi HTML mo ta SelectBox danh sach cac trang (Trang 1; Trang 2;...)
	 *
	 * @param $piTotalRecord : Tong so trang
	 * @param $piCurrentPage : Trang hien thoi
	 * @param $piNumberRecordOnList : So ban ghi / trang
	 * @param $pAction : Thuc hien Action
	 * @return CHuoi html
	 */
	public function _generateNumberPageIntoSelectbox($piTotalRecord, $piCurrentPage, $piNumberRecordOnList, $pAction){
		$psHtmlString = "";
		//Hien thi SelectBox Tong so trang
		if ($piTotalRecord % $piNumberRecordOnList == 0){
			$psNumberPage = (int)($piTotalRecord / $piNumberRecordOnList);
		}else{
			$psNumberPage = (int)($piTotalRecord / $piNumberRecordOnList)+1;		
		}
		$psHtmlString = $psHtmlString . "Tổng số &nbsp;" . $psNumberPage . "&nbsp;trang.&nbsp;&nbsp;Xem &nbsp;" . "<select class='normal_selectbox' name='sel_page' optional='true' style='width:auto' title='Ch&#7885;n trang mu&#7889;n xem' style='width:40'" . "onChange='page_onchange(this,\"" . $pAction ."\")';" . "onKeyDown='change_focus(document.forms[0],this)' >";
		for ($index = 1; $index <= $psNumberPage; $index++){				
			if ($piCurrentPage == $index){
				$psSelect = " selected ";
			}else{
				$psSelect = " ";
			}
			$psHtmlString = $psHtmlString . "<option id='' value='$index' name='$index' $psSelect> " . "Trang &nbsp;" . $index  . "</option>";
		}
		$psHtmlString = $psHtmlString . "</select>";
		
		//Return		
		return $psHtmlString;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 03/02/2009
	 * Idea: Tao SelectBox quy dinh so Record / page
	 *
	 * @param $piValue : Gia tri lua chon trong SelectBox
	 * @param $pAction : Action can thuc hien
	 * @return Xau HTML
	 */
	public function _generateChangeRecordNumberPage($piValue, $pAction){
		//Load class
		//Zend_Loader::loadClass('Efy_Publib_Xml');
		$psHtmlString = "";	
		//Doc file XML mota thong tin	
		$psXmlDataInUrl = Efy_Library::_readFile("./xml/list/output/SO_HS_TREN_TRANG.xml");		
		//Chuyen doi thong tin trong xau XML -> Mang
		$arrListItem = Efy_Xml::_convertXmlStringToArray($psXmlDataInUrl, "item");		
		//Tao chuoi HTML
		$psHtmlString = $psHtmlString . "Hiển thị ";
		$psHtmlString = $psHtmlString . "<select class='normal_selectbox' id = 'cbo_nuber_record_page' name='cbo_nuber_record_page' optional='true' style='width:60' onChange='page_record_number_onchange(this,\"" . $pAction ."\")'" . "onKeyDown='change_focus(document.forms[0],this)'>";
		$psHtmlString = $psHtmlString . Efy_Library::_generateSelectOption($arrListItem, 'c_code', 'c_code', 'c_name', $piValue);
		$psHtmlString = $psHtmlString . "</select>";	
		$psHtmlString = $psHtmlString . " hồ sơ/1 trang";		
		return $psHtmlString;
	}
	
	
	/**
	 * Creater: Thainv
	 * Date: 04/02/2009
	 * Idea: Load tat ca cac file js hoac css 
	 *
	 * @param : $psModuleName : Ten module can load het
	 * @param : $psModuleNameOrther :Lay them cac file o module khac
	 * @param : $parrFileName: Cac file can lay o module khac
	 * @param : $psDelimitor: ky tu ngan cach giua cac file can lay o module khac
	 * @param : $psExtension: Duoi mo rong cua file can lay
	 * @return: Xau HTML
	 */
	public function _getAllFileJavaScriptCss($psModuleName,$psModuleNameOrther = "" ,$parrFileName = "",$psDelimitor = ",",$psExtension){		
		// Duong dan thu muc doc file
		$sDir = null;		
		// chuoi ket qua tra ve
		$sResHtml = null;
		
		Zend_Loader::loadClass(Efy_Init_Config);
		
		$objInitConfif  = new Efy_Init_Config();
		
		//
		$filetype = strtolower($psExtension);
		
		// thuc hien lay tren tung module cu the
		if($psModuleName != ""){
			//
			$sDir = $psModuleName.'/';		
			
			if (is_dir($sDir)) {				
			    if ($dh = opendir($sDir)) {
			    	
			        while (($file = readdir($dh)) !== false) {			        			        	
			        							
			        	//Thuc hien include file JavaScript	
			        	$filetypeinDirJs = substr($file,strlen($file)-2,2);			        	
		        		$filetypeinDirJs = strtolower($filetypeinDirJs);		        		
			        			        	
			        	if($filetype == "js" && $filetypeinDirJs =="js" ){		
			        		
			        		$sDirFull = $objInitConfif->_setWebSitePath().$sDir.$file;			        		
			            	$sResHtml = $sResHtml.  "<script src=\" ".$sDirFull. " \" type=\"text/javascript\"></script>" . "\n";            	
			        	}	
			        	
			        	//Thuc hien include file Css
			        	$filetypeinDirCss = substr($file,strlen($file)-3,3);			        	
		        		$filetypeinDirCss = strtolower($filetypeinDirCss);	
			        	if($filetype == "css" && $filetypeinDirCss =="css"){			        		
			        		$sDirFull = $objInitConfif->_setWebSitePath().$sDir.$file;	            				        		
			            	$sResHtml = $sResHtml.  "<link href=\" " .$sDirFull." \" rel=\"stylesheet\" />" . "\n";            	
			        	}	
			        }
			        closedir($dh);
			    }
			}
		}
		
		// Thuc hien lay file js o nhung module khac		
		if($psModuleNameOrther!= ""){
			//
			$sDir =  $psModuleNameOrther .'/';
        	
			//			
        	$arrTemp = explode($psDelimitor,$parrFileName);
        	
        	for($index =0 ; $index < sizeof($arrTemp); $index++){
        		
        		//Thuc hien include file JavaScript	
	        	$filetypeinDirJs = substr($arrTemp[$index],strlen($file)-2,2);			        	
        		$filetypeinDirJs = strtolower($filetypeinDirJs);		        		
	        	if($filetype == "js" && $filetypeinDirJs =="js" ){				        		
	        		$sDirFull = $objInitConfif->_setLibUrlPath().$sDir.$arrTemp[$index];
	            	$sResHtml = $sResHtml.  "<script src=\" ".$sDirFull. " \" type=\"text/javascript\"></script>" . "\n";            	
	        	}	
	        	
	        	//Thuc hien include file Css
	        	$filetypeinDirCss = substr($arrTemp[$index],strlen($file)-3,3);			        	
        		$filetypeinDirCss = strtolower($filetypeinDirCss);	
	        	if($filetype == "css" && $filetypeinDirCss =="css"){			        		
	        		$sDirFull = $objInitConfif->_setLibUrlPath().$sDir.$arrTemp[$index];            				        		
	            	$sResHtml = $sResHtml.  "<link href=\" " .$sDirFull." \" rel=\"stylesheet\" />" . "\n";            	
	        	}	
        	}		
					
		}
		return $sResHtml;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 05/02/2009
	 * Idea: Tao thong bao man hinh danh sach nay co bao nhieu ho so / tong so ho so; dong thoi hien thi hai radio CHON TAT CA va BO CHON TAT CA
	 *
	 * @param $psNumRowOnPage : So ban gi tren man hinh danh sach
	 * @param $psTotalRecord : Tong so ban gi
	 * @return Xau html mo ta thong tin
	 */
	public function _selectDeselectAll($psNumRowOnPage, $psTotalRecord){
		$psHtmlString = '<table width="98%" cellpadding="0" cellspacing="0" border="0">';
		$psHtmlString .= '<tr><td height="8px"></td></tr><tr>';
		
		//Lay ra so tong so ho so tren mot trang
		if ($psNumRowOnPage > 0){
			$psHtmlString .= '<td height="20px" align="left" colspan = "2" class="small_label">';
			$psHtmlString .= '<small class="small_starmark"><font color="Red"> Danh sách này có ' . $psNumRowOnPage.'/'.$psTotalRecord. " tài sản " . '</font></small>';
			$psHtmlString .= '</td>';
			$psHtmlString .='<td class="normal_label" align="right"><font style="font-size:12px;">';
			$psHtmlString .= '<input optional = "true" type="radio" id = "rad_selectall" name="rad_selectall" value="0" onClick="select_all_checkbox(document.forms[0],\'chk_item_id\');">Chọn tất cả';
			$psHtmlString .= '<input optional = "true" type="radio" id = "rad_selectall" name="rad_selectall" value="1" onClick="deselect_all(document.forms[0],\'chk_item_id\');">Bỏ chọn tất cả';
			$psHtmlString .='</font></td>';
		}else{
			$psHtmlString .='<td height="20px" align="left" colspan = "2" class="small_label">';
			$psHtmlString .='<small class="small_starmark"><font color="Red">Danh sách này không có tài sản nào!' . '</font></small>';
			$psHtmlString .='</td>';
		}
		
		$psHtmlString .='</tr></table>';
		return $psHtmlString;
	}
	public function _selectDeselectAllArchives($psNumRowOnPage, $psTotalRecord){
		$psHtmlString = '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		$psHtmlString .= '<tr><td height="8px"></td></tr><tr>';
		
		//Lay ra so tong so ho so tren mot trang
		if ($psNumRowOnPage > 0){
			$psHtmlString .= '<td height="20px" align="left" colspan = "2" class="small_label">';
			$psHtmlString .= '<small class="small_starmark"><font color="Red"> Danh sách này có ' . $psNumRowOnPage.'/'.$psTotalRecord. " tài sản " . '</font></small>';
			$psHtmlString .= '</td>';			
		}else{
			$psHtmlString .='<td height="20px" align="left" colspan = "2" class="small_label">';
			$psHtmlString .='<small class="small_starmark"><font color="Red">Danh sách này không có tài sản nào!' . '</font></small>';
			$psHtmlString .='</td>';
		}
		
		$psHtmlString .='</tr></table>';
		return $psHtmlString;
	}
	/**
	 * Creater: Hoang Van Toan
	 * Date: 29/09/2009
	 * Discription: Tao folder
	 *
	 * @param $path: noi can tao folder
	 * @param $folderYear: tao ra folder nam
	 * @param $folderMonth: tao ra folder thang
	 * @return tra ve duong dan toi folder
	 */
	public function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = ""){
		$sPath = '..' . str_replace("/","\\",$pathLink);
		if(!file_exists($sPath . $folderYear)){
			mkdir($sPath . $folderYear, 0777);	      
        	$sPath = $sPath . $folderYear;
            if(!file_exists($sPath . chr(92) . $folderMonth)){
        		mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
		}else {
			$sPath = '..' . $sPath . $folderYear;
            if(!file_exists($sPath . chr(92) . $folderMonth)){
        		mkdir($sPath . chr(92) . $folderMonth, 0777);
        	}
		}
		//Tao ngay trong nam->thang
		if(!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)){
			mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
		}
		//
		$strReturn = '..' . $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay.'/';
		return $strReturn;
	}
	/**
	 * Creater: Hoang Van Toan
	 *
	 * @return Ham lay mo so ngau nhien
	 */
	function _get_randon_number(){
		$ret_value = mt_rand(1,1000000);
		return $ret_value;
	}

	/**
	 * @author :thainv
	 * @editer: Hoang Van Toan
	 * @param: '@!~!@': phan tach cac file dinh kem khac nhau
	 * @since  : 17/02/2009
	 * @see : Upload Mot mang file attach len o cung
	 * @param :
	 * @param:	$iFileMaxNum: So file toi da de upload
	 * @param :	$sDir:		  Duong dan chua file can upload 
	 * @param :	$sVarName:    Ten cua bien trong <input type="upload" name='$sVarName'>
	 * @return :
	 * 			$sFileNameList:	Mang danh sach ten file da duoc upload len o cung
	 * 
	 * @package : Efy_Publib_Library
	 * 			
	 **/
	public function _uploadFileList($iFileMaxNum = 10, $sDir, $sVarName = "FileName", $sDelimitor = "@!~!@"){		
		$path = self::_createFolder($sDir,date('Y'),date('m'),date('d'));
		
		$sFileNameList = "";
		for($index = 0;$index < $iFileMaxNum; $index++){
			$random = self::_get_randon_number();
			$sAttachFileName = $sVarName . $index;
			$sFullFileName =  date("Y").'_'.date("m").'_'.date("d")."_".date("H").date("i").date("u").$random."!~!". self::_replaceBadChar($_FILES[$sAttachFileName]['name']);
			// Neu la file
			if(is_file($_FILES[$sAttachFileName]['name']) || $_FILES[$sAttachFileName]['name'] != ""){
				move_uploaded_file($_FILES[$sAttachFileName]['tmp_name'], $path . self::_convertVNtoEN($sFullFileName));
				//Neu la file
				$sFileNameList .= $sFullFileName . $sDelimitor;
			}			
		}
		// xu ly chuoi
		$sFileNameList = substr($sFileNameList,0,strlen($sFileNameList) - strlen($sDelimitor));
		// tra lai gia tri			
		return self::_convertVNtoEN($sFileNameList);			
	}
	/**
	 * Creater: Hoang Van Toan
	 * date: 30/09/2009
	 *
	 * @param $strFileList: danh sach cac file dinh kem
	 * @param $deliFileList; ky tu phan tach giua cac file dinh kem
	 * @param $deliFileName: ky tu phan tach giua ID va Name cua mot file dinh kem
	 * @param $sPathLink: duong dan toi folder chua file dinh kem can hien thi
	 * @return tra ve danh sach cac file dinh kem
	 */
	public function _getAllFileAttach($strFileList, $deliFileList = '!#~$|*', $deliFileName = '!~!', $sPathLink){
		if($strFileList == '')
			return '';
		$strFileName = explode($deliFileList,$strFileList);
		for($i =0; $i < sizeof($strFileName); $i ++){
			$arrFileName = explode($deliFileName,$strFileName[$i]);
			$fileName    = $arrFileName[1];
			$fileID 	 = $arrFileName[0];
			$arrFileLink = explode('_',$fileID);
			$fileLink = $sPathLink . $arrFileLink[0] . "/" . $arrFileLink[1] . "/" . $arrFileLink[2] . "/" . $strFileName[$i];
			$pathFileExist = '..' . str_replace("/","\\",$fileLink);
			$linkImg = " <img src = '" . $sPathLink . "../images/file_attach.gif'/>";
		/*	//neu la file tieng viet co dau
			if (strlen($fileLink) != strlen(utf8_decode($fileLink))){
				$fileAttach = $fileAttach . $linkImg . "<a href = '" . self::_replaceBadChar(self::_convertTCVN3ToUnicode($fileLink)) ."' title = 'file dinh kem'>" . $fileName . "</a>";
			}*/
			//neu la file tieng viet khong dau
			if(file_exists($pathFileExist)){				
				$fileAttach = $fileAttach . $linkImg . "<a href = '" . self::_replaceBadChar($fileLink) ."' title = 'file dinh kem'>" . $fileName . "</a>";
			}else{
				$fileLink = $sPathLink . $arrFileLink[0] . "/" . $strFileName[$i];
				$pathFileExist = '..' . str_replace("/","\\",$fileLink);
				if(file_exists($pathFileExist)){
					$fileAttach = $fileAttach . $linkImg . "<a href = '" . self::_replaceBadChar($fileLink) ."' title = 'file dinh kem'>" . $fileName . "</a>";
				}else{
					$fileLink = $sPathLink . $strFileName[$i];
					$pathFileExist = '..' . str_replace("/","\\",$fileLink);
					if(file_exists($pathFileExist)){
						$fileAttach = $fileAttach . $linkImg . "<a href = '" . self::_replaceBadChar($fileLink) ."' title = 'file dinh kem'>" . $fileName . "</a>";
					}
				}
			}
		}
		return $fileAttach;
	}
	
	/**
	 * @author :thainv
	 * @since  : 19/02/2009
	 * @see : Lay tat ca cac file attach
	 * @param :
	 * 			$psID:  ID cua doi tuong can lay
	 * 			$pDocumentType: Kieu cua tai lieu can lay : PIC	
	 * 			
	 * @return :
	 * 			$arrResult:	Mang danh sach ten file da duoc upload len o cung
	 * 
	 * @package : Efy_Publib_Library
	 * 			
	 **/
	public  function _getAllFileList($psID,$pDocumentType){	
		// Load Class Connection
		Zend_Loader::loadClass('Efy_Db_Connection');
		
		// Tao doi tuong
		$objConn = new Efy_DB_Connection();			
		
		$sql = "Exec Doc_GetAllDocumentFileAttach '" . $psID . "','".$pDocumentType."'" ;		
		try {						
			$arrResult = $objConn->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;		
	}	
	
	//'********************************************************************************
	//Ham _builtXmlTree
	//Muc dich: Xay dung cau truc cay du lieu (TreeView) dong khong gioi han cap do. Gom 2 loai doi tuong la:
	//	-ContainerOBJ: la dang container object chua cac object khac (Vi du: cac Phong ban, cac to chuc khac...)
	//	-LeafOBJ: la dang leaf object cuoi cung chua cac thong tin, khong chua cac object khac (Vi du: cac nhan vien...)
	//Phuong phap xay dung:
	//	-Su dung dang du lieu XML de xay dung cau truc dong tu mot mang du lieu 2 chieu.
	//	-Su dung phuong phap de quy de xay dung cau truc dong
	//Input:
	//	1. $arrAllList: Mang chua cac ban ghi cua bang can xay dung cay du lieu. Mang co cau truc sau
	//		-Phan tu thu 1 (index 0): ID cua Object
	//		-Phan tu thu 2 (index 1): ID cua Object cha chua Object ID
	//		-Phan tu thu 3 (index 2): Ma viet tat cua Object
	//		-Phan tu thu 4 (index 3): Ten cua Object
	//		-Phan tu thu 5 (index 4): Loai Object (0 - ContainerOBJ, 1 - LeafOBJ) 
	//	2. $root_text: Dong text o goc cay thu muc
	//	3. $exception_brand_id: ID cua doi tuong can hieu chinh
	//		Duoc dung trong truong hop muon hieu chinh mot doi tuong dang ConrainerOBJ
	//		Khi hieu chinh can chon lai ParentOBJ tu ModalDialog, khi do nhanh cua Object dang hieu chinh (ke ca cac ChildOBJ)
	//		se khong xuat hien tren cay thu muc (tranh loi mot Object nay vua la cha dong thoi vua la con cua Object khac)
	//		Gia tri cua ID nay: -1 neu tao tat ca cay, >0 neu tao thieu nhanh do.
	//Output:
	//	Tra ve mot chuoi dang XML cua cau truc cay du lieu
	//'********************************************************************************
	public 	function _builtXmlTree($arrAllList,$exception_brand_id,$show_control,$opening_node_img_name,$closing_node_img_name,$leaf_node_img_name,$select_parent,$list_id_checked="",$object_name="") {
		global $_EFY_IMAGE_URL_PATH;
		//global $_MODAL_DIALOG_MODE;
		
		$objConfig = new Efy_Init_Config();
		$sPath = $objConfig->_setImageUrlPath();
		
		$strTop='<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$strTop.='<treeview title="Treeview" >'."\n";
		$strTop.="<custom-parameters>"."\n";
		$strTop.='<param name="shift-width" value="10"/>' . "\n";
		$strTop.='<param name="opening_node_img_name" value="'.$sPath.$opening_node_img_name.'"/>'."\n";
		$strTop.='<param name="closing_node_img_name" value="'.$sPath.$closing_node_img_name.'"/>'."\n";	
		$strTop.='<param name="leaf_node_img_name" value="'.$sPath . $leaf_node_img_name.'"/>' . "\n";
		$strTop.='<param name="modal_dialog_mode" value="'.$_MODAL_DIALOG_MODE.'"/>' . "\n";
		$strTop.='<param name="show_control" value="'.$show_control.'"/>'."\n";
		$strTop.='<param name="select_parent" value="'.$select_parent.'"/>'."\n";
		$strTop.="</custom-parameters>"."\n";	
		$strBottom= "</treeview>";
		$strXML="";
		$parent_id=NULL;
		//Lay ra mang chua cac Object muc ngoai cung 
		$v_count = sizeof($arrAllList);
		$v_current_index = 0;
		for($i=0; $i<$v_count; $i++){
			if (strcasecmp(trim($arrAllList[$i][1]),$parent_id)==0){
			//if($arrAllList[$i][1]==$parent_id){
				$arr_current_list[$v_current_index][0]=$arrAllList[$i][0];//PK
				$arr_current_list[$v_current_index][1]=$arrAllList[$i][1];//FK
				$arr_current_list[$v_current_index][2]=htmlspecialchars($arrAllList[$i][2]);//C_CODE
				$arr_current_list[$v_current_index][3]=htmlspecialchars($arrAllList[$i][3]);//C_NAME
				$arr_current_list[$v_current_index][4]=$arrAllList[$i][4];//C_TYPE
				$arr_current_list[$v_current_index][5]=$arrAllList[$i][5];//C_LEVEL
				$v_current_index++;
			}
		}
		//Tao cac Node muc 2 cua treeview
		for ($i=0; $i<$v_current_index; $i++) {
			$v_current_id = $arr_current_list[$i][0];//PK
			$v_parent_id = 0;// id cua cha (FK =0)
			$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
			$v_current_name = htmlspecialchars($arr_current_list[$i][3]);	//C_NAME
			$v_current_type = $arr_current_list[$i][4];//C_TYPE
			$v_current_level = $arr_current_list[$i][5];//C_LEVEL
			//Kiem tra ID neu no khong la $exception_brand_id thi moi tao (tranh truong hop "vua la chau vua la cha" giua hai phan tu)
			if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
			//if($v_current_id!=$exception_brand_id){
				$arr_id_list = explode(",",$list_id_checked);
				$value_checked = 0;
				for ($j=0;$j<sizeof($arr_id_list);$j++){
					if ($arr_id_list[$j]==$v_current_id){
						$value_checked = $v_current_id;
					}				
				}
				$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" value_check="'.$value_checked.'" type="'.$v_current_type.'" parent_id="'.$v_parent_id.'" xml_tag_in_db_name="'.$object_name.'" level="'.$v_current_level.'" >'."\n";
				//Tao ra cac Node  con cua tree view
				$strXML.= self::_builtChildNode($arrAllList,$v_current_level,$v_current_id,$exception_brand_id,$list_id_checked,$object_name);
				$strXML.="</folder>"."\n";
			}
		}
		return  $strTop . $strXML . $strBottom;
	
	}
		
	
/**
	 * ******************************************************************************************************************
	 * @Idea: tra ve mot gia tri cua mot thuoc tinh cua mot phan tu mang.
	 * @param $p_array : Mang danh sach cac phan tu
	 * @param $p_id : Id cua phan tu can lay trong mang $p_array
	 * @param $p_attr_name : Ten phan tu can lay
	 * @return  Ten phan tu can lay
	 *******************************************************************************************************************
	 */		
	public function _getItemAttrById($p_array,$p_id,$p_attr_name) {		
		try {
			foreach($p_array as $staff){
				if ($staff['id'] == $p_id){					
					return $staff[$p_attr_name];
					break;
				}
			}
			return "";
		}catch (Exception $ex){;}
	}
	/**
	 * Creater: Hoang Van Toan
	 * Date: 30/10/2009
	 * Dis: lay Name boi Code trong danh muc
	 *
	 * @param  $p_array
	 * @param  $p_id
	 * @param  $p_attr_name
	 * @return Ten cua doi tuong duoc truyen vao
	 */
	public function _getNameByCode($p_array,$p_id,$p_attr_name) {	
		try {
			foreach($p_array as $staff){
				if ($staff['C_CODE'] == trim($p_id)){					
					return $staff[$p_attr_name];
					break;
				}
			}
			return "";
		}catch (Exception $ex){;}
	}	
	//Lay danh sach tat ca cac phogn ban
	public function _getArrAllUnit(){
		try {
			$i = 0;
			foreach($_SESSION['arr_all_unit'] as $v_unit){
				$arr_child_unit[$i][0] = $v_unit['id'];
				$arr_child_unit[$i][1] = $v_unit['parent_id'];
				$arr_child_unit[$i][2] = $v_unit['code'];
				$arr_child_unit[$i][3] = $v_unit['name'];
				$arr_child_unit[$i][4] = 0;
				$arr_child_unit[$i][5] = 0;
				$i++;
			}
			return $arr_child_unit;	
		}catch (Exception $ex){;}
	}
	
	//*********************************************************************************************************************
	//Lay danh sach nguoi su dung cua mot don vi
	//*********************************************************************************************************************
	public function _getArrChildStaff($arr_unit){
		try {
			$i = 0;
			$v_count = sizeof($arr_unit);
			for($j=0; $j < $v_count; $j++){
				foreach($_SESSION['arr_all_staff'] as $v_staff){
					if($v_staff['unit_id'] == $arr_unit[$j]['0']){
						$arr_child_staff[$i][0] = $v_staff['id'];
						$arr_child_staff[$i][1] = $v_staff['unit_id'];
						$arr_child_staff[$i][2] = $v_staff['code'];
						$arr_child_staff[$i][3] = $v_staff['name'];
						$arr_child_staff[$i][4] = 1;
						$arr_child_staff[$i][5] = 1;
						$i++;
					}
				}
			}
			return $arr_child_staff;
		}catch (Exception $ex){;}
	}
	
	//********************************************************************************************************************
	//Thuc hien noi array2 vao array1 voi so phan tu cua 2 mang la $number_element
	//********************************************************************************************************************
	public function _attachTwoArray($p_array1, $p_array2, $number_element){
		$v_count_arr1 = sizeof($p_array1);
		$v_count_arr2 = sizeof($p_array2);
		$j = $v_count_arr1;
		for($i = 0; $i<$v_count_arr2; $i++){
			for($h=0; $h<=$number_element; $h++){
				$p_array1[$j][$h] = $p_array2[$i][$h];
			}
			$j++;
		}
		return $p_array1;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $arrAllList
	 * @param unknown_type $current_level
	 * @param unknown_type $parent_id
	 * @param unknown_type $exception_brand_id
	 * @param unknown_type $list_id_checked
	 * @param unknown_type $object_name
	 * @return unknown
	 */
	//Xay dung cac node con cua mot doi tuong
	public function _builtChildNode($arrAllList,$current_level,$parent_id,$exception_brand_id,$list_id_checked="",$object_name=""){
		$strXML="";
		$v_current_index = 0;
		$v_count = sizeof($arrAllList);
		for($j=0;$j<$v_count;$j++){
		//Tim nhung thang con
			if ((strcasecmp(trim($arrAllList[$j][1]),$parent_id)==0) && ($arrAllList[$j][5]>=$current_level)){
			//if (($arrAllList[$j][1]==$parent_id) and ($arrAllList[$j][5]>=$current_level)){
				$arr_current_list[$v_current_index][0]=$arrAllList[$j][0];//PK
				$arr_current_list[$v_current_index][1]=$arrAllList[$j][1];//FK
				$arr_current_list[$v_current_index][2]=htmlspecialchars($arrAllList[$j][2]);//C_CODE
				$arr_current_list[$v_current_index][3]=htmlspecialchars($arrAllList[$j][3]);//C_NAME			
				$arr_current_list[$v_current_index][4]=$arrAllList[$j][4];//C_TYPE
				$arr_current_list[$v_current_index][5]=$arrAllList[$j][5];//C_LEVEL
				$v_current_index++;
			}
		}
		//Truong hop mang $arr_current_list rong thi ket thuc de quy
		if($v_current_index <= 0){return;}
		for ($i=0; $i<$v_current_index; $i++){
			$v_current_id = $arr_current_list[$i][0];//PK
			$v_parent_id = $arr_current_list[$i][1];//FK	
			$v_current_code = htmlspecialchars($arr_current_list[$i][2]);//C_CODE
			$v_current_name = htmlspecialchars($arr_current_list[$i][3]);//C_NAME
			$v_current_type = $arr_current_list[$i][4];//C_TYPE
			$v_current_level = $arr_current_list[$i][5];//C_LEVEL
			//Kiem tra ID neu no khong la $exception_brand_id thi moi tao
			if (strcasecmp(trim($v_current_id),$exception_brand_id)!=0){
			//if($v_current_id!=$exception_brand_id){
				$arr_id_list = explode(",",$list_id_checked);
				$value_checked = 0;
				for ($j=0;$j<sizeof($arr_id_list);$j++){
					if ($arr_id_list[$j]==$v_current_id){
						$value_checked = $v_current_id;
					}				
				}
				$strXML.= '<folder title="'.trim($v_current_name).'" id="'.$v_current_id.'" value="'.$v_current_code.'" value_check="'.$value_checked.'" type="'.$v_current_type.'" parent_id="'.$parent_id.'" xml_tag_in_db_name="'.$object_name.'" level="'.$v_current_level.'" >'."\n";
				if ($v_current_type=='0'){
					$strXML.=self::_builtChildNode($arrAllList,$v_current_level,$v_current_id,$exception_brand_id,$list_id_checked,$object_name);
				}
				$strXML.="</folder>"."\n";
			}
		}
		return  $strXML;
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 21/05/2009
	 * Idea : Tao phuong thuc be dong tren ma hinh danh sach (Vi du: Nhap noi dung trong textarea co xuong dong tuy nhien khi hien thi ra man hinh danh sach thi
	 * thi lai hien thi tren mot dong)
	 *
	 * @param $pContent : Noi dung can be dong
	 * @return Xau noi dung da duoc be
	 */
	public function _breakLine ($pContent = ''){
		$ilen = strlen($pContent);
		if($ilen > 0){			
			for($index = 0; $index < $ilen; $index++){
				if(ord(substr($pContent,$index,1)) == 10){//=10 la ma xuong dau dong
					$pContent = str_replace(chr(10),"<br>",$pContent);
				}
			}	
		}
		return $pContent;
	}	
		//*************************************************************************
	//Muc dich:Kiem tra xem trong danh sach $p_list co chua mot gia tri $p_element hay khong	
	//*************************************************************************
	
	/**
	 * Creater : HUNGVM
	 * Date : 21/06/2009
	 * Idea : Phuong thuc kiem tra co ton tai mot phan tu trong tap gia tri khong? (true => ton tai; false => khong ton tai)
	 *
	 * @param $p_list	: Tap phan tu
	 * @param $p_element : Phan tu can kiem tra
	 * @param $p_delimitor : Ky tu phan tach giua cac phan tu trong $p_list
	 * @return Tra lai gia tri true/false
	 */
	function _listHaveElement($p_list, $p_element, $p_delimitor){
		if ($p_list=="" Or $p_element==""){
			return false;
		}
		$ret_value = false;
		if(strlen($p_list)>0){
			$array = explode($p_delimitor, $p_list);
			$ret_value = in_array($p_element,$array);
		}
		return $ret_value;
	}
	public function _InforStaff(){
		$StaffName = Efy_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$piUnitId = Efy_Publib_Library :: _getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$piUnitName = Efy_Publib_Library::_getItemAttrById($_SESSION['arr_all_unit'],$piUnitId,'name');			
		return $StaffName."<br>".$piUnitName;
	}	

	
	 	
	/**
	 * Creater : HUNGVM
	 * Date : 30/09/2009
	 * Idea : Tao phuong thuc hien thi thong tin nguoi dang nhap hien thoi
	 *
	 * @return Chuoi HTML hien thi thong tin nguoi dang nhap
	 */
	public function _getInformationStaffLogin(){		
		//Ten can bo
		$sStaffName = self::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Chuc vu
		$sPositionName = self::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
		//Phong ban
		$iUnitId = self::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sUnitName = self::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		$strHtml = "";
		if ($sStaffName != ""){
			$strHtml .= "<table width='98%' cellpadding='0'  cellspacing='0' align='center'  class='left_menu_table_logout'>";
			$strHtml .= "<tr><td class='level_dangnhap_1'>" . "Đăng nhập" . "</td></tr>";
			$strHtml .= "<tr><td class='level_dangnhap'>" . $sPositionName . ' - ' . $sStaffName . "</td></tr>";
			$strHtml .= "<tr><td class='level_dangnhap'>" . $sUnitName . "</td></tr>";
			$strHtml .= "</table>";
		}	
		return $strHtml;
	}
	/**
	 * Creater: hoang van toan
	 * date(29/10/2009)
	 * Dics: ham chuyen ky tu chu thuong thanh chu HOA
	 *
	 * @param $strText: chuoi ky tu truyen vao
	 */
	public function Lower2Upper($strText){
		$strLC = "a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|đ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|í|ì|ỉ|ĩ|ị|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|ý|ỳ|ỷ|ỹ|ỵ'";
		$strUC = "A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Đ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Í|Ì|Ỉ|Ĩ|Ị|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Ý|Ỳ|Ỷ|Ỹ|Ỵ'";
		$arrLC = explode('|', $strLC);
		$arrUC = explode('|', $strUC);
		for($i =0; $i <sizeof($arrLC); $i ++){
			$strText = str_replace($arrLC[$i], $arrUC[$i], $strText);
		}	
		return 	$strText; 
	}
	public function _deleteFileUpload($url){
		return unlink($url);
	}
	
	/**
	 * Chuc nang:	Sinh ra cac tuan trong mot nam
		Tham so:
		+	$p_year (int): Xac dinh nam. Vidu: $p_year=2004
		+	$p_number_of_week (int) So tuan se duoc hien thi trong combo_box:
				= -1 : hien thi tat ca cac tuan trong nam
				=  0 : Hien thi tu tuan dau toi tuan hien thoi
				n >0 : Hien thi n tuan tinh tu tuan hien thoi (neu muon hien thi tu tuan hien thoi den het nam thi n>=53)
		+	$p_current_week (int): Tuan hien thoi hien dang duoc chon trong combo_box. Vi du: $p_current_week=33
	 */
	public function _generateWeeksOfYear($p_year, $p_number_of_week, $p_current_week){
		$p_week_name = "Tu&#7847;n ";
		//Xac dinh ngay 1/1 la thu may
		$v_timestamp = mktime(0,0,0,1,1,$p_year);
		$v_fisrt_day_of_year = date('w',$v_timestamp);
		if ($v_fisrt_day_of_year == 0) {$v_fisrt_day_of_year = 7;}
		//Xac dinh ngay dau cua tuan thu nhat la ngay nao, ngay nay co the thuoc nam truoc.
		if ($v_fisrt_day_of_year <= 4){
			$v_day = 2 - $v_fisrt_day_of_year;
		}else{
			$v_day = 9 - $v_fisrt_day_of_year;
		}
		$strHTML = "";
		for ($i=1; $i <= 53; $i++){
			$v_fisrt_day_of_week = date("d/m/Y",mktime(0,0,0,1,$v_day,$p_year));
			$v_last_day_of_week = date("d/m/Y",mktime(0,0,0,1,$v_day+6,$p_year));
			$v_text = $p_week_name.$i.' ('.$v_fisrt_day_of_week.' --> '.$v_last_day_of_week.')';
			if($i == $p_current_week){
				$str_selected = "selected";
			}else{
				$str_selected = "";
			} 
			// Hien thi toan bo (ngam dinh)
			$add_HTML=true;
			if ($p_number_of_week == 0){ // Hien thi tu tuan 1 toi tuan hien thoi
				if ($i > date("W")){break;}
			}
			if ($p_number_of_week > 0){ // Hien thi n tuan tu tuan hien thoi tro di
				if ($i < date("W")){
					$add_HTML=false;
				}else{
					$add_HTML=true;
					if ($i - date("W") >= $p_number_of_week){break;}
				}
			}
			if ($add_HTML == true){
				$strHTML.='<option id='.'"'.$i.'"'.' name='.'"'.$i.'"';
				$strHTML.=' value='.'"'.$i.'"';
				$strHTML.=' fisrt_day='.'"'.$v_fisrt_day_of_week.'"';
				$strHTML.=' last_day='.'"'.$v_last_day_of_week.'"';
				$strHTML.=' '.$str_selected.'>' .$v_text.'</option>';
			}
			$v_day = $v_day + 7;
			//Kiem tra tuan sau da sang tuan thu nhat cua nam sau chua.
			if (date("W",mktime(0,0,0,1,$v_day,$p_year)) == 1){break;}
		}
		return $strHTML;
	}
	/**
	 * Creater: Toanhv
	 *
	 * @param $strText: chuoi ky tu can chuyen font tu unicode sang TCVN3
	 * @return tra ve font TCVN3
	 */
    function _convertTCVN3ToUnicode($strText){
    	$uniChars = array("á","à","ả","ã","ạ","ă","ắ","ằ","ẳ","ẵ","ặ","â","ấ","ầ","ẩ","ẫ","ậ","é","è","ẻ","ẽ","ẹ","ê","ế","ề","ể","ễ","ệ","í","ì","ỉ","ĩ","ị","ó","ò","ỏ","õ","ọ","ô","ố","ồ","ổ","ỗ","ộ","ơ","ớ","ờ","ở","ỡ","ợ","ú","ù","ủ","ũ","ụ","ư","ứ","ừ","ử","ữ","ự","ý","ỳ","ỷ","ỹ","ỵ","đ","Á","﻿À","Ả","Ã","Ạ","Ă","Ắ","Ằ","Ẳ","Ẵ","Ặ","Â","Ấ","Ầ","Ẩ","Ẫ","Ậ","É","È","Ẻ","Ẽ","Ẹ","Ê","Ế","Ề","Ể","Ễ","Ệ","Í","Ì","Ỉ","Ĩ","Ị","Ó","Ò","Ỏ","Õ","Ọ","Ô","Ố","Ồ","Ổ","Ỗ","Ộ","Ơ","Ớ","Ờ","Ở","Ỡ","Ợ","Ú","Ù","Ủ","Ũ","Ụ","Ư","Ứ","Ừ","Ử","Ữ","Ự","Ý","Ỳ","Ỷ","Ỹ","Ỵ","Đ");
    	$tcvnChars = array("¸","µ","¶","·","¹","¨","¾","»","¼","½","Æ","©","Ê","Ç","È","É","Ë","Ð","Ì","Î","Ï","Ñ","ª","Õ","Ò","Ó","Ô","Ö","Ý","×","Ø","Ü","Þ","ã","ß","á","â","ä","«","è","å","æ","ç","é","¬","í","ê","ë","ì","î","ó","ï","ñ","ò","ô","­","ø","õ","ö","÷","ù","ý","ú","û","ü","þ","®","¸","#µ","¶","·","¹","¡","¾","»","¼","½","Æ","¢","Ê","Ç","È","É","Ë","Ð","Ì","Î","Ï","Ñ","£","Õ","Ò","Ó","Ô","Ö","Ý","×","Ø","Ü","Þ","ã","ß","á","â","ä","¤","è","å","æ","ç","é","¥","í","ê","ë","ì","î","ó","ï","ñ","ò","ô","¦","ø","õ","ö","÷","ù","ý","ú","û","ü","þ","§");
    	for($i =0; $i <sizeof($uniChars); $i ++){
			$strText = str_replace($uniChars[$i], $tcvnChars[$i], $strText);
		}	
		return 	$strText; 
    }
    /**
	 * Creater: Phongtd
	 * Date: 12/05/2010
	 * IDea: Tao mot xau HTML mo ta danh sach cac trang (Trang 1; Trang 2;...)
	 *
	 * @param $piTotalRecord : Tong so trang
	 * @param $piCurrentPage : Trang hien thoi
	 * @param $piNumberRecordOnList : So ban ghi / trang
	 * @param $pAction : Thuc hien Action
	 * @return Xau html
	 */
	public function _generateStringNumberPage($piTotalRecord, $piCurrentPage, $piNumberRecordOnList,$pUrl){
		if($piTotalRecord > $piNumberRecordOnList){
			//Xau hien thi danh sach trang
			$psHtmlString = "";
			//Trang bat dau hien thi
			$piStartPage = 1;
			//Trang ket thuc hien thi
			$piEndPage = 10;
			//Tinh tong so trang
			if ($piTotalRecord % $piNumberRecordOnList == 0){
				$psNumberPage = (int)($piTotalRecord / $piNumberRecordOnList);
			}else{
				$psNumberPage = (int)($piTotalRecord / $piNumberRecordOnList)+1;		
			}
			$psHtmlString = $psHtmlString . "<tr align='center'>";
			if($piCurrentPage >1){
				$piCurrentPagepre =$piCurrentPage - 1;
				$psHtmlString = $psHtmlString . "<td width='2%' onclick = 'break_page(\"$piCurrentPagepre\",\"$pUrl\");'>"."<a><b><u>Trước</u></b></a>"."</td>";
			}
			//Truong hop tong so trang nho hon so trang can hien thi
			if($psNumberPage <= $piEndPage){ 
				for ($index = 1; $index <= $psNumberPage; $index++){
					$iCurren = $index;
					if($index == $piCurrentPage){
						$iCurren = "<b>" . $index . "</b>";
					}
					$psHtmlString = $psHtmlString . "<td width='1%' onclick='break_page(\"$index\",\"$pUrl\"); '>"."<a><span>".$iCurren."</span></a>". "</td>";		
				}
			//Truong hop tong so trang lon hon so trang can hien thi	
			}else {
				if($piCurrentPage >6){
					//Hien thi truoc va sau trang hien tai 5 trang
					$piStartPage = $piCurrentPage - 5;
					$index = $piStartPage;
					//Neu chi so trang dau tien cong trang cuoi cung nho hon tong so trang
					if($piStartPage + $piEndPage < $psNumberPage){
						for ($index; $index <= $piStartPage + $piEndPage; $index++){
							$iCurren = $index;
							if($index == $piCurrentPage){
								$iCurren = "<b>" . $index . "</b>";
							}						
							$psHtmlString = $psHtmlString . "<td width='1%' onclick='break_page(\"$index\",\"$pUrl\"); '>"."<a><span>".$iCurren."</span></a>". "</td>";				
						}
					//Neu chi so trang dau tien cong trang cuoi cung lon hon tong so trang	
					}else{
						$index = $psNumberPage - $piEndPage;
						for ($index; $index <= $psNumberPage; $index++){
							$iCurren = $index;
							if($index == $piCurrentPage){
								$iCurren = "<b>" . $index . "</b>";
							}
							$psHtmlString = $psHtmlString . "<td width='1%' onclick='break_page(\"$index\",\"$pUrl\"); '>"."<a><span>".$iCurren."</span></a>". "</td>";			
						}
					}
				}else{
					for ($index=$piStartPage; $index <=$piEndPage; $index++){	
						$iCurren = $index;
						if($index == $piCurrentPage){
							$iCurren = "<b>" . $index . "</b>";
						}
						$psHtmlString = $psHtmlString . "<td width='1%' onclick='break_page(\"$index\",\"$pUrl\"); '>"."<a><span>".$iCurren."</span></a>". "</td>";					
					}
				}
			}
			if($piCurrentPage < $psNumberPage){
				$piCurrentPagenex = $piCurrentPage +1;
				$psHtmlString = $psHtmlString . "<td width='2%' onclick = 'break_page(\"$piCurrentPagenex\",\"$pUrl\");'>"."<a><b><u>Tiếp</u></b></a>" ."</td>";
			}
			$psHtmlString = $psHtmlString . "</tr>";		
			return $psHtmlString;
		}	
	}
	/**
	 * Creater: Nghiat
	 * Date: 07/06/2010
	 * IDea: Lay mot ngay bat ky trong tuan cua nam(Trang 1; Trang 2;...)
	 *
	 * @param $year(int) : Xac dinh nam
	 * @param $numberOfWeek(int) : Tuan thu may trong nam
	 * @param $orderDate : Lay ngay thu may trong tuan
	 * @return Ngay trong tuan
	 */
	public	function _getAnyDateOnWeekOfYear($year, $numberOfWeek, $orderDate){
		//Xac dinh ngay 1/1 la thu may
		$timestamp = mktime(0,0,0,1,1,$year);
		$fisrtDayOfYear = date('w',$timestamp);
		if ($fisrtDayOfYear == 0) {$fisrtDayOfYear = 7;}
		//Xac dinh ngay dau cua tuan thu nhat la ngay nao, ngay nay co the thuoc nam truoc.
		if ($fisrtDayOfYear <= 4){
		$day = 2 - $fisrtDayOfYear;
		}else{
		$day = 9 - $fisrtDayOfYear;
		}
		for ($i=1; $i <= 53; $i++){
		if($i == $numberOfWeek){
		$dateOfWeek = date("d/m/Y",mktime(0,0,0,1,$day+$orderDate,$year));
		break;
		}
		$day = $day + 7;
		//Kiem tra tuan sau da sang tuan thu nhat cua nam sau chua.
		if (date("W",mktime(0,0,0,1,$day,$year)) == 1){break;}
		}
		return $dateOfWeek;
	}
	/**
	 * Creater: Phuongtt
	 * Date: 16/05/2010
	 * Dis: lay CODE boi Name trong danh muc
	 *
	 * @param  $p_array
	 * @param  $p_attr_name
	 * @param  $p_id
	 * @return Ten cua doi tuong duoc truyen vao
	 */
	public function _getCodeByName($p_array,$p_attr_name,$p_id) {		
		try {
			foreach($p_array as $staff){
				if ($staff['C_NAME'] == $p_attr_name){					
					return $staff[$p_id];
					break;
				}
			}
			return "";
		}catch (Exception $ex){;}
	}	
	/**
	 * Creater: Phuongtt
	 *
	 * @param $strText: chuoi ky tu can chuyen font tu VN sang EN
	 * @return tra ve chuoi khong dau
	 */
    function _convertVNtoEN($strText){
    	$vnChars = array("á","à","ả","ã","ạ","ă","ắ","ằ","ẳ","ẵ","ặ","â","ấ","ầ","ẩ","ẫ","ậ","é","è","ẻ","ẽ","ẹ","ê","ế","ề","ể","ễ","ệ","í","ì","ỉ","ĩ","ị","ó","ò","ỏ","õ","ọ","ô","ố","ồ","ổ","ỗ","ộ","ơ","ớ","ờ","ở","ỡ","ợ","ú","ù","ủ","ũ","ụ","ư","ứ","ừ","ử","ữ","ự","ý","ỳ","ỷ","ỹ","ỵ","đ","Á","﻿À","Ả","Ã","Ạ","Ă","Ắ","Ằ","Ẳ","Ẵ","Ặ","Â","Ấ","Ầ","Ẩ","Ẫ","Ậ","É","È","Ẻ","Ẽ","Ẹ","Ê","Ế","Ề","Ể","Ễ","Ệ","Í","Ì","Ỉ","Ĩ","Ị","Ó","Ò","Ỏ","Õ","Ọ","Ô","Ố","Ồ","Ổ","Ỗ","Ộ","Ơ","Ớ","Ờ","Ở","Ỡ","Ợ","Ú","Ù","Ủ","Ũ","Ụ","Ư","Ứ","Ừ","Ử","Ữ","Ự","Ý","Ỳ","Ỷ","Ỹ","Ỵ","Đ");
    	$enChars = array("a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","e","e","e","e","e","e","e","e","e","e","e","i","i","i","i","i","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","d","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y","D");
    	for($i =0; $i <sizeof($vnChars); $i ++){
			$strText = str_replace($vnChars[$i], $enChars[$i], $strText);
		}	
		return 	$strText; 
    }
    /**
     *Creater: HUNGVM
     *create date: 19/10/2008
     *Idea: Generate list empty row
     * @param  string $pCurrentRow => number current row
     * @param  string $pTotalRow => number total row
     * @param  string $pCurrentStyleName class name
     * @param  string $pNextStyleName class name
     * @param  string $pTotalColumn number colums
     * @return $strHTML
     */
    public  function _addEmptyRow($pCurrentRow,$pTotalRow,$pCurrentStyleName,$pTotalColumn) {    			
		//Dinh dang style
		if ($pCurrentStyleName == "odd_row"){
			$pNextStyleName = "round_row";
		}else{
			$pNextStyleName = "odd_row";
		}    		
		
		if($pCurrentRow>=$pTotalRow) {
			return "";
		}
		$strHTML = "";
		$style_name = $pCurrentStyleName;
		for($ii=$pCurrentRow+1;$ii<=$pTotalRow;$ii++) {
			if($style_name == $pCurrentStyleName) { 
				$style_name = $pNextStyleName;
			} else {
				$style_name = $pCurrentStyleName;
			}
			$strHTML.='<tr class='.'"'."$style_name".'"'.'>';
			for($jj=1;$jj<=$pTotalColumn;$jj++) {		
				$strHTML.="<td>&nbsp;</td>";
			}
			$strHTML.="</tr>";
		}
		return $strHTML;
	}
	/**
     *Creater: HUNGVM
     *create date: 19/10/2008
     *Idea: Generate header of table
     *parameter:
     * 		@param  array $widthCols => height of cols
     *	 	@param  array $TitleCols => title of cols
     * 		@param  string $delimitor special char
     * 		@return $strHTML
     * 
     * 
     * Editer: TOANHV
     * Edit date: 17/09/2009
     */
     public  function _GenerateHeaderTable($widthCols,$TitleCols,$delimitor) {       	
    	//Xu ly sinh cac cot ung voi do rong tuong ung cua Table    	
    	$arrWidthCol = explode($delimitor,$widthCols);//Mang luu thong tin do rong cac cot
    	$arrTitleCol = explode($delimitor,$TitleCols);//Mang luu thong tin luu ten tot tuong ung    	
    	$countCol = sizeof($arrWidthCol);
    	$countTitle = sizeof($arrTitleCol);
    	//Tao col
    	$strHTML = "";    	    	
    	for($index = 0; $index < $countCol; $index++){
    		$strHTML = $strHTML . "<col width='" . $arrWidthCol[$index] . "'>";
    	}
    	$psHtmlTempWidth = $strHTML;
    	$strHTML = $strHTML . "<tr class='header'>"; 	
    	for($index = 0; $index < $countCol; $index++){
			$styleCol = "";
			$strHTML = $strHTML . "<td class = 'title' style = 'text-align:center;'>" . $arrTitleCol[$index] . "</td>";
    	}  
    	$strHTML = $strHTML . "</tr>";
    	return $strHTML . "!~~!". $psHtmlTempWidth;
	
	}	
	/**
     *Creater: HUNGVM
     *create date: 19/10/2008
     *Idea: Generate select (number page)
     *parameter:
     * 		@param  array $widthCols => height of cols
     *	 	@param  array $TitleCols => title of cols
     * 		@param  string $delimitor special char
     * 		@return $strHTML
     */
    public  function _generateSelectBoxPage($totalRow,$currentPage,$numRowOnPage,$actionUrl){

		if($totalRow % $numRowOnPage == 0){
			$numpage = (int)($totalRow/$numRowOnPage);
		}else{
			$numpage = (int)($totalRow/$numRowOnPage)+1;		
		}	
		//Mang luu tong so trang
		$arrCurrentPage	= array();		
		for($index = 1; $index <= $numpage; $index++){
			$arrCurrentPage[] = $index;
		}
		$strHTML = "";
		$strHTML = $strHTML . "<table width = '100%' border = '0' cellpadding='0' cellspacing='0' align='right'>";
		$strHTML = $strHTML . "<tr align='right'>";
		$strHTML = $strHTML . "<td align='right' class='small_label'><font color='Red' size='2px'>";
		$strHTML = $strHTML . "Xem trang  " . $this->formSelect('C_CURRENT_PAGE',$currentPage,array("id"=>"sel_current_page","onChange"=>"document.forms(0).hdn_page.value = this.value;_action_url('" . $actionUrl . "');"),$arrCurrentPage);					
		$strHTML = $strHTML . "Hi&#7875;n th&#7883;  " . $this->formSelect('C_NUM_RECORD_ON_PAGE',$numRowOnPage,array("id"=>"sel_num_page","onChange"=>"document.forms(0).hdn_record_number_page.value = this.value;_action_url('" . $actionUrl . "');"),array("3"=>"3","15"=>"15","30"=>"30","50"=>"50","100"=>"100")) . " S&#7889; d&#242;ng/trang";
		$strHTML = $strHTML . "</font></td></tr></table>";    	    	
    	return $strHTML;
	
	}	
}?>