<?php
/**
 * @var $this \yii\web\View
 * @var $tag Tag
 * @var $articles \frontend\models\Article[]
 * @var $jsonParams string
 * @var $hasMore boolean
 * @var $page integer
 */
use frontend\models\Tag;
use yii\helpers\Html;
?>
<div class="left">
    <section>
        <div class="heading clr">
            <h2 class="title">
                <span>Tag: <?= Html::encode($tag->name) ?></span>
            </h2>
        </div>
        <div class="body clr">
            <div class="thumbnail-story-list aspect-ratio __3x2">
                <?php
                if (count($articles) > 0) {
                    echo $this->render('//article/_thumbnailList', [ 'models' => $articles ]);
                } else {
                    echo 'Chưa có nội dung cho mục này.';
                }
                ?>
            </div>
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
    </section>
</div>
<div class="right">
    <?= $this->render('//article/_asideFeaturedList') ?>
    <?= $this->render('//article/_asideCategoryBasedList') ?>
</div>


<script>
    var jsonParams = <?= $jsonParams ?>;
    var page = <?= $page + 1 ?>;
    function loadMore(container, button) {
        loadMoreArticles(container, button, '_thumbnailList', jsonParams, page, function () {
            page++;
        });
    }
</script>
