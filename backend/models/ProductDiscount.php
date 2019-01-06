<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 10:01 PM
 */

namespace backend\models;


use yii\helpers\ArrayHelper;

class ProductDiscount extends \common\models\ProductDiscount
{
    /**
     * @var $product_ids null|int[]
     * @var $product_category_ids null|int[]
     */
    public $product_ids = null;
    public $product_category_ids = null;

    public function saveProductToProductDiscounts()
    {
        if (is_array($this->product_ids)) {
            $currentJunctions = $this->productToProductDiscounts;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_id, $this->product_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_ids = ArrayHelper::getColumn($currentJunctions, 'product_id');
            foreach ($this->product_ids as $product_id) {
                if (!in_array($product_id, $current_product_ids)) {
                    $junction = new ProductToProductDiscount();
                    $junction->product_discount_id = $this->id;
                    $junction->product_id = $product_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductCategoryToProductDiscounts()
    {
        if (is_array($this->product_category_ids)) {
            $currentJunctions = $this->productCategoryToProductDiscounts;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_category_id, $this->product_category_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_category_ids = ArrayHelper::getColumn($currentJunctions, 'product_category_id');
            foreach ($this->product_category_ids as $product_category_id) {
                if (!in_array($product_category_id, $current_product_category_ids)) {
                    $junction = new ProductCategoryToProductDiscount();
                    $junction->product_discount_id = $this->id;
                    $junction->product_category_id = $product_category_id;
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
            [[
                'product_category_ids',
                'product_ids',
            ], 'each', 'rule' => ['integer']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'product_category_ids' => 'Product Category IDs',
            'product_ids' => 'Product IDs',
        ]);
    }
}