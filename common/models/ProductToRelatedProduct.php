<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_to_related_product".
 *
 * @property int $product_id
 * @property int $related_product_id
 *
 * @property Product $product
 * @property Product $relatedProduct
 */
class ProductToRelatedProduct extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_related_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'related_product_id'], 'required'],
            [['product_id', 'related_product_id'], 'integer'],
            [['product_id', 'related_product_id'], 'unique', 'targetAttribute' => ['product_id', 'related_product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['related_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['related_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'related_product_id' => 'Related Product ID',
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
    public function getRelatedProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'related_product_id']);
    }
}
