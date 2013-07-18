<?php

/**
 * This is the model class for table "attribute".
 *
 * The followings are the available columns in table 'attribute':
 * @property integer $id
 * @property integer $feature_id
 * @property integer $feature_value_id
 * @property integer $product_id
 *
 * The followings are the available model relations:
 * @property FeatureValue $featureValue
 * @property Feature $feature
 * @property Product $product
 */
class Attribute extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Attribute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feature_id, feature_value_id, product_id', 'required'),
			array('feature_id, feature_value_id, product_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('feature_id, feature_value_id, product_id, is_filtered', 'safe', 'on'=>'create,update,search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'featureValue' => array(self::BELONGS_TO, 'FeatureValue', 'feature_value_id'),
			'feature' => array(self::BELONGS_TO, 'Feature', 'feature_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'feature_id' => 'Feature',
			'feature_value_id' => 'Feature Value',
			'product_id' => 'Product',
			'is_filtered' => 'Show in filter ?'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('feature_id',$this->feature_id);
		$criteria->compare('feature_value_id',$this->feature_value_id);
		$criteria->compare('product_id',$this->product_id);
        $criteria->compare('is_filtered', $this->is_filtered, true);
        
        $criteria->with  = array('feature','featureValue');
        $criteria->mergeWith(array('order' => 'feature.name, featureValue.name ASC'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>50,
            ),
		));
	}
}