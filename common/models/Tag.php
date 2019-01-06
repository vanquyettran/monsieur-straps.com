<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This is the model class for table "tag".
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
 * @property int $active
 * @property int $visible
 * @property int $featured
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $sort_order
 * @property string $created_time
 * @property string $updated_time
 * @property int $creator_id
 * @property int $updater_id
 * @property int $avatar_image_id
 *
 * @property Image $avatarImage
 * @property User $creator
 * @property User $updater
 * @property ArticleToTag[] $articleToTags
 * @property Article[] $articles
 * @property ProductToTag[] $productToTags
 * @property Product[] $products
 */
class Tag extends \common\db\MyActiveRecord
{
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
            array_merge(['tag/view', UrlParam::SLUG => $this->slug], $params)
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
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'sort_order'], 'required'],
            [['long_description'], 'string'],
            [['active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'sort_order', 'avatar_image_id'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description'], 'string', 'max' => 511],
            [['name', 'heading', 'page_title', 'meta_title', 'meta_description'], 'validateAllCaps'],
            [['slug'], 'unique'],
            ['slug', 'validateSlug'],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
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
            'active' => 'Active',
            'visible' => 'Visible',
            'featured' => 'Featured',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
            'sort_order' => 'Sort Order',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'avatar_image_id' => 'Avatar Image ID',
        ];
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
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleToTags()
    {
        return $this->hasMany(ArticleToTag::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])->viaTable('article_to_tag', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToTags()
    {
        return $this->hasMany(ProductToTag::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_to_tag', ['tag_id' => 'id']);
    }
}
