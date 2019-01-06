<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_category_to_banner_image".
 *
 * @property int $product_category_id
 * @property int $banner_image_id
 * @property int $sort_order
 *
 * @property Image $bannerImage
 * @property ProductCategory $productCategory
 */
class ProductCategoryToBannerImage extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category_to_banner_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_category_id', 'banner_image_id', 'sort_order'], 'required'],
            [['product_category_id', 'banner_image_id', 'sort_order'], 'integer'],
            [['product_category_id', 'banner_image_id'], 'unique', 'targetAttribute' => ['product_category_id', 'banner_image_id']],
            [['banner_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['banner_image_id' => 'id']],
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
            'banner_image_id' => 'Banner Image ID',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'banner_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }
}
