<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_customization_to_product_discount".
 *
 * @property int $product_customization_id
 * @property int $product_discount_id
 * @property int $product_id
 *
 * @property ProductCustomization $productCustomization
 * @property ProductDiscount $productDiscount
 * @property Product $product
 */
class ProductCustomizationToProductDiscount extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customization_to_product_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_customization_id', 'product_discount_id', 'product_id'], 'required'],
            [['product_customization_id', 'product_discount_id', 'product_id'], 'integer'],
            [['product_customization_id', 'product_discount_id'], 'unique', 'targetAttribute' => ['product_customization_id', 'product_discount_id']],
            [['product_customization_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCustomization::className(), 'targetAttribute' => ['product_customization_id' => 'id']],
            [['product_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductDiscount::className(), 'targetAttribute' => ['product_discount_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_customization_id' => 'Product Customization ID',
            'product_discount_id' => 'Product Discount ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomization()
    {
        return $this->hasOne(ProductCustomization::className(), ['id' => 'product_customization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDiscount()
    {
        return $this->hasOne(ProductDiscount::className(), ['id' => 'product_discount_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
