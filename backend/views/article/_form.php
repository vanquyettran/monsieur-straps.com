<?php

use backend\models\Article;
use backend\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\ArticleCategory;
use backend\models\Game;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'avatar_image_id')
                ->dropDownList(($image = $model->avatarImage) ? [$image->id => $image->name] : []) ?>

            <?= $form->field($model, 'article_category_id')
                ->dropDownList(ArticleCategory::dropDownListData(), ['prompt' => '']) ?>

            <?= $form->field($model, 'published_time')->textInput() ?>
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
        <div class="col-md-6">
            <?= $form->field($model, 'banner_image_id')
                ->dropDownList(($image = $model->bannerImage) ? [$image->id => $image->name] : []) ?>

            <?= $form->field($model, 'banner_filter')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'banner_caption')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'banner_font_src')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'video_src')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            $relatedArticles = Article::find()->where(['IN', 'id', $model->related_article_ids])->all();
            $tags = Tag::find()->where(['IN', 'id', $model->tag_ids])->all();
            ?>

            <?= $form->field($model, 'related_article_ids')
                ->dropDownList(ArrayHelper::map($relatedArticles, 'id', 'name'), ['multiple' => true]) ?>

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
    initCKEditor("<?= Html::getInputId($model, 'content') ?>");
    initDatetimePicker("<?= Html::getInputId($model, 'published_time') ?>");

    var banner_caption_id = "<?= Html::getInputId($model, 'banner_caption') ?>";
    initCKEditor(banner_caption_id, true);
    CKEDITOR.instances[banner_caption_id].on('instanceReady', function () {
        this.document.getBody().setStyle('background', '#F8F');
    });
</script>

<?php
$this->registerJs(
    '
    initPortableImageUploader("' . Html::getInputId($model, 'avatar_image_id') . '");
    initPortableImageUploader("' . Html::getInputId($model, 'banner_image_id') . '");
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'tag_ids') . '", "'
        . Url::to(['api/find-tags'])
    . '", true);
    
    initRemoteDropDownList("'
        . Html::getInputId($model, 'related_article_ids') . '", "'
        . Url::to(['api/find-articles'])
    . '", true);
    
    ',
    \yii\web\View::POS_READY
);
