<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "static_page_info".
 *
 * @property int $id
 * @property string $name
 * @property string $route
 * @property string $url_pattern
 * @property string $heading
 * @property string $page_title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $long_description
 * @property int $active
 * @property int $allow_indexing
 * @property int $allow_following
 * @property int $avatar_image_id
 *
 * @property Image $avatarImage
 */
class StaticPageInfo extends \common\db\MyActiveRecord
{
    const ROUTE_HOME = 'site/index';

    /**
     * @return string[]
     */
    public function getRouteLabels()
    {
        return [
            self::ROUTE_HOME => 'Home',
        ];
    }

    /**
     * @return string
     */
    public function routeLabel()
    {
        $routeLabels = $this->getRouteLabels();
        if (isset($routeLabels[$this->route])) {
            return $routeLabels[$this->route];
        } else {
            return $this->route;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_page_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'route'], 'required'],
            [['long_description'], 'string'],
            [['active', 'allow_indexing', 'allow_following', 'avatar_image_id'], 'integer'],
            [['name', 'url_pattern', 'route', 'heading', 'page_title', 'meta_title'], 'string', 'max' => 255],
            [['meta_keywords', 'meta_description'], 'string', 'max' => 511],
            [['route', 'url_pattern'], 'unique', 'targetAttribute' => ['route', 'url_pattern']],
            [['avatar_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['avatar_image_id' => 'id']],
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
            'route' => 'Route',
            'url_pattern' => 'URL Pattern',
            'heading' => 'Heading',
            'page_title' => 'Page Title',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'long_description' => 'Long Description',
            'active' => 'Active',
            'allow_indexing' => 'Allow Indexing',
            'allow_following' => 'Allow Following',
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
}
