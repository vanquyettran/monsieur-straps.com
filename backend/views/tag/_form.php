<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'avatar_image_id')
                ->dropDownList(($image = $model->avatarImage) ? [$image->id => $image->name] : []) ?>

            <?= $form->field($model, 'sort_order')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'visible')->checkbox() ?>

            <?= $form->field($model, 'featured')->checkbox() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'allow_indexing')->checkbox() ?>

            <?= $form->field($model, 'allow_following')->checkbox() ?>
        </div>
    </div>

    <?= $form->field($model, 'long_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

    <script>
        initCKEditor("<?= Html::getInputId($model, 'long_description') ?>");
    </script>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'avatar_image_id') . '");
    ',
    \yii\web\View::POS_READY
);
