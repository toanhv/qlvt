<?php 

/**
 * Nguoi tao: phongtd 
 * Noi dung: Tao lop Efy_Init_Config dung de config cau hinh lien quan den ho thong
 */

class Efy_Init_Config{	
	
	/**
	 * Khoi tao bien xac dinh duong dan website
	 *	
	 */	
	public function _setWebSitePath(){		
		return "/qlts-vega/";
	}
	
	/**
	 * Xac dinh duong dan URL toi thu muc chua ISA-LIB
	 *
	 * @return unknown
	 */
	public function _setLibUrlPath(){
		return self::_setWebSitePath() . "public/";
	}

	/**
	 * Xac dinh duong dan URL toi thu muc chua anh dung chung
	 *
	 * @return unknown
	 */
	public function _setImageUrlPath(){
		return self::_setLibUrlPath() . "images/";
	}

	/**
	 * Xac dinh duong dan URL den cac file trong thu muc dinh kem
	 *
	 * @return unknown
	 */
	public function _setAttachFileUrlPath(){
		return self::_setLibUrlPath() . "attach-file/";
	}
	
	/**
	 * Idea: Lay duong dan luu file XML
	 *
	 * @param $piLevel : Cap thu muc chua file XML
	 */
	public function _setXmlFileUrlPath($piLevel){
		switch($piLevel){
			//Duong dan toi thu muc chua cac file XML tinh tu thu muc goc
		 	 case 0;
				return "xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 1	
			 case 1;
				return "./xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 2	
			 case 2;
				return "../../xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 3	
			 case 3;
				return "../../../xml/";
				break;
			default: 
				return "";
				break;
		}	
	}
	
	/**
	 * Idea: Tao cac hang so dung chung cho viec xu ly JS
	 *
	 * @return Chuoi mo ta JS
	 */
	public function _setJavaScriptPublicVariable(){
		$arrConst = $this->_setProjectPublicConst();		
		$psHtml = "<script>\n";
		$psHtml = $psHtml . "_LIST_DELIMITOR='" . $arrConst['_CONST_LIST_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_SUB_LIST_DELIMITOR='" . $arrConst['_CONST_SUB_LIST_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_DECIMAL_DELIMITOR='" . $arrConst['_CONST_DECIMAL_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_LIST_WORK_DAY_OF_WEEK='" . $arrConst['_CONST_LIST_WORK_DAY_OF_WEEK'] . "';\n";
		$psHtml = $psHtml . "_LIST_DAY_OFF_OF_YEAR='" . $arrConst['_CONST_LIST_DAY_OFF_OF_YEAR'] . "';\n";
		$psHtml = $psHtml . "_INCREASE_AND_DECREASE_DAY='" . $arrConst['_CONST_INCREASE_AND_DECREASE_DAY'] . "';\n";
		
		$psHtml = $psHtml . "_MODAL_DIALOG_MODE='" . $arrConst['_MODAL_DIALOG_MODE'] . "';\n";
		$psHtml = $psHtml . "_GET_HTTP_AND_HOST='" . $arrConst['_GET_HTTP_AND_HOST'] . "';\n";
		$psHtml = $psHtml . "_IMAGE_URL_PATH='" . $arrConst['_CONST_IMAGE_URL_PATH'] . "';\n";
		
		$psHtml = $psHtml . "</script>\n";		
		
		return $psHtml;
	}
	
	/**
	 * @see : Thuc hien viec lay URL day du cua ung dung
	 *
	 * @return unknown
	 */
	public function _getCurrentHttpAndHost(){		
		//
		$sCurrentHttpHost = 'http://'.$_SERVER['HTTP_HOST'].self::_setWebSitePath();	
		//
		return $sCurrentHttpHost;
	}

	/**
	 * @see : thuc hien lay don vi bao cao
	 * 
	 * 	*/
	public function _setOnerName(){
		return  'VEGA';
	}
		/**
	 * Creater: HUNGVM
	 * Date : 03/08/2010
	 * Idea : Tao phuong thuc dat gia tri ID cua don vi cap cha, o day la ID cua don vi Quan/Huyen
	 *
	 * @return ID cua don vi cha
	 */
	public function _setParentOwnerId(){		
		return 262;
	}

	/**
	 * @see : thuc hien lay don vi bao cao
	 * 
	 * 	*/
	public function _setOnerReportName(){
		return  'Vega, ';
	}
	/**
	 * @see : Thuc hien lay dia chi dang nhap cua user & Duong dan mac dinh vao ung dung
	 * 	*/
	public function _setUserLoginUrl(){
		return "http://10.0.9.116/user-vega/login/index.php?url_back=".self::_getCurrentHttpAndHost().'search/search/index';	
	}
	
	/**
	 * @see : Lay duong dan toi cho dat webservice
	 * 
	 * 	*/
	public function  _setWebServiceUrl(){
		return "http://10.0.9.116/user-vega/login/webservice.php";
	}
	/**
	 * Thuc hien lay gan gia tri time out
	 * 	*/
	
	public  function _setTimeOut(){
		return 1900;
	}
	/***
	 * @see: Thuc hien gan ma ung dung
		*/
	public function _setAppCode(){
		return "EFY-QLTS";
	}
	
	/**
	 * Thuc hien lay duong dan den file khai bao phan Header cho bao cao
	 *
	 * @return unknown
	 */
	public function _setUrlTempHeaderReport(){		
		return self::_getCurrentHttpAndHost() . "templates/report-template/";
	}

	/**
	 * Creater: HUNGVM
	 * Date : 21/09/2009
	 * Idea : Tao phuong thuc lay dia chi xu ly AJAX (Vi du: http://hungvm:8080/efy-doc-boxd/application/....)
	 *
	 * @return Dia chi URL
	 */
	public function _setUrlAjax(){		
		return self::_getCurrentHttpAndHost() . "application/";
	}

	
	/**
	 * Idea: Tao phuong thuc khoi tao cac hang so dung chung
	 *
	 * @return Mang luu thong tin cac hang so dung chung
	 */
	public function _setProjectPublicConst(){
		$arrPublicConst = array();
		$arrPublicConst = array("_CONST_LIST_DELIMITOR"=>"!#~$|*",
								"_CONST_SUB_LIST_DELIMITOR"=>"!~~!",
								"_CONST_DECIMAL_DELIMITOR"=>",",
								"_CONST_IMAGE_URL_PATH"=>self::_setImageUrlPath(),
								//DInh nghia bien xac dinh cac ngay le nghi trong nam
								"_CONST_LIST_DAY_OFF_OF_YEAR"=>"+/30/04,+/01/05,+/02/09,+/01/01,-/30/12,-/01/01,-/02/12,-/10/03",
								//Dinh nghia bien quy dinh cac ngay lam viec trong tuan
								"_CONST_LIST_WORK_DAY_OF_WEEK"=>"2,3,4,5,6",								
								//Dinh nghia hang so cho phep tang len hay giam di so ngay hien giai quyet
								//Neu = 1 thi viec tinh so ra so ngay giai quyet bat dau tu ngay hien thoi
								// = 0 thi tang len 01 ngay; = 2 thi luoi ngay giai quyet di 01 ngay,...
								"_CONST_INCREASE_AND_DECREASE_DAY"=>"1",
								//Menu Top
								"_QLTS"						=>"QUẢN LÝ TÀI SẢN",
								"_TRA_CUU"					=>"TRA CỨU",
								"_BAO_CAO"					=>"BÁO CÁO",
								"_DANH_MUC"					=>"DANH MUC",
								"_HUONG_DAN"				=>"HƯỚNG DẪN",
								"_TRANG_CHU"				=>"TRANG CHỦ",
								"_THOAT"					=>"THOÁT",
								//Menu Left
								"_DM_LOAI"					=>"Loại danh mục",
								"_DM_DOITUONG"				=>"Danh mục đối tượng",
								"_DM_QUYEN"					=>"Danh mục quyền",
								//Hang so trong ung dung
								"_IN_PHIEU_BAN_GIAO"		=>"In phiếu bàn giao",
								"_IN_PHIEU_KIEM_SOAT"		=>"In phiếu kiểm soat",
								"_CHON"						=>"Chọn",
								"_STT"						=>"STT",
								"_NAM"						=>"Năm",
								"_QUYEN_XEM"				=>"Quyền xem",
								"_NGUOI_TAO_LAP"			=>"Người tạo lập",
								"_TAT_CA"					=>"Tất cả",
								"_KHAC"						=>"Khác",
								"_TINH_TRANG"				=>"Tình trạng",
								"_FILE_DINH_KEM"			=>"File đính kèm",
								"_NGAY_THUC_HIEN"			=>"Ngày thực hiện",
								"_EXPORT_WEB"				=>"Web",
								"_EXPORT_WORD"				=>"Word",
								"_EXPORT_EXCEL"				=>"Excel",
								"_TU_NGAY"					=>"Từ ngày",
								"_DEN_NGAY"					=>"Đến ngày",
								"_SO_NGAY"					=>"Số ngày",
								"_GHI_THEM_MOI"				=>"Ghi&Thêm <U>m</U>ới",
								"_GHI_THEM_TIEP"			=>"Ghi&Thêm ti<U>ế</U>p",
								"_GHI_QUAY_LAI"				=>"Ghi&<U>Q</U>uay lại",
								"_GHI_TAM"					=>"<U>G</U>hi tạm",
								"_QUAY_LAI"					=>"Quay lại",
								"_THEM"						=>"Thêm",
								"_SUA"						=>"Sửa",
								"_XOA"						=>"Xóa",
								"_XEM_CHI_TIET"				=>"Xem chi tiết",
								"_IN"						=>"In",
								"_KET_XUAT"					=>"Kết xuất",
								"_TIM_KIEM"					=>"Tìm kiếm",
								"_GHI"						=>"Ghi",
								"_DIEN_THOAI"				=>"Điện thoại",	
								"_NOI_DUNG"					=>"Nội dung",				
								"_THU_TU"					=>"Thứ tự",		
								"_TEN_CONG_TY"				=>"CÔNG TY CỔ PHẦN CÔNG NGHỆ TIN HỌC EFY VIỆT NAM<br>
															Tel (84-4) 287 2290 - Fax (84-4) 287 2290    Email: contact@efy.com.vn   Website: http://www.efy.com.vn",
								"_GET_HTTP_AND_HOST"=>self::_getCurrentHttpAndHost(),
								"_MODAL_DIALOG_MODE"=>"0");
		return 	$arrPublicConst;					
	}
	
}

