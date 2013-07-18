<?php

class FeatureValueController extends Controller {
    public $layout = 'backend';
    public function filters() {
        return array('accessControl', );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'users' => array('@'),
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id) {
        $this->render('view', array('model' => $this->loadModel($id), ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $iFeatureId = $_GET['feature_id'];
        $iFeatureId = $iFeatureId ? intval($iFeatureId) : null;
        if (!$iFeatureId) {
            throw new CHttpException(400, 'Invalid request. Please provide product ID');
        }
        $model = new FeatureValue();
        $model->feature_id = $iFeatureId;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FeatureValue'])) {
            $model->setAttributes($_POST['FeatureValue']);
            if ($model->validate() && $model->save())
                $this->redirect(array('featureValue/index?feature_id=' . $model->feature_id));
        }

        $this->render('create', array('model' => $model, ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FeatureValue'])) {
            $model->setAttributes($_POST['FeatureValue']);
            if ($model->validate() && $model->save())
                $this->redirect(array(
                    'index',
                    'feature_id' => $model->feature_id
                ));
        }

        $this->render('update', array('model' => $model, ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $aoModel = $this->loadModel($id);
            $iFeatureId = $aoModel->feature_id;
            $aoModel->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('featureValue/index?feature_id=' . $iFeatureId));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $iFeatureId = $_GET['feature_id'];
        $iFeatureId = $iFeatureId ? intval($iFeatureId) : null;
        if (!$iFeatureId) {
            throw new CHttpException(400, 'Invalid request. Please provide product ID');
        }
        $model = new FeatureValue('search');
        $model->unsetAttributes();
        // clear any default values
        if (isset($_GET['FeatureValue']))
            $model->setAttributes($_GET['FeatureValue']);
        $model->feature_id = $iFeatureId;

        $this->render('index', array(
            'model' => $model,
            'featureId' => $iFeatureId
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = FeatureValue::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
