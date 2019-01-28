<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $heading
 * @property string $page_title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $description
 * @property string $code
 * @property int $price
 * @property string $long_description
 * @property string $details
 * @property int $active
 * @property int $visible
 * @property int $featured
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $production_status
 * @property int $sort_order
 * @property string $published_time
 * @property string $created_time
 * @property string $updated_time
 * @property int $view_count
 * @property int $creator_id
 * @property int $updater_id
 * @property int $avatar_image_id
 * @property int $product_category_id
 *
 * @property MenuItem[] $menuItems
 * @property Image $avatarImage
 * @property User $creator
 * @property ProductCategory $productCategory
 * @property User $updater
 * @property ProductCustomization[] $productCustomizations
 * @property ProductCustomizationToProductDiscount[] $productCustomizationToProductDiscounts
 * @property ProductToDetailedImage[] $productToDetailedImages
 * @property Image[] $detailedImages
 * @property ProductToProductAttribute[] $productToProductAttributes
 * @property ProductAttribute[] $productAttributes
 * @property ProductToProductDiscount[] $productToProductDiscounts
 * @property ProductDiscount[] $productDiscounts
 * @property ProductToRelatedProduct[] $productToRelatedProducts
 * @property ProductToRelatedProduct[] $productToRelatedProducts0
 * @property Product[] $relatedProducts
 * @property Product[] $products
 * @property ProductToTag[] $productToTags
 * @property Tag[] $tags
 */
class Product extends \common\db\MyActiveRecord
{
    const PRODUCTION_STATUS__AVAILABLE = 1;
    const PRODUCTION_STATUS__PREORDER = 2;
    const PRODUCTION_STATUS__SOLD_OUT = 3;

    /**
     * @return string[]
     */
    public function getProductionStatusLabels()
    {
        return [
            self::PRODUCTION_STATUS__AVAILABLE => 'Available',
            self::PRODUCTION_STATUS__PREORDER => 'Pre-order',
            self::PRODUCTION_STATUS__SOLD_OUT => 'Sold out',
        ];
    }

    /**
     * @return string
     */
    public function productionStatusLabel()
    {
        $productionStatusLabels = $this->getProductionStatusLabels();
        if (isset($productionStatusLabels[$this->production_status])) {
            return $productionStatusLabels[$this->production_status];
        } else {
            return "$this->production_status";
        }
    }

    /**
     * @param $params array
     * @param bool $schema
     * @return string
     */
    public function viewUrl($params = [], $schema = true)
    {
        if (Yii::$app->params['amp']) {
            $params[UrlParam::AMP] = 'amp';
        }

        /**
         * @var $urlMng UrlManager
         */
        $urlMng = Yii::$app->frontendUrlManager;

        return ($schema ? Yii::getAlias('@frontendHost') : '') . $urlMng->createUrl(
            array_merge(['product/view', UrlParam::SLUG => $this->slug], $params)
        );
    }

    /**
     * @param string|null $text
     * @param array $options
     * @return string
     */
    public function viewAnchor($text = null, $options = [])
    {
        if (!isset($options['title'])) {
            $options['title'] = $this->name;
        }
        return Html::a($text === null ? $this->name : $text, $this->viewUrl(), $options);
    }

    /**
     * @param string|null $size
     * @param array $options
     * @return string
     */
    public function avatarImg($size = null, $options = [])
    {
        if ($this->avatarImage) {
            return $this->avatarImage->img($size, $options);
        }
        return '';
    }

    public function formatPrice($price)
    {
        return '$' . number_format($price, 2, '.', ',');
    }

    public function totalDiscountPercentage()
    {
        $discounts = $this->productDiscounts;
        $percentage_total = 0;
        foreach ($discounts as $discount) {
            $percentage_total += $discount->percentage;
        }
        return $percentage_total;
    }

    public function discountedPrice()
    {
        if ($this->price > 0) {
            return $this->price - $this->price * $this->totalDiscountPercentage() / 100;
        }

        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'meta_description', 'description', 'code', 'price', 'production_status', 'published_time', 'avatar_image_id', 'product_category_id'], 'required'],
            [['price', 'active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'production_status', 'view_count', 'avatar_image_id', 'product_category_id'], 'integer'],
            [['long_description', 'details'], 'string'],
            [['published_time'], 'safe'],
            [['name', 'slug', 'code', 'heading', 'page_title', 'meta_title'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description', 'description'], 'string', 'max' => 511],
            [['name', 'heading', 'page_title', 'meta_title', 'meta_description', 'description'], 'validateAllCaps'],
            [['slug', 'code'], 'unique'],
            ['slug', 'validateSlug'],
            ['price', 'number', 'min' => 1],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['product_category_id' => 'id']],
        ];
    }

    public function validateAllCaps($attr, $params, $validator) {
        if ($this->$attr === mb_strtoupper($this->$attr) || $this->$attr === mb_strtolower($this->$attr)) {
            $this->addError($attr, $this->getAttributeLabel($attr) . ' must contain both lowercase and uppercase letters');
        }
    }

    public function validateSlug($attr, $params, $validator) {
        if(!preg_match("/^[0-9a-z-]+$/", $this->$attr)) {
            $this->addError($attr, $this->getAttributeLabel($attr) . ' invalid');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'heading' => 'Heading',
            'page_title' => 'Page Title',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'description' => 'Description',
            'code' => 'Code',
            'price' => 'Price',
            'long_description' => 'Long Description',
            'details' => 'Details',
            'active' => 'Active',
            'visible' => 'Visible',
            'featured' => 'Featured',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
            'production_status' => 'Production Status',
            'sort_order' => 'Sort Order',
            'published_time' => 'Published Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'view_count' => 'View Count',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'avatar_image_id' => 'Avatar Image ID',
            'product_category_id' => 'Product Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'avatar_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
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
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizations()
    {
        return $this->hasMany(ProductCustomization::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCustomizationToProductDiscounts()
    {
        return $this->hasMany(ProductCustomizationToProductDiscount::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToDetailedImages()
    {
        return $this->hasMany(ProductToDetailedImage::className(), ['product_id' => 'id'])->orderBy('sort_order ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailedImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'detailed_image_id'])->viaTable('product_to_detailed_image', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToProductAttributes()
    {
        return $this->hasMany(ProductToProductAttribute::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['id' => 'product_attribute_id'])->viaTable('product_to_product_attribute', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToProductDiscounts()
    {
        return $this->hasMany(ProductToProductDiscount::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDiscounts()
    {
        return $this->hasMany(ProductDiscount::className(), ['id' => 'product_discount_id'])->viaTable('product_to_product_discount', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToRelatedProducts()
    {
        return $this->hasMany(ProductToRelatedProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToRelatedProducts0()
    {
        return $this->hasMany(ProductToRelatedProduct::className(), ['related_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'related_product_id'])->viaTable('product_to_related_product', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_to_related_product', ['related_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToTags()
    {
        return $this->hasMany(ProductToTag::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('product_to_tag', ['product_id' => 'id']);
    }
}
