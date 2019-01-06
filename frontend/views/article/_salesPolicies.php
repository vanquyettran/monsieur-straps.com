<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/18/2018
 * Time: 4:39 PM
 */

use common\models\ArticleCategory;
use frontend\controllers\ArticleController;

/**
 * @var ArticleCategory[] $categories
 */
$categories = ArticleCategory::find()
    ->where([
        'active' => 1,
        'visible' => 1,
        'type' => ArticleCategory::TYPE_SALES_POLICY,
    ])
    ->orderBy('sort_order ASC')
    ->all();

foreach ($categories as $category) {
    $articles = ArticleController::findModels($category->getAllArticles());
    ?>
    <div class="sales-policies">
        <div class="title"><?= $category->name ?></div>
        <ul class="contents aspect-ratio __1x1 clr">
            <?php
            foreach ($articles as $article) {
                ?><li>
                    <?= $article->viewAnchor(
                        '<div class="image"><span>' . $article->avatarImg('300x300') . '</span></div>'
                        . '<h3 class="name">' . $article->name . '</h3>'
                        . '<div class="desc">' . $article->description . '</div>'
                    ) ?>
                </li><?php
            }
            ?>
        </ul>
    </div>
    <?php
}
?>

<?php
$cols = 3;
$mg_per = 5;
$mg_rem = 5;

switch ($this->context->screenSize) {
    case 'large':
        $cols = 5;
        $mg_per = 10;
        $mg_rem = 10;
        break;
    case 'medium':
        $cols = 3;
        $mg_per = 5;
        $mg_rem = 5;
        break;
    case 'small':
        $cols = 2;
        $mg_per = 2;
        $mg_rem = 2;
        break;
}


?>

<!-- Dynamic styles -->
<style>
    .sales-policies .contents li {
        margin-left: calc(<?= $mg_per ?>% / <?= $cols - 1 ?> + <?= $mg_rem ?>rem / <?= $cols - 1 ?>);
        width: calc(100% / <?= $cols ?> - <?= $mg_per ?>% / <?= $cols ?> - <?= $mg_rem ?>rem / <?= $cols ?>);
    }
    .sales-policies .contents li:nth-child(<?= $cols ?>n + 1) {
        clear: left;
        margin-left: 0;
    }
    .sales-policies .contents li:nth-child(n + <?= $cols + 1 ?>) {
        margin-top: 3rem;
    }
</style>

<!-- Fixed styles -->
<style>
    .sales-policies {
        margin-top: 2.5rem;
    }
    .sales-policies .title {
        text-align: center;
        font-weight: normal;
        font-size: 1.5em;
        text-transform: uppercase;
    }
    .sales-policies .contents {
        margin-top: 1.5rem;
        text-align: center;
    }
    .sales-policies .contents li {
        display: inline-block;
        list-style: none;
        vertical-align: top;
    }
    .sales-policies .contents li a {
        display: block;
    }
    .sales-policies .contents li a .image {
        width: 30%;
        margin: auto;
        background: transparent;
    }
    .sales-policies .contents li a .name {
        margin-top: 1em;
        font-size: 1.1em;
        font-weight: bold;
        text-align: center;
    }
    .sales-policies .contents li a .desc {
        margin-top: 0.5em;
        text-align: center;
        color: #888;
    }
</style>
