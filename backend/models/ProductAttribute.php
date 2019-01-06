<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 10:00 PM
 */

namespace backend\models;


class ProductAttribute extends \common\models\ProductAttribute
{
    /**
     * @return array
     */
    public static function dropDownListData() {
        $productAttributeIDsDropDownListData = [];
        foreach (ProductAttributeGroup::find()->orderBy('sort_order ASC')->all() as $group) {
            /**
             * @var $group ProductAttributeGroup
             */
            $groupData = [];
            foreach ($group->getProductAttributes()->orderBy('sort_order ASC')->all() as $productAttribute) {
                $groupData[$productAttribute->id] = "$group->name: $productAttribute->name";
            }
            $productAttributeIDsDropDownListData[$group->name] = $groupData;
        }
        return $productAttributeIDsDropDownListData;
    }
}