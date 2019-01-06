<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/21/2018
 * Time: 10:01 PM
 */

namespace backend\models;


class ProductAttributeGroup extends \common\models\ProductAttributeGroup
{
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if ($this->getProductAttributes()->count() > 0) {
            $this->addBreakRefsPreventingFlash('ProductAttributes');
            return false;
        }

        return parent::beforeDelete();
    }
}