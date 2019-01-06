<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 10:01 PM
 */

namespace backend\models;


use yii\helpers\ArrayHelper;

class ProductCustomization extends \common\models\ProductCustomization
{
    /**
     * @var $product_attribute_ids null|int[]
     * @var $detailed_image_ids null|int[]
     */
    public $product_attribute_ids = null;
    public $detailed_image_ids = null;

    public function saveProductCustomizationToProductAttributes()
    {
        if (is_array($this->product_attribute_ids)) {
            $currentJunctions = $this->productCustomizationToProductAttributes;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->product_attribute_id, $this->product_attribute_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_product_attribute_ids = ArrayHelper::getColumn($currentJunctions, 'product_attribute_id');
            foreach ($this->product_attribute_ids as $product_customization_id) {
                if (!in_array($product_customization_id, $current_product_attribute_ids)) {
                    $junction = new ProductCustomizationToProductAttribute();
                    $junction->product_customization_id = $this->id;
                    $junction->product_attribute_id = $product_customization_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveProductCustomizationToDetailedImages()
    {
        if (is_array($this->detailed_image_ids)) {
            $currentJunctions = $this->productCustomizationToDetailedImages;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->detailed_image_id, $this->detailed_image_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_detailed_image_ids = ArrayHelper::getColumn($currentJunctions, 'detailed_image_id');
            $sort_order = 0;
            foreach ($this->detailed_image_ids as $product_customization_id) {
                $sort_order++;
                if (!in_array($product_customization_id, $current_detailed_image_ids)) {
                    $junction = new ProductCustomizationToDetailedImage();
                    $junction->product_customization_id = $this->id;
                    $junction->detailed_image_id = $product_customization_id;
                    $junction->sort_order = $sort_order;
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
            [['product_attribute_ids', 'detailed_image_ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'product_attribute_ids' => 'Product Attributes IDs',
            'detailed_image_ids' => 'Detailed Image IDs',
        ]);
    }

}