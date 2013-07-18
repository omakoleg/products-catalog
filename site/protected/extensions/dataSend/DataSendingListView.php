<?php

Yii::import('zii.widgets.CListView');

class DataSendingListView extends CListView {
    
    public $populateDataFunction = '';

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript() {
        $id = $this->getId();

        if ($this->ajaxUpdate === false)
            $ajaxUpdate = array();
        else
            $ajaxUpdate = array_unique(preg_split('/\s*,\s*/', $this->ajaxUpdate . ',' . $id, -1, PREG_SPLIT_NO_EMPTY));
        $options = array(
            'ajaxUpdate' => $ajaxUpdate,
            'ajaxVar' => $this->ajaxVar,
            'pagerClass' => $this->pagerCssClass,
            'loadingClass' => $this->loadingCssClass,
            'sorterClass' => $this->sorterCssClass,
        );
        if ($this->ajaxUrl !== null)
            $options['url'] = CHtml::normalizeUrl($this->ajaxUrl);
        if ($this->populateDataFunction !== null)
            $options['populateDataFunction'] = (strpos($this->populateDataFunction, 'js:') !== 0 ? 'js:' : '') . $this->populateDataFunction;
        if ($this->updateSelector !== null)
            $options['updateSelector'] = $this->updateSelector;
        if ($this->beforeAjaxUpdate !== null)
            $options['beforeAjaxUpdate'] = (strpos($this->beforeAjaxUpdate, 'js:') !== 0 ? 'js:' : '') . $this->beforeAjaxUpdate;
        if ($this->afterAjaxUpdate !== null)
            $options['afterAjaxUpdate'] = (strpos($this->afterAjaxUpdate, 'js:') !== 0 ? 'js:' : '') . $this->afterAjaxUpdate;

        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');
        $cs->registerScriptFile('/js/jquery.datasending.yiilistview.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').dataSendingListView($options);");
    }

}
