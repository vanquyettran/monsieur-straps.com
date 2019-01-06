<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/16/2017
 * Time: 8:25 AM
 */

namespace frontend\controllers;


use common\models\UrlParam;
use frontend\models\Article;
use frontend\models\ArticleCategory;
use frontend\models\Product;
use frontend\models\Tag;
use SimpleXMLElement;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SitemapController extends Controller
{
    const PAGE_SIZE = 500;

    /**
     * Render sitemap index
     * @return string
     */
    public function actionIndex()
    {
        $lastmod = date('c', 360 * floor(time() / 360));

        $sitemaps = [];

        $sitemaps[] = [
            'loc' => Url::to(['static-page'], true),
            'lastmod' => $lastmod
        ];

        // article category sitemap
        $sitemaps[] = [
            'loc' => Url::to(['article-category'], true),
            'lastmod' => $lastmod
        ];

        // article sitemaps
        $articles_number = Article::find()->where(['active' => 1, 'allow_indexing' => 1])->count(); // include invisible articles, only require active
        $article_sitemaps_number = ceil($articles_number / self::PAGE_SIZE);
        for ($i = 0; $i < $article_sitemaps_number; $i++) {
            $sitemaps[] = [
                'loc' => Url::to(['article', UrlParam::PAGE => $i + 1], true),
                'lastmod' => $lastmod
            ];
        }

        // product sitemaps
        $products_number = Product::find()->where(['active' => 1, 'allow_indexing' => 1])->count(); // include invisible products, only require active
        $product_sitemaps_number = ceil($products_number / self::PAGE_SIZE);
        for ($i = 0; $i < $product_sitemaps_number; $i++) {
            $sitemaps[] = [
                'loc' => Url::to(['product', UrlParam::PAGE => $i + 1], true),
                'lastmod' => $lastmod
            ];
        }

        // tag sitemaps
        $tags_number = Tag::find()->where(['active' => 1, 'allow_indexing' => 1])->count(); // include invisible tags, only require active
        $tag_sitemaps_number = ceil($tags_number / self::PAGE_SIZE);
        for ($i = 0; $i < $tag_sitemaps_number; $i++) {
            $sitemaps[] = [
                'loc' => Url::to(['tag', UrlParam::PAGE => $i + 1], true),
                'lastmod' => $lastmod
            ];
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');

        // function call to convert array to xml
        self::array_to_xml($sitemaps, $xml_data, 'sitemap');

        //saving generated xml file;
        return $xml_data->saveXML();
    }

    /**
     * Render sitemap for the static pages
     * @return string
     */
    public function actionStaticPage()
    {
        $urls = [
            'homePage' => [
                'loc' => Url::home(true),
                'lastmod' => date('c', 360 * floor(time() / 360)),
                'priority' => '1.0',
                'changefreq' => 'hourly',
            ]
        ];

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // function call to convert array to xml
        self::array_to_xml($urls, $xml_data, 'url');

        //saving generated xml file;
        return $xml_data->saveXML();

    }

    /**
     * Render sitemap for the article categories
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionArticleCategory()
    {
        /**
         * @var $models ArticleCategory[]
         */
        $models = ArticleCategory::find()
            ->where(['active' => 1, 'allow_indexing' => 1])
            ->orderBy('updated_time desc')
            ->all();

        if (empty($models)) {
            throw new NotFoundHttpException();
        }

        $urls = [];

        foreach ($models as $model) {
            $url = [
                'loc' => $model->viewUrl([], true),
                'lastmod' => date('c', 360 * floor(strtotime($model->updated_time) / 360)),
                'changefreq' => 'hourly',
                'priority' => '0.8',
            ];
//            $image = $model->avatarImage;
//            if ($image) {
//                $url['image'] = [
//                    'image:loc' => $image->getSource(),
//                    'image:title' => Html::encode($image->name),
//                    'image:caption' => Html::encode($image->name)
//                ];
//            }
            $urls[] = $url;
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"></urlset>');

        // function call to convert array to xml
        self::array_to_xml($urls, $xml_data, 'url');

        //saving generated xml file;
        return $xml_data->saveXML();
    }

    /**
     * Render sitemap for the articles
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionArticle()
    {
        $page = Yii::$app->request->get(UrlParam::PAGE);

        if (!is_numeric($page) || $page < 1) {
            throw new NotFoundHttpException();
        }

        /**
         * @var $models Article[]
         */
        $models = Article::find()
            ->where(['active' => 1, 'allow_indexing' => 1])
            ->orderBy('updated_time desc')
            ->offset(($page - 1) * self::PAGE_SIZE)
            ->limit(self::PAGE_SIZE)
            ->all();

        if (empty($models)) {
            throw new NotFoundHttpException();
        }

        $urls = [];

        foreach ($models as $model) {
            $url = [
                'loc' => $model->viewUrl([], true),
                'lastmod' => date('c', 360 * floor(strtotime($model->updated_time) / 360)),
                'changefreq' => 'hourly',
                'priority' => '0.6',
            ];
//            $image = $model->avatarImage;
//            if ($image) {
//                $url['image'] = [
//                    'image:loc' => $image->getSource(),
//                    'image:title' => Html::encode($image->name),
//                    'image:caption' => Html::encode($image->name)
//                ];
//            }
            $urls[] = $url;
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"></urlset>');

        // function call to convert array to xml
        self::array_to_xml($urls, $xml_data, 'url');

        //saving generated xml file;
        return $xml_data->saveXML();
    }

    /**
     * Render sitemap for the products
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProduct()
    {
        $page = Yii::$app->request->get(UrlParam::PAGE);

        if (!is_numeric($page) || $page < 1) {
            throw new NotFoundHttpException();
        }

        /**
         * @var $models Product[]
         */
        $models = Product::find()
            ->where(['active' => 1, 'allow_indexing' => 1])
            ->orderBy('updated_time desc')
            ->offset(($page - 1) * self::PAGE_SIZE)
            ->limit(self::PAGE_SIZE)
            ->all();

        if (empty($models)) {
            throw new NotFoundHttpException();
        }

        $urls = [];

        foreach ($models as $model) {
            $url = [
                'loc' => $model->viewUrl([], true),
                'lastmod' => date('c', 360 * floor(strtotime($model->updated_time) / 360)),
                'changefreq' => 'hourly',
                'priority' => '0.6',
            ];
//            $image = $model->avatarImage;
//            if ($image) {
//                $url['image'] = [
//                    'image:loc' => $image->getSource(),
//                    'image:title' => Html::encode($image->name),
//                    'image:caption' => Html::encode($image->name)
//                ];
//            }
            $urls[] = $url;
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"></urlset>');

        // function call to convert array to xml
        self::array_to_xml($urls, $xml_data, 'url');

        //saving generated xml file;
        return $xml_data->saveXML();
    }

    /**
     * Render sitemap for the tags
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTag()
    {
        $page = Yii::$app->request->get(UrlParam::PAGE);

        if (!is_numeric($page) || $page < 1) {
            throw new NotFoundHttpException();
        }

        /**
         * @var $models Tag[]
         */
        $models = Tag::find()
            ->where(['active' => 1, 'allow_indexing' => 1])
            ->orderBy('updated_time desc')
            ->offset(($page - 1) * self::PAGE_SIZE)
            ->limit(self::PAGE_SIZE)
            ->all();

        if (empty($models)) {
            throw new NotFoundHttpException();
        }

        $urls = [];

        foreach ($models as $model) {
            $url = [
                'loc' => $model->viewUrl([], true),
                'lastmod' => date('c', 360 * floor(strtotime($model->updated_time) / 360)),
                'changefreq' => 'hourly',
                'priority' => '0.6',
            ];
//            $image = $model->avatarImage;
//            if ($image) {
//                $url['image'] = [
//                    'image:loc' => $image->getSource(),
//                    'image:title' => Html::encode($image->name),
//                    'image:caption' => Html::encode($image->name)
//                ];
//            }
            $urls[] = $url;
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');
        $this->layout = false;

        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"></urlset>');

        // function call to convert array to xml
        self::array_to_xml($urls, $xml_data, 'url');

        //saving generated xml file;
        return $xml_data->saveXML();
    }



    /**
     * @param $data array
     * @param $xml_data SimpleXMLElement
     * @param $item_key string
     */
    private static function array_to_xml( $data, &$xml_data, $item_key = '') {
        foreach ( $data as $key => $value ) {
            //dealing with <0/>..<n/> issues
            if ( $item_key ) {
                $key = $item_key;
            } else {
                if ( is_numeric($key) ) {
                    $key = "item$key";
                }
            }
            if ( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                $has_num_key = false;
                foreach ($value as $value_key => $value_value) {
                    if ( is_numeric($value_key) ) {
                        $has_num_key = true;
                        break;
                    }
                }
                if ( $has_num_key ) {
                    self::array_to_xml($value, $subnode, Inflector::singularize($key));
                } else {
                    self::array_to_xml($value, $subnode);
                }
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}