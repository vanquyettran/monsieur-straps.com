<?php

use backend\models\Product;
use backend\models\ProductAttribute;
use backend\models\ProductAttributeGroup;
use common\models\Image;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ProductCategory;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="product-category-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'type')->dropDownList($model->getTypeLabels()) ?>

                <?= $form->field($model, 'parent_id')
                    ->dropDownList(ProductCategory::dropDownListData($model->id ? [$model->id] : []), ['prompt' => '']) ?>

                <?= $form->field($model, 'avatar_image_id')
                    ->dropDownList(($image = $model->avatarImage) ? [$image->id => $image->name] : []) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]) ?>

                <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

                <?= $form->field($model, 'sort_order')->textInput() ?>

                <?= $form->field($model, 'displaying_areas')->dropDownList(
                    ProductCategory::displayingAreas(),
                    ['multiple' => true]
                ) ?>
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
            <div class="col-md-6">
                <?php
                $bannerImages = Image::find()->where(['IN', 'id', $model->banner_image_ids])->all();
                echo $form->field($model, 'banner_image_ids')
                    ->dropDownList(ArrayHelper::map($bannerImages, 'id', 'name'), ['multiple' => true])
                ?>

                <?= $form->field($model, 'banner_filter')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'banner_caption')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'banner_font_src')->textarea(['rows' => 6]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'long_description')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'product_attribute_group_ids')
                    ->dropDownList(ArrayHelper::map(ProductAttributeGroup::find()->all(), 'id', 'name'), ['multiple' => true]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <script>
        initCKEditor("<?= Html::getInputId($model, 'long_description') ?>");

        var banner_caption_id = "<?= Html::getInputId($model, 'banner_caption') ?>";
        initCKEditor(banner_caption_id, true);
        CKEDITOR.instances[banner_caption_id].on('instanceReady', function () {
            this.document.getBody().setStyle('background', '#F8F');
        });
    </script>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'banner_image_ids') . '", true);
    
    $("#' . Html::getInputId($model, 'product_attribute_group_ids') . '").select2();
    
    initPortableImageUploader("' . Html::getInputId($model, 'avatar_image_id') . '");
    $("#' . Html::getInputId($model, 'displaying_areas') . '").select2();
    ',
    \yii\web\View::POS_READY
);
