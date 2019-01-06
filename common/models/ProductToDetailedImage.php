<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_to_detailed_image".
 *
 * @property int $product_id
 * @property int $detailed_image_id
 * @property int $sort_order
 *
 * @property Image $detailedImage
 * @property Product $product
 */
class ProductToDetailedImage extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_detailed_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'detailed_image_id', 'sort_order'], 'required'],
            [['product_id', 'detailed_image_id', 'sort_order'], 'integer'],
            [['product_id', 'detailed_image_id'], 'unique', 'targetAttribute' => ['product_id', 'detailed_image_id']],
            [['detailed_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['detailed_image_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'detailed_image_id' => 'Detailed Image ID',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailedImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'detailed_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
