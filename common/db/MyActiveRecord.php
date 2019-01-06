<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/31/2017
 * Time: 12:26 AM
 */

namespace common\db;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use common\models\Image;
use vanquyet\queryTemplate\QueryTemplate;
use Prophecy\Exception\Doubler\MethodNotFoundException;

class MyActiveRecord extends ActiveRecord
{
    /**
     * @return MyActiveQuery
     */
    public static function find()
    {
        return new MyActiveQuery(get_called_class());
    }

    /**
     * @param $ref_text string
     */
    public function addRefMissingFlash($ref_text)
    {
        /**
         * @var $ref self
         */
        $msg = Yii::t(
            'app',
            'Missing {ref_text} for {object_text}',
            [
                'ref_text' => $ref_text,
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey)
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('warning', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addCreateSuccessFlash()
    {
        $msg = Yii::t(
            'app',
            'Created {object_text} successfully',
            [
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey)
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('success', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addUpdateSuccessFlash()
    {
        $msg = Yii::t(
            'app',
            'Updated {object_text} successfully',
            [
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey)
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('success', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addDeleteSuccessFlash()
    {
        $msg = Yii::t(
            'app',
            'Deleted {object_text} successfully',
            [
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey)
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('success', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addCreateFailureFlash()
    {
        $msg = Yii::t(
            'app',
            'Failed to create {object_text}: {error_message}',
            [
                'object_text' => $this->tableName(),
                'error_message' => VarDumper::dumpAsString($this->getErrors()),
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('error', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addUpdateFailureFlash()
    {
        $msg = Yii::t(
            'app',
            'Failed to update {object_text}: {error_message}',
            [
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey),
                'error_message' => VarDumper::dumpAsString($this->getErrors()),
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('error', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addDeleteFailureFlash()
    {
        $msg = Yii::t(
            'app',
            'Failed to delete {object_text}',
            [
                'object_text' => $this->tableName() . '#' . json_encode($this->primaryKey)
            ]
        );
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('success', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public function addBreakRefsPreventingFlash($refs_text, $object_text = '', $preserved_attributes_text = '')
    {
        if (!$object_text) {
            $object_text = $this->tableName() . '#' . json_encode($this->primaryKey);
        }
        $msg_params = [
            'refs_text' => $refs_text,
            'object_text' => $object_text,
            'preserved_attributes_text' => $preserved_attributes_text,
        ];
        if ($preserved_attributes_text) {
            $msg = \Yii::t('app', 'Cannot update {preserved_attributes_text} of {object_text}', $msg_params);
        } else {
            $msg = \Yii::t('app', 'Cannot delete {object_text}', $msg_params);
        }
        $msg .= '. ' . \Yii::t('app', 'Cause there are {refs_text} linked to it', $msg_params);
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('error', $msg);
        } else {
            echo "$msg\n";
        }
    }

    /**
     * @param $exception \Exception
     */
    public static function addExceptionFlash($exception)
    {
        $msg = "Exception:"
            . " CODE= {$exception->getCode()}"
            . " MESSAGE= {$exception->getMessage()}"
            . " FILE= {$exception->getFile()}"
            . " LINE= {$exception->getLine()}";
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('error', $msg);
        } else {
            echo "$msg\n";
        }
    }
    public static function addInfoFlash($msg)
    {
        if (isset(Yii::$app->session)) {
            Yii::$app->session->addFlash('info', $msg);
        } else {
            echo "$msg\n";
        }
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

    public $templateLastMethod = '';

    public $templateLogMessage = '';

    public function templateToHtml($attributes = null)
    {
        if (__METHOD__ === $this->templateLastMethod) {
            return false;
        }

        if (!$attributes) {
            $attributes = ['content', 'long_description', 'details'];
        }

        foreach ($attributes as $attribute) {
            if (!$this->hasAttribute($attribute)) {
                continue;
            }
            $html = QueryTemplate::widget([
                'content' => $this->$attribute,
                'queries' => [
                    'Image' => function ($id) {
                        return Image::findOne($id);
                    },
                ],
                'enableDebugMode' => true,
            ]);
            $this->$attribute = $html;
            $this->templateLogMessage .= VarDumper::dumpAsString(QueryTemplate::$errors) . "\n";
        }

        $this->templateLastMethod = __METHOD__;

        return true;
    }

    public function htmlToTemplate($attributes = null)
    {
        if (__METHOD__ === $this->templateLastMethod) {
            return false;
        }

        if (!$attributes) {
            $attributes = ['content', 'long_description'];
        }

        foreach ($attributes as $attribute) {
            /**
             * @var \DOMElement $imgTag
             */
            if (!$this->hasAttribute($attribute)) {
                continue;
            }
            $html = $this->$attribute;
//            $doc = new \DOMDocument('1.0', 'UTF-8');
            $doc = new \DOMDocument();

            /**
             * @TODO: Disable warning, which will be inserted into content
             * Warning: DOMDocument::loadHTML(): Unexpected end tag: 'example' in Entity
             * https://stackoverflow.com/questions/11819603/dom-loadhtml-doesnt-work-properly-on-a-server
             */
            libxml_use_internal_errors(true);

            try {
                $doc->loadHTML(
                    mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')
//                    , LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
                );
                $imgTags = $doc->getElementsByTagName('img');
                $i = 0;
                while ($imgTag = $imgTags->item($i)) {
                    if (!$imgTag) {
                        $i++;
                        continue;
                    }
                    $src = $imgTag->getAttribute('src');
                    $id = null;
                    if (strpos($src, '?image_id=') === false && strpos($src, '&image_id=') === false) {
                        $i++;
                        continue;
                    }
                    $parts = parse_url($src);
                    if (isset($parts['query'])) {
                        parse_str($parts['query'], $query);
                        if (isset($query['image_id'])) {
                            $id = $query['image_id'];
                        }
                    }
                    if (!$id) {
                        $i++;
                        continue;
                    }

                    $opts = [];
                    $width = null;
                    $height = null;
                    foreach (['width', 'height', 'id', 'class', 'style', 'alt', 'title'] as $attr) {
                        $val = $imgTag->getAttribute($attr);
                        if ($val) {
                            $opts[$attr] = $val;
                            if (in_array($attr, ['width', 'height'])) {
                                $$attr = $val;
                            }
                        }
                    }
                    $opts_str = json_encode($opts);
                    if (is_numeric($width) && is_numeric($height)) {
                        $size = "{$width}x{$height}";
                    } else {
                        $size = null;
                    }
                    $size_str = json_encode($size);

                    $node = $doc->createTextNode(
                        QueryTemplate::__FUNC_OPEN
                        . " Image($id)" . QueryTemplate::__OBJECT_OPERATOR . "imgTag($size_str, $opts_str) "
                        . QueryTemplate::__FUNC_CLOSE
                    );

                    $imgTag->parentNode->replaceChild($node, $imgTag);
                }
//                $this->$attribute = $doc->saveHTML();
                $doc->saveHTML();
                $bodies = $doc->getElementsByTagName('body');
                if (isset($bodies[0])) {
                    $this->$attribute = self::DOMinnerHTML($bodies[0]);
                } else {
                    $this->templateLogMessage .= "Body tag was not found\n";
                    return false;
                }
            } catch (\Exception $e) {
                $this->templateLogMessage .= $e->getMessage() . "\n";
                return false;
            }
        }

        $this->templateLastMethod = __METHOD__;

        return true;
    }

    public $allowedTemplateActions = ['index', 'create', 'update'];

    public function beforeSave($insert)
    {
        if (\Yii::$app->controller
            && \Yii::$app->controllerNamespace === 'backend\\controllers'
            && in_array(\Yii::$app->controller->action->id, $this->allowedTemplateActions)
        ) {
            $success = $this->htmlToTemplate();

            $this->templateLogMessage
                .= ($success ? 'success' : 'failure')
                . ': html to template | ' . __METHOD__ . "\n\n";
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();

        if (\Yii::$app->controller
            && \Yii::$app->controllerNamespace === 'backend\\controllers'
            && in_array(\Yii::$app->controller->action->id, $this->allowedTemplateActions)
        ) {
            $success = $this->templateToHtml();

            $this->templateLogMessage
                .= ($success ? 'success' : 'failure')
                . ': template to html | ' . __METHOD__ . "\n\n";
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (\Yii::$app->controller
            && \Yii::$app->controllerNamespace === 'backend\\controllers'
            && in_array(\Yii::$app->controller->action->id, $this->allowedTemplateActions)
        ) {
            $success = $this->templateToHtml();

            $this->templateLogMessage
                .= ($success ? 'success' : 'failure')
                . ': template to html | ' . __METHOD__ . "\n\n";
        }
    }

    public static function DOMinnerHTML(\DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }
}