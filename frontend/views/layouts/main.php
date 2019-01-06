<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\Banner;
use common\models\SiteParam;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);

/**
 * @var $seoInfo \frontend\models\SeoInfo
 */
$seoInfo = $this->context->seoInfo;
$seoInfo->registerMetaTags($this);
$seoInfo->registerLinkTags($this);

$this->title = $seoInfo->page_title ? $seoInfo->page_title : Yii::$app->name;

$regCss = function ($cssFile) {
    echo str_replace(
        ['@img'],
        [Yii::getAlias('@web/img')],
        file_get_contents(Yii::getAlias("@webroot/css/$cssFile.css"))
    );
};

/**
 * @var $headerBanners Banner[]
 */
$headerBanners = array_filter($this->context->headerBanners, function (Banner $item) {
    return $item->image;
});

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"
      class="<?= count($headerBanners) > 0 ? 'has-header-banner' : '' ?>"
      data-page="<?= Yii::$app->controller->id ?>/<?= Yii::$app->controller->action->id ?>"
>
<head>
    <title><?= Html::encode($this->title) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <style>
        <?php
        $regCss('shared');
        $regCss('separated');
        $regCss('slider');
        ?>
    </style>
    <?= $this->render('headerJs') ?>
</head>
<body data-sticky-container="global">
<?php $this->beginBody() ?>

<div id="menu-mobile-backdrop"
     onclick="document.querySelector('html').classList.remove('menu-active')">
</div>


<div id="header">
    <?php
    if (count($headerBanners) > 0) {
        ?>

        <div class="header-banner frame aspect-ratio __16x9">
            <div class="slider"
                 data-item-aspect-ratio="1.77777777778"
                 data-autorun-delay="4000"
                 data-slide-time="400"
                 data-slide-timing="ease"
                 data-swipe-timing="ease-out"
                 data-display-navigator="true"
            >
                <?php
                foreach ($headerBanners as $item) {
                    $bannerImage = $item->image;
                    if ($bannerImage) {
                        ?>
                        <div class="image aspect-ratio __16x9">
                            <span>
                                <?= $bannerImage->img() ?>
                            </span>
                            <?php
                                if ($item->caption) {
                                    ?>
                                    <div class="caption-overlay" style="background: <?= $item->filter ? $item->filter : 'transparent' ?>;">
                                        <div class="caption-content">
                                            <?php

                                            $captionListStr = $item->caption;
                                            $captionList = explode('<p>===</p>', $captionListStr);

                                            $captionSmall = '';
                                            $captionMedium = '';
                                            $captionLarge = '';
                                            switch (count($captionList)) {
                                                case 1:
                                                    $captionLarge = trim($captionList[0]);
                                                    $captionMedium = trim($captionList[0]);
                                                    $captionSmall = trim($captionList[0]);
                                                    break;
                                                case 2:
                                                    $captionLarge = trim($captionList[0]);
                                                    $captionMedium = trim($captionList[0]);
                                                    $captionSmall = trim($captionList[1]);
                                                    break;
                                                case 3:
                                                default:
                                                    $captionLarge = trim($captionList[0]);
                                                    $captionMedium = trim($captionList[1]);
                                                    $captionSmall = trim($captionList[2]);
                                            }

                                            ?>
                                            <div class="lg-only">
                                                <?= $captionLarge ?>
                                            </div>
                                            <div class="md-only">
                                                <?= $captionMedium ?>
                                            </div>
                                            <div class="sm-only">
                                                <?= $captionSmall ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="overlay" <?= count($headerBanners) == 0 ? 'data-sticky-in="global" data-sticky-responsive="mobile"' : '' ?>>
        <div class="container">
            <div class="inner">
                <a class="logo" href="<?= Url::home(true) ?>">
                    <?= $this->render('svgLogo') ?>
                </a>

                <button type="button" class="open-search-button" onclick="popupSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <?= $this->render('topNav', ['menu' => $this->context->menu]) ?>
    </div>
</div>

<?= $content ?>

<?= $this->render('footer') ?>
<?= $this->render('fbChatOverlay') ?>
<?= $this->render('footerJs') ?>

<?php echo $this->render('fbSDK') ?>
<?php //echo $this->render('googlePlatform') ?>
<?php //echo $this->render('twitterWidget') ?>
<?php echo $this->render('tracking') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
