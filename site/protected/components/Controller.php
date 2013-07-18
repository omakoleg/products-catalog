<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = 'frontend';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    protected $session = null;

    protected function setSessionVar($sVar, $sValue) {
        if (!$this->session) {
            $this->session = new CHttpSession;
            $this->session->open();
        }
        $this->session[$sValue] = $sValue;
    }

    protected function getSessionVar($sVar) {
        if (!$this->session) {
            $this->session = new CHttpSession;
            $this->session->open();
        }
        return $this->session[$sVar];
    }
	protected function renderJSONError($data = array()) {
		$data = array_merge($data, array('error' => true));
		$this->_renderJSON($data);
	}
	
	protected function renderJSON($data = array()) {
		$data = array_merge($data, array('error' => false));
		$this->_renderJSON($data);
	}
	protected function renderDialog($name, $data = array()) {
		$this->layout = false;
        $sContent = $this->render($name,$data, true);
		$this->renderJSON(array_merge(array('content'=>$sContent), $data));
	}

	protected function _renderJSON($data = array()) {
		header('Content-type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();
	}

}
