<?php

class CategoryController extends Controller {

	public $layout = 'backend';

	public function actionTable() {
		$aList = Category::model()->findAll(array(
			'with' => array('childsCount'),
			'order' => 'parent_id ASC'
		));
		$aData = array();
		foreach ($aList as $value) {
			$aData[] = $value->toArrayJson();

		}
		$this->renderJSON(array('data' => $aData));
	}

	public function actionIndex() {
		$model = new Category('search');
		$model->unsetAttributes();
		if (isset($_GET['Category']))
			$model->attributes = $_GET['Category'];
		$this->render('index', array('model' => $model, ));
	}

	public function filters() {
		return array('accessControl');
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

	private function getParents($iExclude = null) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'parent_id IS NULL ' . ($iExclude ? ' AND id != ' . $iExclude : '');
		return Category::model()->findAll($criteria);
	}

	public function loadModel() {
		$model = null;
		if (isset($_GET['id'])) {
			$model = Category::model()->findByPk(intval($_GET['id']));
		}
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	public function actionUpdate() {
		if (isset($_GET['id'])) {
			$model = $this->loadModel();
			$model->scenario = 'update';
		} else if (isset($_POST['Category'])) {
			$model = Category::model()->findByPk(intval($_POST['Category']['id']));
			$model->setAttributes($_POST['Category']);
			if ($model->validate() && $model->save()) {
				$this->renderJSON();
			}
		}
		$this->layout = false;
		$sContent = $this->renderPartial('_form', array(
			'model' => $model,
			'parents' => $this->getParents($model->id)
		), true);
		$this->renderJSON(array('content' => $sContent));
	}

	public function actionCreate() {
		$model = new Category();
		$model->scenario = 'create';
		if (isset($_POST['Category'])) {
			$model->attributes = $_POST['Category'];
			if ($model->validate() && $model->save()) {
				$this->renderJSON();
			}
		}
		$this->layout = false;
		$sContent = $this->renderPartial('_form', array(
			'model' => $model,
			'parents' => $this->getParents()
		), true);
		$this->renderJSON(array('content' => $sContent));
	}

	public function actionDelete() {
		$model = null;
		$iProductId = $_POST['id'];
		if (isset($iProductId)) {
			$model = Category::model()->findByPk(intval($iProductId));
		}
		if ($model === null) {
			$this->renderJSONError(array('message'=>'Category not found'));
		} else if ($model->childsCount) {
			$this->renderJSONError(array('message'=>'Category cant be removed. There are subcategories, remove them first'));
		} else {
			try {
				$model->delete();
				$this->renderJSON();
			} catch(\Exception $e) {
				$this->renderJSONError(array('message'=>'Category cant be deleted:' . $e->getMessage()));
			}
		}
	}

}
