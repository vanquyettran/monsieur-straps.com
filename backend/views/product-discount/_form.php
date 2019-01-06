<?php

use backend\models\Product;
use backend\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductDiscount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-discount-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'percentage')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'start_time')->textInput() ?>

            <?= $form->field($model, 'end_time')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'occasion')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            $products = Product::find()->where(['IN', 'id', $model->product_ids])->all();
            ?>

            <?= $form->field($model, 'product_ids')
                ->dropDownList(ArrayHelper::map($products, 'id', 'name'), ['multiple' => true]) ?>

            <?= $form->field($model, 'product_category_ids')
                ->dropDownList(ProductCategory::dropDownListData(), ['multiple' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    initCKEditor("<?= Html::getInputId($model, 'occasion') ?>");
    initDatetimePicker("<?= Html::getInputId($model, 'start_time') ?>");
    initDatetimePicker("<?= Html::getInputId($model, 'end_time') ?>");
</script>

<?php
$this->registerJs(
    '
    
    $("#' . Html::getInputId($model, 'product_category_ids') . '").select2();
    
    initRemoteDropDownList("'
    . Html::getInputId($model, 'product_ids') . '", "'
    . Url::to(['api/find-products'])
    . '", true);
    
    ',
    \yii\web\View::POS_READY
);
