<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category_to_product_attribute_group".
 *
 * @property int $product_category_id
 * @property int $product_attribute_group_id
 *
 * @property ProductAttributeGroup $productAttributeGroup
 * @property ProductCategory $productCategory
 */
class ProductCategoryToProductAttributeGroup extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_to_product_attribute_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_category_id', 'product_attribute_group_id'], 'required'],
            [['product_category_id', 'product_attribute_group_id'], 'integer'],
            [['product_category_id', 'product_attribute_group_id'], 'unique', 'targetAttribute' => ['product_category_id', 'product_attribute_group_id']],
            [['product_attribute_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttributeGroup::className(), 'targetAttribute' => ['product_attribute_group_id' => 'id']],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['product_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_category_id' => 'Product Category ID',
            'product_attribute_group_id' => 'Product Attribute Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributeGroup()
    {
        return $this->hasOne(ProductAttributeGroup::className(), ['id' => 'product_attribute_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }
}
