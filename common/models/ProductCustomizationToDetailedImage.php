<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_customization_to_detailed_image".
 *
 * @property int $product_customization_id
 * @property int $detailed_image_id
 * @property int $sort_order
 *
 * @property Image $detailedImage
 * @property ProductCustomization $productCustomization
 */
class ProductCustomizationToDetailedImage extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customization_to_detailed_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_customization_id', 'detailed_image_id', 'sort_order'], 'required'],
            [['product_customization_id', 'detailed_image_id', 'sort_order'], 'integer'],
            [['product_customization_id', 'detailed_image_id'], 'unique', 'targetAttribute' => ['product_customization_id', 'detailed_image_id']],
            [['detailed_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['detailed_image_id' => 'id']],
            [['product_customization_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCustomization::className(), 'targetAttribute' => ['product_customization_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_customization_id' => 'Product Customization ID',
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
    public function getProductCustomization()
    {
        return $this->hasOne(ProductCustomization::className(), ['id' => 'product_customization_id']);
    }
}
