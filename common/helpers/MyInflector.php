<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/30/2017
 * Time: 7:50 AM
 */

namespace common\helpers;


use yii\helpers\Inflector;

class MyInflector extends Inflector
{
    public static function slug($string, $replacement = '-', $lowercase = true)
    {
        $string = MyStringHelper::stripUnicode($string);
        return parent::slug($string, $replacement, $lowercase);
    }
}