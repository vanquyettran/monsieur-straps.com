<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_to_product_discount".
 *
 * @property int $product_id
 * @property int $product_discount_id
 *
 * @property ProductDiscount $productDiscount
 * @property Product $product
 */
class ProductToProductDiscount extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_product_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_discount_id'], 'required'],
            [['product_id', 'product_discount_id'], 'integer'],
            [['product_id', 'product_discount_id'], 'unique', 'targetAttribute' => ['product_id', 'product_discount_id']],
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
            'product_id' => 'Product ID',
            'product_discount_id' => 'Product Discount ID',
        ];
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
