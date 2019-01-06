<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UrlManager;

/**
 * This is the model class for table "menu_item".
 *
 * @property int $id
 * @property int $menu_id
 * @property string $label
 * @property int $sort_order
 * @property string $anchor_target
 * @property string $anchor_rel
 * @property string $link
 * @property string $static_page_route
 * @property int $article_category_id
 * @property int $article_id
 * @property int $product_category_id
 * @property int $product_id
 * @property int $parent_id
 *
 * @property ArticleCategory $articleCategory
 * @property Article $article
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 * @property ProductCategory $productCategory
 * @property Product $product
 */
class MenuItem extends \common\db\MyActiveRecord
{
    const MENU_ID_TOP = 1;
    const MENU_ID_BOTTOM = 2;

    const STATIC_PAGE_ROUTE_HOME = 'site/index';
    const STATIC_PAGE_ROUTE_ARTICLE_INDEX = 'article/index';

    /**
     * @return string[]
     */
    public function getMenuNames()
    {
        return [
            self::MENU_ID_TOP => 'Top Menu',
            self::MENU_ID_BOTTOM => 'Bottom Menu',
        ];
    }

    /**
     * @return string
     */
    public function menuName()
    {
        $menuNames = self::getMenuNames();
        if (isset($menuNames[$this->menu_id])) {
            return $menuNames[$this->menu_id];
        } else {
            return "$this->menu_id";
        }
    }

    /**
     * @return string[]
     */
    public function getStaticPageRouteLabels()
    {
        return [
            self::STATIC_PAGE_ROUTE_HOME => 'Home',
            self::STATIC_PAGE_ROUTE_ARTICLE_INDEX => 'Article Index',
        ];
    }

    /**
     * @return string
     */
    public function staticPageRouteLabel()
    {
        $routeLabels = $this->getStaticPageRouteLabels();
        if (isset($routeLabels[$this->static_page_route])) {
            return $routeLabels[$this->static_page_route];
        } else {
            return $this->static_page_route;
        }
    }

    public function getMenuKey()
    {
        return "-{$this->id}-";
    }

    public function getUrl()
    {
        $url = $this->link;
        if ($url === '') {
            if (in_array($this->static_page_route, array_keys($this->getStaticPageRouteLabels()))) {
                /**
                 * @var $urlMng UrlManager
                 */
                $urlMng = Yii::$app->frontendUrlManager;
                $url = Yii::getAlias('@frontendHost') . $urlMng->createUrl([$this->static_page_route]);
            }
        }
        if ($url === '') {
            if ($this->article_category_id && $this->articleCategory) {
                $url = $this->articleCategory->viewUrl();
            }
        }
        if ($url === '') {
            if ($this->article_id && $this->article) {
                $url = $this->article->viewUrl();
            }
        }
        if ($url === '') {
            if ($this->product_category_id && $this->productCategory) {
                $url = $this->productCategory->viewUrl();
            }
        }
        if ($url === '') {
            if ($this->product_id && $this->product) {
                $url = $this->product->viewUrl();
            }
        }
        
        return $url;
    }

    private static $_indexData = null;

    /**
     * @return self[]
     */
    public static function indexData($menu_id = null)
    {
        if (self::$_indexData === null) {
            self::$_indexData = self::find()
                ->orderBy('sort_order asc')
                ->indexBy('id')
                ->all();
        }
        if ($menu_id !== null) {
            return array_filter(self::$_indexData, function ($item) use ($menu_id) {
                /**
                 * @var $item self
                 */
                return $menu_id === $item->menu_id;
            });
        }
        return self::$_indexData;
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
                if ($item->parent_id == $this->id) {
                    $this->_children[] = $item;
                }
            }
        }

        return $this->_children;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'label', 'sort_order'], 'required'],
            [['menu_id', 'sort_order', 'article_category_id', 'article_id', 'product_category_id', 'product_id', 'parent_id'], 'integer'],
            [['label', 'anchor_target', 'anchor_rel', 'static_page_route'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 511],
            [['article_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['article_category_id' => 'id']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['product_category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            ['menu_id', 'customValidator'],
        ];
    }

    public function customValidator($attribute, $params, $validator)
    {

        if ($this->parent && "{$this->parent->menu_id}" !== "$this->menu_id") {
            $this->addError('parent_id', 'Parent must be in the same menu. The menu this parent belong to is "' . $this->parent->menuName() . '".');
        }

        if ($this->link === ''
            && $this->static_page_route === ''
            && $this->article_category_id === ''
            && $this->article_id === ''
            && $this->product_category_id === ''
            && $this->product_id === ''
        ) {
            $msg = 'One (and only one) of the link, static_page_route, article_category_id, article_id, product_category_id, product_id fields must be filled out.';
            $this->addError('link', $msg);
            $this->addError('static_page_route', $msg);
            $this->addError('article_category_id', $msg);
            $this->addError('article_id', $msg);
            $this->addError('product_category_id', $msg);
            $this->addError('product_id', $msg);
            
        } else {
            $checked_attrs = [];
            if ($this->link !== '') {
                $checked_attrs = ['static_page_route', 'article_category_id', 'article_id', 'product_category_id', 'product_id'];
            } else if ($this->static_page_route !== '') {
                $checked_attrs = ['article_category_id', 'article_id', 'product_category_id', 'product_id'];
            } else if ($this->article_category_id !== '') {
                $checked_attrs = ['article_id', 'product_category_id', 'product_id'];
            } else if ($this->article_id !== '') {
                $checked_attrs = ['product_category_id', 'product_id'];
            } else if ($this->product_category_id !== '') {
                $checked_attrs = ['product_id'];
            }

            foreach ($checked_attrs as $checked_attr) {
                if ($this->$checked_attr !== '') {
                    $this->addError($checked_attr, 'It is not allowed to have more than one of the link, static_page_route, article_category_id, article_id, product_category_id, product_id fields are filled out.');
                }
            }

        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'label' => 'Label',
            'sort_order' => 'Sort Order',
            'anchor_target' => 'Anchor Target',
            'anchor_rel' => 'Anchor Rel',
            'link' => 'Link',
            'static_page_route' => 'Static Page Route',
            'article_category_id' => 'Article Category ID',
            'article_id' => 'Article ID',
            'parent_id' => 'Parent ID',
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
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
