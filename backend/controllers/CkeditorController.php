<?php
/**
 * Created by PhpStorm.
 * User: VanQuyet
 * Date: 4/4/2019
 * Time: 1:14 PM
 */

namespace backend\controllers;


use common\models\Image;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class CkeditorController extends Controller
{
    public function actionBrowseImage() {
        $this->layout = 'ckeditor';

        $funcNum = (string) Yii::$app->request->get('CKEditorFuncNum');
        $funcNum = preg_replace('/[^0-9]/', '', $funcNum);

        return $this->render('browse-image', ['funcNum' => $funcNum]);
    }
}