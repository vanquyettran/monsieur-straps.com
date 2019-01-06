<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 1/4/2018
 * Time: 2:49 PM
 */

namespace backend\models;


class ArticleCategory extends \common\models\ArticleCategory
{

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
                $add($category->getArticleCategories()->andWhere(['NOT IN', 'id', $excludedIds])->all(), $level + 1);
            }
        };
        $add(self::find()->where(['parent_id' => null])->andWhere(['NOT IN', 'id', $excludedIds])->all());
        return $result;
    }
}