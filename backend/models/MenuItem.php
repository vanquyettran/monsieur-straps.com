<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 2/5/2018
 * Time: 9:09 AM
 */

namespace backend\models;


class MenuItem extends \common\models\MenuItem
{
    /**
     * @param int[] excludedIds
     * @return string[]
     */
    public static function dropDownListData($excludedIds = [])
    {
        $result = [];
        /**
         * @param $menuItems self[]
         * @param $level int
         */
        $add = function ($menuItems, $level = 0) use (&$add, &$result, $excludedIds) {
            foreach ($menuItems as $menuItem) {
                $indent = '';
                for ($i = 0; $i < $level; $i++) {
                    $indent .= '...';
                }
                $menuID = $menuItem->menu_id . ') ' . $menuItem->menuName();
                $result[$menuID]["$menuItem->id"] = "{$indent}{$menuItem->label}";
                $add($menuItem->getMenuItems()->andWhere(['NOT IN', 'id', $excludedIds])->all(), $level + 1);
            }
        };
        $add(self::find()->where(['parent_id' => null])->andWhere(['NOT IN', 'id', $excludedIds])->all());
        return $result;
    }
}