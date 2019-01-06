<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 9:59 PM
 */

namespace backend\models;


use yii\helpers\ArrayHelper;

class ProductCategory extends \common\models\ProductCategory
{
    /**
     * @var $banner_image_ids null|int[]
     * @var $product_attribute_group_ids null|int[]
     */
    public $banner_image_ids = null;
    public $product_attribute_group_ids = null;

    public function saveProductCategoryToBannerImages()
    {
        if (is_array($this->banner_image_ids)) {
            $currentJunctions = $this->productCategoryToBannerImages;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->banner_image_id, $this->banner_image_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_banner_image_ids = ArrayHelper::getColumn($currentJunctions, 'banner_image_id');
            $i = 0;
            foreach ($this->banner_image_ids as $product_category_id) {
                $i++;
                if (!in_array($product_category_id, $current_banner_image_ids)) {
                    $junction = new ProductCategoryToBannerImage();
                    $junction->product_category_id = $this->id;
                    $junction->banner_image_id = $product_category_id;
                    $junction->sort_order = $i;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductCategoryToProductAttributeGroups()
    {
        if (is_array($this->product_attribute_group_ids)) {
            $currentJunctions = $this->productCategoryToProductAttributeGroups;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_attribute_group_id, $this->product_attribute_group_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_attribute_group_ids = ArrayHelper::getColumn($currentJunctions, 'product_attribute_group_id');
            foreach ($this->product_attribute_group_ids as $product_category_id) {
                if (!in_array($product_category_id, $current_product_attribute_group_ids)) {
                    $junction = new ProductCategoryToProductAttributeGroup();
                    $junction->product_category_id = $this->id;
                    $junction->product_attribute_group_id = $product_category_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['banner_image_ids', 'product_attribute_group_ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'banner_image_ids' => 'Banner Image IDs',
            'product_attribute_group_ids' => 'Product Attribute Group IDs',
        ]);
    }

    /**
     * @param int[] excludedIds
     * @return string[]
     */
    public static function dropDownListData($excludedIds = [])
    {
        $result = [];
        /**
         * @param $categories self[]
         * @param $level int
         */
        $add = function ($categories, $level = 0) use (&$add, &$result, $excludedIds) {
            foreach ($categories as $category) {
                $indent = '';
                for ($i = 0; $i < $level; $i++) {
                    $indent .= '...';
                }
                $result["$category->id"] = "{$indent}{$category->name}";
                $add($category->getProductCategories()->andWhere(['NOT IN', 'id', $excludedIds])->all(), $level + 1);
            }
        };
        $add(self::find()->where(['parent_id' => null])->andWhere(['NOT IN', 'id', $excludedIds])->all());
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if ($this->getProducts()->count() > 0) {
            $this->addBreakRefsPreventingFlash('Products');
            return false;
        }

        return parent::beforeDelete();
    }
}