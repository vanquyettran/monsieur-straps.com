<?php
/**
 * @var $this \yii\web\View
 * @var $category ArticleCategory
 * @var $articles \frontend\models\Article[]
 * @var $queryParams string
 * @var $hasMore boolean
 * @var $page integer
 */
use frontend\models\ArticleCategory;
use yii\helpers\Html;

$view_id = '_thumbnailList';
$img_size = '142x95';
$img_size_full = '381x254';
?>

<?php
if ($bannerImage = $category->bannerImage) {

    $bannerId = "Banner_ArticleCategory{$category->id}";

    if ($category->banner_font_src) {
        ?>
        <style>
            <?= $this->render('//layouts/_fontFace', ['src_mixed' => $category->banner_font_src, 'family' => $bannerId]) ?>
            <?= "#$bannerId" ?> .caption-content {
                font-family: <?= $bannerId ?>, Arial, Sans-Serif;
            }
        </style>
        <?php
    }

    if ($category->banner_filter) {
        ?>
        <style>
            <?= "#$bannerId" ?> .caption-overlay {
                background: <?= $category->banner_filter ?>;
            }
        </style>
        <?php
    }

    ?>
    <div class="article-category-banner" id="<?= $bannerId ?>">
        <div class="image aspect-ratio __4x1 __sm-16x9">
            <span>
                <?= $bannerImage->img() ?>
            </span>
        </div>

        <?php
        if ($category->banner_caption) {
            ?>
            <div class="caption-overlay">
                <div class="caption-content">
                    <?= $category->banner_caption ?>
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
            <div class="news-category">
                <div class="aspect-ratio __3x2 news-thumbnail">
                    <div class="title"><?= $category->name ?></div>
                    <ul class="clr">
                        <?= $this->render($view_id, [
                            'models' => $articles,
                            'img_size' => $img_size,
                            'img_size_full' => $img_size_full
                        ]) ?>
                    </ul>
                    <?php
                    if ($hasMore) {
                        ?>
                        <button
                            type="button"
                            class="see-more"
                            onclick="loadMore(this.previousElementSibling, this)"
                        >Xem thêm</button>
                        <?php
                    }
                    ?>
                </div>
            </div>

    </div>
</div>

<script>
    var viewId = "<?= $view_id ?>";
    var viewParams = {
        img_size: "<?= $img_size ?>",
        img_size_full: "<?= $img_size_full ?>"
    };
    var queryParams = <?= json_encode($queryParams) ?>;
    var nextPage = <?= $page + 1 ?>;
    function loadMore(container, button) {
        loadMoreArticles(container, button, viewId, viewParams, queryParams, nextPage, function () {
            nextPage++;
        });
    }
</script>
