<?php

use backend\models\ProductAttributeGroup;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'illustration_image_id')
        ->dropDownList(($image = $model->illustrationImage) ? [$image->id => $image->name] : []) ?>

    <?= $form->field($model, 'product_attribute_group_id')->dropDownList(
        ArrayHelper::map(ProductAttributeGroup::find()->orderBy('sort_order ASC')->all(), 'id', 'name'),
        ['prompt' => '']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'illustration_image_id') . '");
    ',
    \yii\web\View::POS_READY
);
