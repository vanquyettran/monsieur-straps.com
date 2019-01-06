<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/17/2017
 * Time: 12:22 AM
 */

namespace frontend\models;

use common\models\Image;
use common\models\UrlParam;
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\View;

class SeoInfo extends Model
{
    public $canonicalLink = '';
    public $ampLink = '';

    public $heading = '';
    public $page_title = '';
    public $meta_title = '';
    public $meta_keywords = '';
    public $meta_description = '';
    public $long_description = '';
    public $allow_indexing = '';
    public $allow_following = '';
    public $image_src = '';
    public $image_type = '';
    public $image_width = 0;
    public $image_height = 0;

    public function init()
    {
        parent::init();

        // Canonical & AMP
        $reqParams = Yii::$app->request->get();
        $allCanonicalParams = UrlParam::canonicalParams();
        $canonicalParams = [];
        foreach ($reqParams as $paramName => $paramValue) {
            if (isset($allCanonicalParams[$paramName])) {
                $canonicalParams[$paramName] = $paramValue;
            }
        }
        $this->canonicalLink = Url::to(array_merge([Yii::$app->requestedRoute], $canonicalParams), true);
        $this->canonicalLink = rtrim($this->canonicalLink, '/');

        //Uncomment the following lines to enable AMP pages
        //if (in_array(Yii::$app->requestedRoute, ['site/index', 'article/index', 'article/view', 'article/category'])) {
        //    if (!Yii::$app->params['amp']) {
        //        $this->ampLink = Url::to(array_merge([Yii::$app->requestedRoute], array_merge($canonicalParams, [UrlParam::AMP => 'amp'])), true);
        //    }
        //}
    }

    /**
     * @param array $data
     */
    public function loadAttrValues(Array $data)
    {
        $attrNames = [
            'heading',
            'page_title',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'long_description',
            'allow_indexing',
            'allow_following',
            'image_src',
            'image_type',
            'image_width',
            'image_height',
        ];

        foreach ($attrNames as $attrName) {
            if (isset($data[$attrName])) {
                $this->$attrName = $data[$attrName];
            }
        }
    }

    /**
     * @param Image|null $image
     */
    public function loadFromImageObject($image) {
        if ($image instanceof Image) {
            $this->loadAttrValues([
                'image_src' => $image->getImgSrc(),
                'image_type' => $image->mime_type,
                'image_width' => $image->width,
                'image_height' => $image->height,
            ]);
        }
    }

    /**
     * @param View $view
     */
    public function registerMetaTags($view)
    {
        $view->registerMetaTag([
            'name' => 'description',
            'content' => $this->meta_description
        ], 'description');
        $view->registerMetaTag([
            'name' => 'keywords',
            'content' => $this->meta_keywords
        ], 'keywords');
        $view->registerMetaTag([
            'name' => 'robots',
            'content' => ($this->allow_indexing ? 'index' : 'noindex') . ', ' . ($this->allow_following ? 'follow' : 'nofollow')
        ]);
        $view->registerMetaTag([
            'name' => 'robots',
            'content' => 'NOODP, NOYDIR'
        ]);
        $view->registerMetaTag([
            'name' => 'geo.region',
            'content' => 'VN-HN'
        ], 'geo.region');
        $view->registerMetaTag([
            'name' => 'geo.placename',
            'content' => 'Hà Nội'
        ]);
        $view->registerMetaTag([
            'name' => 'geo.position',
            'content' => '21.033953;105.785002'
        ]);
        $view->registerMetaTag([
            'name' => 'DC.Source',
            'content' => Url::home(true)
        ]);
//        $view->registerMetaTag([
//            'name' => 'DC.language',
//            'scheme' => 'UTF-8',
//            'content' => 'vi'
//        ]);
        $view->registerMetaTag([
            'name' => 'DC.Coverage',
            'content' => 'Việt Nam'
        ]);
        $view->registerMetaTag([
            'name' => 'RATING',
            'content' => 'GENERAL'
        ]);
        $view->registerMetaTag([
            'name' => 'COPYRIGHT',
            'content' => Yii::$app->name
        ]);
//        $view->registerMetaTag([
//            'name' => 'REVISIT-AFTER',
//            'content' => '1 DAYS'
//        ]);
        /** Facebook Meta */
        $view->registerMetaTag([
            'property' => 'fb:app_id',
            'content' => Yii::$app->params['fb_app_id']
        ]);
        $view->registerMetaTag([
            'property' => 'og:type',
            'content' => 'website'
        ]);
        $view->registerMetaTag([
            'property' => 'og:title',
            'content' => $this->meta_title
        ]);
        $view->registerMetaTag([
            'property' => 'og:description',
            'content' => $this->meta_description
        ]);
        $view->registerMetaTag([
            'property' => 'og:url',
            'content' => $this->canonicalLink
        ]);
        if ($this->image_src) {
            $view->registerMetaTag([
                'property' => 'og:image',
                'content' => $this->image_src
            ]);
        }
        if ($this->image_width) {
            $view->registerMetaTag([
                'property' => 'og:image:width',
                'content' => $this->image_width
            ]);
        }
        if ($this->image_height) {
            $view->registerMetaTag([
                'property' => 'og:image:height',
                'content' => $this->image_height
            ]);
        }
        $view->registerMetaTag([
            'property' => 'og:site_name',
            'content' => Yii::$app->name
        ]);
    }

    /**
     * @param View $view
     */
    public function registerLinkTags($view)
    {
        $view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $this->canonicalLink
        ]);
        if ($this->image_src) {
            $view->registerLinkTag([
                'rel' => 'image_src',
                'type' => $this->image_type,
                'href' => $this->image_src
            ]);
        }
        $view->registerLinkTag([
            'rel' => 'shortcut icon',
            'href' => Yii::getAlias('@web/favicon.ico')
        ]);
        if ($this->ampLink) {
            $view->registerLinkTag([
                'rel' => 'amphtml',
                'href' => $this->ampLink
            ]);
        }
    }

}