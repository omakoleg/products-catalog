<?php

/**
 * This is the model class for table "product_image".
 *
 * The followings are the available columns in table 'product_image':
 * @property integer $id
 * @property string $filename
 * @property string $name
 * @property integer $product_id
 *
 * The followings are the available model relations:
 * @property Product $product
 */
class ProductImage extends CActiveRecord {

    public $filename;
    // used by the form to send the file.

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductImage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'product_image';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'product_id',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'filename',
                'file',
                'types' => 'png, gif, jpg, jpeg, bmp',
                'allowEmpty' => true
            ),
            array(
                'name',
                'length',
                'max' => 255
            ),
            array(
                'name, filename',
                'required',
                'on' => 'create'
            ),
            array(
                'name, product_id, filename, is_default',
                'safe',
                'on' => 'search, update, create'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array('product' => array(
                self::BELONGS_TO,
                'Product',
                'product_id'
            ), );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'product_id' => 'Product',
            'filename' => 'Select file',
            'is_default' => 'Is default image'
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
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('is_default', $this->is_default, true);
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));
    }

    public function behaviors() {
        return array('recipeImgBehavior' => array(
                'class' => 'ImageARBehavior',
                'attribute' => 'filename', // this must exist
                'extension' => 'png, gif, jpg, jpeg, bmp', // possible extensions, comma separated
                'prefix' => 'img_',
                'relativeWebRootFolder' => 'images/products', // this folder must exist
                // this will define formats for the image.
                // The format 'normal' always exist. This is the default format, by default no
                // suffix or no processing is enabled.
                'formats' => array(
                    // create a thumbnail grayscale format
                    'thumb' => array(
                        'suffix' => '_thumb',
                        'process' => array(
                            'resize' => array(
                                60,
                                60
                            ),
                        ),
                    ),
                    // create a large one (in fact, no resize is applied)
                    //375 × 482
                    'preview' => array(
                        'suffix' => '_preview',
                        'process' => array('resize' => array(
                                375,
                                482
                            ))
                    ), 
                    'large' => array('suffix' => '_large'),
                    // and override the default :
                    'normal' => array('process' => array('resize' => array(
                                180,
                                231
                            ))),
                ),

                'defaultName' => 'default', // when no file is associated, this one is used by getFileUrl
                // defaultName need to exist in the relativeWebRootFolder path, and prefixed by prefix,
                // and with one of the possible extensions. if multiple formats are used, a default file must exist
                // for each format. Name is constructed like this :
                //     {prefix}{name of the default file}{suffix}{one of the extension}
            ));
    }

    public function renderImageTag($data, $row){
        //https://bitbucket.org/z_bodya/fileimagearbehavior
        return CHtml::image($data->getFileUrl('thumb'));
    }
	
	public function toArrayJson(){
		 $aTemp = $this->getAttributes();
		 $aTemp['img_thumb'] = $this->getFileUrl('thumb');
		 $aTemp['img_large'] = $this->getFileUrl('large');
		 return $aTemp;
	}
	
	public function getAttributes($names=true){
		$aData = parent::getAttributes($names);
		if($this->id){
			$aData = array_merge($aData, array(
				'img_normal' => $this->getFileUrl('normal')
			));
		}
		return $aData;
	}


}
