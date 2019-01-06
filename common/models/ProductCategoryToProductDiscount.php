<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category_to_product_discount".
 *
 * @property int $product_category_id
 * @property int $product_discount_id
 *
 * @property ProductCategory $productCategory
 * @property ProductDiscount $productDiscount
 */
class ProductCategoryToProductDiscount extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_to_product_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_category_id', 'product_discount_id'], 'required'],
            [['product_category_id', 'product_discount_id'], 'integer'],
            [['product_category_id', 'product_discount_id'], 'unique', 'targetAttribute' => ['product_category_id', 'product_discount_id']],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['product_category_id' => 'id']],
            [['product_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductDiscount::className(), 'targetAttribute' => ['product_discount_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_category_id' => 'Product Category ID',
            'product_discount_id' => 'Product Discount ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDiscount()
    {
        return $this->hasOne(ProductDiscount::className(), ['id' => 'product_discount_id']);
    }
}
