<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 1/8/2018
 * Time: 2:54 PM
 */

namespace frontend\controllers;

use common\db\MyActiveQuery;
use common\models\MenuItem;
use common\models\UrlParam;
use frontend\models\Article;
use frontend\models\ArticleCategory;
use frontend\models\Tag;
use Guzzle\Http\Url;
use Yii;
use yii\db\ActiveQuery;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class ArticleController extends BaseController
{
    const PAGE_SIZE = 20;

    public function actionView()
    {
        $model = self::findModel(Yii::$app->request->get(UrlParam::SLUG));

        // @TODO: Check whether Requested Url is same as Correct Url or not.
        // @TODO: If not, redirect to Correct Url.
        $this->cmpUrlsAndRedirectIfNotEqual($model->viewUrl());

        $model->templateToHtml([ 'content' ]);

        $this->seoInfo->loadAttrValues($model->attributes);
        $this->seoInfo->loadFromImageObject($model->avatarImage);

        $category = $model->articleCategory;
        if ($category) {
            $modelType = $category->type;

            //@TODO: Active corresponding menu item

            $notInTopMenu = 0 === count(array_filter($model->menuItems, function ($item) {
                /**
                 * @var $item MenuItem
                 */
                return $item->menu_id === MenuItem::MENU_ID_TOP;
            }));

            $notInBottomMenu = 0 === count(array_filter($model->menuItems, function ($item) {
                /**
                 * @var $item MenuItem
                 */
                return $item->menu_id === MenuItem::MENU_ID_BOTTOM;
            }));

            if ($notInTopMenu) {
                foreach (MenuItem::indexData(MenuItem::MENU_ID_TOP) as $menuItem) {
                    if ($menuItem->article_category_id === $category->id) {
                        foreach ($this->menu->data as $item) {
                            if (strpos($item->key, $menuItem->getMenuKey()) !== false) {
                                $this->menu->activeItemKey = $item->key;
                            }
                        }
                    }
                }
            }

            if ($notInBottomMenu) {
                foreach (MenuItem::indexData(MenuItem::MENU_ID_BOTTOM) as $menuItem) {
                    if ($menuItem->article_category_id === $category->id) {
                        foreach ($this->bottomMenu->data as $item) {
                            if (strpos($item->key, $menuItem->getMenuKey()) !== false) {
                                $this->bottomMenu->activeItemKey = $item->key;
                            }
                        }
                    }
                }
            }
        } else {
            $modelType = null;
        }

        if ($modelType === ArticleCategory::TYPE_INFO) {
            if ($orderCode = Yii::$app->request->get(UrlParam::ORDER_CODE)) {
                $template = 'XXX';
                $model->content = str_replace($template, $orderCode, $model->content);
            }
        }

        $relatedItems = self::findModels($model->getRelatedArticles());

        return $this->render('view', compact('model', 'modelType', 'relatedItems'));
    }

    public function actionCategory()
    {
        $category = ArticleCategory::findOneBySlug(Yii::$app->request->get(UrlParam::SLUG));

        if (!$category) {
            throw new NotFoundHttpException();
        }

        // @TODO: Check whether Requested Url is same as Correct Url or not.
        // @TODO: If not, redirect to Correct Url.
        $this->cmpUrlsAndRedirectIfNotEqual($category->viewUrl());

        $category->templateToHtml([ 'introduction', 'long_description' ]);

        $this->seoInfo->loadAttrValues($category->attributes);
        $this->seoInfo->loadFromImageObject($category->avatarImage);

        $page = 1;

        $articles = self::findModels($category->getAllArticles(), $page);

        $hasMore = isset($articles[static::PAGE_SIZE]);

        $articles = array_slice($articles, 0, static::PAGE_SIZE);

        $queryParams = ['ancestor_article_category_id' => $category->id];

        return $this->render('category', compact(
            'category',
            'articles',
            'queryParams',
            'page',
            'hasMore'
        ));
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionAjaxGetItems()
    {
        $this->layout = false;

        if (!Yii::$app->request->isPost) {
            throw new BadRequestHttpException();
        }

        $viewId = Yii::$app->request->getBodyParam(UrlParam::VIEW_ID);

        if (!in_array($viewId, ['_simpleList', '_thumbnailList'])) {
            throw new BadRequestHttpException('Invalid view_id');
        }

        $viewParams = Yii::$app->request->getBodyParam(UrlParam::VIEW_PARAMS);
        $viewParams = @json_decode($viewParams, true);

        if (!is_array($viewParams)) {
            throw new BadRequestHttpException('`viewParams` must be an array.');
        }

        $queryParams = Yii::$app->request->getBodyParam(UrlParam::QUERY_PARAMS);
        $queryParams = @json_decode($queryParams, true);

        if (is_array($queryParams)) {
            try {
                $query = null;

                if (isset($queryParams['ancestor_article_category_id'])) {
                    $articleCategory = ArticleCategory::findOne($queryParams['ancestor_article_category_id']);
                    if ($articleCategory) {
                        $query = $articleCategory->getAllArticles();
                    } else {
                        throw new BadRequestHttpException('`ArticleCategory` not found.');
                    }
                    unset($queryParams['ancestor_article_category_id']);
                }

                if (isset($queryParams['tag_id'])) {
                    $tag = Tag::findOne($queryParams['tag_id']);
                    if ($tag) {
                        $query = $tag->getArticles();
                    } else {
                        throw new BadRequestHttpException('`Tag` not found.');
                    }
                    unset($queryParams['tag_id']);
                }

                if (null === $query) {
                    $query = Article::find();
                }

                $query->andWhere($queryParams);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        } else {
            throw new BadRequestHttpException('`queryParams` must be an array.');
        }

        $page = (int) Yii::$app->request->getBodyParam(UrlParam::PAGE, 1);

        $models = self::findModels($query, $page);

        $hasMore = isset($models[static::PAGE_SIZE]);

        $models = array_slice($models, 0, self::PAGE_SIZE);

        $content = $this->render($viewId, array_merge(compact('models'), $viewParams));

        return json_encode(compact('content', 'page', 'hasMore'));
    }

    public function actionAjaxUpdateCounter()
    {
        if (!Yii::$app->request->isPost) {
            return '';
        }
        $field = Yii::$app->request->getBodyParam(UrlParam::FIELD);
        $value = (int) Yii::$app->request->getBodyParam(UrlParam::VALUE, 1);
        $id = (int) Yii::$app->request->getBodyParam(UrlParam::ID);
        if (!in_array($field, ['view_count', 'comment_count', 'like_count', 'share_count'])) {
            throw new BadRequestHttpException();
        }
        $table = Article::tableName();
        $query = Yii::$app->db
            ->createCommand("UPDATE `$table` SET `$field` = (IFNULL(`$field`, 0) + :value) WHERE `id` = :id")
            ->bindValues([':value' => $value, ':id' => $id])
            ->execute();
        return !!$query;
    }


    /**
     * @param $slug
     * @return Article
     * @throws NotFoundHttpException
     */
    private static function findModel($slug) {
        $model = Article::findOne([
            'active' => 1,
            'slug' => $slug
        ]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * @param ActiveQuery $query
     * @param int $page
     * @param int $page_size
     * @return Article[]
     */
    public static function findModels($query, $page = 1, $page_size = self::PAGE_SIZE)
    {
        return $query
            ->andWhere(['active' => 1, 'visible' => 1])
            ->andWhere(['<', 'published_time', date('Y-m-d H:i:s', floor(time() / 180) * 180)])
            ->orderBy('published_time desc')
            ->limit($page_size + 1)
            ->offset(($page - 1) * $page_size)
            ->all();
    }
}