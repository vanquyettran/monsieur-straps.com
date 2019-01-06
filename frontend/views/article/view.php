<?php
/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\Article
 * @var $modelType string
 * @var $relatedItems \frontend\models\Article[]
 */
use frontend\models\ArticleCategory;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$addToBreadcrumb = function (\common\models\ArticleCategory $category) use (&$addToBreadcrumb) {
    if ($category->parent) {
        $addToBreadcrumb($category->parent);
    }
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => $category->viewUrl()];
};
if ($model->articleCategory) {
    $addToBreadcrumb($model->articleCategory);
}
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => $model->viewUrl()];

?>

<?php
if ($bannerImage = $model->bannerImage) {

    $bannerId = "Banner_Article{$model->id}";

    if ($model->banner_font_src) {
        ?>
        <style>
            <?= $this->render('//layouts/_fontFace', ['src_mixed' => $model->banner_font_src, 'family' => $bannerId]) ?>
            <?= "#$bannerId" ?> .caption-content {
                font-family: <?= $bannerId ?>, Arial, Sans-Serif;
            }
        </style>
        <?php
    }

    if ($model->banner_filter) {
        ?>
        <style>
            <?= "#$bannerId" ?> .caption-overlay {
                background: <?= $model->banner_filter ?>;
            }
        </style>
        <?php
    }


    ?>
    <div class="article-banner" id="<?= $bannerId ?>">

        <div class="image aspect-ratio __4x1 __sm-16x9">
            <span>
                <?= $bannerImage->img() ?>
            </span>
        </div>

        <?php
        if ($model->banner_caption) {
            ?>
            <div class="caption-overlay">
                <div class="caption-content">
                    <?= $model->banner_caption ?>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
    <?php
}
?>

<div class="container">
    <div class="inner clr">
        <?= $this->render('//layouts/breadcrumb', [
            'links' => $this->params['breadcrumbs'],
        ]) ?>

        <article class="news-detail">
            <h1 class="name"><?= $model->name ?></h1>

            <?php
            if (!in_array($modelType, [ArticleCategory::TYPE_INFO, ArticleCategory::TYPE_SALES_POLICY])) {
                ?>
                <div class="info">
                    <span><?= (new \DateTime($model->published_time))->format('H:i - d/m/Y') ?></span>
                    <span class="divider">|</span>
                    <span><?= $model->view_count ?> lượt xem</span>
                </div>
                <?php
            }
            ?>

            <div class="content paragraph">
                <?= $model->content ?>
            </div>
        </article>

        <?php
        if (count($relatedItems) > 0) {
            ?>
            <div class="news-related">
                <div class="aspect-ratio __3x2 news-imaged full-width">
                    <ul>
                        <?= $this->render('_imagedList', [
                            'models' => $relatedItems,
                            'img_size' => '274x183'
                        ]) ?>
                    </ul>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>
<script>
    window.addEventListener("load", function () {
        updateArticleCounter(<?= $model->id ?>, "view_count", 1);
    });
</script>