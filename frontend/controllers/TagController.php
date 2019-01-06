<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 2/7/2018
 * Time: 8:18 AM
 */

namespace frontend\controllers;


use common\models\UrlParam;
use frontend\models\ArticleCategory;
use frontend\models\Tag;
use Yii;
use yii\web\NotFoundHttpException;

class TagController extends BaseController
{
    public function actionView()
    {
        $tag = Tag::findOne(['slug' => Yii::$app->request->get(UrlParam::SLUG)]);

        if (!$tag) {
            throw new NotFoundHttpException();
        }

        $this->seoInfo->loadAttrValues($tag->attributes);
        $this->seoInfo->loadFromImageObject($tag->avatarImage);

        $page = 1;

        $articles = ArticleController::findModels($tag->getArticles(), $page);

        $hasMore = isset($articles[ArticleController::PAGE_SIZE]);

        $articles = array_slice($articles, 0, ArticleController::PAGE_SIZE);

        $jsonParams = json_encode([ 'tag_id' => $tag->id ]);

        return $this->render('view', compact(
            'tag',
            'articles',
            'jsonParams',
            'page',
            'hasMore'
        ));
    }
}