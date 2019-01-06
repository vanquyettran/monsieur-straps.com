<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\models\ProductOrder;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php require_once 'js.php' ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        /**
         * @var $user \common\models\User
         */
        $user = Yii::$app->user->identity;

        $newOrders = ProductOrder::find()->where(['status' => ProductOrder::STATUS__NEW])->count();

        $menuItems = array_merge(
            $menuItems,
            [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Product', 'items' => [
                    ['label' => 'List', 'url' => ['/product/index']],
                    ['label' => 'Create New', 'url' => ['/product/create']],
                    ['label' => 'Categories', 'url' => ['/product-category/index']],
                    ['label' => 'Attributes', 'url' => ['/product-attribute/index']],
                    ['label' => 'Attribute Groups', 'url' => ['/product-attribute-group/index']],
                    ['label' => 'Discounts', 'url' => ['/product-discount/index']],
                ]],
                ['label' => 'Article', 'items' => [
                    ['label' => 'List', 'url' => ['/article/index']],
                    ['label' => 'Create New', 'url' => ['/article/create']],
                    ['label' => 'Categories', 'url' => ['/article-category/index']],
                ]],
                [
                    'label' => 'Order' . ($newOrders > 0 ? " <span class='label label-danger'>$newOrders</span>" : ''),
                    'url' => ['/product-order/index']
                ],
                ['label' => 'Banner', 'items' => [
                    ['label' => 'List', 'url' => ['/banner/index']],
                    ['label' => 'Create New', 'url' => ['/banner/create']],
                ]],
                ['label' => 'Menu Item', 'items' => [
                    ['label' => 'List', 'url' => ['/menu-item/index']],
                    ['label' => 'Create New', 'url' => ['/menu-item/create']],
                ]],
                ['label' => 'Static Page Info', 'items' => [
                    ['label' => 'List', 'url' => ['/static-page-info/index']],
                    ['label' => 'Create New', 'url' => ['/static-page-info/create']],
                ]],
                ['label' => 'Site Param', 'items' => [
                    ['label' => 'List', 'url' => ['/site-param/index']],
                    ['label' => 'Create New', 'url' => ['/site-param/create']],
                ]],
                ['label' => 'Image', 'items' => [
                    ['label' => 'List', 'url' => ['/image/default/index']],
                    ['label' => 'Create New', 'url' => ['/image/default/create']],
                ]],
                ['label' => 'User', 'items' => [
                    ['label' => 'List', 'url' => ['/user/index']],
                    ['label' => 'Create New', 'url' => ['/user/create']],
                ]],
            ],
            [
                ['label' => $user->username, 'items' => [
                    ['label' => 'Update Profile', 'url' => ['/user/update', 'id' => $user->id]],
                    [
                        'label' => 'Logout',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ]
                ]],
            ]
        );
    }

    echo Nav::widget([
        'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'activateParents' => true,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
