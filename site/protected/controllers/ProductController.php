<?php

class ProductController extends Controller {
	public $layout = 'backend';

	public function actionIndex() {
		$this->render('index', array(
			'categories' => $this->convertCategoriesToHierarchy(Category::model()->findAll())
		));
	}

	public function actionTable() {
		
		$iLimit = isset($_GET['limit'])? intval($_GET['limit']): 50;
		$iOffset = isset($_GET['offset'])? intval($_GET['offset']): 0;
		$aFilter = isset($_GET['filter'])? $_GET['filter']: array();
		
		$criteria = new CDbCriteria;
		if(isset($aFilter['category_id'])){
			$aData = Yii::app()->db->createCommand()
                ->select('p.id')
                ->from('product AS p')
                ->join('category_to_product ctp', 'ctp.product_id = p.id')
                ->join('category c', 'ctp.category_id = c.id AND c.id = :cid',array(':cid' => $aFilter['category_id']))                
                ->queryAll();
        	$aIds = MArrayHelper::toArray($aData, 'id', true);
			if($aIds && count($aIds)){
				$criteria->condition = 't.id in (' . implode(',', $aIds) . ')';
			}
		}
		if(isset($aFilter['is_new'])){
        	$criteria->compare('t.is_new', $aFilter['is_new']);
		}
		if(isset($aFilter['name_like'])){
			$criteria->addSearchCondition('t.name', $aFilter['name_like']);
		}
		if(isset($aFilter['ref_like'])){
        	$criteria->addSearchCondition('t.ref', $aFilter['ref_like']);
		}
        $criteria->with = array('category_to_product', 'productImages');
		$criteria->limit = $iLimit;
		$criteria->offset = $iOffset;
		$criteria->order = 't.id ASC';



        $aProvider =  new CActiveDataProvider('Product', array(
            'criteria' => $criteria
        ));
		$aList = $aProvider->getData();
		
		$criteria->offset = 0;
		$criteria->limit = 0;
		$mCount = $aProvider->getData();
		
		$aData = array();
		foreach ($aList as $value) {
			$aData[] = $value->toArrayJson();
		}
		$this->renderJSON(array(
			'data' => $aData,
			'count' => count($mCount)
		));
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

	public function loadModel() {
		$model = null;
		if (isset($_GET['id'])) {
			$model = Product::model()->findByPk(intval($_GET['id']));
		}
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	public function actionUpdate() {
		if (isset($_GET['id'])) {
			$model = $this->loadModel();
			$model->scenario = 'update';
		} else if (isset($_POST['Product'])) {
			$model = Product::model()->findByPk(intval($_POST['Product']['id']));
			$model->setAttributes($_POST['Product']);
			if ($model->validate() && $model->save()) {
				$this->renderJSON();
			}
		}
		$this->layout = false;
		$sContent = $this->renderPartial('_form', array('model' => $model), true);
		$this->renderJSON(array('content' => $sContent));
	}

	public function actionAssignSave() {
		$iProductId = $_POST['product_id'] ? intval($_POST['product_id']) : null;
		$aCategories = $_POST['category_id'] ? $_POST['category_id'] : array();

		if (!$iProductId) {
			throw new CHttpException(400, 'Invalid request. Please provide product ID');
		}
		$aoAssign = CategoryToProduct::model()->findAll(array(
			'condition' => 'product_id = :product_id',
			'params' => array(':product_id' => $iProductId),
		));
		foreach ($aoAssign as $value) {
			$value->delete();
		}
		foreach ($aCategories as $category_id) {
			$oItem = new CategoryToProduct();
			$oItem->product_id = $iProductId;
			$oItem->category_id = $category_id;
			if ($oItem->validate()) {
				$oItem->save();
			}
		}
		$this->renderJSON();
	}

	public function actionAssign() {

		$iProductId = $_GET['product_id'];
		$iProductId = $iProductId ? intval($iProductId) : null;
		if (!$iProductId) {
			throw new CHttpException(400, 'Invalid request. Please provide product ID');
		}
		$aSelectedCategories = Category::model()->findAll(array(
			'condition' => 'category_to_product.product_id = :product_id',
			'params' => array(':product_id' => $iProductId),
			'with' => array('category_to_product')
		));

		$aCategories = Category::model()->findAll();
		$aCategories = $this->convertCategoriesToHierarchy($aCategories);

		$this->renderDialog('assign', array(
			'product_id' => $iProductId,
			'categories' => $aCategories,
			'selected' => $aSelectedCategories
		));
	}

	public function actionAttributes() {
		$iProductId = $_GET['product_id'];
		$iProductId = $iProductId ? intval($iProductId) : null;
		if (!$iProductId) {
			throw new CHttpException(400, 'Invalid request. Please provide product ID');
		}
		$aoFeatures = Feature::model()->findAll(array('with' => array(
				'featureValues',
				'valuesCount'
			)));
		$features = Feature::model()->findAll(array(
			'condition' => 'attributes.product_id = :product_id AND attributes.feature_value_id = featureValues.id',
			'with' => array(
				'attributes',
				'featureValues'
			),
			'params' => array(':product_id' => $iProductId),
		));
		$aSelected = array();
		foreach ($features as $v) {
			$aSelected[$v->id] = $v->toArrayJson();
		}
		$aAll = array();
		foreach ($aoFeatures as $v) {
			$aAll[$v->id] = $v->toArrayJson();
		}

		$this->renderDialog('attributes', array(
			'product_id' => $iProductId,
			'featuresall' => $aAll,
			'features' => $aSelected
		));
	}

	public function actionAttributesSave() {
		$iProductId = $_POST['product_id'];
		$aFeatureIds = isset($_POST['feature_id']) ? $_POST['feature_id'] : NULL;
		$aFeatureValuesIds = isset($_POST['feature_value_id']) ? $_POST['feature_value_id'] : NULL;

		$aItems = array();
		if (!empty($aFeatureIds))
			foreach ($aFeatureIds as $featureId) {
				foreach ($aFeatureValuesIds[$featureId] as $feaureValueId) {
					$aItems[] = array(
						'feature_value_id' => $feaureValueId,
						'feature_id' => $featureId,
						'product_id' => $iProductId
					);
				}
			}
		Attribute::model()->deleteAllByAttributes(array('product_id' => $iProductId));
		foreach ($aItems as $value) {
			$attr = new Attribute();
			$attr->setAttributes($value);
			$attr->save();
		}
	}

	public function actionCreate() {
		$model = new Product();
		$model->scenario = 'create';
		if (isset($_POST['Product'])) {
			$model->name = $_POST['Product']['name'];
			$model->price = $_POST['Product']['price'];
			$model->description = $_POST['Product']['description'];
			$model->is_new = $_POST['Product']['is_new'];
			$model->ref = $_POST['Product']['ref'];
			if ($model->validate() && $model->save()) {
				$this->renderJSON();
			}
		}
		$this->layout = false;
		$sContent = $this->renderPartial('_form', array('model' => $model), true);
		$this->renderJSON(array('content' => $sContent));
	}

	public function actionDelete() {
		$model = null;
		$iProductId = $_POST['id'];
		if (isset($iProductId)) {
			$model = Product::model()->findByPk(intval($iProductId));
		}
		if ($model === null) {
			$this->renderJSONError('Product not found');
		} else {
			try {
				$model->delete();
				$this->renderJSON();
			} catch(\Exception $e) {
				$this->renderJSONError('Product cant be deleted:' . $e->getMessage());
			}
		}

	}

	public function actionImages() {
		$iProductId = $_GET['product_id'];
		$iProductId = $iProductId ? intval($iProductId) : null;
		if (!$iProductId) {
			throw new CHttpException(400, 'Invalid request. Please provide product ID');
		}

		$this->renderDialog('images', array(
			'product_id' => $iProductId,
			'product_images' => $this->getProductPhotos($iProductId)
		));
	}

	public function actionFileUpload() {
		$model = new ProductImage();
		$model->setAttributes($_POST['ProductImage']);
		if ($model->validate() && $model->save()) {
			$this->renderJSON($model->getAttributes());
		} else {
			$this->renderJSONError(array('errors' => $model->getErrors()));
		}
	}

	public function actionImagesSave() {
		$aPost = $_POST;
		$iProductId = $aPost['product_id'];
		$iDefaultImageId = isset($aPost['default_image_id']) ? $aPost['default_image_id'] : NULL;
		$aUpdateData = isset($aPost['product_image']) ? $aPost['product_image'] : array();

		$aDoNotRemoveIDs = !empty($aUpdateData) ? array_keys($aUpdateData) : array();
		$aExisting = $this->getProductPhotos($iProductId);

		if (empty($iDefaultImageId) && count($aDoNotRemoveIDs)) {
			$iDefaultImageId = $aDoNotRemoveIDs[0];
		}

		foreach ($aExisting as $value) {
			if (!in_array($value->id, $aDoNotRemoveIDs)) {
				$value->delete();
			} else if ($value->name != $aUpdateData[$value->id]) {
				$value->name = $aUpdateData[$value->id]['name'];
				$value->is_default = ($iDefaultImageId == $value->id);
				$value->save();
			}
		}
	}

	/*
	 *
	 */
	protected function getProductPhotos($iProductId) {
		return ProductImage::model()->findAll(array(
			'condition' => 'product_id = :product_id',
			'params' => array(':product_id' => $iProductId),
		));
	}

	protected function convertCategoriesToHierarchy($aCatagories) {
		$aResult = array();
		foreach ($aCatagories as $value) {
			if (empty($value->parent_id)) {
				if (!isset($aResult[$value->id])) {
					$aResult[$value->id] = array(
						'id' => '',
						'name' => '',
						'items' => array()
					);
				}
				$aResult[$value->id]['id'] = $value->id;
				$aResult[$value->id]['name'] = $value->name;
			} else {
				if (!isset($aResult[$value->parent_id])) {
					$aResult[$value->parent_id] = array(
						'id' => '',
						'name' => '',
						'items' => array()
					);
				}
				$aResult[$value->parent_id]['items'][] = array(
					'id' => $value->id,
					'name' => $value->name
				);
			}
		}
		$aData = array();
		foreach ($aResult as $value) {
			$aData[$value['id']] = $value['name'];
			if (isset($value['items'])) {
				foreach ($value['items'] as $value2) {
					$aData[$value2['id']] = ' --- ' . $value2['name'];
				}
			}
		}
		return $aData;

	}

}
