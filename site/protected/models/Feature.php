<?php

/**
 * This is the model class for table "feature".
 *
 * The followings are the available columns in table 'feature':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Attribute[] $attributes
 * @property FeatureValue[] $featureValues
 */
class Feature extends CActiveRecord {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Feature the static model class
     */
    public $display_types = array(
        'regular' => array('name'=>'Regular','value'=>'regular'),
        'color' => array('name'=>'Color','value'=>'color'),
        'backblack' => array('name'=>'Back Black','value'=>'backblack')
    );
     
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'feature';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'name',
                'length',
                'max' => 255
            ),
            array(
                'name, display_type',
                'required',
                'on' => 'update, create'
            ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array(
                'name, display_type',
                'safe',
                'on'=>'search, create, update'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'attributes' => array(
                self::HAS_MANY,
                'Attribute',
                'feature_id'
            ),
            'featureValues' => array(
                self::HAS_MANY,
                'FeatureValue',
                'feature_id'
            ),
            'valuesCount' => array(
                self::STAT,
                'FeatureValue',
                'feature_id'
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
	}
	
	public function toArrayJson(){
		 $aTemp = $this->getAttributes();
		 $aTemp['display_type_label'] = $this->display_types[$this->display_type]['name'];
		 if($this->featureValues){
		 	foreach ($this->featureValues as $value) {
				 $aTemp['featureValues'][$value->id] = $value->toArrayJson();
			 }
		 }
		 return $aTemp;
	}
	protected function beforeDelete() {
        if ($this->featureValues)
            foreach ($this->featureValues as $value) {
                $value->delete();
            }
        return parent::beforeDelete();
    }
    
    public function renderDisplayType($data, $row) {
        return $this->display_types[$data['display_type']]['name'];
    }

}
