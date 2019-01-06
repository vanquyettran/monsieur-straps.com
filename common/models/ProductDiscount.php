<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_discount".
 *
 * @property int $id
 * @property string $title
 * @property string $occasion
 * @property int $percentage
 * @property string $start_time
 * @property string $end_time
 *
 * @property ProductCategoryToProductDiscount[] $productCategoryToProductDiscounts
 * @property ProductCategory[] $productCategories
 * @property ProductCustomizationToProductDiscount[] $productCustomizationToProductDiscounts
 * @property ProductCustomization[] $productCustomizations
 * @property ProductToProductDiscount[] $productToProductDiscounts
 * @property Product[] $products
 */
class ProductDiscount extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'percentage', 'start_time', 'end_time'], 'required'],
            [['occasion'], 'string'],
            [['percentage'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'occasion' => 'Occasion',
            'percentage' => 'Percentage',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategoryToProductDiscounts()
    {
        return $this->hasMany(ProductCategoryToProductDiscount::className(), ['product_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'product_category_id'])->viaTable('product_category_to_product_discount', ['product_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizationToProductDiscounts()
    {
        return $this->hasMany(ProductCustomizationToProductDiscount::className(), ['product_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizations()
    {
        return $this->hasMany(ProductCustomization::className(), ['id' => 'product_customization_id'])->viaTable('product_customization_to_product_discount', ['product_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToProductDiscounts()
    {
        return $this->hasMany(ProductToProductDiscount::className(), ['product_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_to_product_discount', ['product_discount_id' => 'id']);
    }
}
