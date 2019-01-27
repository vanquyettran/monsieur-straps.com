<?php
use common\models\UrlParam;

return [
    'name' => 'Monsieur Straps',
    'language' => 'vi-VN',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@frontendUrl' => 'http://monsieur-straps.com',
        '@frontendHost' => 'http://monsieur-straps.com',
        '@backendUrl' => 'http://monsieur-straps.com/backend',
        '@backendHost' => 'http://monsieur-straps.com',
        '@imagesUrl' => 'http://monsieur-straps.com/images',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
            'dirMode' => 0777,
        ],
        'frontendUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'sitemap.xml' => 'sitemap/index',
                'sitemap-static-page.xml' => 'sitemap/static-page',
                'sitemap-article-category.xml' => 'sitemap/article-category',
                'sitemap-product-category.xml' => 'sitemap/product-category',
                'sitemap-article-<' . UrlParam::PAGE . ':\d+>.xml' => 'sitemap/article',
                'sitemap-product-<' . UrlParam::PAGE . ':\d+>.xml' => 'sitemap/product',
                'sitemap-tag-<' . UrlParam::PAGE . ':\d+>.xml' => 'sitemap/tag',

                'ghtk-api/get-fee' => 'ghtk-api/get-fee',
                'product/ajax-update-counter' => 'product/ajax-update-counter',
                'article/ajax-update-counter' => 'article/ajax-update-counter',

                '' => 'site/index',
                '/' => 'site/index',
                '<' . UrlParam::SLUG . '>.html' => 'product/view',
                '<' . UrlParam::SLUG . '>' => 'product/category',
                '<' . UrlParam::SLUG . '>/' => 'product/category',
                'article/<' . UrlParam::SLUG . '>.html' => 'article/view',
                'article/<' . UrlParam::SLUG . '>' => 'article/category',
                'article/<' . UrlParam::SLUG . '>/' => 'article/category',
                'tag/<' . UrlParam::SLUG . '>' => 'tag/view',
                'tag/<' . UrlParam::SLUG . '>/' => 'tag/view',
            ],
        ],
        'backendUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '/' => 'site/index',
            ],
        ],
    ],
];
