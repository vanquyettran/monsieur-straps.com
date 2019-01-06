<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_order_to_product".
 *
 * @property int $product_order_id
 * @property int $product_id
 * @property int $product_quantity
 * @property string $product_code
 * @property string $product_name
 * @property int $product_price
 * @property int $product_discounted_price
 *
 * @property Product $product
 * @property ProductOrder $productOrder
 */
class ProductOrderToProduct extends \common\db\MyActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_order_to_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_order_id', 'product_id', 'product_quantity', 'product_code', 'product_name', 'product_price', 'product_discounted_price'], 'required'],
            [['product_order_id', 'product_id', 'product_quantity', 'product_price', 'product_discounted_price'], 'integer'],
            [['product_code', 'product_name'], 'string', 'max' => 255],
            [['product_order_id', 'product_id'], 'unique', 'targetAttribute' => ['product_order_id', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrder::className(), 'targetAttribute' => ['product_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_order_id' => 'Product Order ID',
            'product_id' => 'Product ID',
            'product_quantity' => 'Product Quantity',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'product_price' => 'Product Price',
            'product_discounted_price' => 'Product Discounted Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrder()
    {
        return $this->hasOne(ProductOrder::className(), ['id' => 'product_order_id']);
    }
}
