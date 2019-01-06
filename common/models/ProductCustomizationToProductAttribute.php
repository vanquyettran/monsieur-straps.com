<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_customization_to_product_attribute".
 *
 * @property int $product_customization_id
 * @property int $product_attribute_id
 *
 * @property ProductAttribute $productAttribute
 * @property ProductCustomization $productCustomization
 */
class ProductCustomizationToProductAttribute extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customization_to_product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_customization_id', 'product_attribute_id'], 'required'],
            [['product_customization_id', 'product_attribute_id'], 'integer'],
            [['product_customization_id', 'product_attribute_id'], 'unique', 'targetAttribute' => ['product_customization_id', 'product_attribute_id']],
            [['product_attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttribute::className(), 'targetAttribute' => ['product_attribute_id' => 'id']],
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
            'product_attribute_id' => 'Product Attribute ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'product_attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomization()
    {
        return $this->hasOne(ProductCustomization::className(), ['id' => 'product_customization_id']);
    }
}
