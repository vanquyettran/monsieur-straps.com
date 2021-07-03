<?php
use frontend\models\Article;
use frontend\models\ArticleCategory;
use frontend\models\Product;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $featured_products Product[]
 * @var $featured_articles Article[]
 */

?>
<div class="container" id="home-featured-products">
    <div class="inner">
        <div class="clr product-thumbnail-list aspect-ratio <?= Yii::$app->params['product_image_ratio_class'] ?>">
            <?= $this->render('//product/_thumbnailList', [
                'models' => $featured_products
            ]) ?>
        </div>
    </div>
</div>

<?php
if (count($featured_articles) > 0) {
    ?>
    <div class="container" id="home-featured-articles">
        <div class="inner">
            <div class="aspect-ratio __3x2 news-thumbnail news-thumbnail--all-equals-on-mobile">
                <div class="title">Blog</div>
                <ul class="clr">
                    <?= $this->render('//article/_thumbnailList', [
                        'models' => $featured_articles,
                        'img_size' => '274x183',
                        'img_size_full' => '381x254'
                    ]) ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
?>
