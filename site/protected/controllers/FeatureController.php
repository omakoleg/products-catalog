<?php

class FeatureController extends Controller {

    public $layout = 'backend';

    public function filters() {
        return array('accessControl', );
    }

    public function accessRules() {
        return array(
            array(
                'allow', // allow authenticated users to access all actions
                'users' => array('@'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
	
	public function actionCreateValue() {
        $model = new FeatureValue();
        $model->scenario = 'create';
        if (isset($_POST['FeatureValue'])) {
            $model->name = $_POST['FeatureValue']['name'];
			$model->feature_id = $_POST['FeatureValue']['feature_id'];
            if ($model->validate() && $model->save()) {
                $this->renderJSON();
            }
        }else{
        	if(isset($_GET['feature_id'])){
        		$model->feature_id = intval($_GET['feature_id']);
			}else{
				$this->renderJSON(array('content' => "This request invalid, use page buttons only"));
			}
        }
        $this->layout = false;
        $sContent = $this->renderPartial('_value_form', array(
            'model' => $model
        ), true);
        $this->renderJSON(array('content' => $sContent));
    }
   
    public function actionCreate() {
        $model = new Feature();
        $model->scenario = 'create';
        if (isset($_POST['Feature'])) {
            $model->name = $_POST['Feature']['name'];
            $model->display_type = $_POST['Feature']['display_type'];
            if ($model->validate() && $model->save()) {
                $this->renderJSON();
            }
        }
        $this->layout = false;
        $sContent = $this->renderPartial('_form', array(
            'model' => $model,
            'displaytypes' => $model->display_types 
        ), true);
        $this->renderJSON(array('content' => $sContent));
    }

    public function actionUpdate() {
        if (isset($_GET['id'])) {
            $model = $this->loadModel();
            $model->scenario = 'update';
        } else if (isset($_POST['Feature'])) {
            $model = Feature::model()->findByPk(intval($_POST['Feature']['id']));
            $model->setAttributes($_POST['Feature']);
            if ($model->validate() && $model->save()) {
                $this->renderJSON();
            }
        }
        $this->layout = false;
        $sContent = $this->renderPartial('_form', array(
            'model' => $model,
            'displaytypes' => $model->display_types 
        ), true);
        $this->renderJSON(array('content' => $sContent));
    }
	
	public function actionUpdateValue() {
        if (isset($_GET['id'])) {
            $model = $this->loadValueModel();
            $model->scenario = 'update';
        } else if (isset($_POST['FeatureValue'])) {
            $model = FeatureValue::model()->findByPk(intval($_POST['FeatureValue']['id']));
            $model->setAttributes($_POST['FeatureValue']);
            if ($model->validate() && $model->save()) {
                $this->renderJSON();
            }
        }
        $this->layout = false;
        $sContent = $this->renderPartial('_value_form', array(
            'model' => $model
        ), true);
        $this->renderJSON(array('content' => $sContent));
    }
	
	
    public function actionDelete() {
		$model = null;
		$iFeatureId = $_POST['id'];
		if (isset($iFeatureId)) {
			$model = Feature::model()->findByPk(intval($iFeatureId));
		}
		if ($model === null) {
			$this->renderJSONError(array('message'=>'Feature not found'));
		} else {
			try {
				$model->delete();
				$this->renderJSON();
			} catch(\Exception $e) {
				$this->renderJSONError(array('message'=>'Feature cant be deleted:' . $e->getMessage()));
			}
		}
	}
	public function actionDeleteValue() {
		$model = null;
		$iFeatureValueId = $_POST['id'];
		if (isset($iFeatureValueId)) {
			$model = FeatureValue::model()->findByPk(intval($iFeatureValueId));
		}
		if ($model === null) {
			$this->renderJSONError(array('message'=>'Feature value not found'));
		} else {
			try {
				$model->delete();
				$this->renderJSON();
			} catch(\Exception $e) {
				$this->renderJSONError(array('message'=>'Feature value cant be deleted:' . $e->getMessage()));
			}
		}
	}

    public function actionIndex() {
        $this->render('index', array());
    }
    public function actionTable(){
        $aList = Feature::model()->findAll(array(
            'with' => array('featureValues')
        ));
        $aResult = array();
        foreach ($aList as $key => $value) {
            $aResult[] = $value->toArrayJson();
        }
        $this->renderJSON(array('data' => $aResult));
    }
   

    public function loadModel() {
        $model = null;
        if (isset($_GET['id'])) {
            $model = Feature::model()->findByPk(intval($_GET['id']));
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	
	public function loadValueModel() {
        $model = null;
        if (isset($_GET['id'])) {
            $model = FeatureValue::model()->findByPk(intval($_GET['id']));
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
