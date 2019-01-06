<?php

use backend\models\ArticleCategory;
use backend\models\MenuItem;
use backend\models\ProductCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'menu_id')->dropDownList($model->getMenuNames()) ?>

            <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'sort_order')->textInput() ?>

            <?= $form->field($model, 'parent_id')->dropDownList(
                MenuItem::dropDownListData($model->isNewRecord ? [] : [$model->id]),
                ['prompt' => '']
            ) ?>

            <!--
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'anchor_target')->dropDownList([
                        '_self' => '_self',
                        '_parent' => '_parent',
                        '_top' => '_top',
                        '_blank' => '_blank',
                    ], ['prompt' => '']) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'anchor_rel')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            -->
        </div>
    </div>

    <h4>Fill out (only) one of the following fields to specify which page this menu item will lead to:</h4>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'article_category_id')
                ->dropDownList(ArticleCategory::dropDownListData(), ['prompt' => '']) ?>

            <?= $form->field($model, 'product_category_id')
                ->dropDownList(ProductCategory::dropDownListData(), ['prompt' => '']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'static_page_route')->dropDownList($model->getStaticPageRouteLabels(), ['prompt' => '']) ?>

            <?= $form->field($model, 'article_id')->dropDownList(
                $model->article ? [$model->article->id => $model->article->name] : [],
                ['prompt' => '']
            ) ?>

            <?= $form->field($model, 'product_id')->dropDownList(
                $model->product ? [$model->product->id => $model->product->name] : [],
                ['prompt' => '']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
    '
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'article_id') . '", "'
        . Url::to(['api/find-articles']) . '");
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'product_id') . '", "'
        . Url::to(['api/find-products']) . '");
        
    ',
    \yii\web\View::POS_READY
);
