<?php

class SiteController extends Controller {
	
    public $layout = 'frontend';
    public $categories;
    public $selected = array();
    public $breadcrumbs = array();
    public $selectedCategory;

    private function getCategoriesMenu($slug = null) {
        $aCategories = Category::model()->findAll(array(
            'condition' => 't.parent_id IS NULL',
            'with' => array('childs')
        ));
        $aCategorySlug = Category::model()->findAll();
        $aSlug = MArrayHelper::objectsToArray($aCategorySlug, 'slug', true);

        $aSelected = array();
        $aCategorySelected = NULL;
        if ((empty($slug) || !in_array($slug, $aSlug)) && count($aCategories)) {
            $slug = $aCategories[0]->slug;
        }
        if ($slug) {
            $aCategorySelected = Category::model()->find('slug = :slug', array('slug' => $slug));
            $this->breadcrumbs[] = $aCategorySelected;
            if ($aCategorySelected) {
                $aSelected[] = $aCategorySelected->id;
                if ($aCategorySelected->parent) {
                    $aSelected[] = $aCategorySelected->parent->id;
                    $this->breadcrumbs[] = $aCategorySelected->parent;
                }; 
            };
        };
        $this->categories = $aCategories;
        $this->selected = $aSelected;
        $this->selectedCategory = $aCategorySelected;
        return array(
            'categories' => $aCategories,
            'selected' => $aSelected,
            'categorySelected' => $aCategorySelected
        );
    }

    private function getProductDataprovider($iProductId) {
        $oProduct = Product::model()->find(array(
            'condition' => 't.id = :id',
            'params'=>array(':id'=>$iProductId),
            'with' => array(
                'productImages',
                'attributes',
                'attributes.feature',
                'attributes.featureValue'
            )
        ));
        return $oProduct;
    }

    private function getProductIds($aParams) {
        $aData = Yii::app()->db->createCommand()
                ->select('p.id')
                ->from('product AS p')
                ->join('category_to_product ctp', 'ctp.product_id = p.id')
                ->join('category c', 'ctp.category_id = c.id AND c.id = :cid',array(':cid' => $aParams['category_id']))                
                ->queryAll();
        $aIds = MArrayHelper::toArray($aData, 'id', true);
        if($aIds && count($aIds) && $aParams['feature_values']){            
            $aData = Yii::app()->db->createCommand()
                ->select('p.id')
                ->from('product p')
                ->leftJoin('attribute a', 'p.id = a.product_id')                              
                ->where('fv.id IS NULL ')
                ->where(array('and',array('in','p.id',$aIds),
                        array('or',
                            array('in','a.feature_value_id',$aParams['feature_values']),
                            'a.feature_value_id IS NULL')))
                ->group('p.id')
                ->queryAll();
                $aFilteredIds = MArrayHelper::toArray($aData, 'id', true);
                $aIds = array_values(array_intersect($aIds, $aFilteredIds));                
        }
        return $aIds;
    }

    private function getProductsDataprovider($aIds = NULL) {
        if(!$aIds){
            $aIds = array(-1);
        }
        return new CActiveDataProvider('Product', array(
            'criteria' => array(
                'condition' => 't.id in (' . implode(',', $aIds) . ')',
                'order' => 't.name ASC',
                'with' => array(
                    'attributes',
                    'productImages',
                )
            ),
            'pagination' => array('pageSize' => 8)
        ));
    }

    private function getFilter($aProductIds) {
        $aData = Yii::app()->db->createCommand()
                ->select('
                a.product_id,
                    f.id as f_id,
                    f.name as f_name,
                    fv.id as fv_id,
                    fv.name as fv_name')
                ->from('attribute a')
                ->join('feature f', 'f.id = a.feature_id')
                ->join('feature_value fv', 'fv.id = a.feature_value_id')   
                ->where(array('and',array('in', 'a.product_id', $aProductIds),'a.is_filtered = 1'))                           
                ->group('f.id, fv.id')
                ->order('f.name')
                ->queryAll();

        $aFeature = array();
        foreach ($aData as $value) {
            if (!isset($aFeature[$value['f_id']])) {
                $aFeature[$value['f_id']] = array();
            }
            $aFeature[$value['f_id']]['name'] = $value['f_name'];
            if (!isset($aFeature[$value['f_id']]['values'])) {
                $aFeature[$value['f_id']]['values'] = array();
            }
            $aFeature[$value['f_id']]['values'][$value['fv_id']] = array('name' => $value['fv_name']);
        }
      
        return $aFeature;
    }

    public function actionView($slug) {
       $this->subactionView($slug);
    }

    public function actionIndex() {
       $this->subactionView();
    }
    
    public function subactionView($slug = NULL){
        $this->layout = 'site';

        $aFeatureValues = Yii::app()->request->getQuery('featureValues', NULL);
        $aMenu = $this->getCategoriesMenu($slug);
		if(empty($aMenu['categories'])){
			throw new CHttpException(404, 'Categories not found. Data Error');
		}
	    $iIds = $this->getProductIds(array(
	        'category_id' => $aMenu['categorySelected']->id,
	        'feature_values' => isset($aFeatureValues) ? explode(',', $aFeatureValues) : NULL
	    ));
	    $aFilter = $this->getFilter($iIds);
	    $oDataprovider = $this->getProductsDataprovider($iIds);
	    $this->render('index', array(
	        'categories' => $aMenu['categories'],
	        'selected' => $aMenu['selected'],
	        'dataProvider' => $oDataprovider,
	        'filter' => $aFilter
	    ));
		
    }

    public function actionItem($slug, $id) {
        $this->layout = 'site';
        $aoProduct = Product::model()->with('categories')->findByPk($id);
        if (!$aoProduct) {
            throw new CHttpException(404, 'Product doesnt exist');
        }
        $aMenu = $this->getCategoriesMenu($slug);
        $oProduct = $this->getProductDataprovider($id);
        $this->render('item', array(
            'categories' => $aMenu['categories'],
            'selected' => $aMenu['selected'],
            'product' => $oProduct
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
