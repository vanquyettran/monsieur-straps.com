<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_to_product_attribute".
 *
 * @property int $product_id
 * @property int $product_attribute_id
 *
 * @property ProductAttribute $productAttribute
 * @property Product $product
 */
class ProductToProductAttribute extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_attribute_id'], 'required'],
            [['product_id', 'product_attribute_id'], 'integer'],
            [['product_id', 'product_attribute_id'], 'unique', 'targetAttribute' => ['product_id', 'product_attribute_id']],
            [['product_attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttribute::className(), 'targetAttribute' => ['product_attribute_id' => 'id']],
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
