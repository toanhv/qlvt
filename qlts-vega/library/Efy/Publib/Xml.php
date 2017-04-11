<?php 
require_once 'Efy/prax.php';
/**
 * Nguoi tao: HUNGVM 
 * Ngay tao: 09/01/2009
 * Noi dung: Tao lop Efy_Publib_Xml xu ly XML
 */
class Efy_Publib_Xml extends RAX {		
	/**
	 * @Idea: Ham _xmlGetXmlTagValue tra lai mot array chua ten cac the XML va gia tri tuong ung TRONG MOT NHANH cua file XML
	 * @param : 
	 * 		+ $spXmlFileName: XML file nam
	 * 		+ $p_xml_parent_tag: ten the XML CHA  - xac dinh nhanh can lay thong tin(phai la the cap 1 sau the root)
	 * 		+ $psXmlTag: ten the XML can lay gia tri
	 * Vi du:
	 * $ret = _xmlGetXmlTagValue ("../xml/quan_tri_doi_tuong_danh_muc.xml","listtype_type","label");
	 * echo $ret;
	 */
	public function _xmlGetXmlTagValue($spXmlString, $spXmlParentTag, $spXmlTag){		
		//Neu ton tai xau XML
		if ($spXmlString != ""){
			$objXmlData = new Zend_Config_Xml($spXmlString,$spXmlParentTag);
			return Efy_Library::_restoreXmlBadChar($objXmlData->$spXmlTag);
		}else{
			return "";
		}
	}	
	/**
	 *$spXmlFileName: duong dan toi file XML mo ta cac form field
	 $psXmlTag: ten THE XML xac dinh NHANH mo ta cac form field. Phai lay ten THE mo ta cau truc bang
	 $p_xml_string_in_db: xau XML lay tu CSDL
	 $p_arr_item_value: array chua gia tri cua cac column
	 $p_input_file_name: De xac dinh truyen vao xau xml hay ten file xml
	 Vi du: echo _XML_generate_formfield("../xml/quan_tri_doi_tuong_danh_muc.xml", "update_row", $v_xml_str, $arr_single_list);
	*/
	 /*------------------------khai bao bien dung chung--------------------------*/
	 public $sLabel;							public $efyImageUrlPath;
	 public $efyLibUrlPath;						public $efyWebSitePath;
	 public $efyListWebSitePath;				public $isaMaxFileSizeUpload;
	 public $spType;							public $spDataFormat;
	 public $spMessage;							public $optOptional;
	 public $xmlData;							public $columnName;
	 public $xmlTagInDb;						public $readonlyInEditMode;
	 public $disabledInEditMode;				public $note;
	 public $relateRecordType;					public $width;
	 public $height;							public $row;
	 public $rowId;								public $max;
	 public $min;								public $maxlength;
	 public $tooltip;							public $count;
	 public $selectBoxOptionSql;				public $selectBoxIdColumn;
	 public $selectBoxNameColumn;				public $functionValue;
	 public $theFirstOfIdValue;					public $checkBoxMultipleSql;
	 public $checkBoxMultipleIdColumn;			public $checkBoxMultipleNameColumn;
	 public $direct;							public $textBoxMultipleSql;
	 public $textBoxMultipleIdColumn;			public $textBoxMultipleNameColumn;
	 public $firstWidth;						public $fileAttachSql;
	 public $fileAttachMax;						public $descriptionName;
	 public $hideUpdateFile;					public $tableName;
	 public $orderColumn;						public $whereClause;
	 public $directory;							public $fileType;
	 public $jsFunctionList;					public $jsActionList;
	 public $value;								public $xmlStringInFile;
	 public $jsFunctionAfterSelect;				public $pathRootToModul;
	 public $inputData;							public $sessionName;
	 public $sessionIdIndex;					public $sessionNameIndex;
	 public $sessionValueIndex;					public $channelSql;
	 public $url;								public $path;
	 public $otherAttribute;					public $radioValue;
	 public $phpFunction;						public $content;
	 public $mediaFileOnclickUrl;				public $mediaFileName;
	 public $mediaFileNameColumn;				public $mediaFileUrlColumn;
	 public $title;								public $className;
	 public $haveTitleValue;					public $defaultValue;
	 public $hrf;								public $spLabel_list;
	 public $colWidthList;						public $tblSqlString;
	 public $colNameInDbList;					public $colInputTypeList;
	 public $viewMode;							public $publicListCode;
	 public $storeInChildTable;					public $textboxSql;
	 public	$textboxIdColumn; 					public $textboxNameColumn;
	 public $textboxFuseaction;					public $display;
	 public $dspDiv;							public $cacheOption;
	 public $sDataFormatStr;					public $optOptionalLabel;
	 public $formFielName;						public $counterFileAttack;
	 public $v_align;							public $value_id;
	 public $v_inc;								public $v_label;
	 public $groupBy;							public $xmlDataCompare;
	 public $xmlTagInDb_list;					public $viewPosition;
	 public $onclickFunction;					public $widthLabel;
	 /*------------------------khai bao bien dung chung--------------------------*/
	 /**
	  * Ham tao form lay mau tu file XML
	  * Enter description here ...
	  * @param unknown_type $spXmlFileName		: Ten file XML
	  * @param unknown_type $pathXmlTagStruct	: Duong dan den the chua cau truc form
	  * @param unknown_type $pathXmlTag			: Duong dan den the chua thong tin cua cac phan tu tren form
	  * @param unknown_type $p_xml_string_in_db	: Gia tri trong cot XML vi du: C_XML_DATA
	  * @param unknown_type $p_arr_item_value	:
	  * @param unknown_type $p_input_file_name  :
	  * @param unknown_type $p_view_mode		:
	  */
	public function _xmlGenerateFormfield($spXmlFileName, $pathXmlTagStruct,$pathXmlTag, $p_xml_string_in_db, $p_arr_item_value,$p_input_file_name=true,$p_view_mode=false){		
		global $i;
		//Goi class lay tham so cau hinh he thong
		//Zend_Loader::loadClass('Efy_Init_Config');
		$ojbEfyInitConfig = new Efy_Init_Config();
		//Lay tham so cau hinh
		$this->efyImageUrlPath = $ojbEfyInitConfig->_setImageUrlPath();
		$this->efyLibUrlPath = $ojbEfyInitConfig->_setLibUrlPath();
		$this->efyWebSitePath = $ojbEfyInitConfig->_setWebSitePath();		
		// Lay toan bo URL cua web
		$this->efyListWebSitePath = $ojbEfyInitConfig->_getCurrentHttpAndHost();		
		// Tao doi tuong trong thu vien dung trung
		$ojbEfyLib = new Efy_Library();		
		$ojbEfyXmlLib = new Efy_Xml();		
		$this->viewMode = $p_view_mode;	
		if ($p_input_file_name)
			$this->xmlStringInFile = $ojbEfyLib->_readFile($spXmlFileName);
		Zend_Loader::loadClass('Zend_Config_Xml');
		$objConfigXml = new Zend_Config_Xml($spXmlFileName);
		$v_first_col_width = $objConfigXml->common_para_list->common_para->first_col_width;
		$v_js_file_name = $objConfigXml->common_para_list->common_para->js_file_name;
		$v_js_function = $objConfigXml->common_para_list->common_para->js_function;	
		$p_xml_string = $p_arr_item_value[0][$p_xml_string_in_db];	
		if($p_xml_string <> ''){
			$p_xml_string = '<?xml version="1.0" encoding="UTF-8"?>' . $p_xml_string;
			$objXmlData = new Zend_Config_Xml($p_xml_string,'data_list');
		}
		else
			$objXmlData = new Zend_Config_Xml($p_xml_string_in_db,'data_list');
		//echo htmlspecialchars($p_xml_string);exit;
		//Tao mang luu cau truc cua form
		$arrTagsStruct = explode("/", $pathXmlTagStruct);
		$strcode = '$arrTable_truct_rows = $objConfigXml->'.$arrTagsStruct[0];
		for($i = 1; $i < sizeof($arrTagsStruct); $i++)
			$strcode .= '->'.$arrTagsStruct[$i];
		eval($strcode.'->toArray();'); 
		//Tao mang luu thong tin cua cac phan tu tren form
		$arrTags = explode("/", $pathXmlTag);
		$strcode = '$arrTable_rows = $objConfigXml->'.$arrTags[0];
		for($i = 1; $i < sizeof($arrTags); $i++)
			$strcode .= '->'.$arrTags[$i];
		eval($strcode.'->toArray();');
		$sContentXmlTop = '<div id = "Top_contentXml">';
		$sContentXmlTopLeft = '<div id = "Topleft_contentXml">';
		$sContentXmlTopRight = '<div id = "Topright_contentXml">';
		$sContentXmlBottom = '<div id="Bottom_contentXml">';
		
		if(!is_array($arrTable_truct_rows[0])){	
			$arrTemp = array();		
			array_push($arrTemp,$arrTable_truct_rows);	
			$arrTable_truct_rows = $arrTemp; 			
		}
		foreach ($arrTable_truct_rows as $row){		
			$v_have_line_before = $row["have_line_before"];
			$v_tag_list = $row["tag_list"];
			$this->rowId = $row["row_id"];
			$this->viewPosition = $row["view_position"];
			$this->storeInChildTable = $row["store_in_child_table"];
			$v_sql_select_child_table = $row["sql_select_child_table"];
			$v_xml_data_column = $row["xml_data_column"];
			$v_hide_button = $row["hide_button"];	
			$arr_tag = explode(",", $v_tag_list);
			$this->count = 0;
			$spHtmlString_temp = '';
			$this->xmlTagInDb_list = '';
			$strdiv = '<div>';
			if ($this->rowId != '')
				$strdiv = '<div id = "id_' . $this->rowId . '">';
			if($this->viewPosition == 'left')
				$sContentXmlTopLeft .= $strdiv;		
			else if($this->viewPosition == 'right')
				$sContentXmlTopRight .= $strdiv;
			else 
				$sContentXmlBottom .= $strdiv;	
			
			$psHtmlTable = "";
			$psHtmlTag = "";
			for($i=0;$i < sizeof($arr_tag);$i++){
				$this->sLabel = $arrTable_rows[$arr_tag[$i]]["label"];
				$this->widthLabel = $arrTable_rows[$arr_tag[$i]]["width_label"];
				$this->spType = $arrTable_rows[$arr_tag[$i]]["type"];
				$this->spDataFormat = $arrTable_rows[$arr_tag[$i]]["data_format"];
				$this->inputData = $arrTable_rows[$arr_tag[$i]]["input_data"];
				$this->url = $arrTable_rows[$arr_tag[$i]]["url"];
				$this->width = $arrTable_rows[$arr_tag[$i]]["width"];
				$this->phpFunction = $arrTable_rows[$arr_tag[$i]]["php_function"];
				$this->row = $arrTable_rows[$arr_tag[$i]]["row"];
				$this->max = $arrTable_rows[$arr_tag[$i]]["max"];
				$this->min = $arrTable_rows[$arr_tag[$i]]["min"];
				$this->note = $arrTable_rows[$arr_tag[$i]]["note"];
				$this->spMessage = $arrTable_rows[$arr_tag[$i]]["message"];
				$this->optOptional = $arrTable_rows[$arr_tag[$i]]["optional"];
				$this->maxlength = $arrTable_rows[$arr_tag[$i]]["max_length"];
				$this->xmlData = $arrTable_rows[$arr_tag[$i]]["xml_data"];
				$this->columnName = $arrTable_rows[$arr_tag[$i]]["column_name"];
				$this->xmlTagInDb = $arrTable_rows[$arr_tag[$i]]["xml_tag_in_db"];
				$this->jsFunctionList = $arrTable_rows[$arr_tag[$i]]["js_function_list"];
				$this->jsActionList = $arrTable_rows[$arr_tag[$i]]["js_action_list"];
				$this->relateRecordType = $arrTable_rows[$arr_tag[$i]]["relate_recordtype"];	
				$this->defaultValue = $arrTable_rows[$arr_tag[$i]]["default_value"];
				$this->pathRootToModul = $arrTable_rows[$arr_tag[$i]]["pathRootToModule"];
				$this->jsFunctionAfterSelect = $arrTable_rows[$arr_tag[$i]]["js_function_after_select"];
				$this->readonlyInEditMode = $arrTable_rows[$arr_tag[$i]]["readonly_in_edit_mode"];
				$this->disabledInEditMode = $arrTable_rows[$arr_tag[$i]]["disabled_in_edit_mode"];
				$this->sessionName = $arrTable_rows[$arr_tag[$i]]["session_name"];
				//lay du lieu tu session
				$this->sessionIdIndex = $arrTable_rows[$arr_tag[$i]]["session_id_index"];
				$this->sessionNameIndex = $arrTable_rows[$arr_tag[$i]]["session_name_index"];
				$this->sessionValueIndex = $arrTable_rows[$arr_tag[$i]]["session_value_index"];
				$this->channelSql = $arrTable_rows[$arr_tag[$i]]["channel_sql"];
				$this->mediaFileNameColumn = $arrTable_rows[$arr_tag[$i]]["media_name"];
				$this->mediaFileUrlColumn = $arrTable_rows[$arr_tag[$i]]["media_url"];	
				$this->path = $arrTable_rows[$arr_tag[$i]]["path"];
				$this->title = $arrTable_rows[$arr_tag[$i]]["title"];
				$this->className = $arrTable_rows[$arr_tag[$i]]["class_name"];
				$this->haveTitleValue = $arrTable_rows[$arr_tag[$i]]["have_title_value"];
				$this->otherAttribute = ($arrTable_rows[$arr_tag[$i]]["other_attribute"] != '')? $arrTable_rows[$arr_tag[$i]]["other_attribute"]:'';
				$this->radioValue = $arrTable_rows[$arr_tag[$i]]["value"];
				$this->v_align = $arrTable_rows[$arr_tag[$i]]["align"];
				$v_valign = $arrTable_rows[$arr_tag[$i]]["valign"];	
				$this->hrf = $arrTable_rows[$arr_tag[$i]]["hrf"];
				$this->spLabel_list = $arrTable_rows[$arr_tag[$i]]["label_list"];
				$this->colWidthList = $arrTable_rows[$arr_tag[$i]]["col_width_list"];
				$this->tblSqlString = $arrTable_rows[$arr_tag[$i]]["tbl_sql_string"];
				$this->colNameInDbList = $arrTable_rows[$arr_tag[$i]]["col_name_in_db_list"];
				$this->colInputTypeList = $arrTable_rows[$arr_tag[$i]]["col_input_type_list"];
				$this->publicListCode = $arrTable_rows[$arr_tag[$i]]["public_list_code"];
				$this->cacheOption = $arrTable_rows[$arr_tag[$i]]["cache_option"];	
				//echo 'cachr:' . $this->cacheOption . "<br>";	
				//Hien thi DIV
				$this->display = $arrTable_rows[$arr_tag[$i]]["display"];					
				
				//Cac thuoc tinh cua textbox lay du lieu tu dialog
				$this->textboxSql = $arrTable_rows[$arr_tag[$i]]["textbox_sql"];
				$this->textboxIdColumn = $arrTable_rows[$arr_tag[$i]]["textbox_id_column"];
				$this->textboxNameColumn = $arrTable_rows[$arr_tag[$i]]["textbox_name_column"];
				$this->textboxFuseaction = $arrTable_rows[$arr_tag[$i]]["textbox_fuseaction"];			
				if (($p_xml_string_in_db != '' || $p_xml_string != "") && $this->xmlData == "true"){
					$tag = $this->xmlTagInDb;
					$this->value = $objXmlData->$tag;
				}else{
					if ($this->spDataFormat=="isdate"){
						$this->value = Efy_Library::_yyyymmddToDDmmyyyy(Efy_Library::_replaceBadChar($p_arr_item_value[$this->columnName]));
					}else{
						$this->value = Efy_Library::_replaceBadChar($p_arr_item_value[$this->columnName]);
						if (trim($this->value) == ""){
							$this->value = Efy_Library::_replaceBadChar($p_arr_item_value[0][$this->columnName]);
						}	
					}
					$this->mediaFileName = $p_arr_item_value[$this->mediaFileNameColumn];
					$this->mediaFileOnclickUrl = $p_arr_item_value[$this->mediaFileUrlColumn];
				}
				//Dat gia gi mac dinh cho doi tuong
				if (trim($this->defaultValue)!= "" && (is_null($p_arr_item_value) || sizeof($p_arr_item_value)==0 || $p_arr_item_value["chk_save_and_add_new"]=="true")){
					$v_arr_function_valiable = explode('(',$this->defaultValue);
					if(function_exists($v_arr_function_valiable[0])){
						$v_valiable = $v_arr_function_valiable[1];
						$v_valiable = str_replace(')','',$v_valiable);
						$v_valiable = str_replace('(','',$v_valiable);
						$v_arr_valiable = explode(',',$v_valiable);
						$v_call_user_function_str = "'".trim ($v_arr_function_valiable[0])."'";
						for($i=0;$i<sizeof($v_arr_valiable);$i++){
							$v_call_user_function_str = $v_call_user_function_str . "," . $v_arr_valiable[$i];
						}
						$v_call_user_function_str = "call_user_func(". $v_call_user_function_str . ")";
						eval("\$this->value = ".$v_call_user_function_str.";");
					}
					else{
						$this->value = $this->defaultValue;
					}
				}
				if ($this->spType=="selectbox" || $this->spType=="textselectbox"){
					$this->selectBoxOptionSql = $arrTable_rows[$arr_tag[$i]]["selectbox_option_sql"];
					$this->selectBoxIdColumn = $arrTable_rows[$arr_tag[$i]]["selectbox_option_id_column"];
					$this->selectBoxNameColumn = $arrTable_rows[$arr_tag[$i]]["selectbox_option_name_column"];
					$this->theFirstOfIdValue = $arrTable_rows[$arr_tag[$i]]["the_first_of_id_value"];
				}
				
				if ($this->spType=="channel"){
					$this->channelSql = $arrTable_rows[$arr_tag[$i]]["channel_sql"];
				}			
				if ($this->spType=="button"){
					$this->onclickFunction = $arrTable_rows[$arr_tag[$i]]["onclick_function"];
				}			
				//Kieu file attach-file
				if ($this->spType=="file_attach"){
					$this->fileAttachSql = $arrTable_rows[$arr_tag[$i]]["file_attach_sql"];
					$this->fileAttachMax = $arrTable_rows[$arr_tag[$i]]["file_attach_max"];
					$this->descriptionName = $arrTable_rows[$arr_tag[$i]]["description_name"];
					$this->hideUpdateFile = $arrTable_rows[$arr_tag[$i]]["hide_update_file"];
				}					
				//Kieu hien thi multil checkbox hoac radio
				if ($this->spType=="multiplecheckbox" || $this->spType=="multipleradio"){
					$this->checkBoxMultipleSql = $arrTable_rows[$arr_tag[$i]]["checkbox_multiple_sql"];
					$this->checkBoxMultipleIdColumn = $arrTable_rows[$arr_tag[$i]]["checkbox_multiple_id_column"];
					$this->checkBoxMultipleNameColumn = $arrTable_rows[$arr_tag[$i]]["checkbox_multiple_name_column"];
					$this->direct = $arrTable_rows[$arr_tag[$i]]["direct"];
					$this->firstWidth = $arrTable_rows[$arr_tag[$i]]["first_width"];
					$this->dspDiv = $arrTable_rows[$arr_tag[$i]]["dsp_div"];
				}					
				//Kieu multil textbox
				if ($this->spType=="multipletextbox"){
					$this->firstWidth = $arrTable_rows[$arr_tag[$i]]["first_width"];
					$this->textBoxMultipleSql = $arrTable_rows[$arr_tag[$i]]["textbox_multiple_sql"];
					$this->textBoxMultipleIdColumn = $arrTable_rows[$arr_tag[$i]]["textbox_multiple_id_column"];
					$this->textBoxMultipleNameColumn = $arrTable_rows[$arr_tag[$i]]["textbox_multiple_name_column"];

				}					
				if ($this->spType=="textboxorder"){
					$this->tableName = $arrTable_rows[$arr_tag[$i]]["table_name"];
					$this->orderColumn = $arrTable_rows[$arr_tag[$i]]["order_column"];
					$this->whereClause = $arrTable_rows[$arr_tag[$i]]["where_clause"];
				}					
				if ($this->spType=="fileserver"){
					$this->directory = $arrTable_rows[$arr_tag[$i]]["directory"];
					$this->fileType = $arrTable_rows[$arr_tag[$i]]["file_type"];
				}					
				if ($this->spType=="media" || $this->spType=="iframe"){
					$this->height = $arrTable_rows[$arr_tag[$i]]["height"];
				}					
				if ($this->spType=="labelcontent"){
					$this->content = $arrTable_rows[$arr_tag[$i]]["content"];
				}					
				if(is_null($v_valign) or trim($v_valign) == ''){ 
					if ($this->spType=="textarea"){
						$v_valign = "top";
					}else{
						$v_valign = "middle";
					}
				}			
				//Kiem tra neu ma form them moi thi cho phep nhap du lieu
				if ((is_null($this->value) || $this->value=='') && $this->spType != "channel"){
					$this->readonlyInEditMode = "false";
					$this->disabledInEditMode = "false";
				}
				if($this->viewPosition == 'left'){
					$sContentXmlTopLeft .= Efy_Publib_Xml::_generateHtmlInput();	
				}
				else if($this->viewPosition == 'right'){
					$sContentXmlTopRight .= Efy_Publib_Xml::_generateHtmlInput();	
				}
				else {
					$sContentXmlBottom .= Efy_Publib_Xml::_generateHtmlInput();	
				}	
			}
			if($this->viewPosition == 'left')
				$sContentXmlTopLeft .= '</div>';		
			else if($this->viewPosition == 'right')
				$sContentXmlTopRight .= '</div>';
			else 
				$sContentXmlBottom .= '</div>';	
			if($this->v_align != '' && !(is_null($this->v_align))){ 
				$this->v_align = "align='".$this->v_align."'";
			}else{
				$this->v_align = '';
			}	
		}
		if($v_js_file_name != '' && !(is_null($v_js_file_name))){
			$spHtmlStr .= "<script src = '$v_js_file_name'></script>";
		}
		if($v_js_function != '' && !(is_null($v_js_function))){
			$spHtmlStr .= '<script>try{'.$v_js_function.'}catch(e){;}</script>';
		}	
		$sContentXmlTopLeft .= '</div>';
		$sContentXmlTopRight .= '</div>';
		$sContentXmlTop .= $sContentXmlTopLeft . $sContentXmlTopRight . '</div>';
		$sContentXmlBottom .= '</div>';
		if($sContentXmlTop != '<div id = "Top_contentXml"><div id = "Topleft_contentXml"></div><div id = "Topright_contentXml"></div></div>'){
			$spHtmlStr .= $sContentXmlTop;
			$spHtmlStr .='<script type="text/javascript">$(function(){$(\'#Top_contentXml\').equalHeights();});</script>';
		}
		if($sContentXmlBottom != '<div id="Bottom_contentXml"></div>')
			$spHtmlStr .= $sContentXmlBottom;
		$spHtmlStr .= '<style> #Bottom_contentXml div label{width:' . $v_first_col_width . ';} #Top_contentXml div label{width:' . ($v_first_col_width * 2) . '%;}</style>';
		return $spHtmlStr;	
	}	
	/**
	 * @idea:Tao chuoi html ung voi doi tuong de generate form fields 
	 */	
	private function _generateHtmlInput(){
		global $i;
		//Sinh ra cac thuoc tinh dung cho viec kiem hop du lieu tren form
		$this->sDataFormatStr = Efy_Publib_Xml::_generateVerifyProperty($this->spDataFormat);		
		$url_path_calendar = '"'.$this->efyLibUrlPath.'efy-calendar/"';
		$this->optOptionalLabel = "";
		if ($this->optOptional == "false"){
			$this->optOptionalLabel = "<small class='normal_starmark'>*</small>";
		}
		$styleLabel = '';
		if($this->widthLabel != '')
			$styleLabel = "width:" . $this->widthLabel;
		if($this->sLabel == '')
			$this->sLabel = "&nbsp;";
		if($i > 0)
			//Neu co nhieu hon 1 phan tu nam tren 1 hang
			$v_str_label = '<label style="float:none;'.$styleLabel.'">' . $this->sLabel.$this->optOptionalLabel . '</label>';
		else
			$v_str_label = '<label style = "'.$styleLabel.'">' . $this->sLabel.$this->optOptionalLabel . '</label>';
		$v_checked = "";
	
		if ($this->xmlData=='true'){
			$this->formFielName = $this->xmlTagInDb;
		}else{
			$this->formFielName = $this->columnName;
		}
		switch($this->spType){
			case "table";
				$spRetHtml = $spRetHtml . $this->sLabel.$this->optOptionalLabel;
				$spRetHtml = $spRetHtml . _generate_html_for_table();
				break;
			case "label";
				if($this->className !=""){
					$spRetHtml = "<span class='".$this->className."'>".$this->sLabel.$this->optOptionalLabel."</span>";
				}else{
					$spRetHtml = $this->sLabel.$this->optOptionalLabel;
				}
				break;
			case "link";
				$this->hrf = str_replace('"','&quot;',$this->hrf);
				$spRetHtml = '<a class="normal_link" href="'.$this->hrf.'">'.$this->sLabel.'</a>';
				break;
			case 'small_title':
				$spRetHtml .= $v_str_label . '<label class="small_title" valign="bottom">' . $this->value . '</label>';
				break;
			case "media_file";
				$spRetHtml .= '<object id="MediaPlayer" classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">';
				$spRetHtml .= '<param id="MediaPlayer_FileName" name="filename" value="'.$this->mediaFileOnclickUrl.'">';
				$spRetHtml .= '<param name="Showcontrols" value="True"><param name="autoStart" value="False">';
				$spRetHtml .= '</object>';
				break;	
						
			case "file_upload";
				$spRetHtml = $v_str_label;
				$spRetHtml = $spRetHtml ."<input type='file' name='file_media_upload' value='$this->value' class='normal_textbox' title='$this->tooltip' style='width:$this->width' onKeyDown='change_focus(document.forms[0],this,event)'".Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).">";
				$spRetHtml = $spRetHtml . $this->note;
				break;
				
			case "file";
				$spRetHtml = $spRetHtml . $v_str_label;
				$spRetHtml = $spRetHtml ."<input type='file' name='file_attach' value='$this->value' class='normal_textbox' title='$this->tooltip' style='width:$this->width' onKeyDown='change_focus(document.forms[0],this,event)'".Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).">";
				$spRetHtml = $spRetHtml . $this->note;
				break;
				
			case "fileclient";
				$v_file_attack_name= "txt_xml_file_name". $this->counterFileAttack;
				$spRetHtml = $v_str_label;				
				$spRetHtml = $spRetHtml ."<div name='$this->formFielName' style='display:none'><input id = '$this->formFielName' type='text' name='$this->formFielName'class='normal_textbox' title='$this->tooltip' value='$this->value'  style='width:$this->width' style='border=0' readonly  $this->sDataFormatStr xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' ". Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)."></div>";
				$spRetHtml = $spRetHtml ."<input type='file' name='$v_file_attack_name' value='$this->value' class='normal_textbox' title='$this->tooltip' style='width:$this->width' onKeyDown='change_focus(document.forms[0],this,event)' OnChange=\"GetFileName(this,document.getElementById('".$this->formFielName."'))\">";
				$spRetHtml = $spRetHtml . "";
				$spRetHtml = $spRetHtml . $this->note;
				$this->counterFileAttack= $this->counterFileAttack +1;
				break;
				
			case "fileserver";
				$spRetHtml = $v_str_label;
				$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' class='normal_textbox' value='$this->value' directory='$this->directory' title='$this->tooltip' style='width:$this->width' xml_data='$this->xmlData' ".Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." $this->sDataFormatStr xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)' readonly>&nbsp;&nbsp;";
				$spRetHtml = $spRetHtml . "<input type='button' name='btn_choose' class='select_button' value='Ch&#7885;n' OnClick=\"_btn_show_all_file(document.forms[0].$this->formFielName.directory,'$this->fileType',document.forms[0].$this->formFielName);\" onKeyDown='change_focus(document.forms[0],this,event)'>";
				$spRetHtml = $spRetHtml . $this->note;
				break;
				
			case "file_attach";
				$arr_attach =_adodb_query_data_in_name_mode($this->fileAttachSql);
				$spRetHtml = '<dt>' . $v_str_label . '</dt>';
				$spRetHtml = $spRetHtml . process_attach($arr_attach,$this->fileAttachMax,$this->descriptionName);
				break;	
						
			case "textbox";				
				$spRetHtml = $spRetHtml . $v_str_label;
				if ($this->viewMode && $this->readonlyInEditMode=="true"){
					$spRetHtml = $spRetHtml . $this->value;
				}else{
					if ($this->spDataFormat == "isdate"){	
						$objBrower = new Efy_Publib_Browser();
						$brwName = $objBrower->Name;			
						$spRetHtml = $spRetHtml . '<input type="text" id="'.$this->formFielName.'"  name="'.$this->formFielName.'" class="normal_textbox" value="'.$this->value.'" title="'.$this->tooltip.'" style="width:'.$this->width.'" '.Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).' '.$this->sDataFormatStr.' store_in_child_table="'.$this->storeInChildTable.'" xml_tag_in_db="'.$this->xmlTagInDb.'" xml_data="'.$this->xmlData.'" column_name="'.$this->columnName.'" message="'.$this->spMessage.'" onKeyDown="change_focus(document.forms[0],this,event)">';
						$spRetHtml = $spRetHtml . "<img src='". $this->efyImageUrlPath. "calendar.gif' border='0' title='$this->tooltip' onclick='DoCal($url_path_calendar,document.getElementById(\"$this->formFielName\"),\"$brwName\");' style='cursor:pointer'>";
					}else{					
						$spRetHtml = $spRetHtml . '<input type="text" id="'. $this->formFielName.'" name="'.$this->formFielName.'" class="normal_textbox" value="'.$this->value.'" title="'.$this->tooltip.'" store_in_child_table="'.$this->storeInChildTable.'" style="width:'.$this->width.'" '.Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).' '.$this->sDataFormatStr.' store_in_child_table="'.$this->storeInChildTable.'" xml_tag_in_db="'.$this->xmlTagInDb.'" xml_data="'.$this->xmlData.'" column_name="'.$this->columnName.'" message="'.$this->spMessage.'" maxlength="'.$this->maxlength.'" ';
						if (rtrim($this->max) != '' && !is_null($this->max)){
							 $spRetHtml = $spRetHtml .' max="'.$this->max.'"';
						}
						if (rtrim($this->min) != '' && !is_null($this->min)){
							 $spRetHtml = $spRetHtml .' min="'.$this->min.'"';
						}
						$spRetHtml = $spRetHtml . ' onKeyDown="change_focus(document.forms[0],this,event)">';
					}
					$spRetHtml = $spRetHtml . $this->note;
				}
				break;
				
			case "text";
				$spRetHtml = $spRetHtml . $v_str_label;
				$spRetHtml = $spRetHtml.'<span class="data">'.$this->value.'&nbsp;</span>';
				break;
				
			case "identity";
				$this->value = $this->count+1;
				if ($this->value < 10){
					$spRetHtml = $spRetHtml.'<span class="data">0'.$this->value.'</span>';
				}else{
					$spRetHtml = $spRetHtml.'<span class="data">'.$this->value.'</span>';
				}
				break;
				
			case "checkbox";
				
				if ($this->value == "true" || $this->value==1){
					$v_checked = " checked ";
				}else{
					$v_checked = " ";
				}
				if($this->sLabel != '' || $this->optOptionalLabel != ''){
					$spRetHtml = "";
				}else{
					$spRetHtml = "";
				}
				$spRetHtml = $spRetHtml ."<input type='checkbox' id = '" . $this->formFielName . "' name='$this->formFielName' class='normal_checkbox' title='$this->tooltip' $v_checked value='1' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)'>";
				$spRetHtml = $spRetHtml . "" . $this->sLabel .$this->optOptionalLabel."";
				break;
			case "radio";
				if ($this->radioValue == $this->value || $this->value == "true"){
					$v_checked = " checked ";
				}else{
					$v_checked = " ";
				}
				$spRetHtml =  "<input type='radio' id = '" . $this->formFielName . "' name='$this->rowId' class='normal_checkbox' $v_checked value='$this->radioValue' title='$this->tooltip' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)'>";
				$spRetHtml = $spRetHtml . "" . $this->sLabel .$this->optOptionalLabel."";
				break;
				
			case "textarea";			
				$spRetHtml = $spRetHtml . $v_str_label;
				if ($this->viewMode && $this->readonlyInEditMode=="true"){
					$spRetHtml = $spRetHtml . $this->value;
				}else{
					$spRetHtml = $spRetHtml . '<textarea class="normal_textarea" id = "'.$this->formFielName.'" name="'.$this->formFielName.'" rows="'.$this->row.'" title="'.$this->tooltip.'" style="width:'.$this->width.'" '.Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).' xml_tag_in_db="'.$this->xmlTagInDb.'" xml_data="'.$this->xmlData.'" column_name="'.$this->columnName.'" message="'.$this->spMessage.'">'.$this->value.'</textarea>';
				}
				break;
			case "selectbox";
				$spRetHtml = $spRetHtml . $v_str_label;
				if ($this->viewMode && $this->readonlyInEditMode=="true"){
					if ($this->inputData == "session"){
						$j = 0;
						$arrListItem = array();
						if (isset($_SESSION[$this->sessionName])){
							foreach($_SESSION[$this->sessionName] as $arr_item) {
								$arrListItem[$j] = $arr_item;
								$j++;
							}
						}
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->sessionIdIndex];
						} 
						$spRetHtml = $spRetHtml . Efy_Publib_Xml::_getValueFromArray($arrListItem,$this->sessionIdIndex,$this->sessionNameIndex,$this->value);
					}elseif ($this->inputData == "efylist"){//Lay du lieu tu file XML					
						$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."xml/list/output/".$this->publicListCode.".xml");
						//
						$arrListItem = Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item");
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->selectBoxIdColumn];
						}
						$spRetHtml = $spRetHtml .Efy_Publib_Xml::_getValueFromArray($arrListItem,$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
					}else{		
						//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
						$this->selectBoxOptionSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->selectBoxOptionSql);					
						// thuc hien co che cache o day
						$arrListItem = Efy_DB_Connection::adodbQueryDataInNumberMode($this->selectBoxOptionSql,$this->cacheOption);											
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->selectBoxIdColumn];
						}
						$spRetHtml = $spRetHtml .Efy_Publib_Xml::_getValueFromArray($arrListItem,$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
					}
				}else{
					if ($this->inputData == "session"){
						$j = 0;
						$arrListItem = array();
						if (isset($_SESSION[$this->sessionName])){
							foreach($_SESSION[$this->sessionName] as $arr_item) {
								$arrListItem[$j] = $arr_item;
								$j++;
							}
						}
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->sessionIdIndex];
						}							
						//$spRetHtml = $spRetHtml . "<select id='$this->formFielName' class='normal_selectbox' name='$this->formFielName' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$v_xml_data' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)' >";
						$spRetHtml = $spRetHtml . "<select id='$this->rowId' class='normal_selectbox' name='$this->formFielName' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)' >";
						if ($this->theFirstOfIdValue == "true"){
							$spRetHtml = $spRetHtml . Efy_Library::_generateSelectOption($arrListItem,$this->sessionIdIndex,$this->sessionValueIndex,$this->sessionNameIndex,$this->value);
						}else{
							$spRetHtml = $spRetHtml . "<option id='' value='' name=''>--- Ch&#7885;n $this->sLabel  ---</option>".Efy_Library::_generateSelectOption($arrListItem,$this->sessionIdIndex,$this->sessionValueIndex,$this->sessionNameIndex,$this->value);
						}	
						$spRetHtml = $spRetHtml . "</select>";
	
					}elseif ($this->inputData == "efylist"){
						$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."xml/list/output/".$this->publicListCode.".xml");
						$arrListItem = Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item");
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->selectBoxIdColumn];
						}
						$spRetHtml = $spRetHtml . "<select id='$this->formFielName' class='normal_selectbox' name='$this->formFielName' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)' >";
						if(is_null($this->haveTitleValue) || ($this->haveTitleValue=="") || ($this->haveTitleValue=="true")){
							if($this->theFirstOfIdValue != "true"){
								$spRetHtml = $spRetHtml . "<option id='' value='' name=''>--- Ch&#7885;n $this->sLabel ---</option>";
							}	
						}
	
						$spRetHtml = $spRetHtml .Efy_Library::_generateSelectOption($arrListItem,$this->selectBoxIdColumn,$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
						$spRetHtml = $spRetHtml . "</select>";
					}else{		
						//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
						$this->selectBoxOptionSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->selectBoxOptionSql);	
						//echo $this->selectBoxOptionSql;
						// thuc hien cach
						$arrListItem = Efy_DB_Connection::adodbQueryDataInNumberMode($this->selectBoxOptionSql,$this->cacheOption);						
						if ( $this->theFirstOfIdValue =="true" && $this->value == "" ){
							$this->value = $arrListItem[0][$this->selectBoxIdColumn];
						}
						$spRetHtml = $spRetHtml . "<select id='$this->formFielName' class='normal_selectbox' name='$this->formFielName' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)' >";
						if(is_null($this->haveTitleValue) || ($this->haveTitleValue=="") || ($this->haveTitleValue=="true")){
							if($this->theFirstOfIdValue != "true"){
								$spRetHtml = $spRetHtml . "<option id='' value='' name=''>--- Ch&#7885;n $this->sLabel ---</option>";
							}	
						}						
						$spRetHtml = $spRetHtml .Efy_Library::_generateSelectOption($arrListItem,$this->selectBoxIdColumn,$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
						$spRetHtml = $spRetHtml . "</select>";
					}
				}
				break;
			case "multiplecheckbox";
				$spRetHtml = $v_str_label;
				if ($this->inputData == "session"){
					$spRetHtml = $spRetHtml . "<div style='display:none'><input type='textbox' id='$this->formFielName' name='$this->formFielName' value='' hide='true' readonly " . Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional) . "xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'></div>";
					$spRetHtml = $spRetHtml . Efy_Publib_Xml::_generateHtmlForMultipleCheckboxFromSession($this->sessionName, $this->sessionIdIndex,$this->sessionNameIndex,$this->value);
				}elseif ($this->inputData == "efylist"){
					$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."xml/list/output/".$this->publicListCode.".xml");
					$spRetHtml = $spRetHtml . Efy_Publib_Xml::_generateHtmlForMultipleCheckbox(Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item"),$this->checkBoxMultipleIdColumn,$this->checkBoxMultipleNameColumn,$this->value);
				}else{		
					//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
					$this->checkBoxMultipleSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->checkBoxMultipleSql);	
					$spRetHtml = $spRetHtml . "<div style='display:none'><input type='textbox' id='$this->formFielName' name='$this->formFielName' value='' hide='true' readonly " . Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional) . "xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'></div>";
					$spRetHtml = $spRetHtml . Efy_Publib_Xml::_generateHtmlForMultipleCheckbox(Efy_DB_Connection::adodbQueryDataInNumberMode($this->checkBoxMultipleSql,$this->cacheOption),$this->checkBoxMultipleIdColumn,$this->checkBoxMultipleNameColumn,$this->value);
				}
				break;
			case "multipleradio";
				if ($this->inputData == "efylist"){				
					$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."xml/list/output/".$this->publicListCode.".xml");
					$arrListItem = Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item");
				}else{
					$arrListItem = Efy_DB_Connection::adodbQueryDataInNumberMode($this->checkBoxMultipleSql,$this->cacheOption);
				}
			
				if($this->direct == 'true'){
					$spRetHtml = $this->sLabel.$this->optOptionalLabel;
					$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' value='$this->value' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
					$spRetHtml = $spRetHtml . _generate_html_for_multiple_radio($arrListItem,$this->checkBoxMultipleIdColumn,$this->checkBoxMultipleNameColumn,$this->value,$this->direct);
				}else{
					if($this->sLabel != "" && isset($this->sLabel)){
						$spRetHtml = $this->sLabel.$this->optOptionalLabel;
						if ($this->inputData == "session"){
							$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' value='' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
							$spRetHtml = $spRetHtml . _generate_html_for_multiple_radio_from_session($this->sessionName, $this->sessionIdIndex,$this->sessionNameIndex,$this->value);
						}else{					
							$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' value='$this->value' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
							$spRetHtml = $spRetHtml . _generate_html_for_multiple_radio($arrListItem,$this->checkBoxMultipleIdColumn,$this->checkBoxMultipleNameColumn,$this->value,$this->direct);
						}	
					}
					else{
						if ($this->inputData == "session"){
							$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' value='' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
							$spRetHtml = $spRetHtml . _generate_html_for_multiple_radio_from_session($this->sessionName, $this->sessionIdIndex,$this->sessionNameIndex,$this->value);
						}else{				
							$spRetHtml = $this->sLabel.$this->optOptionalLabel;
							$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' value='$this->value' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
							$spRetHtml = $spRetHtml . _generate_html_for_multiple_radio($arrListItem,$this->checkBoxMultipleIdColumn,$this->checkBoxMultipleNameColumn,$this->value,$this->direct);
						}						
					}
				}
				break;
			case "multipletextbox";
				if ($this->inputData == "efylist"){
					$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."listxml/output/".$this->publicListCode.".xml");
					$arrListItem = Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item");
				}elseif($this->inputData == "session"){
					$j = 0;
					$arrListItem = array();
					if (isset($_SESSION[$this->sessionName])){
						foreach($_SESSION[$this->sessionName] as $arr_item) {
							$arrListItem[$j] = $arr_item;
							$j++;
						}
					}
					$this->textBoxMultipleIdColumn = $this->sessionIdIndex;
					$this->textBoxMultipleNameColumn = $this->sessionNameIndex;
				}else{
					//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
					$this->textBoxMultipleSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->textBoxMultipleSql);	
					$arrListItem = Efy_DB_Connection::adodbQueryDataInNumberMode($this->textBoxMultipleSql,$this->cacheOption);
				}				
				$spRetHtml = $this->sLabel;
				$spRetHtml = $spRetHtml . "<input type='text' id='$this->formFielName' name='$this->formFielName' value='' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
				$spRetHtml = $spRetHtml . _generate_html_for_multiple_textbox($arrListItem,$this->textBoxMultipleIdColumn,$this->textBoxMultipleNameColumn,$this->value);
				break;
			case "treeuser";		
				$spRetHtml = $this->sLabel;
				$spRetHtml = $spRetHtml . "<input type='text' style='display:none' id='$this->formFielName' name='$this->formFielName' value='' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
				$spRetHtml = $spRetHtml . self::_generateHtmlForTreeUser($this->value);
				break;
			/*Tao ra mot cay user ma co parrent_id != parrent_id cua NSD dang nhap hien thoi*/		
			case "treealluser";		
				$spRetHtml = $this->sLabel;
				$spRetHtml = $spRetHtml . "<input type='text' id='$this->formFielName' name='$this->formFielName' value='' hide='true' readonly ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." xml_data='true' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
				$spRetHtml = $spRetHtml . self::_generateHtmlForTreeUser($this->value);
				break;	
			case "textboxorder";
				$spRetHtml = $spRetHtml . $v_str_label;
				if(is_null($this->value) || $this->value==""){
					$this->value = Efy_Library::_getNextValue("T_EFYLIB_LIST","C_ORDER","FK_LISTTYPE = " . $_SESSION['listtypeId']);					
					if(!is_null($this->tableName) && $this->tableName!=""){
						$this->value = Efy_Library::_getNextValue($this->tableName, $this->orderColumn, $this->whereClause);
					}
				}
				$spRetHtml = $spRetHtml . "<input type='text' name='$this->formFielName' class='normal_textbox' value='$this->value' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." $this->sDataFormatStr xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' min='$this->min' max='$this->max' maxlength='$this->maxlength' onKeyDown='change_focus(document.forms[0],this,event)'>";
				break;
			case "checkboxstatus";
				if ($this->value == "true" || $this->value == 1 || $this->value == "HOAT_DONG" || $this->value == ""){
					$v_checked = " checked ";
				}
				$spRetHtml = $spRetHtml . '<label>&nbsp;</label>';
				$spRetHtml = $spRetHtml ."<input type='checkbox' id='$this->formFielName' name='$this->formFielName' class='normal_checkbox' title='$this->tooltip' $v_checked value='1' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName'  message='$this->spMessage' onKeyDown='change_focus(document.forms[0],this,event)'>";
				$spRetHtml = $spRetHtml . "<font style='font-family:arial;font-size:13px;font-weight:normal;line-height:13px;'>" . $this->sLabel .$this->optOptionalLabel."</font>";
				break;
			case "button";
				if(is_null($this->className) || ($this->className=="")){
					$this->className = "small_button";
				}
				$spRetHtml = $spRetHtml . "&nbsp;&nbsp;<input type='button' name='$this->formFielName' class='$this->className' value='$this->sLabel' title='$this->tooltip' style='width:$this->width' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." $this->sDataFormatStr xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$this->spMessage' onClick='$this->onclickFunction'>";
				$spRetHtml = $spRetHtml . $this->note;
				break;
			case "hidden";	
				$spRetHtml = $spRetHtml . "<input type='text' style='width:0;visibility:hidden' name='$this->formFielName' value='$this->value' hide='true' xml_data='$this->xmlData' ".Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional)." store_in_child_table='".$this->storeInChildTable."' xml_tag_in_db='$this->xmlTagInDb' message='$this->spMessage'>";
				break;
			case "image";
				$v_image_type = _list_get_last($this->value,'.');// Lay phan mo rong cua file anh
				$spRetHtml = $v_str_label;
				$spRetHtml = $spRetHtml.'';
				if($this->width =='' || $this->width <=0 || is_null($this->width)) $this->width = 100;
				if (strtoupper($v_image_type) != 'SWF' ){
					$spRetHtml .= '<img src="'.$this->path.'" name="'.$this->formFielName.'" title="'.$this->tooltip.'"';
					$spRetHtml .= ' id="'.$this->formFielName.'"  width="'.$this->width.'"';
					$spRetHtml .= Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional);
					$spRetHtml .= Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList);
					$spRetHtml .= ($this->xmlTagInDb == '')?'':' xml_tag_in_db="'.$this->xmlTagInDb.'"';
					$spRetHtml .= ($this->xmlData =='')?'':' xml_data="'.$this->xmlData.'"';
					$spRetHtml .= ($this->columnName == '')?'':' column_name="'.$this->columnName.'"';
					$spRetHtml .= $this->otherAttribute .' />';
				}
				else{
					$spRetHtml .= '<object id="'.$this->formFielName.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$this->width.'">
										<param name="movie" value="'.$this->path.'">
										<param name="quality" value="high">
										<embed name="swf" src="'.$this->path.'">"
											quality="high"
											pluginspage="http://www.macromedia.com/go/getflashplayer"
											type="application/x-shockwave-flash"
											width="'.$this->width.'" >
										</embed>
									</object>';
				}
				break;
			case "media";
				$spRetHtml = "&nbsp;&nbsp;<td class='normal_label'>";
				$spRetHtml = $spRetHtml . "<OBJECT id = 'Player'
						CLASSID = 'CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6' height = ".$this->height." width = ".$this->width.">
							<PARAM Name = 'AutoStart' value = '0'>
							<PARAM Name = 'uiMode' value = 'invisible'>
					</OBJECT>";
				break;
			case "iframe";
				if(is_null($this->height) || empty($this->height) || $this->height <=0 || $this->height == ''){$this->height = _CONST_HEIGHT_OF_LIST;};
				$spRetHtml = $spRetHtml . $this->sLabel;
				$spRetHtml = $spRetHtml . '<tr><td>';
				$spRetHtml = $spRetHtml . '<IFRAME';
				$spRetHtml = $spRetHtml . ' ID="'.$this->formFielName.'"';
				$spRetHtml = $spRetHtml . ' NAME="'.$this->formFielName.'"';
				$spRetHtml = $spRetHtml . ' FRAMEBORDER=0';
				$spRetHtml = $spRetHtml . ' SCROLLING=YES';
				$spRetHtml = $spRetHtml . ' HEIGHT = '. $this->height;
				$spRetHtml = $spRetHtml . ' WIDTH = '. $this->width;
				$spRetHtml = $spRetHtml . ' ALIGN = BASELINE';
				$spRetHtml = $spRetHtml . ' MARGINHEIGHT = 0';
				$spRetHtml = $spRetHtml . ' MARGINWIDTH = 0';
				$spRetHtml = $spRetHtml . ' SRC="'.$this->url.'"> ';
				$spRetHtml = $spRetHtml . '</IFRAME>';
				$spRetHtml = $spRetHtml . '</td></tr>';
				break;
			case "editor";
				$spRetHtml = $v_str_label;
				$spRetHtml = $spRetHtml . ' <script language="javascript">';
				$spRetHtml = $spRetHtml . ' _editor_url = "'.$this->efyLibUrlPath.'js-editor/";';
				$spRetHtml = $spRetHtml . ' var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);';
				$spRetHtml = $spRetHtml . ' if (win_ie_ver >= 5.5) { ';
				$spRetHtml = $spRetHtml . "document.write('<scr' + 'ipt src=\"' +_editor_url+ 'editor.js\" language=\"JavaScript\"></scr' + 'ipt>')";
				$spRetHtml = $spRetHtml . "}else { document.write('<scr' + 'ipt>function editor_generate() { return false; }</scr' + 'ipt>'); }";
				$spRetHtml = $spRetHtml . ' </script>';
				$spRetHtml = $spRetHtml . '<textarea class="normal_textarea" name="'.$this->formFielName.'" rows="'.$this->row.'" title="'.$this->tooltip.'" style="width:'.$this->width.'"'.Efy_Publib_Xml::_generatePropertyType("optional",$this->optOptional).Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).'" xml_tag_in_db="'.$this->xmlTagInDb.'" xml_data="'.$this->xmlData.'" column_name="'.$this->columnName.'" message="'.$this->spMessage.'">'.$this->value.'</textarea>';
				$spRetHtml = $spRetHtml . ' <script language="javascript">editor_generate("'.$this->formFielName.'");</script>';
				break;
			case "channel";
				if ($this->inputData != "session"){
					//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
					$this->channelSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->channelSql);
					$arr_all_channel = Efy_DB_Connection::adodbQueryDataInNumberMode($this->channelSql,$this->cacheOption);
				}else{
					$arr_all_channel = $_SESSION[$this->sessionName];
				}
				$spRetHtml =  _genarate_tree_channel($arr_all_channel, $this->value, $this->sLabel,$this->disabledInEditMode);
				break;
			case "labelcontent";
				$spRetHtml = $v_str_label;
				if($this->phpFunction != ""){
					$spRetHtml = $spRetHtml . call_user_func($this->phpFunction,$this->value);//$this->value.$this->phpFunction;
				}
				else{
					if($this->content != "" && !is_null($this->content)){
						$spRetHtml = $spRetHtml . $this->content;
					}
					else{
						$spRetHtml = $spRetHtml . $this->value;
					}
				}
				break;
			case 'image_button':
				$spRetHtml = '<span class="normal_link" title="'.$this->title.'" align="left" id = "'.$this->xmlTagInDb.'"';
				$spRetHtml .= Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList);
				$spRetHtml .= '>';
				$spRetHtml .= '<img style="cursor:pointer" src="'.$this->path.'" border="0"';
				$spRetHtml .= $this->otherAttribute;
				$spRetHtml .= ' />&nbsp;'.$v_str_label;
				$spRetHtml .= '</span>';
				break;
			default:
				$spRetHtml = '<label style="width:100%">' . $this->sLabel.$this->optOptionalLabel . '</label>';
		}
		return $spRetHtml;
	}	
	/**
	 * Tao chuoi HTML chua thuoc tinh rang buoc du lieu cua cac doi tuong tren from
	 *-----------
	 * @param 	$spDataFormat gia tri can dinh dang
	 * @return Gia tri dinh dang
	 */
	private function _generateVerifyProperty($spDataFormat){
		switch($spDataFormat) {
			case "isemail";
				$psRetHtml = " isemail=true " ;
				break;
			case "isdate";
				$psRetHtml = " isdate=true " ;
				break;
			case "isnumeric";
				$psRetHtml = " isnumeric=true " ;
				break;
			case "isdouble";
				$psRetHtml = " isdouble=true " ;
				break;
			case "ismoney";
				$psRetHtml = " isnumeric=true onKeyUp='format_money(this,event)' ";
				break;
			case "ismoney_float";
				$psRetHtml = " isfloat=true onKeyUp='format_money(this)' ";
				break;
			default:
				$psRetHtml = "";
		}
		return $psRetHtml;
	}	
	/**
	 * Lay gia tri cua phan tu co ID:$SelectedValue tu danh sach
	 *
	 * @param + $arrList 		: Mang chua danh sach
	 * @param $ $IdColumn 		: Ten cot chua ID can so sanh
	 * @param + $NameColumn 	: Ten cot chua chua gi tri tra ve
	 * @param + $SelectedValue 	: Gia tri so sanh voi ID cua danh sach
	 * @return unknown
	 */ 
	private function _getValueFromArray($paArrList, $iIdColumn, $psNameColumn, $psSelectedValue) {
		$pValue = "";
		$count=sizeof($paArrList);
		for($rowIndex = 0;$rowIndex< $count;$rowIndex++){
			$strID = trim($paArrList[$rowIndex][$iIdColumn]);
			$DspColumn = trim($paArrList[$rowIndex][$psNameColumn]);
			if($strID == $psSelectedValue) {
				$pValue = $DspColumn;
			}
		}
		return $pValue;
	}	
	/**
	 * Chuyen doi kieu du lieu tu file XML -> Mang
	 *
	 * @param unknown_type $p_xml_string_in_file
	 * @param unknown_type $p_item_tag
	 * @return unknown
	 */	
	public function _convertXmlStringToArray($psXmlStringInFile, $psItemTag){
		$paArrListItem = array();
		$i = 0;
		$objStructRax = new RAX();
		$objStructRec = new RAX();
		$objStructRax->open($psXmlStringInFile);
		$objStructRax->record_delim = $psItemTag;
		$objStructRax->parse();
		$objStructRec = $objStructRax->readRecord();
		while ($objStructRec) {
			$pSstructRow = $objStructRec->getRow();
			$paArrListItem[$i] = $pSstructRow;
			$i++;
			$objStructRec = $objStructRax->readRecord();
		}
		return $paArrListItem;
	}	
	/**
	 * Sinh ra XAU chua thuoc tinh cua doi tuong
	 *
	 * @param $this->spType : Kieu du lieu can sinh
	 * @param $this->value : Gia tri so sanh
	 * @return Tra ve tuy chon xac dinh doi tuong co bat nhap hay khon bat nhap
	 */
	private function _generatePropertyType($pType, $pValue){
		switch($pType) {
			case "optional";
				if ($pValue=="false"){
					$psRetHtml = "";
				}else{
					$psRetHtml = " optional = true ";
				}
				break;
			case "readonly";
				if ($pValue=="false"){
					$psRetHtml = "";
				}else{
					$psRetHtml = " readonly = true ";
				}
				break;
			case "disabled";
				if ($pValue=="false"){
					$psRetHtml = " ";
				}else{
					$psRetHtml = " disabled = true ";
				}
				break;
			default:
				$psRetHtml = "";
		}
		return $psRetHtml;	
	}	
	/**
	 * Tao chuoi HTML chua ham va cac su kien tuong ung voi ham cua cac doi tuong
	 *
	 * @param $this->jsFunctionList : Danh sach ham
	 * @param $this->jsActionList  : hanhf dong
	 * @return unknown
	 */
	public function _generateEventAndFunction($psJsFunctionList, $psJsActionList){  
		$arrJsFunctionList = explode(",", $psJsFunctionList);
		$arrJsActionList =   explode(",", $psJsActionList);
		$pCountFunction =     sizeof($arrJsFunctionList);
		$pCountAction =       sizeof($arrJsActionList);
		$this->count = $pCountFunction > $pCountAction ? $pCountAction : $pCountFunction;
		$v_temp = "";
		for ($i=0;$i<$this->count;$i++){
			$v_temp = $v_temp . " $arrJsActionList[$i]='$arrJsFunctionList[$i]' ";
		}
		return $v_temp;
	}	
	/**
	 *Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
	 *
	 * @param unknown_type $p_session_name
	 * @param unknown_type $p_session_id_index
	 * @param unknown_type $session_name_index
	 * @param unknown_type $p_valuelist
	 * @return unknown
	 */
	public function _generateHtmlForMultipleCheckboxFromSession($p_session_name, $p_session_id_index,$session_name_index,$p_valuelist) { 
		$arrValue = explode(",", $p_valuelist);
		$count_value = sizeof($arrValue);
		$v_tr_name = '"tr_'.$this->formFielName.'"';
		$v_radio_name = '"rad_'.$this->formFielName.'"';
		if($this->dspDiv == 1){// = 1 thi hien di DIV
			$strHTML = $strHTML ."<DIV title='$this->tooltip' STYLE='overflow: auto; height:auto;padding-left:0px;margin:0px;'>";
			$strHTML = $strHTML . "<table class='list_table2'  width='99%' cellpadding='0' cellspacing='0'><col width='2%'><col width='48%'><col width='2%'><col width='48%'>";
		}else{		
			$strHTML = $strHTML . "<table class='list_table2'  width='99%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
		}	
		$i = 0;			
		foreach($_SESSION[$p_session_name] as $arrList) {	
			$v_item_id = $arrList["$p_session_id_index"];
			$v_item_name = $arrList[$session_name_index];
			if($this->dspDiv != 1){
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
			}else{
				if($i % 2 == 0){
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";
					}				
				}
			}	
			$v_item_checked = "";
			$v_item_display = "block";
			if ($p_valuelist != "" && $this->dspDiv != 1 && $p_valuelist != 0){ //Kiem tra xem Hieu chinh hay la them moi
				$v_item_display = "none";
			}
			for ($j=0; $j<$count_value; $j++)
			if ($arrValue[$j] == $v_item_id){
				$v_item_checked = "checked";
				$v_item_display = "block";
				break;
			}
			if($i % 2 == 0 && $this->dspDiv == 1){
				$strHTML = $strHTML . "<tr id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name'>";
			}
			if( $this->dspDiv != 1){
				$strHTML = $strHTML . "<tr id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name'>";
			}
			if ($this->viewMode && $this->readonlyInEditMode=="true"){
				;
			}else{
				$strHTML = $strHTML . "<td><input type='checkbox' nameUnit = '$v_item_name' id='$this->formFielName' name='chk_multiple_checkbox' value='$v_item_id' xml_tag_in_db_name ='$this->formFielName' $v_item_checked ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='$v_item_url_onclick' onKeyDown='change_focus(document.forms[0],this,event)'></td>";
			}
			if($this->dspDiv == 1){
				$strHTML = $strHTML . "<td>$v_item_name</td>";
			}else{
				$strHTML = $strHTML . "<td>$v_item_name</td></tr></div>";
			}	
			if($i % 2 == 1 && $this->dspDiv == 1){
				$strHTML = $strHTML . "</tr>";
			}
			$i++;
		}
		if ($p_valuelist!="" && $p_valuelist <> 0){   //Kiem tra xem Hieu chinh hay la them moi
			$v_checked_show_row_all = "";
			$v_checked_show_row_selected = "checked";
		}else{
			$v_checked_show_row_all = "checked";
			$v_checked_show_row_selected = "";
		}
		if ($this->sLabel==""){
			$this->sLabel = "&#273;&#7889;i t&#432;&#7907;ng";
		}else{
			$this->sLabel = Efy_Publib_Xml::_firstStringToLower($this->sLabel);
		}
		$strHTML = $strHTML ."</table>";
		// = 1 thi hien thi DIV
		if($this->dspDiv == 1){
			$strHTML = $strHTML . "</DIV>";
		}	
		if ($this->viewMode && $this->readonlyInEditMode=="true"){
			;
		}else{
			$strHTML = $strHTML . "<table width='100%' cellpadding='0' cellspacing='0'><colgroup width = '100%' span = '2'><col width='2%'><col width='98%'></colgroup>";
			$strHTML = $strHTML . "<tr><td class='small_radiobutton' colspan='10' align='right'>";	
			if($this->dspDiv != 1){	
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' value='1' hide='true' $v_checked_show_row_all ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_show_row_all($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms[0],this,event)'>Hi&#7875;n th&#7883; t&#7845;t c&#7843; $this->sLabel";
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' value='2' hide='true' $v_checked_show_row_selected ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_show_row_selected($v_radio_name,$v_tr_name)' onKeyDown='change_focus(document.forms[0],this,event)'>Ch&#7881; hi&#7875;n th&#7883; c&#225;c $this->sLabel &#273;&#432;&#7907;c ch&#7885;n";
			}	
			if($this->dspDiv == 1){			
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' optional='true' value='1' hide='true' ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_select_all_multiple_checkbox(document.getElementsByName(\"chk_multiple_checkbox\"),\"$this->formFielName\",this,0);' onKeyDown='change_focus(document.forms[0],this,event)'>Ch&#7885;n t&#7845;t c&#7843;";
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' optional='true' value='2' hide='true' ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_select_all_multiple_checkbox(document.getElementsByName(\"chk_multiple_checkbox\"),\"$this->formFielName\",this,1);' onKeyDown='change_focus(document.forms[0],this,event)'>B&#7887; ch&#7885;n t&#7845;t c&#7843;";
				
			}	
			$strHTML = $strHTML . "</td></tr>";
			$strHTML = $strHTML ."</table>";
		}
		$strHTML = '<div style = "width:' . $this->width . ';">' . $strHTML . '</div>';
		return $strHTML;
	}	
	/**
	 * Chuyen chu cai dau tien cua xau thanh chu thuong
	 *
	 * @param $pStr : Sau can chuyen doi
	 * @return Xau da duoc chuyen doi ky tu dau thanh chu hoa
	 */
	public function _firstStringToLower($pStr){ 
		$psTemp = substr($pStr,1,strlen($pStr));
		$psTemp = strtolower(substr($pStr,0,1)).$psTemp ;
		return $psTemp;
	}	
	/**
	 * @Idea: Hien thi man hinh danh sach cac doi tuong
	 *
	 * @param $p_xml_file : Ten file XML mo ta danh sach
	 * @param $psXmlTag : The cha mo ta cau truc hien thi danh sach
	 * @param $pArrAllItem : Mang du lieu cua danh sach
	 * @param $psColumeNameOfXmlString : 
	 * @param $pHaveMove : Co hien thi bieu tuong cho phep thay doi vi tri khong
	 * @param $NamOfColId : Ten cot luu Id cua row
	 * @param $pOnclick :
	 * VD:
	 * 	_XML_generate_list($v_xml_file, 'col', $arr_all_list, "C_XML_DATA");
	 * @return Xau HTML mo ta danh sach
	 */
	public function _xmlGenerateList($psXmlFile, $psXmlTag, $pArrAllItem, $psColumeNameOfXmlString, $NamOfColId, $pHaveMove=false, $pOnclick= false){	
		global $row_index,$v_current_style_name,$v_id_column;
		global $v_onclick_up,$v_onclick_down,$v_have_move;
		global $v_table, $v_pk_column,$v_filename_column,$content_column,$v_append_column;
		global $p_arr_item;
		global $display_option,$url_exec;
		global $pClassname,$objectId;		
		$v_current_style_name = "round_row";		
		$v_have_move = $pHaveMove;		
		//Goi class lay tham so cau hinh he thong
		//Zend_Loader::loadClass('Efy_Init_Config');
		$ojbEfyInitConfig = new Efy_Init_Config();
		$objConfiXml = new Zend_Config_Xml($psXmlFile);
		$arrXml = $objConfiXml->toArray();
		//Lay tham so cau hinh
		$this->efyImageUrlPath = $ojbEfyInitConfig->_setImageUrlPath();
		$this->efyLibUrlPath = $ojbEfyInitConfig->_setLibUrlPath();
		$this->efyWebSitePath = $ojbEfyInitConfig->_setWebSitePath();	
		//Doc file XML
		$this->xmlStringInFile = Efy_Publib_Library::_readFile($psXmlFile);	
		//Dem so phan tu cua mang
		$this->count = sizeof($pArrAllItem);
		//Bang chua cac thanh phan cua form
		$psHtmlString = '';	
		$psHtmlString = $psHtmlString . '<table class="list_table2" width="99%" cellpadding="0" cellspacing="0" border="0" align="center" id="table1">';
		$arrTable_Struct = $arrXml['list_of_object']['list_body']['col'];
		//Tao header cho bang
		foreach ($arrTable_Struct as $col){
			$this->v_label = $col["label"];
			$this->width = $col["width"];
			$psHtmlTempWidth = $psHtmlTempWidth . '<col width="'.$this->width.'">';
			$psHtmlTempLabel = $psHtmlTempLabel . "<td class='title' align='center'>".$this->v_label.'</td>';
		}
		$psHtmlString = $psHtmlString  . $psHtmlTempWidth;
		$psHtmlString = $psHtmlString  . '<tr class="header">';
		$psHtmlString = $psHtmlString  . $psHtmlTempLabel;
		$psHtmlString = $psHtmlString  . '<tr>';		
		//Day du lieu vao bang
		for($iRow = 0; $iRow < sizeof($pArrAllItem); $iRow++){
			$objectId = $pArrAllItem[$iRow][$NamOfColId];
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			$psHtmlString = $psHtmlString  . '<tr class="'.$v_current_style_name.'">';
				foreach ($arrTable_Struct as $col){
					$v_type = $col["type"];
					$this->v_align = $col["align"];
					$this->xmlData = $col["xml_data"];
					$this->inputData = $col["input_data"];
					$this->columnName = $col["column_name"];
					$this->xmlTagInDb = $col["xml_tag_in_db"];
					$this->phpFunction = $col["php_function"];
					$v_id_column = $col["id_column"];
					$v_repeat = $col["repeat"];
					$this->selectBoxOptionSql = $col["selectbox_option_sql"];
					$this->readonlyInEditMode = $col["readonly_in_edit_mode"];
					$this->disabledInEditMode = $col["disabled_in_edit_mode"];				
					$this->publicListCode = $col["public_list_code"];	
					if($this->xmlData == 'false'){
						$this->value = $pArrAllItem[$iRow][$this->columnName];
						$p_arr_item = $pArrAllItem[$iRow];
						if ($v_id_column=="true"){
							$this->value_id = $pArrAllItem[$iRow][$this->columnName];
							if(!$pOnclick){
								$this->url ="item_onclick('" . $this->value_id . "')";
							}else{
								$this->url ="";
							}
							$v_onclick_up = "btn_move_updown('".$this->value_id . "','UP')";
							$v_onclick_down = "btn_move_updown('".$this->value_id . "','DOWN')";
						}
						$psHtmlString = $psHtmlString . $this->_generateHtmlForColumn($v_type); 
					}else{
						$strxml = $pArrAllItem[$iRow][$psColumeNameOfXmlString];
						if($strxml !=''){
							$strxml = '<?xml version="1.0" encoding="UTF-8"?>' . $strxml;
							$this->value = $this->_xmlGetXmlTagValue($strxml, 'data_list', $this->xmlTagInDb);
						}else{
							$this->value = '';
						}
						$psHtmlString = $psHtmlString . $this->_generateHtmlForColumn($v_type);
					}
			}
			$psHtmlString = $psHtmlString  . '</tr>';
		}
		if(!$pOnclick){
			$psHtmlString = $psHtmlString  . Efy_Library::_addEmptyRow(sizeof($pArrAllItem),15,$v_current_style_name,sizeof($arrTable_Struct));
		}else{
			$psHtmlString = $psHtmlString  . Efy_Library::_addEmptyRow(sizeof($pArrAllItem),15,$v_current_style_name,sizeof($arrTable_Struct));
			
		}
		$psHtmlString = $psHtmlString  .'</table>';		
		return $psHtmlString;
	}
	/**
	 * Idea: Tao chuoi HTML cho cac cot cua danh sach
	 *
	 * @param $pType : Kieu du lieu can sinh chuoi html
	 * @return Chuoi html duoc sinh theo kieu tuong ung
	 */
	private function _generateHtmlForColumn($pType){			
		global $row_index,$v_id_column,$v_onclick_up,$v_onclick_down;
		global $v_have_move;
		global $v_table, $v_pk_column,$v_filename_column,$content_column,$v_append_column;
		global $p_arr_item;
		global $v_dataformat;
		global $display_option,$url_exec;		
		global $pClassname,$objectId;
		//Tao doi tuong trong class Efy_Library
		$objEfyLib = new Efy_Library();			
		switch($pType) {
			case "checkbox";
				$psRetHtml = '<td align="'.$this->v_align.'"><input type="checkbox" onclick="selectrow(this)" name="chk_item_id" '.' value="'.$this->value.'" />';
				if ($v_id_column =="true" && $v_have_move){
					if ($row_index !=0){
						$psRetHtml = $psRetHtml. '<img src="'.$this->efyImageUrlPath.'/up.gif" border="0" style="cursor:pointer;" ondbClick="'.$v_onclick_up.'">';
					}else{
						$psRetHtml = $psRetHtml. '&nbsp;&nbsp;&nbsp;';
					}
					if ($row_index != $this->count-1){
						$psRetHtml = $psRetHtml. '<img src="'.$this->efyImageUrlPath.'/down.gif" border="0" style="cursor:pointer;" ondbClick="'.$v_onclick_down.'">';
					}else{
						$psRetHtml = $psRetHtml. '&nbsp;&nbsp;&nbsp;&nbsp;';
					}
				}
				$psRetHtml  = $psRetHtml .'</td>';
				break;
			case "selectbox";			
				if ($this->inputData == "efylist"){
					$v_xml_data_in_url = Efy_Publib_Library::_readFile($this->efyListWebSitePath."listxml/output/".$this->publicListCode.".xml");
					$arr_list_item = Efy_Publib_Xml::_convertXmlStringToArray($v_xml_data_in_url,"item");
				}else{
					//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
					$this->selectBoxOptionSql = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$this->selectBoxOptionSql);
					$arr_list_item = Efy_DB_Connection::adodbQueryDataInNumberMode($this->selectBoxOptionSql,$this->cacheOption);					
				} 
				$psRetHtml = $v_str_label;
				$psRetHtml = $psRetHtml . "<td align='.$this->v_align.'><select class='normal_selectbox' name='sel_item' title='$this->tooltip' style='width:100%' ".$this->_generatePropertyType("optional",$v_optional).$this->_generatePropertyType("readonly",$this->readonlyInEditMode).$this->_generatePropertyType("disabled",$this->disabledInEditMode).Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList)." xml_tag_in_db='$this->xmlTagInDb' xml_data='$this->xmlData' column_name='$this->columnName' message='$v_message' onKeyDown='change_focus(document.forms[0],this,event)'>";
				$psRetHtml = $psRetHtml . "<option id='' value=''>--- Ch&#7885;n $this->v_label ---</option>". Efy_Library::_generateSelectOption($arr_list_item,$this->selectBoxIdColumn,$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
				$psRetHtml = $psRetHtml . "</select></td>";				
				break;
				
			case "textbox";			
				if($this->phpFunction !="" && !is_null($this->phpFunction)){
					$this->value = call_user_func($this->phpFunction,$this->value);
				}
				$psRetHtml = '<td align="'.$this->v_align.'"><input type="text" name="txt_item_id" value="'.$this->value.'" style="width:100%" '.$this->_generatePropertyType("readonly",$this->readonlyInEditMode). $this->_generatePropertyType("disabled",$this->disabledInEditMode).' maxlength="'.$this->maxlength.'"'.Efy_Publib_Xml::_generateEventAndFunction($this->jsFunctionList, $this->jsActionList).'>';
				$psRetHtml  = $psRetHtml .'</td>';
				break;
				
			case "function";
				//Load class $pClassname
				Zend_Loader::loadClass($pClassname);
				//Tao doi tuong $objClass
				$objClass = new $pClassname;				
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'.$objClass->$this->phpFunction($this->value) .'&nbsp;</td>';
				break;
				
			case "date";
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'.'&nbsp;'. $objEfyLib->_yyyymmddToDDmmyyyy($this->value).'&nbsp;</td>';
				break;
				
			case "time";
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'.'&nbsp;'. $objEfyLib->_yyyymmddToHHmm($this->value).'&nbsp;</td>';
				break;
				
			case "text";
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'.'&nbsp;'.$this->value.'&nbsp;</td>';
				break;
				
			case "identity";
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'.$this->v_inc.'&nbsp;</td>';
				break;
				
			case "money";
				$psRetHtml = '<td class="data" align="'.$this->v_align.'" onclick="set_hidden(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_list_id\'),\''.$objectId.'\')" ondblclick="'.$this->url.'">'._dataFormat($this->value).'&nbsp;</td>';
				break;						
			default:
				$psRetHtml = $this->value;
		}
		return $psRetHtml;
	}	
	// Ham Thay the cac the cac gia tri trong cau lenh Query du lieu
	//$p_sql_replace  : Chuoi can thay the
	//$p_xml_string_in_file  : chuoi XML mo ta cac tieu thuc loc
	//$p_xml_tag : The XML can lay trong chuoi XMl mo ta tieu thuc loc
	//$p_filter_xml_string  : chuoi XML gom cac the va gia tri cua tung tieu thuc loc do.
	
	function _replaceTagXmlValueInSql($p_sql_replace,$p_xml_string_in_file,$p_xml_tag,$p_filter_xml_string){
		//Tao mang luu thong tin cua cac phan tu tren form
		$objConfigXml = new Zend_Config_Xml($p_xml_string_in_file);
		$arrTags = explode("/", $p_xml_tag);
		$strcode = '$arrfilter_rows = $objConfigXml->'.$arrTags[0];
		for($i = 1; $i < sizeof($arrTags); $i++)
			$strcode .= '->'.$arrTags[$i];
		eval($strcode.'->toArray();');
		//
		$p_sql_replace = Efy_Library::_restoreXmlBadChar($p_sql_replace);		
		//thay the ma don vi cua nguoi dang nhap hien thoi vao chuoi SQL												
		$p_sql_replace = str_replace("#OWNER_CODE#",$_SESSION['OWNER_CODE'],$p_sql_replace);
		//
		$v_sql_replace_temp = $p_sql_replace;
		global $_EfyOwnerCode;			
		foreach ($arrfilter_rows as $rows){
			$v_tag_list = $rows["tag_list"];
			$arr_tag = explode(",", $v_tag_list);
			for($i=0;$i < sizeof($arr_tag); $i++){
				$v_data_format = $rows[$i]["data_format"];
				$this->xmlTagInDb = $rows[$i]["xml_tag_in_db"];
				if ($p_filter_xml_string!=""){
					$value = $this->_xmlGetXmlTagValue($p_filter_xml_string, 'data_list', $arr_tag[$i]);
					$value_input = Efy_Library::_replaceXmlBadChar($value);
					if ($v_data_format == "isdate"){
						$value_input = Efy_Publib_Library::_ddmmyyyyToYYyymmdd($value_input);
					}					
					if ($v_data_format == "isnumeric"){
						$value_input = intval($value_input);
					}
					if ($v_data_format=="ismoney"){
						$value_input = floatval($value_input);
					}
				}				
				$v_sql_replace_temp = str_replace("#".$arr_tag[$i]."#",$value_input,$v_sql_replace_temp);
				
			}
		}
		return  $v_sql_replace_temp;
	}	
	/**
	 * Dinh dang tien te, so theo dung dinh dang
	 *
	 * @param $psValue : Gia tri can dinh dang
	 * @return Tra lai gia tri da dinh dang theo kieu tuong ung
	 */
	// 
	public function _dataFormat($psValue){
		$psRetValue = strval($psValue);
		if ($psRetValue=="" || is_null($psRetValue)){
			return "";
		}
		$arrValue=explode(".",$psRetValue);
		if (isset($arrValue[1]) && $arrValue[1]*1==0){
			$psRetValue = $arrValue[0];
		}
		if (strpos($psRetValue,".")===false){
			$psRetValue = number_format($psRetValue, 0, '.', ',');
		}else{
			$psRetValue = number_format($psRetValue, 2, '.', ',');
		}
		if ($psRetValue == "0.00") $psRetValue = "0";
		return $psRetValue;
	}	
	/**
	 * Creater: HUNGVM
	 * Date: 14/01/2009
	 * editor: Phuongtt
	 * Date: 22/10/2010
	 * @Idea: Ham tao chuoix XML de ghi vao CSDL
	 *
	 * @param $psXmlTagList : danh sach cac the XML (phan cach boi _CONST_SUB_LIST_DELIMITOR)
	 * @param $psValueList :  danh sach cac gia tri tuong ung voi moi the XML (phan cach boi _CONST_SUB_LIST_DELIMITOR)
	 * @return unknown
	 */	
	public function _xmlGenerateXmlDataString ($psXmlTagList, $psValueList){
		//Tao doi tuong Efy_Library
		$objLib = new Efy_Library();
		//Tao doi tuong config
		$objConfig = new Efy_Init_Config();
		$arrConst = $objConfig->_setProjectPublicConst();
		
		$strXML = '<?xml version="1.0" encoding="UTF-8"?><root><data_list>';
		for ($i = 0;$i < $objLib->_listGetLen($psXmlTagList,$arrConst['_CONST_SUB_LIST_DELIMITOR']); $i++){
			$strXML = $strXML ."<". $objLib->_listGetAt($psXmlTagList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR']).">";
			$strXML = $strXML .trim($objLib->_restoreXmlBadChar($objLib->_listGetAt($psValueList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR'])));
			$strXML = $strXML ."</".$objLib->_listGetAt($psXmlTagList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR']).">";
		}
		$strXML = $strXML . "</data_list></root>";
		return $strXML;
	}	
	public function _xmlGenerateXmlDataString_forXML($psXmlTagList, $psValueList){
		//Tao doi tuong Efy_Library
		$objLib = new Efy_Library();
		//Tao doi tuong config
		$objConfig = new Efy_Init_Config();
		$arrConst = $objConfig->_setProjectPublicConst();
		
		$strXML = '<root><data_list>';
		for ($i = 0;$i < $objLib->_listGetLen($psXmlTagList,$arrConst['_CONST_SUB_LIST_DELIMITOR']); $i++){
			$strXML = $strXML ."<". $objLib->_listGetAt($psXmlTagList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR']).">";
			$strXML = $strXML .trim($objLib->_restoreXmlBadChar($objLib->_listGetAt($psValueList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR'])));
			$strXML = $strXML ."</".$objLib->_listGetAt($psXmlTagList,$i,$arrConst['_CONST_SUB_LIST_DELIMITOR']).">";
		}
		$strXML = $strXML . "</data_list></root>";
		return $strXML;
	}
	/**
	 * Creater: HUNGVM
	 * Date: 14/01/2009
	 * 
	 * @Idea: Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
	 *
	 * @param $arrList : Mang luu thong tin cac phan tu can hien thi
	 * @param $IdColumn : Ten cot the hien Id
	 * @param $NameColumn : Ten cot se hien thi (ten)
	 * @param $Valuelist :
	 * @return CHuoi HTML multil checkbox
	 */
public function _generateHtmlForMultipleCheckbox($arrList, $IdColumn, $NameColumn, $Valuelist, $arrPara = array()) {
		global $v_current_style_name;
		$arr_value = explode(",", $Valuelist);
		$count_item = sizeof($arrList);	
		$count_value = sizeof($arr_value);
		$v_tr_name = '"tr_'.$this->formFielName.'"';
		$v_radio_name = '"rad_'.$this->formFielName.'"';
		if (is_array($arrPara) && count($arrPara) > 0){
			$this->dspDiv = $arrPara['dspDiv'];
			$this->readonlyInEditMode = 'false';
			$this->disabledInEditMode = 'false';
		}		
		$strHTML = '';
		if($this->dspDiv == 1){// = 1 thi hien di DIV
			$strHTML = $strHTML ."<DIV title='$this->tooltip' STYLE='overflow: auto; height:150pt;padding-left:0px;margin:0px; width:100%;'>";
			$strHTML = $strHTML . "<table id = 'mytable' class='list_table2'  width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='48%'><col width='2%'><col width='48%'>";
		}else{		
			$strHTML = $strHTML . "<table id = 'mytable' class='list_table2'  width='100%' cellpadding='0' cellspacing='0'><col width='2%'><col width='98%'>";
		}	
		if ($count_item > 0){
			$i=0;
			$v_item_url_onclick = "_change_item_checked(this,\"mytable\")";
			while ($i<$count_item) {
				$v_item_id = $arrList[$i][$IdColumn];
				$v_item_name = $arrList[$i][$NameColumn];			
				if($this->dspDiv != 1){
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";
					}
				}else{
					if($i % 2 == 0){
						if ($v_current_style_name == "odd_row"){
							$v_current_style_name = "round_row";
						}else{
							$v_current_style_name = "odd_row";
						}				
					}
				}
				$v_item_checked = "";
				$v_item_display = "block";
				if ($Valuelist!=""){ //Kiem tra xem Hieu chinh hay la them moi
					//$v_item_display = "none";
				}
				for ($j=0; $j<$count_value; $j++)
				if ($arr_value[$j]==$v_item_id){
					$v_item_checked = "checked";
					$v_item_display = "block";
					break;
				}
				if($i % 2 == 0 && $this->dspDiv == 1)
					$strHTML = $strHTML . "<tr style = 'width:100%;' id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name'>";
				if( $this->dspDiv != 1)
					$strHTML = $strHTML . "<tr style = 'width:100%;' id=$v_tr_name  value='$v_item_id' checked='$v_item_checked' class='$v_current_style_name'>";
				if ($this->viewMode && $this->readonlyInEditMode=="true"){
					;
				}else{
						$strHTML = $strHTML . "<td><input id='$this->formFielName' type='checkbox' name='chk_multiple_checkbox' value='$v_item_id' xml_tag_in_db_name ='$this->formFielName' $v_item_checked ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='$v_item_url_onclick' onKeyDown='change_focus(document.forms[0],this,event)'></td>";
				}
				if($this->dspDiv == 1){
					$strHTML = $strHTML . "<td>$v_item_name</td>";
				}else{
					$strHTML = $strHTML . "<td>$v_item_name</td></tr>";
				}	
				if($i % 2 == 1 && $this->dspDiv == 1){
					$strHTML = $strHTML . "</tr>";
				}
				$i++;
			}
		}
		if ($Valuelist!=""){   //Kiem tra xem Hieu chinh hay la them moi
			$v_checked_show_row_all = "";
			$v_checked_show_row_selected = "checked";
		}else{
			$v_checked_show_row_all = "checked";
			$v_checked_show_row_selected = "";
		}
		if ($this->v_label==""){
			$this->v_label = "&#273;&#7889;i t&#432;&#7907;ng";
		}else{
			$this->v_label = self::_firstStringToLower($this->v_label);
		}
		$strHTML = $strHTML ."</table>";
		if($this->dspDiv == 1)
			$strHTML = $strHTML . "</DIV>";
		if ($this->viewMode && $this->readonlyInEditMode=="true"){
			;
		}else{
			$strHTML = $strHTML . "<table width='100%' cellpadding='0' cellspacing='0'><colgroup width = '100%' span = '2'><col width='2%'><col width='98%'></colgroup>";
			$strHTML = $strHTML . "<tr><td class='small_radiobutton' colspan='10' align='right'>";	
			if($this->dspDiv != 1){	
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' value='1' hide='true' $v_checked_show_row_all ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_show_row_all(\"mytable\")' onKeyDown='change_focus(document.forms[0],this,event)'>Hi&#7875;n th&#7883; t&#7845;t c&#7843; $this->sLabel";
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' value='2' hide='true' $v_checked_show_row_selected ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_show_row_selected(\"mytable\")' onKeyDown='change_focus(document.forms[0],this,event)'>Ch&#7881; hi&#7875;n th&#7883; c&#225;c $this->sLabel &#273;&#432;&#7907;c ch&#7885;n";
			}	
			if($this->dspDiv == 1){			
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' optional='true' value='1' hide='true' ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_select_all_multiple_checkbox(document.getElementsByName(\"chk_multiple_checkbox\"),\"$this->formFielName\",this,0);' onKeyDown='change_focus(document.forms[0],this,event)'>Ch&#7885;n t&#7845;t c&#7843;";
				$strHTML = $strHTML . "<input type='radio' name='rad_$this->formFielName' optional='true' value='2' hide='true' ".Efy_Publib_Xml::_generatePropertyType("readonly",$this->readonlyInEditMode).Efy_Publib_Xml::_generatePropertyType("disabled",$this->disabledInEditMode)." onClick='_select_all_multiple_checkbox(document.getElementsByName(\"chk_multiple_checkbox\"),\"$this->formFielName\",this,1);' onKeyDown='change_focus(document.forms[0],this,event)'>B&#7887; ch&#7885;n t&#7845;t c&#7843;";
				
			}	
			$strHTML = $strHTML . "</td></tr>";
			$strHTML = $strHTML ."</table>";
		}
		$strHTML = '<div style = "width:' . $this->width . ';">' . $strHTML . '</div>';
		return $strHTML;
	}
/**
	 * @author : Thainv
	 * @since : 11/04/2009
	 * 
	 * @see : Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
	 *
	 * @param :
	 * 			$p_xml_file: Ten file xml
	 * 			$p_xml_tag: The in ra
	 * 			$p_arr_all_item: Mang chua ket qua cua cau lenh select
	 * 			$p_colume_name_of_xml_string: Cot chua noi dung bao cao
	 * 
	 * @return CHuoi HTML 
	 */

	public  function _xmlGenerateReportBody($p_xml_file,$p_xml_tag, $p_arr_all_item, $p_colume_name_of_xml_string){
		global $row_index,$v_current_style_name,$v_id_column;
		global $pClassname;		
		$this->xmlStringInFile = Efy_Publib_Library::_readFile($p_xml_file);
		$this->count = sizeof($p_arr_all_item);	
		//Bang chua cac phan than cua bao cao
		$v_column = 0;
		$v_html_temp_width = '';
		$v_html_temp_label = '';
		$v_current_style_name = "round_row";
		$v_HTML_string = '';
		//Cac tham so de nhom du lieu
		$this->groupBy = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_sql","group_by");
		$v_group_name = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_sql","group_name");
		$this->xmlDataCompare = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_sql","xml_data");
		$v_calculate_total = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_sql","calculate_total");
		$v_calculate_group = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_sql","calculate_group");
	
		//Lay ten file HTML dinh dang tieu de cot bao cao
		$v_report_label_file = trim(self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","table_header_file"));
		if ($v_report_label_file != ""){
			//Tieu de cot doc tu file HTML vao
			$v_report_label_file = Efy_Init_Config::_setUrlTempHeaderReport().$v_report_label_file;
			$v_html_label_content = Efy_Publib_Library::_readFile($v_report_label_file);			
			$v_HTML_string = $v_HTML_string . $v_html_label_content;
		}	
		$table_struct_rax = new RAX();
		$table_struct_rec = new RAX();
		$table_struct_rax->open($this->xmlStringInFile);
		$table_struct_rax->record_delim = $p_xml_tag;
		$table_struct_rax->parse();
		$table_struct_rec = $table_struct_rax->readRecord();
		while ($table_struct_rec) {
			$table_struct_row = $table_struct_rec->getRow();
			$v_type =  $table_struct_row["type"];
			$this->v_label = $table_struct_row["label"];
			$this->width = $table_struct_row["width"];
			$this->v_align = $table_struct_row["align"];
			//Lay danh sach do rong cac cot cua bang
			$v_html_temp_width = $v_html_temp_width  . '<col width="'.$this->width .'">';
			//Lay danh sach cac tieu de cua cot
			$v_html_temp_label = $v_html_temp_label . '<td class="header" align="'.$this->v_align.'">'.$this->v_label.'</td>';
			$arr_type[$v_column] = $v_type;
			$arr_align[$v_column] = $this->v_align;
			$table_struct_rec = $table_struct_rax->readRecord();
			$v_column ++;
		}
		$width_col = 100/$v_column;
		$v_html_col_list = $v_html_col_list .str_repeat("<col width:'$width_col%'>",$v_column);
	
		if($v_report_label_file == ""){
			$v_HTML_string = $v_HTML_string  . '<table class="report_table" style="width:100%" border="0" cellpadding="0" cellspacing="0">';
			$v_HTML_string = $v_HTML_string  . $v_html_temp_width;
			//Lay tieu de cot tu file XML
			$v_HTML_string = $v_HTML_string  . '<tr>';
			$v_HTML_string = $v_HTML_string  . $v_html_temp_label;
			$v_HTML_string = $v_HTML_string  . '</tr>';
		}
		//Khoi tao thu tu cua danh sach va nhom	
		$group_index=1;
		$this->v_inc = 1;
		if ($this->count >0){
			//Vong lap hien thi danh sach cac ho so
			$v_old_row = $p_arr_all_item[0];
			for ($i=0; $i< $v_column; $i++){
				$arr_calculate[$i] = 0;
			}
			for($row_index = 0;$row_index <$this->count ;$row_index++){
				$v_recordset = $p_arr_all_item[$row_index];
				$v_received_record_xml_data = $p_arr_all_item[$row_index][$p_colume_name_of_xml_string];
				$v_recordtype_code = $p_arr_all_item[$row_index]['FK_RECORDTYPE'];
				$v_group_name_label = $p_arr_all_item[$row_index][$v_group_name];
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				//Bat dau 1 dong
				$table_struct_rax = new RAX();
				$table_struct_rec = new RAX();
				$table_struct_rax->open($this->xmlStringInFile);
				$table_struct_rax->record_delim = $p_xml_tag;
				$table_struct_rax->parse();
				$table_struct_rec = $table_struct_rax->readRecord();
				$v_col_index = 0;
				$v_HTML_string_row = '';
				//In tieu de cua nhom
				if (trim($this->groupBy)!="" && $row_index == 0){
					$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
					$v_HTML_string = $v_HTML_string  .'<td class="data"><B>'.$group_index.'</B></td>';
					$v_HTML_string = $v_HTML_string  .'<td class="data" colspan="'.($v_column-1).'"><B>'.$p_arr_all_item[$row_index][$v_group_name].'</B></td>';
					$v_HTML_string = $v_HTML_string  .'</tr>';
				}
				while ($table_struct_rec) {
					$table_struct_row = $table_struct_rec->getRow();
					$v_type = $table_struct_row["type"];
					$this->width = $table_struct_row["width"];
					$this->v_align = $table_struct_row["align"];
					$this->xmlData = $table_struct_row["xml_data"];
					$v_calculate = $table_struct_row["calculate"];
					$v_compare_value = $table_struct_row["compare_value"];
					$this->columnName = $table_struct_row["column_name"];
					$this->xmlTagInDb_list = $table_struct_row["xml_tag_in_db_list"];
					
					//Lay ten class
					$pClassname = $table_struct_row["class_name"];
					
					//Lay the xml chua noi dung can hien thi tu danh sach tuong ung voi ma
					if (Efy_Library::_listGetLen($this->xmlTagInDb_list,',')>1){
						$this->xmlTagInDb = get_value_from_two_list($v_recordtype_code,$table_struct_row["recordtype_code_list"],$table_struct_row["xml_tag_in_db_list"]);
					}else{
						$this->xmlTagInDb = $table_struct_row["xml_tag_in_db_list"];
					}
					$this->selectBoxOptionSql = $table_struct_row["selectbox_option_sql"];
					$this->phpFunction = $table_struct_row["php_function"];
					$arr_xml_tag_in_db = explode(".",$this->xmlTagInDb);
					if (sizeof($arr_xml_tag_in_db)>1){
						$v_received_record_xml_data = $p_arr_all_item[$row_index][$arr_xml_tag_in_db[0]];
						$this->xmlTagInDb = $arr_xml_tag_in_db[1];
					}else{
						$this->xmlTagInDb = $table_struct_row["xml_tag_in_db_list"];
					}
					if ($this->xmlData=="true" && $v_received_record_xml_data!="" && !is_null($v_received_record_xml_data)){
						$column_rax = new RAX();
						$column_rec = new RAX();
						$column_rax->open($v_received_record_xml_data);
						$column_rax->record_delim = 'data_list';
						$column_rax->parse();
						$column_rec = $column_rax->readRecord();
						$column_row = $column_rec->getRow();
						$this->value = _restore_XML_bad_char($column_row[$this->xmlTagInDb]);
					}else{
						$this->value = $p_arr_all_item[$row_index][$this->columnName];
					}
					if ($v_type=="money"){
						$this->value = str_replace(",","",$this->value);
					}
					//In tu dong cua bao cao
					$v_HTML_string_row = $v_HTML_string_row .self::_generateHtmlForColumn($v_type);
					//Neu ma tinh so luong
					if ($v_calculate=="count"){
						if ((trim($v_compare_value)!="")&&(_list_have_element(trim($v_compare_value), trim($this->value),","))){
							$arr_calculate[$v_col_index] = $arr_calculate[$v_col_index] + 1;
							$arr_total_calculate[$v_col_index] = $arr_total_calculate[$v_col_index] + 1;
						}
					}elseif ($v_calculate=="sum"){//Neu tinh tong cac gia tri
						$arr_calculate[$v_col_index] = $arr_calculate[$v_col_index] + floatval($this->value);
						$arr_total_calculate[$v_col_index] = $arr_total_calculate[$v_col_index] + floatval($this->value);
					}else{
						$arr_calculate[$v_col_index] = "";
						$arr_total_calculate[$v_col_index] = "";
					}
					$v_col_index ++;
					$table_struct_rec = $table_struct_rax->readRecord();
				}//End while
				$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
				$v_HTML_string = $v_HTML_string  .$v_HTML_string_row;
				$v_HTML_string = $v_HTML_string  .'</tr>';
				$this->v_inc ++;
				if (trim($this->groupBy)!=""){
					$v_current_row = $p_arr_all_item[$row_index+1];
					if ((self::_compareTwoValue($v_old_row,$v_current_row)!=0)){
						//Khoi tao lai thu tu cua danh sach
						$this->v_inc = 1;
						$group_index++;
						$v_html_temp = "";
						//Hien thi phan tinh toan theo nhom
						for ($i=0;$i < sizeof($arr_calculate);$i++){
							if ($arr_calculate[$i]>=0 && $v_calculate_group=="true"){
								$v_type = $arr_type[$i];
								$this->v_align = $arr_align[$i];
								$this->value = $arr_calculate[$i];
								$arr_calculate[$i] = 0;
								if ($v_type=="money"){
									$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">'.onegate_data_format($this->value).'&nbsp;</td>';
								}elseif($v_type=="identity"){
									$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">&nbsp;</td>';
								}elseif($i==1){
									$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'"><B>C&#7897;ng:&nbsp;</B></td>';
								}else{
									$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">'.$this->value.'&nbsp;</td>';
								}
							}
						}
						$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
						$v_HTML_string = $v_HTML_string  .$v_html_temp;
						$v_HTML_string = $v_HTML_string  .'</tr>';
						//In tieu de cua nhom
						if (trim($this->groupBy)!="" && $row_index<$this->count-1){
							$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
							$v_HTML_string = $v_HTML_string  .'<td class="data"><B>'.$group_index.'</B></td>';
							$v_HTML_string = $v_HTML_string  .'<td class="data" colspan="'.($v_column-1).'"><B>'.$p_arr_all_item[$row_index+1][$v_group_name].'</B></td>';
							$v_HTML_string = $v_HTML_string  .'</tr>';
						}	
					}//End if
					$v_old_row = $v_current_row;
				}
				//Ket thuc mot dong
			}//End for
		}//End if
		//Hien thi phan tinh toan tong
		if ($v_calculate_total=="true"){
			$v_html_temp = "";
			for ($i=0;$i < sizeof($arr_total_calculate);$i++){
				//if ($arr_total_calculate[$i]>=0){
					$v_type = $arr_type[$i];
					$this->v_align = $arr_align[$i];
					$this->value = $arr_total_calculate[$i];
					if ($v_type=="money"){
						$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">'._data_format($this->value).'&nbsp;</td>';
					}elseif($v_type=="identity"){
						$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">&nbsp;</td>';
					}elseif($i==1){
						$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'"><B>T&#7893;ng c&#7897;ng&nbsp;</B></td>';
					}else{
						$v_html_temp = $v_html_temp .'<td class="data" align="'.$this->v_align.'">'.$this->value.'&nbsp;</td>';
					}
				//}
			}
			$v_HTML_string = $v_HTML_string  .'<tr class="'.$v_current_style_name.'" >';
			$v_HTML_string = $v_HTML_string  .$v_html_temp;
			$v_HTML_string = $v_HTML_string  .'</tr>';
		}
		if ($v_current_style_name == "odd_row"){
			$v_next_style_name = "round_row";
		}else{
			$v_next_style_name = "odd_row";
		}
		//Ket thuc ban bang cua bao cao
		$v_HTML_string = $v_HTML_string  .'</table>';
		return $v_HTML_string;
	}	
/**
	 * @author : Thainv
	 * @since : 11/04/2009
	 * 
	 * @see : Hien thi phan dau Header cua bao cao
	 * @param :
	 * 			$p_xml_file: Ten file xml
	 * 			$p_xml_tag: The in ra
	 * 			$p_xml_tag_col: col	
	 * 			$p_filter_xml_string: Gia tri cua ket hop giua tag va value xml
	 * 			
	 * 
	 * @return CHuoi HTML 
	 */
function _xmlGenerateReportHeader($p_xml_file,$p_xml_tag_row,$p_xml_tag_col, $p_filter_xml_string){
	global $v_type, $v_dataformat;
	global $i;
	$this->xmlStringInFile =Efy_Publib_Library::_readFile($p_xml_file);
	$v_current_date = "ng&#224;y ". date("d"). " th&#225;ng " . date("m")." n&#259;m " . date("Y");
	$v_column = 0;
	$table_struct_rax = new RAX(); 
	$table_struct_rec = new RAX(); 
	$table_struct_rax->open($this->xmlStringInFile);
	$table_struct_rax->record_delim = $p_xml_tag_col;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord(); 
	while ($table_struct_rec) { 
		$table_struct_row = $table_struct_rec->getRow();
		$table_struct_rec = $table_struct_rax->readRecord();
		$v_column ++;
	}
	$width_col = 100/$v_column;
	$v_html_col_list = '';
	$v_html_col_list = $v_html_col_list . str_repeat("<col width:'$width_col%'>",$v_column);
	$v_HTML_string = '';
	$v_report_unit =self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","report_unit");
	//$v_report_unit_father = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","report_unit_father");
	$v_report_unit_father = Efy_Init_Config::_setOnerName();
	$v_report_unit_child = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","report_unit_child");
	//$v_report_date = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","report_date");
	$v_report_date = Efy_Init_Config::_setOnerReportName();
	$v_large_title = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","large_title");
	$v_report_unit = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_header","report_unit");
	
	if (isset($$v_report_date)){
		$v_report_date = $$v_report_date;
	}
	if (isset($$v_report_unit_father)){
		$v_report_unit_father = $$v_report_unit_father;
	}
	if (isset($$v_report_unit_child)){
		$v_report_unit_child = $$v_report_unit_child;
	}
	$v_HTML_string = $v_HTML_string  .'<table width="99%" border="0" cellpadding="0" cellspacing="0">';
	$v_HTML_string = $v_HTML_string  . $v_html_col_list;
	$v_HTML_string = $v_HTML_string  .'<tr valign="top"><td align="center" class="report_unit_name" colspan="'.floor($v_column/2).'">'.$v_report_unit_father."<br>".$v_report_unit_child.'</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="freedom_republic" colspan="'.($v_column-floor($v_column/2)).'">CÃ¡Â»ËœNG HÃƒâ€™A XÃƒÆ’ HÃ¡Â»ËœI CHÃ¡Â»Â¦ NGHÃ„Â¨A VIÃ¡Â»â€ T NAM <br>Ã„ï¿½Ã¡Â»â„¢c lÃ¡ÂºÂ­p - TÃ¡Â»Â± do - HÃ¡ÂºÂ¡nh phÃƒÂºc</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'"><BR>==============***==============</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="right" class="date" colspan="'.$v_column.'"><i>'.$v_report_date.$v_current_date.'</i></td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="title" colspan="'.$v_column.'">'.$v_large_title.'</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="sub_title" colspan="'.$v_column.'">'.$v_small_title.'</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr><tr><td align="center" class="normal_label" colspan="'.$v_column.'">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr></table>';
	//Het phan tieu de cua bao cao
	//Phan chua cac tieu thuc loc bao cao
	$table_struct_rax = new RAX(); 
	$table_struct_rec = new RAX(); 
	$table_struct_rax->open($this->xmlStringInFile);
	$table_struct_rax->record_delim = $p_xml_tag_row;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord(); 
	while ($table_struct_rec) { 
		$table_struct_row = $table_struct_rec->getRow();
		$v_tag_list = $table_struct_row["tag_list"];
		$this->rowId = $table_struct_row["rowId"];
		$arr_tag = explode(",", $v_tag_list);
		//Bang chua mot dong cua form
		$v_HTML_string = $v_HTML_string . "<table width='99%'  border='0' cellspacing='0' cellpadding='0'>";
		$v_html_table = "";
		$v_html_tag = "";									
		for($i=0;$i < sizeof($arr_tag);$i++){
			$formfield_rax = new RAX(); 
			$formfield_rec = new RAX(); 
			$formfield_rax->open($this->xmlStringInFile);
			$formfield_rax->record_delim = $arr_tag[$i];
			$formfield_rax->parse();
			$formfield_rec = $formfield_rax->readRecord(); 
			$formfield_row = $formfield_rec->getRow(); 
			$this->v_label = $formfield_row["label"];
			$v_type = $formfield_row["type"];
			$v_dataformat = $formfield_row["data_format"];
			$this->width = $formfield_row["width"];
			$this->row = $formfield_row["row"];
			$this->max = $formfield_row["max"];
			$this->min = $formfield_row["min"];
			$this->maxlength = $formfield_row["max_length"];
			$this->note = $formfield_row["note"];
			$v_message = $formfield_row["message"];
			$v_optional = $formfield_row["optional"];
			$this->xmlTagInDb = $formfield_row["xml_tag_in_db"];
			$this->jsFunctionList = $formfield_row["js_function_list"];
			$this->jsActionList = $formfield_row["js_action_list"];
			$this->readonlyInEditMode = $formfield_row["readonly_in_edit_mode"];
			$this->disabledInEditMode = $formfield_row["disabled_in_edit_mode"];
			//lay du lieu tu session
			$this->inputData = $formfield_row["input_data"];
			$this->sessionName = $formfield_row["session_name"];
			$this->sessionIdIndex = $formfield_row["session_id_index"];
			$this->sessionNameIndex = $formfield_row["session_name_index"];
			$this->sessionValueIndex = $formfield_row["session_value_index"];
			if ($p_filter_xml_string!=""){
				$column_rax = new RAX(); 
				$column_rec = new RAX();
				$column_rax->open($p_filter_xml_string);
				$column_rax->record_delim = 'data_list';
				$column_rax->parse();
				$column_rec = $column_rax->readRecord(); 
				$column_row = $column_rec->getRow();
				$this->value =Efy_Publib_Library::_restoreXmlBadChar($column_row[$this->xmlTagInDb]); 
			}
			if ($v_type=="selectbox"){
				$this->selectBoxOptionSql = $formfield_row["selectbox_option_sql"];
				$this->selectBoxIdColumn = $formfield_row["selectbox_option_id_column"];
				$this->selectBoxNameColumn = $formfield_row["selectbox_option_name_column"];
			}
			if ($v_type=="checkboxmultiple"){
				$this->checkBoxMultipleSql = $formfield_row["checkbox_multiple_sql"];
				$this->checkBoxMultipleIdColumn = $formfield_row["checkbox_multiple_id_column"];
				$this->checkBoxMultipleNameColumn = $formfield_row["checkbox_multiple_name_column"];
			}
			$v_html_table = $v_html_table . "<col width='$v_first_col_width'>" . "<col width='$v_second_col_width'>";		
			$v_html_tag = $v_html_tag .self::_generateHtmlOutput();
		}
		$v_HTML_string = $v_HTML_string .  $v_html_table . "<tr><td class='normal_label' align='center' colspan='$v_column'>" . $v_html_tag."</td></tr>";
		$v_HTML_string = $v_HTML_string . "</table>";
		$table_struct_rec = $table_struct_rax->readRecord();
	}
	$v_HTML_string = $v_HTML_string . "<table width='99%'  border='0' cellspacing='0' cellpadding='0'>";
	$v_HTML_string = $v_HTML_string . "<tr><td colspan='$v_column'>&nbsp;</td></tr>";
	$v_HTML_string = $v_HTML_string . "</table>";
	return $v_HTML_string;
}
/**
	 * @author : Thainv
	 * @since : 11/04/2009
	 * 
	 * @see : Hien thi phan cuoi Footer cua bao cao
	 * @param :
	 * 			$p_xml_file: Ten file xml
	 * 			$p_xml_tag: The in ra
	 * 			
	 * @return CHuoi HTML 
	 */
function _xmlGenerateReportFooter($p_xml_file,$p_xml_tag){
	$this->xmlStringInFile = Efy_Publib_Library::_readFile($p_xml_file);
	$v_column = 0;
	$table_struct_rax = new RAX();
	$table_struct_rec = new RAX();
	$table_struct_rax->open($this->xmlStringInFile);
	$table_struct_rax->record_delim = $p_xml_tag;
	$table_struct_rax->parse();
	$table_struct_rec = $table_struct_rax->readRecord();
	while ($table_struct_rec) {
		$table_struct_row = $table_struct_rec->getRow();
		$table_struct_rec = $table_struct_rax->readRecord();
		$v_column ++;
	}
	$width_col = 100/$v_column;
	$v_html_col_list = '';
	$v_html_col_list = $v_html_col_list .str_repeat("<col width:'$width_col%'>",$v_column);

	$v_HTML_string = '';
	$v_report_creator =self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_footer","report_creator");
	$v_report_approver = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_footer","report_approver");
	$v_report_signer = self::_xmlGetXmlTagValue($this->xmlStringInFile,"report_footer","report_signer");
	$v_HTML_string = $v_HTML_string  .'<table width="99%" border="0" cellspacing="0" cellpadding="0">';
	$v_HTML_string = $v_HTML_string  . $v_html_col_list;
	$v_HTML_string = $v_HTML_string  .'<tr><td class="normal_label" colspan="'.$v_column.'">&nbsp;</td></tr><tr>';
	$v_HTML_string = $v_HTML_string  .'<tr><td class="normal_label" colspan="'.$v_column.'">&nbsp;</td></tr><tr>';
	$v_HTML_string = $v_HTML_string  .'<td class="normal_label">&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="creator">'.$v_report_creator.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="approver">'.$v_report_approver.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'<td align="center" class="signer">'.$v_report_signer.'&nbsp;</td>';
	$v_HTML_string = $v_HTML_string  .'</tr></table>';
	return $v_HTML_string;
}
	/**
	 * @author : Thainv
	 * @since : 13/04/2009
	 * 
	 * @see : Tao chuoi HTML 
	 *
	 * @param :
	 * 			
	 * 
	 * @return CHuoi HTML 
	 */
	function _generateHtmlOutput(){
	//global $_EFY_OWNER_CODE;
	global $v_type, $v_dataformat;
	global $i ;
	$this->selectBoxOptionSql = str_replace('#OWNER_CODE#' ,$_SESSION['OWNER_CODE'],$this->selectBoxOptionSql);
	$v_optional_label = "";
	$v_str_label = "&nbsp;&nbsp;".$this->v_label."&nbsp;&nbsp;";
	switch($v_type) {
		case "label";
			$v_ret_html = $this->v_label.$v_optional_label."&nbsp;&nbsp;";
			break;
		case "textbox";
			$v_ret_html = $v_str_label;
			$v_ret_html = $v_ret_html . $this->value;
			break;
		case "selectbox";
			$v_ret_html = $v_str_label;
			if ($this->inputData == "session"){
				$j = 0;
				$arr_list_item = array();
				if (isset($_SESSION[$this->sessionName])){
					foreach($_SESSION[$this->sessionName] as $arr_item) {
						$arr_list_item[$j] = $arr_item;
						$j++;
					}
				}
				$v_ret_html = $v_ret_html ._get_value_from_array($arr_list_item,$this->sessionIdIndex,$this->sessionNameIndex,$this->value);
			}elseif ($this->inputData == "isalist"){
				$v_xml_data_in_url = Efy_Publib_Library::_readFile($_EFY_LIST_WEB_SITE_PATH."listxml/output/".$this->publicListCode.".xml");
				$v_ret_html = $v_ret_html ._get_value_from_array(_convert_xml_string_to_array($v_xml_data_in_url,"item"),$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
			}else{
				$v_ret_html = $v_ret_html .self::_getValueFromArray(Efy_DB_Connection::adodbQueryDataInNumberMode($this->selectBoxOptionSql,$this->cacheOption),$this->selectBoxIdColumn,$this->selectBoxNameColumn,$this->value);
			}
			break;
		default:
			$v_ret_html = "";
	}
	return $v_ret_html;
	}
	/**
	 * @author : Thainv
	 * @since : 16/04/2009
	 * 
	 * @see : Tao chuoi HTML dinh nghia danh sach cac check box
	 *
	 * @param :
	 * 			
	 * 
	 * @return CHuoi HTML 
	 */
	function _generateHtmlForTreeUser($p_valuelist) {
		Zend_Loader::loadClass('Efy_Library');		
		$arr_all_cooperator = explode(",", $p_valuelist);
		$v_cooperator_count = sizeof($arr_all_cooperator);
		if (trim($p_valuelist)!="" && trim($p_valuelist) != "0"){
			$strHTML = '<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">';
			$strHTML = $strHTML .'<col width="6%"><col width="27%"><col width="29%"><col width="38%">';
			$strHTML = $strHTML .'<tr  class="header">';
			$strHTML = $strHTML .'<td align="center" class="title" width="6%">STT</td>';
			$strHTML = $strHTML .'<td align="center" class="title" width="25%">H&#7885; t&#234;n</td>';
			$strHTML = $strHTML .'<td align="center" class="title" width="27%">Ch&#7913;c v&#7909</td>';
			$strHTML = $strHTML .'<td align="center" class="title" width="36%">Ph&#242;ng ban</td>';
			$strHTML = $strHTML .'</tr>';
			$strHTML = $strHTML .'</table>';
			//$strHTML = $strHTML ."<DIV title='$this->tooltip' STYLE='overflow: auto; height:100pt;padding-left:0px;margin:0px'>";
			$strHTML = $strHTML .'<table class="list_table2" width="100%" cellpadding="0" cellspacing="0" >';
			$strHTML = $strHTML .'<col width="6%"><col width="27%"><col width="29%"><col width="38%">';
				for($j = 0; $j < $v_cooperator_count; $j++){
					$v_cooperator_id = $arr_all_cooperator[$j];
					$v_cooperator_name = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff'],$v_cooperator_id, 'name');
					$v_cooperator_position_name = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff'],$v_cooperator_id, 'position_name');					
					$v_cooperator_unit_id = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff'],$v_cooperator_id, 'unit_id');
					$v_cooperator_unit_name = Efy_Library::_getItemAttrById($_SESSION['arr_all_unit'],$v_cooperator_unit_id, 'name');
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";
					}
					$strHTML = $strHTML .'<tr class="'.$v_current_style_name.'">';
					$strHTML = $strHTML .'<td align="center">'.($j+1).'</td>';
					$strHTML = $strHTML .'<td align="left">'.$v_cooperator_name.'&nbsp;</td>';
					$strHTML = $strHTML .'<td align="left">'.$v_cooperator_position_name.'&nbsp;</td>';
					$strHTML = $strHTML .'<td align="left">'.$v_cooperator_unit_name.'&nbsp;</td>';
					$strHTML = $strHTML .'</tr>';
				}
			$strHTML = $strHTML .'</table>';
			//$strHTML = $strHTML .'</DIV>';
		}
		if (!($this->viewMode && $this->readonlyInEditMode=="true")){
			//$strHTML = $strHTML .'<DIV STYLE="overflow: auto; height:100pt; padding-left:0px;margin:0px">';
			$strHTML = $strHTML . "<table class='list_table2'  width='100%' cellpadding='0' cellspacing='0'>";
			$strHTML = $strHTML . '<input type="hidden" id = "hdn_item_id" name="hdn_item_id" value="">';
			$v_item_unit_id =Efy_Library::_getRootUnitId() ;
			$arr_unit = Efy_Library::_getArrAllUnit();
			$arr_staff =Efy_Library::_getArrChildStaff($arr_unit);
			$arr_list = Efy_Library::_attachTwoArray($arr_unit,$arr_staff, 5);
			//var_dump($arr_list);
			$v_current_id = 0;
			
			$xml_str = Efy_Library::_builtXmlTree($arr_list,$v_current_id,'true','home.jpg','home.jpg','user.jpg','false',$p_valuelist,$this->formFielName);
			
			$xml = new DOMDocument;
			$xml->loadXML($xml_str);
				
			$xsl = new DOMDocument;
			
			$xsl->load("public/treeview.xsl");
			
			// Configure the transformer
			$proc = new XSLTProcessor(); 
			
			$proc->importStylesheet($xsl);// attach the xsl rules
			
			$ret = $proc->transformToXML($xml);
			$strHTML = $strHTML . "<tr><td>".$ret."</td></tr>";					
		
			$strHTML = $strHTML ."</table>";
			//$strHTML = $strHTML . "</DIV>";
		}
		return $strHTML;
	}			
	/**
	 * Enter description here...
	 * Idea : Ham so sanh gia tri cua xau  DUNG CHO SAP XEP DU LIEU
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return unknown
	 */
	public function _compareTwoValue($a, $b){
		if ($this->xmlDataCompare == "true"){
			$v_xml_string_a = $a['C_RECEIVED_RECORD_XML_DATA'];
			$v_xml_string_b = $b['C_RECEIVED_RECORD_XML_DATA'];
			//Lay gia tri tu mang a
			$column_rax = new RAX();
			$column_rec = new RAX();
			$column_rax->open($v_xml_string_a);
			$column_rax->record_delim = 'data_list';
			$column_rax->parse();
			$column_rec = $column_rax->readRecord();
			$column_row = $column_rec->getRow();
			$value_a = _restore_XML_bad_char($column_row[$this->groupBy]);
			//Lay gia tri tu mang b
			$column_rax = new RAX();
			$column_rec = new RAX();
			$column_rax->open($v_xml_string_b);
			$column_rax->record_delim = 'data_list';
			$column_rax->parse();
			$column_rec = $column_rax->readRecord();
			$column_row = $column_rec->getRow();
			$value_b = _restore_XML_bad_char($column_row[$this->groupBy]);
			return strcmp($value_a, $value_b);
		}else{
			return strcmp($a[$this->groupBy],$b[$this->groupBy]);
		}
	}	
}
