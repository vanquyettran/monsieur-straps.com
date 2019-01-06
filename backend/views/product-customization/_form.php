<?php

use backend\models\ProductAttribute;
use common\models\Image;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductCustomization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-customization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')
        ->dropDownList(($product = $model->product) ? [$product->id => $product->name] : []) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'product_attribute_ids')
        ->dropDownList(ProductAttribute::dropDownListData(), ['multiple' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'available')->checkbox() ?>

    <?= $form->field($model, 'featured')->checkbox() ?>

    <?= $form->field($model, 'details')->textarea(['rows' => 6]) ?>

    <?php
    $detailedImages = Image::find()->where(['IN', 'id', $model->detailed_image_ids])->all();
    ?>

    <?= $form->field($model, 'detailed_image_ids')
        ->dropDownList(ArrayHelper::map($detailedImages, 'id', 'name'), ['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    initCKEditor("<?= Html::getInputId($model, 'details') ?>");
</script>

<?php
$this->registerJs(
    '
    
    $("#' . Html::getInputId($model, 'product_attribute_ids') . '").select2();
    initPortableImageUploader("' . Html::getInputId($model, 'detailed_image_ids') . '", true);
    
    initRemoteDropDownList("'
    . Html::getInputId($model, 'product_id') . '", "'
    . Url::to(['api/find-products'])
    . '", false);
    
    ',
    \yii\web\View::POS_READY
);
