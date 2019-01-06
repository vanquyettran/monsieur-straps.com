<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 9:59 PM
 */

namespace backend\models;


use yii\helpers\ArrayHelper;

class Product extends \common\models\Product
{
    /**
     * @var $product_attribute_ids null|int[]
     * @var $detailed_image_ids null|int[]
     * @var $related_product_ids null|int[]
     * @var $tag_ids null|int[]
     * @var $product_discount_ids null|int[]
     */
    public $product_attribute_ids = null;
    public $detailed_image_ids = null;
    public $related_product_ids = null;
    public $tag_ids = null;
    public $product_discount_ids = null;

    public function saveProductToRelatedProducts()
    {
        if (is_array($this->related_product_ids)) {
            $currentJunctions = $this->productToRelatedProducts;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->related_product_id, $this->related_product_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_related_product_ids = ArrayHelper::getColumn($currentJunctions, 'related_product_id');
            foreach ($this->related_product_ids as $product_id) {
                if (!in_array($product_id, $current_related_product_ids)) {
                    $junction = new ProductToRelatedProduct();
                    $junction->product_id = $this->id;
                    $junction->related_product_id = $product_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductToTags()
    {
        if (is_array($this->tag_ids)) {
            $currentJunctions = $this->productToTags;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->tag_id, $this->tag_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_tag_ids = ArrayHelper::getColumn($currentJunctions, 'tag_id');
            foreach ($this->tag_ids as $product_id) {
                if (!in_array($product_id, $current_tag_ids)) {
                    $junction = new ProductToTag();
                    $junction->product_id = $this->id;
                    $junction->tag_id = $product_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductToDetailedImages()
    {
        if (is_array($this->detailed_image_ids)) {
            $currentJunctions = $this->productToDetailedImages;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->detailed_image_id, $this->detailed_image_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_detailed_image_ids = ArrayHelper::getColumn($currentJunctions, 'detailed_image_id');
            $sort_order = 0;
            foreach ($this->detailed_image_ids as $detailed_image_id) {
                $sort_order++;
                if (!in_array($detailed_image_id, $current_detailed_image_ids)) {
                    $junction = new ProductToDetailedImage();
                    $junction->product_id = $this->id;
                    $junction->detailed_image_id = $detailed_image_id;
                    $junction->sort_order = $sort_order;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                } else {
                    foreach ($currentJunctions as $currentJunction) {
                        if ($currentJunction->detailed_image_id === +$detailed_image_id) {
                            $currentJunction->sort_order = $sort_order;
                            if (!$currentJunction->save()) {
                                $currentJunction->addCreateFailureFlash();
                            }
                        }
                    }
                }
            }
        }
    }

    public function saveProductToProductAttributes()
    {
        if (is_array($this->product_attribute_ids)) {
            $currentJunctions = $this->productToProductAttributes;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_attribute_id, $this->product_attribute_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_attribute_ids = ArrayHelper::getColumn($currentJunctions, 'product_attribute_id');
            foreach ($this->product_attribute_ids as $product_id) {
                if (!in_array($product_id, $current_product_attribute_ids)) {
                    $junction = new ProductToProductAttribute();
                    $junction->product_id = $this->id;
                    $junction->product_attribute_id = $product_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductToProductDiscounts()
    {
        if (is_array($this->product_discount_ids)) {
            $currentJunctions = $this->productToProductDiscounts;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_discount_id, $this->product_discount_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_discount_ids = ArrayHelper::getColumn($currentJunctions, 'product_discount_id');
            foreach ($this->product_discount_ids as $product_id) {
                if (!in_array($product_id, $current_product_discount_ids)) {
                    $junction = new ProductToProductDiscount();
                    $junction->product_id = $this->id;
                    $junction->product_discount_id = $product_id;
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
                'product_attribute_ids',
                'product_discount_ids',
                'related_product_ids',
                'tag_ids',
                'detailed_image_ids',
            ], 'each', 'rule' => ['integer']],
            ['related_product_ids', 'avoidRelateToSelf']
        ]);
    }

    public function avoidRelateToSelf($attribute, $params, $validator)
    {
        if (in_array($this->id, $this->related_product_ids)) {
            $this->addError('related_product_ids', 'Product cannot relate to itself.');
        }
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'product_attribute_ids' => 'Product Attribute IDs',
            'product_discount_ids' => 'Product Discount IDs',
            'related_product_ids' => 'Related Product IDs',
            'tag_ids' => 'Tag IDs',
            'detailed_image_ids' => 'Detailed Image IDs',
        ]);
    }
}