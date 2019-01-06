<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This is the model class for table "article".
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
 * @property string $banner_caption
 * @property string $banner_font_src
 * @property string $banner_filter
 * @property string $video_src
 * @property string $content
 * @property int $active
 * @property int $visible
 * @property int $featured
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $view_count
 * @property string $published_time
 * @property string $created_time
 * @property string $updated_time
 * @property int $creator_id
 * @property int $updater_id
 * @property int $article_category_id
 * @property int $avatar_image_id
 * @property int $banner_image_id
 *
 * @property ArticleCategory $articleCategory
 * @property Image $avatarImage
 * @property Image $bannerImage
 * @property User $creator
 * @property User $updater
 * @property ArticleToRelatedArticle[] $articleToRelatedArticles
 * @property Article[] $relatedArticles
 * @property ArticleToTag[] $articleToTags
 * @property Tag[] $tags
 * @property MenuItem[] $menuItems
 */
class Article extends \common\db\MyActiveRecord
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
            array_merge(['article/view', UrlParam::SLUG => $this->slug], $params)
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
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'meta_description', 'description', 'content', 'published_time', 'avatar_image_id', 'article_category_id'], 'required'],
            [['content'], 'string'],
            [['published_time'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'avatar_image_id', 'article_category_id'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'banner_filter'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description', 'description', 'video_src'], 'string', 'max' => 511],
            [['name', 'heading', 'page_title', 'meta_title', 'meta_description', 'description'], 'validateAllCaps'],
            [['banner_caption', 'banner_font_src'], 'string', 'max' => 2047],
            [['slug'], 'unique'],
            ['slug', 'validateSlug'],
            [['article_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['article_category_id' => 'id']],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
            [['banner_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['banner_image_id' => 'id']],
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
            'video_src' => 'Video Src',
            'content' => 'Content',
            'active' => 'Active',
            'visible' => 'Visible',
            'featured' => 'Featured',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
            'view_count' => 'View Count',
            'published_time' => 'Published Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'article_category_id' => 'Article Category ID',
            'avatar_image_id' => 'Avatar Image ID',
            'banner_image_id' => 'Banner Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
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
    public function getBannerImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'banner_image_id']);
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
    public function getArticleToRelatedArticles()
    {
        return $this->hasMany(ArticleToRelatedArticle::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'related_article_id'])->viaTable('article_to_related_article', ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleToTags()
    {
        return $this->hasMany(ArticleToTag::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('article_to_tag', ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['article_id' => 'id']);
    }
}
