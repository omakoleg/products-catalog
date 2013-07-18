<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $name
 * @property string $slug
 */
class Category extends CActiveRecord {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Category the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'parent_id',
                'safe'
            ),
            array(
                'name, slug',
                'length',
                'max' => 255
            ),
            array(
                'name, slug',
                'safe',
                'on' => 'search'
            ),
            array(
                'name, slug',
                'required',
                'on' => 'create, update'
            ),
            array(
                'slug',
                'unique',
                'on' => 'create, update'
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
                'category_id'
            ),
            'products' => array(
                self::HAS_MANY,
                'Product',
                array('product_id' => 'id'),
                'through' => 'category_to_product'
            ),
            'parent' => array(
                self::BELONGS_TO,
                'Category',
                array('parent_id' => 'id')
            ),
            'childsCount' => array(
                self::STAT,
                'Category',
                'parent_id'
            ),
            'childs'=> array(
                self::HAS_MANY,
                'Category',
                'parent_id'
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
            'slug' => 'Slug',
            'parent_id' => 'Parent'
        );
    }
	protected function beforeDelete() {
        if ($this->category_to_product)
            foreach ($this->category_to_product as $value) {
                $value->delete();
            }
        return parent::beforeDelete();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.slug', $this->slug, true);
        $criteria->mergeWith(array('order' => 'parent.name ASC'));
        $criteria->with = array(
            'parent','childsCount'
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));
    }
	
	public function toArrayJson(){
		 $aTemp = $this->getAttributes();
		 $aTemp['childs_count'] = $this->childsCount;
		 return $aTemp;
	}

}
