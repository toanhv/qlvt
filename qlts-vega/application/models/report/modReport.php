<?php
/**
 * Nguoi tao: Phongtd
 * Ngay tao; 19/11/2010
 *
 */
class report_modReport extends Efy_DB_Connection {	
	/**
	 * @see: Ham thuc hien lay tat ca cac thong tin Bao cao
	 * @param :
	 * 			$psFilterXmlString: Chuoi Xml mo ta tieu chi loc
	 * 			$psXmlFileName: Ten file XMl
	 * @return :Mang chua thong tin 
	 * 	
	 * */
	public function getAllReportProject($psFilterXmlString,$psXmlFileName){
		//Tao doi tuong Efy_Library
		$objEfyLib = new Efy_Library();
		
		//Tao doi tuong Efy_Publib_Xml
		Zend_Loader::loadClass('Efy_Publib_Xml');
		$objEfyLibXml = new Efy_Publib_Xml();

		//Doc file XML
		$psXmlStringInFile = $objEfyLib->_readFile($psXmlFileName);
		//echo '$psXmlStringInFile ='.$psXmlStringInFile ;
		$psSqlString = $objEfyLibXml->_xmlGetXmlTagValue($psXmlStringInFile,"report_sql","sql");
		//echo '<br> psSqlString = ' . $psSqlString . '<br>';	//exit;	
		
		// Thay the gia tri trong file xml 
		$psSqlString = $objEfyLibXml->_replaceTagXmlValueInSql($psSqlString, $psXmlStringInFile, 'filter_row', $psFilterXmlString);			
		//echo '<br>$psSqlString = ' .$psSqlString .'<br>';exit;
		//Thuc thi lenh SQL
		$arrResult = $this->adodbQueryDataInNameMode($psSqlString);	
		//echo 'psSqlString = '.$psSqlString; exit;
		$piCount = sizeof($arrResult);
		for ($index = 0; $index < $piCount;$index++){
			$arrResult[$index]['FK_UNIT'] = Efy_Function_DocFunctions::DocGetNameProcessingPlace($arrResult[$index]['FK_UNIT']);
		}		
		return $arrResult;		
		var_dump($arrResult);
	}
		
	/**
	 * @see : Lay thong tin mot bao cao
	 * @param :
	 * 			$psReportId: id cua  bao cao
	 * @return :Mang chua thong tin cua mot bao cao
	 * 
	 * */
	public function getSingleReportStaff($psReportId){
		$sql = "Exec CBCC_ReportGetSingle '" . $psReportId . "'";		
//		echo $sql;
		try {						
			$arrResult = $this->adodbExecSqlString($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;	
	}

	/**

	 * @see : Lay danh sach loai bao cao
	 * @param :
	 * 			$psReportListTypeCode: Ma Loai Danh muc : DM_BAO_CAO
	 * 			
	 * @return : 
	 * 			$arrResult 
	 * 			
	 * */
	public function getAllReportByReportType($psReportListTypeCode){			
		$sql = "Exec [f_GetAllReportByReporttype]  '" . $psReportListTypeCode ."'";		
		//echo $sql ; //exit();
		try {			
			$arrResult = $this->adodbQueryDataInNameMode($sql) ; 			
			
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;	
	}
	
	/**
	 * @see : Lay danh sach loai bao cao
	 * @param :
	 * 			$arrParam: Mang chua danh muc
	 * 			
	 * @return : $sHtmlRes : chuoi mo ta mot danh sach cac checkbox
	 * 			
	 * */
	public function showListReport($arrParam,$sChecked){
		
		//Tao doi tuong trong trong thu vien dung chung
		$objLib = new Efy_Library();
		//
		$sHtmlRes = '<table class="list_table2"  width="99%" cellpadding="0" cellspacing="0" align="right">
					<col width="5%"><col width="95%">'		;
			
		for ($i=0;$i<sizeof($arrParam);$i++) {
			$v_report_code = $arrParam[$i]['C_CODE'];
			$v_report_name =Efy_Publib_Library::_replaceBadChar($arrParam[$i]['C_NAME']);
			if ($v_current_style_name == 'odd_row'){
				$v_current_style_name = 'round_row';
			}else{
				$v_current_style_name = 'odd_row';
			}				
										
			$v_report_checked = '';
			if ($sChecked!='' && $sChecked == $v_report_code)
			$v_report_checked = 'checked';
			// In danh sach
			$sHtmlRes = $sHtmlRes.'<tr class="'. $v_current_style_name.'">'.
								'<td ><input type="radio" message="Phai chon it nhat mot BAO CAO " name="opt_reporttype_id" readonly="true"  value="'. $v_report_code.'"' . $v_report_checked.' onClick="btn_rad_onclick(this,document.getElementById(\'hdn_Report_id\'));document.forms[0].submit();"></td>
								<td colspan="10">' .$v_report_name.'</td></tr>';
			
		}	
		
		$sHtmlRes = $sHtmlRes.'</table>'	;				
		return $sHtmlRes;
		//var_dump($sHtmlRes);
	}
	/**
	 * @see : Lay ten file xml dung de bao cao
	 * @param :
	 * 			$arrList: Mang chua danh muc
	 * 			$sListCode: Ma cua ten file xml
	 * 			
	 * @return : 
	 * 			$sFileNameXml : Ten file xml
	 * 			
	 * */
	public function getFileNameXml($arrList,$sListCode){
		
		// Load thu vien xml
		Zend_Loader::loadClass("Efy_Publib_Xml");
		
		// Neu khong co file nao thi lay mac dinh
		$sFileNameXml = "BC.001.xml"; 
		
		for ($i=0;$i<sizeof($arrList);$i++){			
			if ($arrList[$i]['C_CODE'] == $sListCode){
				$sFileNameXml =  Efy_Publib_Xml::_xmlGetXmlTagValue($arrList[$i]['C_XML_DATA'],'data_list','xml_file_name');
			}
		}
		echo $sFileNameXml; //exit;
		return $sFileNameXml;		
	}	

}
