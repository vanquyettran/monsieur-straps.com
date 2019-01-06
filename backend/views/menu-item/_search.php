<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\searchModels\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'menu_id') ?>

    <?= $form->field($model, 'label') ?>

    <?= $form->field($model, 'sort_order') ?>

    <?= $form->field($model, 'anchor_target') ?>

    <?php // echo $form->field($model, 'anchor_rel') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'static_page_route') ?>

    <?php // echo $form->field($model, 'article_category_id') ?>

    <?php // echo $form->field($model, 'article_id') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
