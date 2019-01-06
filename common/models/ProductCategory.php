<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This is the model class for table "product_category".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $heading
 * @property string $page_title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $long_description
 * @property string $description
 * @property string $banner_caption
 * @property string $banner_font_src
 * @property string $banner_filter
 * @property int $active
 * @property int $visible
 * @property int $featured
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $sort_order
 * @property string $displaying_areas
 * @property int $type
 * @property string $created_time
 * @property string $updated_time
 * @property int $creator_id
 * @property int $updater_id
 * @property int $avatar_image_id
 * @property int $parent_id
 *
 * @property MenuItem[] $menuItems
 * @property Product[] $products
 * @property Image $avatarImage
 * @property User $creator
 * @property ProductCategory $parent
 * @property ProductCategory[] $productCategories
 * @property User $updater
 * @property ProductCategoryToBannerImage[] $productCategoryToBannerImages
 * @property Image[] $bannerImages
 * @property ProductCategoryToProductAttributeGroup[] $productCategoryToProductAttributeGroups
 * @property ProductAttributeGroup[] $productAttributeGroups
 * @property ProductCategoryToProductDiscount[] $productCategoryToProductDiscounts
 * @property ProductDiscount[] $productDiscounts
 */
class ProductCategory extends \common\db\MyActiveRecord
{
    const TYPE_UNKNOWN = 0;
    const TYPE_WALLET = 1;
    const TYPE_BAG = 2;
    const TYPE_BELT = 3;
    const TYPE_WATCHSTRAP = 4;

    const DISPLAYING_AREA__HOME_BODY = 'home_body';
    const DISPLAYING_AREA__ASIDE = 'aside';

    /**
     * @return string[]
     */
    public function getTypeLabels()
    {
        return [
            self::TYPE_UNKNOWN => 'Unknown',
            self::TYPE_WALLET => 'Wallet',
            self::TYPE_BAG => 'Bag',
            self::TYPE_BELT => 'Belt',
            self::TYPE_WATCHSTRAP => 'Watchstrap',
        ];
    }

    /**
     * @return string
     */
    public function typeLabel()
    {
        $typeLabels = $this->getTypeLabels();
        if (isset($typeLabels[$this->type])) {
            return $typeLabels[$this->type];
        } else {
            return "$this->type";
        }
    }

    public static function displayingAreas() {
        return [
            self::DISPLAYING_AREA__HOME_BODY => 'Home Body',
            self::DISPLAYING_AREA__ASIDE => 'Aside',
        ];
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
            array_merge(['product/category', UrlParam::SLUG => $this->slug], $params)
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

    private static $_indexData = null;

    /**
     * @param bool $visibleOnly
     * @return self[]
     */
    public static function indexData($visibleOnly = false)
    {
        if (self::$_indexData === null) {
            self::$_indexData = self::find()
                ->where(['active' => 1])
                ->orderBy('sort_order asc')
                ->indexBy('id')
                ->all();
        }

        if ($visibleOnly) {
            return array_filter(self::$_indexData, function ($item) {
                return 1 == $item->visible;
            });
        }
        return self::$_indexData;
    }

    /**
     * @return \common\db\MyActiveQuery
     */
    public function getAllProducts()
    {
        $allCatIds = [];
        $findChildIds = function (self $category) use (&$findChildIds, &$allCatIds) {
            $allCatIds[] = $category->id;
            foreach ($category->findChildren() as $child) {
                $findChildIds($child);
            }
        };
        $findChildIds($this);

        $query = Product::find();
        $query->where(['in', 'product_category_id', $allCatIds]);
        $query->multiple = true;
        return $query;
    }

    /**
     * @param $slug
     * @return self|null
     */
    public static function findOneBySlug($slug)
    {
        $data = static::indexData();
        foreach ($data as $item) {
            if ($item->slug == $slug) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $types
     * @return self[]
     */
    public static function findAllByTypes($types)
    {
        $result = [];

        $data = static::indexData();
        foreach ($data as $item) {
            if (in_array($item->type, $types)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param $id
     * @return self|null
     */
    public static function findOneById($id)
    {
        $data = static::indexData();
        return isset($data[$id]) ? $data[$id] : null;
    }

    public $_parent = 1;

    /**
     * @return self|null
     */
    public function findParent()
    {
        if ($this->parent_id === null) {
            return null;
        }

        if ($this->_parent === 1) {
            $this->_parent = self::findOneById($this->parent_id);
        }

        return $this->_parent;
    }

    public function findAncestor()
    {
        $parent = $this->findParent();
        if ($parent === null) {
            return $this;
        } else {
            return $parent->findAncestor();
        }
    }

    public $_children = null;

    /**
     * @return self[]
     */
    public function findChildren()
    {
        if ($this->_children === null) {
            $this->_children = [];
            $items = static::indexData();
            foreach ($items as $item) {
                if ($item->parent_id === $this->id) {
                    $this->_children[] = $item;
                }
            }
        }

        return $this->_children;
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
        return 'product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'type', 'sort_order', 'avatar_image_id'], 'required'],
            [['long_description'], 'string'],
            [['active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'sort_order', 'type', 'creator_id', 'updater_id', 'avatar_image_id', 'parent_id'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'banner_filter'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description', 'description'], 'string', 'max' => 511],
            [['name', 'heading', 'page_title', 'meta_title', 'meta_description', 'description'], 'validateAllCaps'],
            [['banner_caption', 'banner_font_src'], 'string', 'max' => 2047],
            [['slug'], 'unique'],
            ['slug', 'validateSlug'],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
            ['displaying_areas', 'each', 'rule' => ['string']],
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
            'long_description' => 'Long Description',
            'description' => 'Description',
            'banner_caption' => 'Banner Caption',
            'active' => 'Active',
            'visible' => 'Visible',
            'featured' => 'Featured',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
            'sort_order' => 'Sort Order',
            'displaying_areas' => 'Displaying Areas',
            'type' => 'Type',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'avatar_image_id' => 'Avatar Image ID',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_category_id' => 'id']);
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
    public function getParent()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['parent_id' => 'id']);
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
    public function getProductCategoryToBannerImages()
    {
        return $this->hasMany(ProductCategoryToBannerImage::className(), ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'banner_image_id'])->viaTable('product_category_to_banner_image', ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategoryToProductAttributeGroups()
    {
        return $this->hasMany(ProductCategoryToProductAttributeGroup::className(), ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributeGroups()
    {
        return $this->hasMany(ProductAttributeGroup::className(), ['id' => 'product_attribute_group_id'])->viaTable('product_category_to_product_attribute_group', ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategoryToProductDiscounts()
    {
        return $this->hasMany(ProductCategoryToProductDiscount::className(), ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDiscounts()
    {
        return $this->hasMany(ProductDiscount::className(), ['id' => 'product_discount_id'])->viaTable('product_category_to_product_discount', ['product_category_id' => 'id']);
    }
}
