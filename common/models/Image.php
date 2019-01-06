<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 1/4/2018
 * Time: 9:49 AM
 */

namespace common\models;

use Yii;

class Image extends \common\modules\image\models\Image
{
    /** Query Template */

    /**
     * @return array
     */
    public function templateMethods()
    {
        return [
            'attribute' => function ($name) {
                return $this->getAttribute($name);
            },
            'imgTag' => function ($size = null, $options = [], $srcOptions = []) {
                if (
                    Yii::$app->controller
                    && in_array(Yii::$app->controllerNamespace, ['backend\controllers'])
                ) {
                    $srcOptions = array_merge($srcOptions, ['image_id' => $this->id]);
                }
                return $this->img($size, $options, $srcOptions);
            },
            'source' => function ($size = null, $options = []) {
                return $this->getImgSrc($size, $options);
            },
            'filename' => function ($size = null, $options = []) {
                return $this->getImgFileName($size, $options);
            },
        ];
    }

    /**
     * @param $methodName
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function callTemplateMethod($methodName, $arguments)
    {
        if (method_exists($this, 'templateMethods')) {
            $methods = $this->templateMethods();
        } else {
            throw new \Exception('Missing method `templateMethods()` from `' . get_class($this) . '`');
        }
        if (!isset($methods[$methodName])) {
            throw new \Exception("Missing template method `$methodName()` from `" . get_class($this) . '`');
        }
        return call_user_func_array($methods[$methodName], $arguments);
    }
}