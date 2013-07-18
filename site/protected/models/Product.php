<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $name
 * @property string $price
 * @property string $description
 */
class Product extends CActiveRecord {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'product';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'name, price, description, is_new, ref',
                'safe',
                'on' => 'search, create, update'
            ),
            array(
                'name, description',
                'length',
                'max' => 255
            ),
            array(
                'price',
                'type',
                'type' => 'float'
            ),
            array(
                'name, price, description',
                'required',
                'on' => 'update, create'
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
            'category_to_product' => array(
                self::HAS_MANY,
                'CategoryToProduct',
                'product_id'
            ),
            'categories' => array(
                self::HAS_MANY,
                'Category',
                array('category_id' => 'id'),
                'through' => 'category_to_product'
            ),
            'attributes' => array(
                self::HAS_MANY,
                'Attribute',
                array('product_id' => 'id'),
            ),
            'productImages' => array(
                self::HAS_MANY,
                'ProductImage',
                array('product_id' => 'id'),
                'order' => 'productImages.is_default DESC',
            ),
            'productImagesCount' => array(
                self::STAT,
                'ProductImage',
                array('product_id' => 'id'),
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
            'price' => 'Price',
            'description' => 'Description',
            'is_new' => 'Is new',
            'ref' => 'Ref.'
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

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.price', $this->price, true);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.is_new', $this->is_new, true);
        $criteria->compare('t.ref', $this->ref, true);
        $criteria->with = array('categories');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));
    }

    protected function beforeDelete() {
        if ($this->productImages)
            foreach ($this->productImages as $value) {
                $value->delete();
            }
        if ($this->attributes)
            foreach ($this->attributes as $value) {
                $value->delete();
            }
        if ($this->category_to_product)
            foreach ($this->category_to_product as $value) {
                $value->delete();
            }
        return parent::beforeDelete();
    }
	
	public function toArrayJson(){
		 $aTemp = $this->getAttributes();
		 if($this->productImages){
		 	foreach ($this->productImages as $value) {
				 $aTemp['productImages'][] = $value->toArrayJson();
			 }
		 }
		 return $aTemp;
	}
    
    
    public function getFeaturesHierarchy($aoFeatures = array()){
        $aResult = array();
        foreach ($this->attributes as $attribute) {
            if(!isset($aResult[$attribute->feature->id])){
                $aResult[$attribute->feature->id] = array(
                    'name'=> $attribute->feature->name,
                    'display_type'=> $attribute->feature->display_type,
                    'values' => array()
                );
            }
            $aResult[$attribute->feature->id]['values'][] =  $attribute->featureValue->name;
        }
        return $aResult;
    }

}
