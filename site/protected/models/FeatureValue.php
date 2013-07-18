<?php

/**
 * This is the model class for table "feature_value".
 *
 * The followings are the available columns in table 'feature_value':
 * @property integer $id
 * @property integer $feature_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Attribute[] $attributes
 * @property Feature $feature
 */
class FeatureValue extends CActiveRecord {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FeatureValue the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'feature_value';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'feature_id',
                'required'
            ),
            array(
                'feature_id',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'name',
                'required'
            ),
            array(
                'feature_id, name',
                'safe',
                'on' => 'search'
            ),
            array(
                'name',
                'length',
                'max' => 255
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
                'feature_value_id'
            ),
            'feature' => array(
                self::BELONGS_TO,
                'Feature',
                'feature_id'
            ),
            'attributesCount' => array(
                self::STAT,
                'Attribute',
                'feature_value_id'
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'feature_id' => 'Feature',
            'name' => 'Name',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('feature_id', $this->feature_id);
        $criteria->compare('name', $this->name, true);
        $criteria->with = array('attributesCount');
       return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));
    }
	
	public function toArrayJson(){
		 return $this->getAttributes();
	}
	

}
