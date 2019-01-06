<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductCustomizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product null|\backend\models\Product */

$this->title = 'Product Customizations';

if ($product) {
    $this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['product/view', 'id' => $product->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-customization-index">

    <?php
    if ($product) {
        ?>
        <h1>
            <a href="<?= Url::to(['product/update', 'id' => $product->id]) ?>"
               ><?= $product->name ?></a>'s Customizations
        </h1>
        <p>
            <?= Html::a('NEW Customization', [
                'product-customization/create',
                'product_id' => $product->id
            ], ['class' => 'btn btn-success']) ?>
            for <a href="<?= Url::to(['product/update', 'id' => $product->id]) ?>"
                   ><?= $product->name ?></a>
        </p>
        <?php
    } else {
        ?>
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a('Create Product Customization', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php
    }
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
//            'details:ntext',
            'price',
            'sort_order',
            //'featured',
            'available',
            //'product_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
