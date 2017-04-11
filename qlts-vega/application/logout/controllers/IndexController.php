<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class Logout_IndexController extends  Zend_Controller_Action {
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
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";

	}	
	/**
	 * Creater : HUNGVM
	 * Date : 27/09/2009
	 * Idea : Tao phuong thuc hien xu ly logout khoi he thong
	 *
	 */
	public function indexAction(){
		Zend_Loader::loadClass('Zend_Session');
		//Load class Efy_Init_Config
		Zend_Loader::loadClass('Efy_Init_Config');
		Zend_Session::destroy();
		$sReURL = Efy_Init_Config::_setUserLoginUrl();?>
		<script>
			window.location.href = '<?=$sReURL;?>';
		</script>
		<?php
	}
}
?>