<?php

use backend\models\Product;
use backend\models\ProductAttribute;
use backend\models\ProductAttributeGroup;
use backend\models\ProductCategory;
use backend\models\ProductDiscount;
use backend\models\Tag;
use common\models\Image;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!$model->isNewRecord) {
                ?>
                <a href="<?= Url::to([
                    'product-customization/index',
                    'product_id' => $model->id
                ]) ?>">All Customizations</a>
                &middot;
                <a href="<?= Url::to([
                    'product-customization/create',
                    'product_id' => $model->id
                ]) ?>">NEW Customization</a>
                &middot;
                <a href="<?= Url::to([
                    'product/reorder-detailed-images',
                    'id' => $model->id
                ]) ?>" target="_blank">Reorder Detailed Images</a>

                <?php
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'avatar_image_id')
                ->dropDownList(($image = $model->avatarImage) ? [$image->id => $image->name] : []) ?>

            <?= $form->field($model, 'product_category_id')
                ->dropDownList(ProductCategory::dropDownListData(), ['prompt' => '']) ?>

            <?= $form->field($model, 'published_time')->textInput() ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'price')->textInput() ?>

            <?= $form->field($model, 'production_status')->dropDownList(
                $model->getProductionStatusLabels(),
                ['prompt' => '']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
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

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'details')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'long_description')->textarea(['rows' => 6]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?php
            $detailedImages = [];
            foreach ($model->detailed_image_ids as $detailed_image_id) {
                $detailedImages[] = Image::findOne($detailed_image_id);
            }
            $relatedProducts = Product::find()->where(['IN', 'id', $model->related_product_ids])->all();
            $tags = Tag::find()->where(['IN', 'id', $model->tag_ids])->all();
            ?>

            <?= $form->field($model, 'product_attribute_ids')
                ->dropDownList(ProductAttribute::dropDownListData(), ['multiple' => true]) ?>

            <?= $form->field($model, 'detailed_image_ids')
                ->dropDownList(ArrayHelper::map($detailedImages, 'id', 'name'), ['multiple' => true]) ?>

            <?= $form->field($model, 'related_product_ids')
                ->dropDownList(ArrayHelper::map($relatedProducts, 'id', 'name'), ['multiple' => true]) ?>

            <?= $form->field($model, 'tag_ids')
                ->dropDownList(ArrayHelper::map($tags, 'id', 'name'), ['multiple' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    initCKEditor("<?= Html::getInputId($model, 'long_description') ?>");
    initCKEditor("<?= Html::getInputId($model, 'details') ?>");
    initDatetimePicker("<?= Html::getInputId($model, 'published_time') ?>");
</script>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'avatar_image_id') . '");
    initPortableImageUploader("' . Html::getInputId($model, 'detailed_image_ids') . '", true);
    
    $("#' . Html::getInputId($model, 'product_attribute_ids') . '").select2();
    
    initRemoteDropDownList("'
    . Html::getInputId($model, 'related_product_ids') . '", "'
    . Url::to(['api/find-products'])
    . '", true);
    
    initRemoteDropDownList("'
    . Html::getInputId($model, 'tag_ids') . '", "'
    . Url::to(['api/find-tags'])
    . '", true);
    
    ',
    \yii\web\View::POS_READY
);
