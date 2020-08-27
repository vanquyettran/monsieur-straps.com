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
use common\models\ProductAttributeGroup;
use common\models\UrlParam;
use frontend\models\Product;
use frontend\models\ProductCategory;
use frontend\models\Tag;
use Yii;
use yii\db\ActiveQuery;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class ProductController extends BaseController
{
    const PAGE_SIZE = 18;

    public function actionView()
    {
        $model = self::findModel(Yii::$app->request->get(UrlParam::SLUG));
        $model->templateToHtml([ 'long_description', 'details' ]);

        $this->seoInfo->loadAttrValues($model->attributes);
        $this->seoInfo->loadFromImageObject($model->avatarImage);

        $category = $model->productCategory;
        if ($category) {
            $modelType = $category->type;

            //@TODO: Active corresponding menu item

            $notInTopMenu = empty(array_filter($model->menuItems, function ($item) {
                /**
                 * @var $item MenuItem
                 */
                return $item->menu_id === MenuItem::MENU_ID_TOP;
            }));

            $notInBottomMenu = empty(array_filter($model->menuItems, function ($item) {
                /**
                 * @var $item MenuItem
                 */
                return $item->menu_id === MenuItem::MENU_ID_BOTTOM;
            }));

            if ($notInTopMenu) {
                foreach (MenuItem::indexData(MenuItem::MENU_ID_TOP) as $menuItem) {
                    if ($menuItem->product_category_id === $category->id) {
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
                    if ($menuItem->product_category_id === $category->id) {
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

        $relatedItems = self::findModels($model->getRelatedProducts());

        return $this->render('view', compact('model', 'modelType', 'relatedItems'));
    }

    public function actionCategory()
    {
        $category = ProductCategory::findOneBySlug(Yii::$app->request->get(UrlParam::SLUG));

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $this->seoInfo->loadAttrValues($category->attributes);
        $this->seoInfo->loadFromImageObject($category->avatarImage);

        $page = 1;

        $query = $category->getAllProducts();

        $groupedAttributes = [];
        $attributeGroups = $category->getProductAttributeGroups()->orderBy('sort_order asc')->all();
        foreach ($attributeGroups as $attrGroup) {
            /**
             * @var ProductAttributeGroup $attrGroup
             */
            $groupedAttributes[$attrGroup->id] = Yii::$app->request->get('group_' . $attrGroup->id, []);
        }

        foreach ($groupedAttributes as $group_id => $attribute_ids) {
            if (count($attribute_ids)) {
                $query->innerJoinWith(['productToProductAttributes' =>
                    function ($query) use ($attribute_ids, $group_id) {
                        $query->from("product_to_product_attribute junction_$group_id")
                            ->andWhere(['IN', "junction_$group_id.product_attribute_id", $attribute_ids]);
                    }
                ]);
            }
        }

        $sort = Yii::$app->request->get('sort');
        $query->orderBy($this->getOrderBy($sort));

        $products = self::findModels($query, $page);

        $hasMore = isset($products[static::PAGE_SIZE]);

        $products = array_slice($products, 0, static::PAGE_SIZE);

        $jsonParams = json_encode([
            'ancestor_product_category_id' => $category->id,
            'sort' => $sort
        ]);

        return $this->render('category', compact(
            'category',
            'products',
            'attributeGroups',
            'groupedAttributes',
            'sort',
            'jsonParams',
            'page',
            'hasMore'
        ));
    }

    private function getOrderBy($sort) {
        switch ($sort) {
            case 'price_down':
                return 'price desc';
            case 'price_up':
                return 'price asc';
            case 'recently':
            default:
                return 'published_time desc';
        }
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
            throw new BadRequestHttpException();
        }

        $jsonParams = Yii::$app->request->getBodyParam(UrlParam::JSON_PARAMS);
        $jsonParams = @json_decode($jsonParams, true);


        if (is_array($jsonParams)) {
            try {
                $query = null;

                if (isset($jsonParams['ancestor_product_category_id'])) {
                    $productCategory = ProductCategory::findOne($jsonParams['ancestor_product_category_id']);
                    if ($productCategory) {
                        $query = $productCategory->getAllProducts();
                    } else {
                        throw new BadRequestHttpException('`ProductCategory` not found.');
                    }
                    unset($jsonParams['ancestor_product_category_id']);
                }

                if (isset($jsonParams['tag_id'])) {
                    $tag = Tag::findOne($jsonParams['tag_id']);
                    if ($tag) {
                        $query = $tag->getProducts();
                    } else {
                        throw new BadRequestHttpException('`Tag` not found.');
                    }
                    unset($jsonParams['tag_id']);
                }

                if (null === $query) {
                    $query = Product::find();
                }

                $sort = null;
                if (isset($jsonParams['sort'])) {
                    $sort = $jsonParams['sort'];
                    unset($jsonParams['sort']);
                }
                $query->orderBy($this->getOrderBy($sort));

                $query->andWhere($jsonParams);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        } else {
            throw new BadRequestHttpException('`jsonParams` must be an array.');
        }

        $page = (int) Yii::$app->request->getBodyParam(UrlParam::PAGE, 1);

        $models = self::findModels($query, $page);

        $hasMore = isset($models[static::PAGE_SIZE]);

        $models = array_slice($models, 0, self::PAGE_SIZE);

        $content = $this->render($viewId, compact('models'));

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
        $table = Product::tableName();
        $query = Yii::$app->db
            ->createCommand("UPDATE `$table` SET `$field` = (IFNULL(`$field`, 0) + :value) WHERE `id` = :id")
            ->bindValues([':value' => $value, ':id' => $id])
            ->execute();
        return !!$query;
    }


    /**
     * @param $slug
     * @return Product
     * @throws NotFoundHttpException
     */
    private static function findModel($slug) {
        $model = Product::findOne([
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
     * @return Product[]
     */
    public static function findModels($query, $page = 1)
    {
        return $query
            ->andWhere(['active' => 1, 'visible' => 1])
            ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
            ->limit(static::PAGE_SIZE + 1)
            ->offset(($page - 1) * static::PAGE_SIZE)
            ->all();
    }
}