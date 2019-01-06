<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property int $sort_order
 * @property int $illustration_image_id
 * @property int $product_attribute_group_id
 *
 * @property Image $illustrationImage
 * @property ProductAttributeGroup $productAttributeGroup
 * @property ProductCustomizationToProductAttribute[] $productCustomizationToProductAttributes
 * @property ProductCustomization[] $productCustomizations
 * @property ProductToProductAttribute[] $productToProductAttributes
 * @property Product[] $products
 */
class ProductAttribute extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'sort_order', 'product_attribute_group_id'], 'required'],
            [['sort_order', 'illustration_image_id', 'product_attribute_group_id'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
            [['illustration_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['illustration_image_id' => 'id']],
            [['product_attribute_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttributeGroup::className(), 'targetAttribute' => ['product_attribute_group_id' => 'id']],
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
            'value' => 'Value',
            'sort_order' => 'Sort Order',
            'illustration_image_id' => 'Illustration Image ID',
            'product_attribute_group_id' => 'Product Attribute Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'illustration_image_id']);
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
    public function getProductCustomizationToProductAttributes()
    {
        return $this->hasMany(ProductCustomizationToProductAttribute::className(), ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizations()
    {
        return $this->hasMany(ProductCustomization::className(), ['id' => 'product_customization_id'])->viaTable('product_customization_to_product_attribute', ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToProductAttributes()
    {
        return $this->hasMany(ProductToProductAttribute::className(), ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_to_product_attribute', ['product_attribute_id' => 'id']);
    }
}
