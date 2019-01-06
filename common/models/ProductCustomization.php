<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_customization".
 *
 * @property int $id
 * @property string $name
 * @property string $details
 * @property int $price
 * @property int $sort_order
 * @property int $featured
 * @property int $available
 * @property int $product_id
 *
 * @property Product $product
 * @property ProductCustomizationToDetailedImage[] $productCustomizationToDetailedImages
 * @property Image[] $detailedImages
 * @property ProductCustomizationToProductAttribute[] $productCustomizationToProductAttributes
 * @property ProductAttribute[] $productAttributes
 * @property ProductCustomizationToProductDiscount[] $productCustomizationToProductDiscounts
 * @property ProductDiscount[] $productDiscounts
 */
class ProductCustomization extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'sort_order', 'product_id'], 'required'],
            [['details'], 'string'],
            [['price', 'sort_order', 'featured', 'available', 'product_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'product_id'], 'unique', 'targetAttribute' => ['name', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'details' => 'Details',
            'price' => 'Price',
            'sort_order' => 'Sort Order',
            'featured' => 'Featured',
            'available' => 'Available',
            'product_id' => 'Product ID',
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
    public function getProductCustomizationToDetailedImages()
    {
        return $this->hasMany(ProductCustomizationToDetailedImage::className(), ['product_customization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailedImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'detailed_image_id'])->viaTable('product_customization_to_detailed_image', ['product_customization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizationToProductAttributes()
    {
        return $this->hasMany(ProductCustomizationToProductAttribute::className(), ['product_customization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['id' => 'product_attribute_id'])->viaTable('product_customization_to_product_attribute', ['product_customization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizationToProductDiscounts()
    {
        return $this->hasMany(ProductCustomizationToProductDiscount::className(), ['product_customization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDiscounts()
    {
        return $this->hasMany(ProductDiscount::className(), ['id' => 'product_discount_id'])->viaTable('product_customization_to_product_discount', ['product_customization_id' => 'id']);
    }
}
